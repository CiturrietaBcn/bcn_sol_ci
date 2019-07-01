<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class UsuarioModel extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->db = $this->load->database('default', true);
        $this->load->model("Model");
    }

    function setUsuario($datos, $paises, $perfil, $codigo, $id = 0) {
        $this->db->trans_begin();
        $id_usu = "";
        //datos del usuario
        if ($id === 0) {
            $datos['usu_codigo'] = $codigo;
            $datos['usu_creacion_usuario'] = $_SESSION['usuario']->usu_codigo;
            $datos['usu_creacion_ts'] = date("Y-m-d");
            $datos['usu_modificacion_usuario'] = $_SESSION['usuario']->usu_codigo;
            $datos['usu_modificacion_ts'] = date("Y-m-d");
            $sql = $this->setDatosQ($datos, "usuarios");
            $this->db->query($sql);
            $id_usu = $codigo;
        } else {
            if (empty($datos['usu_contrasena'])) {
                unset($datos['usu_contrasena']);
            }
            $datos['usu_modificacion_usuario'] = $_SESSION['usuario']->usu_codigo;
            $datos['usu_modificacion_ts'] = date("Y-m-d");
            $sql = $this->updateDatosQ($datos, "usuarios", ['usu_codigo' => $id]);
            $this->db->query($sql); 
            $sql = $this->deleteDatosQ("paises_usuarios", ['paus_usuario' => $id]);
            $this->db->query($sql); 
            $id_usu = $id;
        }
        //datos de perfil
        $res = $this->Model->getTodos("perfiles_usuarios", ['pu_us_codigo' => $id_usu]);
        if ($res == FALSE) {
            $resPerfil = [];
        }else{
            $resPerfil = $res;
        }   
        $dPerfil = [
            'pu_id_perfil' => $perfil,
            'pu_us_codigo' => $id_usu
        ];
        if (empty($resPerfil)) {
            $sql = $this->setDatosQ($dPerfil, "perfiles_usuarios");
            $this->db->query($sql);
        } else {
            if (count($resPerfil) == 1) {
                $sql = $this->updateDatosQ($dPerfil, "perfiles_usuarios", ['pu_id_pu' => $resPerfil[0]->pu_id_pu]);
                $this->db->query($sql);
            } else {
                foreach ($resPerfil as $val) {
                    $sql = $this->deleteDatosQ("perfiles_usuarios", ['pu_id_pu' => $val->pu_id_pu]);
                    $this->db->query($sql);
                }
                $sql = $this->setDatosQ($dPerfil, "perfiles_usuarios");
                $this->db->query($sql);
            }
        }
        //paises
        foreach ($paises as $val) {
            $dPais = [
                'paus_id_pais' => $val,
                'paus_usuario' => $id_usu
            ];
            $sql = $this->setDatosQ($dPais, "paises_usuarios");
            $this->db->query($sql);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $this->db->trans_status();
    }

}
