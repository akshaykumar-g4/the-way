<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - THE WAY</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        body {
            font-family:  'Times New Roman', Times, serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('https://files.oaiusercontent.com/file-VPQScy5CqbZZXE7wvLwkXa?se=2025-03-20T07%3A04%3A57Z&sp=r&sv=2024-08-04&sr=b&rscc=max-age%3D604800%2C%20immutable%2C%20private&rscd=attachment%3B%20filename%3Dc086ec90-3e52-479a-b66c-38b323c13756.webp&sig=gCCIHC1966Fhf0JWCGVusLzYPk7CK1sncY9eHdiQFpA%3D') no-repeat center center/cover fixed;

            /*background: linear-gradient(135deg, #ffd8a8, #ffb066);*/
            ;
            ;
            ;
            margin: 0;
        }
        .signup-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }
        .signup-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.3s;
        }
        .input-field:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 8px rgba(102, 126, 234, 0.5);
        }
        .signup-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(45deg, #024693, #0167b1);
            border: none;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 8px;
            margin-top: 15px;
            transition: 0.3s;
        }
        .signup-btn:hover {
            background: #ff7200;
        }
        .success-btn {
            width: 100%;
            padding: 12px;
            background: #28a745;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 8px;
            margin-top: 10px;
            display: none;
        }
        .success-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="signup-container">
    <h2>Sign Up - <b>THE CGS</b></h2>
    <form id="signupForm" action="server.php" method="POST">
    <input type="hidden" name="reg_user" value="1">
    <input type="text" name="fullname" class="input-field" placeholder="Full Name" required>
    <input type="email" name="email" class="input-field" placeholder="Email ID" required>
    <input type="password" name="password_1" class="input-field" placeholder="Password" required>
    <input type="password" name="password_2" class="input-field" placeholder="Confirm Password" required>
    <button type="submit" class="signup-btn">Sign Up</button>
</form>


    </div>
    
    <script>
        function showSuccess() {
            document.querySelector('.signup-btn').style.display = 'none';
            document.getElementById('successButton').style.display = 'block';
        }
        function redirectToNextPage() {
            window.location.href = "login.php";
        }
    </script>
</body>
</html>
