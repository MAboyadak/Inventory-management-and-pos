<x-app-layout>

    @section('styles')
        <!-- Custom styles for this page -->
        <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    @endsection

    <!-- Page Heading -->
    @section('header')
        <x-page-header>
            Warehouses
        </x-page-header>
    @endsection

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between mb-4">
            <h6 class="m-0 font-weight-bold text-primary">Warehouses List</h6>
            @can('store warehouse')
                <a href="" data-toggle="modal" data-target="#addWarehouseModal" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                        class="fas fa-plus fa-sm text-white-50"></i>{{__('Add Warehouse')}}</a>
            @endcan
        </div>
        <div class="card-body">

            {{-- Flashed msgs  --}}

            @error('name')
                <div class="mb-3 alert alert-danger">{{ $message }}</div>
            @enderror

            @if (session()->has('error'))
                <div class="mb-3  alert alert-danger">{{ session()->get('error') }}</div>
            @endif

            @if (session()->has('success'))
                <div class="mb-3 alert alert-success">{{ session()->get('success') }}</div>
            @endif

            {{-- ## --}}

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Phone')}}</th>
                            <th>{{__('Address')}}</th>
                            <th>{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($warehouses as $warehouse)

                            <tr>
                                <td>{{$warehouse->name}}</td>
                                <td>{{$warehouse->phone}}</td>
                                <td>{{$warehouse->address}}</td>
                                <td>
                                    @can('edit warehouse')
                                        <a data-toggle="modal" data-id="{{$warehouse->id}}" data-target="#editWarehouseModal" class="edit btn btn-primary btn-sm">{{__('Edit')}}</a>
                                    @endcan
                                    @can('delete warehouse')
                                        <a href="{{route('warehouses.delete',$warehouse->id)}}" class="delete btn btn-danger btn-sm">{{__('Delete')}}</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @section('modals')
            <!-- Logout Modal-->
        {{-- <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="login.html">Logout</a>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="modal fade" id="addWarehouseModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Add Warehouse')}}</h5>
                        <button type="button" data-dismiss="modal">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('warehouses.store')}}" method="post">
                            @csrf

                            <div class="form-group">
                                <label>{{__('Warehouse Name')}}</label>
                                <input name="name" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>{{__('Phone')}}</label>
                                <input name="phone" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>{{__('Address')}}</label>
                                <input name="address" type="text" class="form-control">
                            </div>

                            <div class="modal-footer float-left">
                                <button type="submit" name="submit" class="btn btn-primary">{{__('Submit')}}</button>
                                <button class="btn btn-secondary mx-2" type="button" data-dismiss="modal">{{__('Cancel')}}</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editWarehouseModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Edit Warehouse')}}</h5>
                        <button type="button" data-dismiss="modal">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" action="" method="post">
                            @csrf

                            <input id="warehouse_id" type="text" name="id" value="" hidden>

                            <div class="form-group">
                                <label>{{__('Warehouse Name')}}</label>
                                <input id="warehouse_name" name="name" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>{{__('Phone')}}</label>
                                <input id="warehouse_phone" name="phone" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>{{__('Address')}}</label>
                                <input id="warehouse_address" name="address" type="text" class="form-control">
                            </div>

                            <div class="modal-footer float-left">
                                <button type="submit" name="submit" class="btn btn-primary">{{__('Submit')}}</button>
                                <button class="btn btn-secondary mx-2" type="button" data-dismiss="modal">{{__('Cancel')}}</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>


    @endsection

    @section('scripts')
        <!-- Page level plugins -->
        <script src="vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="js/demo/datatables-demo.js"></script>

        <script>
            $('.delete').on('click',function(event){
                event.preventDefault();
                const url = $(this).attr('href');
                return new swal({
                    title: 'Are you sure ?',
                    text: "{{__('This record will be deleted permanently')}}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                        // Swal.fire(
                        // 'Deleted!',
                        // 'Your file has been deleted.',
                        // 'success'
                        // )
                    }
                });
            });
        </script>
        <script>
            // const url = "{{route('warehouses.update',':id')}}";
            // url = url.replace(':id',id)

            $('.edit').on('click',function(event){
                event.preventDefault();
                let id = $(this).attr('data-id');
                let url = "{{route('warehouses.getdata',':id')}}";
                url = url.replace(':id',id);

                let updateUrl = "{{route('warehouses.update',':id')}}";
                updateUrl = updateUrl.replace(':id',id);


                console.log(url);
                $.ajax({
                    url : url,
                    type : 'get',
                    // data : {
                    //     'numberOfWords' : 10
                    // },
                    // dataType:'json',
                    success : function(data) {
                        // console.log(typeof(data));
                        // if(typeof(data) !=='object'){
                        //     Swal.fire({
                        //     icon: 'error',
                        //     title: 'Oops...',
                        //     text: 'You are not authorized!',
                        //     })
                        //     return;
                        // }
                        $('#warehouse_name').val(data.name);
                        $('#warehouse_phone').val(data.phone);
                        $('#warehouse_address').val(data.address);
                        $('#warehouse_id').val(id);

                        console.log(updateUrl)
                        $("#editForm").attr('action', updateUrl).submit();
                    },
                    error : function(request,error)
                    {
                        console.log(request.responseText);
                        console.log(error);
                    }
                });
            });

        </script>
    @endsection
</x-app-layout>
