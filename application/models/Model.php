<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->db = $this->load->database('default', true);
    }

    function setDatosB($datos, $tabla) {
        $query = 'INSERT INTO ' . $tabla . ' ';
        $campos = '(';
        $valores = '(';
        $totalElementos = count($datos);
        $x = 1;
        foreach ($datos as $key => $val) {
            $campos = $campos . $key;
            $valores = $valores . "'" . $val . "'";
            if ($x != ($totalElementos)) {
                $campos = $campos . ',';
                $valores = $valores . ',';
            }
            $x++;
        }
        $campos = $campos . ')';
        $valores = $valores . ')';
        $query = $query . $campos . " VALUES " . $valores;
        $this->db->query($query);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function setDatosId($datos, $tabla) {
        $query = 'INSERT INTO ' . $tabla . ' ';
        $campos = '(';
        $valores = '(';
        $totalElementos = count($datos);
        $x = 1;
        foreach ($datos as $key => $val) {
            $campos = $campos . $key;
            $valores = $valores . "'" . $val . "'";
            if ($x != ($totalElementos)) {
                $campos = $campos . ',';
                $valores = $valores . ',';
            }
            $x++;
        }
        $campos = $campos . ')';
        $valores = $valores . ')';
        $query = $query . $campos . " VALUES " . $valores;
        $this->db->query($query);
        //echo $query;
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    function where($datos) {
        $query = "";
        if (!empty($datos)) {
            $query = " WHERE ";
            $x = 1;
            foreach ($datos as $key => $val) {
                $query .= $key . "='" . $val . "'";
                if ($x != (count($datos))) {
                    $query .= ' and ';
                }
                $x++;
            }
        }
        return $query;
    }

    function whereLike($datos) {
        $query = "";
        if (!empty($datos)) {
            $query = " WHERE ";
            $x = 1;
            foreach ($datos as $key => $val) {
                if (strlen($val) > 1 or $val > 0) {
                    if ($x > 1) {
                        $query .= ' and ';
                    }
                    $query .= $key . " LIKE '%" . $val . "%'";
                    $x++;
                }
            }
        }
        return $query;
    }

    function updateDatosb($datos, $tabla, $id) {
        $query = 'UPDATE ' . $tabla . ' SET ';
        $totalElementos = count($datos);
        $x = 1;
        foreach ($datos as $key => $val) {
            $query .= $key . "='" . $val . "'";
            if ($x != ($totalElementos)) {
                $query .= ',';
            }
            $x++;
        }
        $x = 1;
        $this->db->query($query . $this->where($id));
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function deleteDatosB($tabla, $id) {
        $query = 'DELETE FROM ' . $tabla;
        $x = 1;
        $query;
        $this->db->query($query . $this->where($id));
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function getTodos($tabla, $datos = array(), $orden = null) {
        $sql = 'SELECT * FROM ' . $tabla . $this->where($datos);
        if ($orden != null) {
            $sql .= ' ORDER BY ' . $orden;
        }
        return $this->db->query($sql)->result();
    }

    function getTodosLike($tabla, $datos = array(), $orden = null) {
        $sql = 'SELECT * FROM ' . $tabla . $this->whereLike($datos);
        if ($orden != null) {
            $sql .= ' ORDER BY ' . $orden;
        }
        return $this->db->query($sql)->result();
    }

    function getRow($tabla, $condic) {
        return $this->db->query('SELECT * FROM ' . $tabla . $this->where($condic))->row();
    }

    function getMaxId($tabla, $id) {
        return $this->db->query('SELECT MAX(' . $id . ') as ' . $id . ' FROM ' . $tabla)->row();
    }

    function sp($sp, $parametros = null) {
        if ($parametros == null) {
            $result = $this->db->query('CALL ' . $sp . '();');
            echo 'CALL ' . $sp . '();<br>';
            //$result->free_result();
        } else {
            $sql = "CALL " . $sp . "(";
            $x = 0;
            if (!empty($parametros)) {
                foreach ($parametros as $p) {
                    if ($x > 0) {
                        $sql .= ",";
                    }
                    $sql .= "'" . $p . "'";
                    $x++;
                }
            }
            $sql .= ");";
            echo '<br>' . $sql . '<br>';
            $result = $this->db->query($sql);
            //$result->free_result();
        }
        return $result->result();

        //clean_mysqli_connection($this->db->conn_id);
    }

}
