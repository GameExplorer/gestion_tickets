<?php
session_start();

include 'connection.php';
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
                <h1>ACCESO</h1>
                <label for="username">Username:</label>
                <input id="username" name="username" minlength="4" maxLength="32" type="text" class="inputStyle"
                    placeholder="Enter a username" required />

                <label for="password">Password:</label>
                <input id="password" name="password" minlength="6" maxlength="16" required type="password"
                    class="inputStyle" placeholder="Enter a password" />
                <input type="checkbox" class="show-password"
                    onchange="document.getElementById('password').type = this.checked ? 'text' : 'password'"> Show
                password<br>
                <?php


                if (isset($_POST['login'])) {
                    // Get the form data
                    $username = $_POST['username'];
                    $password = $_POST['password'];

                    // Prepare SQL statement
                    $stmt = $conn->prepare("SELECT User_ID, Pass, Department_ID FROM Users WHERE Username = ?");// this
                    $stmt->bind_param("s", $username);

                    // Execute the statement
                    $stmt->execute();
                    $stmt->store_result();

                    // Checks if user exists
                    if ($stmt->num_rows > 0) {
                        $stmt->bind_result($id, $hashed_password, $loggedDepartmentId); // BIND Department_ID
                
                        // Fetch the result
                        $stmt->fetch();

                        // Verify the password
                        if ($password === $hashed_password) {
                            $_SESSION['loggedin'] = true;
                            $_SESSION['id'] = $id;
                            $_SESSION['username'] = $username;
                            $_SESSION['department_id'] = $loggedDepartmentId; // STORE Department_ID 
                
                            header("Location: ticket_table.php");
                            exit;
                        } else {
                            echo "<div class='errorMessage'>There was an error signing in. Incorrect Password</div>";
                        }
                    } else {
                        echo "<div class='errorMessage'>User doesn't exist. Not found</div>";
                    }

                    $stmt->close();
                    $conn->close();
                }
                ?>


                <input name="login" type="submit" value="Acceso" class="login" />
            </form>
        </div>


    </body>

</html>

