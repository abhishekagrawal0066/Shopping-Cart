@extends('admin.master')

@section('admin.content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">User /</span> List</h4>
    
    <!-- Success Message -->
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
        <div>
            {{-- <a class="btn btn-primary float-end" href="{{ route('products.create') }}" role="button">Add</a> --}}
        </div>
    
        <div class="table-responsive text-nowrap" style="padding:5px">
            <table class="table table-bordered" id="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        @if(auth()->user()->is_admin)
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('users.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                @if(auth()->user()->is_admin)
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                @endif
            ]
        });
        
        @if(auth()->user()->is_admin)
            $('#users-table').on('click', '.delete', function() {
                var id = $(this).data('id');
                var url = '{{ route('users.destroy', ':id') }}'.replace(':id', id);

                if (confirm('Are you sure you want to delete this user?')) {
                    if (confirm('Do you want to perform a soft delete? Click "OK" for soft delete or "Cancel" for hard delete.')) {
                        // Soft delete logic
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}', soft: 'true' },
                            success: function(result) {
                                if (result.success) {
                                    table.ajax.reload();
                                } else {
                                    alert(result.message);
                                }
                            }
                        });
                    } else {
                        // Hard delete logic
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}', soft: 'false' },
                            success: function(result) {
                                if (result.success) {
                                    table.ajax.reload();
                                } else {
                                    alert(result.message);
                                }
                            }
                        });
                    }
                }
            });

            $('#users-table').on('click', '.restore', function() {
                var id = $(this).data('id');
                var url = '{{ route('users.restore', ':id') }}'.replace(':id', id);

                if (confirm('Are you sure you want to restore this user?')) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(result) {
                            if (result.success) {
                                table.ajax.reload();
                            } else {
                                alert(result.message);
                            }
                        }
                    });
                }
            });
        @endif
    });
</script>
@endpush


@endsection
