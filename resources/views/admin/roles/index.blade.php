@extends('admin.layouts.admin')

@section('title', 'Roles — DQIN AC Admin')
@section('page-title', 'Role Management')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <p style="color:#888;font-size:.85rem;">Manage user roles & assign permissions</p>
    </div>
    <button onclick="openModal()" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Role
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="roles-table" class="dt-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Display Name</th>
                        <th>Description</th>
                        <th>Users</th>
                        <th>Permissions</th>
                        <th style="width:160px">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- Create/Edit Modal --}}
<div class="modal" id="roleModal">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:20px 24px;">
                <h5 class="modal-title" id="modalTitle" style="font-weight:700;color:#333;">Add Role</h5>
                <button type="button" class="btn-close" onclick="closeModal()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#999;">&times;</button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <form id="roleForm">
                    <input type="hidden" id="role_id">
                    <div class="form-group">
                        <label class="form-label">Role Name <span style="color:#dc3545;">*</span></label>
                        <input type="text" id="name" class="form-control" placeholder="e.g. admin_operasional" required>
                        <small style="color:#888;">System name — lowercase, underscores only</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Display Name <span style="color:#dc3545;">*</span></label>
                        <input type="text" id="display_name" class="form-control" placeholder="e.g. Admin Operasional" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea id="description" class="form-control" rows="3" placeholder="Describe this role..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f0;padding:16px 24px;display:flex;justify-content:flex-end;gap:8px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveBtn" onclick="saveRole()"><i class="fa-solid fa-floppy-disk"></i> Save</button>
            </div>
        </div>
    </div>
</div>

{{-- Permissions Modal --}}
<div class="modal" id="permModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;max-height:80vh;overflow-y:auto;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:20px 24px;">
                <h5 class="modal-title" style="font-weight:700;color:#333;">Permissions: <span id="permRoleName"></span></h5>
                <button type="button" class="btn-close" onclick="closePermModal()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#999;">&times;</button>
            </div>
            <div class="modal-body" style="padding:24px;" id="permBody">
                <div style="text-align:center;padding:40px;color:#999;">Loading...</div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f0;padding:16px 24px;display:flex;justify-content:flex-end;gap:8px;">
                <button type="button" class="btn btn-secondary" onclick="closePermModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="savePermissions()"><i class="fa-solid fa-floppy-disk"></i> Save Permissions</button>
            </div>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="modalBackdrop" onclick="closeModal();closePermModal();" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1040;"></div>

<style>
.modal { display:none;position:fixed;inset:0;z-index:1050;justify-content:center;align-items:center; }
.modal.show { display:flex; }
.modal-dialog { width:100%;margin:20px; }
.modal-content { background:#fff; }
.modal-backdrop.show { display:block !important; }
.perm-group { margin-bottom:20px; }
.perm-group h6 { font-weight:600;font-size:.9rem;color:#333;margin-bottom:8px;padding-bottom:6px;border-bottom:1px solid #eee;text-transform:capitalize; }
.perm-group label { display:inline-flex;align-items:center;gap:6px;margin:4px 8px;font-size:.85rem;cursor:pointer; }
.perm-group input[type="checkbox"] { accent-color:var(--primary);width:16px;height:16px; }
</style>
@endsection

@push('scripts')
<script>
let rolesTable, currentRoleId;

$(document).ready(function() {
    rolesTable = $('#roles-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("admin.roles.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'display_name', name: 'display_name' },
            { data: 'description', name: 'description', defaultContent: '-' },
            { data: 'users_count', name: 'users_count' },
            { data: 'permissions_count', name: 'permissions_count' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});

function openModal(editId) {
    $('#roleModal').addClass('show');
    $('#modalBackdrop').addClass('show');
    $('#modalTitle').text('Add Role');
    $('#saveBtn').html('<i class="fa-solid fa-floppy-disk"></i> Save');
    $('#roleForm')[0].reset();
    $('#role_id').val('');

    if (editId) {
        $('#modalTitle').text('Edit Role');
        $('#saveBtn').html('<i class="fa-solid fa-pen"></i> Update');
        $.get('/admin/roles/' + editId, function(res) {
            const r = res.data;
            $('#role_id').val(r.id);
            $('#name').val(r.name);
            $('#display_name').val(r.display_name);
            $('#description').val(r.description || '');
        });
    }
}

function closeModal() {
    $('#roleModal').removeClass('show');
    $('#modalBackdrop').removeClass('show');
}

function saveRole() {
    const id = $('#role_id').val();
    const isEdit = !!id;
    const data = {
        name: $('#name').val(),
        display_name: $('#display_name').val(),
        description: $('#description').val(),
        _token: '{{ csrf_token() }}'
    };

    if (!data.name || !data.display_name) {
        Swal.fire('Validation Error', 'Name and Display Name are required.', 'warning');
        return;
    }

    $.ajax({
        url: isEdit ? '/admin/roles/' + id : '/admin/roles',
        method: isEdit ? 'PUT' : 'POST',
        data: data,
        success: function() {
            Swal.fire({ icon: 'success', title: isEdit ? 'Updated!' : 'Created!', timer: 1500, showConfirmButton: false });
            closeModal();
            rolesTable.ajax.reload();
        },
        error: function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong.', 'error');
        }
    });
}

$(document).on('click', '.edit-btn', function() {
    openModal($(this).data('id'));
});

$(document).on('click', '.perm-btn', function() {
    const id = $(this).data('id');
    const name = $(this).data('name');
    currentRoleId = id;
    $('#permRoleName').text(name);
    $('#permBody').html('<div style="text-align:center;padding:40px;color:#999;">Loading...</div>');
    $('#permModal').addClass('show');
    $('#modalBackdrop').addClass('show');

    $.get('/admin/roles/' + id + '/permissions', function(res) {
        let html = '';
        $.each(res.permissions, function(group, perms) {
            html += '<div class="perm-group">';
            html += '<h6>' + group.charAt(0).toUpperCase() + group.slice(1) + '</h6>';
            perms.forEach(function(p) {
                const checked = res.rolePermissionIds.indexOf(p.id) !== -1 ? 'checked' : '';
                html += '<label><input type="checkbox" class="perm-checkbox" value="' + p.id + '" ' + checked + '> ' + p.display_name + '</label>';
            });
            html += '</div>';
        });
        $('#permBody').html(html);
    });
});

function closePermModal() {
    $('#permModal').removeClass('show');
    $('#modalBackdrop').removeClass('show');
}

function savePermissions() {
    const ids = [];
    $('.perm-checkbox:checked').each(function() {
        ids.push(parseInt($(this).val()));
    });

    $.ajax({
        url: '/admin/roles/' + currentRoleId + '/permissions',
        method: 'PUT',
        data: { permission_ids: ids, _token: '{{ csrf_token() }}' },
        success: function() {
            Swal.fire({ icon: 'success', title: 'Permissions Updated!', timer: 1500, showConfirmButton: false });
            closePermModal();
            rolesTable.ajax.reload();
        },
        error: function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Failed to update permissions.', 'error');
        }
    });
}

$(document).on('click', '.delete-btn', function() {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Delete Role?',
        text: 'Users with this role will lose its permissions.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Delete'
    }).then(r => {
        if (r.isConfirmed) {
            $.ajax({
                url: '/admin/roles/' + id,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    rolesTable.ajax.reload();
                    Swal.fire('Deleted!', 'Role has been deleted.', 'success');
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Cannot delete this role.', 'error');
                }
            });
        }
    });
});
</script>
@endpush
