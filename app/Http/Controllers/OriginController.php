<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Origin;

class OriginController extends Controller
{
    public function index(Request $request) {
        $verify = AccessController::verify('origin');
        if($verify)
            return redirect($verify);

        return view('modules.origin.index', [
            'menu' => ModuleController::menu(),
            'success' => $request->cookie('success'),
            'data' => Origin::all()
        ]);
    }

    public function create(Request $request) {
        $verify = AccessController::verify('origin');
        if($verify)
            return redirect($verify);

        return view('modules.origin.create', [
            'menu' => ModuleController::menu(),
            'erro' => $request->cookie('erro'),
            'origin' => Origin::all()
        ]);
    }

    public function store(Request $request) {
        if(Origin::where('title', $request->title)->count() > 0) {
            return redirect('origin/create')->cookie('erro', 'Origem Já Cadastrada!', 0.03);
        } else {
            $create = new Origin;
            $create->title  = trim(mb_strtoupper($request->title));
            $create->active = 1;
            $create->save();

            return redirect('origin')->cookie('success', 'Origem Cadastrada com Sucesso!', 0.03);
        }
    }

    public function edit(Request $request, $id) {
        $verify = AccessController::verify('origin');
        if($verify)
            return redirect($verify);

        if($id <= 0 || $id > Origin::max('id'))
            return redirect('origin');

        return view('modules.origin.update', [
            'menu' => ModuleController::menu(),
            'erro' => $request->cookie('erro'),
            'data' => Origin::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id) {
        if(Origin::where([['title', $request->title],['id', '!=', $id]])->count() > 0) {
            return redirect('origin/' . $id)->cookie('erro', 'Origem Já Cadastrada!', 0.03);
        } else {
            $update = Origin::find($id);
            $update->title  = trim(mb_strtoupper($request->title));
            $update->active = 1;
            $update->save();

            return redirect('origin')->cookie('success', 'Origem Atualizada com Sucesso!', 0.03);
        }
    }

    public function active(Request $request, $id) {
        if($request->disable) {
            $disable = Origin::find($id);
            $disable->active = 0;
            $disable->save();

            return redirect('origin')->cookie('success', 'Origem Desativada com Sucesso!', 0.03);
        } elseif($request->enable) {
            $enable = Origin::find($id);
            $enable->active = 1;
            $enable->save();

            return redirect('origin')->cookie('success', 'Origem Reativada com Sucesso!', 0.03);
        }
    }

}
