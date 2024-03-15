<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Module;

class ModuleController extends Controller
{
    public static function menu() {
        return Module::whereIn('id', session()->get('modules'))->get();
    }

    public function index(Request $request) {
        $verify = AccessController::verify('module');
        if($verify)
            return redirect($verify);

        return view('modules.module.index', [
            'menu' => ModuleController::menu(),
            'success' => $request->cookie('success'),
            'data' => Module::all()
        ]);
    }

    public function create(Request $request) {
        $verify = AccessController::verify('module');
        if($verify)
            return redirect($verify);

        return view('modules.module.create', [
            'menu' => ModuleController::menu(),
            'erro' => $request->cookie('erro')
        ]);
    }

    public function store(Request $request) {
        if(Module::orWhere([['module', $request->module],['title', $request->title]])->count() > 0) {
            return redirect('/module/create')->cookie('erro', 'Módulo Já Cadastrado!', 0.03);
        } else {
            $create = new Module;
            $create->icon   = trim(mb_strtolower($request->icon));
            $create->module = trim(mb_strtolower($request->module));
            $create->title  = trim(ucwords(mb_strtolower($request->title)));
            $create->save();

            return redirect('/module')->cookie('success', 'Módulo Cadastrado com Sucesso!', 0.03);
        }
    }

    public function edit(Request $request, $id) {
        $verify = AccessController::verify('module');
        if($verify)
            return redirect($verify);

        if($id <= 0 || $id > Module::max('id'))
            return redirect('module');

        return view('modules.module.update', [
            'menu' => ModuleController::menu(),
            'data' => Module::findOrFail($id),
            'erro' => $request->cookie('erro')
        ]);
    }

    public function update(Request $request, $id) {
        if(Module::where([['module', $request->module], ['title', $request->title], ['id', '!=', $request->id]])->count() > 0) {
            return redirect('module/' . $id)->cookie('erro', 'Módulo Já Cadastrado!', 0.03);
        } else {
            $update = Module::find($id);
            $update->icon   = trim(mb_strtolower($request->icon));
            $update->module = trim(mb_strtolower($request->module));
            $update->title  = trim(ucwords(mb_strtolower($request->title)));
            $update->save();

            return redirect('/module')->cookie('success', 'Módulo Atualizado com Sucesso!', 0.03);
        }
    }

    public function destroy($id) {
        Module::destroy($id);
        return redirect('/module')->cookie('success', 'Módulo Apagado com Sucesso!', 0.03);
    }
}
