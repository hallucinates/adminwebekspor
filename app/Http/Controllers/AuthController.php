<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('dasbor');
        }

        return view('login');
    }

    public function loginPost(Request $request)
    {
        $messages = [
            'email.required'    => 'Isian Email diperlukan.',
            'password.required' => 'Isian Kata Sandi diperlukan.',
        ];

        $validator = Validator::make($request->all(), [
            'email'    => 'required',
            'password' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $cek = User::where('email', $request->email)->where('deleted', 0);

        if ($cek->count() < 1) {
            $validator->errors()->add('email', 'Kredensial ini tidak cocok dengan catatan kami.');

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $row = $cek->first();

        $data = [
            'email'    => $request->email,
            'password' => $request->password,
        ];

        if (Auth::Attempt($data)) {
            toastr()->success('Halo '.$row->name.', semoga harimu menyenangkan.', 'Gotcha!');
            return redirect('dasbor');
        } else {
            $validator->errors()->add('email', 'Kredensial ini tidak cocok dengan catatan kami.');
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
