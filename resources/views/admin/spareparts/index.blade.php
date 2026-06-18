@extends('admin.layouts.admin')

@section('title', 'Spareparts — DQIN AC Admin')
@section('page-title', 'Spareparts')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <p style="color:#888;font-size:.85rem;">Manage spare parts and components stock</p>
    </div>
    <button onclick="openModal()" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Sparepart
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="spareparts-table" class="dt-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Part Number</th>
                        <th>Stock</th>
                        <th>Min Stock</th>
                        <th>Price</th>
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
    $('#spareparts-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("admin.spareparts.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'brand', name: 'brand' },
            { data: 'part_number', name: 'part_number' },
            { data: 'stock', name: 'stock' },
            { data: 'min_stock', name: 'min_stock' },
            { data: 'price', name: 'price' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});

function openModal() {
    Swal.fire({
        title: 'Add Sparepart',
        text: 'Sparepart creation form will be implemented here.',
        icon: 'info'
    });
}

function editSparepart(id) {
    Swal.fire({
        title: 'Edit Sparepart',
        text: 'Edit ID: ' + id,
        icon: 'info',
        input: 'text',
        inputLabel: 'Update details (placeholder)',
        inputPlaceholder: 'Enter new value...'
    });
}

function deleteSparepart(id) {
    Swal.fire({
        title: 'Confirm Delete?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Delete'
    }).then(r => {
        if (r.isConfirmed) {
            fetch('/admin/spareparts/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(() => {
                $('#spareparts-table').DataTable().ajax.reload();
                Swal.fire('Deleted!', 'Sparepart has been deleted.', 'success');
            });
        }
    });
}
</script>
@endpush
