<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use App\Models\PeriodeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PeriodeController extends Controller
{
    public function index()
    {
        $activeMenu = 'periode';
        $breadcrumb = (object)[
            'title' => 'Manajemen Periode',
            'list'  => ['Periode']
        ];

        return view('periode.index', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $periodes = PeriodeModel::all();

        return DataTables::of($periodes)
            ->addIndexColumn()
            ->addColumn('aksi', function ($periode) {
                $btn  = '<button onclick="modalAction(\'' . url('/periode/' . $periode->id_periode . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/periode/' . $periode->id_periode . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/periode/' . $periode->id_periode . '/confirm_delete') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show(string $id)
    {
        $periode = PeriodeModel::find($id);
        return view('periode.show', ['data' => $periode]);
    }

    public function create()
    {
        return view('periode.create');
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'semester' => 'required|string|max:100',
                'tahun_ajaran' => 'required|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            PeriodeModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data periode berhasil disimpan',
                'data' => $request->all(),
            ]);
        }
        return redirect('/periode');
    }

    public function edit(string $id)
    {
        $periode = PeriodeModel::find($id);
        return view('periode.edit', ['data' => $periode]);
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'semester' => 'required|string|max:100',
                'tahun_ajaran' => 'required|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $periode = PeriodeModel::find($id);
            if ($periode) {
                $periode->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data periode berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/periode');
    }

    public function confirm_delete(string $id)
    {
        $periode = PeriodeModel::find($id);
        return view('periode.delete', ['data' => $periode]);
    }

    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $periode = PeriodeModel::find($id);
            if ($periode) {
                $periode->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data periode berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/periode');
    }
}