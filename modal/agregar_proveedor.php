 
<form class="form-horizontal" method="post" id="guardar_proveedor" name="guardar_cliente">
<!-- Modal -->
<div class="modal fade" id="proveedor_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Nuevo Cliente</h4>
      </div>
      <div class="modal-body">
	  
                      
                    <div class="form-group">
                        <label for="razonsocia" class="col-sm-3 control-label">Nombre/Razón Social</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="razonsocia"  name="razonsocia" required>
                        </div>
                    </div>
					  <div class="form-group">
                        <label for="ruc" class="col-sm-3 control-label">RUC/CI</label>
                        <div class="col-sm-9">
                          <input class="form-control" id="ruc" name="ruc" required>
                        </div>
                      </div>
					  <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">Mail</label>
                        <div class="col-sm-9">
                          <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                      </div>
					  <div class="form-group">
                        <label for="fono" class="col-sm-3 control-label">Teléfono</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="fono" name="fono" required>
                        </div>
                      </div>
					 <div class="form-group">
                        <label for="dir" class="col-sm-3 control-label">Dirección</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="dir" name="dir" required>
                        </div>
                      </div>
	  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" id="guardar_datos" class="btn btn-primary">Registrar</button>
      </div>
    </div>
  </div>
</div>
</form>