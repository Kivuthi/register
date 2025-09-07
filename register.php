<?php
include "db.php";
session_start();

$nameErr = $emailErr = $passErr = "";
$name = $email = $password = "";
$success = "";

// sign up

if (isset($_POST["signup"])) {
    $_SERVER["REQUEST_METHOD"] == "POST";{
        $name = htmlspecialchars($_POST["username"]);
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm = $_POST["confirm"];
    }
    
    // validation 
    if (empty($name)) $nameErr = "Name is Required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $emailErr = "Invalid email";
    if (strlen($password) < 6) $passErr = "Password must be at least 6 characters";

    if ($nameErr == "" && $emailErr == "" && $passErr == "") {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO register(name, email, password) VALUES('$name', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            $success = "Registration successful";
        } else {
            $success = "Error: ". $conn->error;
        }
    }
}


// loggin 
if (isset($_POST["login"])) {
    $name = htmlspecialchars($_POST["username"]);
    $password = $_POST["password"];

    $sql = "SELECT FROM register WHERE email = '$email' LIMIT 1";
    $result = $conn->query($ql);

    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            $_SESSION["username"] = $user["name"];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Wrong password";
        }

    } else {
        $error = "No user email found";
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>KipMall Sliding Split-Screen</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container" id="container">
    <!-- Left: Image + Text -->
    <div class="panel image-panel">
      <h1>Welcome to KipMall ✨<br>Discover Luxury Fashion & Lifestyle</h1>
    </div>

    <!-- Right: Form -->
    <div class="panel form-panel">
      <!-- Login -->
      <form class="form active" id="loginForm">
        <h2>Login</h2>
        <input type="text" placeholder="username" name="username">
        <input type="password" placeholder="Password" name="password">
        <button type="submit" name="login">Login</button>
      </form>

      <!-- Signup -->
      <form class="form" id="signupForm">
        <h2>Sign Up</h2>
        <input type="text" placeholder="Name" name="name">
        <input type="email" placeholder="Email" name="email">
        <input type="password" placeholder="Password" name="password">
        <input type="password" placeholder="Confirm" name="confirm">
        <button type="submit" name="signup">Register</button>
      </form>

      <div class="toggle" onclick="toggleForms()">Switch to Sign Up</div>
    </div>
  </div>

  <script>
    const container = document.getElementById('container');
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');
    const toggleBtn = document.querySelector('.toggle');
    let isLogin = true;

    function toggleForms() {
      if (isLogin) {
        loginForm.classList.remove('active');
        signupForm.classList.add('active');
        container.classList.add('signup-mode');
        toggleBtn.textContent = "Switch to Login";
      } else {
        signupForm.classList.remove('active');
        loginForm.classList.add('active');
        container.classList.remove('signup-mode');
        toggleBtn.textContent = "Switch to Sign Up";
      }
      isLogin = !isLogin;
    }
  </script>
</body>
</html>
