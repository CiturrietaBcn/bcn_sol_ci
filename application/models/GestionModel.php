<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GestionModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->db = $this->load->database('default', true);
    }
    // GENERALES
    function getInicioAjax($us, $pass, $record) {
        $login_accion = "ERROR";
        $login_mensaje = "Error Interno de la Aplicacion";
        $mensaje_error = "";
        $mensaje = array(
            "estado" => "",
            "mensaje" => "",
            "datosUsuario" => ""
        );
        $usuario = strtolower($this->securePOST($us));
        $clave = $this->securePOST($pass);
        $consultaSQL = "select *,md5('" . $clave . "') as codificada "
                . " from usuarios "
                . "  where "
                . "    usu_codigo ilike '" . $usuario . "'"
                . "    and usu_activo ";
        $_usuario = $this->db->query($consultaSQL)->row();
        if ($_usuario == FALSE) {
            $login_mensaje = "Las credenciales ingresadas no son validas";
        } else {
            if ($_usuario->usu_bloqueo == "t") {
                $login_mensaje = "Usuario Bloqueado. Contactese con el Administrador de la Plataforma";
            } else {
                if ($_usuario->usu_contrasena == $_usuario->codificada) {
                    $this->db->query("update usuarios "
                            . " set usu_intentos = 0 "
                            . " where usu_codigo ilike '" . $_usuario->usu_codigo . "'");
                    $perfil = $this->db->query("SELECT * FROM perfiles_usuarios WHERE pu_us_codigo = '" . $_usuario->usu_codigo . "'")->row();
                    if ($perfil != FALSE) {
                        $login_accion = "OK";
                        $login_mensaje = "Inicio de session correcto";
                    } else {
                        $login_mensaje = "Las credenciales ingresadas no son validas";
                    }
                } else {
                    $usuario_intentos = $_usuario->usu_intentos + 1;
                    $login_mensaje = "Datos de Acceso Incorrecto. (Intentos Incorrectos: $usuario_intentos)";
                    $this->db->query("update usuarios set usu_intentos = " . $usuario_intentos . " where usu_codigo ilike '" . $_usuario->usu_codigo . "'");
                    if ($usuario_intentos >= 3) {
                        $this->db->query("update usuarios set usu_bloqueo = FALSE where usu_codigo ilike '" . $_usuario->usu_codigo . "'");
                    }
                    $_usuario = NULL;
                }
            }
        }
        $mensaje = array(
            "estado" => $login_accion,
            "mensaje" => $login_mensaje,
            "datosUsuario" => $_usuario
        );
        return $mensaje;
    }

    function getUsuarios($post, $campos, $id, $salida_tipo) {
        return $this->getXMLGRID($post, $campos, $id, $salida_tipo, "usuarios,perfiles,perfiles_usuarios", "usu_codigo = pu_us_codigo and pu_id_perfil=per_id_perfil");
    }

    function getPaises($post, $campos, $id, $salida_tipo) {
        return $this->getXMLGRID($post, $campos, $id, $salida_tipo, "paises", "ps_id is not null");
    }

}
