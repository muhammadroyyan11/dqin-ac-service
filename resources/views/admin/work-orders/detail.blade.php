@extends('admin.layouts.admin')

@section('title', 'WO #'.$workOrder->wo_number.' — DQIN AC Admin')
@section('page-title', '')

@section('content')
<div style="margin-bottom:20px;">
    <a href="{{ route('admin.work-orders.index') }}" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>
</div>

<div class="detail-header">
    <div class="detail-header-left">
        <h2 style="margin:0 0 4px;">{{ $workOrder->wo_number }}</h2>
        <p style="margin:0;color:#888;font-size:.85rem;">{{ str_replace('_', ' ', ucfirst($workOrder->service_type)) }} &middot; {{ $workOrder->created_at->format('d M Y H:i') }}</p>
    </div>
    <div class="detail-header-right">
        @php
            $statusClass = match($workOrder->status) {
                'completed' => 'badge-success',
                'in_progress' => 'badge-info',
                'cancelled' => 'badge-danger',
                default => 'badge-warning',
            };
            $priorityClass = match($workOrder->priority) {
                'emergency' => 'badge-danger',
                'high' => 'badge-warning',
                'low' => 'badge-secondary',
                default => 'badge-info',
            };
        @endphp
        <span class="badge {{ $statusClass }}" style="font-size:.8rem;padding:4px 14px;">{{ str_replace('_', ' ', ucfirst($workOrder->status)) }}</span>
        <span class="badge {{ $priorityClass }}" style="font-size:.8rem;padding:4px 14px;margin-left:6px;">{{ ucfirst($workOrder->priority) }}</span>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
    <div class="card">
        <div class="card-header">
            <h5><i class="fa-solid fa-user" style="color:var(--primary);"></i> Customer</h5>
        </div>
        <div class="card-body">
            @if($workOrder->customer)
            <div style="display:flex;flex-direction:column;gap:10px;">
                <div>
                    <strong style="font-size:1.05rem;">{{ $workOrder->customer->full_name }}</strong>
                </div>
                @if($workOrder->customer->phone)
                <div style="display:flex;align-items:center;gap:8px;font-size:.875rem;color:#555;">
                    <i class="fa-solid fa-phone" style="width:16px;color:#888;"></i> {{ $workOrder->customer->phone }}
                </div>
                @endif
                @if($workOrder->customer->email)
                <div style="display:flex;align-items:center;gap:8px;font-size:.875rem;color:#555;">
                    <i class="fa-solid fa-envelope" style="width:16px;color:#888;"></i> {{ $workOrder->customer->email }}
                </div>
                @endif
                @if($workOrder->customer->address)
                <div style="display:flex;align-items:flex-start;gap:8px;font-size:.875rem;color:#555;">
                    <i class="fa-solid fa-location-dot" style="width:16px;color:#888;margin-top:3px;"></i>
                    <span>{{ $workOrder->customer->address }}{{ $workOrder->customer->city ? ', ' . $workOrder->customer->city : '' }}</span>
                </div>
                @endif
                @if($workOrder->customerUnit)
                <hr style="border:none;border-top:1px solid #eee;margin:6px 0;">
                <div style="display:flex;align-items:center;gap:8px;font-size:.875rem;color:#555;">
                    <i class="fa-solid fa-snowflake" style="width:16px;color:#888;"></i>
                    <span>{{ $workOrder->customerUnit->brand }} {{ $workOrder->customerUnit->type }} ({{ $workOrder->customerUnit->pk }} PK)</span>
                </div>
                @endif
            </div>
            @else
            <p style="color:#999;">No customer data.</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5><i class="fa-solid fa-calendar" style="color:var(--primary);"></i> Schedule & Estimate</h5>
        </div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;justify-content:space-between;font-size:.875rem;">
                    <span style="color:#888;">Scheduled</span>
                    <strong>{{ $workOrder->scheduled_date ? \Carbon\Carbon::parse($workOrder->scheduled_date)->format('d M Y H:i') : '-' }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.875rem;">
                    <span style="color:#888;">Completed</span>
                    <strong>{{ $workOrder->completed_date ? \Carbon\Carbon::parse($workOrder->completed_date)->format('d M Y H:i') : '-' }}</strong>
                </div>
                <hr style="border:none;border-top:1px solid #eee;margin:2px 0;">
                <div style="display:flex;justify-content:space-between;font-size:.875rem;">
                    <span style="color:#888;">Total Estimate</span>
                    <strong style="font-size:1.1rem;color:var(--primary);">RM {{ number_format($workOrder->total_estimate ?? 0, 2) }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.875rem;">
                    <span style="color:#888;">Created</span>
                    <strong>{{ $workOrder->created_at->format('d M Y H:i') }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h5><i class="fa-solid fa-align-left" style="color:var(--primary);"></i> Description</h5>
    </div>
    <div class="card-body">
        <div style="font-size:.875rem;line-height:1.7;color:#444;">
            {!! $workOrder->description ?: '<em style="color:#999;">No description</em>' !!}
        </div>
    </div>
</div>

@if($workOrder->notes)
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h5><i class="fa-solid fa-sticky-note" style="color:var(--primary);"></i> Notes</h5>
    </div>
    <div class="card-body">
        <div style="font-size:.875rem;line-height:1.7;color:#444;">
            {!! $workOrder->notes !!}
        </div>
    </div>
</div>
@endif

<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h5><i class="fa-solid fa-users-gear" style="color:var(--primary);"></i> Assigned Technicians</h5>
        @if($workOrder->status !== 'completed' && $workOrder->status !== 'cancelled')
        <button class="btn btn-sm btn-success" onclick="markAllComplete()">
            <i class="fa-solid fa-check-double"></i> Complete All
        </button>
        @endif
    </div>
    <div class="card-body">
        @if($workOrder->technicians->count() > 0)
        <div style="display:grid;gap:16px;">
            @foreach($workOrder->technicians as $tech)
            @php
                $techStatusClass = match($tech->pivot->status) {
                    'completed' => 'tech-done',
                    'in_progress' => 'tech-progress',
                    default => 'tech-pending',
                };
            @endphp
            <div class="tech-card {{ $techStatusClass }}">
                <div class="tech-card-top">
                    <div class="tech-card-user">
                        <div class="tech-avatar">{{ substr($tech->full_name, 0, 1) }}</div>
                        <div>
                            <strong>{{ $tech->full_name }}</strong>
                            @if($tech->pivot->is_captain)
                            <span class="captain-badge">Captain</span>
                            @endif
                        </div>
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
                <div class="tech-controls">
                    <select class="progress-status form-control" data-tech-id="{{ $tech->id }}">
                        <option value="assigned" {{ $tech->pivot->status === 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="in_progress" {{ $tech->pivot->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $tech->pivot->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    <input type="text" class="progress-note form-control" data-tech-id="{{ $tech->id }}" placeholder="Progress note..." value="{{ $tech->pivot->progress_note ?? '' }}">
                    <button class="btn btn-sm btn-primary" onclick="updateProgress({{ $tech->id }})">
                        <i class="fa-solid fa-rotate"></i> Update
                    </button>
                </div>
                @endif

                @if($tech->pivot->progress_note)
                <div class="tech-note">
                    <i class="fa-solid fa-quote-left" style="color:#999;font-size:.7rem;margin-right:4px;"></i>
                    {{ $tech->pivot->progress_note }}
                </div>
                @endif

                @if($tech->pivot->completed_at)
                <div class="tech-completed-at">
                    <i class="fa-solid fa-check-circle" style="color:#28a745;"></i>
                    Completed {{ \Carbon\Carbon::parse($tech->pivot->completed_at)->format('d M Y H:i') }}
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

@if($workOrder->serviceReports->count() > 0)
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h5><i class="fa-solid fa-file-lines" style="color:var(--primary);"></i> Service Reports ({{ $workOrder->serviceReports->count() }})</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="dt-table" style="width:100%;">
                <thead>
                    <tr>
                        <th>Technician</th>
                        <th>Findings</th>
                        <th>Actions Taken</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workOrder->serviceReports as $report)
                    <tr>
                        <td>{{ $report->technician?->full_name ?? '-' }}</td>
                        <td>{{ Str::limit($report->findings ?? '-', 80) }}</td>
                        <td>{{ Str::limit($report->actions_taken ?? '-', 80) }}</td>
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
.detail-header {
    display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;
    background:#fff;border-radius:14px;padding:20px 24px;margin-bottom:24px;
    box-shadow:0 1px 4px rgba(0,0,0,.06);
}
.detail-header-left h2 { font-size:1.25rem;font-weight:700;color:#333; }
.tech-card {
    border:1px solid #eee;border-radius:12px;padding:16px;background:#fafafa;
    transition:box-shadow .2s,border-color .2s;
}
.tech-card:hover { box-shadow:0 2px 10px rgba(0,0,0,.07); }
.tech-card.tech-done { border-left:3px solid #28a745; }
.tech-card.tech-progress { border-left:3px solid #17a2b8; }
.tech-card.tech-pending { border-left:3px solid #6c757d; }
.tech-card-top {
    display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;
}
.tech-card-user {
    display:flex;align-items:center;gap:10px;
}
.tech-avatar {
    width:36px;height:36px;border-radius:50%;background:var(--primary);
    color:#fff;display:flex;align-items:center;justify-content:center;
    font-weight:700;font-size:.9rem;flex-shrink:0;
}
.captain-badge {
    display:inline-block;font-size:.65rem;font-weight:600;text-transform:uppercase;
    background:var(--primary);color:#fff;padding:1px 8px;border-radius:10px;margin-left:6px;vertical-align:middle;
}
.tech-controls {
    display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin-bottom:8px;
}
.tech-controls select { width:auto;min-width:130px;padding:6px 10px;font-size:.8rem; }
.tech-controls input { flex:1;min-width:160px;padding:6px 10px;font-size:.8rem; }
.tech-note {
    font-size:.8rem;color:#666;background:#fff;padding:8px 12px;border-radius:8px;
    border:1px solid #eee;margin-top:8px;
}
.tech-completed-at {
    font-size:.75rem;color:#28a745;margin-top:6px;display:flex;align-items:center;gap:4px;
}
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
