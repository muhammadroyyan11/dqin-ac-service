<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\WorkOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = Customer::count();
        $activeWorkOrders = WorkOrder::whereIn('status', ['pending', 'in_progress'])->count();
        $revenueThisMonth = Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');
        $pendingInvoices = Invoice::whereIn('status', ['unpaid', 'partial'])->count();
        $recentWorkOrders = WorkOrder::with('customer')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalCustomers', 'activeWorkOrders', 'revenueThisMonth', 'pendingInvoices', 'recentWorkOrders'
        ));
    }
}
