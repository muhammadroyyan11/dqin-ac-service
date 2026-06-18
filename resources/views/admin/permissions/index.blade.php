@extends('admin.layouts.admin')

@section('title', 'Permissions — DQIN AC Admin')
@section('page-title', 'Permission Management')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <p style="color:#888;font-size:.85rem;">Manage individual permissions</p>
    </div>
    <button onclick="openModal()" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Permission
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="permissions-table" class="dt-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Display Name</th>
                        <th>Group</th>
                        <th>Created</th>
                        <th style="width:120px">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- Create/Edit Modal --}}
<div class="modal" id="permModal">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:20px 24px;">
                <h5 class="modal-title" id="modalTitle" style="font-weight:700;color:#333;">Add Permission</h5>
                <button type="button" class="btn-close" onclick="closeModal()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#999;">&times;</button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <form id="permForm">
                    <input type="hidden" id="perm_id">
                    <div class="form-group">
                        <label class="form-label">Permission Name <span style="color:#dc3545;">*</span></label>
                        <input type="text" id="name" class="form-control" placeholder="e.g. work-orders.view" required>
                        <small style="color:#888;">System name — format: group.action</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Display Name <span style="color:#dc3545;">*</span></label>
                        <input type="text" id="display_name" class="form-control" placeholder="e.g. View Work Orders" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Group <span style="color:#dc3545;">*</span></label>
                        <select id="group" class="form-control" required>
                            <option value="">-- Select Group --</option>
                            <option value="dashboard">Dashboard</option>
                            <option value="customers">Customers</option>
                            <option value="customer-units">Customer Units</option>
                            <option value="technicians">Technicians</option>
                            <option value="work-orders">Work Orders</option>
                            <option value="service-reports">Service Reports</option>
                            <option value="complaints">Complaints</option>
                            <option value="spareparts">Spareparts</option>
                            <option value="freon">Freon</option>
                            <option value="quotations">Quotations</option>
                            <option value="invoices">Invoices</option>
                            <option value="payments">Payments</option>
                            <option value="contracts">Contracts</option>
                            <option value="roles">Roles</option>
                            <option value="permissions">Permissions</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f0;padding:16px 24px;display:flex;justify-content:flex-end;gap:8px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveBtn" onclick="savePermission()"><i class="fa-solid fa-floppy-disk"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="modalBackdrop" onclick="closeModal()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1040;"></div>

<style>
.modal { display:none;position:fixed;inset:0;z-index:1050;justify-content:center;align-items:center; }
.modal.show { display:flex; }
.modal-dialog { width:100%;margin:20px; }
.modal-content { background:#fff; }
.modal-backdrop.show { display:block !important; }
</style>
@endsection

@push('scripts')
<script>
let permsTable;

$(document).ready(function() {
    permsTable = $('#permissions-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("admin.permissions.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'display_name', name: 'display_name' },
            { data: 'group', name: 'group' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});

function openModal(editId) {
    $('#permModal').addClass('show');
    $('#modalBackdrop').addClass('show');
    $('#modalTitle').text('Add Permission');
    $('#saveBtn').html('<i class="fa-solid fa-floppy-disk"></i> Save');
    $('#permForm')[0].reset();
    $('#perm_id').val('');

    if (editId) {
        $('#modalTitle').text('Edit Permission');
        $('#saveBtn').html('<i class="fa-solid fa-pen"></i> Update');
        $.get('/admin/permissions/' + editId, function(res) {
            const p = res.data;
            $('#perm_id').val(p.id);
            $('#name').val(p.name);
            $('#display_name').val(p.display_name);
            $('#group').val(p.group);
        });
    }
}

function closeModal() {
    $('#permModal').removeClass('show');
    $('#modalBackdrop').removeClass('show');
}

function savePermission() {
    const id = $('#perm_id').val();
    const isEdit = !!id;
    const data = {
        name: $('#name').val(),
        display_name: $('#display_name').val(),
        group: $('#group').val(),
        _token: '{{ csrf_token() }}'
    };

    if (!data.name || !data.display_name || !data.group) {
        Swal.fire('Validation Error', 'All fields are required.', 'warning');
        return;
    }

    $.ajax({
        url: isEdit ? '/admin/permissions/' + id : '/admin/permissions',
        method: isEdit ? 'PUT' : 'POST',
        data: data,
        success: function() {
            Swal.fire({ icon: 'success', title: isEdit ? 'Updated!' : 'Created!', timer: 1500, showConfirmButton: false });
            closeModal();
            permsTable.ajax.reload();
        },
        error: function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong.', 'error');
        }
    });
}

$(document).on('click', '.edit-btn', function() {
    openModal($(this).data('id'));
});

$(document).on('click', '.delete-btn', function() {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Delete Permission?',
        text: 'Roles using this permission will lose it.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Delete'
    }).then(r => {
        if (r.isConfirmed) {
            $.ajax({
                url: '/admin/permissions/' + id,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    permsTable.ajax.reload();
                    Swal.fire('Deleted!', 'Permission deleted.', 'success');
                }
            });
        }
    });
});
</script>
@endpush
