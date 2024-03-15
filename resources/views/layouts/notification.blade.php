@if(isset($erro) || isset($success))
    <div class="alert alert-{{ isset($erro) ? 'danger' : 'success' }}" role="alert" id="notifications">
        {{ isset($erro) ? $erro : $success }}
    </div>
@endif
