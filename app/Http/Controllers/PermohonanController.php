<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

use App\Models\Permohonan;
use App\Models\PermohonanDetail;
use App\Models\Dokumen;
use App\Models\Syarat;

class PermohonanController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        $permohonans = Permohonan::where('deleted', 0)->where('users_id', Auth::user()->id)
            ->where(function($query) use($keyword) {
                $query->where('kode', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('villages.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('catatan', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('permohonan.created_at', 'LIKE', '%' . $keyword . '%');
            })
            ->select(
                'permohonan.kode AS kode',
                'villages.name AS desa',
                'permohonan.status AS status',
                'permohonan.catatan AS catatan',
                
                'permohonan.deleted AS deleted',
                'permohonan.created_by AS created_by',
                'permohonan.created_at AS created_at',
            )->leftjoin('villages', 'villages.id', '=', 'permohonan.desa')
            ->latest('permohonan.created_at')
            ->paginate(10);

        if ($keyword != '') {
            $permohonans->appends(['keyword' => $keyword]);
        }

        return view('permohonan.buat', compact('permohonans'));
    }

    public function detail($permohonan_kode, Request $request)
    {
        $permohonan = Permohonan::where('kode', $permohonan_kode)->where('deleted', 0)->firstOrFail();

        $filterItem = $request->get('filterItem');
        
        $keyword = $request->get('keyword');

        $dokumens = Dokumen::where('dokumen.deleted', 0)->where('permohonan_kode', $permohonan_kode)->where('dokumen.item_id', $filterItem)
            ->where(function($query) use($keyword) {
                $query->where('syarat.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('dokumen.created_at', 'LIKE', '%' . $keyword . '%');
            })
            ->select(
                'dokumen.id AS id',
                'syarat.name AS syarat',
                'dokumen.file AS file',
                
                'dokumen.deleted AS deleted',
                'dokumen.created_by AS created_by',
                'dokumen.created_at AS created_at',
            )->leftjoin('item', 'item.id', '=', 'dokumen.item_id')
            ->leftjoin('syarat', 'syarat.id', '=', 'dokumen.syarat_id')
            ->orderBy('syarat.name')
            ->paginate(10);

        if ($keyword != '') {
            $dokumens->appends(['keyword' => $keyword]);
        }

        return view('permohonan.detail', compact('permohonan', 'dokumens'));
    }

    public function store(Request $request)
    {
        $messages = [
            'desa.required' => 'Isian Desa diperlukan.',
            'item.required' => 'Isian Item diperlukan.',
        ];

        $validator = Validator::make($request->all(), [
            'desa' => 'required',
            'item' => 'required',
        ], $messages);

        if ($validator->fails()) {
            toastr()->error('Ada Isian yang belum terisi', 'Gagal!');

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $kode = \App\Helper::generatePermohonanKode();

        try {
            \DB::beginTransaction();

            Permohonan::create([
                'kode' => $kode,
                'desa' => $request->desa,

                'users_id'   => Auth::user()->id,
                'tgl'        => date('Y-m-d'),
                'created_by' => Auth::user()->name,
            ]);

            foreach ($request->item as $item) {                
                PermohonanDetail::create([
                    'permohonan_kode' => $kode,
                    'item_id'         => $item,
        
                    'created_by' => Auth::user()->name,
                ]);

                $syarats = Syarat::where('item_id', $item)->where('deleted', 0)->get();
                foreach ($syarats as $syarat) {
                    Dokumen::create([
                        'permohonan_kode' => $kode,
                        'item_id'         => $item,
                        'syarat_id'       => $syarat->id,
            
                        'created_by' => Auth::user()->name,
                    ]);
                }
            }

            \DB::commit();

            toastr()->success('Buat Permohonan berhasil', 'Gotcha!');
            return redirect(url('buat-permohonan/detail/' . $kode));
        } catch (\Exception $e) {
            \DB::rollBack();
        
            toastr()->error('Rollback, Harap diulang', 'Gagal!');
            return back();
        }
    }

    public function storeDokumen($permohonan_kode, Request $request)
    {
        $permohonan = Permohonan::where('kode', $permohonan_kode)->where('deleted', 0)->firstOrFail();

        $messages = [
            'file.required' => 'Isian File diperlukan.',
            'file.mimes'    => 'Harap upload file dengan format PDF.',
            'file.max'      => 'Ukuran file PDF kelebihan, maksimal 2MB.',
        ];

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:pdf|max:2048',
        ], $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();

            if ($errors->has('file')) {
                if ($errors->first('file') == $messages['file.required']) {
                    toastr()->error($messages['file.required'], 'Gagal!');
                    return back();
                } elseif ($errors->first('file') == $messages['file.mimes']) {
                    toastr()->error($messages['file.mimes'], 'Gagal!');
                    return back();
                } elseif ($errors->first('file') == $messages['file.max']) {
                    toastr()->error($messages['file.max'], 'Gagal!');
                    return back();
                }
            }
        }

        if ($request->id == '') {
            toastr()->error('Isian ID Diperlukan', 'Gagal!');
            return back();
        }

        $dokumen = Dokumen::where('id', $request->id)->where('deleted', 0)->firstOrFail();

        $file = $request->file('file');
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // Nama asli file tanpa extension
        $extension = $file->getClientOriginalExtension(); // Extension file

        // Ganti spasi dengan underscore pada nama asli file
        $originalName = str_replace(' ', '_', $originalName);

        // Tentukan nama file unik
        $fileName = $originalName;
        $counter = 1;
        while (Storage::exists("public/dokumen/{$fileName}.{$extension}")) {
            $fileName = "{$originalName}-{$counter}";
            $counter++;
        }
        $fileName = "{$fileName}.{$extension}";

        // Simpan file PDF ke storage dengan nama file yang unik
        $filePath = $file->storeAs('public/dokumen', $fileName);

        Dokumen::where('id', $request->id)->update([
            'file' => $fileName,

            'updated_by' => Auth::user()->name,
        ]);

        toastr()->success('Upload Dokumen berhasil', 'Gotcha!');
        return back();
    }

    public function destroy($permohonan_kode)
    {
        $permohonan = Permohonan::where('kode', $permohonan_kode)->firstOrFail();

        try {
            \DB::beginTransaction();

            if ($permohonan->deleted == 0) {
                Permohonan::where('kode', $permohonan_kode)->update(['deleted' => 1, 'updated_by' => Auth::user()->name]);
                PermohonanDetail::where('permohonan_kode', $permohonan_kode)->update(['deleted' => 1, 'updated_by' => Auth::user()->name]);

                \DB::commit();

                return response()->json([
                    'pesan' => 'Hapus Permohonan Berhasil',
                ]);
            } else {
                Permohonan::where('kode', $permohonan_kode)->update(['deleted' => 0, 'updated_by' => Auth::user()->name]);
                PermohonanDetail::where('permohonan_kode', $permohonan_kode)->update(['deleted' => 0, 'updated_by' => Auth::user()->name]);

                \DB::commit();

                return response()->json([
                    'pesan' => 'Kembalikan Permohonan Berhasil',
                ]);
            }
        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([
                'tipe'  => 'error',
                'pesan' => 'Rollback, Harap diulang',
            ]);
        }
    }

    public function destroyDokumen($dokumen_id)
    {
        $dokumen = Dokumen::where('id', $dokumen_id)->where('deleted', 0)->firstOrFail();

        if ($dokumen->deleted == 0) {
            if ($dokumen->file != NULL) {
                // Path file asli
                $originalPath = 'public/dokumen/' . $dokumen->file;

                // Path file yang akan dipindahkan ke folder sampah
                $trashPath = 'public/sampah/' . $dokumen->file;
                
                Storage::move($originalPath, $trashPath);
            }

            Dokumen::find($dokumen_id)->update(['file' => NULL, 'updated_by' => Auth::user()->name]);

            return response()->json([
                'pesan' => 'Hapus Dokumen Berhasil',
            ]);
        } else {
            // Dokumen::find($dokumen_id)->update(['deleted' => 0, 'updated_by' => Auth::user()->name]);

            return response()->json([
                'pesan' => 'Kembalikan Dokumen Berhasil',
            ]);
        }
    }
}
