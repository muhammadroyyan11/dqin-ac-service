@extends('admin.layouts.admin')

@section('title', 'Invoices — DQIN AC Admin')
@section('page-title', 'Invoices')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <p style="color:#888;font-size:.85rem;">Manage customer invoices</p>
    </div>
    <button onclick="openModal()" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Invoice
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="invoices-table" class="dt-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Invoice Number</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Status</th>
                        <th>Due Date</th>
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
    $('#invoices-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("admin.invoices.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'invoice_number', name: 'invoice_number' },
            { data: 'customer_name', name: 'customer.full_name' },
            { data: 'total', name: 'total' },
            { data: 'paid', name: 'paid' },
            { data: 'status', name: 'status' },
            { data: 'due_date', name: 'due_date' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});

function openModal() {
    Swal.fire({
        title: 'Add Invoice',
        text: 'Invoice creation form will be implemented here.',
        icon: 'info'
    });
}

function editInvoice(id) {
    Swal.fire({
        title: 'Edit Invoice',
        text: 'Edit ID: ' + id,
        icon: 'info',
        input: 'text',
        inputLabel: 'Update details (placeholder)',
        inputPlaceholder: 'Enter new value...'
    });
}

function deleteInvoice(id) {
    Swal.fire({
        title: 'Confirm Delete?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Delete'
    }).then(r => {
        if (r.isConfirmed) {
            fetch('/admin/invoices/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(() => {
                $('#invoices-table').DataTable().ajax.reload();
                Swal.fire('Deleted!', 'Invoice has been deleted.', 'success');
            });
        }
    });
}
</script>
@endpush
