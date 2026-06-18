<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FreonInventory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FreonController extends Controller
{
    public function index()
    {
        return view('admin.freon-inventory.index');
    }

    public function data()
    {
        $freon = FreonInventory::select(['id', 'type', 'stock_quantity', 'unit', 'price_per_unit', 'notes', 'created_at']);

        return DataTables::of($freon)
            ->addColumn('action', function ($freon) {
                return '<button class="edit-btn" data-id="'.$freon->id.'">Edit</button>
                        <button class="delete-btn" data-id="'.$freon->id.'">Delete</button>';
            })
            ->editColumn('price_per_unit', function ($freon) {
                return number_format($freon->price_per_unit, 2);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'           => 'required|string|max:100',
            'stock_quantity' => 'nullable|integer|min:0',
            'unit'           => 'nullable|string|max:50',
            'price_per_unit' => 'nullable|numeric|min:0',
            'notes'          => 'nullable|string',
        ]);

        $freon = FreonInventory::create($validated);

        return response()->json(['success' => true, 'data' => $freon]);
    }

    public function show(FreonInventory $freonInventory)
    {
        return response()->json(['data' => $freonInventory]);
    }

    public function update(Request $request, FreonInventory $freonInventory)
    {
        $validated = $request->validate([
            'type'           => 'required|string|max:100',
            'stock_quantity' => 'nullable|integer|min:0',
            'unit'           => 'nullable|string|max:50',
            'price_per_unit' => 'nullable|numeric|min:0',
            'notes'          => 'nullable|string',
        ]);

        $freonInventory->update($validated);

        return response()->json(['success' => true, 'data' => $freonInventory]);
    }

    public function destroy(FreonInventory $freonInventory)
    {
        $freonInventory->delete();

        return response()->json(['success' => true]);
    }
}
