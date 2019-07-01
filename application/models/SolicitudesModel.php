<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class SolicitudesModel extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->db = $this->load->database('default', true);
        $this->load->model("Model");
    }

    function getSolicitudes($post, $campos, $id, $salida_tipo) {
        $where = "sol_emp = emp_id and emp_pais in (";
        $paises = $this->getPaisesUser();
        foreach ($paises as $val) {
            $where .= $val->ps_id;
            if (!($val === end($paises))) {
                $where .= ",";
            } else {
                $where .= ")";
            }
        }
        if ($_SESSION['perfil'] == '2') {
            $where .= " and sol_creacion_usuario = '" . $_SESSION['usuario']->usu_codigo . "'";
        }
        return $this->getXMLGRID($post, $campos, $id, $salida_tipo, "solicitud,empresas", $where);
    }

    function getPaisesUser() {
        return $this->db->query("select * from paises, paises_usuarios where paus_id_pais = ps_id "
                        . "and paus_usuario = '" . $_SESSION['usuario']->usu_codigo . "'")->result();
    }

    function setSolicitudes($empresa, $solicitud, $subDominio, $id_sol) {
        $subDominio = strtolower($subDominio);
        $model = new Model();
        $this->db->trans_begin();
        $idSolicitud = "";
        if ($id_sol != '0') {
            $solActual = $model->getRow("solicitud", ['sol_id' => $id_sol]);
            $empresaActual = $model->getRow("empresas", ['emp_id' => $solActual->sol_emp]);
            $sql = $this->updateDatosQ($empresa, "empresas", ['emp_id' => $solActual->sol_emp]);
            $this->db->query($sql);
            if ($solicitud['sol_ambiente'] == '0') {
                $solicitud['sol_amb_url1'] = $subDominio . $this->urls[$empresa['emp_pais']][$solicitud['sol_ambiente']]['url1'];
                $solicitud['sol_amb_url2'] = $subDominio . $this->urls[$empresa['emp_pais']][$solicitud['sol_ambiente']]['url2'];
                $solicitud['sol_amb_url_publica'] = str_replace("*d*", $subDominio, $this->urls[$empresa['emp_pais']][$solicitud['sol_ambiente']]['publica']);
                $solicitud['sol_amb_activo'] = "TRUE";
            } else {
                $solicitud['sol_amb_url1'] = NULL;
                $solicitud['sol_amb_url2'] = NULL;
                $solicitud['sol_amb_url_publica'] = NULL;
                $solicitud['sol_amb_activo'] = "TRUE";
            }
            $solicitud['sol_modificacion_fecha'] = date("Y-m-d H:i:s");
            $solicitud['sol_modificacion_usuario'] = $_SESSION['usuario']->usu_codigo;
            $sql = $this->updateDatosQ($solicitud, 'solicitud', ['sol_id' => $id_sol]);
            $this->db->query($sql);
            $idSolicitud = $id_sol;
        } else {
            $sql = $this->setDatosQ($empresa, "empresas");
            $this->db->query($sql);
            $solicitud['sol_emp'] = $this->db->insert_id();
            if ($solicitud['sol_ambiente'] == '0') {
                $solicitud['sol_amb_url1'] = $subDominio . $this->urls[$empresa['emp_pais']][$solicitud['sol_ambiente']]['url1'];
                $solicitud['sol_amb_url2'] = $subDominio . $this->urls[$empresa['emp_pais']][$solicitud['sol_ambiente']]['url2'];
                $solicitud['sol_amb_url_publica'] = str_replace("*d*", $subDominio, $this->urls[$empresa['emp_pais']][$solicitud['sol_ambiente']]['publica']);
                $solicitud['sol_amb_activo'] = "TRUE";
            } else {
                $solicitud['sol_amb_url1'] = NULL;
                $solicitud['sol_amb_url2'] = NULL;
                $solicitud['sol_amb_url_publica'] = NULL;
                $solicitud['sol_amb_activo'] = "TRUE";
            }
            $solicitud['sol_modificacion_fecha'] = date("Y-m-d H:i:s");
            $solicitud['sol_creacion_fecha'] = date("Y-m-d H:i:s");
            $solicitud['sol_modificacion_usuario'] = $_SESSION['usuario']->usu_codigo;
            $solicitud['sol_creacion_usuario'] = $_SESSION['usuario']->usu_codigo;
            $sql = $this->setDatosQ($solicitud, 'solicitud');
            $this->db->query($sql);
            $idSolicitud = $this->db->insert_id();
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return $idSolicitud;
        }
    }
    function getRemitSolAprov($idPais){
        return $this->db->query("SELECT usuarios.* 
                                FROM paises_usuarios,perfiles_usuarios,usuarios
                                WHERE paus_usuario = pu_us_codigo 
                                AND paus_usuario = usu_codigo
                                AND pu_id_perfil in (1,3)
                                AND paus_id_pais =".$idPais)->result();
    }

}
