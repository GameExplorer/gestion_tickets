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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <style>
            .tab {
                overflow: hidden;
                border: 1px solid #ccc;
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                padding: 10px;
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
                margin: 5px 10px;
                padding: 12.5px;
                border: none;
                border-radius: 7px;
                outline: none;
                cursor: pointer;
            }

            .tabcontent {
                display: none;
                padding: 6px 12px;
                border: 1px solid #ccc;
                border-top: none;
            }

            .modal-content {
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
            }

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

            //document.getElementById("defaultOpen").click();

            function openUserModal() {
                $('#addUserModal').modal('show');
                $.ajax({
                    url: 'includes/department_listing.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.length > 0) {
                            var options = '';
                            response.forEach(function (department) {
                                options += '<option value="' + department.id_departamento + '">' + department.nombre_departamento + '</option>';
                            });
                            $('#departmentSelectUser').html(options);
                        } else {
                            $('#departmentSelectUser').html('<option value="">No departments found</option>');
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('AJAX error: ' + status + ' - ' + error);
                    }
                });
            }

            $(document).ready(function () {
                $('#addUserForm').on('submit', function (event) {
                    event.preventDefault();
                    var username = $('#username').val();
                    var password = $('#password').val();
                    var departmentId = $('#departmentSelectUser').val();

                    $.ajax({
                        url: 'includes/add_user.php',
                        type: 'POST',
                        data: { username: username, password: password, departmentSelectUser: departmentId },
                        success: function (response) {
                            if (response.trim() == 'success') {
                                alert('User added successfully');
                                $('#addUserModal').modal('hide');
                                location.reload(); // Refresh the page to show the new user
                            } else {
                                alert('Failed to add user: ' + response);
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('AJAX error: ' + status + ' - ' + error);
                        }
                    });
                });
            });

            function openCategoryModal() {
                $('#categoryModal').modal('show');
                $.ajax({
                    url: 'includes/department_listing.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (response.length > 0) {
                            var options = '';
                            response.forEach(function (department) {
                                options += '<option value="' + department.id_departamento + '">' + department.nombre_departamento + '</option>';
                            });
                            $('#departmentSelect').html(options);
                        } else {
                            $('#departmentSelect').html('<option value="">No departments found</option>');
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('AJAX error: ' + status + ' - ' + error + '\nResponse: ' + xhr.responseText);
                        console.error('AJAX error details:', {
                            status: status,
                            error: error,
                            responseText: xhr.responseText
                        });
                    }
                });
            }
            $(document).ready(function () {
                $('#addCategoryForm').on('submit', function (event) {
                    event.preventDefault();
                    var categoryName = $('#categoryName').val();
                    var departmentId = $('#departmentSelect').val();

                    $.ajax({
                        url: 'includes/add_category.php',
                        type: 'POST',
                        data: { departmentSelect: departmentId, categoryName: categoryName },
                        success: function (response) {
                            if (response.trim() == 'success') {
                                alert('Category added successfully');
                                $('#categoryModal').modal('hide');
                                location.reload(); // Refresh the page to show the new category
                            } else {
                                alert('Failed to add category: ' + response);
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('AJAX error: ' + status + ' - ' + error);
                        }
                    });
                });
            });

            $(document).ready(function () {
                $('#addDeptForm').on('submit', function (event) {
                    event.preventDefault();
                    var deptName = $('#deptName').val();
                    $.ajax({
                        url: 'includes/add_department.php',
                        type: 'POST',
                        data: { deptName: deptName },
                        success: function (response) {
                            if (response.trim() == 'success') {
                                alert('Department added successfully');
                                $('#addDeptModal').modal('hide');
                                location.reload(); // Refresh the page to show the new department
                            } else {
                                alert('Failed to add department: ' + response);
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('AJAX error: ' + status + ' - ' + error);
                        }
                    });
                });
            });
            function openDepartmentModal() {
                // Show the modal for adding a new department
                $('#addDeptModal').modal('show');
            }

            $(document).ready(function () {
                // Handle the Edit button click for departments
                $('.viewBtn').on('click', function () {
                    var deptId = $(this).closest('tr').attr('id').split('-')[1];
                    var deptName = $(this).closest('tr').find('td').eq(1).text();

                    $('#editDeptId').val(deptId);
                    $('#editDeptName').val(deptName);

                    $('#editDeptModal').modal('show');
                });

                // Handle the Edit Department form submission
                $('#editDeptForm').on('submit', function (event) {
                    event.preventDefault();
                    var deptId = $('#editDeptId').val();
                    var deptName = $('#editDeptName').val();

                    $.ajax({
                        url: 'includes/edit_department.php',
                        type: 'POST',
                        data: { deptId: deptId, deptName: deptName },
                        success: function (response) {
                            if (response.trim() == 'success') {
                                alert('Department updated successfully');
                                $('#editDeptModal').modal('hide');
                                location.reload(); // Refresh the page to show the updated department
                            } else {
                                alert('Failed to update department: ' + response);
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('AJAX error: ' + status + ' - ' + error);
                        }
                    });
                });
            });

            $(document).ready(function () {
                // Handle the Edit button click for categories
                $('.editCatBtn').on('click', function () {
                    var catId = $(this).closest('tr').attr('id').split('-')[1];
                    var catName = $(this).closest('tr').find('td').eq(2).text();

                    $('#editCatId').val(catId);
                    $('#editCatName').val(catName);

                    // Clear previous dropdown options
                    $('#editCatDeptSelect').empty();

                    // Populate department dropdown using AJAX
                    $.ajax({
                        url: 'includes/department_listing.php',
                        type: 'GET',
                        dataType: 'json',
                        success: function (response) {
                            if (response && response.length > 0) {
                                var options = '';
                                response.forEach(function (department) {
                                    options += '<option value="' + department.id_departamento + '">' + department.nombre_departamento + '</option>';
                                });
                                $('#editCatDeptSelect').html(options);
                                // Select the department of the category
                                //$('#editCatDeptSelect').val($('#category-' + catId).find('td').eq(1).text());
                                var catDeptId = $('#category-' + catId).data('department-id');
                                $('#editCatDeptSelect').val(catDeptId);

                            } else {
                                $('#editCatDeptSelect').html('<option value="">No departments found</option>');
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('AJAX error: ' + status + ' - ' + error);
                            console.error('AJAX error details:', {
                                status: status,
                                error: error,
                                responseText: xhr.responseText
                            });
                        }
                    });

                    $('#editCatModal').modal('show');
                });
            });

            $(document).ready(function () {
                $('#editCatForm').on('submit', function (event) {
                    event.preventDefault();
                    var catId = $('#editCatId').val();
                    var catName = $('#editCatName').val();
                    var catDeptId = $('#editCatDeptSelect').val();

                    $.ajax({
                        url: 'includes/edit_category.php',
                        type: 'POST',
                        data: { catId: catId, catName: catName, deptId: catDeptId },
                        success: function (response) {
                            if (response.trim() == 'success') {
                                alert('Category updated successfully');
                                $('#editCatModal').modal('hide');
                                location.reload(); // Refresh the page to show the updated category
                            } else {
                                alert('Failed to update category: ' + response);
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('AJAX error: ' + status + ' - ' + error);
                        }
                    });
                });
            });

            function openEditUserModal(userId) {
                $.ajax({
                    url: 'includes/get_user_details.php',
                    type: 'GET',
                    data: { id: userId },
                    dataType: 'json',
                    success: function (response) {
                        if (response) {
                            $('#editUserId').val(response.id_usuario);
                            $('#editUserName').val(response.nombre_usuario);
                            $('#editUserDept').val(response.id_departamento);

                            // Populate department dropdown
                            $.ajax({
                                url: 'includes/department_listing.php',
                                type: 'GET',
                                dataType: 'json',
                                success: function (deptResponse) {
                                    if (deptResponse.length > 0) {
                                        var options = '';
                                        deptResponse.forEach(function (department) {
                                            options += '<option value="' + department.id_departamento + '">' + department.nombre_departamento + '</option>';
                                        });
                                        $('#editUserDept').html(options);
                                        $('#editUserDept').val(response.id_departamento);
                                    } else {
                                        $('#editUserDept').html('<option value="">No departments found</option>');
                                    }
                                },
                                error: function (xhr, status, error) {
                                    alert('AJAX error: ' + status + ' - ' + error);
                                }
                            });

                            $('#editUserModal').modal('show');
                        } else {
                            alert('Failed to fetch user details');
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('AJAX error: ' + status + ' - ' + error);
                    }
                });
            }

            $(document).ready(function () {
                $('#editUserForm').on('submit', function (event) {
                    event.preventDefault();
                    var userId = $('#editUserId').val();
                    var userName = $('#editUserName').val();
                    var userDeptId = $('#editUserDept').val();
                    var newPassword = $('#newPassword').val();

                    $.ajax({
                        url: 'includes/edit_user.php',
                        type: 'POST',
                        data: { userId: userId, userName: userName, userDeptId: userDeptId, newPassword: newPassword },
                        success: function (response) {
                            if (response.trim().includes('success')) {
                                alert('User updated successfully');
                                $('#editUserModal').modal('hide');
                                location.reload();
                            } else {
                                alert('Failed to update user: ' + response);
                            }
                        },
                        error: function (xhr, status, error) {
                            alert('AJAX error: ' + status + ' - ' + error);
                        }
                    });
                });
            });
        </script>
    </head>

    <body>
        <div class="tab">
            <button class="tablinks btn btn-primary" onclick="openTab(event, 'Users')" id="defaultOpen">Users</button>
            <button class="tablinks btn btn-primary" onclick="openTab(event, 'Departments')">Departments</button>
            <button class="btn btn-primary" onclick="openTab(event, 'Categories')">Categories</button>
            <button class="newUsers btn btn-primary" onclick="openUserModal()">Add User</button>
            <button class="newDept btn btn-primary" onclick="openDepartmentModal()">Add Department</button>
            <button class="newCategory btn btn-primary" onclick="openCategoryModal()">Add Category</button>
        </div>

        <!-- ADD MODAL WINDOWS -->
        <div id="addUserModal" class="modal fade" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addUserForm">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" minlength="6"
                                    maxlength="32" required>
                            </div>
                            <div class="mb-3">
                                <label for="departmentSelectUser" class="form-label">Department</label>
                                <select class="form-select" id="departmentSelectUser" name="departmentSelectUser"
                                    required>
                                    <option value="">Select a department</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Add User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="addDeptModal" class="modal fade" tabindex="-1" aria-labelledby="addDeptModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDeptModalLabel">Add Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addDeptForm">
                            <div class="mb-3">
                                <label for="deptName" class="form-label">Department Name</label>
                                <input type="text" class="form-control" id="deptName" name="deptName" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Department</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="categoryModalLabel">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addCategoryForm">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="categoryName" name="categoryName" required>
                            </div>
                            <div class="mb-3">
                                <label for="departmentSelect" class="form-label">Department</label>
                                <select class="form-select" id="departmentSelect" name="departmentSelect" required>
                                    <option value="">Select a department</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Category</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- EDIT MODAL WINDOWS -->
        <div id="editDeptModal" class="modal fade" tabindex="-1" aria-labelledby="editDeptModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDeptModalLabel">Edit Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editDeptForm">
                            <div class="mb-3">
                                <label for="editDeptName" class="form-label">Department Name</label>
                                <input type="text" class="form-control" id="editDeptName" name="editDeptName" required>
                                <input type="hidden" id="editDeptId" name="editDeptId">
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="editCatModal" class="modal fade" tabindex="-1" aria-labelledby="editCatModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCatModalLabel">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editCatForm">
                            <div class="mb-3">
                                <label for="editCatName" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="editCatName" name="editCatName" required>
                                <input type="hidden" id="editCatId" name="editCatId">
                            </div>
                            <div class="mb-3">
                                <label for="editCatDept" class="form-label">Department</label>
                                <input type="hidden" id="editCatDept" name="editCatDept">
                                <select class="form-select" id="editCatDeptSelect" name="editCatDeptSelect" required>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="editUserModal" class="modal fade" tabindex="-1" aria-labelledby="editUserModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editUserForm">
                            <div class="mb-3">
                                <label for="editUserName" class="form-label">User Name</label>
                                <input type="text" class="form-control" id="editUserName" name="editUserName" required>
                                <input type="hidden" id="editUserId" name="editUserId">
                            </div>
                            <div class="mb-3">
                                <label for="editUserDept" class="form-label">Department</label>
                                <select class="form-select" id="editUserDept" name="editUserDept" required>
                                    <!-- Department options will be populated dynamically -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="newPassword" name="newPassword"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
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
                    echo "<button type='button' class='viewUserBtn' style='color: white; font-weight: 600; background-color:#4caf50;' onclick='openEditUserModal(" . $row["id_usuario"] . ")'>EDIT</button>";
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
                    echo "<button type='button' class='editDeptBtn' style='color: white; font-weight: 600; background-color:#4caf50;' onclick='openEditDeptModal(" . $row["id_departamento"] . ")'>EDIT</button>";
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
                        echo "<td id='category-1' data-department-id='2'>" . $row["nombre_departamento"] . "</td>";
                        echo "<td>" . $row["nombre_categoria"] . "</td>";
                        echo "<td class='button-group'>";
                        echo "<button type='button' class='editCatBtn' style='color: white; font-weight: 600; background-color:#4caf50;' onclick='openEditCatModal(" . $row["id_categoria"] . ")'>EDIT</button>";
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

        </div>
    </body>

</html>