document.querySelector('.btn-show-password').addEventListener('click', function() {
    if(document.querySelector('#password').getAttribute('type') == 'password') {
        document.querySelector('#password').setAttribute('type', 'text');
        document.querySelector('#pass').style.display = 'none';
        document.querySelector('#text').style.display = 'block';
    } else {
        document.querySelector('#password').setAttribute('type', 'password');
        document.querySelector('#pass').style.display = 'block';
        document.querySelector('#text').style.display = 'none';
    }
});

document.querySelector('.btn-show-password-confirm').addEventListener('click', function() {
    if(document.querySelector('#password_confirm').getAttribute('type') == 'password') {
        document.querySelector('#password_confirm').setAttribute('type', 'text');
        document.querySelector('#pass_confirm').style.display = 'none';
        document.querySelector('#text_confirm').style.display = 'block';
    } else {
        document.querySelector('#password_confirm').setAttribute('type', 'password');
        document.querySelector('#pass_confirm').style.display = 'block';
        document.querySelector('#text_confirm').style.display = 'none';
    }
});