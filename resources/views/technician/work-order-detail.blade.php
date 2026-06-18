<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WO #{{ $workOrder->wo_number }} — DQIN AC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Inter, sans-serif; background: #f5f5f5; color: #333; }
        .topbar {
            background: #164841; color: #fff; padding: 16px 24px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .topbar h2 { font-size: 1.1rem; font-weight: 600; }
        .container { max-width: 800px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.06); margin-bottom: 16px; }
        .card-header { padding: 16px 20px; border-bottom: 1px solid #f0f0f0; font-weight: 600; font-size: .95rem; }
        .card-body { padding: 20px; }
        .info-row { display: flex; padding: 8px 0; border-bottom: 1px solid #f5f5f5; font-size: .88rem; }
        .info-row:last-child { border-bottom: none; }
        .info-label { width: 140px; color: #888; flex-shrink: 0; }
        .info-value { font-weight: 500; }
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; margin-bottom: 6px; font-weight: 500; font-size: .88rem; }
        .form-control {
            width: 100%; padding: 10px 12px; border: 1px solid #ddd;
            border-radius: 8px; font-size: .88rem; background: #fff;
        }
        .form-control:focus { outline: none; border-color: #164841; box-shadow: 0 0 0 3px rgba(22,72,65,.15); }
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 20px; border-radius: 8px; border: none;
            cursor: pointer; font-size: .88rem; font-weight: 600;
            text-decoration: none; transition: all .2s;
        }
        .btn-primary { background: #164841; color: #fff; }
        .btn-primary:hover { background: #0f332e; }
        .btn-success { background: #28a745; color: #fff; }
        .btn-success:hover { background: #218838; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .btn-secondary:hover { background: #5a6268; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: .75rem; font-weight: 600; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .badge-secondary { background: #e2e3e5; color: #383d41; }
        .back-link { display: inline-flex; align-items: center; gap: 6px; color: #164841; text-decoration: none; font-size: .88rem; margin-bottom: 16px; }
        .back-link:hover { text-decoration: underline; }
        .tech-list { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }
        .tech-tag { background: #e8f5e9; color: #2e7d32; padding: 4px 12px; border-radius: 20px; font-size: .82rem; }
        .tech-tag.captain { background: #164841; color: #fff; }
        @media (max-width: 600px) {
            .info-row { flex-direction: column; gap: 2px; }
            .info-label { width: auto; }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div>
            <h2><i class="fa-solid fa-clipboard-list"></i> Work Order #{{ $workOrder->wo_number }}</h2>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:none;border:1px solid rgba(255,255,255,.3);color:#fff;padding:6px 14px;border-radius:6px;cursor:pointer;font-size:.82rem;">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </form>
    </div>

    <div class="container">
        <a href="{{ route('technician.dashboard') }}" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
        </a>

        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-info-circle"></i> Work Order Information
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">WO Number</span>
                    <span class="info-value">{{ $workOrder->wo_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Customer</span>
                    <span class="info-value">{{ $workOrder->customer?->full_name ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Service Type</span>
                    <span class="info-value">{{ str_replace('_', ' ', ucfirst($workOrder->service_type)) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        @if($workOrder->status === 'completed')
                        <span class="badge badge-success">Completed</span>
                        @elseif($workOrder->status === 'in_progress')
                        <span class="badge badge-info">In Progress</span>
                        @elseif($workOrder->status === 'cancelled')
                        <span class="badge badge-warning">Cancelled</span>
                        @else
                        <span class="badge badge-secondary">Pending</span>
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Priority</span>
                    <span class="info-value">{{ ucfirst($workOrder->priority) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Scheduled</span>
                    <span class="info-value">{{ $workOrder->scheduled_date ? \Carbon\Carbon::parse($workOrder->scheduled_date)->format('d M Y H:i') : '-' }}</span>
                </div>
                @if($workOrder->customerUnit)
                <div class="info-row">
                    <span class="info-label">AC Unit</span>
                    <span class="info-value">{{ $workOrder->customerUnit->brand }} {{ $workOrder->customerUnit->type }} ({{ $workOrder->customerUnit->pk }} PK)</span>
                </div>
                @endif
                @if($workOrder->description)
                <div class="info-row">
                    <span class="info-label">Description</span>
                    <span class="info-value">{!! $workOrder->description !!}</span>
                </div>
                @endif
                @if($workOrder->notes)
                <div class="info-row">
                    <span class="info-label">Notes</span>
                    <span class="info-value">{!! $workOrder->notes !!}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-users"></i> Team
            </div>
            <div class="card-body">
                <div class="tech-list">
                    @foreach($workOrder->technicians as $t)
                    <span class="tech-tag {{ $t->pivot->is_captain ? 'captain' : '' }}">
                        {{ $t->full_name }}
                        @if($t->pivot->is_captain) (Captain) @endif
                    </span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-rotate"></i> My Progress
            </div>
            <div class="card-body">
                <div style="margin-bottom:16px;">
                    <p style="font-size:.88rem;color:#888;margin-bottom:6px;">Current Status:</p>
                    @if($myPivot->status === 'completed')
                    <span class="badge badge-success" style="font-size:.9rem;padding:6px 16px;">Completed</span>
                    @elseif($myPivot->status === 'in_progress')
                    <span class="badge badge-info" style="font-size:.9rem;padding:6px 16px;">In Progress</span>
                    @else
                    <span class="badge badge-secondary" style="font-size:.9rem;padding:6px 16px;">Assigned</span>
                    @endif
                </div>

                @if($myPivot->status !== 'completed' && $workOrder->status !== 'cancelled')
                <div class="form-group">
                    <label class="form-label">Update Status</label>
                    <select id="progressStatus" class="form-control">
                        <option value="assigned" {{ $myPivot->status === 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="in_progress" {{ $myPivot->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $myPivot->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Progress Note</label>
                    <textarea id="progressNote" class="form-control" rows="3" placeholder="Describe what you've done...">{{ $myPivot->progress_note ?? '' }}</textarea>
                </div>
                <button class="btn btn-primary" onclick="updateProgress()">
                    <i class="fa-solid fa-rotate"></i> Update Progress
                </button>
                @endif

                @if($myPivot->progress_note)
                <div style="margin-top:16px;padding:12px;background:#f8f9fa;border-radius:8px;">
                    <p style="font-size:.8rem;color:#888;margin-bottom:4px;">Latest Note:</p>
                    <p style="font-size:.88rem;">{{ $myPivot->progress_note }}</p>
                </div>
                @endif

                @if($myPivot->completed_at)
                <div style="margin-top:12px;font-size:.82rem;color:#999;">
                    Completed at: {{ \Carbon\Carbon::parse($myPivot->completed_at)->format('d M Y H:i') }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function updateProgress() {
        const status = document.getElementById('progressStatus').value;
        const note = document.getElementById('progressNote').value;

        fetch('{{ route("technician.work-orders.update-progress", $workOrder->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status, progress_note: note })
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                Swal.fire({ icon: 'success', title: 'Progress Updated!', timer: 1500, showConfirmButton: false });
                setTimeout(() => location.reload(), 1500);
            } else {
                Swal.fire('Error', res.message || 'Failed to update.', 'error');
            }
        })
        .catch(() => Swal.fire('Error', 'Network error.', 'error'));
    }
    </script>
</body>
</html>
