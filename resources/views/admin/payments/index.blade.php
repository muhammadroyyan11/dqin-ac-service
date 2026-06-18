@extends('admin.layouts.admin')

@section('title', 'Payments — DQIN AC Admin')
@section('page-title', 'Payments')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <p style="color:#888;font-size:.85rem;">Manage payment records</p>
    </div>
    <button onclick="openModal()" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Payment
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="payments-table" class="dt-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Invoice</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Date</th>
                        <th>Reference</th>
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
    $('#payments-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("admin.payments.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'invoice_number', name: 'invoice.invoice_number' },
            { data: 'amount', name: 'amount' },
            { data: 'payment_method', name: 'payment_method' },
            { data: 'payment_date', name: 'payment_date' },
            { data: 'reference', name: 'reference' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});

function openModal() {
    Swal.fire({
        title: 'Add Payment',
        text: 'Payment creation form will be implemented here.',
        icon: 'info'
    });
}

function editPayment(id) {
    Swal.fire({
        title: 'Edit Payment',
        text: 'Edit ID: ' + id,
        icon: 'info',
        input: 'text',
        inputLabel: 'Update details (placeholder)',
        inputPlaceholder: 'Enter new value...'
    });
}

function deletePayment(id) {
    Swal.fire({
        title: 'Confirm Delete?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Delete'
    }).then(r => {
        if (r.isConfirmed) {
            fetch('/admin/payments/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(() => {
                $('#payments-table').DataTable().ajax.reload();
                Swal.fire('Deleted!', 'Payment has been deleted.', 'success');
            });
        }
    });
}
</script>
@endpush
