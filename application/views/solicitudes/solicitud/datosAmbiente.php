<div class="card-body">
    <h6 class="card-title"><b>Ambiente.</b></h6>
    <div class="row">
        <div class="col-md-3">
            Tipo de Ambiente:
            <select required name="sol[sol_ambiente]" id="amb_id" onchange="getAmbientes();" class="input-editable">
                <option value="">-Seleccione-</option>
                <option value="0" title="CERT" <?= (isset($solicitud) && $solicitud->sol_ambiente == 0) ? "selected" : "" ?>>
                    Certificación
                </option>
                <option value="1" title="PROD" <?= (isset($solicitud) && $solicitud->sol_ambiente == 1) ? "selected" : "" ?>>
                    Producción
                </option>
            </select>
        </div>
        <div class="col-md-3">
            Implementación:
            <select name="sol[sol_emp_implementacion]" class="input-editable" id="tipoImplementacion">
                <option value="0" <?= (isset($solicitud) && $solicitud->sol_emp_implementacion == 0) ? "selected" : "" ?>>
                    ERP Normal
                </option>
                <option value="1" <?= (isset($solicitud) && $solicitud->sol_emp_implementacion == 1) ? "selected" : "" ?>>
                    ERP Intensivo
                </option>
                <option value="2" <?= (isset($solicitud) && $solicitud->sol_emp_implementacion == 2) ? "selected" : "" ?>>
                    WebDte
                </option>
            </select>
        </div>
        <div class="col-md-6">
            Ambiente:
            <select name="sol[sol_amb_id]" class="input-editable" id="ambiente_select" onchange="activaAmbiente();" required>
                <?php if (isset($solicitud)): ?>
                    <?= $ambientes ?>
                <?php else: ?>
                    <option value=''>-Seleccione-</option>
                <?php endif; ?>
            </select>
        </div>
    </div>
    <hr>
    <div class="row">        
        <div class="col-md-6">
            Nombre Ambiente:
            <input class="input-<?=(isset($solicitud) && $solicitud->sol_amb_id == '0')?"editable":"deshabilitado"?> nAmbiente" name="sol[sol_amb_nombre]" value="<?= (isset($solicitud)) ? $solicitud->sol_amb_nombre : "" ?>">
        </div>
        <div class="col-md-6">
            Sub Dominio:
            <?php
            $subdominio = "";
            if (isset($solicitud) && (!empty($solicitud->sol_amb_url1))) {
                $sub = explode(".", $solicitud->sol_amb_url1);
                $subdominio = str_replace("-cert", "", $sub[0]);
            }
            ?>
            <input class="input-<?=(isset($solicitud) && $solicitud->sol_amb_id == '0')?"editable":"deshabilitado"?> nAmbiente" name="subdominio" 
                   value="<?= $subdominio ?>">
        </div>
    </div>
</div>