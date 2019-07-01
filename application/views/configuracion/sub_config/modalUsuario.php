<div class="modal" tabindex="-1" role="dialog" id="modalUsuario">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formUsuario">
                <div class="modal-header"><h5 id='tituloModalUs'></h5></div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            Usuario:<input type="text" class="input-editable" name="usu_codigo" required
                                           value="<?= (isset($usuario)) ? $usuario->usu_codigo : "" ?>">
                        </div>
                        <div class="col-md-8">
                            Nombre:<input type="text" class="input-editable" required name="usuario[usu_nombre]"
                                          value="<?= (isset($usuario)) ? $usuario->usu_nombre : "" ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            Correo:
                            <input type="email" class="input-editable" required name="usuario[usu_email]"
                                   value="<?= (isset($usuario)) ? $usuario->usu_email : "" ?>">
                        </div>
                        <div class="col-md-6">
                            Cargo:
                            <input type="text" class="input-editable" required name="usuario[usu_cargo]"
                                   value="<?= (isset($usuario)) ? $usuario->usu_cargo : "" ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            Password:
                            <input type="password" id="password" class="input-editable" <?= (!isset($usuario)) ? "required" : "" ?>>
                        </div>
                        <div class="col-md-6">
                            Repita Password:
                            <input type="password" id="confirm_password" class="input-editable" name="usuario[usu_contrasena]" 
                                   <?= (!isset($usuario)) ? "required" : "" ?>>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 text-center">
                            Habilitado:<br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="usuario[usu_activo]" id="inlineRadio1" value="1"
                                       <?= (isset($usuario) && (($usuario->usu_activo))) ? "checked" : "" ?>>
                                <label class="form-check-label" for="inlineRadio1">Si</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="usuario[usu_activo]" id="inlineRadio2" value="0" 
                                       <?= (isset($usuario) && ((!$usuario->usu_activo))) ? "checked" : "" ?>>
                                <label class="form-check-label" for="inlineRadio2">No</label>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            Bloqueado:<br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="usuario[usu_bloqueo]" id="inlineRadio1" value="1"
                                       <?= (isset($usuario) && (($usuario->usu_bloqueo))) ? "checked" : "" ?>>
                                <label class="form-check-label" for="inlineRadio1">Si</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="usuario[usu_bloqueo]" id="inlineRadio2" value="0"
                                       <?= (isset($usuario) && ((!$usuario->usu_bloqueo))) ? "checked" : "" ?>>
                                <label class="form-check-label" for="inlineRadio2">No</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            Perfil:<br>
                            <select class="input-editable" name="perfil">
                                <?php foreach ($perfiles as $val): ?>
                                    <option value="<?= $val->per_id_perfil ?>" <?= (isset($perfil) && ($perfil == $val->per_id_perfil)) ? "selected" : "" ?>>
                                        <?= $val->per_nombre_perfil ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <?php foreach ($paises as $val) : ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="pais<?= $val->ps_id ?>" 
                                           value="<?= $val->ps_id ?>" name="pais[]" 
                                           <?= (isset($pList) && (in_array($val->ps_id, $pList))) ? "checked" : "" ?>>
                                    <label class="form-check-label" for="pais<?= $val->ps_id ?>"><?= $val->ps_detalle ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
                    <input type="submit" class="btn btn-primary btn-sm" value="Guardar">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var password = document.getElementById("password")
            , confirm_password = document.getElementById("confirm_password");

    function validatePassword() {
        if (password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Las contraseñas no coinciden");
        } else {
            confirm_password.setCustomValidity('');
        }
    }
    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;

    $("#formUsuario").submit(function (e) {
        var complemento = "<?= (isset($usuario)) ? "/" . $usuario->usu_codigo : "" ?>";
        var formData = new FormData($("#formUsuario")[0]);
        $.ajax({
            url: '<?= base_url() ?>index.php/Ajax/guardarUsuario' + complemento,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
                if (data == "ok") {
                    mensaje_exito("El registro se creo correctamente");
                    $("#tUsuarios").trigger('reloadGrid');
                    $("#modalUsuario").modal("hide");
                } else {
                    mensaje_error(data);
                }
//                location.href = "<?= base_url() ?>index.php/inicio/nueva_solicitud/" + data;
            }
        });
        e.preventDefault();
    });
</script>