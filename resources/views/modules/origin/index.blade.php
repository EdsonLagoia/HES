@extends('layouts.module')

@section('content')
    <div class="row">
        <div class="col-sm-9">
            <h2>Origens</h2>
        </div>

        @include('layouts.insert', ['module' => '/origin/create'])
    </div>

    <hr>

    <table class="table table-responsive table-striped table-secondary" id="table">
        <thead>
            <th width=5%>#</th>
            <th width=74%>Origem</th>
            <th width=10%>Ativo</th>
            <th width=11%>Ações</th>
        </thead>

        <tbody>
            @foreach($data as $data)
                <tr class="align-middle">
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->title }}</td>
                    <th class="text-{{ $data->active ? 'success' : 'danger' }}">{{ $data->active ? 'Sim' : 'Não' }}</th>
                    <td class="text-end">
                        <form action="origin/active/{{ $data->id }}" method="post">
                            @csrf
                            @method('PUT')
                            @if(!$data->active)
                                <button type="submit" class="btn btn-info" id="enable" name="enable" value="{{ $data->id }}">
                                    <i class="fa-solid fa-recycle"></i>
                                </button>
                            @else
                                <a href="origin/{{ $data->id }}" class="btn btn-info">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <button type="submit" class="btn btn-danger" id="disable" name="disable" value="{{ $data->id }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            @endif
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
