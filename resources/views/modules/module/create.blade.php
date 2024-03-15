@extends('layouts.module')

@section('content')
    <div class="row">
        <div class="col-sm-12 text-start">
            <h2>Cadastrar Módulo</h2>
        </div>
    </div>

    <hr>

    <form action="/module/store" method="post">
        @csrf
        <div class="row mb-3">
            <div class="col-sm-4">
                <label for="icon" class="form-label">
                    Ícone <span class="text-danger fw-bold">*</span>
                    <a href="https://fontawesome.com/search?o=r&m=free" class="text-white" target="_blank" title="FontAwesome">
                        <i class="fa-solid fa-circle-info"></i>
                    </a>
                </label>
                <input type="text" class="form-control" id="icon" name="icon" required>
            </div>
            
            <div class="col-sm-4">
                <label for="module" class="form-label">Módulo <span class="text-danger fw-bold">*</span></label>
                <input type="text" class="form-control" id="module" name="module" required>
            </div>
            
            <div class="col-sm-4">
                <label for="title" class="form-label">Título <span class="text-danger fw-bold">*</span></label>
                <input type="text" class="form-control" id="title" name="title" required>
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
        
                <a href="module" class="btn btn-danger">
                    <i class="fa-solid fa-angles-left"></i> Voltar
                </a>
            </div>
        </div>
    </form>
@endsection