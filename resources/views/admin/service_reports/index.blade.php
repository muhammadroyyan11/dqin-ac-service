@extends('admin.layouts.admin')

@section('title', 'Service Reports — DQIN AC Admin')
@section('page-title', 'Service Reports')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <p style="color:#888;font-size:.85rem;">Manage service report records</p>
    </div>
    <button onclick="openModal()" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Report
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="service-reports-table" class="dt-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Work Order</th>
                        <th>Technician</th>
                        <th>Findings</th>
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
    $('#service-reports-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("admin.service-reports.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'wo_number', name: 'workOrder.wo_number' },
            { data: 'technician_name', name: 'technician.full_name' },
            { data: 'findings', name: 'findings' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});

function openModal() {
    Swal.fire({
        title: 'Add Service Report',
        text: 'Service report creation form will be implemented here.',
        icon: 'info'
    });
}

function editReport(id) {
    Swal.fire({
        title: 'Edit Service Report',
        text: 'Edit ID: ' + id,
        icon: 'info',
        input: 'text',
        inputLabel: 'Update details (placeholder)',
        inputPlaceholder: 'Enter new value...'
    });
}

function deleteReport(id) {
    Swal.fire({
        title: 'Confirm Delete?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Delete'
    }).then(r => {
        if (r.isConfirmed) {
            fetch('/admin/service-reports/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(() => {
                $('#service-reports-table').DataTable().ajax.reload();
                Swal.fire('Deleted!', 'Service report has been deleted.', 'success');
            });
        }
    });
}
</script>
@endpush
