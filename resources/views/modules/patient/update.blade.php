@extends('layouts.module')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h2>Atualizar Paciente</h2>
        </div>
    </div>

    <hr>

    <form action="/patient/update/{{ $data->id }}" method="post">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <div class="col-sm-6">
                <label for="name" class="form-label">Nome <span class="text-danger fw-bold">*</span></label>
                <input type="text" class="form-control text-uppercase" id="name" name="name" value="{{ $data->name }}" required>
            </div>

            <div class="col-sm-6">
                <label for="name" class="form-label">Mãe</label>
                <input type="text" class="form-control text-uppercase" id="mother" name="mother" value="{{ $data->mother }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-4 d-flex align-items-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="use_sn" name="use_sn" onchange="Social_Name()" {{ $data->social_name ? 'checked' : '' }}>
                    <label class="form-check-label" for="use_sn">Tratamento pelo nome social?</label>
                </div>
            </div>

            <div class="col-sm-8">
                <label for="social_name" class="form-label">Nome Social</label>
                <input type="text" class="form-control text-uppercase" id="social_name" name="social_name" required {{ $data->social_name ? '' : 'disabled' }}>
                <p class="mb-0" style="font-size: 9pt">Nome social: designação pela qual a pessoa travesti ou transexual se identifica e é socialmente reconhecida;<br>
                Conforme o Decreto Federal Nº 8.727, de 28 de Abril de 2016.</p>
            </div>
        </div>


        <div class="row mb-3">
            <div class="col-sm-3">
                <label for="birth_date" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" id="birth_date" name="birth_date" max="{{ date('Y-m-d') }}"  value="{{ $data->birth_date }}">
            </div>

            <div class="col-sm-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" value="{{ $data->cpf }}">
            </div>

            <div class="col-sm-3">
                <label for="sus" class="form-label">SUS</label>
                <input type="text" class="form-control" id="sus" name="sus" value="{{ $data->sus }}">
            </div>

            <div class="col-sm-3">
                <label for="phone" class="form-label">Telefone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ $data->phone }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-10">
                <label for="street" class="form-label">Endereço</label>
                <input type="text" class="form-control text-uppercase" id="street" name="street" value="{{ $data->street }}">
            </div>

            <div class="col-sm-2">
                <label for="number" class="form-label">Número</label>
                <input type="text" class="form-control text-uppercase" id="number" name="number" value="{{ $data->number}}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-5">
                <label for="district" class="form-label">Bairro</label>
                <input type="text" class="form-control text-uppercase" id="district" name="district" value="{{ $data->district }}">
            </div>

            <div class="col-sm-5">
                <label for="city" class="form-label">Cidade</label>
                <input type="text" class="form-control text-uppercase" id="city" name="city" value="{{ $data->city }}">
            </div>

            <div class="col-sm-2">
                <label for="state" class="form-label">Estado</label>
                <select class="form-select" id="state" name="state">
                    <option value="">---</option>
                    <option value="AC" {{ $data->state == 'AC' ? 'selected' : '' }}>AC</option>
                    <option value="AL" {{ $data->state == 'AL' ? 'selected' : '' }}>AL</option>
                    <option value="AM" {{ $data->state == 'AM' ? 'selected' : '' }}>AM</option>
                    <option value="AP" {{ $data->state == 'AP' ? 'selected' : '' }}>AP</option>
                    <option value="BA" {{ $data->state == 'BA' ? 'selected' : '' }}>BA</option>
                    <option value="CE" {{ $data->state == 'CE' ? 'selected' : '' }}>CE</option>
                    <option value="DF" {{ $data->state == 'DF' ? 'selected' : '' }}>DF</option>
                    <option value="ES" {{ $data->state == 'ES' ? 'selected' : '' }}>ES</option>
                    <option value="GO" {{ $data->state == 'GO' ? 'selected' : '' }}>GO</option>
                    <option value="MA" {{ $data->state == 'MA' ? 'selected' : '' }}>MA</option>
                    <option value="MG" {{ $data->state == 'MG' ? 'selected' : '' }}>MG</option>
                    <option value="MS" {{ $data->state == 'MS' ? 'selected' : '' }}>MS</option>
                    <option value="MT" {{ $data->state == 'MT' ? 'selected' : '' }}>MT</option>
                    <option value="PA" {{ $data->state == 'PA' ? 'selected' : '' }}>PA</option>
                    <option value="PB" {{ $data->state == 'PB' ? 'selected' : '' }}>PB</option>
                    <option value="PE" {{ $data->state == 'PE' ? 'selected' : '' }}>PE</option>
                    <option value="PI" {{ $data->state == 'PI' ? 'selected' : '' }}>PI</option>
                    <option value="PR" {{ $data->state == 'PR' ? 'selected' : '' }}>PR</option>
                    <option value="RJ" {{ $data->state == 'RJ' ? 'selected' : '' }}>RJ</option>
                    <option value="RN" {{ $data->state == 'RN' ? 'selected' : '' }}>RN</option>
                    <option value="RO" {{ $data->state == 'RO' ? 'selected' : '' }}>RO</option>
                    <option value="RR" {{ $data->state == 'RR' ? 'selected' : '' }}>RR</option>
                    <option value="RS" {{ $data->state == 'RS' ? 'selected' : '' }}>RS</option>
                    <option value="SC" {{ $data->state == 'SC' ? 'selected' : '' }}>SC</option>
                    <option value="SE" {{ $data->state == 'SE' ? 'selected' : '' }}>SE</option>
                    <option value="SP" {{ $data->state == 'SP' ? 'selected' : '' }}>SP</option>
                    <option value="TO" {{ $data->state == 'TO' ? 'selected' : '' }}>TO</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-redo"></i> Atualizar
                </button>

                <button type="reset" class="btn btn-warning">
                    <i class="fa-solid fa-eraser"></i> Limpar
                </button>

                <a href="/entry" class="btn btn-danger">
                    <i class="fa-solid fa-angles-left"></i> Voltar
                </a>
            </div>
        </div>
    </form>
@endsection
