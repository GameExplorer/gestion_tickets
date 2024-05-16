<?php
// Initialize variables
session_start();
$title = '';
$name = '';
$description = '';
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $department = isset($_POST['department']) ? $_POST['department'] : '';
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $name = isset($_POST['sender']) ? $_POST['sender'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : '';
} elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Check if URL parameters are present and populate the form fields
    $department = isset($_GET['department']) ? htmlspecialchars($_GET['department']) : '';
    $title = isset($_GET['title']) ? htmlspecialchars($_GET['title']) : '';
    $name = isset($_GET['sender']) ? htmlspecialchars($_GET['sender']) : '';
    $description = isset($_GET['description']) ? htmlspecialchars($_GET['description']) : '';
    $category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '';
}
?>
<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DETALLES TICKET</title>
        <link rel="stylesheet" href="css/ticket_form_style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/ticket_form_script.js"></script>
    </head>

    <body>
        <?php
        $pageTitle = "CREAR TICKET";
        include 'includes/menu.php';
        require_once 'includes/timezone_setting.php';
        ?>
        <div class="container">
            <form action="includes/submit_ticket.php" method="post" enctype="multipart/form-data" class="incident-form">
                <!-- title -->
                <div class="row mb-3">
                    <label for="title" class="col-md-3 col-form-label">Título:</label>
                    <div class="col-md-9">
                        <input type="text" id="title" name="title" maxlength="32" placeholder="Introducir título"
                            value="<?php echo htmlspecialchars($title); ?>" required class="form-control">
                    </div>
                </div>
                <!-- Sender and Location -->
                <div class="row mb-3">
                    <label for="sender" class="col-md-3 col-form-label">Emisor:</label>
                    <div class="col-md-5">
                        <input type="text" id="sender" name="sender" maxlength="32" placeholder="Introducir nombre"
                            value="<?php echo htmlspecialchars($name); ?>" required class="form-control">
                    </div>
                    <div class="col-md-4">
                        <div class="text-md-right mt-2">Localización:&nbsp;&nbsp;
                            <?php echo $nombre; ?>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="row mb-3">
                    <label for="description" class="col-md-3 col-form-label">Descripción:</label>
                    <div class="col-md-9">
                        <textarea id="description" name="description" maxlength="500"
                            placeholder="Incluye una descripción" required
                            class="form-control"><?php echo htmlspecialchars($description); ?></textarea>
                    </div>
                </div>

                <!-- Department and Category -->
                <div class="row mb-3">
                    <label for="department" class="col-md-3 col-form-label">Departamento:</label>
                    <div class="col-md-3">
                        <select id="department" name="department" onchange="populateCategories()"
                            class="form-control"></select>
                    </div>
                    <label for="category" class="col-md-3 col-form-label">Categoría:</label>
                    <div class="col-md-3">
                        <select id="category" name="category" class="form-control"></select>
                    </div>

                    <script>
                        // Function to populate the category select options based on the selected department
                        function populateCategories() {
                            var department = document.getElementById('department').value;

                            // Send AJAX request to fetch categories for the selected department
                            var xhr = new XMLHttpRequest();
                            xhr.onreadystatechange = function () {
                                if (this.readyState == 4 && this.status == 200) {
                                    var categories = JSON.parse(this.responseText);

                                    // Clear existing options
                                    var categorySelect = document.getElementById('category');
                                    categorySelect.innerHTML = '';

                                    // Add new options
                                    categories.forEach(function (category) {
                                        var option = document.createElement('option');
                                        option.value = category;
                                        option.textContent = category;
                                        categorySelect.appendChild(option);
                                    });
                                }
                            };
                            xhr.open('GET', 'includes/category_listing.php?department=' + encodeURIComponent(department), true);
                            xhr.send();
                        }
                    </script>
                </div>

                <!-- Attachment -->
                <div class="row mb-3">
                    <label for="attachment" class="col-md-3 col-form-label">Adjuntos:</label>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-12 col-sm-8 order-sm-1 order-1">
                                <div id="attachment-container">
                                    <?php if (isset($_POST['attachment']) && is_array($_POST['attachment'])): ?>
                                        <?php foreach ($_POST['attachment'] as $attachment): ?>
                                            <?php
                                            $attachmentPath = 'adjuntos/' . basename($attachment);
                                            ?>
                                            <a href="<?php echo $attachmentPath; ?>"
                                                target="_blank"><?php echo basename($attachment); ?></a><br>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <label class="btn btn-success mb-4"
                                        style="background-color: #4caf50; color: white; border:none;">
                                        <i class="fa fa-image"></i> <span id="file-label">Escoge un archivo</span>
                                        <input type="file" id="attachment" name="attachment[]"
                                            accept=".pdf, .png, .jpg, .jpeg" class="form-control" style="display: none;"
                                            onchange="updateFileName(this)">
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-3 order-sm-2 order-2">
                                <button id="addAttachmentButton" class="btn btn-primary"
                                    style="background-color: #4caf50; color: white; border: none; cursor: pointer; width: auto; height:auto; margin-left: 10px;"
                                    onclick="addAttachment(event)">Añadir otro</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function addAttachment(event) {
                        // Prevent the default form submission behavior
                        event.preventDefault();

                        var container = document.getElementById('attachment-container');

                        var newLabel = document.createElement('label');
                        newLabel.className = 'btn btn-success attachment-label';
                        newLabel.style = 'background-color: #4caf50; color: white; border:none; margin-top: 15px;';

                        var icon = document.createElement('i');
                        icon.className = 'fa fa-image';
                        newLabel.appendChild(icon);

                        var newText = document.createTextNode('Escoge un archivo');
                        var span = document.createElement('span');
                        span.id = 'file-label';
                        span.appendChild(newText);
                        newLabel.appendChild(span);

                        var newInput = document.createElement('input');
                        newInput.type = 'file';
                        newInput.name = 'attachment[]';
                        newInput.accept = '.pdf, .png, .jpg, .jpeg';
                        newInput.className = 'form-control attachment-input';
                        newInput.style = 'display: none;';
                        newInput.addEventListener('change', function () {
                            updateFileName(this);
                        });

                        newLabel.appendChild(newInput);

                        container.appendChild(newLabel);
                    }
                </script>

                <!-- Submit and Back Button -->
                <div class="row mb-3">
                    <div class="col-md-6 text-md-end">
                        <input type="submit" value="Guardar" class="btn btn-primary">
                    </div>
                    <div class="col-md-6">
                        <input type="button" value="Volver" id="backButton" onclick="goToTicketTable()"
                            class="btn btn-secondary">
                    </div>
                </div>
            </form>
        </div>
    </body>

</html>
<script>
    function updateFileName(input) {
        var fileName = "Escoge un archivo";
        if (input.files && input.files.length > 0) {
            fileName = input.files[0].name;
        }
        // Find the parent label element of the input
        var parentLabel = input.parentNode;
        // Find the span element within the label
        var spanElement = parentLabel.querySelector('span');
        // Update the text content of the span element
        if (spanElement) {
            spanElement.textContent = fileName;
        }
    }
</script>