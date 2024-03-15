@extends('layouts.module')

@section('content')
    <div class="row">
        <div class="col-sm-12 text-start">
            <h2>Cadastrar Passagem do Paciente</h2>
        </div>
    </div>

    <hr>

    <form action="/travel/store/{{ $patient->id }}" method="post">
        @csrf
        <div class="row mb-3">
            <div class="col-sm-6">
                <label for="patient" class="form-label">Paciente</label>
                <input type="text" class="form-control" value="{{ $patient->name }}" disabled>
            </div>

            <div class="col-sm-3">
                <label for="cpf" class="form-label">CPF:</label>
                <input type="text" class="form-control" value="{{ $patient->cpf }}" disabled>
            </div>

            <div class="col-sm-3">
                <label for="rg" class="form-label">RG:</label>
                <input type="text" class="form-control" value="{{ $patient->rg }}" disabled>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-3">
                <label for="sex" class="form-label">Sexo:</label>
                <input type="text" class="form-control" value="{{ $patient->sex }}" disabled>
            </div>

            <div class="col-sm-3">
                <label for="birth_date" class="form-label">Data de Nascimento:</label>
                <input type="date" class="form-control" value="{{ $patient->birth_date }}" disabled>
            </div>

            <div class="col-sm-6">
                <label for="phone" class="form-label">Telefone:</label>
                <input type="text" class="form-control" value="{{ $patient->phone }}" disabled>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-sm-12">
                <h2>Dados da Passagem</h2>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-2 d-flex align-items-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="companion" name="companion">
                    <label class="form-check-label" for="companion">Somente Acompanhante</label>
                </div>
            </div>

            <div class="col-sm-5">
                <label for="companion1" class="form-label">Acompanhante 1:</label>
                <select class="form-select" id="companion1" name="companions[]">
                    <option value="">---</option>
                    @foreach($companion as $companion)
                        <option value="{{ $companion->id }}">{{ $companion->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-sm-5" id="companion2">
                <label for="companion2" class="form-label">Acompanhante 2:</label>
                <select class="form-select" id="companion2" name="companions[]" disabled>
                    <option value="">---</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-3">
                <label for="part" class="form-label">Trecho: <span class="text-danger fw-bold">*</span></label>
                <select class="form-select" id="part" name="part" required>
                    <option value="">---</option>
                    @foreach($part as $part)
                        <option value="{{ $part->id }}">{{ $part->part }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-sm-3">
                <label for="specialty" class="form-label">Especialidade: <span class="text-danger fw-bold">*</span></label>
                <select class="form-select" id="specialty" name="specialty" required>
                    <option value="">---</option>
                    @foreach($specialty as $specialty)
                        <option value="{{ $specialty->id }}">{{ $specialty->specialty }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-sm-6">
                <label for="process" class="form-label">Numero do Processo <span class="text-danger fw-bold">*</span></label>
                <input type="text" class="form-control text-uppercase" id="process" name="process" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-1 d-flex align-items-center">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="going" id="going" value="1">
                    <label class="form-check-label" for="going">Ida</label>
                </div>
            </div>

            <div class="col-sm-4">
                <label for="date_going" class="form-label">Data da Ida <span class="text-danger fw-bold d-none going">*</span></label>
                <input type="date" class="form-control goings" id="date_going" name="date_going" min="{{ date('Y-m-d') }}" required disabled>
            </div>

            <div class="col-sm-3">
                <label for="date_consult" class="form-label">Data da Consulta</label>
                <input type="date" class="form-control goings" id="date_consult" name="date_consult" min="{{ date('Y-m-d') }}" disabled>
            </div>

            <div class="col-sm-4" id="goings">
                <label for="going_request" class="form-label">Numero da Requisição <span class="text-danger fw-bold d-none going">*</span></label>
                <input type="text" class="form-control goings" id="going_request" name="going_request" required disabled>
            </div>
        </div>

        <div class="row mb-3 d-flex align-items-center">
            <div class="col-sm-1">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="return" id="return" value="1">
                    <label class="form-check-label" for="return">Volta</label>
                </div>
            </div>

            <div class="col-sm-5">
                <label for="date_return" class="form-label">Data da Volta <span class="text-danger fw-bold d-none return">*</span></label>
                <input type="date" class="form-control returns" id="date_return" name="date_return" min="{{ date('Y-m-d') }}" required disabled>
            </div>

            <div class="col-sm-6" id="returns">
                <label for="return_request" class="form-label">Numero da Requisição <span class="text-danger fw-bold d-none return">*</span></label>
                <input type="text" class="form-control returns" id="return_request" name="return_request" required disabled>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-sm-12">
                <label for="obs" class="form-label">Observação</label>
                <textarea class="form-control" name="obs" id="obs" rows=4></textarea>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-plus"></i> Cadastrar
                </button>

                <button type="reset" class="btn btn-warning">
                    <i class="fa-solid fa-eraser"></i> Limpar
                </button>

                <a href="/travel" class="btn btn-danger">
                    <i class="fa-solid fa-angles-left"></i> Voltar
                </a>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#companion1').change(function(){
                $.ajax({
                    url: "{{ route('loadCompanion') }}",
                    data: {
                        'id': $(this).val(),
                        'patient': {{ $patient->id }}
                    },
                    success: function(data){
                        $('#companion2').html(data);
                    }
                });
            });

           $('#going').change(function(){
               if($(this).prop('checked')) {
                    $('.going').removeClass('d-none');
                    $('.goings').removeAttr('disabled');
                    $.ajax({
                        url: "{{ route('loadNumber') }}",
                        data: {
                            'going': $(this).val()
                        },
                        success: function(data){
                            $('#goings').html(data);
                        }
                    });
                } else {
                    $('.going').addClass('d-none');
                    $('.goings').attr('disabled', 'disabled');
                    $('.goings').attr('value', '');
                }
            });

            $('#return').change(function(){
                if($(this).prop('checked')) {
                    $('.return').removeClass('d-none');
                    $('.returns').removeAttr('disabled');
                    $.ajax({
                        url: "{{ route('loadNumber') }}",
                        data: {
                            'return': $(this).val()
                        },
                        success: function(data){
                            $('#returns').html(data);
                        }
                    });
                } else {
                    $('.return').addClass('d-none');
                    $('.returns').attr('disabled', 'disabled');
                    $('.returns').attr('value', '');
                }
            });
        });
    </script>
@endsection
