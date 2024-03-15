<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Patient;

class PatientController extends Controller
{
    public function index(Request $request) {
        $verify = AccessController::verify('patient');
        if($verify)
            return redirect($verify);

        return view('modules.patient.index', [
            'menu' => ModuleController::menu(),
            'success' => $request->cookie('success'),
            'data' => Patient::where('id', '!=', 1)->get()
        ]);
    }

    public function create(Request $request) {
        $verify = AccessController::verify('patient');
        if($verify)
            return redirect($verify);

        return view('modules.patient.create', [
            'menu' => ModuleController::menu(),
            'erro' => $request->cookie('erro')
        ]);
    }

    public function store(Request $request) {
        if(Patient::where('name', $request->name)->count() > 0) {
            return redirect('patient/create')->cookie('erro', 'Paciente Já Cadastrado!', 0.03);
        } else {
            $create = new Patient;
            $create->name        = trim(mb_strtoupper($request->name));
            $create->social_name = trim(mb_strtoupper($request->social_name));
            $create->mother      = trim(mb_strtoupper($request->mother));
            $create->birth_date  = $request->birth_date;
            $create->sus         = $request->sus;
            $create->cpf         = $request->cpf;
            $create->phone       = $request->phone;
            $create->street      = $request->street;
            $create->number      = $request->number;
            $create->district    = $request->district;
            $create->city        = $request->city;
            $create->state       = $request->state;
            $create->active      = 1;
            $create->save();
            return redirect('entry')->cookie('success', 'Paciente Cadastrado com Sucesso!', 0.03);
        }
    }

    public function edit(Request $request, $id) {
        $verify = AccessController::verify('patient');
        if($verify)
            return redirect($verify);

        if($id <= 0 || $id > Patient::max('id'))
            return redirect('travel');

        return view('modules.patient.update', [
            'menu' => ModuleController::menu(),
            'erro' => $request->cookie('erro'),
            'data' => Patient::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id) {
        if(Patient::where([['cpf', $request->cpf], ['id', '!=', $id]])->count() > 0) {
            return redirect('patient/' . $id)->cookie('erro', 'Paciente Já Cadastrado!', 0.03);
        } else {
            $update = Patient::find($id);
            $update->name        = trim(mb_strtoupper($request->name));
            $update->social_name = trim(mb_strtoupper($request->social_name));
            $update->mother      = trim(mb_strtoupper($request->mother));
            $update->birth_date  = $request->birth_date;
            $update->sus         = $request->sus;
            $update->cpf         = $request->cpf;
            $update->phone       = $request->phone;
            $update->street      = $request->street;
            $update->number      = $request->number;
            $update->district    = $request->district;
            $update->city        = $request->city;
            $update->state       = $request->state;
            $update->save();

            return redirect('entry')->cookie('success', 'Paciente Atualizado com Sucesso!', 0.03);
        }
    }

    public function active(Request $request, $id) {
        if($request->disable) {
            $disable = Patient::find($id);
            $disable->active = 0;
            $disable->save();

            return redirect('entry')->cookie('success', 'Paciente Desativado com Sucesso!', 0.03);

        } elseif($request->enable) {
            $enable = Patient::find($id);
            $enable->active = 1;
            $enable->save();

            return redirect('entry')->cookie('success', 'Paciente Reativado com Sucesso!', 0.03);
        }
    }
}
