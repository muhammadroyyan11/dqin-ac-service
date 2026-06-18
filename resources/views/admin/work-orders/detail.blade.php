@extends('admin.layouts.admin')

@section('title', 'WO #'.$workOrder->wo_number.' — DQIN AC Admin')
@section('page-title', 'Work Order #'.$workOrder->wo_number)

@section('content')
<div style="margin-bottom:20px;">
    <a href="{{ route('admin.work-orders.index') }}" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--primary);">
            <i class="fa-solid fa-clipboard-list"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $workOrder->wo_number }}</h3>
            <p>Work Order Number</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#6f42c1;">
            <i class="fa-solid fa-tag"></i>
        </div>
        <div class="stat-info">
            <h3>{{ str_replace('_', ' ', ucfirst($workOrder->service_type)) }}</h3>
            <p>Service Type</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#0d6efd;">
            <i class="fa-solid fa-flag"></i>
        </div>
        <div class="stat-info">
            <h3>{!! $workOrder->status === 'completed' ? '<span class="badge badge-success">Completed</span>' : ($workOrder->status === 'in_progress' ? '<span class="badge badge-info">In Progress</span>' : ($workOrder->status === 'cancelled' ? '<span class="badge badge-danger">Cancelled</span>' : '<span class="badge badge-warning">Pending</span>')) !!}</h3>
            <p>Status</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dc3545;">
            <i class="fa-solid fa-exclamation-triangle"></i>
        </div>
        <div class="stat-info">
            <h3>{!! $workOrder->priority === 'emergency' ? '<span class="badge badge-danger">Emergency</span>' : ($workOrder->priority === 'high' ? '<span class="badge badge-warning">High</span>' : ($workOrder->priority === 'low' ? '<span class="badge badge-secondary">Low</span>' : '<span class="badge badge-info">Normal</span>')) !!}</h3>
            <p>Priority</p>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
    {{-- Customer Info --}}
    <div class="card">
        <div class="card-header">
            <h5><i class="fa-solid fa-user"></i> Customer Information</h5>
        </div>
        <div class="card-body">
            @if($workOrder->customer)
            <table style="width:100%;font-size:.875rem;">
                <tr><td style="padding:6px 0;color:#888;width:120px;">Name</td><td style="padding:6px 0;font-weight:500;">{{ $workOrder->customer->full_name }}</td></tr>
                <tr><td style="padding:6px 0;color:#888;">Phone</td><td style="padding:6px 0;">{{ $workOrder->customer->phone ?? '-' }}</td></tr>
                <tr><td style="padding:6px 0;color:#888;">Email</td><td style="padding:6px 0;">{{ $workOrder->customer->email ?? '-' }}</td></tr>
                <tr><td style="padding:6px 0;color:#888;">Address</td><td style="padding:6px 0;">{{ $workOrder->customer->address ?? '-' }}</td></tr>
                <tr><td style="padding:6px 0;color:#888;">City</td><td style="padding:6px 0;">{{ $workOrder->customer->city ?? '-' }}</td></tr>
            </table>
            @else
            <p style="color:#999;">No customer data.</p>
            @endif
        </div>
    </div>

    {{-- Work Order Info --}}
    <div class="card">
        <div class="card-header">
            <h5><i class="fa-solid fa-info-circle"></i> Order Details</h5>
        </div>
        <div class="card-body">
            <table style="width:100%;font-size:.875rem;">
                <tr><td style="padding:6px 0;color:#888;width:140px;">Scheduled</td><td style="padding:6px 0;font-weight:500;">{{ $workOrder->scheduled_date ? \Carbon\Carbon::parse($workOrder->scheduled_date)->format('d M Y H:i') : '-' }}</td></tr>
                <tr><td style="padding:6px 0;color:#888;">Completed</td><td style="padding:6px 0;">{{ $workOrder->completed_date ? \Carbon\Carbon::parse($workOrder->completed_date)->format('d M Y H:i') : '-' }}</td></tr>
                <tr><td style="padding:6px 0;color:#888;">Estimate</td><td style="padding:6px 0;"><strong>RM {{ number_format($workOrder->total_estimate ?? 0, 2) }}</strong></td></tr>
                <tr><td style="padding:6px 0;color:#888;">Created</td><td style="padding:6px 0;">{{ $workOrder->created_at->format('d M Y H:i') }}</td></tr>
            </table>

            @if($workOrder->customerUnit)
            <div style="margin-top:12px;padding-top:12px;border-top:1px solid #f0f0f0;">
                <p style="font-weight:600;font-size:.85rem;margin-bottom:6px;">Customer Unit</p>
                <p style="font-size:.85rem;color:#555;">{{ $workOrder->customerUnit->brand }} {{ $workOrder->customerUnit->type }} ({{ $workOrder->customerUnit->pk }} PK)</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Description & Notes --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
    <div class="card">
        <div class="card-header">
            <h5><i class="fa-solid fa-align-left"></i> Description</h5>
        </div>
        <div class="card-body">
            <div style="font-size:.875rem;line-height:1.7;color:#444;">
                {!! $workOrder->description ?: '<em style="color:#999;">No description</em>' !!}
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5><i class="fa-solid fa-sticky-note"></i> Notes</h5>
        </div>
        <div class="card-body">
            <div style="font-size:.875rem;line-height:1.7;color:#444;">
                {!! $workOrder->notes ?: '<em style="color:#999;">No notes</em>' !!}
            </div>
        </div>
    </div>
</div>

{{-- Assigned Technicians --}}
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h5><i class="fa-solid fa-users-gear"></i> Assigned Technicians</h5>
        <div>
            @if($workOrder->status !== 'completed' && $workOrder->status !== 'cancelled')
            <button class="btn btn-sm btn-success" onclick="markAllComplete()">
                <i class="fa-solid fa-check-double"></i> Mark All Complete
            </button>
            @endif
        </div>
    </div>
    <div class="card-body">
        @if($workOrder->technicians->count() > 0)
        <div style="display:grid;gap:16px;">
            @foreach($workOrder->technicians as $tech)
            <div class="tech-card" style="border:1px solid #eee;border-radius:12px;padding:16px;background:#fafafa;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <strong style="font-size:.95rem;">{{ $tech->full_name }}</strong>
                        @if($tech->pivot->is_captain)
                        <span class="badge badge-captain" style="background:var(--primary);color:#fff;font-size:.7rem;padding:2px 10px;border-radius:12px;">Captain</span>
                        @endif
                    </div>
                    <div>
                        @if($tech->pivot->status === 'completed')
                        <span class="badge badge-success">Completed</span>
                        @elseif($tech->pivot->status === 'in_progress')
                        <span class="badge badge-info">In Progress</span>
                        @else
                        <span class="badge badge-secondary">Assigned</span>
                        @endif
                    </div>
                </div>

                @if($workOrder->status !== 'completed' && $workOrder->status !== 'cancelled')
                <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                    <select class="progress-status form-control" data-tech-id="{{ $tech->id }}" style="width:auto;min-width:140px;padding:6px 10px;font-size:.8rem;">
                        <option value="assigned" {{ $tech->pivot->status === 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="in_progress" {{ $tech->pivot->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $tech->pivot->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    <input type="text" class="progress-note form-control" data-tech-id="{{ $tech->id }}" placeholder="Progress note..." value="{{ $tech->pivot->progress_note ?? '' }}" style="width:auto;flex:1;min-width:200px;padding:6px 10px;font-size:.8rem;">
                    <button class="btn btn-sm btn-primary" onclick="updateProgress({{ $tech->id }})">
                        <i class="fa-solid fa-rotate"></i> Update
                    </button>
                </div>
                @endif

                @if($tech->pivot->progress_note)
                <div style="margin-top:8px;font-size:.8rem;color:#666;background:#fff;padding:8px 12px;border-radius:8px;border:1px solid #eee;">
                    <strong>Note:</strong> {{ $tech->pivot->progress_note }}
                </div>
                @endif

                @if($tech->pivot->completed_at)
                <div style="margin-top:4px;font-size:.75rem;color:#999;">
                    Completed at: {{ \Carbon\Carbon::parse($tech->pivot->completed_at)->format('d M Y H:i') }}
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <p style="color:#999;text-align:center;padding:20px;">No technicians assigned to this work order.</p>
        @endif
    </div>
</div>

{{-- Service Reports --}}
@if($workOrder->serviceReports->count() > 0)
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h5><i class="fa-solid fa-file-lines"></i> Service Reports</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table style="width:100%;font-size:.85rem;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Technician</th>
                        <th>Findings</th>
                        <th>Actions Taken</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workOrder->serviceReports as $report)
                    <tr>
                        <td>{{ $report->id }}</td>
                        <td>{{ $report->technician?->full_name ?? '-' }}</td>
                        <td>{{ Str::limit($report->findings ?? '-', 50) }}</td>
                        <td>{{ Str::limit($report->actions_taken ?? '-', 50) }}</td>
                        <td>{{ $report->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if($workOrder->status !== 'completed' && $workOrder->status !== 'cancelled')
<div style="margin-top:20px;display:flex;gap:8px;justify-content:flex-end;">
    <button class="btn btn-success" onclick="markAllComplete()">
        <i class="fa-solid fa-check-circle"></i> Complete Work Order
    </button>
</div>
@endif

<style>
.tech-card { transition: box-shadow .2s; }
.tech-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,.06); }
.badge-captain { background: var(--primary); color: #fff; font-size: .7rem; padding: 2px 10px; border-radius: 12px; }
</style>
@endsection

@push('scripts')
<script>
function updateProgress(technicianId) {
    const status = $(`.progress-status[data-tech-id="${technicianId}"]`).val();
    const note = $(`.progress-note[data-tech-id="${technicianId}"]`).val();

    $.ajax({
        url: '{{ route("admin.work-orders.update-progress", $workOrder->id) }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            technician_id: technicianId,
            status: status,
            progress_note: note
        },
        success: function() {
            Swal.fire({ icon: 'success', title: 'Progress Updated!', timer: 1500, showConfirmButton: false });
            setTimeout(() => location.reload(), 1500);
        },
        error: function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Failed to update progress.', 'error');
        }
    });
}

function markAllComplete() {
    Swal.fire({
        title: 'Complete Work Order?',
        text: 'All technicians will be marked as completed.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        confirmButtonText: 'Yes, complete all'
    }).then(r => {
        if (r.isConfirmed) {
            $.ajax({
                url: '{{ route("admin.work-orders.complete", $workOrder->id) }}',
                method: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    Swal.fire({ icon: 'success', title: 'Completed!', timer: 1500, showConfirmButton: false });
                    setTimeout(() => location.reload(), 1500);
                },
                error: function() {
                    Swal.fire('Error', 'Failed to complete work order.', 'error');
                }
            });
        }
    });
}
</script>
@endpush
