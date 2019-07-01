<div class="bienvenida">
    <div id="titulo-sitio-01"><div class="titulo-sitio-01">Bienvenidos a <?=$this->parametrosInicio['software_nombre'] ?> <?=$this->configLocal['software_version'] ?></div></div>
</div>
<div class="contenedor">
    <div class="">
        <table width="400" border="0" cellspacing="0">
            <tr>
                <td width="20" height="20" align="center" valign="middle" style="padding:10px; border-bottom:1px solid #d5d6d4;">
                    <img src="<?= base_url()?>include/imagenes/icn-persona.png" width="11" height="10" />
                </td>
                <td style="padding:10px; border-bottom:1px solid #d5d6d4;">Usuario: <?php echo $_SESSION['usuario']->usu_nombre; ?>
                </td>
            </tr>
            <tr>
                <td width="20" height="20" align="center" valign="middle" style="padding:10px; border-bottom:1px solid #d5d6d4;">
                    <img src="<?= base_url()?>include/imagenes/icn-cerrar.png" width="9" height="9" />
                </td>
                <td style="padding:10px; border-bottom:1px solid #d5d6d4;">
                    <a href="<?= base_url() ?>index.php/inicio/Salir" class="gris">Cerrar sesión</a>
                </td>
            </tr>
        </table>
    </div>
    <?= print_r($_SESSION)?>
</div>