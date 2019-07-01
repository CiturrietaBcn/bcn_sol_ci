<div class="row">
    <div class="col-md-12 text-center" id="head-wrp">
        <div> 
            <img src="<?= $this->parametrosInicio['software_logo'] ?>" width="auto" height="78" alt="<?= $this->parametrosInicio['software_nombre'] ?>">
        </div>
    </div>
</div>
<div class="row d-none d-sm-block">
    <div class="col-md-12 d-none d-sm-block">
        <div id="titulo-sitio-01">
            <div class="titulo-sitio-01">
                Bienvenidos a la plataforma <?= $this->parametrosInicio['software_nombre'] . " " . $this->configLocal['software_version'] ?>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="titulo-sitio-02"><?= $this->parametrosInicio['ambiente_descripcion'] ?></div>
    </div>
</div>
<div class="row d-block d-sm-none">
    <div class="col text-center">
        <b>Bienvenidos a la plataforma <?= $this->parametrosInicio['software_nombre'] . "<br>" . $this->configLocal['software_version'] ?></b>
        <br>
        <i>
            <?= $this->parametrosInicio['ambiente_descripcion'] ?>
        </i>
    </div>
</div>
<br><br>
<div class="container">
    <form method="post" id="formLogin">
        <?php if (isset($mensaje)): ?>
            <div class="row justify-content-center">
                <div class="col-sm-4 col-xs-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $mensaje ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="row justify-content-center">
            <div class="col-sm-4 col-xs-12">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <img src="./include/imagenes/2.0/login_username.png" border="0" width="20" height="20">
                        </span>
                    </div>
                    <input type="text" class="form-control" name="loginUsuario" id="loginUsuario" placeholder="Nombre de Usuario" required=""
                           value="<?=(isset($user))?$user:""?>">
                </div>
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="col-sm-4 col-xs-12">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <img src="./include/imagenes/2.0/login_password.png" border="0" width="20" height="20">
                        </span>
                    </div>
                    <input type="password" class="form-control" placeholder="Contraseña" id="loginClave" name="loginClave" required>
                </div>
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="col-sm-4 col-xs-12">
                <input title="Recordar mis datos" type="checkbox" name="loginRecordar" id="loginRecordar" class="form-check-inline"> Recordar datos
                <input type="submit" value="INGRESAR" onclick="" class="btn btn-success f-r float-right">
            </div>
        </div>
    </form>
</div>