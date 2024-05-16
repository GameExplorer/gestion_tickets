<?php
session_start();
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Detalles Ticket</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/view_ticket_style.css" <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <style>
            body {
                width: 100%;
                margin: 0;
                padding: 0;
                overflow-x: hidden;
            }

            h1 {
                text-align: center;
                text-transform: uppercase;
                font-weight: 900;
                padding-top: 15px;
                padding-bottom: 5px;
            }

            h3 {
                text-align: center;
                text-transform: uppercase;
                border-bottom: 2px solid #c0c0c0;
            }

            h4 {
                margin-left: 10px;
            }

            h5 {
                margin-left: 10px;
                font-size: 1.3em;
                text-transform: uppercase;
                font-weight: bold;
                margin-left: 10px;
                border-bottom: 2px solid #c0c0c0;
            }

            .formControl {
                border-radius: 5px;
                padding-top: 7px;
                padding-bottom: 7px;
                background-color: #f8f9fa;
            }

            label {
                display: inline-block;
                padding: 5px;
            }

            textarea {
                width: 100%;
                height: 100px;
            }

            .ticket {
                font-weight: 400;
                color: #3366cc;
                font-size: 1.25em;
                text-transform: uppercase;
                padding: 10px;
                margin-left: 10px;
                margin-bottom: 15px;
                align-items: center;
            }

            .ticketUnderline {
                border-bottom: 1px solid #ccc;
                margin-bottom: 10px;
                width: 100%;
            }

            .ticketText {
                font-size: 1.25em;
                font-weight: 500;
            }

            .descText {
                margin: 10px;
                padding: 10px;
                width: 95%;
                height: 150px;
                border: 1px solid #ccc;
                resize: none;
            }

            .comment {
                background-color: #ebebeb;
                padding: 12.5px 0 12.5px 10px;
                margin: 15px;
                border-radius: 7px;
            }

            .ticketUnderline {
                border-bottom: 1px solid #ccc;
            }

            a:link {
                text-decoration: none;
            }

            a:visited {
                text-decoration: none;
            }

            a:hover {
                text-decoration: none;
            }

            a:active {
                text-decoration: none;
            }

            @media (max-width: 767px) {
                .textBreak {
                    padding-left: 22.5px;
                    display: block;
                }
            }

            .markCompletedBtn {
                background-color: #4caf50;
                border: none;
                border-radius: 7px;
                color: white;
                font-weight: 600;
                text-transform: uppercase;
                float: right;
                padding: 15px;
                margin-top: 50px;
                margin-bottom: 25px;
                margin-left: 40px;
            }

            .guardarbtn {
                text-transform: uppercase;
                float: right;
                border-radius: 7px;
                margin-top: 50px;
                margin-bottom: 25px;
                margin-right: 10px;
                margin-left: 10px;
            }

            .send-button {
                width: 150px;
                height: 50px;
                margin-left: 10px;
            }
        </style>
    </head>

    <body>

        <?php
        $pageTitle = "Detaille Ticket";
        include 'includes/menu.php';
        include 'includes/connection.php';
        include 'includes/upload_config.php';
        require_once 'includes/timezone_setting.php';

        $errorFound = false;

        // PHP code to retrieve ticket details from the database
        $ticketId = isset($_POST['ticket_id']) ? $_POST['ticket_id'] : null;

        // Query the database to get ticket details based on $ticketId
        $sql = "SELECT id_departamento, titulo, nombre, localizacion, prioridad, descripcion, categoria, estado, check_usuario, check_dept, fecha_creacion, fecha_actualizacion, oculto FROM tickets WHERE id_ticket = $ticketId";

        // Execute the SQL query
        $result = $conn->query($sql);

        // Display ticket details using PHP
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $departmentId = $row['id_departamento'];
            $title = $row['titulo'];
            $name = $row['nombre'];
            $location = $row['localizacion'];
            $priority = $row['prioridad'];
            $description = $row['descripcion'];
            $category = $row['categoria'];
            $status = $row['estado'];
            $checkLocation = $row['check_usuario'];
            $checkDept = $row['check_dept'];
            $ticketOpen = $row['fecha_creacion'];
            $lastUpdated = $row['fecha_actualizacion'];
            $hiddenTicket = $row['oculto'];
            $sql = "SELECT nombre_departamento FROM departamentos WHERE id_departamento = $departmentId";
            $result = $conn->query($sql);
            if ($result === false) {
                echo "Error: Ejecución de Query fallida. " . $conn->error;
            } else {
                // Check if the query returned any rows
                if ($result->num_rows > 0) {
                    // Fetch the department name
                    $row = $result->fetch_assoc();
                    $departmentName = $row['nombre_departamento'];
                } else {
                    // Assign a default department name
                    $departmentName = "Departamento Desconocido";
                }
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
            // Retrieve updated values from the form
            $newPriority = $_POST['priority'];
            $newCategory = $_POST['categories'];
            $newDepartmentId = $_POST['department'];
            $newStatus = $_POST['status'];

            // Update the corresponding fields in the database
            $sql = "UPDATE tickets SET prioridad = ?, categoria = ?, id_departamento = ?, estado = ? WHERE id_ticket = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $newPriority, $newCategory, $newDepartmentId, $newStatus, $ticketId);

            if ($stmt->execute()) {
                // Ticket updated successfully
                $lastUpdated = date("Y-m-d H:i:s"); // Update last updated timestamp
                $sql = "UPDATE tickets SET fecha_actualizacion = '$lastUpdated' WHERE id_ticket = $ticketId";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Ticket actualizado correctamente');</script>";
                    echo "<script>window.location.href = 'ticket_table.php';</script>";
                } else {
                    echo "<script>alert('Error al actualizar ticket: " . $conn->error . "');</script>";
                }
            } else {
                // Error occurred while updating ticket
                echo "Error: " . $stmt->error;
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }

            // Close the prepared statement
            $stmt->close();
        }
        ?>
        <div class="row">
            <div class="col-lg-6">
                <h1 class="ticketUnderline"><?php echo $title; ?></h1>
                <div class="ticketUnderline">
                    <div class="row">
                        <div class="col-md-2">
                            <p class="text-md text-sm"><span class="ticket">ID:</span> <span
                                    class="ticketText"><?php echo $ticketId; ?></span>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p><span class="ticket">Nombre:</span> <span class="ticketText"><?php echo $name; ?></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-md text-sm"><span class="ticket">Fecha Creación:</span> <span
                                    class="ticketText"><?php echo $ticketOpen; ?></span>
                            </p>
                        </div>

                    </div>
                    <div class="row ticketUnderline">
                        <div class="col-md-6 order-1 order-md-2">
                            <p class="text-md text-sm">
                                <span class="ticket">Última Modificación:</span>
                                <span class="ticketText textBreak" style=""><?php echo $lastUpdated; ?></span>
                            </p>
                        </div>
                        <div class="col-md-6 order-2 order-md-1">
                            <p>
                                <span class="ticket">Localización:</span>
                                <span class="ticketText textBreak"
                                    style="padding-left: 22.5px;"><?php echo $location; ?></span>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p><span class="ticket">Descripción:</span></p>
                            <div class="descText" contenteditable="false" class="form-control">
                                <?php echo $description; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Priority Selector, Category Selector, Department Selector, Status Selector, Save Button (if logged in) -->
                <?php if (isset($_SESSION['loggedin'])) { ?>
                    <h4 class="titles"
                        style="font-size:1.7em; text-align:center; text-transform:uppercase; margin-top:10px; border:none">
                        Editar
                        Información</h4>
                    <form method="post">
                        <div>
                            <div class="row">
                                <div class="col-md-3 ">
                                    <label for="priority">Priority:</label>
                                    <select name="priority" id="priority" class="formControl">
                                        <option value="Nuevo" <?php if ($priority == "Nuevo")
                                            echo "selected"; ?>>Nuevo</option>
                                        <option value="Urgente" <?php if ($priority == "Urgente")
                                            echo "selected"; ?>>Urgente</option>
                                        <option value="Alta" <?php if ($priority == "Alta")
                                            echo "selected"; ?>>Alta</option>
                                        <option value="Media" <?php if ($priority == "Media")
                                            echo "selected"; ?>>Media</option>
                                        <option value="Baja" <?php if ($priority == "Baja")
                                            echo "selected"; ?>>Baja</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="department">Dept:</label>
                                    <select name="department" id="department" class="formControl">
                                        <?php
                                        // Retrieve departments from the database and populate the dropdown menu
                                        $sql = "SELECT id_departamento, nombre_departamento FROM departamentos WHERE id_departamento !=0";
                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                // Check if the current department matches the one fetched from the database
                                                $selected = ($row['id_departamento'] == $departmentId) ? 'selected' : '';
                                                echo "<option value='" . $row['id_departamento'] . "' $selected>" . $row['nombre_departamento'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="categories">Categoría:</label>
                                    <select name="categories" id="categories" class="formControl">
                                    </select>
                                </div>
                                <script>
                                    // Function to populate the category select options based on the selected department
                                    document.getElementById('department').addEventListener('change', function () {
                                        var department = this.value;

                                        // Send AJAX request to fetch categories for the selected department
                                        var xhr = new XMLHttpRequest();
                                        xhr.onreadystatechange = function () {
                                            if (this.readyState == 4 && this.status == 200) {
                                                var categories = JSON.parse(this.responseText);

                                                // Clear existing options
                                                var categorySelect = document.getElementById('categories');
                                                categorySelect.innerHTML = '';

                                                // Add new options
                                                categories.forEach(function (category) {
                                                    var option = document.createElement('option');
                                                    option.value = category;
                                                    option.textContent = category;
                                                    categorySelect.appendChild(option);
                                                });

                                                // Set the selected category based on the value from the database
                                                var selectedCategory = '<?php echo $category; ?>';
                                                if (selectedCategory) {
                                                    var option = categorySelect.querySelector('option[value="' + selectedCategory + '"]');
                                                    if (option) {
                                                        option.selected = true;
                                                    }
                                                }
                                            }
                                        };
                                        xhr.open('GET', 'includes/category_listing.php?department=' + encodeURIComponent(department), true);
                                        xhr.send();
                                    });

                                    // Trigger the department change event to populate categories initially
                                    document.getElementById('department').dispatchEvent(new Event('change'));
                                </script>
                                <div class="col-md-3">
                                    <label for="status">Estado:</label>
                                    <select name="status" id="status" class="formControl">
                                        <option value="Abierto" <?php if ($status == "Abierto")
                                            echo "selected"; ?>>Abierto</option>
                                        <option value="En Progreso" <?php if ($status == "En Progreso")
                                            echo "selected"; ?>>En
                                            Progreso</option>
                                        <option value="En Espera" <?php if ($status == "En Espera")
                                            echo "selected"; ?>>En Espera</option>
                                        <option value="Cerrado" <?php if ($status == "Cerrado")
                                            echo "selected"; ?>>Cerrado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12 d-flex justify-content-center">
                                    <input type="hidden" name="ticket_id" value="<?php echo $ticketId; ?>">
                                    <input type="submit" name="save" value="Guardar" class="guardarbtn btn btn-success">
                                    <form method="post">
                                        <input type="hidden" name="ticket_id" value="<?php echo $ticketId; ?>">
                                        <input type="submit" name="close_ticket" value="Marcar como Resuelto"
                                            class="markCompletedBtn">
                                    </form>
                                </div> <!-- Button to close the ticket -->
                            </div>
                        </div>
                    </form>
                <?php } else { ?>
                    <!-- Non-editable fields for non-logged-in users -->
                    <div class="ticketDetails">
                        <div class="row">
                            <div class="col-md-3">
                                <p><span class="ticket">Prioridad:</span> <span
                                        class="ticketText"><?php echo $priority; ?></span>
                                </p>
                            </div>
                            <div class="col-md-5">
                                <p><span class="ticket">Departamento:</span> <span
                                        class="ticketText"><?php echo $departmentName; ?></span></p>
                            </div>
                            <div class="col-md-4">
                                <p><span class="ticket">Categoría:</span> <span
                                        class="ticketText"><?php echo $category; ?></span></p>
                            </div>
                            <div class="col-md-4">
                                <p><span class="ticket">Estado:</span> <span
                                        class="ticketText"><?php echo $status; ?></span></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['close_ticket'])) {
                    if (isset($_SESSION['loggedin'])) {
                        $checkDept = true;
                    } else {
                        $checkLocation = true;
                    }

                    // Update the database based on the boolean values
                    if ($checkLocation) {
                        $timeTicketSolved = date("Y-m-d H:i:s");
                        $sql = "UPDATE tickets SET check_usuario = '1', fecha_actualizacion = '$timeTicketSolved' WHERE id_ticket = $ticketId";
                        if ($conn->query($sql) === TRUE) {
                            // Insert a message into the mensajes table
                            $sql = "INSERT INTO mensajes (id_ticket, emisor, contenido, hora_publicacion) VALUES ($ticketId, 'Sistema', 'La localización ha marcado la incidencia como Resuelto', '$timeTicketSolved')";
                            $conn->query($sql);
                            echo "<script>alert('Ticket marcado como resuelto correctamente.');</script>";
                            echo "<script>window.location.href = 'ticket_table.php';</script>";
                        } else {
                            echo "<script>alert('Error al actualizar ticket: " . $conn->error . "');</script>";
                        }
                    }

                    if ($checkDept) {
                        $timeTicketSolved = date("Y-m-d H:i:s");
                        $sql = "UPDATE tickets SET check_dept = '1', fecha_actualizacion = '$timeTicketSolved' WHERE id_ticket = $ticketId";
                        if ($conn->query($sql) === TRUE) {
                            // Insert a message into the mensajes table
                            $sql = "INSERT INTO mensajes (id_ticket, emisor, contenido, hora_publicacion) VALUES ($ticketId, 'Sistema', 'El Departamento ha marcado la incidencia como Resuelto', '$timeTicketSolved')";
                            $conn->query($sql);
                            echo "<script>alert('Ticket marcado como resuelto correctamente.');</script>";
                            echo "<script>window.location.href = 'ticket_table.php';</script>";
                        } else {
                            echo "<script>alert('Error al actualizar ticket: " . $conn->error . "');</script>";
                        }
                    }
                }

                if ($checkDept && $checkLocation) {
                    $timeTicketSolved = date("Y-m-d H:i:s");
                    $sql = "UPDATE tickets SET estado ='Cerrado', oculto = '1', fecha_actualizacion = '$timeTicketSolved' WHERE id_ticket = $ticketId";
                    $conn->query($sql);
                    if ($conn->query($sql) === TRUE) {
                        $sql = "INSERT INTO mensajes (id_ticket, emisor, contenido, hora_publicacion) VALUES ($ticketId, 'Sistema', 'El Ticket está cerrado', '$timeTicketSolved')";
                        $conn->query($sql);
                    } else {
                        echo "<script>alert('Error al actualizar ticket: " . $conn->error . "');</script>";
                    }
                }
                ?>

            </div>
            <div class="col-lg-6">

                <?php
                // PHP code to retrieve attachments for the ticket from the database
                // Query the database to get attachments related to $ticketId
                $sql = "SELECT id_archivo, id_ticket, nombre_archivo FROM archivos WHERE id_ticket = $ticketId";
                $result = $conn->query($sql);
                $attachments = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $attachments[] = $row;
                    }
                }

                if (!empty($attachments)) {
                    echo "<h5 class=''>Archivos Adjuntos</h5>";
                    echo "<ul>";
                    foreach ($attachments as $attachment) {
                        echo "<li><a href='" . $targetDir . $attachment['nombre_archivo'] . "' target='_blank'>" . $attachment['nombre_archivo'] . "</a></li>";
                    }
                    echo "</ul>";
                }
                ?>

                <h5 class="">Mensajes</h5>

                <?php
                // PHP code to retrieve comments for the ticket from the database
                // Query the database to get comments related to $ticketId
                $sql = "SELECT id_mensaje, emisor, hora_publicacion, contenido FROM mensajes WHERE id_ticket = $ticketId";
                $result = $conn->query($sql);

                // Check if there are comments retrieved
                if ($result->num_rows > 0) {
                    // Initialize an empty array to store comments
                    $comments = array();

                    // Loop through the result set and fetch comments
                    while ($row = $result->fetch_assoc()) {
                        // Add each comment to the $comments array$_SESSION['Department_ID']
                        $comments[] = $row;
                    }

                    // Display the comments using PHP
                    foreach ($comments as $comment) {
                        echo "<div>";
                        echo "<div><span style='text-transform: uppercase; margin-left:10px;'>Escrito por: </span><span style='font-weight: bold;'>" . $comment['emisor'] . ", " . $comment['hora_publicacion'] . "</span></div>";
                        echo "<p class='comment'>" . $comment['contenido'] . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p class='px-3'>No se han encontrado mensajes.</p>";
                }
                ?>
                <div class="col-md-12">
                    <h4>Escribir Comentario Nuevo</h4>
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3 col-md-4">
                            <input type="file" id="attachment" name="attachment[]" accept=".pdf, .png, .jpg, .jpeg"
                                class="form-control">
                        </div>
                        <div class="mb-3">
                            <textarea name="new_comment" maxlength="255" class="form-control" required></textarea>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-2">
                                <input type="hidden" name="ticket_id" value="<?php echo $ticketId; ?>">
                                <input type="submit" name="submit_comment" value="Enviar"
                                    class="send-button btn btn-primary">
                            </div>
                        </div>
                </div>

                </form>
                <?php
                // PHP code to handle posting new comments and file uploads
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_comment'])) {
                    // Retrieve new comment from the form
                    $newComment = $_POST['new_comment'];
                    $timeComment = date("Y-m-d H:i:s");

                    // Determine the sender based on user session or store's name
                    if (isset($_SESSION['loggedin'])) {
                        // If the user is logged in, set $newSender to the department name stored in the session
                        $newDepartmentId = $_SESSION['department_id'];
                        $sql = "SELECT nombre_departamento FROM departamentos WHERE id_departamento = $newDepartmentId";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $newSender = $row['nombre_departamento'];
                        } else {
                            // Default department name in case it can't find any
                            $newSender = $newDepartmentId;
                        }
                    } else {
                        // If the user is not logged in, set $newSender to the store's name from numTienda.php
                        $newSender = $nombre;
                    }

                    // Save new comment to the database using prepared statements
                    $stmt = $conn->prepare("INSERT INTO mensajes (id_ticket, emisor, contenido, hora_publicacion) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("isss", $ticketId, $newSender, $newComment, $timeComment);

                    if ($stmt->execute()) {
                        $commentId = $conn->insert_id; // Get the ID of the inserted comment
                        // Define the target directory
                        //$targetDir = realpath(__DIR__ . $targetDir) . DIRECTORY_SEPARATOR;
                
                        // Ensure the target directory exists; if not, create it
                        if (!file_exists($targetDir) && !mkdir($targetDir, 0777, true)) {
                            die('Failed to create target directory: ' . $targetDir);
                        }
                        // Handle file uploads
                        foreach ($_FILES['attachment']['name'] as $key => $fileName) {
                            if ($_FILES['attachment']['size'][$key] > 0) {
                                // Check file type
                                $fileType = pathinfo($_FILES['attachment']['name'][$key], PATHINFO_EXTENSION);
                                if (!in_array(strtolower($fileType), $fileTypeRestrictions)) {
                                    echo "<script>alert('" . $errorMessages['invalidFileType'] . "');</script>";
                                    $errorFound = true;
                                    break;
                                }

                                // Rename file
                                $newFileName = "ticket" . $ticketId . "_mensaje" . $commentId . "." . $fileType;
                                $targetFilePath = $targetDir . $newFileName;

                                // Check file size
                                if ($_FILES['attachment']['size'][$key] > $maxFileSizeMB * 1024 * 1024) {
                                    echo "<script>alert('" . $errorMessages['fileSizeExceedLimit'] . "');</script>";
                                    $errorFound = true;
                                    break;
                                }
                                echo "Target File Path: " . $targetFilePath . "<br>";
                                if (move_uploaded_file($_FILES['attachment']['tmp_name'][$key], $targetFilePath)) {
                                    // Insert attachment details into database
                                    $sql = "INSERT INTO archivos (id_ticket, nombre_archivo) VALUES ('$ticketId', '$newFileName')";
                                    $conn->query($sql);
                                } else {
                                    echo "<script>alert('Error al subir archivo.');</script>";
                                    $errorFound = true;
                                    break;
                                }
                            }
                        }
                        if (!$errorFound) {
                            echo "<script>alert('Mensaje enviado correctamente.');</script>";
                            echo "<script>window.location.href = 'ticket_table.php';</script>";
                        } else {
                            // Delete the message if there's any error with files
                            $sql = "DELETE FROM mensajes WHERE id_mensaje = $commentId";
                            $conn->query($sql);
                            echo "<script>alert('Error al enviar mensaje.');</script>";
                            echo "<script>window.location.href = 'ticket_table.php';</script>";
                        }
                    } else {
                        // Error occurred while inserting comment
                        echo "Error: " . $stmt->error;
                    }

                    // Close the prepared statement
                    $stmt->close();
                }
                ?>
            </div>
        </div>
    </body>

</html>