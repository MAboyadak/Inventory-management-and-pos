<x-app-layout>

    @section('styles')
        <!-- Custom styles for this page -->
        <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">

    @endsection

    <!-- Page Heading -->
    @section('header')
        <x-page-header>
            Categories
        </x-page-header>
    @endsection

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between mb-4">
            <h6 class="m-0 font-weight-bold text-primary">Categories List</h6>
            @can('store warehouse')
                <a data-toggle="modal" data-target="#addWarehouseModal" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                        class="fas fa-plus fa-sm text-white-50"></i>{{__('Add Category')}}</a>
            @endcan
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

            {{-- ## --}}

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Parent Category')}}</th>
                            <th>{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)

                            <tr>
                                <td>{{$category->name}}</td>
                                <td>{{$category->parent->name ?? ''}}</td>
                                {{-- <td>@if ($category->parent_id !== null)
                                    {{$category->parent->name}}
                                @else
                                    {{' '}}
                                @endif
                                </td> --}}
                                <td>
                                    @can('edit warehouse')
                                        <a data-toggle="modal" data-id="{{$category->id}}" data-target="#editWarehouseModal" class="edit btn btn-primary btn-sm">{{__('Edit')}}</a>
                                    @endcan
                                    @can('delete warehouse')
                                        <a href="{{route('categories.delete',$category->id)}}" class="delete btn btn-danger btn-sm">{{__('Delete')}}</a>
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
                        <h5 class="modal-title">{{__('Add Category')}}</h5>
                        <button type="button" data-dismiss="modal">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('categories.store')}}" method="post">
                            @csrf

                            <div class="form-group">
                                <label>{{__('Category Name')}}</label>
                                <input name="name" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>{{__('Parent Category')}}</label>
                                <select class="form-control" name="parent_id">
                                    <option class="p-2" value="" selected></option>
                                    @foreach ($categories as $category)
                                        <option class="p-4 " value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
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
                        <h5 class="modal-title">{{__('Edit Category')}}</h5>
                        <button type="button" data-dismiss="modal">
                            <span aria-hidden="true">x</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" action="" method="post">
                            @csrf

                            <input id="cat_id" type="text" name="id" value="" hidden>

                            <div class="form-group">
                                <label>{{__('Category Name')}}</label>
                                <input id="cat_name" name="name" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>{{__('Category Parent')}}</label>
                                <select id="cat_parent" name="parent_id" type="text" class="form-control">
                                    @foreach ($categories as $category)
                                        <option class="p-4 " value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
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
        <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

        <!-- Page level custom scripts -->
        <script src="{{('js/demo/datatables-demo.js')}}"></script>

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
                let url = "{{route('categories.getdata',':id')}}";
                url = url.replace(':id',id);

                let updateUrl = "{{route('categories.update',':id')}}";
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
                        console.log(data);
                        $('#cat_name').val(data.name);
                        $('#cat_parent').val(data.parent_id);
                        $('#cat_id').val(id);

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
