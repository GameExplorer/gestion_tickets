<?php

session_start();

include 'includes/connection.php';

?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="css/login_style.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/ticket_table_script.js"></script>
        <title>Acceso</title>
    </head>

    <body>
        <div class="container">
            <form method="post">
                <h1>LOGIN</h1>
                <label for="username">Usuario:</label>
                <input id="username" name="username" minlength="4" maxLength="32" type="text" class="inputStyle"
                    placeholder="Introduzca nombre de usuario" required />

                <label for="password">Contraseña:</label>
                <input id="password" name="password" minlength="6" maxlength="16" required type="password"
                    class="inputStyle" placeholder="Introduzca contraseña" />
                <input type="checkbox" class="show-password"
                    onchange="document.getElementById('password').type = this.checked ? 'text' : 'password'"> Mostrar contraseña<br>
                <?php

                if (isset($_POST['login'])) {
                    // Get the form data
                    $nombreUsuario = $_POST['username'];
                    $password = $_POST['password'];

                    // Prepare SQL statement
                    $stmt = $conn->prepare("SELECT id_usuario, pass_usuario, id_departamento FROM usuarios WHERE nombre_usuario = ?");
                    $stmt->bind_param("s", $nombreUsuario);

                    // Execute the statement
                    $stmt->execute();
                    $stmt->store_result();

                    // Checks if user exists
                    if ($stmt->num_rows > 0) {
                        $stmt->bind_result($id, $hashed_password, $idDepartamentoLogeado);
                
                        // Fetch the result
                        $stmt->fetch();

                        // Verify the password
                        if ($password === $hashed_password) {
                            $_SESSION['loggedin'] = true;
                            $_SESSION['id'] = $id;
                            $_SESSION['username'] = $nombreUsuario;
                            $_SESSION['department_id'] = $idDepartamentoLogeado;
                
                            if (empty($_SERVER['REMOTE_ADDR'])) {
                                $_SESSION['location'] = ''; // Set default location here
                            }

                            header("Location: ticket_table.php");
                            exit;
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


                <input name="login" type="submit" value="Acceder" class="login" />
            </form>
        </div>


    </body>

</html>