/*
    Autor : CMOP
    fecha: 22/04/2013
*/
;
(function($) {
    testint=1;
    $.jgrid.extend({
        exportarExcelCliente: function(o) {
            var archivoExporta, hojaExcel;
            archivoExporta = {
                worksheets: [[]],
                creator: "Arcmop",
                created: new Date(),
                lastModifiedBy: "Arcmop",
                modified: new Date(),
                activeWorksheet: 0
            };
            hojaExcel = archivoExporta.worksheets[0];
            hojaExcel.name = o.nombre;

            var arrayCabeceras = new Array();
            
            arrayCabeceras = $(this).jqGrid('getDataIDs'); 
                       //console.log(arrayCabeceras);
                       //return false;
            var dataFilaGrid = $(this).jqGrid('getRowData',arrayCabeceras[0]); 
            

            var dataFilaGrid = $(this).getRowData(arrayCabeceras[0]); 
            var nombreColumnas = new Array();
            var ii = 0;
            
            /*var j=0;
            if(o.nombre=='planeacion'){
                j=1;
            }*/
            numerocols=0;
           
            for (var i in dataFilaGrid) {



                if(hojaExcel.name=='traspasos'){
                    
                    if(numerocols==0){
                        console.log(i);
                        numerocols++;
                        continue;
                    }else{
                        nombreColumnas[ii++] = i;
                        numerocols++;
                        continue;
                    }
                        //r=dataFilaGrid[nombreColumnas[0]].split(' ');
                        //dataFilaGrid[nombreColumnas[0]]=r[0];
                }

                if(hojaExcel.name=='remesas'){
                    console.log(i);
                    if(numerocols==8 || numerocols==7){
                        console.log(i);
                        numerocols++;
                        continue;
                    }else{
                        nombreColumnas[ii++] = i;
                        numerocols++;
                        continue;
                    }

                  
                        //r=dataFilaGrid[nombreColumnas[0]].split(' ');
                        //dataFilaGrid[nombreColumnas[0]]=r[0];
                }
                //console.log(i);
                if(hojaExcel.name=='hojarequisComp' && numerocols==1){
                    nombreColumnas[ii++] = 'Estatus';
                    //dataFilaGrid[nombreColumnas[numerocols]]="lalala";
                    //arrayCabeceras.push("12");
                }


                if(i=='icon' || i=='Precio_compra_' || i=='Entrada__' || i=='salida__' || i=='impsem__'){

                }else{
                    nombreColumnas[ii++] = i;
                }
            numerocols++;
            }
            
            //console.log(arrayCabeceras);
            //console.log(hojaExcel);
            //console.log(dataFilaGrid);

            var obra=$('#nombre_obra_x').val();
            if(typeof obra != "undefined"){
                hojaExcel.push(['Obra: '+obra]);
                hojaExcel.push([' ']);
            }

            hojaExcel.push(nombreColumnas);
            var dataFilaArchivo;



            if(hojaExcel.name=='hojarequispp'||hojaExcel.name=='subcon'||hojaExcel.name=='dest'||hojaExcel.name=='cli'||hojaExcel.name=='prov'||hojaExcel.name=='chica'||hojaExcel.name=='ind'){
                var Estatus='h';
                dataFilaGrid[dataFilaGrid.length]=Estatus;
                /*arrayCabeceras[arrayCabeceras.length]=""+arrayCabeceras.length+"";*/
                nombreColumnas[nombreColumnas.length]='Estatus';
            }
            console.log(dataFilaGrid);
            console.log(nombreColumnas);



                 if(hojaExcel.name=='cobros'){

                   nombreColumnas[6]='';
                   console.log(nombreColumnas);
                    //dataFilaGrid[nombreColumnas[numerocols]]="lalala";
                    //arrayCabeceras.push("12");
                }


            for (i = 0; i < arrayCabeceras.length; i++) {
                
                dataFilaGrid = $(this).jqGrid('getRowData',arrayCabeceras[i]); 


                   // console.log(nombreColumnas);
                //console.log(arrayCabeceras[i]);
                //console.log(dataFilaGrid);
                if(o.nombre=='hojarequisComp'){
                    r=dataFilaGrid[nombreColumnas[0]].split(' ');
                    //console.log(r);
                }

                dataFilaArchivo = new Array();
               
                for (j = 0; j < nombreColumnas.length; j++) {
                    dataFilaGrid[nombreColumnas[j]];

                    if(o.nombre=='hojarequis'){
                        //r=dataFilaGrid[nombreColumnas[0]].split(' ');
                        //y=dataFilaGrid[nombreColumnas[0]].split('>');
                       
                   


                        //dataFilaGrid[nombreColumnas[0]]=r[0];
                         
                    }


if(o.nombre=='hojarequispp'){

                        r=dataFilaGrid[nombreColumnas[0]].split(' ');
                        y=dataFilaGrid[nombreColumnas[0]].split('>');
                          if(j==0){
                      
                        if(y[1]!=null){
                     

                       if((y[1].substr(0,y[1].length-6)).length<22){
                                

                        dataFilaGrid[nombreColumnas[nombreColumnas.length-1]]=(y[1].substr(0,y[1].length-6));}
                            else{
                                    
                                   dataFilaGrid[nombreColumnas[nombreColumnas.length-1]]='Estimación Pendiente';

                            }




                    }
                        


                        dataFilaGrid[nombreColumnas[0]]=r[0];
                         
                    }}

     if(o.nombre=='hojarequis2'){

                        r=dataFilaGrid[nombreColumnas[0]].split(' ');
                        y=dataFilaGrid[nombreColumnas[0]].split('>');
                          if(j==0){
                      
                        if(y[1]!=null){
                     

                       if((y[1].substr(0,y[1].length-6)).length<22){
                                

                        dataFilaGrid[nombreColumnas[nombreColumnas.length-1]]=(y[1].substr(0,y[1].length-6));}
                            else{
                                    
                                   dataFilaGrid[nombreColumnas[nombreColumnas.length-1]]='Estimación Pendiente';

                            }




                    }
                        


                        dataFilaGrid[nombreColumnas[0]]=r[0];
                         
                    }}


                       if(o.nombre=='cobrados'){
                        r=dataFilaGrid[nombreColumnas[2]].split(' ');
                        dataFilaGrid[nombreColumnas[2]]=r[0];
                    }


                    if(o.nombre=='hojarequisComp'){
                        dataFilaGrid[nombreColumnas[0]]=r[0];
                        dataFilaGrid[nombreColumnas[1]]="---";

                        var regexp1 = new RegExp(/value/g);
                        var regexp2 = new RegExp(/070/g);
                        var regexp3 = new RegExp(/ff0000/g);

                        if(r[2].match(regexp1)=='value' ){
                            dataFilaGrid[nombreColumnas[1]]="Pendiente";
                        }else if(r[2].match(regexp2)=='070') {
                            dataFilaGrid[nombreColumnas[1]]="Autorizada";
                        }else if(r[2].match(regexp3)=='ff0000') {
                            dataFilaGrid[nombreColumnas[1]]="Cancelada";
                        }else{
                            dataFilaGrid[nombreColumnas[1]]=" ";
                        }


                        
                    }


                    if(o.nombre=='Arbolp'){
                        if(j==13){
                            r=dataFilaGrid[nombreColumnas[j]].split('value="');
                            t=r[1].split('"');
                            dataFilaGrid[nombreColumnas[j]]=t[0];
                        }
                    }
                    if(o.nombre=='asignar'){
                        if(j==8){
                            r=dataFilaGrid[nombreColumnas[j]].split(';">');
                            t=r[1].split('<');
                            dataFilaGrid[nombreColumnas[j]]=t[0];
                        }
                    }
                    if(o.nombre=='pedidos2'){
                        if(j==0){
                            r=dataFilaGrid[nombreColumnas[j]].split('<');
                            dataFilaGrid[nombreColumnas[j]]=r[0];
                        }
                    }
 

                    if(o.nombre=='subcon'){
                       
                       
                        if(j==1){
                            
                            r=dataFilaGrid[nombreColumnas[j]].split('<');
                           r2=r[1].split('>');
                            dataFilaGrid[nombreColumnas[j]]=r[0];

                        if(r2[1].length>2){
                                
                            dataFilaGrid[nombreColumnas[nombreColumnas.length-1]]=r2[1].substr(11);
                        }else{  
                            dataFilaGrid[nombreColumnas[nombreColumnas.length-1]]='Pendiente';}
                              
                        }
                       
                        
                        
                    }


                    if(o.nombre=='dest'){
                        if(j==1){
                            r=dataFilaGrid[nombreColumnas[j]].split('<');
                            r2=r[1].split('>');
                            dataFilaGrid[nombreColumnas[j]]=r[0];

                        if(r2[1].length>2){
                                
                            dataFilaGrid[nombreColumnas[nombreColumnas.length-1]]=r2[1].substr(11);
                        }else{  
                            dataFilaGrid[nombreColumnas[nombreColumnas.length-1]]='Pendiente';}
                              
                        
                        }
                    }
                    if(o.nombre=='prov'){
                        if(j==1){
                            r=dataFilaGrid[nombreColumnas[j]].split('<');
                            r2=r[1].split('>');
                            dataFilaGrid[nombreColumnas[j]]=r[0];

                        if(r2[1].length>2){
                                
                            dataFilaGrid[nombreColumnas[nombreColumnas.length-1]]=r2[1].substr(11);
                        }else{  
                            dataFilaGrid[nombreColumnas[nombreColumnas.length-1]]='Pendiente';}
                              
                        
                        }
                    }
                    if(o.nombre=='cli'){
                        if(j==1){
                            r=dataFilaGrid[nombreColumnas[j]].split('<');
                            r2=r[1].split('>');
                            dataFilaGrid[nombreColumnas[j]]=r[0];

                        if(r2[1].length>2){
                                
                            dataFilaGrid[nombreColumnas[nombreColumnas.length-1]]=r2[1].substr(11);
                        }else{  
                            dataFilaGrid[nombreColumnas[nombreColumnas.length-1]]='Pendiente';}
                              
                        
                        }
                    }
                     if(o.nombre=='chica'){
                        if(j==0){
                            r=dataFilaGrid[nombreColumnas[j]].split('<');
                             r2=r[1].split('>');
                            dataFilaGrid[nombreColumnas[j]]=r[0];

                        if(r2[1].length>2){
                                
                            dataFilaGrid[nombreColumnas[nombreColumnas.length-1]]=r2[1].substr(11);
                        }else{  
                            dataFilaGrid[nombreColumnas[nombreColumnas.length-1]]='Pendiente';}
                              
                        
                        }

                        
                    }

                     if(o.nombre=='ind'){
                        if(j==1){
                            r=dataFilaGrid[nombreColumnas[j]].split('<');
                           
                                 r2=r[1].split('>');

                            dataFilaGrid[nombreColumnas[j]]=r[0];

                        if(r2[1].length>2){
                               
                            dataFilaGrid[nombreColumnas[nombreColumnas.length-1]]=r2[1].substr(11);
                        }else{  
                            dataFilaGrid[nombreColumnas[nombreColumnas.length-1]]='Pendiente';}
                              
                        
                        
                        }
                    }
                    if(o.nombre=='remesas'){
                        if(j==3){
                            if(dataFilaGrid[nombreColumnas[j]]!=""){
                            r=dataFilaGrid[nombreColumnas[j]].split('value=');
                            r2=r[1].split('>');
                            dataFilaGrid[nombreColumnas[j]]=r2[0].replace(/"/g,"");}


                        
                              
                        
                        
                        }
                    }
                       if(o.nombre=='cobros'){
                        if(j==6){
                            
                            dataFilaGrid[nombreColumnas[j]]='';


                        
                              
                        
                        
                        }
                    }

                  


                  if(o.nombre=='Arbolp'){
                        if(j==0||j==1||j==2||j==3){
                            if(dataFilaGrid[nombreColumnas[j]]!=""){
                            r=dataFilaGrid[nombreColumnas[j]].split('- Importe Total');
                            dataFilaGrid[nombreColumnas[j]]=r[0];}


                        
                              
                        
                        
                        }
                    }
                    
                  /*  if(o.nombre=='remesas'){
                        if(j==2){
                            r=dataFilaGrid[nombreColumnas[j]].split('name="');
                            t=r[1].split('">');
                            dataFilaGrid[nombreColumnas[j]]='EST-'+''+t[0];
                        }
                    }*/

                    //console.log(dataFilaGrid[nombreColumnas[0]]);
                    dataFilaArchivo.push(dataFilaGrid[nombreColumnas[j]]);
                }
             //   console.log(dataFilaArchivo);
                hojaExcel.push(dataFilaArchivo);
            }
            return window.location = xlsx(archivoExporta).href();
        },
        exportarTextoCliente: function(o) {
            var arrayCabeceras = new Array();
            arrayCabeceras = $(this).getDataIDs();
            var dataFilaGrid = $(this).getRowData(arrayCabeceras[0]);
            var nombreColumnas = new Array();
            var ii = 0;
            var textoRpta = "";
            for (var i in dataFilaGrid) {
                nombreColumnas[ii++] = i;
                textoRpta = textoRpta + i + "\t";
            }
            textoRpta = textoRpta + "\n";
            for (i = 0; i < arrayCabeceras.length; i++) {
                dataFilaGrid = $(this).getRowData(arrayCabeceras[i]);
                for (j = 0; j < nombreColumnas.length; j++) {
                    textoRpta = textoRpta + dataFilaGrid[nombreColumnas[j]] + "\t";
                }
                textoRpta = textoRpta + "\n";
            }
            textoRpta = textoRpta + "\n";
            return textoRpta;
        }
    });
})(jQuery);