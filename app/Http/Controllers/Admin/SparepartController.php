<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SparepartController extends Controller
{
    public function index()
    {
        return view('admin.spareparts.index');
    }

    public function data()
    {
        $spareparts = Sparepart::select(['id', 'name', 'brand', 'part_number', 'unit', 'stock_quantity', 'min_stock', 'price', 'created_at']);

        return DataTables::of($spareparts)
            ->addColumn('action', function ($sparepart) {
                return '<button class="btn btn-sm btn-primary edit-btn" data-id="'.$sparepart->id.'"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="'.$sparepart->id.'"><i class="fa-solid fa-trash"></i></button>';
            })
            ->editColumn('price', function ($sparepart) {
                return number_format($sparepart->price, 2);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'brand'          => 'nullable|string|max:100',
            'part_number'    => 'nullable|string|max:100',
            'unit'           => 'nullable|string|max:50',
            'stock_quantity' => 'nullable|integer|min:0',
            'min_stock'      => 'nullable|integer|min:0',
            'price'          => 'nullable|numeric|min:0',
            'notes'          => 'nullable|string',
        ]);

        $sparepart = Sparepart::create($validated);

        return response()->json(['success' => true, 'data' => $sparepart]);
    }

    public function show(Sparepart $sparepart)
    {
        return response()->json(['data' => $sparepart]);
    }

    public function update(Request $request, Sparepart $sparepart)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'brand'          => 'nullable|string|max:100',
            'part_number'    => 'nullable|string|max:100',
            'unit'           => 'nullable|string|max:50',
            'stock_quantity' => 'nullable|integer|min:0',
            'min_stock'      => 'nullable|integer|min:0',
            'price'          => 'nullable|numeric|min:0',
            'notes'          => 'nullable|string',
        ]);

        $sparepart->update($validated);

        return response()->json(['success' => true, 'data' => $sparepart]);
    }

    public function destroy(Sparepart $sparepart)
    {
        $sparepart->delete();

        return response()->json(['success' => true]);
    }
}
