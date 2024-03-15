<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Module;

use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request) {
        $verify = AccessController::verify('user');
        if($verify)
            return redirect($verify);

        return view('modules.user.index', [
            'menu' => ModuleController::menu(),
            'success' => $request->cookie('success'),
            'data' => User::all()
        ]);
    }

    public function create(Request $request) {
        $verify = AccessController::verify('user');
        if($verify)
            return redirect($verify);

        return view('modules.user.create', [
            'menu' => ModuleController::menu(),
            'erro' => $request->cookie('erro'),
            'module' => Module::all()
        ]);
    }

    public function store(Request $request) {
        if(User::where('cpf', $request->cpf)->orWhere('email', $request->email)->count() > 0) {
            return redirect('user/create')->cookie('erro', 'Usuário Já Cadastrado!', 0.03);
        } else {
            $create = new User;
            $create->name            = trim(ucwords(mb_strtolower($request->name)));
            $create->cpf             = $request->cpf;
            $create->phone           = $request->phone;
            $create->email           = trim(mb_strtolower($request->email));
            $create->password        = Hash::make('123');
            $create->modules         = $request->module;
            $create->active          = 1;
            $create->change_password = 1;
            $create->save();

            return redirect('user')->cookie('success', 'Usuário Cadastrado com Sucesso! Senha padrão: 123', 0.03);
        }
    }

    public function edit(Request $request, $id) {
        $verify = AccessController::verify('user');
        if($verify)
            return redirect($verify);

        if($id <= 0 || $id > User::max('id'))
            return redirect('user');

        return view('modules.user.update', [
            'menu' => ModuleController::menu(),
            'erro' => $request->cookie('erro'),
            'data' => User::findOrFail($id),
            'module' => Module::all()
        ]);
    }

    public function update(Request $request, $id) {
        if(User::where([['cpf', $request->cpf],['id', '!=', $id]])->count() > 0) {
            return redirect('user/' . $id)->cookie('erro', 'Usuário Já Cadastrado!', 0.03);
        } else {
            $update = User::find($id);
            $update->name    = trim(ucwords(mb_strtolower($request->name)));
            $update->cpf     = $request->cpf;
            $update->phone   = $request->phone;
            $update->email   = trim(mb_strtolower($request->email));
            $update->modules = $request->module;
            $update->save();

            return redirect('user')->cookie('success', 'Usuário Atualizado com Sucesso!', 0.03);
        }
    }

    public function function(Request $request, $id) {
        if($request->disable) {
            $disable = User::find($id);
            $disable->active = 0;
            $disable->save();

            return redirect('user')->cookie('success', 'Usuário Desativado com Sucesso!', 0.03);

        } elseif($request->enable) {
            $enable = User::find($id);
            $enable->password = Hash::make('123');
            $enable->active = 1;
            $enable->change_password = 1;
            $enable->save();

            return redirect('user')->cookie('success', 'Usuário Reativado com Sucesso!', 0.03);

        } elseif($request->password) {
            $password = User::find($id);
            $password->password = Hash::make('123');
            $password->change_password = 1;
            $password->save();

            return redirect('user')->cookie('success', 'Senha Resetada com Sucesso! Senha padrão: 123', 0.03);
        }
    }
}
