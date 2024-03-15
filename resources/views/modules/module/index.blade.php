@extends('layouts.module')

@section('content')
    <div class="row">
        <div class="col-sm-9 text-start">
            <h2>Módulos</h2>
        </div>
    
        @include('layouts.insert', ['module' => '/module/create'])
    </div>
    
    <hr>
    
    <table class="table table-responsive table-striped table-secondary" id="table">
        <thead>
            <th width=5%>#</th>
            <th width=84%>Módulo</th>
            <th width=11%>Ações</th>
        </thead>
    
        <tbody>
            @foreach($data as $data) 
                <tr class="align-middle">
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->title }}</td>
                    <td class="text-end">
                        @if(!session()->get('viewer'))
                            <form class="px-0" action="/module/destroy/{{ $data->id }}" method="post">
                                @csrf
                                @method('PUT')
                                <a href="module/{{ $data->id }}" class="btn btn-info" title="Editar Modulo">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <button type="submit" class="btn btn-danger" id="delete" name="delete" value="{{ $data->id }}" title="Deletar Modulo">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection