<form action="colsunt" method="post">
    @csrf
    <div class="mb-4">
        <label for="cpf_process" class="form-label">CPF</label>
        <input type="text" class="form-control" id="cpf_process" name="search[]" placeholder="CPF">
    </div>
    <div class="mb-4">
        <label for="process" class="form-label">Número do Processo</label>
        <input type="text" class="form-control" id="process" name="search[]" placeholder="Número do Processo">
    </div>

    <div class="mb-2">
        <button type="submit" class="btn-login">
            Logar
        </button>
    </div>
</form>