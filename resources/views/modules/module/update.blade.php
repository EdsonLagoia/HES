@extends('layouts.module')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h2>Atualizar Módulo</h2>
        </div>
    </div>

    <hr>

    <form action="/module/update/{{ $data->id }}" method="post">
        @csrf
        @method('PUT')
        <div class="row mb-3">
            <div class="col-sm-4">
                <label for="icon" class="form-label">
                    Ícone <span class="text-danger fw-bold">*</span> 
                    <a href="https://fontawesome.com/search?o=r&m=free" class="text-white" target="_blank" title="FontAwesome">
                        <i class="fa-solid fa-circle-info"></i>
                    </a>
                </label>
                <input type="text" class="form-control" id="icon" name="icon" value="{{ $data->icon }}" required>
            </div>
            
            <div class="col-sm-4">
                <label for="module" class="form-label">Módulo <span class="text-danger fw-bold">*</span></label>
                <input type="text" class="form-control" id="module" name="module" value="{{ $data->module }}" required>
            </div>
            
            <div class="col-sm-4">
                <label for="title" class="form-label">Título <span class="text-danger fw-bold">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $data->title }}" required>
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
        
                <a href="module" class="btn btn-danger">
                    <i class="fa-solid fa-angles-left"></i> Voltar
                </a>
            </div>
        </div>
    </form>
@endsection