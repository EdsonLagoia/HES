@extends('layouts.module')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h2>Cadastrar Paciente</h2>
        </div>
    </div>

    <hr>

    <form action="store" method="post">
        @csrf
        <div class="row mb-3">
            <div class="col-sm-6">
                <label for="name" class="form-label">Nome <span class="text-danger fw-bold">*</span></label>
                <input type="text" class="form-control text-uppercase" id="name" name="name" required>
            </div>

            <div class="col-sm-6">
                <label for="name" class="form-label">Mãe</label>
                <input type="text" class="form-control text-uppercase" id="mother" name="mother">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-4 d-flex align-items-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="use_sn" name="use_sn" onchange="Social_Name()">
                    <label class="form-check-label" for="use_sn">Tratamento pelo nome social?</label>
                </div>
            </div>

            <div class="col-sm-8">
                <label for="social_name" class="form-label">Nome Social</label>
                <input type="text" class="form-control text-uppercase" id="social_name" name="social_name" required disabled>
                <p class="mb-0" style="font-size: 9pt">Nome social: designação pela qual a pessoa travesti ou transexual se identifica e é socialmente reconhecida;<br>
                Conforme o Decreto Federal Nº 8.727, de 28 de Abril de 2016.</p>
            </div>
        </div>


        <div class="row mb-3">
            <div class="col-sm-3">
                <label for="birth_date" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" id="birth_date" name="birth_date" max="{{ date('Y-m-d') }}">
            </div>

            <div class="col-sm-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf">
            </div>

            <div class="col-sm-3">
                <label for="sus" class="form-label">SUS</label>
                <input type="text" class="form-control" id="sus" name="sus">
            </div>

            <div class="col-sm-3">
                <label for="phone" class="form-label">Telefone</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-10">
                <label for="street" class="form-label">Endereço</label>
                <input type="text" class="form-control text-uppercase" id="street" name="street">
            </div>

            <div class="col-sm-2">
                <label for="number" class="form-label">Número</label>
                <input type="text" class="form-control text-uppercase" id="number" name="number">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-5">
                <label for="district" class="form-label">Bairro</label>
                <input type="text" class="form-control text-uppercase" id="district" name="district">
            </div>

            <div class="col-sm-5">
                <label for="city" class="form-label">Cidade</label>
                <input type="text" class="form-control text-uppercase" id="city" name="city">
            </div>

            <div class="col-sm-2">
                <label for="state" class="form-label">Estado</label>
                <select class="form-select" id="state" name="state">
                    <option value="">---</option>
                    <option value="AC">AC</option>
                    <option value="AL">AL</option>
                    <option value="AM">AM</option>
                    <option value="AP">AP</option>
                    <option value="BA">BA</option>
                    <option value="CE">CE</option>
                    <option value="DF">DF</option>
                    <option value="ES">ES</option>
                    <option value="GO">GO</option>
                    <option value="MA">MA</option>
                    <option value="MG">MG</option>
                    <option value="MS">MS</option>
                    <option value="MT">MT</option>
                    <option value="PA">PA</option>
                    <option value="PB">PB</option>
                    <option value="PE">PE</option>
                    <option value="PI">PI</option>
                    <option value="PR">PR</option>
                    <option value="RJ">RJ</option>
                    <option value="RN">RN</option>
                    <option value="RO">RO</option>
                    <option value="RR">RR</option>
                    <option value="RS">RS</option>
                    <option value="SC">SC</option>
                    <option value="SE">SE</option>
                    <option value="SP">SP</option>
                    <option value="TO">TO</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-plus"></i> Cadastrar
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
