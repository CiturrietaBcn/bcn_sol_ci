<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AmbcertModel extends MY_Model {

    public function __construct($conect = "default") {
        parent::__construct();
        $this->db = $this->load->database($conect, true);
    }

    function getAmbientes() {
        return $this->db->query("select amb_id, (amb_nombre || ' - ' || amb_id) as nombre "
                        . "from ambientes where amb_activo = true order by amb_nombre asc")->result();
    }

    function setCargarSolicitud($empresa, $sol, $pais) {
        $idAmbiente = null;
        $idEmpresa = null;
        $this->db->trans_start(TRUE); // Query will be rolled back
        if ($sol->sol_amb_id == "0") {
            $ultimoIdAmb = $this->db->query("select MAX(amb_id) as maximo from ambientes")->row();
            $datosAmb = [
                'amb_id' => ($ultimoIdAmb->maximo) + 1,
                'amb_nombre' => $sol->sol_amb_nombre,
                'amb_descripcion' => "AMBIENTE " . (($sol->sol_ambiente == 0) ? "CERTIFICACIÓN - " : "PRODUCCIÓN - ") . strtoupper($sol->sol_amb_nombre),
                'amb_url1' => $sol->sol_amb_url1,
                'amb_url2' => $sol->sol_amb_url2,
                'amb_texto' => "AMBIENTE " . (($sol->sol_ambiente == 0) ? "CERTIFICACIÓN - " : "PRODUCCIÓN - ") . strtoupper($sol->sol_amb_nombre),
                'amb_activo' => 'TRUE',
                'amb_url_publica' => $sol->sol_amb_url_publica
            ];
            $sql = $this->setDatosQ($datosAmb, 'ambientes');
            $this->db->query($sql);
            $idAmbiente = ($ultimoIdAmb->maximo) + 1;
        } else {
            $idAmbiente = $sol->sol_amb_id;
        }
        $sql = "select MAX(emp_id) as maximo from empresas";
        $empId = $this->db->query($sql)->row();
        $idEmpresa = $empId->maximo + 1;
        $datosEmpresa = [
            'emp_id' => $idEmpresa,
            'emp_amb_id' => $idAmbiente,
            'emp_rut' => $empresa->emp_rut,
            'emp_razonsocial' => $empresa->emp_razonsocial,
            'emp_direccion' => $empresa->emp_direccion,
            'emp_comuna' => $empresa->emp_comuna,
            'emp_ciudad' => $empresa->emp_ciudad,
            'emp_pais' => strtoupper($pais),
            'emp_fecha_registro' => $sol->sol_postulacionfecha,
            'emp_fono' => $empresa->emp_fono,
            'emp_resolucion_fecha' => $sol->sol_emp_resolucionfecha,
            'emp_resolucion_nro' => $sol->sol_emp_resolucionnumero,
            'emp_activo' => 'TRUE',
            'emp_mail_consultor' => $empresa->emp_cont_mail_comer,
            'emp_mail_tecnico' => $empresa->emp_cont_mail_tec,
            'emp_contacto_tecnico' => $empresa->emp_cont_nom_tec,
            'emp_tipo_implementacion' => $sol->sol_emp_implementacion,
            'emp_contacto_comercial' => $empresa->emp_cont_nom_comer
        ];
        $sql = $this->setDatosQ($datosEmpresa, 'empresas');
        $this->db->query($sql);
        $this->db->trans_complete();
        return ['estado' => $this->db->trans_status(), 'id_amb' => $idAmbiente];
    }

}
