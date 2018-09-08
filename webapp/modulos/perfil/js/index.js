var catalogo = "pago";
var formulario = "frm";
var columnas_centradas = [ 4 ];

var tabla_modulo_pago_productos;
var tabla_modulo_pago_pendiente_productos;

$(document).ready(function() {

   /*tabla_modulo_pago_productos = $('#data_table_pago_productos').DataTable({
      language: {
         url: '//cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json'
      },
      "columnDefs": [
         { className: "dt-body-center", "targets": [ ] }
      ]
   });

   tabla_modulo_pago_pendiente_productos = $('#data_table_pago_pendiente_productos').DataTable({
      language: {
         url: '//cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json'
      },
      "columnDefs": [
         { className: "dt-body-center", "targets": [ ] }
      ]
   });

   $("#ano").datepicker({
      language : 'es',
      minViewMode: 2,
      format: 'yy'
   });

   $("#mes").datepicker( {
      language : 'es',
      format: "mm",
      viewMode: "months",
      minViewMode: "months"
   });

   $("#tarjeta_agregar").click(function(){
      if(validarFormulario("frm_tarjeta")){
         var datosfrm = new FormData(document.getElementById("frm_tarjeta"));
         $.ajax({
            type: "POST",
            url: "../perfil/ajax.php?c=srpago&f=agregarTarjeta",
            dataType: "json",
            data: datosfrm,
            processData: false,
            contentType: false,
            success: function(respuesta){
               if(respuesta.status !== undefined && respuesta.status == true){
                  mensajeIcono("success", "", "Información guardada correctamente", function(){
                     $("#tarjeta_limpiar").click();
                     obtenerTarjetas();
                  });
               }else{
                  mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
               }
            },
            error: function(error){
               mensajeIcono("error", "Un momento...", "No se ha podido completar esta acción, por favor intentalo nuevamente", function(){});
            }
         });
      }
   });

   $("#tarjeta_limpiar").click(function(){
      limpiarFormulario("frm_tarjeta");
   });

   $("#numero").validateCreditCard(function(e) {
      $("#banco").val((null != e.card_type) ? e.card_type.name : "");
      return null == e.card_type ? void $(".vertical.maestro").slideUp({
            duration: 200
         }).animate({
            opacity: 0
         }, {
            queue: !1,
            duration: 200
         }) : ($(this).addClass(e.card_type.name), "maestro" === e.card_type.name ? $(".vertical.maestro").slideDown({
            duration: 200
         }).animate({
            opacity: 1
         }, {
            queue: !1
         }) : $(".vertical.maestro").slideUp({
            duration: 200
         }).animate({
            opacity: 0
         }, {
            queue: !1,
            duration: 200
         }), e.valid ? $(this).addClass("valid") : $(this).removeClass("valid"))
         }, {
            accept: ["visa", "visa_electron", "mastercard", "amex"]
   });*/

   //obtenerTarjetas();
   obtenerPerfil();
   obtenerProductos();
   obtenerDatosMailing();

   $("#cambiar_contrasena").click(function(){
      $('#modal-cambiar-contrasena').off().on('shown.bs.modal', function () {
      }).modal({backdrop: 'static', keyboard: false, show: true});
   });

   $("#guardar_contrasena").click(function(){
      cambiarContrasena();
   });

   /*$("#btn_pagar_suscripcion").click(function(){
      var modal = $('#modal-pagar-suscripcion').off().on('shown.bs.modal', function () {
         if(popularTablaConParametros("pago", {}, tabla_modulo_pago_pendiente_productos, "obtenerPagoPendiente", false) == 0){
            mensajeIcono("success", "¡Listo!", "No hay ningún pago pendiente por realizar", function(){
               modal.modal("hide");
            });
         }
      }).modal({backdrop: 'static', keyboard: false, show: true});
   });

   $("#tipo_pago").change(function(){
      if(this.value == 1){
         $("#pago_tarjeta").removeClass("hidden");
         $("#btn_pago_paypal").addClass("hidden");
      } else if(this.value == 2) {
         $("#pago_tarjeta").addClass("hidden");
         $("#btn_pago_paypal").removeClass("hidden");
      } else {
         $("#pago_tarjeta").addClass("hidden");
         $("#btn_pago_paypal").addClass("hidden");
      }
   });

   $("#btn_pago_paypal").click(function(){
      procesarPago(2);
   });

   $("#btn_pago_tarjeta").click(function(){
      procesarPago(1);
   });*/

   /*if(typeof pago_ok != 'undefined'){
      $("#btn_pagar_suscripcion").attr("data-toggle", "tooltip").attr("data-placement", "left").attr("title", "Se encuentra al día en los pagos de los productos").prop("disabled", true);
   } else {
      $("#btn_pagar_suscripcion").removeAttr("data-toggle").removeAttr("title").removeAttr("data-placement").prop("disabled", false);
   }*/

   $("#btn_config_mailing").click(function(){
      procesarDatosMailing();
   });

   $("#mailing_tipo").change(function(){
      if($("#mailing_tipo").val() == 1) $(".mailing_info").removeClass("hidden");
      else $(".mailing_info").addClass("hidden");

      if($("#mailing_tipo").val() == 5) $("#mailing_config").removeClass("hidden");
      else $("#mailing_config").addClass("hidden");
   });


   $('[data-toggle="tooltip"]').tooltip();

});

function procesarDatosMailing(){
   if(validarFormulario("frm_mailing")){
      var datosfrm = new FormData(document.getElementById("frm_mailing"));
      $.ajax({
         type: "POST",
         url: "../perfil/ajax.php?c=mailing&f=guardar",
         dataType: "json",
         data: datosfrm,
         processData: false,
         contentType: false,
         success: function(respuesta){
            if(respuesta.status !== undefined && respuesta.status == true){
               mensajeIcono("success", "OK", "Tus datos de mailing han sido cambiados correctamente", function(){});
            } else {
               mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
            }
         },
         error: function(error){
            mensajeIcono("error", "Un momento...", "No se ha podido completar esta accion, por favor intentalo nuevamente", function(){});
         }
      });
   }
}

function obtenerDatosMailing(){
   $.ajax({
      type: "POST",
      url: "../perfil/ajax.php?c=mailing&f=obtener",
      dataType: "json",
      data: { },
      success: function(respuesta){
         if(respuesta.status !== undefined && respuesta.status == true){
            $.each(respuesta.informacion, function(index, elemento) {
               if($("#frm_mailing #" + index).hasClass("selector")) {
                  if(elemento != null){
                     $("#frm_mailing #" + index).val(elemento).change();
                  }
               } else {
                  $("#frm_mailing #" + index).val(elemento);
               }
            });
         } else {
            mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
         }
      },
      error: function(error){
         mensajeIcono("error", "Un momento...", "No se ha podido completar esta accion, por favor intentalo nuevamente", function(){});
      }
   });
}

function procesarPago(tipo){
   var datos = { tipo: tipo };
   if(tipo == 1){
      if($("#tarjeta_pago").val() == '0'){
         mensajeIcono("error", "Un momento...", "Debes seleccionar una tarjeta a la que se le hará el cargo", function(){});
         return;
      }
      datos["crd"] = $("#tarjeta_pago").val();
   }
   $.ajax({
      type: "POST",
      url: "../perfil/ajax.php?c=pago&f=pagar",
      dataType: "json",
      data: datos,
      beforeSend: function(){
         $("#btn_pago_paypal, #btn_pago_tarjeta").prop("disabled", true);
      },
      success: function(respuesta){
         if(respuesta.status !== undefined && respuesta.status == true){
            if(tipo == 1){
               mensajeIcono("success", "¡Listo!", "El pago ha sido realizado con éxito", function(){
                  window.location.reload();
               });
            } else {
               window.parent.location.href = respuesta.link;
            }
         } else {
            mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
         }
      },
      error: function(error){
         mensajeIcono("error", "Un momento...", "No se ha podido completar esta accion, por favor intentalo nuevamente", function(){});
      },
      complete: function(){
         $("#btn_pago_paypal, #btn_pago_tarjeta").prop("disabled", false);
      }
   });
}

function obtenerPerfil(){
   $.ajax({
      type: "POST",
      url: "../perfil/ajax.php?c=perfil&f=informacion",
      dataType: "json",
      data: { },
      success: function(respuesta){
         if(respuesta.status !== undefined && respuesta.status == true){
            $.each(respuesta.informacion, function(index, elemento) {
               $("#" + index).val(elemento);
            });
         } else {
            mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
         }
      },
      error: function(error){
         mensajeIcono("error", "Un momento...", "No se ha podido completar esta accion, por favor intentalo nuevamente", function(){});
      }
   });
}

function cambiarContrasena(){
   if(validarFormulario("frm-cambio-contrasena")){
      var datosfrm = new FormData(document.getElementById("frm-cambio-contrasena"));
      $.ajax({
         type: "POST",
         url: "../perfil/ajax.php?c=perfil&f=cambiarContrasena",
         dataType: "json",
         data: datosfrm,
         processData: false,
         contentType: false,
         success: function(respuesta){
            if(respuesta.status !== undefined && respuesta.status == true){
               mensajeIcono("success", "OK", "Tu contraseña ha sido cambiada correctamente", function(){});
            } else {
               mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
            }
         },
         error: function(error){
            mensajeIcono("error", "Un momento...", "No se ha podido completar esta accion, por favor intentalo nuevamente", function(){});
         }
      });
   }
}

function obtenerTarjetas() {
   $.ajax({
      type: "POST",
      url: "../perfil/ajax.php?c=srpago&f=obtenerTarjetas",
      dataType: "json",
      data: { },
      success: function(respuesta){
         if(respuesta.status !== undefined && respuesta.status == true){
            $("#listado_tarjetas").html("");
            $("#tarjeta_pago").html("");
            var pago_tarjeta_options = "<option value='0'>Selecciona una tarjeta</option>";
            $.each(respuesta.tarjetas, function(index, tarjeta){
               var item = $("#tarjeta_base").clone().removeClass("hidden").attr("id", "");
               item.find("#numero_base").html(tarjeta.tipo + "  | " + tarjeta.numero);
               item.find("#limpiar_base").click((function(tarjeta){
                  return function(){
                     mensajeIconoDecision("¿Estas seguro?", "La tarjeta será eliminada de tu listado", "Cancelar", "Si", function(){
                        $.ajax({
                           type: "POST",
                           url: "../perfil/ajax.php?c=srpago&f=eliminarTarjeta",
                           dataType: "json",
                           data: { crd: tarjeta },
                           success: function(respuesta){
                              if(respuesta.status !== undefined && respuesta.status == true){
                                 mensajeIcono("success", "", "Tarjeta eliminada correctamente", function(){
                                    obtenerTarjetas();
                                 });
                              }else{
                                 mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
                              }
                           },
                           error: function(error){
                              mensajeIcono("error", "Un momento...", "No se ha podido completar esta acción, por favor intentalo nuevamente", function(){});
                           }
                        });
                     });
                  };
               }(tarjeta.crd)));
               item.find("#default_base").click((function(tarjeta){
                  return function(){
                     mensajeIconoDecision("¿Estas seguro?", "La tarjeta será marcada como la principal", "Cancelar", "Si", function(){
                        $.ajax({
                           type: "POST",
                           url: "../perfil/ajax.php?c=srpago&f=defaultTarjeta",
                           dataType: "json",
                           data: { crd: tarjeta },
                           success: function(respuesta){
                              if(respuesta.status !== undefined && respuesta.status == true){
                                 mensajeIcono("success", "", "Tarjeta definida correctamente", function(){
                                    obtenerTarjetas();
                                 });
                              }else{
                                 mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
                              }
                           },
                           error: function(error){
                              mensajeIcono("error", "Un momento...", "No se ha podido completar esta acción, por favor intentalo nuevamente", function(){});
                           }
                        });
                     });
                  };
               }(tarjeta.crd)));
               if(tarjeta.crd == respuesta.default) item.find("#default_base").removeClass("fa-thumbs-o-up").addClass("fa-thumbs-up");
               item.appendTo($("#listado_tarjetas"));
               $('[data-toggle="tooltip"]').tooltip();

               pago_tarjeta_options += `<option value='${tarjeta.crd}'>${tarjeta.tipo} | ${tarjeta.numero}</option>`;

            });
            $("#tarjeta_pago").html(pago_tarjeta_options);
         }else{
            mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
         }
      },
      error: function(error){
         mensajeIcono("error", "Un momento...", "No se ha podido completar esta acción, por favor intentalo nuevamente", function(){});
      }
   });
}

function obtenerProductos(){
   $.ajax({
      type: "POST",
      url: "../perfil/ajax.php?c=producto&f=listado",
      dataType: "json",
      data: { },
      success: function(respuesta){
         if(respuesta.status !== undefined && respuesta.status == true){
            $.each(respuesta.productos, function(index, elemento) {
               var item = $("#producto_base").clone().removeClass("hidden").attr("id", "producto_" + index);
               item.find("#titulo_producto_base").html(elemento.producto + " " + elemento.version).attr("href", "#info_producto_" + index);
               elemento.porcentaje = parseFloat(elemento.porcentaje);
               var color = (parseFloat(elemento.porcentaje) <= 33) ? "success" : ((parseFloat(elemento.porcentaje) <= 66) ? "info" : ((parseFloat(elemento.porcentaje) <= 90) ? "warning" : "danger"));
               item.find("#progreso_producto_base").html(elemento.porcentaje + "%").css("width", elemento.porcentaje + "%").attr("aria-valuenow", elemento.porcentaje).addClass("progress-bar-" + color);
               item.find("#info_producto_base").attr("id", "info_producto_" + index);
               item.find("#suscripcion_producto_base").html(elemento.periodo);
               item.find("#vencimiento_producto_base").html(elemento.fin);
               item.find("#precio_producto_base").html("$" + parseFloat(elemento.precio).toFixed(2));
               item.appendTo($("#listado_productos"));
            });
            $('[data-toggle="tooltip"]').tooltip();
         } else {
            mensajeIcono("error", "Un momento...", respuesta.mensaje, function(){});
         }
      },
      error: function(error){
         mensajeIcono("error", "Un momento...", "No se ha podido completar esta accion, por favor intentalo nuevamente", function(){});
      }
   });
}

function mostrarProductos(pago, tipo){
   $('#modal_complemento_pago_productos').off().on('shown.bs.modal', function () {
      popularTablaConParametros("pago", { 'pago': pago, 'tipo': tipo }, tabla_modulo_pago_productos, "productos");
   }).modal({backdrop: 'static', keyboard: false, show: true});
}

