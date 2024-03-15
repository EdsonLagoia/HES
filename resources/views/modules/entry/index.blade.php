@extends('layouts.module')

@section('content')
    <div class="row">
        <div class="col-sm-7 text-start">
            <h2>Registro de Entrada</h2>
        </div>

        <div class="col-sm-5 text-end">
            <form action="/entry/store/1" method="post">
                @csrf

                <a href="/patient/create" class="btn btn-success">
                    <i class="fa-solid fa-plus"></i> Cadastrar Paciente
                </a>

                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#unknown" title="Paciente Desconhecido">
                    <i class="fa-solid fa-circle-check"></i> Paciente Desconhecido
                </button>

                <div class="modal fade" id="unknown" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="unknownLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content text-dark text-start">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="unknownLabel">Registrar Entrada</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="type" class="form-label">Tipo: <span class="text-danger fw-bold">*</span></label>
                                        <select class="form-select" id="type" name="type" required>
                                            <option value="">---</option>
                                            <option value="adult">Adulto</option>
                                            <option value="pediatric">Pediátrico</option>
                                            <option value="obstetric">Obstétrico</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-8">
                                        <label for="origin" class="form-label">Procedência: <span class="text-danger fw-bold">*</span></label>
                                        <select class="form-select" id="origin" name="origin" required>
                                            <option value="">---</option>
                                            @foreach ($origins as $origin)
                                                <option value="{{ $origin->id }}">{{ $origin->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-success">Gerar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <hr>

    <div class="row">
        <form method="post" id="patients">
            @csrf
            <div class="col-sm-12">
                <input type="text" class="form-control" id="search" name="search" placeholder="Buscar por Nome ou CPF" required>
            </div>
        </form>
    </div>

    <hr>

    <div class="row" id="result">
        <div class="col-sm-12 mb-2">
            <h3>Resultados (0)</h3>
        </div>
    </div>

    <hr>

    <table class="table table-responsive table-striped table-secondary">
        <thead>
            <th colspan=2 class="text-center table-success" width=100%>
                Últimas Entradas
            </th>
        </thead>

        <tbody id="service_table">
            @foreach($data as $data)
                <tr>
                    <td width=80%>
                        @inject('users', 'App\Models\User')
                        @inject('patients', 'App\Models\Patient')
                        @php
                            $user = $users::findOrFail($data->user);
                            $patient = $patients::findOrFail($data->patient);
                        @endphp
                        <p class="mb-0">
                            <b>Nº do BPA:</b> {{ $data->id }}
                            <b class="ps-4">Entrada:</b> {{ date('d/m/Y H:i:s', strtotime($data->entry)) }}
                        </p>

                        <p class="mb-0">
                            <b>Agendado por:</b> {{ $user->name }}
                        </p>

                        <p class="mb-0">
                            <b>Paciente:</b>
                            @if($patient->id == 1)
                                {{ $patient->name . date(' d/m/Y H:i:s', strtotime($data->entry)) }}
                            @else
                                {{ $data->social_name ? $patient->social_name : $patient->name }}
                            @endif

                            <b class="ps-4">Nasc.:</b> {{ $patient->id != 1 ? date('d/m/Y', strtotime($patient->birth_date)) : '' }}
                            <b class="ps-4">CPF:</b> {{ $patient->cpf }} <b class="ps-4">Tel:</b> {{ $patient->phone }}
                        </p>
                        <p class="mb-0"></p>
                    </td>

                    <td class="text-end align-middle" width="20%">
                        <a href="/entry/{{ $data->type}}/{{ $data->id }}" class="btn btn-secondary" title="Emitir BPA do Paciente" target="_blank">
                            <i class="fa-solid fa-print"></i> Imprimir BPA
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#search').keyup(function(){
                $.ajax({
                    url: '{{ route('loadPatient') }}',
                    data: {
                        'search': $(this).val(),
                    },
                    success: function(data){
                        $('#result').html(data);
                    }
                });
            });
        });
    </script>
@endsection
