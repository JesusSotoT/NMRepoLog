<div id="modal-generico-container" class="modal fade" uso='0'>
    <div class="modal-dialog">
        <div id='generico-type' class="modal-content panel-warning">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Atencion!</h4>
            </div>
            <div class="modal-body">
                <p id='modal-generico-mensaje'></p>
            </div>
            <div class="modal-footer">
                <button id="modal-generico-boton-uno" type="button" class="btn btn-default" onclick='continuar()'>Continuar</button> 
                <button id="modal-generico-boton-dos" type="button" class="btn btn-default" onclick="$('#modal-generico-container').modal('hide');">Cancelar</button>
            </div>
        </div>
    </div> 
</div> 

<div id="modal-alert-container" class="modal fade" uso='0'>
    <div class="modal-dialog">
        <div id='alert-type' class="modal-content panel-warning">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Atencion!</h4>
            </div>
            <div class="modal-body">
                <p id='modal-alert-mensaje'></p>
            </div>
            <div class="modal-footer">
                <button id="modal-alert-boton-uno" type="button" class="btn btn-default" onclick="$('#modal-alert-container').modal('hide');">Aceptar</button>
            </div>
        </div>
    </div> 
</div> 