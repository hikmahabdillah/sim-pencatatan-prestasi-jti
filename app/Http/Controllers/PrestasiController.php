<?php

namespace App\Http\Controllers;

use App\Models\PrestasiMahasiswaModel;
use App\Models\TingkatPrestasiModel;
use App\Models\KategoriModel;
use App\Models\PeriodeModel;
use App\Models\DosenPembimbingModel;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PrestasiController extends Controller
{
    public function create()
    {
        $tingkatPrestasi = TingkatPrestasiModel::all();
        $dosenPembimbing = DosenPembimbingModel::all();
        $kategori = KategoriModel::all();
        $periode = PeriodeModel::all();

        return view('prestasi.create', [
            'tingkatPrestasi' => $tingkatPrestasi,
            'dosenPembimbing' => $dosenPembimbing,
            'kategori' => $kategori,
            'periode' => $periode
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input dengan pesan error yang lebih jelas
        $validator = Validator::make($request->all(), [
            'id_tingkat_prestasi' => 'required|exists:tingkat_prestasi,id_tingkat_prestasi',
            'id_mahasiswa' => 'required|exists:mahasiswa,id_mahasiswa',
            'id_dospem' => 'nullable|exists:dosen_pembimbing,id_dospem',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'nama_prestasi' => 'required|string|max:255',
            'juara' => 'required|string|max:100',
            'tanggal_prestasi' => 'required|date',
            'id_periode' => 'required|exists:periode,id_periode',
            'keterangan' => 'nullable|string',
            'foto_kegiatan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bukti_sertifikat' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'surat_tugas' => 'nullable|file|mimes:pdf|max:2048',
            'karya' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:5120'
        ], [
            'id_tingkat_prestasi.required' => 'Tingkat prestasi wajib dipilih',
            'id_kategori.required' => 'Kategori prestasi wajib dipilih',
            'nama_prestasi.required' => 'Nama prestasi wajib diisi',
            'juara.required' => 'Juara yang diraih wajib diisi',
            'tanggal_prestasi.required' => 'Tanggal prestasi wajib diisi',
            'id_periode.required' => 'Periode wajib dipilih',
            'foto_kegiatan.image' => 'File foto kegiatan harus berupa gambar',
            'foto_kegiatan.mimes' => 'Format foto kegiatan harus jpeg, png, jpg, atau gif',
            'foto_kegiatan.max' => 'Ukuran foto kegiatan maksimal 2MB',
            'bukti_sertifikat.mimes' => 'Format bukti sertifikat harus pdf, jpeg, png, atau jpg',
            'bukti_sertifikat.max' => 'Ukuran bukti sertifikat maksimal 2MB',
            'surat_tugas.mimes' => 'Format surat tugas harus pdf',
            'surat_tugas.max' => 'Ukuran surat tugas maksimal 2MB',
            'karya.mimes' => 'Format karya harus pdf, doc, docx, ppt, atau pptx',
            'karya.max' => 'Ukuran karya maksimal 5MB'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()->toArray()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $request->except(['foto_kegiatan', 'bukti_sertifikat', 'surat_tugas', 'karya']);

            // Handle file uploads
            $fileFields = [
                'foto_kegiatan' => 'prestasi/foto_kegiatan',
                'bukti_sertifikat' => 'prestasi/bukti_sertifikat',
                'surat_tugas' => 'prestasi/surat_tugas',
                'karya' => 'prestasi/karya'
            ];

            foreach ($fileFields as $field => $path) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $data[$field] = $file->storeAs($path, $filename, 'public');
                }
            }

            $prestasi = PrestasiMahasiswaModel::create($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data prestasi berhasil disimpan',
                'data' => $prestasi,
                'redirect_url' => '/mahasiswa/' . $prestasi->id_mahasiswa . '/prestasi',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            // Hapus file yang sudah diupload jika terjadi error
            foreach ($fileFields as $field => $path) {
                if (!empty($data[$field])) {
                    Storage::disk('public')->delete($data[$field]);
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data prestasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPrestasiMahasiswa($id)
    {
        $breadcrumb = (object)[
            'title' => 'Prestasi Mahasiswa',
            'list'  => ['Mahasiswa', 'Prestasi Mahasiswa']
        ];

        // Mengambil data prestasi mahasiswa berdasarkan id
        $prestasi = PrestasiMahasiswaModel::where('id_mahasiswa', $id)->get();
        if (!$prestasi) {
            return redirect('/mahasiswa/' . $id . '/prestasi')->with('error', 'Data mahasiswa tidak ditemukan');
        }

        $prestasi = PrestasiMahasiswaModel::with([
            'tingkatPrestasi',
            'kategori',
            'periode',
            'dosenPembimbing'
        ])
            ->where('id_mahasiswa', $id)
            ->get();

        return view('mahasiswa.prestasi', [
            'prestasi' => $prestasi,
            'breadcrumb' => $breadcrumb,
        ]);
    }

    public function getDetailPrestasiMahasiswa($id)
    {
        $breadcrumb = (object)[
            'title' => 'Prestasi Mahasiswa',
            'list'  => ['Mahasiswa', 'Detail Prestasi Mahasiswa']
        ];
        $prestasi = PrestasiMahasiswaModel::with([
            'tingkatPrestasi',
            'kategori',
            'periode',
            'dosenPembimbing'
        ])
            ->where('id_prestasi', $id)
            ->first();

        // if ($prestasi) {
        //     return redirect('/mahasiswa/' . $prestasi->id_mahasiswa . '/prestasi')->with('error', 'Data prestasi tidak ditemukan');
        // }

        return view('prestasi.show', [
            'prestasi' => $prestasi,
            'breadcrumb' => $breadcrumb,
        ]);
    }

    public function getEditPrestasi($id)
    {
        $prestasi = PrestasiMahasiswaModel::with([
            'tingkatPrestasi',
            'kategori',
            'periode',
            'dosenPembimbing',
            'mahasiswa'
        ])->find($id);

        if (!$prestasi) {
            return redirect()->back()->with('error', 'Data prestasi tidak ditemukan');
        }

        $tingkatPrestasi = TingkatPrestasiModel::all();
        $kategori = KategoriModel::all();
        $periode = PeriodeModel::all();
        $dosenPembimbing = DosenPembimbingModel::all();

        return view('prestasi.edit', [
            'prestasi' => $prestasi,
            'tingkatPrestasi' => $tingkatPrestasi,
            'kategori' => $kategori,
            'periode' => $periode,
            'dosenPembimbing' => $dosenPembimbing
        ]);
    }

    public function updatePrestasi(Request $request, $id)
    {
        $prestasi = PrestasiMahasiswaModel::find($id);

        if (!$prestasi) {
            return response()->json([
                'status' => false,
                'message' => 'Data prestasi tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_prestasi' => 'required|string|max:255',
            'id_tingkat_prestasi' => 'required|exists:tingkat_prestasi,id_tingkat_prestasi',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'juara' => 'required|string|max:50',
            'tanggal_prestasi' => 'required|date',
            'id_periode' => 'required|exists:periode,id_periode',
            'id_dospem' => 'nullable|exists:dosen_pembimbing,id_dospem',
            'keterangan' => 'nullable|string',
            'foto_kegiatan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'bukti_sertifikat' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'surat_tugas' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
            'karya' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:5120'
        ], [
            'nama_prestasi.required' => 'Nama prestasi wajib diisi',
            'id_tingkat_prestasi.required' => 'Tingkat prestasi wajib dipilih',
            'id_kategori.required' => 'Kategori prestasi wajib dipilih',
            'juara.required' => 'Juara yang diraih wajib diisi',
            'tanggal_prestasi.required' => 'Tanggal prestasi wajib diisi',
            'id_periode.required' => 'Periode wajib dipilih',
            'foto_kegiatan.image' => 'File foto kegiatan harus berupa gambar',
            'foto_kegiatan.mimes' => 'Format foto kegiatan harus jpeg, png, atau jpg',
            'foto_kegiatan.max' => 'Ukuran foto kegiatan maksimal 2MB',
            'bukti_sertifikat.mimes' => 'Format bukti sertifikat harus pdf, jpeg, png, atau jpg',
            'bukti_sertifikat.max' => 'Ukuran bukti sertifikat maksimal 5MB',
            'surat_tugas.mimes' => 'Format surat tugas harus pdf, jpeg, png, atau jpg',
            'surat_tugas.max' => 'Ukuran surat tugas maksimal 5MB',
            'karya.mimes' => 'Format karya harus pdf, doc, docx, ppt, atau pptx',
            'karya.max' => 'Ukuran karya maksimal 5MB'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $request->only([
                'nama_prestasi',
                'id_tingkat_prestasi',
                'id_kategori',
                'juara',
                'tanggal_prestasi',
                'id_periode',
                'id_dospem',
                'keterangan'
            ]);

            // Handle file uploads
            $fileFields = [
                'foto_kegiatan' => 'prestasi/foto_kegiatan',
                'bukti_sertifikat' => 'prestasi/bukti_sertifikat',
                'surat_tugas' => 'prestasi/surat_tugas',
                'karya' => 'prestasi/karya'
            ];

            foreach ($fileFields as $field => $path) {
                if ($request->hasFile($field)) {
                    // Delete old file if exists
                    if ($prestasi->$field) {
                        Storage::disk('public')->delete($prestasi->$field);
                    }

                    // Store new file
                    $file = $request->file($field);
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $data[$field] = $file->storeAs($path, $filename, 'public');
                }
            }

            $prestasi->update($data);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Prestasi berhasil diperbarui',
                'data' => $prestasi,
                'redirect_url' => '/mahasiswa/' . $prestasi->id_mahasiswa . '/prestasi',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui prestasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function confirmDeletePrestasi($id)
    {
        $prestasi = PrestasiMahasiswaModel::find($id);

        if (!$prestasi) {
            return redirect()->back()->with('error', 'Data prestasi tidak ditemukan');
        }

        return view('prestasi.delete', [
            'prestasi' => $prestasi
        ]);
    }

    public function deletePrestasi($id)
    {
        $prestasi = PrestasiMahasiswaModel::find($id);

        if (!$prestasi) {
            return response()->json([
                'status' => false,
                'message' => 'Data prestasi tidak ditemukan'
            ], 404);
        }

        try {
            // Delete associated files
            $fileFields = [
                'foto_kegiatan',
                'bukti_sertifikat',
                'surat_tugas',
                'karya'
            ];

            foreach ($fileFields as $field) {
                if ($prestasi->$field) {
                    Storage::delete($prestasi->$field);
                }
            }

            $prestasi->delete();

            return response()->json([
                'status' => true,
                'message' => 'Prestasi berhasil dihapus',
                'redirect_url' => '/mahasiswa/' . $prestasi->id_mahasiswa . '/prestasi',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus prestasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
