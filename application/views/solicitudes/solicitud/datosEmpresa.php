<div class="card bg-light">
    <div class="card-body">
        <h6 class="card-title"><b>Datos Empresa.</b></h6>
        <div class="row">
            <div class="col-md-12">
                Pais:
                <select class="input-editable" id="pais" onchange="cambiarPais($(this).val());"
                        name='empresa[emp_pais]'>
                            <?php foreach ($paises as $val) : ?>
                        <option value="<?= $val->ps_id ?>" title="<?= $val->ps_codigo ?>"
                                <?= (isset($empresa) && $empresa->emp_pais == $val->ps_id) ? "selected" : "" ?>>
                            <?= $val->ps_codigo ?>:<?= $val->ps_detalle ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-12">
                <br>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6">
                        <div id="textEmpresa">RUT Empresa:</div>
                        <input class="input-editable" type="text" id="rut_emp" name="empresa[emp_rut]" required
                               maxlength="10" value="<?= (isset($empresa)) ? $empresa->emp_rut : "" ?>">
                    </div>
                    <div class="col-md-6">
                        <div id="textEmpresa">Tipo de Documento:</div>
                        <select class="input-editable" type="text" id="tipoDocumento" 
                                value="" name="empresa[emp_tipo_doc]" required>
                                    <?= $tdoc ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-12">
                Razón Social:
                <input type="text" name="empresa[emp_razonsocial]" class="input-editable" placeholder="Nombre empresa..."
                       value="<?= (isset($empresa)) ? $empresa->emp_razonsocial : "" ?>" required/>
            </div>   
            <div class="col-12">
                Dirección:
                <input type="text" placeholder="" name="empresa[emp_direccion]" required class="input-editable"
                       value="<?= (isset($empresa)) ? $empresa->emp_direccion : "" ?>">
            </div>   
            <div class="col-12">
                <div class="row">
                    <div class="col-md-4">
                        <div id="textCiudad">Ciudad:</div>
                        <input type="text" placeholder="" value="<?= (isset($empresa)) ? $empresa->emp_ciudad : "" ?>" name="empresa[emp_ciudad]" 
                               required class="input-editable">
                    </div>   
                    <div class="col-md-4">
                        <div id="textComuna">Comuna:</div>
                        <input type="text" placeholder="" value="<?= (isset($empresa)) ? $empresa->emp_comuna : "" ?>" name="empresa[emp_comuna]" 
                               required class="input-editable"/>
                    </div>   
                    <div class="col-md-4">
                        Fono:
                        <input type="tel" name="empresa[emp_fono]" required class="input-editable" pattern="[0-9]{9}" 
                               value="<?= (isset($empresa)) ? $empresa->emp_fono : "" ?>">
                    </div>   
                </div>   
            </div>   
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6">
                        Nombre Contacacto Técnico:
                        <input type="text" placeholder="" value="<?= (isset($empresa)) ? $empresa->emp_cont_nom_tec : "" ?>" 
                               name="empresa[emp_cont_nom_tec]" required class="input-editable"/>
                    </div>
                    <div class="col-md-6">
                        Email Contacacto Técnico:
                        <input type="email" placeholder=""  name="empresa[emp_cont_mail_tec]" required class="input-editable"
                               value="<?= (isset($empresa)) ? $empresa->emp_cont_mail_tec : "" ?>">
                    </div>
                </div>   
            </div>   
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6">
                        Nombre Contacto Comercial:
                        <input type="text" placeholder="" name="empresa[emp_cont_nom_comer]" required class="input-editable"
                               value="<?= (isset($empresa)) ? $empresa->emp_cont_nom_comer : "" ?>"/>
                    </div>   
                    <div class="col-md-6">
                        Email Contacto Comercial:
                        <input type="email" placeholder="" name="empresa[emp_cont_mail_comer]" required class="input-editable"
                               value="<?= (isset($empresa)) ? $empresa->emp_cont_mail_comer : "" ?>" />
                    </div>   
                </div>   
            </div>   
        </div>
    </div>
</div>

