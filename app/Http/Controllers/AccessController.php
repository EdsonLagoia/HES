<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Module;

class AccessController extends Controller
{
    public static function verify($module) {
        if(Auth::id()) {
            $user = User::findOrFail(session()->get('id_user'));
            $module = Module::where('module', $module)->first();
            if (!$user->active)
                return 'login';
            elseif (session()->get('change_password'))
                return 'change-password';

            if($module && !in_array($module->id, session()->get('modules')))
                return '/';
        }
    }

    public function index(Request $request) {
        $verify = AccessController::verify('/');
        if($verify)
            return redirect($verify);

        return view('home', [
            'menu' => ModuleController::menu(),
            'home' => true
        ]);
    }

    public function login(Request $request) {
        if(Auth::id()) {
            if (session()->get('change_password'))
                return redirect('change-password');
        }

        return view('modules.access.login',[
            'erro' => $request->cookie('erro')
        ]);
    }

    public function authentication(Request $request) {
        $credentials = $request->validate([
            'cpf' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = User::where('cpf', $request->cpf)->firstOrFail();
            $request->session()->put('id_user', $user->id);
            $request->session()->put('modules', $user->modules);

            if ($user->change_password) {
                $request->session()->put('change_password', $user->change_password);
                return redirect()->intended('change-password');
            } else {
                return redirect()->intended('/');
            }
        } else {
            $user = User::where('cpf', $request->cpf)->get();

            if (count($user) == 0)
                $cookie = cookie('erro', 'Usuário Não Encontrado!', 0.03);
            elseif (!$user[0]->active)
                $cookie = cookie('erro', 'Usuário Inativo!', 0.03);
            else
                $cookie = cookie('erro', 'Senha Incorreta!', 0.03);

            return redirect('login')->cookie($cookie);
        }
    }

    public function change_password(Request $request) {
        if(Auth::id() && !$request->session()->get('change_password'))
            return redirect('/');

        return view('modules.access.change-password',[
            'erro' => $request->cookie('erro'),
            'success' => $request->cookie('success')
        ]);
    }


    public function new_password(Request $request) {
        if($request->password == $request->password_confirm){
            $update = User::find(session()->get('id_user'));
            $update->password = Hash::make(trim($request->password));
            $update->change_password = 0;
            $update->save();

            $request->session()->pull('change_password');
            return redirect('/')->cookie('success', 'Senha Alterada com Sucesso!', 0.03);
        } else {
            return redirect('change-password')->cookie('erro', 'Senhas não Coincidem!', 0.03);
        }
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}
