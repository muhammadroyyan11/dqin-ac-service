@extends('admin.layouts.admin')

@section('title', 'Complaints — DQIN AC Admin')
@section('page-title', 'Complaints')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <p style="color:#888;font-size:.85rem;">Manage customer complaints</p>
    </div>
    <button onclick="openModal()" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Complaint
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="complaints-table" class="dt-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Created</th>
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
    $('#complaints-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("admin.complaints.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'customer_name', name: 'customer.full_name' },
            { data: 'subject', name: 'subject' },
            { data: 'status', name: 'status' },
            { data: 'priority', name: 'priority' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});

function openModal() {
    Swal.fire({
        title: 'Add Complaint',
        text: 'Complaint creation form will be implemented here.',
        icon: 'info'
    });
}

function editComplaint(id) {
    Swal.fire({
        title: 'Edit Complaint',
        text: 'Edit ID: ' + id,
        icon: 'info',
        input: 'text',
        inputLabel: 'Update details (placeholder)',
        inputPlaceholder: 'Enter new value...'
    });
}

function deleteComplaint(id) {
    Swal.fire({
        title: 'Confirm Delete?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Delete'
    }).then(r => {
        if (r.isConfirmed) {
            fetch('/admin/complaints/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(() => {
                $('#complaints-table').DataTable().ajax.reload();
                Swal.fire('Deleted!', 'Complaint has been deleted.', 'success');
            });
        }
    });
}
</script>
@endpush
