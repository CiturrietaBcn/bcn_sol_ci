<div class="card-body">
    <h6 class="card-title"><b>Resolución.</b></h6>
    <div class="row">
        <div class="col-md-4">
            <div id="textPortal">Fecha de Alta GetDTE:</div>
            <input type="date" class="input-editable" name="sol[sol_postulacionfecha]" required
                   value="<?= (isset($solicitud)) ? date("Y-m-d", strtotime($solicitud->sol_postulacionfecha)) : "" ?>">
        </div>
        <div class="col-md-4">
            Fecha de Resolución:
            <input type="date" name="sol[sol_emp_resolucionfecha]" class="input-editable" required 
                   value="<?= (isset($solicitud)) ? date("Y-m-d", strtotime($solicitud->sol_emp_resolucionfecha)) : "" ?>">
        </div>
        <div class="col-md-4">
            N° de Resolución Prod:
            <input type="text"  placeholder="ej: 11" name="sol[sol_emp_resolucionnumero]" id="resol_num" required class="input-editable"
                   pattern="(?:\d*\.)?\d+" value="<?= (isset($solicitud)) ? $solicitud->sol_emp_resolucionnumero : "" ?>">
        </div>
    </div>
</div>