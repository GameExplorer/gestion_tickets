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
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>DETALLES TICKET</title>
        <link rel="stylesheet" href="css/ticket_form_style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/ticket_form_script.js"></script>
    </head>

    <body>
        <div class="page-wrapper">

            <?php
            $pageTitle = "CREAR TICKET";
            include 'includes/menu.php';
            ?>
            <form action="includes/submit_ticket.php" method="post" enctype="multipart/form-data" class="incident-form">
                <div class="form-row">
                    <label for="title">Título:</label>
                    <input type="text" id="title" name="title" maxlength="32" placeholder="Introducir título"
                        value="<?php echo htmlspecialchars($title); ?>" required>
                </div>

                <div class="form-row">
                    <label for="sender">Emisor:</label>
                    <input type="text" id="sender" name="sender" maxlength="32" placeholder="Introducir nombre"
                        value="<?php echo htmlspecialchars($name); ?>" required>
                    <div for="location" class="textRight">Localización:</div>
                    <div><?php echo $nombre; ?></div>
                </div>

                <div class="form-row">
                    <label for="description">Descripción:</label>
                    <textarea id="description" name="description" maxlength="500" placeholder="Incluye una descripción"
                        required><?php echo htmlspecialchars($description); ?></textarea>
                </div>

                <div class="form-row">
                    <label for="department">Departamento:</label>
                    <select id="department" name="department" onchange="populateCategories()"></select>

                    <div for="category" class="textRight">Categoría:</div>
                    <select id="category" name="category"></select>
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

                <div class="form-row">
                    <label for="attachment">Adjuntos:</label>
                    <div id="attachment-container">
                        <input type="file" id="attachment" name="attachment[]" accept=".pdf, .png, .jpg, .jpeg">
                    </div>
                    <button type="button" onclick="addAttachment()">Añadir otro</button>
                </div>

                <script>
                    function addAttachment() {
                        var container = document.getElementById('attachment-container');
                        var newInput = document.createElement('input');
                        newInput.type = 'file';
                        newInput.name = 'attachment[]';
                        newInput.accept = '.pdf, .png, .jpg, .jpeg';
                        container.appendChild(newInput);
                        container.appendChild(document.createElement('br'));
                    }
                </script>

                <div class="form-row">
                    <input type="submit" value="Guardar">
                    <input type="button" value="Volver" id="backButton" onclick="goToTicketTable()">
                </div>
            </form>
        </div>
    </body>

</html>
<script>
    function goToTicketTable() {
        window.location.href = 'ticket_table.php';
    }
</script>