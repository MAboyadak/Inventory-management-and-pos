<x-app-layout>

    @section('styles')
        <!-- Custom styles for this page -->
        {{-- <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> --}}

    @endsection

    <!-- Page Heading -->
    @section('header')
        <x-page-header>
            Add New Product
        </x-page-header>
    @endsection

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between mb-4">
            <h6 class="m-0 font-weight-bold text-primary">New Product</h6>
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




            <form id="product-form" action="{{route('products.update',$product->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{__('Product Name')}} *</strong> </label>
                            <input  id="name" type="text" value="{{$product->name}}" name="name" class="form-control" required>
                            <span class="validation-msg" id="name-error"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{__('Category')}} *</strong> </label>
                            <div class="input-group">
                              <select value="{{$product->category_id}}" name="category_id" required class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select Category...">
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                              </select>
                          </div>
                          <span class="validation-msg"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{__('Product Code')}} *</strong> </label>
                            <div class="input-group">
                                <input type="text" name="code" value="{{$product->code}}" class="form-control" id="code" required>
                                <div class="input-group-append">
                                    <button id="genbutton" type="button" class="btn btn-sm btn-secondary" title="{{__('Generate')}}"><i class="fa fa-random"></i></button>
                                </div>
                            </div>
                            <span class="validation-msg" id="code-error"></span>
                        </div>
                    </div>

                    <div id="unit" class="col-md-4">
                        <div class="form-group">
                           <label>{{__('Product Unit')}} *</strong> </label>
                           <select class="form-control" value="{{$product->unit_id}}" name="unit_id" id="">
                            @foreach ($units as $unit)
                                <option value="{{$unit->id}}">{{$unit->name}}</option>
                            @endforeach
                           </select>
                           {{-- <input type="text" name="unit_id" required class="form-control"> --}}
                           <span class="validation-msg"></span>
                       </div>
                   </div>

                    <div id="cost" class="col-md-4">
                         <div class="form-group">
                            <label>{{__('Product Cost')}} *</strong> </label>
                            <input type="number" value="{{$product->cost}}" name="cost" required class="form-control" step="any">
                            <span class="validation-msg"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{__('Product Price')}} *</strong> </label>
                            <input type="number" value="{{$product->price}}" name="price" required class="form-control" step="any">
                            <span class="validation-msg"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{__('Alert Quantity')}}</strong> </label>
                            {{-- <div id="imageUpload" class="dropzone"></div> --}}
                            <input type="text" value="{{$product->alert_quantity}}" name="alert_quantity" class="form-control">
                            <span class="validation-msg" id="image-error"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{__('Product Image')}}</strong> </label> <i class="fa fa-question" data-toggle="tooltip" title="{{__('You can upload multiple image. Only .jpeg, .jpg, .png, .gif file can be uploaded. First image will be base image.')}}"></i>
                            {{-- <div id="imageUpload" class="dropzone"></div> --}}
                            <input type="file" name="image" class="form-control">
                            <span class="validation-msg" id="image-error"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-12">
                                <table class="table text-center bg-secondary text-white">
                                    <thead>
                                        <tr style="back">
                                            <th class="col-md-4">{{__('Warehouse')}}</th>
                                            <th class="col-md-4">{{__('Product Quantity')}}</th>
                                            <th class="col-md-4">{{__('Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="first_row">
                                            <td class="col-md-4" >
                                                <select id="warehouse_id" class="form-control">
                                                    <option value="" selected></option>
                                                    @foreach ($warehouses as $wh)
                                                        <option data-val="{{$wh->name}}" value="{{$wh->id}}">{{$wh->name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="col-md-4">
                                                <input id="quantity" placeholder="{{__('Enter quantity..')}}" type="number" step="1" class="form-control">
                                            </td>
                                            <td class="col-md-4 text-center">
                                                <button type="button" id="addQty" style="width:50%;margin:auto" class="btn btn-primary btn-block btn-default">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @foreach ($warehousesProducts as $wh_pr)
                                            <tr id="{{$wh_pr->id}}" class="text-center" >
                                                <td class="col-md-4">
                                                    <input class="form-control" value="{{$wh_pr->warehouse->name}}" disabled>
                                                    <input type="hidden" name="warehouse_ids[]" value="{{$wh_pr->warehouse->id}}">
                                                </td>
                                                <td class="col-md-4">
                                                    <input type="text" class="form-control" value="{{$wh_pr->qty}}" disabled>
                                                    <input type="hidden" class="form-control" name="qtys[]" value="{{$wh_pr->qty}}">
                                                </td>
                                                <td class="col-md-4"><button type="button" onclick="remove_tr(event,{{$wh_pr->warehouse->id}})" class="btn btn-danger btn-block">X</button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{__('Product Details')}}</label>
                            <textarea name="details" class="form-control" rows="3">{{$product->details ?? ''}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" value="{{__('Submit')}}" id="submit-btn" class="btn btn-primary">
                </div>
            </form>



        </div>
    </div>

    @section('modals')

    @endsection

    @section('scripts')

        <script>
            // warehouses that has been filled with qtys
            var arr_of_warehouses_used_before = [@foreach($warehousesProducts as $wh_pr) {{$wh_pr->warehouse_id}}, @endforeach];
            // console.log(arr_of_warehouses_used_before);

            $('#warehouse_id').on('change',beforeAddWh);


            $('#addQty').on('click',function(e){

                if(beforeAddWh(e,true) == false){
                    // console.log('a7ten');
                    return;
                }

                e.preventDefault();

                //get values
                var warehouseId = $('#warehouse_id').val();
                var qty = $('#quantity').val();
                console.log(qty);
                var warehouseVal = $('#warehouse_id').children('option:selected').attr('data-val');

                // <tr> that will be added
                        // <select  value="${warehouseId}" class="form-control" name="warehouse_ids[]" disabled>
                        //      <option>${warehouseVal}</option>
                        // </select>
                var tr = `<tr id="${warehouseId}" class="text-center" >
                            <td class="col-md-4">
                                <input class="form-control" value="${warehouseVal}" disabled>
                                <input type="hidden" name="warehouse_ids[]" value="${warehouseId}">
                            </td>
                            <td class="col-md-4">
                                <input type="text" class="form-control" value="${qty}" disabled>
                                <input type="hidden" class="form-control" name="qtys[]" value="${qty}">
                            </td>
                            <td class="col-md-4"><button type="button" onclick="remove_tr(event,${warehouseId})" class="btn btn-danger btn-block">X</button></td>
                        </tr>`;
                $('#first_row').after(tr);

                arr_of_warehouses_used_before.push(warehouseId) // push the warehouse_id to the array to check next request if it's added to alert if it is
                // console.log(arr_of_warehouses_used_before);

            })

            // check if array has this warehouse_id before , if it has , then alert some exception Msg
            function beforeAddWh(e,checkIfChangeOrAddClick){

                // e.preventDefault();

                let target = $('#warehouse_id').val();

                // if true it's a click event
                if(checkIfChangeOrAddClick){

                    if(target == ""){
                        alert('Warehouse Input can\'t be empty');
                        return false;
                    }
                    if($("#quantity").val() == ""){
                        alert('Quantity Input can\'t be empty');
                        return false;
                    }
                }


                if(arr_of_warehouses_used_before.length > 0){
                    for(let i =0; i < arr_of_warehouses_used_before.length; i++) {
                        if(arr_of_warehouses_used_before[i] == target ){
                            alert('noooo');
                            // console.log($('#warehouse_id').firstElementChild);
                            $("#warehouse_id").val("").change();
                            $("#quantity").val("");
                            // break;
                            return false;
                        }
                    };

                }
            }

            // remove <tr> should remove the id of wh frmo array too
            function remove_tr(event,whId){
                // console.log(whId);
                event.preventDefault();
                event.target.parentElement.parentElement.remove();
                arr_of_warehouses_used_before = arr_of_warehouses_used_before.filter(function(item){
                    return item != whId
                })
                // console.log(arr_of_warehouses_used_before);
                // console.log('a8a');
                // console.log(e.target);
            }

        </script>
        <script>
            // generate random code using keygen package
            $('#genbutton').on("click", function(){
                $.get('gencode', function(data){
                    // console.log(data);
                    $("input[name='code']").val(data);
                });
            });
        </script>
    @endsection
</x-app-layout>
