@extends('admin.layouts.admin')

@section('title', 'Work Orders — DQIN AC Admin')
@section('page-title', 'Work Orders')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <p style="color:#888;font-size:.85rem;">Manage all service work orders & assign technicians</p>
    </div>
    <button onclick="openModal()" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Work Order
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="work_orders-table" class="dt-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>WO Number</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Technicians</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Scheduled</th>
                        <th style="width:120px">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- Create/Edit Modal --}}
<div class="modal fade" id="woModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;max-height:90vh;overflow-y:auto;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:20px 24px;">
                <h5 class="modal-title" id="modalTitle" style="font-weight:700;color:#333;">Add Work Order</h5>
                <button type="button" class="btn-close" onclick="closeModal()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#999;">&times;</button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <form id="woForm">
                    <input type="hidden" id="wo_id">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">WO Number <span style="color:#dc3545;">*</span></label>
                            <input type="text" id="wo_number" class="form-control" placeholder="Auto or manual" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Service Type <span style="color:#dc3545;">*</span></label>
                            <select id="service_type" class="form-control" required>
                                <option value="">-- Select Service --</option>
                                <option value="cleaning">AC Cleaning</option>
                                <option value="repair">AC Repair</option>
                                <option value="freon_refill">Freon Refill</option>
                                <option value="relocation">AC Relocation</option>
                                <option value="installation">New Installation</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Customer <span style="color:#dc3545;">*</span></label>
                            <select id="customer_id" class="form-control" required style="width:100%;">
                                <option value="">-- Select Customer --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Customer Unit (optional)</label>
                            <select id="customer_unit_id" class="form-control" style="width:100%;">
                                <option value="">-- Select Unit --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea id="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Priority <span style="color:#dc3545;">*</span></label>
                            <select id="priority" class="form-control" required>
                                <option value="low">Low</option>
                                <option value="normal" selected>Normal</option>
                                <option value="high">High</option>
                                <option value="emergency">Emergency</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status <span style="color:#dc3545;">*</span></label>
                            <select id="status" class="form-control" required>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Scheduled Date</label>
                            <input type="datetime-local" id="scheduled_date" class="form-control">
                        </div>
                        <div class="form-group" id="completed_date_group" style="display:none;">
                            <label class="form-label">Completed Date</label>
                            <input type="datetime-local" id="completed_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Total Estimate (RM)</label>
                            <input type="number" id="total_estimate" class="form-control" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Assign Technicians</label>
                        <div id="technicians-container" style="border:1px solid #ddd;border-radius:8px;padding:12px;max-height:200px;overflow-y:auto;">
                            <p style="color:#999;font-size:.85rem;" id="no-tech-msg">Loading technicians...</p>
                        </div>
                        <small style="color:#888;">Select one technician as Captain (team leader).</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea id="notes" class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f0;padding:16px 24px;display:flex;justify-content:flex-end;gap:8px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveBtn" onclick="saveWorkOrder()">
                    <i class="fa-solid fa-floppy-disk"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal backdrop --}}
<div class="modal-backdrop" id="modalBackdrop" onclick="closeModal()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1040;"></div>

<style>
.modal { display:none;position:fixed;inset:0;z-index:1050;justify-content:center;align-items:center; }
.modal.show { display:flex; }
.modal-dialog { width:100%;max-width:800px;margin:20px; }
.modal-content { background:#fff; }
.modal-backdrop.show { display:block !important; }
.tech-check-item { display:flex;align-items:center;gap:10px;padding:8px 10px;border-radius:6px;transition:background .15s; }
.tech-check-item:hover { background:#f8f9fa; }
.tech-check-item input[type="checkbox"] { width:18px;height:18px;accent-color:var(--primary);cursor:pointer; }
.tech-check-item input[type="radio"] { width:18px;height:18px;accent-color:var(--primary);cursor:pointer; }
.tech-label { flex:1;font-size:.875rem;color:#444;cursor:pointer; }
.captain-badge { font-size:.7rem;background:var(--primary);color:#fff;padding:2px 8px;border-radius:12px;font-weight:600; }
.badge-captain { background:var(--primary);color:#fff;font-size:.7rem;padding:1px 6px;border-radius:10px; }
.tech-badge { display:inline-block;padding:2px 8px;border-radius:12px;font-size:.75rem;background:#e8f5e9;color:#2e7d32;margin:2px;white-space:nowrap; }
</style>
@endsection

@push('scripts')
<script>
let woTable;
let selectedTechnicians = [];
let ckDescription = null;
let ckNotes = null;

let ckInitialized = false;

$(document).ready(function() {
    woTable = $('#work_orders-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("admin.work-orders.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'wo_number', name: 'wo_number' },
            { data: 'customer_name', name: 'customer.full_name' },
            { data: 'service_type', name: 'service_type' },
            { data: 'technicians_list', name: 'technicians_list', orderable: false, searchable: false },
            { data: 'status', name: 'status' },
            { data: 'priority', name: 'priority' },
            { data: 'scheduled_date', name: 'scheduled_date' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        columnDefs: [
            { targets: 4, width: '200px' }
        ]
    });

    loadCustomers();

    $('#customer_id').on('change', function() {
        loadCustomerUnits($(this).val());
    });

    $('#status').on('change', function() {
        $('#completed_date_group').toggle($(this).val() === 'completed');
    });
});

function loadCustomers() {
    $.get('{{ route("admin.customers.data") }}', function(res) {
        const sel = $('#customer_id');
        sel.html('<option value="">-- Select Customer --</option>');
        if (res.data) {
            res.data.forEach(c => {
                sel.append(`<option value="${c.id}">${e(c.full_name)} (${e(c.phone || '-')})</option>`);
            });
        }
    });
}

function loadCustomerUnits(customerId) {
    const sel = $('#customer_unit_id');
    sel.html('<option value="">-- Select Unit --</option>');
    if (!customerId) return;
    $.get('/admin/customer-units/by-customer/' + customerId, function(data) {
        data.forEach(u => {
            sel.append(`<option value="${u.id}">${e(u.brand)} ${e(u.type)} - ${u.pk} PK (${e(u.installation_location || '-')})</option>`);
        });
    });
}

function loadTechnicians() {
    $.get('{{ route("admin.technicians.data") }}', function(res) {
        const container = $('#technicians-container');
        container.empty();
        if (!res.data || res.data.length === 0) {
            container.html('<p style="color:#999;font-size:.85rem;">No technicians available. Add technicians first.</p>');
            return;
        }
        res.data.forEach(t => {
            const checked = selectedTechnicians.some(s => s.id === t.id) ? 'checked' : '';
            const isCaptain = selectedTechnicians.some(s => s.id === t.id && s.is_captain) ? 'checked' : '';
            container.append(`
                <div class="tech-check-item">
                    <input type="checkbox" class="tech-checkbox" value="${t.id}" data-name="${e(t.full_name)}" ${checked}>
                    <span class="tech-label">${e(t.full_name)} (${e(t.nik || '-')})</span>
                    <label style="font-size:.75rem;color:#888;display:flex;align-items:center;gap:4px;">
                        <input type="radio" name="captain" value="${t.id}" ${isCaptain} style="accent-color:var(--primary);">
                        Captain
                    </label>
                </div>
            `);
        });
        $('.tech-checkbox').on('change', function() {
            updateSelectedTechnicians();
        });
        $('input[name="captain"]').on('change', function() {
            updateSelectedTechnicians();
        });
    });
}

function updateSelectedTechnicians() {
    selectedTechnicians = [];
    $('.tech-checkbox:checked').each(function() {
        const id = parseInt($(this).val());
        const isCaptain = $(`input[name="captain"][value="${id}"]`).is(':checked');
        selectedTechnicians.push({ id, is_captain: isCaptain });
    });
    $('input[name="captain"]').prop('disabled', false);
    if (selectedTechnicians.length === 0) {
        $('input[name="captain"]').prop('disabled', true).prop('checked', false);
    }
}

function openModal(editId) {
    $('#woModal').addClass('show');
    $('#modalBackdrop').addClass('show');
    $('#modalTitle').text('Add Work Order');
    $('#saveBtn').html('<i class="fa-solid fa-floppy-disk"></i> Save');
    $('#woForm')[0].reset();
    $('#wo_id').val('');
    $('#completed_date_group').hide();
    selectedTechnicians = [];

    if (ckDescription) ckDescription.setData('');
    if (ckNotes) ckNotes.setData('');

    loadTechnicians();

    if (!ckInitialized) {
        setTimeout(() => {
            initCKEditor();
            ckInitialized = true;
        }, 500);
    }

    if (editId) {
        $('#modalTitle').text('Edit Work Order');
        $('#saveBtn').html('<i class="fa-solid fa-pen"></i> Update');
        $.get('/admin/work-orders/' + editId, function(res) {
            const wo = res.data;
            $('#wo_id').val(wo.id);
            $('#wo_number').val(wo.wo_number);
            $('#customer_id').val(wo.customer_id).trigger('change');

            setTimeout(() => {
                $('#customer_unit_id').val(wo.customer_unit_id);
            }, 500);

            $('#service_type').val(wo.service_type);
            $('#priority').val(wo.priority);
            $('#status').val(wo.status);
            if (wo.status === 'completed') {
                $('#completed_date_group').show();
                if (wo.completed_date) $('#completed_date').val(wo.completed_date.substring(0,16));
            }
            if (wo.scheduled_date) $('#scheduled_date').val(wo.scheduled_date.substring(0,16));
            $('#total_estimate').val(wo.total_estimate);

            if (ckDescription && wo.description) ckDescription.setData(wo.description);
            if (ckNotes && wo.notes) ckNotes.setData(wo.notes);

            if (wo.technicians) {
                selectedTechnicians = wo.technicians.map(t => ({
                    id: t.id,
                    is_captain: t.pivot.is_captain
                }));
                loadTechnicians();
            }
        });
    }
}

function closeModal() {
    $('#woModal').removeClass('show');
    $('#modalBackdrop').removeClass('show');
}

function saveWorkOrder() {
    const id = $('#wo_id').val();
    const isEdit = !!id;

    const data = {
        wo_number: $('#wo_number').val(),
        customer_id: $('#customer_id').val(),
        customer_unit_id: $('#customer_unit_id').val() || null,
        service_type: $('#service_type').val(),
        description: ckDescription ? ckDescription.getData() : '',
        priority: $('#priority').val(),
        status: $('#status').val(),
        scheduled_date: $('#scheduled_date').val() || null,
        notes: ckNotes ? ckNotes.getData() : '',
        total_estimate: $('#total_estimate').val() || null,
        technicians: selectedTechnicians,
    };

    if (data.status === 'completed') {
        data.completed_date = $('#completed_date').val() || new Date().toISOString().substring(0,16);
    }

    if (!data.wo_number || !data.customer_id || !data.service_type) {
        Swal.fire('Validation Error', 'WO Number, Customer, and Service Type are required.', 'warning');
        return;
    }

    const url = isEdit ? '/admin/work-orders/' + id : '/admin/work-orders';
    const method = isEdit ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        method: method,
        data: { ...data, _token: '{{ csrf_token() }}' },
        success: function() {
            Swal.fire({ icon: 'success', title: isEdit ? 'Updated!' : 'Created!', timer: 1500, showConfirmButton: false });
            closeModal();
            woTable.ajax.reload();
        },
        error: function(xhr) {
            const msg = xhr.responseJSON?.message || 'Something went wrong.';
            Swal.fire('Error', msg, 'error');
        }
    });
}

$(document).on('click', '.edit-btn', function() {
    openModal($(this).data('id'));
});

$(document).on('click', '.delete-btn', function() {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Delete Work Order?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Delete'
    }).then(r => {
        if (r.isConfirmed) {
            $.ajax({
                url: '/admin/work-orders/' + id,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    woTable.ajax.reload();
                    Swal.fire('Deleted!', 'Work order has been deleted.', 'success');
                }
            });
        }
    });
});

function initCKEditor() {
    if (typeof ClassicEditor === 'undefined') return;

    const descEl = document.querySelector('#description');
    const notesEl = document.querySelector('#notes');

    if (!ckDescription && descEl) {
        ClassicEditor.create(descEl, {
            toolbar: ['heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', '|', 'link', 'blockQuote', '|', 'undo', 'redo'],
            heading: { options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                { model: 'heading3', view: 'h3', title: 'Heading', class: 'ck-heading_heading3' },
            ]}
        }).then(editor => {
            ckDescription = editor;
        }).catch(() => {});
    }

    if (!ckNotes && notesEl) {
        ClassicEditor.create(notesEl, {
            toolbar: ['bold', 'italic', 'bulletedList', '|', 'undo', 'redo'],
        }).then(editor => {
            ckNotes = editor;
        }).catch(() => {});
    }
}

function e(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
</script>
@endpush
