<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Technician Dashboard — DQIN AC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Inter, sans-serif; background: #f5f5f5; color: #333; }
        .topbar {
            background: #164841; color: #fff; padding: 16px 24px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar h2 { font-size: 1.1rem; font-weight: 600; }
        .topbar .user { font-size: .85rem; opacity: .9; }
        .container { max-width: 900px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.06); margin-bottom: 16px; }
        .card-header { padding: 16px 20px; border-bottom: 1px solid #f0f0f0; font-weight: 600; font-size: .95rem; }
        .card-body { padding: 20px; }
        .wo-item {
            padding: 16px 20px; border-bottom: 1px solid #f5f5f5;
            display: flex; align-items: center; justify-content: space-between;
            transition: background .15s;
        }
        .wo-item:last-child { border-bottom: none; }
        .wo-item:hover { background: #fafafa; }
        .wo-item .wo-info h4 { font-size: .95rem; font-weight: 600; margin-bottom: 4px; }
        .wo-item .wo-info p { font-size: .82rem; color: #888; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: .75rem; font-weight: 600; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .badge-secondary { background: #e2e3e5; color: #383d41; }
        .empty { text-align: center; padding: 40px; color: #999; }
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px; border: none;
            cursor: pointer; font-size: .85rem; font-weight: 500;
            text-decoration: none; transition: all .2s;
        }
        .btn-primary { background: #164841; color: #fff; }
        .btn-primary:hover { background: #0f332e; }
        .btn-outline { background: transparent; border: 1px solid #ddd; color: #555; }
        .btn-outline:hover { background: #f5f5f5; }
        .btn-sm { padding: 5px 12px; font-size: .8rem; }
        .logout-form { display: inline; }
        .logout-btn { background: none; border: 1px solid rgba(255,255,255,.3); color: #fff; padding: 6px 14px; border-radius: 6px; cursor: pointer; font-size: .82rem; }
        .logout-btn:hover { background: rgba(255,255,255,.1); }
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 24px; }
        .stat { text-align: center; padding: 16px; background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
        .stat h3 { font-size: 1.5rem; font-weight: 700; color: #164841; }
        .stat p { font-size: .78rem; color: #888; margin-top: 4px; }
        @media (max-width: 600px) {
            .stats { grid-template-columns: 1fr; }
            .wo-item { flex-direction: column; align-items: flex-start; gap: 8px; }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div>
            <h2><i class="fa-solid fa-user-gear"></i> DQIN AC — Technician Panel</h2>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            <span class="user">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        @if(!$technician)
        <div class="card">
            <div class="card-body empty">
                <i class="fa-solid fa-triangle-exclamation" style="font-size:2rem;color:#dc3545;margin-bottom:12px;"></i>
                <p>You are not registered as a technician.</p>
                <p style="font-size:.82rem;color:#aaa;">Please contact the administrator.</p>
            </div>
        </div>
        @else
        <div class="stats">
            <div class="stat">
                <h3>{{ $workOrders->count() }}</h3>
                <p>Total Assignments</p>
            </div>
            <div class="stat">
                <h3>{{ $workOrders->where('status', 'in_progress')->count() + $workOrders->filter(fn($wo) => $wo->technicians->find($technician->id)?->pivot->status === 'in_progress')->count() }}</h3>
                <p>In Progress</p>
            </div>
            <div class="stat">
                <h3>{{ $workOrders->filter(fn($wo) => $wo->technicians->find($technician->id)?->pivot->status === 'completed')->count() }}</h3>
                <p>Completed</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-clipboard-list"></i> My Work Orders
            </div>
            <div class="card-body p-0">
                @forelse($workOrders as $wo)
                @php $myPivot = $wo->technicians->find($technician->id)?->pivot; @endphp
                <div class="wo-item">
                    <div class="wo-info">
                        <h4>{{ $wo->wo_number }} — {{ $wo->customer?->full_name ?? '-' }}</h4>
                        <p>
                            {{ str_replace('_', ' ', ucfirst($wo->service_type)) }}
                            @if($myPivot)
                            &middot;
                            My status:
                            @if($myPivot->status === 'completed')
                            <span class="badge badge-success">Completed</span>
                            @elseif($myPivot->status === 'in_progress')
                            <span class="badge badge-info">In Progress</span>
                            @else
                            <span class="badge badge-secondary">Assigned</span>
                            @endif
                            @endif
                        </p>
                    </div>
                    <div style="display:flex;gap:6px;flex-shrink:0;">
                        <a href="{{ route('technician.work-orders.show', $wo->id) }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-eye"></i> Detail
                        </a>
                    </div>
                </div>
                @empty
                <div class="empty">
                    <p>No work orders assigned to you yet.</p>
                </div>
                @endforelse
            </div>
        </div>
        @endif
    </div>
</body>
</html>
