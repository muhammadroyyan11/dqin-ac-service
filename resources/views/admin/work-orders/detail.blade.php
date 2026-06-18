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
        <h2>{{ $workOrder->wo_number }}</h2>
        <p>{{ str_replace('_', ' ', ucfirst($workOrder->service_type)) }} &middot; {{ $workOrder->created_at->format('d M Y H:i') }}</p>
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
        <span class="badge {{ $statusClass }}">{{ str_replace('_', ' ', ucfirst($workOrder->status)) }}</span>
        <span class="badge {{ $priorityClass }}" style="margin-left:6px;">{{ ucfirst($workOrder->priority) }}</span>
    </div>
</div>

<div class="detail-grid">
    <div class="card">
        <div class="card-header"><h5><i class="fa-solid fa-user" style="color:var(--primary);"></i> Customer</h5></div>
        <div class="card-body">
            @if($workOrder->customer)
            <div class="info-rows">
                <div><strong style="font-size:1.05rem;">{{ $workOrder->customer->full_name }}</strong></div>
                @if($workOrder->customer->phone)
                <div class="info-row"><i class="fa-solid fa-phone"></i>{{ $workOrder->customer->phone }}</div>
                @endif
                @if($workOrder->customer->email)
                <div class="info-row"><i class="fa-solid fa-envelope"></i>{{ $workOrder->customer->email }}</div>
                @endif
                @if($workOrder->customer->address)
                <div class="info-row" style="align-items:flex-start;">
                    <i class="fa-solid fa-location-dot" style="margin-top:3px;"></i>
                    <span>{{ $workOrder->customer->address }}{{ $workOrder->customer->city ? ', ' . $workOrder->customer->city : '' }}</span>
                </div>
                @endif
                @if($workOrder->customerUnit)
                <hr style="border:none;border-top:1px solid #eee;margin:6px 0;">
                <div class="info-row"><i class="fa-solid fa-snowflake"></i>{{ $workOrder->customerUnit->brand }} {{ $workOrder->customerUnit->type }} ({{ $workOrder->customerUnit->pk }} PK)</div>
                @endif
            </div>
            @else
            <p style="color:#999;">No customer data.</p>
            @endif
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h5><i class="fa-solid fa-calendar" style="color:var(--primary);"></i> Schedule & Estimate</h5></div>
        <div class="card-body">
            <div class="info-rows">
                <div class="info-row-split"><span>Scheduled</span><strong>{{ $workOrder->scheduled_date ? \Carbon\Carbon::parse($workOrder->scheduled_date)->format('d M Y H:i') : '-' }}</strong></div>
                <div class="info-row-split"><span>Completed</span><strong>{{ $workOrder->completed_date ? \Carbon\Carbon::parse($workOrder->completed_date)->format('d M Y H:i') : '-' }}</strong></div>
                <hr style="border:none;border-top:1px solid #eee;margin:2px 0;">
                <div class="info-row-split"><span>Estimate</span><strong style="font-size:1.1rem;color:var(--primary);">RM {{ number_format($workOrder->total_estimate ?? 0, 2) }}</strong></div>
                <div class="info-row-split"><span>Created</span><strong>{{ $workOrder->created_at->format('d M Y H:i') }}</strong></div>
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom:24px;">
    <div class="card-header"><h5><i class="fa-solid fa-align-left" style="color:var(--primary);"></i> Description</h5></div>
    <div class="card-body">
        <div class="desc-content">{!! $workOrder->description ?: '<em style="color:#999;">No description</em>' !!}</div>
    </div>
</div>

@if($workOrder->notes)
<div class="card" style="margin-bottom:24px;">
    <div class="card-header"><h5><i class="fa-solid fa-sticky-note" style="color:var(--primary);"></i> Notes</h5></div>
    <div class="card-body"><div class="desc-content">{!! $workOrder->notes !!}</div></div>
</div>
@endif

<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h5><i class="fa-solid fa-users-gear" style="color:var(--primary);"></i> Technicians</h5>
        @if($workOrder->status !== 'completed' && $workOrder->status !== 'cancelled')
        <button class="btn btn-sm btn-success" onclick="markAllComplete()"><i class="fa-solid fa-check-double"></i> Complete All</button>
        @endif
    </div>
    <div class="card-body">
        @if($workOrder->technicians->count() > 0)
        <div class="tech-chips">
            @foreach($workOrder->technicians as $tech)
            <div class="tech-chip {{ $tech->pivot->status === 'completed' ? 'chip-done' : ($tech->pivot->status === 'in_progress' ? 'chip-progress' : 'chip-pending') }}">
                <div class="tech-avatar">{{ substr($tech->full_name, 0, 1) }}</div>
                <div>
                    <strong>{{ $tech->full_name }}</strong>
                    @if($tech->pivot->is_captain)<span class="captain-badge">Captain</span>@endif
                    <div class="chip-status">{{ ucfirst($tech->pivot->status) }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p style="color:#999;text-align:center;padding:20px;">No technicians assigned.</p>
        @endif
    </div>
</div>

<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h5><i class="fa-solid fa-clock-rotate-left" style="color:var(--primary);"></i> Progress Timeline</h5>
    </div>
    <div class="card-body">
        @php
            $logs = $workOrder->progressLogs->sortByDesc('created_at');
        @endphp
        @if($logs->count() > 0)
        <div class="timeline">
            @foreach($logs as $log)
            <div class="timeline-item">
                <div class="timeline-dot {{ $log->status === 'completed' ? 'dot-done' : ($log->status === 'in_progress' ? 'dot-progress' : 'dot-pending') }}"></div>
                <div class="timeline-content">
                    <div class="timeline-head">
                        <span class="timeline-status {{ $log->status === 'completed' ? 'text-success' : ($log->status === 'in_progress' ? 'text-info' : 'text-secondary') }}">
                            {{ ucfirst($log->status) }}
                        </span>
                        <span class="timeline-time">{{ $log->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="timeline-body">
                        <strong>{{ $log->technician?->full_name ?? 'Unknown' }}</strong>
                        @if($log->note && $log->note !== 'Technician assigned' && $log->note !== 'Work order completed')
                        <p>{{ $log->note }}</p>
                        @endif
                    </div>
                    @if($log->user)
                    <div class="timeline-by">by {{ $log->user->name }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p style="color:#999;text-align:center;padding:20px;">No progress updates yet.</p>
        @endif
    </div>
</div>

@if($workOrder->status !== 'completed' && $workOrder->status !== 'cancelled')
<div class="card" style="margin-bottom:24px;">
    <div class="card-header">
        <h5><i class="fa-solid fa-pen-to-square" style="color:var(--primary);"></i> Update Progress</h5>
    </div>
    <div class="card-body">
        <form id="progressForm" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
            <div style="flex:1;min-width:180px;">
                <label style="display:block;font-size:.8rem;color:#888;margin-bottom:4px;">Technician</label>
                <select id="progress_technician_id" class="form-control" required>
                    <option value="">-- Select --</option>
                    @foreach($workOrder->technicians as $tech)
                    <option value="{{ $tech->id }}">{{ $tech->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1;min-width:140px;">
                <label style="display:block;font-size:.8rem;color:#888;margin-bottom:4px;">Status</label>
                <select id="progress_status" class="form-control" required>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div style="flex:2;min-width:200px;">
                <label style="display:block;font-size:.8rem;color:#888;margin-bottom:4px;">Note</label>
                <input type="text" id="progress_note" class="form-control" placeholder="Progress note...">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-rotate"></i> Update</button>
        </form>
    </div>
</div>
@endif

@if($workOrder->serviceReports->count() > 0)
<div class="card" style="margin-bottom:24px;">
    <div class="card-header"><h5><i class="fa-solid fa-file-lines" style="color:var(--primary);"></i> Service Reports ({{ $workOrder->serviceReports->count() }})</h5></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="dt-table" style="width:100%;">
                <thead><tr><th>Technician</th><th>Findings</th><th>Actions Taken</th><th>Date</th></tr></thead>
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
    <button class="btn btn-success" onclick="markAllComplete()"><i class="fa-solid fa-check-circle"></i> Complete Work Order</button>
</div>
@endif

<style>
.detail-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;background:#fff;border-radius:14px;padding:20px 24px;margin-bottom:24px;box-shadow:0 1px 4px rgba(0,0,0,.06)}
.detail-header-left h2{margin:0 0 4px;font-size:1.25rem;font-weight:700;color:#333}
.detail-header-left p{margin:0;color:#888;font-size:.85rem}
.detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px}
.info-rows{display:flex;flex-direction:column;gap:10px}
.info-row{display:flex;align-items:center;gap:8px;font-size:.875rem;color:#555}
.info-row i{width:16px;color:#888}
.info-row-split{display:flex;justify-content:space-between;font-size:.875rem}
.info-row-split span{color:#888}
.desc-content{font-size:.875rem;line-height:1.7;color:#444}

.tech-chips{display:flex;flex-wrap:wrap;gap:12px}
.tech-chip{display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:12px;border:1px solid #eee;background:#fafafa;min-width:180px}
.tech-avatar{width:36px;height:36px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;flex-shrink:0}
.chip-done{border-left:3px solid #28a745;background:#f0fff4}
.chip-progress{border-left:3px solid #17a2b8}
.chip-pending{border-left:3px solid #6c757d}
.captain-badge{display:inline-block;font-size:.6rem;font-weight:600;text-transform:uppercase;background:var(--primary);color:#fff;padding:1px 7px;border-radius:10px;margin-left:4px;vertical-align:middle}
.chip-status{font-size:.75rem;color:#888;margin-top:2px}

.timeline{position:relative;padding-left:32px}
.timeline::before{content:'';position:absolute;left:14px;top:4px;bottom:4px;width:2px;background:#e0e0e0;border-radius:2px}
.timeline-item{position:relative;padding-bottom:24px}
.timeline-item:last-child{padding-bottom:0}
.timeline-dot{position:absolute;left:-22px;top:4px;width:14px;height:14px;border-radius:50%;border:3px solid;background:#fff;z-index:1}
.dot-done{border-color:#28a745}
.dot-progress{border-color:#17a2b8}
.dot-pending{border-color:#6c757d}
.timeline-content{background:#fafafa;border:1px solid #eee;border-radius:10px;padding:12px 16px}
.timeline-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px}
.timeline-status{font-weight:700;font-size:.8rem;text-transform:uppercase}
.text-success{color:#28a745}.text-info{color:#17a2b8}.text-secondary{color:#6c757d}
.timeline-time{font-size:.75rem;color:#999}
.timeline-body{font-size:.85rem;color:#444}
.timeline-body p{margin:4px 0 0;color:#666}
.timeline-by{font-size:.7rem;color:#aaa;margin-top:4px}
</style>
@endsection

@push('scripts')
<script>
$('#progressForm').on('submit', function(e) {
    e.preventDefault();
    const technicianId = $('#progress_technician_id').val();
    const status = $('#progress_status').val();
    const note = $('#progress_note').val();

    if (!technicianId) {
        Swal.fire('Validation', 'Select a technician.', 'warning');
        return;
    }

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
});

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
