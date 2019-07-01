<div class="container">
    <form id="form_solicitud" method="POST" >
        <?php if (isset($solicitud)): ?>
            <input type="hidden" name="id_sol" value="<?= $solicitud->sol_id ?>">
        <?php endif; ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    Solicitud de Empresas
                </h5>
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <?php $this->load->view("solicitudes/solicitud/datosEmpresa"); ?>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="card bg-light">
                            <?php $this->load->view("solicitudes/solicitud/datosResolucion"); ?>
                            <?php $this->load->view("solicitudes/solicitud/datosAmbiente"); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <?php if (isset($solicitud)): ?>
                    <?php if ($solicitud->sol_estado == 1 && (in_array($_SESSION['perfil'], [1, 3]))): ?><!--administrador/supervisor-->
                        <a class="btn btn-success btn-sm" onclick='cargar(<?= $solicitud->sol_id ?>);'>Cargar</a>
                    <?php endif; ?>
                    <?php if ($solicitud->sol_estado == 0): ?><!--administrador/supervisor-->
                        <button class="btn btn-primary btn-sm " type="submit">Guardar</button>
                        <?php if ($solicitud->sol_enviada == 0): ?>
                            <a class="btn btn-info btn-sm" id="btnSolAprob" onclick="solicitar(<?= $solicitud->sol_id ?>)">Solicitar Aprobación</a>
                        <?php endif; ?>
                        <?php if (in_array($_SESSION['perfil'], [1, 3])): ?><!--administrador/supervisor-->
                            <a class="btn btn-success btn-sm" onclick='aprobar(<?= $solicitud->sol_id ?>);'>Aprobar</a>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <button class="btn btn-primary btn-sm " type="submit">Guardar</button>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>
<div class="contenedor"></div>
<script>
    $(document).ready(function () {
        cargarDocEmpresa();
        cambiarPais($("#pais").val());
    });
    $("#form_solicitud").submit(function (e) {
        var formData = new FormData($("#form_solicitud")[0]);
        $.ajax({
            url: '<?= base_url() ?>index.php/Ajax/guardarSolicitud',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                location.href = "<?= base_url() ?>index.php/inicio/nueva_solicitud/" + data;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                mensaje_error("No se pudo realizar la operación : " + textStatus);
            }
        });
        e.preventDefault();
    });
    function getAmbientes() {
        var tipo_amb = $("#amb_id").val();
        var dbname = $('#amb_id').find("option:selected").attr("title") + "_" + $('#pais').find("option:selected").attr("title");
        switch (tipo_amb) {
            case 0:
                $("#resol_num").val(0);
                $("#resol_num").attr('disabled', 'disabled');
                $("#resol_num").prop('disabled', true);
                $("#ambien_id").html("<option value=''>Seleccione...</option>");
                break;
            case 1:
                $("#resol_num").val("");
                $("#resol_num").removeAttr('disabled');
                break;
            case 3:
                $("#ambiente_select").html("");
                break;
            default :
                $.post("<?= base_url() ?>index.php/Ajax/getAmbientes", {tipo_amb: tipo_amb, dbname: dbname}, function (d) {
                    $("#ambiente_select").html(d);
                });
                break;
        }
    }
    function activaAmbiente() {
        var ambiente = $("#ambiente_select").val();
        if (ambiente != "0") {
            $(".nAmbiente").attr('disabled', 'disabled');
            $(".nAmbiente").attr('class', 'input-deshabilitado nAmbiente');
        } else {
            $(".nAmbiente").removeAttr('disabled');
            $(".nAmbiente").attr('class', 'input-editable nAmbiente');
        }
    }
    function cargarDocEmpresa() {
        setTimeout(function () {
            var textIden = $("#tipoDocumento option:selected").text();
            $("#textEmpresa").html(textIden + " Empresa");
        }, 200);
    }
    function cambiarPais(idPais) {
        switch (idPais) {
            case '1':
                $("#tipoImplementacion").removeAttr("disabled");
                $("#tipoImplementacion").attr("class", 'input-editable');
                $("#textCiudad").html("Ciudad");
                $("#textComuna").html("Comuna");
                $("#textPortal").html("Fecha de Alta GetDTE:");
                $("#tipoDocumento").load("<?= base_url() ?>index.php/Ajax/cargarDocEmp/" + idPais);
                break;
            case '2':
                $("#tipoImplementacion").attr("disabled", 'disabled');
                $("#tipoImplementacion").attr("class", 'input-deshabilitado');
                $("#textCiudad").html("Departamento");
                $("#textComuna").html("Distrito");
                $("#textPortal").html("Fecha de Alta GetCPE:");
                $("#tipoDocumento").load("<?= base_url() ?>index.php/Ajax/cargarDocEmp/" + idPais);
                break;
            case '3':
                $("#tipoImplementacion").attr("disabled", 'disabled');
                $("#tipoImplementacion").attr("class", 'input-deshabilitado');
                $("#textCiudad").html("Ciudad");
                $("#textComuna").html("Departamento");
                $("#textPortal").html("Fecha de Alta GetFel:");
                $("#tipoDocumento").load("<?= base_url() ?>index.php/Ajax/cargarDocEmp/" + idPais);
                break;
            default :
                $("#tipoImplementacion").attr("disabled", 'disabled');
                $("#tipoImplementacion").attr("class", 'input-deshabilitado');
                $("#textCiudad").html("Sin Definir");
                $("#textComuna").html("Sin Definir");
                $("#textEmpresa").html("N° Identidad Empresa");
                break;

        }
        cargarDocEmpresa();
    }
    function aprobar(idSolicitud) {
        $.post("<?= base_url() ?>index.php/Ajax/aprobarSolicitud/" + idSolicitud, function (d) {
            if (d == 'f') {
                mensaje_error("No fue posible aprobar la solicitud.\nIntente nuevamente");
            } else {
                mensaje_exito("La solicitud fue aprobada con exito");
                setTimeout(function () {
                    location.reload();
                }, 400);
            }
        });
    }
    function cargar(idSolicitud) {
        $.post("<?= base_url() ?>index.php/Ajax/cargarSolicitud/" + idSolicitud, function (d) {
            if (d == 'f') {
                mensaje_error("No fue posible cargar la solicitud.\nIntente nuevamente");
            } else {
                mensaje_exito("La solicitud fue aprobada con exito");
                setTimeout(function () {
                    location.reload();
                }, 400);
            }
        });
    }
    function solicitar(idSolicitud) {
        var idPais = $("#pais").val();
        $.post("<?= base_url() ?>index.php/Ajax/enviarSolicitud/" + idSolicitud + "/" + idPais, function (d) {
            if (d == "t") {
                mensaje_exito("La solicitud se envio correctamente");
                $("#btnSolAprob").hide();
            } else {
                mensaje_error(d);
            }
        });
    }
</script>
