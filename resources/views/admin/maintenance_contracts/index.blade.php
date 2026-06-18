@extends('admin.layouts.admin')

@section('title', 'Maintenance Contracts — DQIN AC Admin')
@section('page-title', 'Maintenance Contracts')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <p style="color:#888;font-size:.85rem;">Manage customer maintenance contracts</p>
    </div>
    <button onclick="openModal()" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Contract
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="contracts-table" class="dt-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Frequency</th>
                        <th>Price</th>
                        <th>Status</th>
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
    $('#contracts-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("admin.maintenance-contracts.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'customer_name', name: 'customer.full_name' },
            { data: 'start_date', name: 'start_date' },
            { data: 'end_date', name: 'end_date' },
            { data: 'frequency', name: 'frequency' },
            { data: 'price', name: 'price' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});

function openModal() {
    Swal.fire({
        title: 'Add Contract',
        text: 'Contract creation form will be implemented here.',
        icon: 'info'
    });
}

function editContract(id) {
    Swal.fire({
        title: 'Edit Contract',
        text: 'Edit ID: ' + id,
        icon: 'info',
        input: 'text',
        inputLabel: 'Update details (placeholder)',
        inputPlaceholder: 'Enter new value...'
    });
}

function deleteContract(id) {
    Swal.fire({
        title: 'Confirm Delete?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Delete'
    }).then(r => {
        if (r.isConfirmed) {
            fetch('/admin/maintenance-contracts/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(() => {
                $('#contracts-table').DataTable().ajax.reload();
                Swal.fire('Deleted!', 'Contract has been deleted.', 'success');
            });
        }
    });
}
</script>
@endpush
