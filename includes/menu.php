<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Menu</title>
        <link rel="stylesheet" href="css/menu_style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>

    <body>

        <?php
        include 'numTienda.php';
        include 'connection.php';
        if (isset($_SESSION['loggedin'])) {

            $user = $_SESSION['username'];
            $deptId = $_SESSION['department_id'];
            $stmt = mysqli_prepare($conn, "SELECT nombre_departamento FROM departamentos WHERE id_departamento = ?");
            mysqli_stmt_bind_param($stmt, "i", $deptId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $deptRow = mysqli_fetch_assoc($result);
                $dept = $deptRow['nombre_departamento'];
            } else {
                $dept = "Unknown Department";
            }

            mysqli_stmt_close($stmt);
        }
        ?>


        <nav class="navbar-custom navbar navbar-expand-lg ">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img src="assets/CentralUniformesLogo.png" alt="Central Uniformes Logo" width="72" height="72">
                </a>
                <div class="px-5 headerText">
                    <?php
                    if (isset($_SESSION['loggedin'])) {
                        echo "$user, $dept";
                    } else {
                        echo "Sede: $nombre";
                    }
                    ?>
                </div>
                <div class="title"><?php echo $pageTitle; ?></div>
                <button class="p-2 mx-5 navbar-toggler order-3" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link underline" href="ticket_form.php">Nuevo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link underline" href="index.php">Abiertos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link underline" href="ticket_table.php">En Curso</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link underline" href="closed_tickets.php">Cerradas</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item login">
                            <?php if (isset($_SESSION['loggedin'])) {
                                echo '<a class="nav-link" href="logout.php">Salir</a>';
                            } else {
                                echo '<a class="nav-link" href="login.php">Acceder</a>';
                            } ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    </body>

</html>