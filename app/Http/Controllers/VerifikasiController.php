<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Permohonan;
use App\Models\Dokumen;

class VerifikasiController extends Controller
{
    public function index(Request $request)
    {
        $filterStatus = $request->get('filterStatus');

        $keyword = $request->get('keyword');

        $permohonans = Permohonan::where('deleted', 0)
            ->when($filterStatus != '', function($query) use($filterStatus) {
                $query->where('status', $filterStatus);
            })
            ->where(function($query) use($keyword) {
                $query->where('kode', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('villages.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('catatan', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('permohonan.created_by', 'LIKE', '%' . $keyword . '%')
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
            ->orderBy('status', 'ASC')
            ->latest('permohonan.created_at')
            ->paginate(10);

        if ($keyword != '') {
            $permohonans->appends(['keyword' => $keyword]);
        }

        return view('verifikasi.index', compact('permohonans'));
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

        return view('verifikasi.detail', compact('permohonan', 'dokumens'));
    }

    public function store($permohonan_kode, Request $request)
    {
        $permohonan = Permohonan::where('kode', $permohonan_kode)->where('deleted', 0)->firstOrFail();

        $messages = [
            'status.required'  => 'Isian Status diperlukan.',
            'catatan.required' => 'Isian Catatan diperlukan.',
        ];

        $validator = Validator::make($request->all(), [
            'status'  => 'required',
            'catatan' => 'required',
        ], $messages);

        if ($validator->fails()) {
            toastr()->error('Ada Isian yang belum terisi', 'Gagal!');

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Permohonan::where('kode', $permohonan_kode)->update([
            'status'  => $request->status,
            'catatan' => $request->catatan,

            'updated_by' => Auth::user()->name,
        ]);

        toastr()->success('Verifikasi berhasil', 'Gotcha!');
        return redirect(url('verifikasi/detail/' . $permohonan_kode));
    }
}
