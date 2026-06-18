<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class QuotationController extends Controller
{
    public function index()
    {
        return view('admin.quotations.index');
    }

    public function data()
    {
        $quotations = Quotation::with('customer')->select(['id', 'quote_number', 'customer_id', 'subtotal', 'tax', 'total', 'status', 'created_at']);

        return DataTables::of($quotations)
            ->addColumn('customer_name', function ($quotation) {
                return $quotation->customer ? $quotation->customer->full_name : '-';
            })
            ->addColumn('action', function ($quotation) {
                return '<button class="btn btn-sm btn-primary edit-btn" data-id="'.$quotation->id.'"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="'.$quotation->id.'"><i class="fa-solid fa-trash"></i></button>';
            })
            ->editColumn('total', function ($quotation) {
                return number_format($quotation->total, 2);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'quote_number'  => 'required|string|max:50|unique:quotations,quote_number',
            'customer_id'   => 'required|exists:customers,id',
            'work_order_id' => 'nullable|exists:work_orders,id',
            'items'         => 'nullable|json',
            'subtotal'      => 'nullable|numeric|min:0',
            'tax'           => 'nullable|numeric|min:0',
            'total'         => 'required|numeric|min:0',
            'status'        => 'required|string|in:draft,sent,approved,rejected',
            'notes'         => 'nullable|string',
        ]);

        $quotation = Quotation::create($validated);

        return response()->json(['success' => true, 'data' => $quotation]);
    }

    public function show(Quotation $quotation)
    {
        $quotation->load('customer');

        return response()->json(['data' => $quotation]);
    }

    public function update(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'quote_number'  => 'required|string|max:50|unique:quotations,quote_number,'.$quotation->id,
            'customer_id'   => 'required|exists:customers,id',
            'work_order_id' => 'nullable|exists:work_orders,id',
            'items'         => 'nullable|json',
            'subtotal'      => 'nullable|numeric|min:0',
            'tax'           => 'nullable|numeric|min:0',
            'total'         => 'required|numeric|min:0',
            'status'        => 'required|string|in:draft,sent,approved,rejected',
            'notes'         => 'nullable|string',
        ]);

        $quotation->update($validated);

        return response()->json(['success' => true, 'data' => $quotation]);
    }

    public function destroy(Quotation $quotation)
    {
        $quotation->delete();

        return response()->json(['success' => true]);
    }
}
