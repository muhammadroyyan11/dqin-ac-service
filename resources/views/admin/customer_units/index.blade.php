@extends('admin.layouts.admin')

@section('title', 'Customer Units — DQIN AC Admin')
@section('page-title', 'Customer Units')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <p style="color:#888;font-size:.85rem;">Manage customer AC units</p>
    </div>
    <button onclick="openModal()" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Unit
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="customer-units-table" class="dt-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer Name</th>
                        <th>Brand</th>
                        <th>Type</th>
                        <th>PK</th>
                        <th>Serial Number</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#customer-units-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("admin.customer-units.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'customer_name', name: 'customer.full_name' },
            { data: 'brand', name: 'brand' },
            { data: 'type', name: 'type' },
            { data: 'pk', name: 'pk' },
            { data: 'serial_number', name: 'serial_number' },
            { data: 'location', name: 'location' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});

function openModal() {
    Swal.fire({
        title: 'Add Unit',
        text: 'Customer unit creation form will be implemented here.',
        icon: 'info'
    });
}

function editUnit(id) {
    Swal.fire({
        title: 'Edit Unit',
        text: 'Edit ID: ' + id,
        icon: 'info',
        input: 'text',
        inputLabel: 'Update details (placeholder)',
        inputPlaceholder: 'Enter new value...'
    });
}

function deleteUnit(id) {
    Swal.fire({
        title: 'Confirm Delete?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Delete'
    }).then(r => {
        if (r.isConfirmed) {
            fetch('/admin/customer-units/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(() => {
                $('#customer-units-table').DataTable().ajax.reload();
                Swal.fire('Deleted!', 'Unit has been deleted.', 'success');
            });
        }
    });
}
</script>
@endpush
