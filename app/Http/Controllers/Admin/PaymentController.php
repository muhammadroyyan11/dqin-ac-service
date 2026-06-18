<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    public function index()
    {
        return view('admin.payments.index');
    }

    public function data()
    {
        $payments = Payment::with('invoice.customer')->select('payments.*');

        return DataTables::of($payments)
            ->addColumn('invoice_number', function ($p) {
                return $p->invoice?->invoice_number ?? '-';
            })
            ->addColumn('customer_name', function ($p) {
                return $p->invoice?->customer?->full_name ?? '-';
            })
            ->addColumn('action', function ($p) {
                return '<button class="edit-btn" data-id="'.$p->id.'">Edit</button>
                        <button class="delete-btn" data-id="'.$p->id.'">Delete</button>';
            })
            ->editColumn('amount', function ($p) {
                return number_format($p->amount, 2);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id'     => 'required|exists:invoices,id',
            'payment_method' => 'required|string|max:50',
            'amount'         => 'required|numeric|min:0',
            'payment_date'   => 'required|date',
            'reference'      => 'nullable|string|max:255',
            'notes'          => 'nullable|string',
        ]);

        $payment = Payment::create($validated);

        return response()->json(['success' => true, 'data' => $payment]);
    }

    public function show(Payment $payment)
    {
        $payment->load('invoice.customer');

        return response()->json(['data' => $payment]);
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'invoice_id'     => 'required|exists:invoices,id',
            'payment_method' => 'required|string|max:50',
            'amount'         => 'required|numeric|min:0',
            'payment_date'   => 'required|date',
            'reference'      => 'nullable|string|max:255',
            'notes'          => 'nullable|string',
        ]);

        $payment->update($validated);

        return response()->json(['success' => true, 'data' => $payment]);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->json(['success' => true]);
    }
}
