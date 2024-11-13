<div id="modalarchivo" class="modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Seleccionar Archivos</h4>
            </div>
            <form method="post" id="detalle_form">
                <input type="hidden" id="doc_id" name="doc_id" class="form-control">
                <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-12 control-label" for="docd_obs">Observación</label>
                            <div class="col-12">
                                <textarea rows="3" id="docd_obs" name="docd_obs" class="form-control text-uppercase" required></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="docd_file">Seleccionar Archivo</label>
                            <div class="col-12">
                                <input type="file" id="docd_file" name="docd_file" accept=".pdf,.docx,.doc" required/>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default btn-default" type="button">Cerrar</button>
                    <button class="btn btn-primary"  type="submit" name="action" value="add" id="btnagregarfile">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>