<div class="   p-1 w-100">
    <fieldset>
        <legend>iniciar session</legend>
        <form method="POST">
            <label class="form-label" for="">Dni:</label>
            <input class="form-control" type="text" id="" name="dni" maxlength="9" required><br>
            <label class="form-label" for="">Contraseña:</label>
            <input class="form-control" type="password" id="" name="contrasena" maxlength="8" required><br>
            <div class="d-flex justify-content-between ">
                <button class="rounded btn-primary" name="login" type="submit">Iniciar sesión</button>
                <a class="btn btn-primary ms-1" href="recuperarcontraseña.php">recuperar contraseña</a>
               
            </div>

        </form>

    </fieldset>
    <h5>no eres cliente?</h5>
    <a class="btn btn-primary" href="insertar.php">registra por aqui</a>
     <a class="btn btn-primary ms-1 float-end" href="activarcuenta.php">activar cuenta</a>
</div>
