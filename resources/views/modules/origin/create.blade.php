@extends('layouts.module')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h2>Cadastrar Origem</h2>
            <h5 class="mb-0 text-danger">Campos com * são obrigatórios</h5>
        </div>
    </div>

    <hr>

    <form action="store" method="post">
        @csrf
        <div class="row mb-3">
            <div class="col-sm-12">
                <label for="title" class="form-label">Título <span class="text-danger fw-bold">*</span></label>
                <input type="text" class="form-control text-uppercase" id="title" name="title" required>
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

                <a href="/origin" class="btn btn-danger">
                    <i class="fa-solid fa-angles-left"></i> Voltar
                </a>
            </div>
        </div>
    </form>
@endsection

