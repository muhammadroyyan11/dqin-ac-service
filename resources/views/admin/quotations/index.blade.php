@extends('admin.layouts.admin')

@section('title', 'Quotations — DQIN AC Admin')
@section('page-title', 'Quotations')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <p style="color:#888;font-size:.85rem;">Manage price quotations</p>
    </div>
    <button onclick="openModal()" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Quotation
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="quotations-table" class="dt-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Quote Number</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Created At</th>
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
    $('#quotations-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("admin.quotations.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'quote_number', name: 'quote_number' },
            { data: 'customer_name', name: 'customer.full_name' },
            { data: 'total', name: 'total' },
            { data: 'status', name: 'status' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});

function openModal() {
    Swal.fire({
        title: 'Add Quotation',
        text: 'Quotation creation form will be implemented here.',
        icon: 'info'
    });
}

function editQuotation(id) {
    Swal.fire({
        title: 'Edit Quotation',
        text: 'Edit ID: ' + id,
        icon: 'info',
        input: 'text',
        inputLabel: 'Update details (placeholder)',
        inputPlaceholder: 'Enter new value...'
    });
}

function deleteQuotation(id) {
    Swal.fire({
        title: 'Confirm Delete?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Delete'
    }).then(r => {
        if (r.isConfirmed) {
            fetch('/admin/quotations/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(() => {
                $('#quotations-table').DataTable().ajax.reload();
                Swal.fire('Deleted!', 'Quotation has been deleted.', 'success');
            });
        }
    });
}
</script>
@endpush
