<script>
$(document).ready(function(){
	
    $("#i1343").hide();
    $("#i1343").parent().append('<select id="i1343c"><option>COM1</option><option>COM2</option><option>COM3</option><option>COM4</option><option>COM5</option><option>COM6</option><option>COM7</option><option>COM8</option><option>COM9</option><option>COM10</option></select>');
    $("#i1343c").val($("#i1343").val());
    $("#i1343c").change(function(){
    	$("#i1343").val($(this).val());
    });
    
    $("#i1344").hide();
    $("#i1344").parent().append('<select id="i1344c"><option>110</option><option>150</option><option>300</option><option>600</option><option>1200</option><option>2400</option><option>4800</option><option>9600</option><option>19200</option></select>');
	$("#i1344c").val($("#i1344").val());
    $("#i1344c").change(function(){
    	$("#i1344").val($(this).val());
    });

    $("#i1345").hide();
    $("#i1345").parent().append('<select id="i1345c"><option>none</option><option>mark</option><option>odd</option><option>space</option><option>even</option></select>');
	$("#i1345c").val($("#i1345").val());
    $("#i1345c").change(function(){
    	$("#i1345").val($(this).val());
    });
	
    $("#i1346").hide();
    $("#i1346").parent().append('<select id="i1346c"><option>1</option><option>2</option></select>');
	$("#i1346c").val($("#i1346").val());
    $("#i1346c").change(function(){
    	$("#i1346").val($(this).val());
    });
	
    $("#i1347").hide();
    $("#i1347").parent().append('<select id="i1347c"><option>7</option><option>8</option></select>');
	$("#i1347c").val($("#i1347").val());
    $("#i1347c").change(function(){
    	$("#i1347").val($(this).val());
    });
});
</script>
