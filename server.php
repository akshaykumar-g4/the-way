<?php
//session_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Connect to the database
$db = new mysqli('localhost', 'root', '', 'career_guidance');

// Check the connection
if ($db->connect_error) {
    die("Database connection failed: " . $db->connect_error);
}

// REGISTER USER
if (isset($_POST['reg_user'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password_1 = trim($_POST['password_1']);
    $password_2 = trim($_POST['password_2']);

    $errors = array();

    // Validate inputs
    if (empty($fullname)) { array_push($errors, "Full Name is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password_1)) { array_push($errors, "Password is required"); }
    if ($password_1 !== $password_2) { array_push($errors, "Passwords do not match"); }

    // Check if user already exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        array_push($errors, "Email already exists");
    }
    $stmt->close();

    // Register user if no errors
    if (count($errors) == 0) {
        $hashed_password = password_hash($password_1, PASSWORD_BCRYPT);

        $stmt = $db->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $fullname, $email, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['email'] = $email;
            $_SESSION['success'] = "Registration successful!";
            header('location: login.php'); // Redirect to welcome page
            exit();
        } else {
            array_push($errors, "Error in registration: " . $stmt->error);
        }
        $stmt->close();
    }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $errors = array();

    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password)) { array_push($errors, "Password is required"); }

    if (count($errors) == 0) {
        $stmt = $db->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['email'] = $email;
                $_SESSION['success'] = "You are now logged in";
                header('location: index.php'); // Redirect to home page
                exit();
            } else {
                array_push($errors, "Wrong email/password combination");
            }
        } else {
            array_push($errors, "No user found with this email");
        }
        $stmt->close();
    }
}

?>


