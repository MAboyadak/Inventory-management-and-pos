<x-app-layout>

    @section('styles')
        <!-- Custom styles for this page -->
        {{-- <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> --}}

    @endsection

    <!-- Page Heading -->
    @section('header')
        <x-page-header>
            Print Barcode
        </x-page-header>
    @endsection

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between mb-4">
            <h6 class="m-0 font-weight-bold text-primary">Print Barcode</h6>
        </div>
        <div class="card-body">

            {{-- Flashed msgs  --}}

            @error('name')
                <div class="mb-3 alert alert-danger">{{ 'validation :: ' . $message }}</div>
            @enderror

            @if (session()->has('error'))
                <div class="mb-3  alert alert-danger">{{ session()->get('error') }}</div>
            @endif

            @if (session()->has('success'))
                <div class="mb-3 alert alert-success">{{ session()->get('success') }}</div>
            @endif



            <p class="italic"><small>{{__('The field labels marked with * are required input fields')}}.</small></p>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <label>{{__('Add Product')}} *</label>
                            <div class="search-box input-group">

                                <button type="button" class="btn btn-secondary btn-lg"><i class="fa fa-barcode"></i></button>
                                <input type="text" name="product_code_name" id="lims_productcodeSearch" placeholder="Please type product code and select..." class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="table-responsive mt-3">
                                <table id="myTable" class="table table-hover order-list">
                                    <thead>
                                        <tr>
                                            <th>{{__('name')}}</th>
                                            <th>{{__('Code')}}</th>
                                            <th>{{__('Quantity')}}</th>
                                            <th><i class="dripicons-trash"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <strong>{{__('Print')}}: </strong>&nbsp;
                        <strong><input type="checkbox" name="name" checked /> {{__('Product Name')}}</strong>&nbsp;
                        <strong><input type="checkbox" name="price" checked/> {{__('Price')}}</strong>&nbsp;
                        {{-- <strong><input type="checkbox" name="promo_price"/> {{__('Promotional Price')}}</strong> --}}
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label><strong>Paper Size *</strong></label>
                            <select class="form-control" name="paper_size" required id="paper-size">
                                <option value="0">Select paper size...</option>
                                <option value="36">36 mm (1.4 inch)</option>
                                <option value="24">24 mm (0.94 inch)</option>
                                <option value="18">18 mm (0.7 inch)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <input type="submit" value="{{__('Submit')}}" class="btn btn-primary" id="submit-button">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('modals')
        <div id="print-barcode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 id="modal_header" class="modal-title">{{__('Barcode')}}</h5>&nbsp;&nbsp;
                    <button id="print-btn" type="button" class="btn btn-secondary btn-sm"><i class="dripicons-print"></i> {{__('Print')}}</button>
                    <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div id="label-content">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
        <script>
            @php $productArray = []; @endphp

            @foreach($products as $product)
                <?php
                    $productArray[] = htmlspecialchars($product->code . ' (' . $product->name . ')');
                ?>
            @endforeach

            // @foreach($productArray as $product)@php echo '"' . $product . '",' ; @endphp@endforeach

            var lims_product_code = [

                <?php echo "'".implode("','", $productArray)."'";?>
            ];


            // console.log(lims_product_code)

            var lims_productcodeSearch = $('#lims_productcodeSearch');

            // autocomplete widget
            lims_productcodeSearch.autocomplete({
                // autoFocus: true,
                // delay:500,
                source: function(request, response) {
                    // console.log(request);
                    var matcher = new RegExp(".?" + $.ui.autocomplete.escapeRegex(request.term), "i");
                    response($.grep(lims_product_code, function(item) {
                        return matcher.test(item);
                    }));
                },
                // on select item from list
                select: function(event, ui) {
                    // console.log(event)
                    // console.log(ui.item)
                    var data = ui.item.value;

                    $.ajax({
                        type: 'GET',
                        url: "{{route('products.search')}}",
                        data: {
                            data: data
                        },
                        success: function(data) {
                            // console.log(data);
                            var flag = 1;
                            $(".product-code").each(function() {
                                if ($(this).text() == data[1]) {
                                    alert('duplicate input is not allowed!')
                                    flag = 0;
                                }
                            });
                            $("input[name='product_code_name']").val('');
                            if(flag){
                                var newRow = $('<tr data-imagedata="'+data[3]+'" data-price="'+data[2]+'">');
                                var cols = '';
                                cols += '<td>' + data[0] + '</td>';
                                cols += '<td class="product-code">' + data[1] + '</td>';
                                cols += '<td><input type="number" class="form-control qty" name="qty[]" value="1" /></td>';
                                cols += '<td><button type="button" class="ibtnDel btn btn-md btn-danger">Delete</button></td>';

                                newRow.append(cols);
                                $("table.order-list tbody").append(newRow);
                            }
                        }
                    });
                }
            });


            //Delete product
            $("table.order-list tbody").on("click", ".ibtnDel", function(event) {
                rowindex = $(this).closest('tr').index();
                $(this).closest("tr").remove();
            });



            // on submit click
            $("#submit-button").on("click", function(event) {
                paper_size = ($("#paper-size").val());
                if(paper_size != "0") {
                    var product_name = [];
                    var code = [];
                    var price = [];
                    // var promo_price = [];
                    var qty = [];
                    var barcode_image = [];
                    // var currency = [];
                    // var currency_position = [];
                    var rowsNumber = $('table.order-list tbody tr:last').index();
                    for(i = 0; i <= rowsNumber; i++){
                        product_name.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').find('td:nth-child(1)').text());
                        code.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').find('td:nth-child(2)').text());
                        price.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').data('price'));
                        // promo_price.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').data('promo-price'));
                        // currency.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').data('currency'));
                        // currency_position.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').data('currency-position'));
                        qty.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').find('.qty').val());
                        barcode_image.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').data('imagedata'));
                    }
                    // var htmltext = '<table class="barcodelist" style="width:378px;">';
                    var htmltext = '<table class="barcodelist" style="width:378px;" cellpadding="5px" cellspacing="10px">';
                    $.each(qty, function(index){
                        i = 0;
                        while(i < qty[index]) {
                            if(i % 2 == 0) // because each row has only 2 <td> elements
                                htmltext +='<tr>';

                                // barcode product label
                            // 36mm
                            if(paper_size == 36)
                                htmltext +='<td style="width:164px;height:88%;padding-top:7px;vertical-align:middle;text-align:center">';
                            //24mm
                            else if(paper_size == 24)
                                htmltext +='<td style="width:164px;height:100%;font-size:12px;text-align:center">';
                            //18mm
                            else if(paper_size == 18)
                                htmltext +='<td style="width:164px;height:100%;font-size:10px;text-align:center">';

                            if($('input[name="name"]').is(":checked"))
                                htmltext += product_name[index] + '<br>';

                                // end of barcode product label

                                // C128 code 'img'
                            if(paper_size == 18)
                                htmltext += '<img style="max-width:150px;height:100%;max-height:12px;margin:auto" src="data:image/png;base64,'+barcode_image[index]+'" alt="barcode" /><br>';
                            else
                                htmltext += '<img style="max-width:150px;height:100%;max-height:20px;margin:auto" src="data:image/png;base64,'+barcode_image[index]+'" alt="barcode" /><br>';

                            htmltext += code[index] + '<br>';
                                // end of C128 code

                                // the real code
                            if($('input[name="code"]').is(":checked"))
                                htmltext += '<strong>'+code[index]+'</strong><br>';
                                // end

                                // demo price
                            // if($('input[name="promo_price"]').is(":checked")) {
                            //     if(currency_position[index] == 'prefix')
                            //         htmltext += 'Price: '+currency[index]+'<span style="text-decoration: line-through;"> '+price[index]+'</span> '+promo_price[index]+'<br>';
                            //     else
                            //         htmltext += 'Price: <span style="text-decoration: line-through;">'+price[index]+'</span> '+promo_price[index]+' '+currency[index]+'<br>';
                            // }

                                // price
                            if($('input[name="price"]').is(":checked")) {
                                // if(currency_position[index] == 'prefix')
                                    htmltext += 'Price: '+ price[index];
                                // else
                                    // htmltext += 'Price: '+price[index]+' '+currency[index];
                            }
                            htmltext +='</td>';
                                // end of td

                                // end or row only if i%2 != 0 which means only two tds in tr
                            if(i % 2 != 0)
                                htmltext +='</tr>';
                            i++;
                        }
                    });
                    htmltext += '</table>';

                    $('#label-content').html(htmltext);
                    $('#print-barcode').modal('show');
                }
                else
                    alert('Please select paper size');
            });

            // $("#print-btn").on("click", function() {
            //     var divToPrint=document.getElementById('print-barcode');
            //     var newWin=window.open('','Print-Window');
            //     newWin.document.open();
            //     newWin.document.write('<style type="text/css">@media print { #modal_header { display: none } #print-btn { display: none } #close-btn { display: none } } table.barcodelist { page-break-inside:auto } table.barcodelist tr { page-break-inside:avoid; page-break-after:auto }</style><body onload="window.print()">'+divToPrint.innerHTML.trim()+'</body>');
            //     newWin.document.close();
            //     setTimeout(function(){newWin.close();},10);
            // });

            $("#print-btn").on("click", function(){
                var divToPrint=document.getElementById('print-barcode');
                // console.log(divToPrint);
                var newWin=window.open('','Print-Window');
                // console.log(newWin);
                newWin.document.open();
                // console.log(newWin.document);
                newWin.document.write('<style type="text/css">@media print { #modal_header { display: none } #print-btn { display: none } #close-btn { display: none } } table.barcodelist { page-break-inside:auto } table.barcodelist tr { page-break-inside:avoid; page-break-after:auto } </style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
                // console.log(newWin.document);
                newWin.document.close();
                setTimeout( () => {newWin.close();},10);
            });
        </script>
    @endsection
</x-app-layout>
