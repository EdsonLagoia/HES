@extends('layouts.access')

@section('content')
    <form action="authentication" method="post">
        @csrf
        <div class="mb-4">
            <label for="cpf" class="form-label">CPF</label>
            <input type="cpf" class="form-control" id="cpf" name="cpf" placeholder="CPF" required>
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">Senha</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Senha" required>
                <button type="button" class="btn btn-secondary btn-show-password input-group-text">
                    <i id="pass" class="fa-solid fa-eye"></i>
                    <i id="text" class="fa-solid fa-eye-slash" style="display: none"></i>
                </button>
            </div>
        </div>

        <div class="mb-2">
            <button type="submit" class="btn-login">
                Logar
            </button>
        </div>
    </form>
    
    <script src="/js/forms.js" type="text/javascript"></script>
    <script src="/js/password.js" type="text/javascript"></script>
@endsection