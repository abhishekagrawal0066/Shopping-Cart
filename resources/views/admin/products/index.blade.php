@extends('admin.master')

@section('admin.content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Product /</span> Products List</h4>
    <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 2000)">
        @if(Session::has('success'))
            <div class="alert alert-success bg-green-300">
                {{ Session::get('success') }}
                @php
                    Session::forget('success');
                @endphp
            </div>
        @endif
    </div>
    <div class="card" style="padding:5px">
        <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <input type="file" name="file" class="form-control" required>
                @error('file')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-success mt-2">Import Products</button>
        </form>
        
        <div>
            <a class="btn btn-primary float-end" href="{{ route('products.create') }}" role="button">Add</a>
        </div>
    
        <div class="table-responsive text-nowrap" style="padding:5px">
            <table id="products-table" class="table table-striped" style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Images</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="uploadImagesModal" tabindex="-1" aria-labelledby="uploadImagesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadImagesModalLabel">Upload Images</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="uploadImagesForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="product_id">
                    <div class="mb-3">
                        <label for="images" class="form-label">Select Images</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple >
                        @error('images')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload Images</button>
                </div>
            </form>
            {{-- <form id="uploadImagesForm" action="{{ route('products.uploadImages') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_id" value="1">
                <input type="file" name="images[]" multiple>
                <button type="submit" class="btn btn-primary">Upload Images</button>
            </form> --}}
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function() {
         var table   = $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('products.index') }}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'category.name', name: 'category.name' },
                    { data: 'name', name: 'name' },
                    { data: 'price', name: 'price' },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'images', name: 'images', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
            $(document).on('click', '.upload-images-btn', function() {
                var productId = $(this).data('id');
                var productName = $(this).data('name');
                $('#product_id').val(productId);
                $('#uploadImagesModalLabel').text('Upload Images for ' + productName);
                $('#uploadImagesModal').modal('show');
            });

            // $('#uploadImagesForm').on('submit', function(e) {
            //     e.preventDefault();
            //     var formData = new FormData(this);
            //     $.ajax({
            //         url: "{{ route('products.uploadImages') }}",
            //         type: 'POST',
            //         data: formData,
            //         contentType: false,
            //         processData: false,
            //         success: function(response) {
            //             $('#uploadImagesModal').modal('hide');
            //             table.ajax.reload();
            //         },
            //         error: function(response) {
            //         }
            //     });
            // });
            $('#uploadImagesForm').on('submit', function(e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    $.ajax({
                        url: "{{ route('products.uploadImages') }}",
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#uploadImagesModal').modal('hide');
                            table.ajax.reload();
                            Swal.fire('Success', response.success, 'success');
                        },
                        error: function(response) {
                            if(response.responseJSON && response.responseJSON.errors) {
                                var errors = response.responseJSON.errors;
                                var errorMessage = '';
                                for (var error in errors) {
                                    if (errors.hasOwnProperty(error)) {
                                        errorMessage += errors[error][0] + '\n';
                                    }
                                }
                                Swal.fire('Error', errorMessage, 'error');
                            } else {
                                Swal.fire('Error', 'An error occurred while uploading images', 'error');
                            }
                        }
                    });
                });
            $(document).on('click', '.delete', function(e) {
                e.preventDefault();
                var url = $(this).data('url');
                var id = $(this).data('id');
                var action = $(this).hasClass('btn-warning') ? 'soft' : 'hard';

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to delete this product?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                table.ajax.reload();
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.restore', function(e) {
                e.preventDefault();
                var url = $(this).data('url');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to restore this product?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, restore it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                table.ajax.reload();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush


@endsection
