<?php
// Obtenemos y traducimos el nombre del día
$dia = date("l");
if ($dia == "Monday")
    $dia = "Lunes";
if ($dia == "Tuesday")
    $dia = "Martes";
if ($dia == "Wednesday")
    $dia = "Miercoles";
if ($dia == "Thursday")
    $dia = "Jueves";
if ($dia == "Friday")
    $dia = "Viernes";
if ($dia == "Saturday")
    $dia = "Sabado";
if ($dia == "Sunday")
    $dia = "Domingo";


// Obtenemos el número del día
$dia2 = date("d");

// Obtenemos y traducimos el nombre del mes
$mes = date("F");
if ($mes == "January")
    $mes = "Enero";
if ($mes == "February")
    $mes = "Febrero";
if ($mes == "March")
    $mes = "Marzo";
if ($mes == "April")
    $mes = "Abril";
if ($mes == "May")
    $mes = "Mayo";
if ($mes == "June")
    $mes = "Junio";
if ($mes == "July")
    $mes = "Julio";
if ($mes == "August")
    $mes = "Agosto";
if ($mes == "September")
    $mes = "Septiembre";
if ($mes == "October")
    $mes = "Octubre";
if ($mes == "November")
    $mes = "Noviembre";
if ($mes == "December")
    $mes = "Diciembre";

// Obtenemos el año
$ano = date("Y");

// Imprimimos la fecha completa
$dia = strtoupper($dia);
$mes = strtoupper($mes);
$fecha = "$dia $dia2 de $mes del $ano";
?>
<style>
    .ui-state-hover {
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #59A700), color-stop(1, #59A700));
        background: -moz-linear-gradient(center top, #59A700 5%, #59A700 100%);
        background: #59A700;
    }

    li#ui-id-1.ui-menu-item.ui-state-focus,
    li#ui-id-2.ui-menu-item.ui-state-focus,
    li#ui-id-3.ui-menu-item.ui-state-focus,
    li#ui-id-4.ui-menu-item.ui-state-focus,
    li#ui-id-5.ui-menu-item.ui-state-focus {
        border: 1px solid #336600;
        background: #7fcd00;
        font-weight: bold;
        color: #1d5987;
    }

    .ui-state-focus {
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #59A700), color-stop(1, #59A700));
        background: -moz-linear-gradient(center top, #59A700 5%, #59A700 100%);
        background: #59A700;
    }

    .ui-menu {
        width: 220px;
        -moz-box-shadow: inset 1px 1px 1px 0px #c1ed9c;
        -webkit-box-shadow: inset 1px 1px 1px 0px #c1ed9c;
        box-shadow: inset 1px 1px 1px 0px #c1ed9c;
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #79c720), color-stop(1, #59A700));
        background: -moz-linear-gradient(center top, #79c720 5%, #59A700 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#9dce2c', endColorstr='#8cb82b');
        background-color: #59A700;
        -webkit-border-top-left-radius: 2px;
        -moz-border-radius-topleft: 2px;
        border-top-left-radius: 2px;
        -webkit-border-top-right-radius: 2px;
        -moz-border-radius-topright: 2px;
        border-top-right-radius: 2px;
        -webkit-border-bottom-right-radius: 2px;
        -moz-border-radius-bottomright: 2px;
        border-bottom-right-radius: 2px;
        -webkit-border-bottom-left-radius: 2px;
        -moz-border-radius-bottomleft: 2px;
        border-bottom-left-radius: 2px;
        text-indent: 0;
        border: 1px solid #83c41a;
        color: #ffffff;
        font-family: Arial;
        font-size: 10px;
        font-weight: bold;
        font-style: normal;
        text-shadow: 1px 1px 0px #336600;
        line-height: 22px;
        text-decoration: none;
        text-align: center;
        position: absolute;
        top: 200px;
        left: 200px;
        z-index: 1;
    }
</style>

<div class="navbar navbar-dark bg-dark shadow-sm">
    <div class="col-12 d-flex justify-content-end">
        <span class="sr-only">Toggle navigation</span>
        <span class="txt-acceso"><?= strtoupper($_SESSION['usuario']->usu_nombre) ?></span>
        <span class="txt-acceso" style="background-color:#006600;"><a href="<?= base_url() ?>index.php/inicio/Salir" style="color:#ffffff;"><table cellspacing="0" cellpadding="0" boder="0"><tr><td><img src="<?= base_url() ?>include/imagenes/cerrar_sesion.png" border="0" width="12" height="12"></td><td>&nbsp;&nbsp;</td><td><font color="#ffffff">CERRAR SESION</font></td></tr></table></a></span>
        <span class="txt-acceso"><?= strtoupper($fecha) ?></span>
    </div>
</div>
<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
    <h5 class="my-0 mr-md-auto font-weight-normal"><img src="<?= base_url() ?>include/logos/bcn_consultores.png" width="165" height="39"></h5>
    <nav class="my-2 my-md-0 mr-md-3 ">
        <a href="<?= base_url() ?>" class="p-2 text-dark btnn btn">
            <img src="<?= base_url() ?>include/imagenes/menu_home.png" width="28" height="28"/>
            <br/>
            <small>Home</small>
        </a>
        <a href="<?= base_url() ?>index.php/inicio/solicitudes" class="p-2 text-dark btnn btn">
            <img src="<?= base_url() ?>include/imagenes/menu_documentos.png" width="28" height="28">
            <br><small>Solicitudes</small>
        </a>
        <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil'] == '1'): ?>
            <a href="<?= base_url() ?>index.php/inicio/configuracion" class="p-2 text-dark btnn btn">
                <img src="<?= base_url() ?>include/imagenes/menu_configuracion.png" width="28" height="28"/>
                <br/>
                <small>Config</small>
            </a>
        <?php endif; ?>
    </nav>
</div>

<div id="navegacion">
    <?php if ($this->pagina_icono != 'home') { ?>
        <h1 class="titulo-pag"><?= $this->pagina_titulo ?></h1>
    <?php } ?>
    <img src="<?= base_url() ?>include/imagenes/flecha.png" width="8" height="7"/> Estás navegando en: <a href="<?= $this->pagina_home ?>" class="gris">Home</a> / <?= $this->pagina_ruta ?> <?= $this->pagina_titulo ?>
</div><!-- Fin navegacion -->
<script>

    function mensaje_exito(texto_mensaje) {
        var n = noty({
            text: texto_mensaje,
            type: 'success',
            dismissQueue: true,
            layout: 'topRight',
            theme: 'defaultTheme',
            closeWith: ['click', 'button'],
            timeout: false,
            animation: {
                open: 'animated bounceInRight', // jQuery animate function property object
                close: 'animated  bounceOutUp', // jQuery animate function property object
                easing: 'swing', // easing
                speed: 500 // opening & closing animation speed
            }
        });
    }

    function mensaje_warning(texto_mensaje) {
        setTimeout('', 1000);
        var x = noty({
            text: texto_mensaje,
            type: 'warning',
            dismissQueue: true,
            layout: 'topRight',
            theme: 'defaultTheme',
            closeWith: ['click', 'button'],
            timeout: false,
            animation: {
                open: 'animated bounceInRight', // jQuery animate function property object
                close: 'animated  bounceOutUp', // jQuery animate function property object
                easing: 'swing', // easing
                speed: 500 // opening & closing animation speed
            }
        });
    }
    function mensaje_error(texto_mensaje) {
        var y = noty({
            text: texto_mensaje,
            type: 'error',
            dismissQueue: true,
            layout: 'topRight',
            theme: 'defaultTheme',
            closeWith: ['click', 'button'],
            timeout: false,
            animation: {
                open: 'animated shake', // jQuery animate function property object
                close: 'animated  bounceOutUp', // jQuery animate function property object
                easing: 'swing', // easing
                speed: 500 // opening & closing animation speed
            }
        });
    }
    function mensaje_informacion(texto_mensaje) {
        var y = noty({
            text: texto_mensaje,
            type: 'information',
            dismissQueue: true,
            layout: 'topRight',
            theme: 'defaultTheme',
            closeWith: ['click', 'button'],
            timeout: false,
            animation: {
                open: 'animated bounceInRight', // jQuery animate function property object
                close: 'animated  bounceOutUp', // jQuery animate function property object
                easing: 'swing', // easing
                speed: 500 // opening & closing animation speed
            }
        });
    }
    function mensaje_informacion_centrado(texto_mensaje) {
        var y = noty({
            text: texto_mensaje,
            type: 'information',
            dismissQueue: true,
            layout: 'center',
            theme: 'defaultTheme',
            closeWith: ['click', 'button'],
            timeout: 3000,
            animation: {
                open: 'animated flipInY', // jQuery animate function property object
                close: 'animated  flipOutY', // jQuery animate function property object
                easing: 'swing', // easing
                speed: 500 // opening & closing animation speed
            }
        });
    }
    function mensaje_consulta(texto_mensaje) {
        var y = noty({
            text: texto_mensaje,
            type: 'confirm',
            dismissQueue: true,
            layout: 'center',
            theme: 'defaultTheme',
            closeWith: ['click'],
            timeout: false,
            animation: {
                open: 'animated bounceIn', // jQuery animate function property object
                close: 'animated  bounceOut', // jQuery animate function property object
                easing: 'swing', // easing
                speed: 500 // opening & closing animation speed,
            },
            buttons: [
                {
                    addClass: 'btn btn-primary', text: 'OK', onClick: function ($noty) {
                        $noty.close();
                        noty({text: 'Pulsaste la Opcion OK', type: 'success', layout: 'center', timeout: 1000});
                    }
                },
                {
                    addClass: 'btn btn-danger', text: 'Cancelar', onClick: function ($noty) {
                        $noty.close();
                        noty({text: 'Pulsaste la opcion CANCELAR', type: 'error', layout: 'center', timeout: 1000});
                    }
                }
            ]
        });
    }
    function mensaje_ok(texto_mensaje) {
        var y = noty({
            text: texto_mensaje,
            type: 'success',
            dismissQueue: true,
            layout: 'center',
            theme: 'defaultTheme',
            closeWith: ['click'],
            timeout: false,
            animation: {
                open: 'animated bounceIn', // jQuery animate function property object
                close: 'animated  bounceOut', // jQuery animate function property object
                easing: 'swing', // easing
                speed: 500 // opening & closing animation speed,
            },
            buttons: [
                {
                    addClass: 'btn btn-primary', text: 'OK', onClick: function ($noty) {
                        $noty.close();
                        noty({text: 'Muchas Gracias', type: 'success', layout: 'center', timeout: 1000});
                    }
                }

            ]
        });
    }
    function mensaje_alerta(texto_mensaje) {
        var y = noty({
            text: texto_mensaje,
            type: 'error',
            dismissQueue: true,
            layout: 'center',
            theme: 'defaultTheme',
            closeWith: ['click'],
            timeout: false,
            animation: {
                open: 'animated bounceIn', // jQuery animate function property object
                close: 'animated  bounceOut', // jQuery animate function property object
                easing: 'swing', // easing
                speed: 500 // opening & closing animation speed,
            },
            buttons: [
                {
                    addClass: 'btn btn-primary', text: 'OK', onClick: function ($noty) {
                        $noty.close();
                        noty({text: 'Muchas Gracias', type: 'success', layout: 'center', timeout: 1000});
                    }
                }
            ]
        });
    }
    var ancho_minimo = <?php echo 365 + (2 * 65); ?>;

    $(document).ready(function () {

        var ancho = $('body').width();

        if (ancho < ancho_minimo) {
            $('#menu').hide();
            $('#menu_reducido').show();
        } else {
            $('#menu').show();
            $('#menu_reducido').hide();
        }

        $(window).resize(function () {
            var ancho = $('body').width();
            if (ancho < ancho_minimo) {
                $('#menu').hide();
                $('#menu_reducido').show();
            } else {
                $('#menu').show();
                $('#menu_reducido').hide();
            }
        });

        $('#menu_selector').change(function () {
            location.href = $(this).val();
        });
    });
</script>
