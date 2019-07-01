<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getXMLGRID($post, $campos, $id, $salida_tipo, $from, $where) {
        $pagina_actual = $this->securePOST($post['page']);
        $pagina_limite = $this->securePOST($post['rows']);
        $orden_campo = $this->securePOST($post['sidx']);
        $orden_tipo = $this->securePOST($post['sord']);
        $omitidos = ['page', 'rows', 'sidx', 'sord', '_search', 'nd'];
        $orden = "";
        if ($orden_campo != "") {
            $orden = "ORDER BY $orden_campo $orden_tipo ";
        }
        $consulta_filtros = "";
        foreach ($omitidos as $val) {
            unset($post[$val]);
        }
        foreach ($post as $key => $val) {
            if (array_key_exists($key, $campos)) {
                $consulta_filtros .= $this->composeSQL($this->securePOST($val), $key, $campos[$key]);
            }
        }
        $sql_calculo = "Select count(*) as contador FROM $from WHERE $where "
                . $consulta_filtros;
        $calc = $this->db->query($sql_calculo)->row();
        $total_registros = $calc->contador;
        $total_paginas = 0;
        if ($pagina_limite == "") {
            $limit = 100;
        };
        if ($pagina_actual == "") {
            $page = 1;
        };
        // Calcula el numero total de paginas de la consulta
        if ($total_registros > 0 && $pagina_limite > 0) {
            $total_paginas = ceil($total_registros / $pagina_limite);
        };
        // Valida que la pagina no sea mayor que el maximo 
        if ($pagina_actual > $total_paginas) {
            $pagina_actual = $total_paginas;
        }
        // Calcula el primer registro a mostrar
        $primer_registro = $pagina_limite * $pagina_actual - $pagina_limite;
        // Valida el primer registro a mostrar 
        if ($primer_registro < 0) {
            $primer_registro = 0;
        }
        // Genera la Consulta SQL
        $sql_consulta = "Select * FROM $from WHERE $where "
                . $consulta_filtros
                . $orden . " LIMIT $pagina_limite OFFSET $primer_registro";
        $datos_consulta = $this->db->query($sql_consulta)->result();
        // Genera la cabecera
        if ($salida_tipo == 'xml') {
            $formato_salida = "<?xml version='1.0' encoding='utf-8'?>"
                    . "<rows>"
                    . "<page>" . $pagina_actual . "</page>"
                    . "<total>" . $total_paginas . "</total>"
                    . "<records>" . $total_registros . "</records>";
        } else {
            $formato_salida = "";
            foreach ($omitidos as $val) {
                unset($post[$val]);
            }
            foreach ($post as $val) {
                $formato_salida .= $val;
                if ($val !== end($post)) {
                    $formato_salida .= "\n";
                } else {
                    $formato_salida .= ";";
                }
            }
        }
        // Genera el cuerpo 
        foreach ($datos_consulta as $val) {
            if ($salida_tipo == 'xml') {
                $formato_salida .= "<row id='" . $val->{$id} . "'>";
                foreach ($campos as $key => $v) {
                    if (in_array($key, ['string', 'date'])) {
                        $formato_salida .= "<cell>" . htmlspecialchars($val->{$key}) . "</cell>";
                    } else {
                        $formato_salida .= "<cell>" . $val->{$key} . "</cell>";
                    }
                }
                $formato_salida .= "</row>";
            } else {
                foreach ($post as $val) {
                    $formato_salida .= $val;
                    if ($val !== end($post)) {
                        $formato_salida .= "\n";
                    } else {
                        $formato_salida .= ";";
                    }
                }
                foreach ($datos_consulta as $val) {
                    foreach ($val as $k => $value) {
                        if (in_array($k, $post)) {
                            if (in_array($k, $string) || in_array($k, $dates)) {
                                $formato_salida .= fixLF($value);
                            } else {
                                $formato_salida .= $value;
                            }
                            if ($value !== end($val)) {
                                $formato_salida .= "\n";
                            } else {
                                $formato_salida .= ";";
                            }
                        }
                    }
                }
            }
        }
        if ($salida_tipo == 'xml') {
            $formato_salida .= "</rows>";
        }
        return $formato_salida;
    }

    function securePOST($variable, $solonumeros = FALSE) {
        $respuesta = pg_escape_string(trim($variable));
        if ($solonumeros) {
            $respuesta = filtraNumeros($respuesta);
        }
        return $respuesta;
    }

    function fixLF($texto_origen) {
        $texto_origen = str_replace("'", "", $texto_origen);
        $texto_origen = str_replace('"', "", $texto_origen);
        $texto_origen = str_replace("\n", " ", $texto_origen);
        $texto_origen = str_replace("\r", " ", $texto_origen);
        $texto_origen = str_replace(";", ":", $texto_origen);
        return($texto_origen);
    }

    function composeSQL($parametro, $campo, $tipoparametro, $unico = 0) {
        $respuesta = "";
        if ($parametro != "") {
            $subconsulta = "";
            if ($tipoparametro == "numeric") {
                $subconsulta = $this->busqueda_numerica($parametro, $campo);
            }
            if ($tipoparametro == "string") {
                $expresiones = explode(' ', $parametro);
                foreach ($expresiones as $individual) {
                    $individual = trim($individual);
                    if ($individual != "") {
                        if ($subconsulta != "") {
                            $subconsulta = $subconsulta . " AND ";
                        }
                        $subconsulta = $subconsulta . $campo . " ILIKE '%$individual%' ";
                    }
                }
            }
            if ($tipoparametro == "date") {
                $subconsulta = busqueda_fecha($parametro, $campo);
            }
            if ($tipoparametro == "boolean") {
                if ($parametro == true) {
                    $subconsulta = $campo;
                }
                if ($parametro == false) {
                    $subconsulta = " not " . $campo;
                }
                if ($parametro == "NULL") {
                    $subconsulta = $campo . " is NULL ";
                }
            }
            if ($tipoparametro == "yesno") {
                if ($parametro == "Y") {
                    $subconsulta = $campo;
                }
                if ($parametro == "N") {
                    $subconsulta = " not " . $campo;
                }
            }
            if ($tipoparametro == "truefalse") {
                if ($parametro == "t") {
                    $subconsulta = $campo;
                }
                if ($parametro == "f") {
                    $subconsulta = " not " . $campo;
                }
            }
            if ($subconsulta != "") {
                if ($unico == 0) {
                    $respuesta = $respuesta . " AND ";
                }
                $respuesta = $respuesta . $subconsulta . " ";
            }
        }
        return $respuesta;
    }

    function busqueda_numerica($parametro, $variable) {
        $salida = "";
        $parametro = trim($parametro);
        $comparacion = "";
        $numeros = "";

        while (strlen($parametro) != 0) {
            $caracter = substr($parametro, 0, 1);
            $parametro = substr($parametro, 1, 1000);
            if (strpos(' 1234567890-', $caracter) != FALSE) {
                $numeros = $numeros . $caracter;
            }
            if (strpos(' ><=', $caracter) != FALSE) {
                $comparacion = $comparacion . $caracter;
            }
        }

        if (($comparacion == "") || ($comparacion == ">") || ($comparacion == "<") || ($comparacion == ">=") ||
                ($comparacion == "<=") || ($comparacion == "<>") || ($comparacion == "=")) {
            if ($numeros != "") {
                $numeros = number_format($numeros, 0, "", "");
                if ($comparacion == "") {
                    $comparacion = "=";
                }
                $salida = $variable . " " . $comparacion . "  " . $numeros;
            }
        }
        return $salida;
    }

    function busqueda_fecha_estandar($parametro, $variable) {

        $dia = "";
        $mes = "";
        $ano = "";
        $salida = "";
        $parametro = trim($parametro);

        $comparacion = "";
        $numeros = "";

        while (strlen($parametro) != 0) {
            $caracter = substr($parametro, 0, 1);
            $parametro = substr($parametro, 1, 1000);
            if (strpos(' 1234567890-/', $caracter) != FALSE) {
                $numeros = $numeros . $caracter;
            }
            if (strpos(' ><=', $caracter) != FALSE) {
                $comparacion = $comparacion . $caracter;
            }
        }

        if ($numeros != "") {
            $ano = "";
            $mes = "";
            $dia = "";
            $numeros = str_replace('/', '-', $numeros);
            if (strpos($numeros, '-') == FALSE) {
                $ano = $numeros;
                $numeros = "";
            } else {
                $ano = substr($numeros, 0, strpos($numeros, '-'));
                $numeros = substr($numeros, strpos($numeros, '-') + 1);
            }
            if (strpos($numeros, '-') == FALSE) {
                $mes = $numeros;
                $numeros = "";
            } else {
                $mes = substr($numeros, 0, strpos($numeros, '-'));
                $numeros = substr($numeros, strpos($numeros, '-') + 1);
            }
            if (strpos($numeros, '-') == FALSE) {
                $dia = $numeros;
                $numeros = "";
            } else {
                $dia = substr($numeros, 0, strpos($numeros, '-'));
                $numeros = substr($numeros, strpos($numeros, '-') + 1);
            }
            $dia = trim(str_replace('-', '', $dia));
            $mes = trim(str_replace('-', '', $mes));
            $ano = trim(str_replace('-', '', $ano));

            if ((($dia != "") && ($mes != "") && ($ano != "")) ||
                    (($dia == "") && ($mes != "") && ($ano != "")) ||
                    (($dia == "") && ($mes == "") && ($ano != ""))) {
                if ($dia != "") {
                    $dia = number_format($dia, 0, "", "");
                }
                if ($mes != "") {
                    $mes = number_format($mes, 0, "", "");
                }
                $ano = number_format($ano, 0, "", "");

                if (($ano < 2000) || ($ano > 2100)) {
                    $ano = "";
                    $mes = "";
                    $dia = "";
                } else {
                    if ($mes == "") {
                        $dia = "";
                    } else {
                        if (($mes > 12) || ($mes < 1)) {
                            $dia = "";
                            $mes = "";
                        } else {
                            if ($dia != "") {
                                $maximo_dia = 31;
                                if ($mes == 4 || $mes == 6 || $mes == 9 || $mes == 11) {
                                    $maximo_dia = 30;
                                }
                                if ($mes == 2) {
                                    $maximo_dia = 28;
                                }
                                if (($mes == 2) && (($ano % 4) == 0) && (($ano % 500) != 0)) {
                                    $maximo_dia = 29;
                                }
                                if ($dia < 1 || $dia > $maximo_dia) {
                                    $dia = "";
                                }
                            }
                        }
                    }
                }
            } else {
                $dia = "";
                $mes = "";
                $ano = "";
            }
        }

        if (($comparacion == "") || ($comparacion == ">") || ($comparacion == "<") || ($comparacion == ">=") ||
                ($comparacion == "<=") || ($comparacion == "<>") || ($comparacion == "=")) {
            if ($comparacion == "") {
                $comparacion = "=";
            }
            if (($dia == "") && ($mes == "") && ($ano != "")) {
                $salida = "date_part('year',$variable) $comparacion $ano";
            }
            if (($dia != "") && ($mes != "") && ($ano != "")) {
                $salida = "$variable $comparacion '$ano-$mes-$dia'";
            }
            if (($dia == "") && ($mes != "") && ($ano != "")) {
                if ($comparacion == "=") {
                    $salida = "(date_part('year',$variable) $comparacion $ano) and (date_part('month',$variable) $comparacion $mes) ";
                } else {
                    $salida = "$variable $comparacion '$ano-$mes-1'";
                }
            }
        }

        return $salida;
    }

    function busqueda_fecha($parametro, $variable) {
        $dia = "";
        $mes = "";
        $ano = "";

        $salida = "";
        $parametro = trim($parametro);

        $comparacion = "";
        $numeros = "";
        $numeros2 = "";

        $separador = 0;
        $parametro_original = $parametro;
        while (strlen($parametro) != 0) {
            $caracter = substr($parametro, 0, 1);
            $parametro = substr($parametro, 1, 1000);
            if ((strpos(' 1234567890-/', $caracter) != FALSE) && ($separador == 0)) {
                $numeros = $numeros . $caracter;
            }
            if ((strpos(' 1234567890-/', $caracter) != FALSE) && ($separador == 1)) {
                $numeros2 = $numeros2 . $caracter;
            }
            if (strpos(' ><=', $caracter) != FALSE) {
                $comparacion = $comparacion . $caracter;
            }
            if ('a' == $caracter) {
                $separador = 1;
            }
        }
        if (($numeros != '') && ($numeros2 != '')) {
            $salida_parte1 = busqueda_fecha_estandar(">=" . $numeros, $variable);
            $salida_parte2 = busqueda_fecha_estandar("<=" . $numeros2, $variable);
            $salida = $salida_parte1;
            if ($salida == '') {
                $salida = $salida_parte2;
            } else {
                if ($salida_parte2 != '') {
                    $salida .= " and " . $salida_parte2;
                }
            }
        } else {
            $salida = busqueda_fecha_estandar($parametro_original, $variable);
        }
        return ($salida);
    }

    function setDatosQ($datos, $tabla) {
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
        return $query;
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

    function updateDatosQ($datos, $tabla, $id) {
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
        return $query . $this->where($id);
    }

    function deleteDatosQ($tabla, $id) {
        $query = 'DELETE FROM ' . $tabla;
        $x = 1;
        $query;
        return $query . $this->where($id);
    }

}
