<x-app-layout>

    @section('styles')
        <!-- Custom styles for this page -->
        {{-- <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> --}}

    @endsection

    <!-- Page Heading -->
    @section('header')
        <x-page-header>
            Add New Sale
        </x-page-header>
    @endsection

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between mb-4">
            <h6 class="m-0 font-weight-bold text-primary">New Sale</h6>
        </div>
        <div class="card-body">

            {{-- Flashed msgs  --}}

            @error('name')
                <div class="mb-3 alert alert-danger">{{ 'a7a ' . $message }}</div>
            @enderror

            @if (session()->has('error'))
                <div class="mb-3  alert alert-danger">{{ session()->get('error') }}</div>
            @endif

            @if (session()->has('success'))
                <div class="mb-3 alert alert-success">{{ session()->get('success') }}</div>
            @endif




            <form id="sale-form" action="{{route('sales.store')}}" method="post" enctype="multipart/form-data">
                @csrf

                {{-- general info --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{__('Date')}}</label>
                            <input type="text" name="created_at" class="form-control date" placeholder="Choose date"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{__('Customer')}} *</label>
                            <select required name="customer_id" id="customer_id" class="selectpicker form-control" data-live-search="true" title="Select customer...">
                                <?php
                                    $deposit = [];
                                    $points = [];
                                ?>
                                @foreach($customers as $customer)

                                    {{-- php $deposit[$customer->id] = $customer->deposit - $customer->expense;

                                    $points[$customer->id] = $customer->points; endphp --}}

                                <option value="{{$customer->id}}">{{$customer->name . ' (' . $customer->phone_number . ')'}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{__('Warehouse')}} *</label>
                            <select required name="warehouse_id" id="warehouse_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select warehouse...">
                                @foreach($warehouses as $warehouse)
                                <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- tax & discount --}}
                <div class="row">
                    <div class="col-md-4">
                         <div class="form-group">
                            <label>{{__('Tax Rate')}}</strong> </label>
                            <select class="form-control" id="tax_rate" name="tax_rate">
                                <option value="0">No Tax</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="25">25</option>
                                <option value="30">30</option>
                                {{-- @foreach($lims_tax_list as $tax)
                                <option value="{{$tax->rate}}">{{$tax->name}}</option>
                                @endforeach --}}
                            </select>
                            {{-- <input type="number" name="order_tax" step="5" class="form-control"> --}}
                            <span class="validation-msg"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{__('Order Discount Type')}}</label>
                            <select id="discount_type" name="discount_type" class="form-control">
                              <option value="flat">{{__('Flat')}}</option>
                              <option value="percentage">{{__('Percentage')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{__('Discount Value')}}</label>
                            <input type="text" name="discount_value" class="form-control numkey" id="discount_value">
                            {{-- <input type="hidden" name="order_discount" class="form-control" id="order-discount"> --}}
                        </div>
                    </div>

                    {{-- shipping cost --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>
                                {{__('Shipping Cost')}}
                            </label>
                            <input type="number" id="shipping_cost" name="shipping_cost" class="form-control" step="any"/>
                        </div>
                    </div>

                </div>

                <div class="row mb-5">
                    <div class="col-md-12">
                        <label>{{__('Select Product')}}</label>
                        <div class="search-box input-group">
                            <button type="button" class="btn btn-primary btn-md"><i class="fa fa-barcode"></i></button>
                            <input type="text" id="productcodeSearch" placeholder="Please type product code and select..." class="form-control" />
                        </div>
                    </div>
                </div>

                <div class="row my-3">
                    {{-- order table --}}
                    <div class="col-md-12">
                        <h5>{{__('Order Table')}} *</h5>
                        <div class="table-responsive mt-3">
                            <table id="myTable" class="table table-hover order-list">
                                <thead>
                                    <tr>
                                        <th>{{__('name')}}</th>
                                        <th>{{__('Code')}}</th>
                                        <th>{{__('Quantity')}}</th>
                                        {{-- <th>{{__('Batch No')}}</th>
                                        <th>{{__('Expired Date')}}</th> --}}
                                        <th>{{__('Unit Price')}}</th>
                                        {{-- <th>{{__('Discount')}}</th> --}}
                                        {{-- <th>{{__('Tax')}}</th> --}}
                                        <th>{{__('Subtotal')}}</th>
                                        <th><i class="fa fa-trash"></i></th>
                                    </tr>
                                </thead>
                                <tbody id="first_body">
                                </tbody>
                                <tbody id="last_body">
                                    <th colspan="2">{{__('Total')}}</th>
                                    <th id="total-qty">0</th>
                                    <th></th>
                                    {{-- <th id="total-discount">0.00</th>
                                    <th id="total-tax">0.00</th> --}}
                                    <th id="total_price">0.00</th>
                                    <th></th>
                                </tbody>
                                <tfoot class="tfoot active text-danger">
                                    <th colspan="3"></th>
                                    <th>{{__('Grand Total')}}</th>
                                    <th colspan="2"><span id="grand_total">0</span></th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    {{-- <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="total_qty" placeholder="total_qty" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="total_discount" placeholder="total_discount" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="total_tax" placeholder="total_tax" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="total_price" placeholder="total_price" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="items_count" placeholder="item" />
                                    <input type="hidden" name="order_tax" placeholder="order_tax" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="hidden" name="grand_total" placeholder="grand_total" />
                                    /* <input type="hidden" name="used_points" />
                                    <input type="hidden" name="pos" placeholder="pos" value="0" />
                                    <input type="hidden" name="coupon_active" value="0" /> */
                                </div>
                            </div>
                        </div>
                    </div> --}}

                </div>





                    <div class="col-12" id="payment">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('Payment Status')}} *</label>
                                    <select name="payment_status" class="form-control">
                                        <option value="0">{{__('')}}</option>
                                        <option value="1">{{__('Due')}}</option>
                                        <option value="2">{{__('Partial')}}</option>
                                        <option value="3">{{__('Paid')}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('Recieved Amount')}} *</label>
                                    <input type="number" name="recieved_amount" class="form-control" id="recieved_amount" step="any" disabled />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('Paying Amount')}} *</label>
                                    <input type="number" name="paid_amount" class="form-control" id="paid_amount" step="any" disabled/>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('Change')}}</label>
                                    <p id="change" class="ml-2" disabled>0.00</p>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="card-element" class="form-control">
                                    </div>
                                    <div class="card-errors" role="alert"></div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- notes --}}
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label>{{__('Sale Note')}}</label>
                                <textarea rows="3" class="form-control" name="note"></textarea>
                            </div>
                            <div class="col-12">
                                <input type="submit" value="{{__('Submit')}}" id="submit-btn" class="btn btn-primary">
                            </div>
                        </div>
                </div>

            </form>

            {{-- <div class="container-fluid">
                <table class="table table-bordered table-condensed totals">
                    <td><strong>{{__('Items')}}</strong>
                        <span class="pull-right" id="item">0.00</span>
                    </td>
                    <td><strong>{{__('Total')}}</strong>
                        <span class="pull-right" id="subtotal">0.00</span>
                    </td>
                    <td><strong>{{__('Order Tax')}}</strong>
                        <span class="pull-right" id="order_tax">0.00</span>
                    </td>
                    <td><strong>{{__('Order Discount')}}</strong>
                        <span class="pull-right" id="order_discount">0.00</span>
                    </td>
                    <td><strong>{{__('Shipping Cost')}}</strong>
                        <span class="pull-right" id="shipping_cost">0.00</span>
                    </td>
                    <td><strong>{{__('grand total')}}</strong>
                        <span class="pull-right" id="grand_total">0.00</span>
                    </td>
                </table>
            </div> --}}


        </div>
    </div>

    @section('modals')
        <div id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal_header" class="modal-title"></h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row modal-element">
                                <div class="col-md-4 form-group">
                                    <label>{{__('Quantity')}}</label>
                                    <input type="number" step="any" name="edit_qty" class="form-control numkey">
                                </div>
                                {{-- <div class="col-md-4 form-group">
                                    <label>{{__('Unit Discount')}}</label>
                                    <input type="number" name="edit_discount" class="form-control numkey">
                                </div> --}}
                                <div class="col-md-4 form-group">
                                    <label>{{__('Unit Price')}}</label>
                                    <input type="number" name="edit_unit_price" class="form-control numkey" step="any">
                                </div>
                                {{-- php
                                    $tax_name_all[] = 'No Tax';
                                    $tax_rate_all[] = 0;
                                    foreach($lims_tax_list as $tax) {
                                        $tax_name_all[] = $tax->name;
                                        $tax_rate_all[] = $tax->rate;
                                    }
                                ?> --}}
                                {{-- <div class="col-md-4 form-group">
                                    <label>{{__('Tax Rate')}}</label>
                                    <select name="edit_tax_rate" class="form-control selectpicker">
                                        @foreach($tax_name_all as $key => $name)
                                        <option value="{{$key}}">{{$name}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div id="edit_unit" class="col-md-4 form-group">
                                    <label>{{__('Product Unit')}}</label>
                                    <select name="edit_unit" class="form-control selectpicker">
                                    </select>
                                </div>
                            </div>
                            <button type="button" name="update_btn" class="btn btn-primary">{{__('update')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')

    <script>

    </script>
    {{-- // validation for customer and warehouse inputs before searching --}}
    <script>

        var productcodeSearch = $('#productcodeSearch');

        $('#productcodeSearch').on('input', function(){
            customer_id = $('#customer_id').val();
            warehouse_id = $('#warehouse_id').val();
            var temp_data = $('#productcodeSearch').val();
            if(!customer_id){
                $('#productcodeSearch').val('');
                alert('Please select Customer!');
            }
            else if(!warehouse_id){
                $('#productcodeSearch').val('');
                alert('Please select Warehouse!');
            }

        });
    </script>

    {{-- on #warehouse change listener --}}
    <script>
        // var productcodeSearch = $('#productcodeSearch');

        // array data depend on warehouse
        var product_array = [];
        var product_code = [];
        var product_qty = [];
        // var product_name = [];
        var wh_id = '';

        $('select[name="warehouse_id"]').on('change', function() {

            // get id of warehouse
            var id = $(this).val();

            emptyAll(id)

            var url = "{{route('sales.getproduct',':id')}}";
            url = url.replace(':id',id);

            // send get request
            $.get(url, function(data) {
                console.log(data)
                $.each(data, function(index,product) {
                        product_array.push(product.code + ' (' + product.name + ')');
                        product_code.push(product.code);
                        product_qty.push(product.qty);
                });
            });
        });

        // empty all inputs and arrays
        function emptyAll(id)
        {
            // if wh changed back all to default
            if(wh_id != '' && wh_id != id)
            {
                // console.log('a7a')
                $('#first_body').empty()
                $('#total-qty').text(0)
                $('#total_price').text(0)
                $('#grand_total').text(0)
                products_added_before = []
                // $('shipping_cost').val('')
                // $('#payment_status option[value="0"]').attr('selected', 'selected');
                $('select[name="payment_status"] option[value="0"]').attr('selected', 'selected');
                $('#recieved_amount').val('')
                $('#paid_amount').val('')
                $('#change').text('')
            }
            wh_id = id;
        }

        // autocomplete widget for auto type searching
        productcodeSearch.autocomplete({
            source: function(request, response) {
                var matcher = new RegExp(".?" + $.ui.autocomplete.escapeRegex(request.term), "i");
                response($.grep(product_array, function(item) {
                    return matcher.test(item);
                }));
            },
            response: function(event, ui) {
                console.log(ui)

                if (ui.content.length == 1) {
                    // console.log('when 1 : ' + ui);
                    let data = ui.content[0].value;
                    productcodeSearch.val('')
                    // ui.item.value = ''
                    $(this).autocomplete( "close" );
                    // console.log('444444444')
                    productSearch(data);
                }
                else if(ui.content.length == 0 && $('#productcodeSearch').val().length == 13) {
                console.log('55555555')
                productSearch($('#productcodeSearch').val()+'|'+1);
                }
            },
            select: function(event, ui) {
                // console.log(ui)
                var data = ui.item.value;
                ui.item.value = ''
                // productcodeSearch.val('');
                $(this).autocomplete( "close" );
                console.log('6666666666')
                productSearch(data);
            }
        });


        $("#myTable").on('change', '.qty', changeQty);
        $("#myTable").on('input', '.qty', changeQty);
        function changeQty(){

            let tr = $(this).closest('tr')[0];
            let unit_price = tr.querySelector('.price').value;
            let qty = $(this).val();
            let prodCode = $(this).parent().prev().text()
            if($(this).val() < 0 && $(this).val() != '') {
                // $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(1);
                $(this).val(1);
                tr.querySelector('.sub_total').value = unit_price * Math.abs(qty);
                calcGrandTotal();
                alert("{{__('Quantity cannot be less than 0')}}");
                return
            }
            // console.log(tr);
            // $(this).parent().siblings()[3].firstElementChild.value = unit_price * qty;
            let checkQty = getProductActualQty(prodCode,qty,tr);
            // console.log(actualQty);
            if(checkQty){
                return;
            }
            tr.querySelector('.sub_total').value = unit_price * qty;
            calcGrandTotal();
        }


        var grand_total = 0;
        var total_price = 0;
        // var shipping_cost = 0;

        function calcGrandTotal()
        {
            let sub_totals = $('.sub_total');
            grand_total = 0;
            total_price = 0;
            // console.log(sub_totals[0].value)
            for(let i=0; i<sub_totals.length; i++){
                total_price += parseInt(sub_totals[i].value);
                // grand_total += parseInt(sub_totals[i].value);
            }

            let discount = calcDiscount();
            let shippingCost = calcShipping();
            let tax = calcTax();

            grand_total = parseInt(total_price) - parseInt(discount) + parseInt(shippingCost) + parseInt(tax);


            // $('#grand_total')[0].text(grand_total);

            document.querySelector('#total_price').textContent = total_price;
            document.querySelector('#grand_total').textContent = grand_total;
        }

        function calcDiscount()
        {
            let discount = $('#discount_value')[0].value;
            if(!discount){
                return 0;
            }
            // console.log(discount);

            if($('#discount_type')[0].value == 'percentage'){
                discount = total_price * (discount/100);
            }
            return discount;
        }

        function calcShipping()
        {
            let shipping = $('#shipping_cost')[0].value;
            // console.log('shipping:' + shipping)
            if(!shipping){
                return 0;
            }
            return parseFloat(shipping);
        }
        function calcTax()
        {
            let discount = calcDiscount();
            let shipping = calcShipping();

            let tax_rate = $('#tax_rate')[0].value;
            let tax_value = (total_price - discount + shipping) * (parseFloat(tax_rate)/100) ;

            return tax_value;
        }

        function getProductActualQty(prod_code,qty,tr)
        {
            // console.log(prod_code+' ' + qty + ' ')
            // console.log(tr)
            // console.log(product_code.length)
            // return;
            for(i=0; i<product_code.length; i++){
                // console.log(product_code.length);
                if(product_code[i] == prod_code)
                {
                    // console.log(prod_code);
                    if(product_qty[i] < qty){
                        // console.log('a7a yasta!');

                        let unit_price = tr.querySelector('.price').value;
                        // console.log(unit_price);
                        // return
                        alert("{{__('Quantity exceeds stock quantity')}}");
                        // $(this).val(product_qty[i]);
                        tr.querySelector('.qty').value = product_qty[i];
                        tr.querySelector('.sub_total').value = unit_price * product_qty[i];
                        calcGrandTotal();
                        return true;
                    }
                    return false
                }
            }


        }


        //Delete product
        $("table.order-list tbody").on("click", ".ibtnDel", function(event) {
            rowindex = $(this).closest('tr').index();
            product_price.splice(rowindex, 1);
            product_discount.splice(rowindex, 1);
            tax_rate.splice(rowindex, 1);
            tax_name.splice(rowindex, 1);
            tax_method.splice(rowindex, 1);
            unit_name.splice(rowindex, 1);
            unit_operator.splice(rowindex, 1);
            unit_operation_value.splice(rowindex, 1);
            $(this).closest("tr").remove();
            calculateTotal();
        });

        //Edit product
        $("table.order-list").on("click", ".edit-product", function() {
            rowindex = $(this).closest('tr').index();
            edit();
        });
    </script>

{{-- Payments --}}
    <script>
        $('[name="payment_status"]').on('change', function(){
            console.log($('#recieved_amount'));
            let payment_status = $(this).val();
            if(payment_status == 2){
                console.log(true)
                $('#recieved_amount').attr('disabled',false)
                $('#paid_amount').attr('disabled',false)
            }
            else if(payment_status == 3){
                console.log(false)
                let grand_total = parseInt($('#grand_total').text());
                // $('#recieved_amount').val('');
                $('#paid_amount').val(grand_total);
                $('#recieved_amount').val(grand_total);
                $('#recieved_amount').attr('disabled',false);
                $('#paid_amount').attr('disabled',true);
            }else{
                $('#recieved_amount').attr('disabled',true)
                $('#paid_amount').attr('disabled',true)
                $('#recieved_amount').val('')
                $('#paid_amount').val('')
            }
        })
        // validation for paid and recieved amounts
        $('input[name="paid_amount"]').on("input", function() {
            if( $(this).val() > parseInt($('input[name="recieved_amount"]').val()) ) {
                alert('Paying amount cannot be bigger than recieved amount');
                $(this).val('');
            }
            else if( $(this).val() > parseInt($('#grand_total').text()) ){
                alert('Paying amount cannot be bigger than grand total');
                $(this).val('');
            }

            // $("#change").text( parseInt($("#recieved_amount").val() - $(this).val()).toFixed(2) );
            // var id = $('select[name="paid_by_id"]').val();
            // if(id == 2){
            //     var balance = gift_card_amount[$("#gift_card_id").val()] - gift_card_expense[$("#gift_card_id").val()];
            //     if($(this).val() > balance)
            //         alert('Amount exceeds card balance! Gift Card balance: '+ balance);
            // }
            // else if(id == 6){
            //     if( $('input[name="paid_amount"]').val() > deposit[$('#customer_id').val()] )
            //         alert('Amount exceeds customer deposit! Customer deposit : '+ deposit[$('#customer_id').val()]);
            // }
        });

        $('input[name="recieved_amount"]').on("input", function() {
            $("#change").text( parseInt( $(this).val() - $("#paid_amount").val()).toFixed(2));
        });
    </script>


    <script>
        var products_added_before = [];

        function productSearch(data) {

            var product_info = data.split(" ");
            var code = product_info[0];
            var pre_qty = 0;

            $(".product-code").each(function(i) {
                if ($(this).val() == code) {
                    rowindex = i;
                    pre_qty = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val();
                    console.log(pre_qty)
                }
            });

            data += '?'+$('#customer_id').val()+'?'+(parseInt(pre_qty) + 1);
            $.ajax({
                type: 'GET',
                url: "{{route('sales.productSearch')}}",
                data: {
                    data: data
                },
                success: function (product){
                    // console.log(product);
                    // console.log(products_added_before);

                    if(products_added_before.length > 0){
                        for(let i = 0; i <= products_added_before.length; i++){
                            if(products_added_before[i] == product.id){

                                let parentTr = $(`[data-id="${product.id}"`).parent()[0];
                                let qty = parseFloat(parentTr.querySelector('.qty').value) + 1  ;
                                let checkQty = getProductActualQty(product.code,qty,parentTr);
                                // console.log(checkQty);
                                if(checkQty){
                                    return;
                                }

                                parentTr.querySelector('.qty').value = qty;
                                parentTr.querySelector('.sub_total').value = qty * parentTr.querySelector('.price').value;
                                calcGrandTotal();
                                // sub_total[]
                                // console.log(parentTr);
                                return;
                                // $.data('id')
                            }
                        }
                    }
                    products_added_before.push(product.id);
                    console.log(product);
                    // return
                    var newRow = $("<tr>");
                        // console.log(newRow)
                    var cols = '';
                    // pos = product_code.indexOf(data[1]);
                    // temp_unit_name = (data[6]).split(',');
                    cols += `<td style="vertical-align:center" ><div class="input-group"><input class='mr-3 px-3 form-control' type="text" value='${product.name}' disabled><button type="button" class="btn btn-link float-right" data-toggle="modal" data-target="#editModal"> <i class="fa fa-edit"></i></button></div></td>`;
                    // cols += ``;
                    cols += `<input name="product_ids[]" type="hidden" data-id="${product.id}" value='${product.id}'>`;
                    cols += `<td>${product.code}</td>`;
                    // cols += `<td> ${product.code}</td>`;
                    cols += `<td><input type="number" class="form-control qty" name="qty[]" value="${product.qty}" step="any" required/></td>`;
                    cols += `<td><input type="number" step='any' class="form-control price" name="price[]" value="${product.price}"/></td>`;
                    // cols += `<td><input type="number" step='any' class="form-control" name="price[]" value="${product.price}"/></td>`;
                    cols += `<td><input type="number" step='any' class="form-control sub_total" name="sub_total[]" value="${product.price}"/></td>`;
                    newRow.append(cols);
                    $("table.order-list #first_body").prepend(newRow);
                    calcGrandTotal();

                }
                // success: function(data) {
                //     // return console.log(data);
                //     var flag = 1;
                //     if (pre_qty > 0) {
                //         var qty = data[14];
                //         $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(qty);
                //         // pos = product_code.indexOf(data[1]);
                //         if(!data[11] && product_warehouse_price[pos]) {
                //             product_price[rowindex] = parseInt(product_warehouse_price[pos] * currency['exchange_rate']) + parseInt(product_warehouse_price[pos] * currency['exchange_rate'] * customer_group_rate);
                //         }
                //         else{
                //             product_price[rowindex] = parseInt(data[2] * currency['exchange_rate']) + parseInt(data[2] * currency['exchange_rate'] * customer_group_rate);
                //         }
                //         flag = 0;
                //         checkQuantity(String(qty), true);
                //         flag = 0;
                //     }
                //     $("input[name='product_code_name']").val('');
                //     if(flag){
                //         var newRow = $("<tr>");
                //         var cols = '';
                //         // pos = product_code.indexOf(data[1]);
                //         // temp_unit_name = (data[6]).split(',');
                //         cols += '<td>' + data[0] + '<button type="button" class="edit-product btn btn-link" data-toggle="modal" data-target="#editModal"> <i class="dripicons-document-edit"></i></button></td>';
                //         cols += '<td>' + data[1] + '</td>';
                //         cols += '<td><input type="number" class="form-control qty" name="qty[]" value="'+data[14]+'" step="any" required/></td>';
                //         if(data[12]) {
                //             cols += '<td><input type="text" class="form-control batch-no" value="'+batch_no[pos]+'" required/> <input type="hidden" class="product-batch-id" name="product_batch_id[]" value="'+product_batch_id[pos]+'"/> </td>';
                //             cols += '<td class="expired-date">'+expired_date[pos]+'</td>';
                //         }
                //         else {
                //             cols += '<td><input type="text" class="form-control batch-no" disabled/> <input type="hidden" class="product-batch-id" name="product_batch_id[]"/> </td>';
                //             cols += '<td class="expired-date">N/A</td>';
                //         }

                //         cols += '<td class="net_unit_price"></td>';
                //         cols += '<td class="discount">0.00</td>';
                //         cols += '<td class="tax"></td>';
                //         cols += '<td class="sub-total"></td>';
                //         cols += '<td><button type="button" class="ibtnDel btn btn-md btn-danger">{{__("delete")}}</button></td>';
                //         cols += '<input type="hidden" class="product-code" name="product_code[]" value="' + data[1] + '"/>';
                //         cols += '<input type="hidden" class="product-id" name="product_id[]" value="' + data[9] + '"/>';
                //         cols += '<input type="hidden" class="sale-unit" name="sale_unit[]" value="' + temp_unit_name[0] + '"/>';
                //         cols += '<input type="hidden" class="net_unit_price" name="net_unit_price[]" />';
                //         cols += '<input type="hidden" class="discount-value" name="discount[]" />';
                //         cols += '<input type="hidden" class="tax-rate" name="tax_rate[]" value="' + data[3] + '"/>';
                //         cols += '<input type="hidden" class="tax-value" name="tax[]" />';
                //         cols += '<input type="hidden" class="subtotal-value" name="subtotal[]" />';
                //         cols += '<input type="hidden" class="imei-number" name="imei_number[]" />';

                //         newRow.append(cols);
                //         $("table.order-list tbody").prepend(newRow);
                //         rowindex = newRow.index();

                //         if(!data[11] && product_warehouse_price[pos]) {
                //             product_price.splice(rowindex, 0, parseInt(product_warehouse_price[pos] * currency['exchange_rate']) + parseInt(product_warehouse_price[pos] * currency['exchange_rate'] * customer_group_rate));
                //         }
                //         else {
                //             product_price.splice(rowindex, 0, parseInt(data[2] * currency['exchange_rate']) + parseInt(data[2] * currency['exchange_rate'] * customer_group_rate));
                //         }
                //         product_discount.splice(rowindex, 0, '0.00');
                //         tax_rate.splice(rowindex, 0, parseInt(data[3]));
                //         tax_name.splice(rowindex, 0, data[4]);
                //         tax_method.splice(rowindex, 0, data[5]);
                //         unit_name.splice(rowindex, 0, data[6]);
                //         unit_operator.splice(rowindex, 0, data[7]);
                //         unit_operation_value.splice(rowindex, 0, data[8]);
                //         is_imei.splice(rowindex, 0, data[13]);
                //         checkQuantity(data[14], true);
                //         if(data[13]) {
                //             $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.edit-product').click();
                //         }
                //     }
                // }
            });
        }
    </script>
    {{-- <script>
        $('#submit-btn').on('click',()=>{

        })
    </script> --}}
    @endsection
</x-app-layout>
