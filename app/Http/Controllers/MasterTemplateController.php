<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Template;

class MasterTemplateController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        $filterKategori = $request->get('filterKategori');

        $templates = Template::where('template.deleted', 0)
            ->where(function($query) use($keyword) {
                $query->where('template.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('harga', 'LIKE', '%' . $keyword . '%');
            })
            ->when($filterKategori, function($query, $filterKategori) {
                return $query->where('template.kategori_id', $filterKategori);
            })
            ->select(
                'template.id AS id',
                'kategori.name AS kategori',
                'template.name AS name',
                'template.gambar AS gambar',
                'template.harga AS harga',
                
                'template.deleted AS deleted',
                'template.created_by AS created_by',
                'template.created_at AS created_at',
            )
            ->leftjoin('kategori', 'kategori.id', '=', 'template.kategori_id')
            ->orderBy('name', 'ASC')
            ->paginate(10);

        if ($keyword != '') {
            $templates->appends(['keyword' => $keyword]);
        }

        return view('master.template.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $messages = [
            'kategori.required' => 'Isian Kategori diperlukan.',
            'name.required'     => 'Isian Name diperlukan.',

            'gambar.required'   => 'Isian Gambar diperlukan.',
            'gambar.image'      => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes'      => 'Gambar harus memiliki format: jpeg, png atau jpg.',
            'gambar.max'        => 'Ukuran gambar maksimal adalah 2MB.',

            'harga.required'    => 'Isian Harga diperlukan.',
        ];

        $validator = Validator::make($request->all(), [
            'kategori' => 'required',
            'name'     => 'required',
            'gambar'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'harga'    => 'required',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // $cek = Template::where('name', $request->name)->where('deleted', 0);

        // if ($cek->count() > 0) {
        //     $validator->errors()->add('name', 'Nama sudah ada.');

        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        $gambarPath = \App\Helper::upload('storage/template/', $request->file('gambar'));

        Template::create([
            'kategori_id' => $request->kategori,
            'name'        => $request->name,
            'gambar'      => $gambarPath,
            'harga'       => $request->harga,

            'created_by' => Auth::user()->name,
        ]);

        toastr()->success('Tambah Template berhasil', 'Gotcha!');
        return back();
    }

    public function edit($id)
    {
        $template = Template::where('id', $id)->firstOrFail();
        return view('master.template.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'kategori.required' => 'Isian Kategori diperlukan.',
            'name.required'     => 'Isian Name diperlukan.',

            // 'gambar.required'   => 'Isian Gambar diperlukan.',
            // 'gambar.image'      => 'File yang diunggah harus berupa gambar.',
            // 'gambar.mimes'      => 'Gambar harus memiliki format: jpeg, png atau jpg.',
            // 'gambar.max'        => 'Ukuran gambar maksimal adalah 2MB.',

            'harga.required'    => 'Isian Harga diperlukan.',
        ];

        $validator = Validator::make($request->all(), [
            'kategori' => 'required',
            'name'     => 'required',
            // 'gambar'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'harga'    => 'required',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // $cek = Template::where('name', $request->name)->where('deleted', 0)->where('id', '!=', $id);

        // if ($cek->count() > 0) {
        //     $validator->errors()->add('name', 'Nama sudah ada.');

        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        $updateData = [
            'kategori_id' => $request->kategori,
            'name'        => $request->name,
            'harga'       => $request->harga,

            'updated_by'  => Auth::user()->name,
        ];

        $template = Template::where('id', $id)->firstOrFail();

        if ($request->hasFile('gambar')) {

            if ($template->gambar) {
                \App\Helper::moveTrash('storage/template/sampah/', $template->gambar);
            }

            $gambarPath = \App\Helper::upload('storage/template/', $request->file('gambar'));
            $updateData['gambar'] = $gambarPath;
        }

        Template::where('id', $id)->update($updateData);

        toastr()->success('Edit Template berhasil', 'Gotcha!');
        return back();
    }

    public function destroy($id)
    {
        $template = Template::where('id', $id)->firstOrFail();

        if ($template->deleted == 0) {
            if ($template->gambar) {
                \App\Helper::moveTrash('storage/template/sampah/', $template->gambar);
            }
            
            Template::find($id)->update(['deleted' => 1, 'updated_by' => Auth::user()->name]);

            return response()->json([
                'pesan' => 'Hapus Template Berhasil',
            ]);
        } else {
            Template::find($id)->update(['deleted' => 0, 'updated_by' => Auth::user()->name]);

            return response()->json([
                'pesan' => 'Kembalikan Template Berhasil',
            ]);
        }
    }
}
