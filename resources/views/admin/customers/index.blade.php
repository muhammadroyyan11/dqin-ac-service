@extends('admin.layouts.admin')

@section('title', 'Customer Management — DQIN AC Admin')
@section('page-title', 'Customer Management')

@section('content')
<div x-data="customerManager()">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <div>
            <p style="color:#888;font-size:.85rem;">Manage all registered customers</p>
        </div>
        <button @click="openModal('add')" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Customer
        </button>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="customers-table" class="dt-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>City</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div x-show="modalOpen" 
         x-cloak
         @keydown.escape.window="modalOpen = false"
         style="position:fixed;inset:0;z-index:2000;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.5);padding:16px;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <div @click.outside="modalOpen = false"
             style="background:#fff;border-radius:12px;width:100%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.3);padding:24px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <h3 style="margin:0;font-size:1.1rem;font-weight:700;" x-text="mode === 'add' ? 'Add Customer' : 'Edit Customer'"></h3>
                <button @click="modalOpen = false" style="background:none;border:none;font-size:1.2rem;cursor:pointer;color:#888;">&times;</button>
            </div>
            <form @submit.prevent="submitForm">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name <span style="color:red;">*</span></label>
                        <input type="text" x-model="form.full_name" required class="form-control" placeholder="Enter full name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone <span style="color:red;">*</span></label>
                        <input type="text" x-model="form.phone" required class="form-control" placeholder="Enter phone number">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" x-model="form.email" class="form-control" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" x-model="form.city" class="form-control" placeholder="Enter city">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea x-model="form.address" class="form-control" rows="2" placeholder="Enter address"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea x-model="form.notes" class="form-control" rows="2" placeholder="Additional notes"></textarea>
                </div>
                <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:20px;padding-top:16px;border-top:1px solid #f0f0f0;">
                    <button type="button" @click="modalOpen = false" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary" x-text="mode === 'add' ? 'Save' : 'Update'"></button>
                </div>
            </form>
            <div x-show="errors.length" style="margin-top:12px;color:#dc3545;font-size:.85rem;">
                <template x-for="err in errors" :key="err">
                    <div><i class="fa-solid fa-circle-exclamation"></i> <span x-text="err"></span></div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function customerManager() {
    return {
        modalOpen: false,
        mode: 'add',
        editId: null,
        errors: [],
        form: { full_name: '', phone: '', email: '', address: '', city: '', notes: '', is_active: true },
        openModal(mode, data = null) {
            this.mode = mode;
            this.errors = [];
            if (mode === 'add') {
                this.form = { full_name: '', phone: '', email: '', address: '', city: '', notes: '', is_active: true };
                this.editId = null;
            } else {
                this.form = { ...data };
                this.editId = data.id;
            }
            this.modalOpen = true;
        },
        submitForm() {
            this.errors = [];
            const url = this.mode === 'add' ? '{{ route("admin.customers.store") }}' : `/admin/customers/${this.editId}`;
            const method = this.mode === 'add' ? 'POST' : 'PUT';
            fetch(url, {
                method,
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                body: JSON.stringify(this.form)
            })
            .then(r => r.json())
            .then(d => {
                if (d.errors) { this.errors = Object.values(d.errors).flat(); return; }
                this.modalOpen = false;
                $('#customers-table').DataTable().ajax.reload();
                Toast.fire({ icon: 'success', title: this.mode === 'add' ? 'Customer created' : 'Customer updated' });
            })
            .catch(() => { Toast.fire({ icon: 'error', title: 'Something went wrong' }); });
        }
    };
}

$(document).ready(function() {
    $('#customers-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("admin.customers.data") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'full_name', name: 'full_name' },
            { data: 'phone', name: 'phone' },
            { data: 'email', name: 'email' },
            { data: 'city', name: 'city' },
            { data: 'is_active', name: 'is_active' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language: { search: 'Search:', lengthMenu: 'Show _MENU_ entries', info: 'Showing _START_ to _END_ of _TOTAL_ entries' }
    });
});

function editCustomer(id) {
    fetch(`/admin/customers/${id}`, { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(d => Alpine.$data(document.querySelector('[x-data]')).openModal('edit', d));
}

function deleteCustomer(id) {
    Swal.fire({ title: 'Confirm?', text: 'This will soft-delete this customer.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545', confirmButtonText: 'Delete' })
        .then(r => {
            if (r.isConfirmed) {
                fetch(`/admin/customers/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
                    .then(() => {
                        $('#customers-table').DataTable().ajax.reload();
                        Toast.fire({ icon: 'success', title: 'Customer deleted' });
                    });
            }
        });
}
</script>
@endpush
