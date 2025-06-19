<?php
session_start();
include('server.php'); // Ensures database connection is included

// ✅ Check if database connection exists
if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login_user'])) {
        $email = mysqli_real_escape_string($db, $_POST['username']);
        $password = mysqli_real_escape_string($db, $_POST['password']);

        if (!empty($email) && !empty($password)) {
            $stmt = $db->prepare("SELECT id, password FROM users WHERE email=?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $hashed_password);
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    $_SESSION['email'] = $email;
                    header("location: dashboard.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Invalid email or password.";
                }
            } else {
                $_SESSION['error'] = "No user found with this email.";
            }
            $stmt->close();
        } else {
            $_SESSION['error'] = "Both email and password are required.";
        }
    }

    if (isset($_POST['send_otp'])) {
        $email = mysqli_real_escape_string($db, $_POST['email']);

        if (!empty($email)) {
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_email'] = $email;

            // Send OTP via Email (Replace with actual mail setup)
            mail($email, "Your OTP Code", "Your OTP is: $otp");

            $_SESSION['success'] = "OTP sent to $email";
        } else {
            $_SESSION['error'] = "Email is required for OTP verification.";
        }
    }

    if (isset($_POST['verify_otp'])) {
        $entered_otp = $_POST['otp'];
        if ($entered_otp == $_SESSION['otp']) {
            $_SESSION['email'] = $_SESSION['otp_email'];
            header("location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid OTP. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | THE CGS</title>
    <style>
        body {
            background: url('https://files.oaiusercontent.com/file-4T1XYvbe4jnQtcTkLqhqgR?se=2025-03-23T16%3A33%3A57Z&sp=r&sv=2024-08-04&sr=b&rscc=max-age%3D604800%2C%20immutable%2C%20private&rscd=attachment%3B%20filename%3D6c99c4fc-9670-4768-b729-bfc13179666b.webp&sig=QhmhrTip6B8/pbOlHrBUJjSzn8Z8CsRAcJXPCRfrfVU%3D') no-repeat center center/cover fixed;
            font font-family: 'Poppins', sans-serif;
            /*background: #f4f4f4;*/
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
           
        }

        .login-box {
            font font-family: 'Poppins', sans-serif;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .highlight {
            color: #003366;
            font-weight: bold;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .input-group input {
            width: 96%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn {
            width: 100%;
            background:rgb(85, 127, 210);
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background: #003366;
        }

        .signup-text {
            margin-top: 15px;
            font-size: 14px;
        }

        .signup-text a {
            color: #FF7F00;
            text-decoration: none;
            font-weight: bold;
        }

        .signup-text a:hover {
            text-decoration: underline;
        }

        .otp-box {
            display: none;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        .success {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h2>Login to <span class="highlight">THE WAY</span></h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form method="post" action="login.php">
            <div class="input-group">
                <label>Email</label>
                <input type="text" name="username" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btn" name="login_user">Sign In</button>

            <div class="or">OR</div>

            <div class="input-group">
                <label>Enter Email for OTP</label>
                <input type="text" name="email">
                <button type="submit" class="btn" name="send_otp">Send OTP</button>
            </div>

            <div class="otp-box" id="otpBox">
                <label>Enter OTP</label>
                <input type="text" name="otp">
                <button type="submit" class="btn" name="verify_otp">Submit OTP</button>
            </div>

            <p class="signup-text">
                New to THE WAY? <a href="signup.php">Create an Account</a>
            </p>
        </form>
    </div>
</div>

</body>
</html>
