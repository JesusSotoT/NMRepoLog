$( document ).ready(function() {
  $( "#tabs" ).tabs();
  $("#selectQry2").multiselect(); 
});

function respuesta_com(id){
  coment = $('#val_area').val();
  if(coment.match(/<|>|\'|\"|-/)){
    alert('El comentario tiene caracteres invalidos');
    return false;
  }
  if(coment==''){
    alert('Escribe un comentario');
    return false;
  }
  
  $("#br__br").css("display","none");
  $("#wa__wa").css("display","block");
  url='smsAjax.php';
  $.ajax({
    url:url,
    type: 'POST',
    data: {funcion:'crear_comentario', coment:coment, id:id, con:1},
    success: function(obj){
      $('#coms_container').prepend('<div class="area__com"><textarea align="center" id="val_x_area" disabled="">'+coment+'</textarea></div>');
      $("#wa__wa").css("display","none");
      $("#br__br").css("display","block");
      $("#val_area").val("");
    }
  }).done(function(response){
    $("#callback").html(response);;  
  });
}
