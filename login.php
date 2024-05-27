<?php

session_start();

include 'includes/connection.php';

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://getbootstrap.com/docs/5.3/assets/css/docs.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/ticket_table_script.js"></script>
    <title>Acceso</title>
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .login-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 30px;
            margin-top: 12.5em;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.1);
        }


        .login-title {
            font-size: 2.5rem;
            margin-bottom: 30px;
            text-align: center;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            margin-bottom: 15px;
        }

        .login-btn {
            background-color: #96c565;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .login-btn:hover {
            background-color: #86af4b;
        }

        .error-message {
            color: red;
            font-size: 1rem;
            margin-top: 10px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container-fluid h-100">
        <div class="row justify-content-center mt-5">
            <div class="col-lg-4 align-self-center">
                <div class="login-container">
                    <h2 class="login-title">LOGIN</h2>
                    <form method="post" class="login-form">
                        <div class="mb-4">
                            <label for="username" class="form-label">Usuario:</label>
                            <input id="username" name="username" minlength="4" maxLength="32" type="text"
                                class="form-control" placeholder="Introduzca nombre de usuario" required />
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Contraseña:</label>
                            <input id="password" name="password" minlength="6" maxlength="16" required
                                type="password" class="form-control" placeholder="Introduzca contraseña" />
                        </div>
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="showPassword">
                            <label class="form-check-label" for="showPassword">Mostrar contraseña</label>
                        </div>
                        <?php

                        if (isset($_POST['login'])) {
                            // Get the form data
                            $nombreUsuario = $_POST['username'];
                            $password = $_POST['password'];

                            // Prepare SQL statement
                            $stmt = $conn->prepare("SELECT id_usuario, pass_usuario, id_departamento, disabled FROM usuarios WHERE nombre_usuario = ?");
                            $stmt->bind_param("s", $nombreUsuario);

                            // Execute the statement
                            $stmt->execute();
                            $stmt->store_result();

                            // Checks if user exists
                            if ($stmt->num_rows > 0) {
                                $stmt->bind_result($id, $hashed_password, $idDepartamentoLogeado, $usuarioDesactivado);

                                // Fetch the result
                                $stmt->fetch();

                                // Verify the password
                                if ($password === $hashed_password) {
                                    // Check if the user is disabled
                                    if ($usuarioDesactivado == 1) {
                                        echo "<div class='errorMessage'>El usuario introducido está deshabilitado. Contacte con el administrador</div>";
                                    } else {
                                        // User is not disabled, proceed with login
                                        $_SESSION['loggedin'] = true;
                                        $_SESSION['id'] = $id;
                                        $_SESSION['username'] = $nombreUsuario;
                                        $_SESSION['department_id'] = $idDepartamentoLogeado;

                                        if (empty($_SERVER['REMOTE_ADDR'])) {
                                            $_SESSION['location'] = ''; // Set default location here
                                        }

                                        header("Location: ticket_table.php");
                                        exit;
                                    }
                                } else {
                                    echo "<div class='errorMessage'>Ha habido un error. Contraseña incorrecta.</div>";
                                }
                            } else {
                                echo "<div class='errorMessage'>El usuario introducido no existe</div>";
                            }

                            $stmt->close();
                            $conn->close();
                        }
                        ?>
                        <div class="text-center">
                            <button name="login" type="submit" class="btn btn-primary login-btn">Acceder</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>