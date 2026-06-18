@extends('admin.layouts.admin')

@section('title', 'Technicians — DQIN AC Admin')
@section('page-title', 'Technicians')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <p style="color:#888;font-size:.85rem;">Manage service technicians</p>
    </div>
    <button onclick="openModal()" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Technician
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="technicians-table" class="dt-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Identity</th>
                        <th>Phone</th>
                        <th>Specialization</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal" id="techModal">
    <div class="modal-dialog modal-dialog-centered" style="max-width:550px;">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:20px 24px;">
                <h5 class="modal-title" id="modalTitle" style="font-weight:700;color:#333;">Add Technician</h5>
                <button type="button" class="btn-close" onclick="closeModal()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#999;">&times;</button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <form id="techForm">
                    <input type="hidden" id="tech_id">

                    <div class="form-group">
                        <label class="form-label">Account Type <span style="color:#dc3545;">*</span></label>
                        <select id="account_type" class="form-control" onchange="toggleAccountFields()">
                            <option value="new">Create new user account</option>
                            <option value="existing">Link to existing user</option>
                        </select>
                    </div>

                    <div id="existingUserGroup" style="display:none;">
                        <div class="form-group">
                            <label class="form-label">Select User</label>
                            <select id="user_id" class="form-control" style="width:100%;">
                                <option value="">-- Select User --</option>
                            </select>
                        </div>
                    </div>

                    <div id="newUserGroup">
                        <div class="form-group">
                            <label class="form-label">Email <span style="color:#dc3545;">*</span></label>
                            <input type="email" id="email" class="form-control" placeholder="technician@email.com">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="text" id="password" class="form-control" placeholder="Default: password">
                            <small style="color:#888;">Leave empty for default password</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Full Name <span style="color:#dc3545;">*</span></label>
                            <input type="text" id="full_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Identity</label>
                            <input type="text" id="identity" class="form-control" placeholder="KTP/Passport No.">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="text" id="phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Start Date</label>
                            <input type="date" id="start_date" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Specialization</label>
                        <input type="text" id="specialization" class="form-control" placeholder="e.g. AC Split, Freon">
                    </div>

                    <div class="form-group">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <input type="checkbox" id="is_active" checked style="width:18px;height:18px;accent-color:var(--primary);">
                            <span style="font-size:.88rem;">Active</span>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f0;padding:16px 24px;display:flex;justify-content:flex-end;gap:8px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveBtn" onclick="saveTechnician()"><i class="fa-solid fa-floppy-disk"></i> Save</button>
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
let techTable;

$(document).ready(function() {
    techTable = $('#technicians-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("admin.technicians.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'full_name', name: 'full_name' },
            { data: 'identity', name: 'identity' },
            { data: 'phone', name: 'phone' },
            { data: 'specialization', name: 'specialization' },
            { data: 'is_active', name: 'is_active' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    loadUsers();
});

function loadUsers() {
    $.getJSON('/admin/users/list', function(res) {
        const sel = $('#user_id');
        sel.html('<option value="">-- Select User --</option>');
        res.forEach(u => {
            sel.append(`<option value="${u.id}">${e(u.name)} (${e(u.email)})</option>`);
        });
    });
}

function toggleAccountFields() {
    const type = $('#account_type').val();
    $('#existingUserGroup').toggle(type === 'existing');
    $('#newUserGroup').toggle(type === 'new');
}

function openModal(editId) {
    $('#techModal').addClass('show');
    $('#modalBackdrop').addClass('show');
    $('#modalTitle').text('Add Technician');
    $('#saveBtn').html('<i class="fa-solid fa-floppy-disk"></i> Save');
    $('#techForm')[0].reset();
    $('#tech_id').val('');
    $('#account_type').val('new').trigger('change');
    $('#is_active').prop('checked', true);

    if (editId) {
        $('#modalTitle').text('Edit Technician');
        $('#saveBtn').html('<i class="fa-solid fa-pen"></i> Update');
        $.get('/admin/technicians/' + editId, function(res) {
            const t = res.data;
            $('#tech_id').val(t.id);
            $('#full_name').val(t.full_name);
            $('#identity').val(t.identity);
            $('#phone').val(t.phone);
            $('#specialization').val(t.specialization);
            $('#address').val(t.address || '');
            $('#start_date').val(t.start_date ? t.start_date.substring(0,10) : '');
            $('#is_active').prop('checked', t.is_active);
            $('#account_type').val('existing').trigger('change');

            if (t.user) {
                $('#user_id').append(`<option value="${t.user.id}" selected>${e(t.user.name)} (${e(t.user.email)})</option>`);
                $('#user_id').val(t.user.id);
            }
        });
    }
}

function closeModal() {
    $('#techModal').removeClass('show');
    $('#modalBackdrop').removeClass('show');
}

function saveTechnician() {
    const id = $('#tech_id').val();
    const isEdit = !!id;

    const data = {
        full_name: $('#full_name').val(),
        identity: $('#identity').val(),
        phone: $('#phone').val(),
        specialization: $('#specialization').val(),
        address: $('#address').val(),
        start_date: $('#start_date').val(),
        is_active: $('#is_active').is(':checked') ? 1 : 0,
        _token: '{{ csrf_token() }}'
    };

    if (!data.full_name) {
        Swal.fire('Validation Error', 'Full Name is required.', 'warning');
        return;
    }

    if (isEdit) {
        $.ajax({
            url: '/admin/technicians/' + id,
            method: 'PUT',
            data: data,
            success: function() {
                Swal.fire({ icon: 'success', title: 'Updated!', timer: 1500, showConfirmButton: false });
                closeModal();
                techTable.ajax.reload();
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Failed to update.', 'error');
            }
        });
    } else {
        const type = $('#account_type').val();
        if (type === 'existing') {
            data.user_id = $('#user_id').val();
            if (!data.user_id) {
                Swal.fire('Validation Error', 'Select a user to link.', 'warning');
                return;
            }
        } else {
            data.email = $('#email').val();
            if (!data.email) {
                Swal.fire('Validation Error', 'Email is required for new account.', 'warning');
                return;
            }
            data.password = $('#password').val() || 'password';
        }

        $.ajax({
            url: '/admin/technicians',
            method: 'POST',
            data: data,
            success: function() {
                Swal.fire({ icon: 'success', title: 'Created!', timer: 1500, showConfirmButton: false });
                closeModal();
                techTable.ajax.reload();
            },
            error: function(xhr) {
                const msg = xhr.responseJSON?.message || xhr.responseJSON?.errors ? Object.values(xhr.responseJSON.errors).flat().join(', ') : 'Something went wrong.';
                Swal.fire('Error', msg, 'error');
            }
        });
    }
}

$(document).on('click', '.edit-btn', function() {
    openModal($(this).data('id'));
});

$(document).on('click', '.delete-btn', function() {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Delete Technician?',
        text: 'The linked user account will also be deleted.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Delete'
    }).then(r => {
        if (r.isConfirmed) {
            $.ajax({
                url: '/admin/technicians/' + id,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    techTable.ajax.reload();
                    Swal.fire('Deleted!', 'Technician deleted.', 'success');
                }
            });
        }
    });
});

function e(str) { if (!str) return ''; return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }
</script>
@endpush
