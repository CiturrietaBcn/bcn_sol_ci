<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("GestionModel");
        $this->load->model("SolicitudesModel");
        $this->load->model("UsuarioModel");
        $this->load->model("Model");
    }

    /* Seccion Usuarios */

    function usuarios_query() {
        $oper = "";
        $salida_tipo = "xml";
        if ($oper == 'excel') {
            $salida_tipo = "csv";
            $nombre_archivo = "Solicitudes_" . date('YmdHis', time()) . ".csv";
            header("Content-type: application/vnd.ms-excel; charset=utf-8");
            header("Content-Disposition: attachment; filename=$nombre_archivo");
        } else {
            header("Content-type: text/xml;charset=utf-8");
        }
        $campos = [
            'usu_codigo' => 'string',
            'usu_idlegal_id' => 'string',
            'per_nombre_perfil' => "string",
            'usu_nombre' => 'string',
            'usu_email' => 'string',
            'usu_cargo' => 'string',
            'usu_activo' => "boolean",
            'usu_bloqueo' => "boolean",
            'usu_intentos' => "numeric",
        ];
        $post = $_POST;
        if (isset($post['sol_amb_nombre'])) {
            $post['sol_amb_nombre'] = strtoupper(['sol_amb_nombre']);
        }
        $res = $this->GestionModel->getUsuarios($post, $campos, "usu_codigo", $salida_tipo);
        echo $res;
    }

    function modalUsuario($codigoUsuario = null) {
        if ($codigoUsuario != null) {
            $data['usuario'] = $this->Model->getRow('usuarios', ['usu_codigo' => $codigoUsuario]);
            $per = $this->Model->getRow("perfiles_usuarios", ['pu_us_codigo' => $codigoUsuario]);
            $data['perfil'] = $per->pu_id_perfil;
            $paises = $this->Model->getTodos("paises_usuarios", ['paus_usuario' => $codigoUsuario]);
            $data['pList'] = [];
            foreach ($paises as $val) {
                $data['pList'][] = $val->paus_id_pais;
            }
        }
        $data['perfiles'] = $this->Model->getTodos('perfiles');
        $data['paises'] = $this->Model->getTodos('paises');
        $this->load->view("configuracion/sub_config/modalUsuario", $data);
    }

    function guardarUsuario($idUsuario = null) {
        if (isset($_POST['usuario']['usu_contrasena']) && (!empty($_POST['usuario']['usu_contrasena']))) {
            $_POST['usuario']['usu_contrasena'] = md5($_POST['usuario']['usu_contrasena']);
        }
        if ($idUsuario == NULL) {
            $consulta = $this->Model->getTodos("usuarios", ['usu_codigo' => $_POST['usu_codigo']]);
            if (empty($consulta)) {
                if ($this->UsuarioModel->setUsuario($_POST['usuario'], $_POST['pais'], $_POST['perfil'], $_POST['usu_codigo'])) {
                    echo "ok";
                } else {
                    echo "El registro no se pudo registrar, favor intentar mas tarde";
                }
            } else {
                echo "El nombre de usuario ya esta registrado.\nRevisar haciendo doble click en la lista";
            }
        } else {
            if ($this->UsuarioModel->setUsuario($_POST['usuario'], $_POST['pais'], $_POST['perfil'], $_POST['usu_codigo'], $_POST['usu_codigo'])) {
                echo "ok";
            } else {
                echo "El registro no se pudo registrar, favor intentar mas tarde";
            }
        }
    }

    /* Seccion Paises */

    function paises_query() {
        $oper = "";
        $salida_tipo = "xml";
        if ($oper == 'excel') {
            $salida_tipo = "csv";
            $nombre_archivo = "Solicitudes_" . date('YmdHis', time()) . ".csv";
            header("Content-type: application/vnd.ms-excel; charset=utf-8");
            header("Content-Disposition: attachment; filename=$nombre_archivo");
        } else {
            header("Content-type: text/xml;charset=utf-8");
        }
        $campos = [
            'ps_codigo' => 'string',
            'ps_detalle' => 'string',
        ];
        $post = $_POST;
        $res = $this->GestionModel->getPaises($post, $campos, "ps_id", $salida_tipo);
        echo $res;
    }

    function paises_edit() {
        switch ($_POST['oper']) {
            case "edit":
                $dat = [
                    'ps_codigo' => $_POST['ps_codigo'],
                    'ps_detalle' => $_POST['ps_detalle']
                ];
                if ($this->Model->updateDatosb($dat, "paises", ['ps_id' => $_POST['id']])) {
                    echo "OK";
                } else {
                    echo "Error";
                }
                break;
            case "add":
                $dat = [
                    'ps_codigo' => $_POST['ps_codigo'],
                    'ps_detalle' => $_POST['ps_detalle']
                ];
                if ($this->Model->setDatosB($dat, "paises")) {
                    echo "OK";
                } else {
                    echo "Error";
                }
                break;
        }
    }

    /* Seccion de solicitudes */

    function solicitudes_query() {
        $oper = "";
        $salida_tipo = "xml";
        if ($oper == 'excel') {
            $salida_tipo = "csv";
            $nombre_archivo = "Solicitudes_" . date('YmdHis', time()) . ".csv";
            header("Content-type: application/vnd.ms-excel; charset=utf-8");
            header("Content-Disposition: attachment; filename=$nombre_archivo");
        } else {
            header("Content-type: text/xml;charset=utf-8");
        }
        $campos = [
            'emp_rut' => 'string',
            'emp_razonsocial' => 'string',
            'emp_pais' => 'numeric',
            'sol_ambiente' => "numeric",
            'sol_estado' => "numeric",
            'sol_creacion_usuario' => 'string',
            'sol_creacion_fecha' => "date",
            'sol_amb_nombre' => 'string'
        ];
        $post = $_POST;
        if (isset($post['sol_amb_nombre'])) {
            $post['sol_amb_nombre'] = strtoupper(['sol_amb_nombre']);
        }
        $res = $this->SolicitudesModel->getSolicitudes($post, $campos, "sol_id", $salida_tipo);
        echo $res;
    }

    function aprobarSolicitud($idSolicitud) {
        $solUpdate = [];
        $solUpdate['sol_estado'] = '1';
        $solUpdate['sol_autorizacion_usuario'] = $_SESSION['usuario']->usu_codigo;
        $solUpdate['sol_autorizacion_fecha'] = date("Y-m-d");
        $res = $this->Model->updateDatosb($solUpdate, "solicitud", ['sol_id' => $idSolicitud]);
        if ($res) {
            echo "t";
        } else {
            echo "f";
        }
    }

    function cargarSolicitud($idSolicitud) {
        $solicitud = $this->Model->getRow('solicitud', ['sol_id' => $idSolicitud]);
        $empresa = $this->Model->getRow('empresas', ['emp_id' => $solicitud->sol_emp]);
        $pais = $this->Model->getRow("paises", ['ps_id' => $empresa->emp_pais]);
        $db_name = (($solicitud->sol_ambiente == 0) ? "CERT_" : "PROD_") . $pais->ps_codigo;
        $this->load->model('AmbcertModel');
        $dbAm = new AmbcertModel($db_name);
        $respuesta = $dbAm->setCargarSolicitud($empresa, $solicitud, $pais->ps_detalle);
        if ($respuesta['estado']) {
            $solUpdate = [];
            $solUpdate['sol_estado'] = '2';
            $solUpdate['sol_autorizacion_usuario'] = $_SESSION['usuario']->usu_codigo;
            $solUpdate['sol_autorizacion_fecha'] = date("Y-m-d");
            $solUpdate['sol_amb_id'] = $respuesta['id_amb'];
            $res = $this->Model->updateDatosb($solUpdate, "solicitud", ['sol_id' => $idSolicitud]);
            if ($res) {

                $datosCorreo = array(
                    'asunto' => "Solicitud de Portal",
                    'from' => ['reply' => $_SESSION['usuario']->usu_email, 'nombre' => $_SESSION['usuario']->usu_nombre],
                    'destinos' => ['infra@bcncons.com', 'rescobar@bcncons.commm', 'citurrieta@bcncons.com']
                );
                $mensaje = "Estimados(as):<br>";
                $mensaje .= "&nbsp;&nbsp;&nbsp;Mediante el presente se comunica que el usuario <b>" . strtoupper($_SESSION['usuario']->usu_nombre)
                        . "</b> ha realizado la carga de la en ambiente de <b>" . (($solicitud->sol_ambiente == 0) ? "CERTIFICACIÓN" : "PRODUCCIÓN")
                        . "</b> con ID <b>" . $respuesta['id_amb'] . "</b> para la empresa <b>" . strtoupper($empresa->emp_razonsocial) . "</b>."
                        . "<br>&nbsp;&nbsp;&nbsp;Se ruega revisar a la brevedad, realizar las observaciones necesarias continuar con el proceso de levanvamiento. "
                        . (($solicitud->sol_amb_id == 0) ? "<br>&nbsp; Adicionalmente es necesario crear el sub dominio <b>"
                        . $solicitud->sol_amb_url1 . "</b> ya que esta carga corresponde a un nuevo cliente." : "")
                        . "<br><br>&nbsp;Saludos Cordiales.<br>";
                $data['mensaje'] = $mensaje;
                $data['titulo'] = $datosCorreo['asunto'];
                $datosCorreo['cuerpo'] = $this->load->view('solicitudes/formatoCorreo', $data, true);
                $res = $this->enviarCorreo($datosCorreo);
                echo "t";
            } else {
                echo "f";
            }
        } else {
            echo "f";
        }
    }

    function guardarSolicitud() {
        $res = $this->SolicitudesModel->setSolicitudes(
                $_POST['empresa'], $_POST['sol'], (isset($_POST['subdominio'])) ? $_POST['subdominio'] : "0"
                , (isset($_POST['id_sol'])) ? $_POST['id_sol'] : "0"
        );
        if ($res == FALSE) {
            echo "f";
        } else {
            echo $res;
        }
    }

    function cargarDocEmp($idPais) {
        $res = $this->Model->getTodos("empresa_documento", ['emp_doc_pais' => $idPais]);
        foreach ($res as $val) {
            echo "<option value='$val->emp_doc_id'>$val->emp_doc_descripcion</option>";
        }
    }

    function getAmbientes() {
        $this->load->model('AmbcertModel');
        $dbAm = new AmbcertModel($_POST['dbname']);
        $ambientes = $dbAm->getAmbientes();
        echo "<option value=''>Seleccionar ambiente...</option>";
        echo "<option value='0'>Nuevo Ambiente</option>";
        foreach ($ambientes as $val) {
            echo "<option value='" . $val->amb_id . "'>" . $val->nombre . "</option>";
        }
    }

    function enviarSolicitud($idSolicitud, $idPais) {
        $remitentes = $this->SolicitudesModel->getRemitSolAprov($idPais);
        $datosCorreo = array(
            'asunto' => "Solicitud de Aprobación de Portal",
            'from' => ['reply' => $_SESSION['usuario']->usu_email, 'nombre' => $_SESSION['usuario']->usu_nombre]
        );
        foreach ($remitentes as $val) {
            $datosCorreo['destinos'][] = $val->usu_email;
        }
        $solicitud = $this->Model->getRow("solicitud", ['sol_id' => $idSolicitud]);
        $empresa = $this->Model->getRow("empresas", ['emp_id' => $solicitud->sol_emp]);
        $mensaje = "Estimados(as):<br>";
        $mensaje .= "&nbsp;&nbsp;&nbsp;Mediante el presente se comunica que el usuario <b>" . strtoupper($_SESSION['usuario']->usu_nombre)
                . "</b> ha levantado una solicitud "
                . "de portal para la empresa <b>" . strtoupper($empresa->emp_razonsocial) . "</b> en el ambiente de "
                . (($solicitud->sol_ambiente == 0) ? "CERTIFICACIÓN" : "PRODUCCIÓN") . ".<br>"
                . "<br>&nbsp;&nbsp;&nbsp;Se ruega revisar a la brevedad, realizar las observaciones necesarias para aprobar la solicitud y continuar "
                . "con el proceso de levanvamiento. <br><br>&nbsp;Saludos Cordiales.<br>";
        $data['mensaje'] = $mensaje;
        $data['titulo'] = $datosCorreo['asunto'];
//        $this->load->view('solicitudes/formatoCorreo', $data);
        $datosCorreo['cuerpo'] = $this->load->view('solicitudes/formatoCorreo', $data, true);
        if ($res = $this->enviarCorreo($datosCorreo)) {
            $this->Model->updateDatosb(['sol_enviada' => '1'], 'solicitud', ['sol_id' => $idSolicitud]);
            echo "t";
        } else {
            echo $res;
        }
    }

    function enviarCorreo($datos) {
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'citurrieta@bcncons.com',
            'smtp_pass' => '64086842019',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'wordwrap' => TRUE
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from($datos['from']['reply'], $datos['from']['nombre']);
        $this->email->to($datos['destinos']);
        $this->email->subject($datos['asunto']);
        $this->email->message($datos['cuerpo']);
        // Set to, from, message, etc.
        $result = $this->email->send();
        if ($result) {
            return TRUE;
        } else {
            echo $this->email->print_debugger();
        }
    }

}
