<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public $urls = array(
        1 => [//chile
            0 => ['url1' => "-cert.getdte.cl", 'url2' => '-cert2.getdte.cl', 'publica' => 'https://*d*-cert.getdte.cl'], //certificacion
            1 => ['url1' => ".getdte.cl", 'url2' => '-prod2.getdte.cl', 'publica' => 'https://*d*.getdte.cl']//produccion
        ],
        2 => [//peru
            0 => ['url1' => "url1", 'url2' => 'url2', 'publica' => 'publica'],
            1 => ['url1' => "url1", 'url2' => 'url2', 'publica' => 'publica']
        ]
    );
    public $parametrosInicio = [
        'ambiente_descripcion' => "Desarrollo plataforma de solicitudes multi-pais",
        'software_nombre' => "BCN Solicitudes",
        'software_logo' => "include/logos/bcn_consultores.png",
        'ambiente_descripcion' => "Desarrollo plataforma de solicitudes multi-pais"
    ];
    public $configLocal = [
        '_APP_LOCATION' => "/bcn/getfel/desarrollo",
        '_APP_ARCHIVOS' => "/bcn/getfel/desarrollo/archivos",
        '_GETONE_LOCATION' => "/bcn/getoneplus/getoneplus",
        '_APP_SERVER' => "GetFEL-Desarrollo",
        'software_version' => "v2.0.0",
        'software_date' => "28/12/2018"
    ];

    function __construct() {
        parent::__construct();
        session_start();
        $this->load->model('GestionModel');
        $this->load->model('Model');
        if (!isset($_SESSION['usuario']) and isset($_COOKIE['usuario']) and $_COOKIE['usuario'] != '') {
            $this->setSesiones();
        }
    }

    function setSesiones() {
        foreach ($_COOKIE as $k => $v) {
            $_SESSION[$k] = $_COOKIE[$k];
        }
    }

    function borrarCookies() {
        session_destroy();
        foreach ($_COOKIE as $k => $v) {
            setcookie($k, null, time() + (3600 * 24 * 365), '/');
        }
        redirect(base_url(), 'location');
    }

    function getHtml($page, $data = null) {
        if (!isset($_SESSION['usuario'])) {
            $this->login();
        } else {
            if (!file_exists(APPPATH . 'views/' . $page . '.php')) {
                $page = 'tema/sitio/dashboar';
            }
            $this->load->view('tema/sitio/head');
            $this->load->view('tema/sitio/menu');
            $this->load->view($page, $data);
            $this->load->view('tema/foot');
        }
    }

    function login() {
        $data = "";
        if (isset($_POST) && (!empty($_POST))) {
            $res = $this->GestionModel->getInicioAjax($_POST['loginUsuario'], $_POST['loginClave'], (isset($_POST['loginRecordar'])) ? $_POST['loginRecordar'] : "");
            if ($res['estado'] == "OK") {
                $_SESSION['usuario'] = $res['datosUsuario'];
                $perf = $this->Model->getRow("perfiles_usuarios", ['pu_us_codigo' => $_SESSION['usuario']->usu_codigo]);
                $_SESSION['perfil'] = $perf->pu_id_perfil;
                redirect(base_url(), 'location');
            } else {
                $data['mensaje'] = $res['mensaje'];
                $data['user'] = $_POST['loginUsuario'];
            }
        }
        $this->load->view('tema/login/head');
        $this->load->view('tema/login/login', $data);
        $this->load->view('tema/foot');
    }
}

?>
