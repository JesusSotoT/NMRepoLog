$.fn.extend({
	JSONTable : function(data){
		return this.each(function(){
			switch( Object.prototype.toString.call(data) )
			{
				case "[object Array]":
					//alert("Dato 1: Es un array");
					hasOnlyObjects = true;
					for (var i = data.length - 1; i >= 0; i--) {
						if ( Object.prototype.toString.call( data[i] ) != "[object Object]" )
						{
							hasOnlyObjects = false;
							break;
						}
					}
					if(hasOnlyObjects === false) 
						console.error( "El dato debe ser un array de objetos en su totalidad." );

					break;
				case "[object String]":
					try
					{
						data = $.parseJSON( data );
					}
					catch(e)
					{
						console.error( "Formato JSON Invalido : \n" + data );
					}
					
					break;
			}

			table = "";
			for (var i = data.length - 1; i >= 0; i--)
			{
				if (i === data.length -1 )
				{
					table += "<table>";
					table += "	<thead>";
					for(var j in data[i])
						table += "		<th>" + j + "</th>";
					
					table +="	</thead>";
					table +="	<tbody>";
					table += "		<tr>";
					for(var j in data[i])
						table += "		<td>" + data[i][j] + "</td>";
					table += "		</tr>";
				}
				else
				{
					table += "		<tr>";
					for(var j in data[i])
						table += "			<td>" + data[i][j] + "</td>";
					table += "		</tr>";
				}
			}
			table += "</tbody></table>";
			this.innerHTML = table;
		});
	}
});