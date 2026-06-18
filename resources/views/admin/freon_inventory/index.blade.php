@extends('admin.layouts.admin')

@section('title', 'Freon Inventory — DQIN AC Admin')
@section('page-title', 'Freon Inventory')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <p style="color:#888;font-size:.85rem;">Manage freon gas inventory</p>
    </div>
    <button onclick="openModal()" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Freon
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="freon-table" class="dt-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Stock</th>
                        <th>Unit</th>
                        <th>Price/Unit</th>
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
    $('#freon-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("admin.freon-inventory.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'type', name: 'type' },
            { data: 'stock', name: 'stock' },
            { data: 'unit', name: 'unit' },
            { data: 'price_per_unit', name: 'price_per_unit' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});

function openModal() {
    Swal.fire({
        title: 'Add Freon',
        text: 'Freon creation form will be implemented here.',
        icon: 'info'
    });
}

function editFreon(id) {
    Swal.fire({
        title: 'Edit Freon',
        text: 'Edit ID: ' + id,
        icon: 'info',
        input: 'text',
        inputLabel: 'Update details (placeholder)',
        inputPlaceholder: 'Enter new value...'
    });
}

function deleteFreon(id) {
    Swal.fire({
        title: 'Confirm Delete?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Delete'
    }).then(r => {
        if (r.isConfirmed) {
            fetch('/admin/freon-inventory/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(() => {
                $('#freon-table').DataTable().ajax.reload();
                Swal.fire('Deleted!', 'Freon has been deleted.', 'success');
            });
        }
    });
}
</script>
@endpush
