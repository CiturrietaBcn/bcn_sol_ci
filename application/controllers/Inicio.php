<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends MY_Controller {

    public $pagina_icono = 'home';
    public $pagina_home = 'menu.php';
    public $pagina_ruta = '';
    public $pagina_titulo = '';

    function __construct() {
        parent::__construct();
        $this->load->model('SolicitudesModel');
        $this->load->model('Model');
    }

    public function dashboard() {
        $this->getHtml('tema/sitio/dashboard');
    }

    function Salir() {
        $this->borrarCookies();
    }

    function solicitudes() {
        $data['ambientes'] = "0:Certificacion;1:Produccion";
        $data['estados'] = "0:Pendiente;1:Aprobado;2:Cargada";
        $paises = $this->SolicitudesModel->getPaisesUser();
        $data['paises'] = "";
        foreach ($paises as $val) {
            $data['paises'] .= $val->ps_id . ":" . $val->ps_detalle;
            if (!($val === end($paises))) {
                $data['paises'] .= ";";
            }
        }
        $this->getHtml('solicitudes/listaSolicitudes', $data);
    }

    function nueva_solicitud($id = null) {
        $data['paises'] = $this->SolicitudesModel->getPaisesUser();
        $data['tdoc'] = "";
        if ($id != NULL) {
            $data['solicitud'] = $this->Model->getRow("solicitud", ['sol_id' => $id]);
            $data['empresa'] = $this->Model->getRow("empresas", ['emp_id' => $data['solicitud']->sol_emp]);
            $res = $this->Model->getTodos("empresa_documento", ['emp_doc_pais' => $data['empresa']->emp_pais]);
            foreach ($res as $val) {
                if ($val->emp_doc_id == $data['empresa']->emp_tipo_doc) {
                    $data['tdoc'] .= "<option value='$val->emp_doc_id' selected>$val->emp_doc_descripcion</option>";
                } else {
                    $data['tdoc'] .= "<option value='$val->emp_doc_id'>$val->emp_doc_descripcion</option>";
                }
            }
            $data['ambientes'] = "";
            $this->load->model('AmbcertModel');
            $dbname = "";
            if ($data["solicitud"]->sol_ambiente == 0) {
                $dbname = "CERT_";
            } else {
                $dbname = "PROD_";
            }
            $paises = $this->Model->getTodos('paises');
            foreach ($paises as $val) {
                if ($val->ps_id === $data['empresa']->emp_pais) {
                    $dbname .= $val->ps_codigo;
                }
            }
            $dbAm = new AmbcertModel($dbname);
            $ambientes = $dbAm->getAmbientes();
            $data['ambientes'] .= "<option value=''>Seleccionar ambiente...</option>";
            if ($data['solicitud']->sol_ambiente == 0) {
                $data['ambientes'] .= "<option value='0' selected>Nuevo Ambiente</option>";
            } else {
                $data['ambientes'] .= "<option value='0'>Nuevo Ambiente</option>";
            }
            foreach ($ambientes as $val) {
                if ($data['solicitud']->sol_amb_id == $val->amb_id) {
                    $data['ambientes'] .= "<option value='" . $val->amb_id . "' selected>" . $val->nombre . "</option>";
                } else {
                    $data['ambientes'] .= "<option value='" . $val->amb_id . "'>" . $val->nombre . "</option>";
                }
            }
//            print_r($data);
        } else {
            foreach ($data['paises'] as $val) {
                if ($val === reset($data['paises'])) {
                    $res = $this->Model->getTodos("empresa_documento", ['emp_doc_pais' => $val->ps_id]);
                    foreach ($res as $val) {
                        $data['tdoc'] .= "<option value='$val->emp_doc_id'>$val->emp_doc_descripcion</option>";
                    }
                }
            }
        }
        $this->getHtml('solicitudes/solicitud', $data);
    }

    function configuracion() {
        $this->getHtml("configuracion/principal");
    }

    function conf_usuarios() {
        $this->load->view("configuracion/usuarios");
    }

    function conf_paises() {
        $this->load->view("configuracion/paises");
    }

}
