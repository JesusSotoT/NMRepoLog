<script>

 $(document).ready(function(){
 	
 	$("#i1249").prop("type","hidden");
 	$("#i1249").val("IVA");

 	$("#i1249").before("<select id='combo-i' onchange='cambia();'><option value='IVA'>IVA</option><option value='IEPS'>IEPS</option></select>")


});
 function cambia(){
 	$("#i1249").val($('#combo-i').val());
 }
</script>