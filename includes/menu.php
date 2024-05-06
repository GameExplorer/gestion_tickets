<!DOCTYPE html>
<html lang="en">


    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Navbar</title>
        <link rel="stylesheet" href="css/menu_style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>

    <body>

        <?php include 'numTienda.php'; ?>

        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img src="assets/CentralUniformesLogo.png" alt="Central Uniformes Logo" width="72" height="72">
                </a>
                <div class="LocationText">
                    <?php echo "Sede: $nombre"; ?>
                </div>
                <div class="title"><?php echo $pageTitle; ?></div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link underline" href="index.php">Index</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link underline" href="ticket_form.php">Nuevo Ticket</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link underline" href="ticket_table.php">Ver Tickets</a>
                        </li>
                    </ul>
                    <div></div>
                    <ul class="navbar-nav">
                        <li class="nav-item login">
                            <?php if (isset($_SESSION['loggedin'])) {
                                echo '<a class="nav-link" href="logout.php">Logout</a>';
                            } else {
                                echo '<a class="nav-link" href="login.php">Login</a>';
                            } ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    </body>

</html>