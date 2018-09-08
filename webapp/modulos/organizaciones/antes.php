<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<h1>sdf</h1>
<script>
    var lbestado = '';
    var lbmunicio = '';
	$(document).ready(function(){
        var btn_father = $("#i184_div").parent()
        btn_father.first().next().hide();
        /*
        $('#i186').html('');
        $.ajax({
            url: '../../modulos/appministra/ajax.php?c=reportes&f=estados',
            type: 'post',
            dataType: 'json',
            data:{idpais:1}
        })
        .done(function(data) {
            //$('#i186').append('<option value="0">Selecciona un estado</option>'); 
            $.each(data, function(index, val) {
                  $('#i186').append('<option value="'+val.idestado+'">'+val.estado+'</option>');  
            });
            $('#i186').select2();
        })
        */

//		var pais = $('#i2494').val(); // -mx 1 -GU 85 -CR 54 -COl47
		$("#i2494 option").each(function(){            
            if ($(this).attr('value') != 1 && $(this).attr('value') != 85 && $(this).attr('value') != 54 && $(this).attr('value') != 47){
                 $(this).remove();
            }
    	});
        $('#i2494').select2();
        $.post("../../modulos/cont/ajax.php?c=edu&f=tipo_inst",
            {},
             function(data)
             {
                if(!parseInt(data))
                {
                    $(".row").hide()
                    document.write("<b style='color:red;font-size:18px;'>Las instancias tipo << Estudiante >> no pueden guardar ni modificar la organizacion.</b>")
                }
             });

	});
	$('#i2494').change(function(){   
        var idpais = $('#i2494').val();
        if(idpais == 1){  lbestado = 'Estado'; lbmunicio = 'Municipio'; }  // mx
        if(idpais == 85){ lbestado = 'Departamento'; lbmunicio = 'Cabecera';} // gu
        if(idpais == 54){ lbestado = 'Estado'; lbmunicio = 'Municipio'; } // cr
        if(idpais == 47){ lbestado = 'Estado'; lbmunicio = 'Municipio'; } // co

        $("#lbl186").text(lbestado+':'); 
        $("#lbl187").text(lbmunicio+':');

        $('#i186, #i187').html('');
        //$('#i186').append('<option value="0">Selecciona un '+lbestado+'</option>');
        //$('#i187').append('<option value="0">Selecciona un '+lbmunicio+'</option>'); 
        $.ajax({
            url: '../../modulos/appministra/ajax.php?c=reportes&f=estados',
            type: 'post',
            dataType: 'json',
            data:{idpais:idpais}
        })
        .done(function(data) {
            
            $.each(data, function(index, val) {
                  $('#i186').append('<option value="'+val.idestado+'">'+val.estado+'</option>');  
            });
            $('#i186').select2();
        })


    });
</script>