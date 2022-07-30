<x-app-layout>

    @section('styles')
        <!-- Custom styles for this page -->
        <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
        {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css"> --}}
        <link rel="stylesheet" type="text/css" href="{{asset('vendor/datatables/buttons.bootstrap4.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}">
        {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"> --}}
        {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css"> --}}

    @endsection

    <!-- Page Heading -->
    @section('header')
        <x-page-header>
            Products
        </x-page-header>
    @endsection

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Products List</h6>
            @can('store warehouse')
                <a href="{{route('products.create')}}" class="d-sm-inline-block btn btn-sm btn-info shadow-sm"><i
                        class="fas fa-plus fa-sm text-white-50"></i>{{__('Add Product')}}</a>
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
                <table class="table table-bordered" id="myTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Code')}}</th>
                            <th>{{__('Category')}}</th>
                            <th>{{__('Unit')}}</th>
                            <th>{{__('Cost')}}</th>
                            <th>{{__('Price')}}</th>
                            <th>{{__('Quantity')}}</th>
                            <th>{{__('Alert')}}</th>
                            <th class="not-exported">{{__('Image')}}</th>
                            <th>{{__('Details')}}</th>
                            <th class="not-exported">{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($products as $product)

                            <tr>
                                <td>{{$product->name}}</td>
                                <td>{{$product->code}}</td>
                                <td>{{$product->category->name ?? ''}}</td>
                                <td>{{$product->unit_id}}</td>
                                <td>{{$product->cost}}</td>
                                <td>{{$product->price}}</td>
                                <td>{{$product->price}}</td>
                                <td>{{$product->alert_quantity}}</td>
                                <td class="not-exported">
                                    @if($product->image)
                                        <img style="max-width:60px" src='{{asset("storage/files/$product->image")}}'>
                                    @endif
                                </td>
                                <td>{{$product->product_details ?? ''}}</td>
                                {{-- <td>@if ($category->parent_id !== null)
                                    {{$category->parent->name}}
                                @else
                                    {{' '}}
                                @endif
                                </td> --}}
                                <td class="not-exported">
                                    @can('edit warehouse')
                                        <a href="{{route('products.edit',$product->id)}}" class="edit btn btn-primary btn-sm">{{__('Edit')}}</a>
                                    @endcan
                                    @can('delete warehouse')
                                        <a href="{{route('products.delete',$product->id)}}" class="delete btn btn-danger btn-sm">{{__('Delete')}}</a>
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

    @endsection

    @section('scripts')
        <!-- Page level plugins -->
        {{-- <script src="vendor/datatables/jquery.dataTables.min.js"></script> --}}
        {{-- <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script> --}}
        {{-- <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('vendor/datatables/dataTables.buttons.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('vendor/datatables/buttons.bootstrap4.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('vendor/datatables/buttons.colVis.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('vendor/datatables/buttons.html5.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('vendor/datatables/buttons.print.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('vendor/datatables/pdfmake.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('vendor/datatables/vfs_fonts.js')}}"></script>

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

        {{-- datatable buttons --}}
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable( {
                    dom: '<"row"lfB>rtip',
                    buttons: [
                        {
                            extend: 'pdf',
                            text: '<i title="export to pdf" style="font-size:14px;color:white;" class="fas fa-file-pdf" ></i>',
                            exportOptions: {
                                columns: ':visible:not(.not-exported)',
                                rows: ':visible',
                                stripHtml: true
                            },
                        },
                        {
                            extend: 'csv',
                            text: '<i title="export to csv" style="font-size:14px;color:white;" class="fas fa-file-csv" ></i>',
                            exportOptions: {
                                columns: ':visible:not(.not-exported)',
                                rows: ':visible',
                                format: {
                                    body: function ( data, row, column, node ) {
                                        if (column === 0 && (data.indexOf('<img src=') !== -1)) {
                                            var regex = /<img.*?src=['"](.*?)['"]/;
                                            data = regex.exec(data)[1];
                                        }
                                        return data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'print',
                            text: '<i title="print" style="font-size:14px;color:white;" class="fa fa-print"></i>',
                            exportOptions: {
                                columns: ':visible:not(.not-exported)',
                                rows: ':visible',
                                stripHtml: false
                            }
                        },
                    ]
                });
            });
        </script>

    @endsection
</x-app-layout>
