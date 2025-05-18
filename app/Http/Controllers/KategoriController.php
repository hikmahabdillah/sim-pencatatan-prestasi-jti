<?php

namespace App\Http\Controllers;
use App\Models\KategoriModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = KategoriModel::all();
        return view('kategori.index', compact('kategori'));
    }
    
    public function create_ajax()
    {
        return view('kategori.create_ajax');
    }
    
    public function store_ajax(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'required|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        // Simpan data
        $kategori = KategoriModel::create($request->all());
    
        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $kategori
        ]);
    }
    
    public function show_ajax($id)
    {
        $kategori = KategoriModel::findOrFail($id);
        return view('kategori.show_ajax', compact('kategori'));
    }
    
    public function edit_ajax($id)
    {
        $kategori = KategoriModel::findOrFail($id);
        return view('kategori.edit_ajax', compact('kategori'));
    }
    
    public function update_ajax(Request $request, $id)
    {
        $kategori = KategoriModel::findOrFail($id);
    
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'deskripsi' => 'required|string'
        ]);
    
        $kategori->update($request->all());
    
        return response()->json(['status' => 'success', 'message' => 'Berhasil diubah']);
    }
    
public function destroy($id)
{
    $kategori = KategoriModel::find($id);

    if (!$kategori) {
        return response()->json([
            'status' => 'error',
            'message' => 'Kategori tidak ditemukan'
        ]);
    }

    $kategori->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Kategori berhasil dihapus'
    ]);
}
}