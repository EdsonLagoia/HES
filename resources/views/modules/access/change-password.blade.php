@extends('layouts.access')

@section('content')
    <form action="new-password" method="post">
        @csrf
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

        <div class="mb-4">
            <label for="password_confirm" class="form-label">Confirmar Senha</label>
            <div class="input-group">
                <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirmar Senha" required>
                <button type="button" class="btn btn-secondary btn-show-password-confirm input-group-text">
                    <i id="pass_confirm" class="fa-solid fa-eye"></i>
                    <i id="text_confirm" class="fa-solid fa-eye-slash" style="display: none"></i>
                </button>
            </div>
        </div>

        <div class="mb-2">
            <button type="submit" class="btn-login">
                Alterar Senha
            </button>
        </div>
    </form>
    
    <script src="/js/password.js" type="text/javascript"></script>
@endsection