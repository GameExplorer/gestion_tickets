<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/connection.php';
$pageTitle = "Control Panel";
include 'includes/menu.php';
// Retrieve user's location using functions from numTienda.php
require_once 'includes/numTienda.php';
require_once 'includes/timezone_setting.php';
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Control Panel</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <style>
            .tab {
                overflow: hidden;
                border: 1px solid #ccc;
                background-color: #f1f1f1;
                text-align: center;
            }

            button {
                margin: 5px 20px;
                padding: 15px;
                float: left;
                border: none;
                border-radius: 7px;
                outline: none;
                cursor: pointer;
            }

            .tab button {
                font-size: 1.2em;
            }

            .tabcontent {
                display: none;
                padding: 6px 12px;
                border: 1px solid #ccc;
                border-top: none;
            }

            /*Modal box*/
            /* The Modal (background) */
            .modal {
                display: none;
                /* Hidden by default */
                position: fixed;
                /* Stay in place */
                z-index: 1;
                /* Sit on top */
                left: 0;
                top: 0;
                width: 100%;
                /* Full width */
                height: 100%;
                /* Full height */
                overflow: auto;
                /* Enable scroll if needed */
                background-color: rgb(0, 0, 0);
                /* Fallback color */
                background-color: rgba(0, 0, 0, 0.4);
                /* Black w/ opacity */
            }

            /* Modal Content/Box */
            .modal-content {
                background-color: #fefefe;
                margin: 15% auto;
                /* 15% from the top and centered */
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
                /* Could be more or less, depending on screen size */
            }

            /* The Close Button */
            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }

            /* Table style */
            table {
                margin: 0 auto;
                width: 50%;
                text-align: center;
                border-collapse: collapse;
            }

            thead {
                display: table-header-group;
            }

            th {
                font-size: 1em;
                text-align: center;
                border-bottom: 1px solid #ccc;
            }

            tr {
                text-align: center;
            }
        </style>
        <script>
            function openTab(evt, tabName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");

                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }

                document.getElementById(tabName).style.display = "block";
                evt.currentTarget.className += " active";
            }

            /* function disableUser(userId) {
                 $.ajax({
                     url: 'includes/disable_user.php',
                     type: 'POST',
                     data: { id: userId },
                     success: function (response) {
                         if (response.trim() == 'success') {
                             document.getElementById('user-' + userId).style.backgroundColor = "#D3D3D3";
                             alert("User has been disabled");
                         } else {
                             alert('Failed to disable user: ' + response);
                         }
                     },
                     error: function (xhr, status, error) {
                         alert('AJAX error: ' + status + ' - ' + error);
                     }
                 });
             }
 
             function disableDept(deptId) {
                 $.ajax({
                     url: 'includes/disable_dept.php',
                     type: 'POST',
                     data: { id: deptId },
                     success: function (response) {
                         if (response.trim() == 'success') {
                             document.getElementById('department-' + deptId).style.backgroundColor = "#D3D3D3";
                             alert("Department has been disabled");
                         } else {
                             alert('Failed to disable department: ' + response);
                         }
                     },
                     error: function (xhr, status, error) {
                         alert('AJAX error: ' + status + ' - ' + error);
                     }
                 });
             }
 
             function disableCat(catId) {
                 $.ajax({
                     url: 'includes/disable_cat.php',
                     type: 'POST',
                     data: { id: catId },
                     success: function (response) {
                         if (response.trim() == 'success') {
                             document.getElementById('category-' + catId).style.backgroundColor = "#D3D3D3";
                             alert("Category has been disabled");
                         } else {
                             alert('Failed to disable category: ' + response);
                         }
                     },
                     error: function (xhr, status, error) {
                         alert('AJAX error: ' + status + ' - ' + error);
                     }
                 });
             }*/


            function toggleUser(userId, action) {
                $.ajax({
                    url: 'includes/disable_user.php',
                    type: 'POST',
                    data: { id: userId, action: action },
                    success: function (response) {
                        if (response.trim() == 'success') {
                            var button = document.getElementById('disable-btn-' + userId);
                            var row = document.getElementById('user-' + userId);
                            if (action == 'disable') {
                                row.style.backgroundColor = "#D3D3D3";
                                button.textContent = "RE-ENABLE";
                                button.style.backgroundColor = "#4caf50";
                                button.onclick = function () { toggleUser(userId, 'reenable'); };
                                alert("User has been disabled");
                            } else {
                                row.style.backgroundColor = "";
                                button.textContent = "DISABLE";
                                button.style.backgroundColor = "red";
                                button.onclick = function () { toggleUser(userId, 'disable'); };
                                alert("User has been re-enabled");
                            }
                        } else {
                            alert('Failed to toggle user: ' + response);
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('AJAX error: ' + status + ' - ' + error);
                    }
                });
            }

            function toggleDept(deptId, action) {
                $.ajax({
                    url: 'includes/disable_dept.php',
                    type: 'POST',
                    data: { id: deptId, action: action },
                    success: function (response) {
                        if (response.trim() == 'success') {
                            var button = document.getElementById('disable-btn-' + deptId);
                            var row = document.getElementById('department-' + deptId);
                            if (action == 'disable') {
                                row.style.backgroundColor = "#D3D3D3";
                                button.textContent = "RE-ENABLE";
                                button.style.backgroundColor = "#4caf50";
                                button.onclick = function () { toggleUser(deptId, 'reenable'); };
                                alert("User has been disabled");
                            } else {
                                row.style.backgroundColor = "";
                                button.textContent = "DISABLE";
                                button.style.backgroundColor = "red";
                                button.onclick = function () { toggleUser(deptId, 'disable'); };
                                alert("User has been re-enabled");
                            }
                        } else {
                            alert('Failed to toggle user: ' + response);
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('AJAX error: ' + status + ' - ' + error);
                    }
                });
            }




            function toggleCat(catId, action) {
                $.ajax({
                    url: 'includes/disable_cat.php',
                    type: 'POST',
                    data: { id: catId, action: action },
                    success: function (response) {
                        if (response.trim() == 'success') {
                            var button = document.getElementById('disable-btn-' + catId);
                            var row = document.getElementById('category-' + catId);
                            if (action == 'disable') {
                                row.style.backgroundColor = "#D3D3D3";
                                button.textContent = "RE-ENABLE";
                                button.style.backgroundColor = "#4caf50";
                                button.onclick = function () { toggleCat(catId, 'reenable'); };
                                alert("Category has been disabled");
                            } else {
                                row.style.backgroundColor = "";
                                button.textContent = "DISABLE";
                                button.style.backgroundColor = "red";
                                button.onclick = function () { toggleCat(catId, 'disable'); };
                                alert("Category has been re-enabled");
                            }
                        } else {
                            alert('Failed to toggle category: ' + response);
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('AJAX error: ' + status + ' - ' + error);
                    }
                });
            }


            document.getElementById("defaultOpen").click();
        </script>
    </head>

    <body>
        <div class="tab">
            <button class="tablinks btn btn-primary" onclick="openTab(event, 'Users')" id="defaultOpen">Users</button>
            <button class="tablinks btn btn-primary" onclick="openTab(event, 'Departments')">Departments</button>
            <button class="btn btn-primary" onclick="openTab(event, 'Categories')">Categories</button>
            <button class="newUsers btn btn-primary">Add User</button>
            <button class="newDept btn btn-primary">Add Department</button>
            <button class="newCategory btn btn-primary">Add Category</button>
        </div>

        <div id="Users" class="tabcontent">
            <?php
            $sql_users = "SELECT usuarios.id_usuario, usuarios.nombre_usuario, usuarios.id_departamento, departamentos.nombre_departamento, usuarios.disabled FROM usuarios
                      INNER JOIN departamentos ON usuarios.id_departamento = departamentos.id_departamento
                      ";
            $result_users = $conn->query($sql_users);

            if ($result_users->num_rows > 0) {
                echo "<table>";
                echo "<thead>";
                echo "<tr><th>ID Usuario</th><th>Nombre Usuario</th><th>Nombre Departamento</th><th>Actions</th></tr>";
                echo "</thead>";
                while ($row = $result_users->fetch_assoc()) {
                    $disabled = isset($row["disabled"]) ? $row["disabled"] : 0;
                    $buttonText = $disabled ? "RE-ENABLE" : "DISABLE";
                    $buttonColor = $disabled ? "#4caf50" : "red";
                    $rowColor = $disabled ? "#D3D3D3" : "";

                    echo "<tr id='user-" . $row["id_usuario"] . "' style='background-color: $rowColor;'>";
                    echo "<td>" . $row["id_usuario"] . "</td>";
                    echo "<td>" . $row["nombre_usuario"] . "</td>";
                    echo "<td>" . $row["nombre_departamento"] . "</td>";
                    echo "<td class='button-group'>";
                    echo '<input type="hidden" name="users_id" value="' . $row["id_usuario"] . '">';
                    echo "<button type='button' class='viewBtn' style='color: white; font-weight: 600; background-color:#4caf50;' id='viewUser'>EDIT</button>";
                    if ($row["id_departamento"] != 0) {
                        echo "<button type='button' id='disable-btn-" . $row["id_usuario"] . "' onclick='toggleUser(" . $row["id_usuario"] . ", \"" . ($disabled ? "reenable" : "disable") . "\")' style='color: white; font-weight: 600; background-color: $buttonColor;'>$buttonText</button>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No users found.";
            }
            ?>
            <div id="myModal" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p>Some text in the Modal..</p>
                </div>
            </div>
        </div>

        <div id="Departments" class="tabcontent">
            <?php
            $sql_dept = "SELECT departamentos.id_departamento, departamentos.nombre_departamento, departamentos.disabled FROM departamentos";
            $result_dept = $conn->query($sql_dept);

            if ($result_dept->num_rows > 0) {
                echo "<table>";
                echo "<thead>";
                echo "<tr><th>ID Departamento</th><th>Nombre Departamento</th><th>Actions</th></tr>";
                echo "</thead>";
                while ($row = $result_dept->fetch_assoc()) {
                    $disabled = $row["disabled"] ? "reenable" : "disable";
                    $buttonText = $row["disabled"] ? "RE-ENABLE" : "DISABLE";
                    $buttonColor = $row["disabled"] ? "#4caf50" : "red";
                    $rowColor = $row["disabled"] ? "#D3D3D3" : "";

                    echo "<tr id='department-" . $row["id_departamento"] . "' style='background-color: $rowColor;'>";
                    echo "<td>" . $row["id_departamento"] . "</td>";
                    echo "<td>" . $row["nombre_departamento"] . "</td>";

                    echo "<td class='button-group'>";
                    echo "<button type='button' class='viewBtn' style='color: white; font-weight: 600; background-color:#4caf50;'>EDIT</button>";
                    if ($row["id_departamento"] != 0) {
                        echo "<button type='button' id='disable-btn-" . $row["id_departamento"] . "'onclick='toggleDept(" . $row["id_departamento"] . ", \"$disabled\")' style='color: white; font-weight: 600; background-color:$buttonColor;'>$buttonText</button>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No departments found.";
            }
            ?>
            <div id="myModal" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p>Some text in the Modal..</p>
                </div>
            </div>
        </div>

        <div id="Categories" class="tabcontent">
            <?php
            $sql_cat = "SELECT categorias.id_categoria, departamentos.nombre_departamento, categorias.nombre_categoria, categorias.disabled FROM categorias
            INNER JOIN departamentos ON categorias.id_departamento = departamentos.id_departamento";
            $result_cat = $conn->query($sql_cat);

            if ($result_cat->num_rows > 0) {
                echo "<table>";
                echo "<thead>";
                echo "<tr><th>ID Category</th><th>Nombre Departamento</th><th>Nombre Categoria</th><th>Actions</th></tr>";
                echo "</thead>";
                while ($row = $result_cat->fetch_assoc()) {
                    $disabled = $row["disabled"] ? "reenable" : "disable";
                    $buttonText = $row["disabled"] ? "RE-ENABLE" : "DISABLE";
                    $buttonColor = $row["disabled"] ? "#4caf50" : "red";
                    $rowColor = $row["disabled"] ? "#D3D3D3" : "";

                    echo "<tr id='category-" . $row["id_categoria"] . "' style='background-color: $rowColor;'>";

                    if ($row["nombre_categoria"] != "Sin categoría") {
                        echo "<td>" . $row["id_categoria"] . "</td>";
                        echo "<td>" . $row["nombre_departamento"] . "</td>";
                        echo "<td>" . $row["nombre_categoria"] . "</td>";
                        echo "<td class='button-group'>";
                        echo "<button type='button' id='viewCat' style='color: white; font-weight: 600; background-color:#4caf50;'>EDIT</button>";
                        if ($row["id_categoria"] != 0) {
                            echo "<button type='button' id='disable-btn-" . $row["id_categoria"] . "'onclick='toggleCat(" . $row["id_categoria"] . ", \"$disabled\")' style='color: white; font-weight: 600; background-color:$buttonColor;'>$buttonText</button>";
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No departments found.";
            }
            ?>
            <div id="myModal" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p>Some text in the Modal..</p>
                </div>
            </div>
        </div>
    </body>
    <script>
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the button that opens the modal
        var btn = document.getElementById("viewUser");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on the button, open the modal
        btn.onclick = function () {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

</html>