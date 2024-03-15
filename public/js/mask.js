$(document).ready(function(){
  $('#cep').mask('00.000-000');
  $('#phone').mask('(00) 0 0000-0000');
  $('#cpf').mask('000.000.000-00');
  $('#cnpj').mask('00.000.000/0000-00');
});

$("#name").on("input", function() {
  var regexp = /[^a-zA-Zà-úÀ-Ú '-.]/g;
  if(this.value.match(regexp)){
    $(this).val(this.value.replace(regexp,''));
  }
});

$("#page").on("input", function() {
  var regexp = /[^a-z-]/g;
  if(this.value.match(regexp)){
    $(this).val(this.value.replace(regexp,''));
  }
});