@extends('admin.master')

@section('admin.content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Category /</span> List</h4>
    
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
            <a class="btn btn-primary float-end" href="{{ route('categories.create') }}" role="button">Add</a>
        </div>
    
        <div class="table-responsive text-nowrap" style="padding:5px">
            <table class="table table-bordered" id="categories-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Action</th>
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
        var table = $('#categories-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('categories.index') }}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        $('#categories-table').on('submit', 'form', function(e) {
            e.preventDefault(); // Prevent default form submission

            var form = $(this);
            var url = form.attr('action');
            var csrfToken = form.find('input[name="_token"]').val();

            if (confirm('Are you sure you want to delete this category?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: csrfToken
                    },
                    success: function(result) {
                        if (result.success) {
                            table.ajax.reload();
                        } else {
                            alert(result.message);
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred while deleting the category.');
                    }
                });
            }
        });
    });
</script>
@endpush

@endsection
