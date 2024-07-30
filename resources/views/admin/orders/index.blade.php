@extends('admin.master')

@section('admin.content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Product /</span> Orders List</h4>
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
        <div class="table-responsive text-nowrap" style="padding:5px">
            <table id="orders-table" class="table table-striped" style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Subtotal</th>
                        <th>GST</th>
                        <th>Total Price</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
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
        $('#orders-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('orders.data') }}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'user_id', name: 'user_id' },
                { data: 'subtotal', name: 'subtotal' },
                { data: 'gst', name: 'gst' },
                { data: 'total_price', name: 'total_price' },
                { data: 'payment_method', name: 'payment_method' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
            ]
        });

        // Handle delete button click
        $('#orders-table').on('click', '.delete', function() {
            var id = $(this).data('id');
            var url = '{{ route('orders.destroy', ':id') }}'.replace(':id', id);

            if (confirm('Are you sure you want to delete this order?')) {
                // Show delete options
                if (confirm('Do you want to perform a soft delete? Click "OK" for soft delete or "Cancel" for hard delete.')) {
                    // Soft delete logic
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}', _method: 'DELETE', soft: true },
                        success: function(result) {
                            $('#orders-table').DataTable().ajax.reload();
                        }
                    });
                } else {
                    // Hard delete logic
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}', _method: 'DELETE', soft: false },
                        success: function(result) {
                            $('#orders-table').DataTable().ajax.reload();
                        }
                    });
                }
            }
        });
    });
</script>
@endpush
@endsection
