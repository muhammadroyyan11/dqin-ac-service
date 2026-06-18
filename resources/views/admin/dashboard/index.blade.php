@extends('admin.layouts.admin')

@section('title', 'Dashboard — DQIN AC Admin')
@section('page-title', 'Dashboard')

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:#164841;">
                <i class="fa-solid fa-users" style="color:#fff;"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalCustomers ?? 156 }}</h3>
                <p>Total Customers</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#e67e22;">
                <i class="fa-solid fa-clipboard-list" style="color:#fff;"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $activeWorkOrders ?? 23 }}</h3>
                <p>Active Work Orders</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#27ae60;">
                <i class="fa-solid fa-money-bill-trend-up" style="color:#fff;"></i>
            </div>
            <div class="stat-info">
                <h3>RM {{ number_format($revenueThisMonth ?? 142500, 2) }}</h3>
                <p>Revenue This Month</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#c0392b;">
                <i class="fa-solid fa-file-invoice" style="color:#fff;"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $pendingInvoices ?? 14 }}</h3>
                <p>Pending Invoices</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Recent Work Orders</h5>
            <a href="{{ route('admin.work-orders.index') }}" class="btn btn-primary btn-sm">View All</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>WO#</th>
                            <th>Customer</th>
                            <th>Technician</th>
                            <th>Status</th>
                            <th>Scheduled</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $dummyOrders = [
                                ['id' => 'WO-001', 'customer' => 'Ahmad Fauzi', 'technician' => 'Rizki', 'status' => 'In Progress', 'scheduled' => '2026-06-17', 'amount' => 450],
                                ['id' => 'WO-002', 'customer' => 'Siti Aminah', 'technician' => 'Donny', 'status' => 'Completed', 'scheduled' => '2026-06-16', 'amount' => 320],
                                ['id' => 'WO-003', 'customer' => 'Rajesh Kumar', 'technician' => 'Hairul', 'status' => 'Pending', 'scheduled' => '2026-06-18', 'amount' => 550],
                                ['id' => 'WO-004', 'customer' => 'Linda Wong', 'technician' => 'Rizki', 'status' => 'In Progress', 'scheduled' => '2026-06-17', 'amount' => 780],
                                ['id' => 'WO-005', 'customer' => 'M. Faiz', 'technician' => 'Donny', 'status' => 'Scheduled', 'scheduled' => '2026-06-19', 'amount' => 210],
                            ];
                        @endphp
                        @foreach($dummyOrders as $o)
                        <tr>
                            <td style="font-weight:600;color:var(--primary);">{{ $o['id'] }}</td>
                            <td>{{ $o['customer'] }}</td>
                            <td>{{ $o['technician'] }}</td>
                            <td>
                                @php
                                    $badge = match($o['status']) {
                                        'Completed' => 'badge-success',
                                        'In Progress' => 'badge-info',
                                        'Pending' => 'badge-warning',
                                        default => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badge }}">{{ $o['status'] }}</span>
                            </td>
                            <td>{{ $o['scheduled'] }}</td>
                            <td style="font-weight:600;">RM {{ number_format($o['amount'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
