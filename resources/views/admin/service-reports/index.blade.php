@extends('admin.layouts.admin')

@section('title', 'Service Reports — DQIN AC Admin')
@section('page-title', 'Service Reports')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <div>
        <p style="color:#888;font-size:.85rem;">Manage service reports & spare parts used per work order</p>
    </div>
    <button onclick="openModal()" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Add Report
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="sr-table" class="dt-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Work Order</th>
                        <th>Technician</th>
                        <th>Findings</th>
                        <th>Actions Taken</th>
                        <th>Spareparts</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="srModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;max-height:90vh;overflow-y:auto;">
            <div class="modal-header" style="border-bottom:1px solid #f0f0f0;padding:20px 24px;">
                <h5 class="modal-title" id="modalTitle" style="font-weight:700;color:#333;">Add Service Report</h5>
                <button type="button" class="btn-close" onclick="closeModal()" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:#999;">&times;</button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <form id="srForm">
                    <input type="hidden" id="sr_id">

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Work Order <span style="color:#dc3545;">*</span></label>
                            <select id="work_order_id" class="form-control" required>
                                <option value="">-- Select Work Order --</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Technician <span style="color:#dc3545;">*</span></label>
                            <select id="technician_id" class="form-control" required>
                                <option value="">-- Select Technician --</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Findings</label>
                        <textarea id="findings" class="form-control" rows="5"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Actions Taken</label>
                        <textarea id="actions_taken" class="form-control" rows="5"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Spare Parts Used</label>
                        <div id="spareparts-container" style="border:1px solid #ddd;border-radius:8px;padding:12px;max-height:200px;overflow-y:auto;margin-bottom:8px;">
                            <p style="color:#999;font-size:.85rem;" id="no-sp-msg">Loading spare parts...</p>
                        </div>
                        <small style="color:#888;">Check the items used and enter quantity.</small>
                        <div id="selected-spareparts-display" style="margin-top:8px;display:none;">
                            <p style="font-size:.8rem;font-weight:600;color:#555;margin-bottom:6px;">Selected:</p>
                            <div id="sp-list" style="display:flex;flex-wrap:wrap;gap:6px;"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Before Photo</label>
                            <input type="file" id="before_photo" class="form-control" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label class="form-label">After Photo</label>
                            <input type="file" id="after_photo" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Customer Notes</label>
                        <textarea id="customer_notes" class="form-control" rows="3" placeholder="Notes from customer..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f0f0f0;padding:16px 24px;display:flex;justify-content:flex-end;gap:8px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveBtn" onclick="saveReport()">
                    <i class="fa-solid fa-floppy-disk"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="modalBackdrop" onclick="closeModal()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1040;"></div>

<style>
.modal { display:none;position:fixed;inset:0;z-index:1050;justify-content:center;align-items:center; }
.modal.show { display:flex; }
.modal-dialog { width:100%;max-width:800px;margin:20px; }
.modal-content { background:#fff; }
.modal-backdrop.show { display:block !important; }
.sp-item { display:flex;align-items:center;gap:10px;padding:6px 10px;border-radius:6px;transition:background .15s; }
.sp-item:hover { background:#f8f9fa; }
.sp-item input[type="checkbox"] { width:18px;height:18px;accent-color:var(--primary);cursor:pointer; }
.sp-item input[type="number"] { width:60px;padding:4px 6px;font-size:.8rem;border:1px solid #ddd;border-radius:6px;text-align:center; }
.sp-label { flex:1;font-size:.85rem;color:#444;cursor:pointer; }
.sp-tag { display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:.75rem;background:#e8f5e9;color:#2e7d32; }
.sp-tag .remove-sp { cursor:pointer;font-weight:700;color:#c62828;margin-left:2px; }
</style>
@endsection

@push('scripts')
<script>
let srTable;
let selectedSpareparts = [];
let ckFindings = null;
let ckActions = null;
let ckInitialized = false;

$(document).ready(function() {
    srTable = $('#sr-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("admin.service-reports.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'wo_number', name: 'workOrder.wo_number' },
            { data: 'technician_name', name: 'technician.full_name' },
            { data: 'findings_truncated', name: 'findings' },
            { data: 'actions_taken_truncated', name: 'actions_taken' },
            { data: 'spareparts_summary', name: 'spareparts_used', orderable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    loadWorkOrders();
    loadTechnicians();
    loadSpareparts();
});

function loadWorkOrders() {
    const sel = $('#work_order_id');
    sel.html('<option value="">-- Select Work Order --</option>');
    $.get('{{ route("admin.work-orders.data") }}', function(res) {
        if (res.data) {
            res.data.forEach(wo => {
                sel.append(`<option value="${wo.id}">${e(wo.wo_number)} - ${e(wo.customer_name || '')}</option>`);
            });
        }
    });
}

function loadTechnicians() {
    const sel = $('#technician_id');
    sel.html('<option value="">-- Select Technician --</option>');
    $.get('{{ route("admin.technicians.data") }}', function(res) {
        if (res.data) {
            res.data.forEach(t => {
                sel.append(`<option value="${t.id}">${e(t.full_name)}</option>`);
            });
        }
    });
}

function loadSpareparts() {
    $.get('{{ route("admin.spareparts.data") }}', function(res) {
        const container = $('#spareparts-container');
        container.empty();
        if (!res.data || res.data.length === 0) {
            container.html('<p style="color:#999;font-size:.85rem;">No spare parts in inventory.</p>');
            return;
        }
        res.data.forEach(sp => {
            const checked = selectedSpareparts.some(s => s.id === sp.id) ? 'checked' : '';
            const qty = selectedSpareparts.find(s => s.id === sp.id)?.qty || 1;
            container.append(`
                <div class="sp-item">
                    <input type="checkbox" class="sp-checkbox" value="${sp.id}" ${checked}>
                    <span class="sp-label">${e(sp.name)} ${sp.brand ? '(' + e(sp.brand) + ')' : ''} - RM ${sp.price ?? 0} (stock: ${sp.stock_quantity ?? 0})</span>
                    <input type="number" class="sp-qty" value="${qty}" min="0" ${checked ? '' : 'disabled'} style="width:55px;">
                </div>
            `);
        });
        $('.sp-checkbox').on('change', function() {
            const qtyInput = $(this).closest('.sp-item').find('.sp-qty');
            qtyInput.prop('disabled', !$(this).is(':checked'));
            if (!$(this).is(':checked')) qtyInput.val(0);
            updateSelectedSpareparts();
        });
        $('.sp-qty').on('input', function() {
            updateSelectedSpareparts();
        });
        renderSelectedSpareparts();
    });
}

function updateSelectedSpareparts() {
    selectedSpareparts = [];
    $('.sp-checkbox:checked').each(function() {
        const id = parseInt($(this).val());
        const qty = parseInt($(this).closest('.sp-item').find('.sp-qty').val()) || 1;
        const name = $(this).closest('.sp-item').find('.sp-label').text().trim();
        selectedSpareparts.push({ id, name, qty });
    });
    renderSelectedSpareparts();
}

function renderSelectedSpareparts() {
    const display = $('#selected-spareparts-display');
    const list = $('#sp-list');
    list.empty();
    if (selectedSpareparts.length === 0) {
        display.hide();
        return;
    }
    display.show();
    selectedSpareparts.forEach(sp => {
        list.append(`<span class="sp-tag">${e(sp.name)} x${sp.qty} <span class="remove-sp" onclick="removeSp(${sp.id})">&times;</span></span>`);
    });
}

function removeSp(id) {
    $(`.sp-checkbox[value="${id}"]`).prop('checked', false).trigger('change');
}

function openModal(editId) {
    $('#srModal').addClass('show');
    $('#modalBackdrop').addClass('show');
    $('#modalTitle').text('Add Service Report');
    $('#saveBtn').html('<i class="fa-solid fa-floppy-disk"></i> Save');
    $('#srForm')[0].reset();
    $('#sr_id').val('');
    selectedSpareparts = [];
    if (ckFindings) ckFindings.setData('');
    if (ckActions) ckActions.setData('');

    loadSpareparts();

    if (!ckInitialized) {
        setTimeout(() => {
            initCKEditor();
            ckInitialized = true;
        }, 500);
    }

    if (editId) {
        $('#modalTitle').text('Edit Service Report');
        $('#saveBtn').html('<i class="fa-solid fa-pen"></i> Update');
        $.get('/admin/service-reports/' + editId, function(res) {
            const r = res.data;
            $('#sr_id').val(r.id);
            $('#work_order_id').val(r.work_order_id);
            $('#technician_id').val(r.technician_id);
            if (ckFindings && r.findings) ckFindings.setData(r.findings);
            if (ckActions && r.actions_taken) ckActions.setData(r.actions_taken);
            $('#customer_notes').val(r.customer_notes || '');

            if (r.spareparts_used) {
                selectedSpareparts = r.spareparts_used.map(sp => ({
                    id: sp.id,
                    name: sp.name || 'Item #' + sp.id,
                    qty: sp.qty || 1
                }));
                loadSpareparts();
            }
        });
    }
}

function closeModal() {
    $('#srModal').removeClass('show');
    $('#modalBackdrop').removeClass('show');
}

function saveReport() {
    const id = $('#sr_id').val();
    const isEdit = !!id;

    const sparepartsData = selectedSpareparts.map(sp => ({
        id: sp.id,
        qty: sp.qty
    }));

    const data = {
        work_order_id: $('#work_order_id').val(),
        technician_id: $('#technician_id').val(),
        findings: ckFindings ? ckFindings.getData() : '',
        actions_taken: ckActions ? ckActions.getData() : '',
        spareparts_used: JSON.stringify(sparepartsData),
        before_photo: null,
        after_photo: null,
        customer_notes: $('#customer_notes').val() || null,
    };

    if (!data.work_order_id || !data.technician_id) {
        Swal.fire('Validation Error', 'Work Order and Technician are required.', 'warning');
        return;
    }

    const url = isEdit ? '/admin/service-reports/' + id : '/admin/service-reports';
    const method = isEdit ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        method: method,
        data: { ...data, _token: '{{ csrf_token() }}' },
        success: function() {
            Swal.fire({ icon: 'success', title: isEdit ? 'Updated!' : 'Created!', timer: 1500, showConfirmButton: false });
            closeModal();
            srTable.ajax.reload();
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
        title: 'Delete Service Report?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Delete'
    }).then(r => {
        if (r.isConfirmed) {
            $.ajax({
                url: '/admin/service-reports/' + id,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    srTable.ajax.reload();
                    Swal.fire('Deleted!', 'Service report has been deleted.', 'success');
                }
            });
        }
    });
});

function initCKEditor() {
    if (typeof ClassicEditor === 'undefined') return;

    const findingsEl = document.querySelector('#findings');
    const actionsEl = document.querySelector('#actions_taken');

    if (!ckFindings && findingsEl) {
        ClassicEditor.create(findingsEl, {
            toolbar: ['heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', '|', 'link', 'blockQuote', '|', 'undo', 'redo'],
            heading: { options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                { model: 'heading3', view: 'h3', title: 'Heading', class: 'ck-heading_heading3' },
            ]},
        }).then(editor => { ckFindings = editor; }).catch(() => {});
    }

    if (!ckActions && actionsEl) {
        ClassicEditor.create(actionsEl, {
            toolbar: ['bold', 'italic', 'bulletedList', '|', 'undo', 'redo'],
        }).then(editor => { ckActions = editor; }).catch(() => {});
    }
}

function e(str) {
    if (!str) return '';
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
</script>
@endpush
