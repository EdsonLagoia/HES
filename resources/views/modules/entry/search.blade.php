<div class="col-sm-12 mb-2">
    <h3>Resultados ({{ $result }})</h3>
</div>

<div class="col-sm-12">
    <table class="table table-responsive table-striped table-secondary" width=100%>
        @if($search)
            @foreach($search as $search)
                <tr class="align-middle">
                    <td width=75%>
                        <table width=100%>
                            <tr>
                                <td>
                                    <p class="mb-0"><b>Nome: </b> {{ $search->social_name ? $search->social_name : $search->name }}
                                    <p class="mb-0">
                                        <b>D. Nasc.:</b> {{ date('d/m/Y', strtotime($search->birth_date)) }}
                                        <b class="ps-4">CPF: </b> {{ $search->cpf }}
                                        <b class="ps-4">SUS: </b> {{ $search->sus }}
                                    </p>
                                    <p class="mb-0"><b>Telefone: </b> {{ $search->phone }}</p>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td width=25% class="text-end">
                        <form action="/entry/store/{{ $search->id }}" method="post">
                            @csrf
                            <a href="/patient/{{ $search->id }}" class="btn btn-info" title="Editar Paciente">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#entry" title="Selecionar Paciente">
                                <i class="fa-solid fa-circle-check"></i>
                            </button>

                            <div class="modal fade" id="entry" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="entryLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content text-dark text-start">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="entryLabel">Registrar Entrada</h1>
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
                    </td>
                </tr>
            @endforeach
        @endif
    </table>
</div>
