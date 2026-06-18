<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('admin.invoices.index');
    }

    public function data()
    {
        $invoices = Invoice::with('customer')->select(['id', 'invoice_number', 'customer_id', 'subtotal', 'tax', 'total', 'paid_amount', 'status', 'due_date', 'created_at']);

        return DataTables::of($invoices)
            ->addColumn('customer_name', function ($invoice) {
                return $invoice->customer ? $invoice->customer->full_name : '-';
            })
            ->addColumn('action', function ($invoice) {
                return '<button class="btn btn-sm btn-primary edit-btn" data-id="'.$invoice->id.'"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="'.$invoice->id.'"><i class="fa-solid fa-trash"></i></button>';
            })
            ->editColumn('total', function ($invoice) {
                return number_format($invoice->total, 2);
            })
            ->editColumn('paid_amount', function ($invoice) {
                return number_format($invoice->paid_amount, 2);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|max:50|unique:invoices,invoice_number',
            'customer_id'    => 'required|exists:customers,id',
            'work_order_id'  => 'nullable|exists:work_orders,id',
            'quotation_id'   => 'nullable|exists:quotations,id',
            'subtotal'       => 'nullable|numeric|min:0',
            'tax'            => 'nullable|numeric|min:0',
            'total'          => 'required|numeric|min:0',
            'paid_amount'    => 'nullable|numeric|min:0',
            'status'         => 'required|string|in:unpaid,partial,paid,cancelled',
            'due_date'       => 'nullable|date',
            'notes'          => 'nullable|string',
        ]);

        $invoice = Invoice::create($validated);

        return response()->json(['success' => true, 'data' => $invoice]);
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('customer', 'payments');

        return response()->json(['data' => $invoice]);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|max:50|unique:invoices,invoice_number,'.$invoice->id,
            'customer_id'    => 'required|exists:customers,id',
            'work_order_id'  => 'nullable|exists:work_orders,id',
            'quotation_id'   => 'nullable|exists:quotations,id',
            'subtotal'       => 'nullable|numeric|min:0',
            'tax'            => 'nullable|numeric|min:0',
            'total'          => 'required|numeric|min:0',
            'paid_amount'    => 'nullable|numeric|min:0',
            'status'         => 'required|string|in:unpaid,partial,paid,cancelled',
            'due_date'       => 'nullable|date',
            'notes'          => 'nullable|string',
        ]);

        $invoice->update($validated);

        return response()->json(['success' => true, 'data' => $invoice]);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return response()->json(['success' => true]);
    }

    public function updatePayment(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'status'      => 'required|string|in:unpaid,partial,paid',
        ]);

        $invoice->update($validated);

        return response()->json(['success' => true, 'data' => $invoice]);
    }
}
