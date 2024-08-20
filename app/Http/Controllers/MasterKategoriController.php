<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Kategori;

class MasterKategoriController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        $kategoris = Kategori::where('deleted', 0)
            ->where(function($query) use($keyword) {
                $query->where('name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('created_at', 'LIKE', '%' . $keyword . '%');
            })
            ->orderBy('name', 'ASC')
            ->paginate(10);

        if ($keyword != '') {
            $kategoris->appends(['keyword' => $keyword]);
        }

        return view('master.kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'Isian Nama diperlukan.',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $cek = Kategori::where('name', $request->name)->where('deleted', 0);

        if ($cek->count() > 0) {
            $validator->errors()->add('name', 'Nama sudah ada.');

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Kategori::create([
            'name' => $request->name,

            'created_by' => Auth::user()->name,
        ]);

        toastr()->success('Tambah Kategori berhasil', 'Gotcha!');
        return back();
    }

    public function edit($id)
    {
        $kategori = Kategori::where('id', $id)->firstOrFail();
        return view('master.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'name.required' => 'Isian Nama diperlukan.',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $cek = Kategori::where('name', $request->name)->where('deleted', 0)->where('id', '!=', $id);

        if ($cek->count() > 0) {
            $validator->errors()->add('name', 'Nama sudah ada.');

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Kategori::where('id', $id)->update([
            'name' => $request->name,
            
            'updated_by' => Auth::user()->name,
        ]);

        toastr()->success('Edit Kategori berhasil', 'Gotcha!');
        return back();
    }

    public function destroy($id)
    {
        $kategori = Kategori::where('id', $id)->firstOrFail();

        if ($kategori->deleted == 0) {
            Kategori::find($id)->update(['deleted' => 1, 'updated_by' => Auth::user()->name]);

            return response()->json([
                'pesan' => 'Hapus Kategori Berhasil',
            ]);
        } else {
            Kategori::find($id)->update(['deleted' => 0, 'updated_by' => Auth::user()->name]);

            return response()->json([
                'pesan' => 'Kembalikan Kategori Berhasil',
            ]);
        }
    }
}
