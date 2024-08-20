<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\TLD;

class MasterTLDController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        $tlds = TLD::where('deleted', 0)
            ->where(function($query) use($keyword) {
                $query->where('name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('harga', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $keyword . '%');
            })
            ->orderBy('name', 'ASC')
            ->paginate(10);

        if ($keyword != '') {
            $tlds->appends(['keyword' => $keyword]);
        }

        return view('master.tld.index', compact('tlds'));
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required'  => 'Isian Nama diperlukan.',
            'harga.required' => 'Isian Harga diperlukan.',
        ];

        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'harga' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $cek = TLD::where('name', $request->name)->where('deleted', 0);

        if ($cek->count() > 0) {
            $validator->errors()->add('name', 'Nama sudah ada.');

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        TLD::create([
            'name'  => $request->name,
            'harga' => $request->harga,

            'created_by' => Auth::user()->name,
        ]);

        toastr()->success('Tambah TLD berhasil', 'Gotcha!');
        return back();
    }

    public function edit($id)
    {
        $tld = TLD::where('id', $id)->firstOrFail();
        return view('master.tld.edit', compact('tld'));
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'name.required'  => 'Isian Nama diperlukan.',
            'harga.required' => 'Isian Harga diperlukan.',
        ];

        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'harga' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $cek = TLD::where('name', $request->name)->where('deleted', 0)->where('id', '!=', $id);

        if ($cek->count() > 0) {
            $validator->errors()->add('name', 'Nama sudah ada.');

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        TLD::where('id', $id)->update([
            'name'  => $request->name,
            'harga' => $request->harga,
            
            'updated_by' => Auth::user()->name,
        ]);

        toastr()->success('Edit TLD berhasil', 'Gotcha!');
        return back();
    }

    public function destroy($id)
    {
        $tld = TLD::where('id', $id)->firstOrFail();

        if ($tld->deleted == 0) {
            TLD::find($id)->update(['deleted' => 1, 'updated_by' => Auth::user()->name]);

            return response()->json([
                'pesan' => 'Hapus TLD Berhasil',
            ]);
        } else {
            TLD::find($id)->update(['deleted' => 0, 'updated_by' => Auth::user()->name]);

            return response()->json([
                'pesan' => 'Kembalikan TLD Berhasil',
            ]);
        }
    }
}
