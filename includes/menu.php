<!DOCTYPE html>
<html lang="en">


    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Responsive Navbar</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <style>
            .navbar-custom {
                position: sticky;
                background-color: #f8f9fa;
                width: 100%;
                margin-bottom: 2.5rem;
            }

            .navbar-brand img {
                width: 72px;
                height: auto;
                margin-right: 10px;
            }

            .navbar-nav .nav-link {
                color: #343a40;
                font-size: 1.25em;
                margin-right: 12px;
                margin-left: 8px;
                text-transform: uppercase;
            }

            .title {
                font-size: 2em;
                font-weight: 500;
                text-align: center;
                text-transform: uppercase;
                margin-left: 10rem;
            }

            .LocationText {
                text-align: center;
                font-size: 1em;
                color: #6c757d;
            }

            @media (max-width: 991.98px) {
                .navbar-collapse {
                    display: block;
                    text-align: center;
                }

                .navbar-nav {
                    display: inline-block;
                    margin-top: 10px;
                }

                .title {
                    display: inline-block;
                    margin-left: 10px;
                }
            }

            /* Nav items hover effect */
            .underline {
                display: inline;
                position: relative;
                overflow: hidden;
            }

            .underline:after {
                content: "";
                position: absolute;
                z-index: -1;
                right: 0;
                width: 0;
                bottom: -5px;
                background: #000;
                height: 2px;
                transition: width 0.4s ease-out;
            }

            .underline:hover:after,
            .underline:focus:after,
            .underline:active:after {
                left: 0;
                right: auto;
                width: 100%;
            }

            /* Login button */
            .login {
                width: 140px;
                height: auto;
                background-color: #96c565;
                text-align: center;
                border-radius: 10px;
            }

            .login:hover {
                background-color: inherit;
                color: #96c565;
                border: 3px solid #96c565;
                transition: background-color 0.4s ease-out, color 0.4s ease-out, border-color 0.4s ease-out;
            }
        </style>
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