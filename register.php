<?php
include "db.php";

$nameErr = $emailErr = $passErr = "";
$name = $email = $password = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Name validation
    if (empty($_POST["name"])) {
        $nameErr = "Name is required ❌";
    } else {
        $name = htmlspecialchars($_POST["name"]);
    }

    // Email validation
    if (empty($_POST["email"])) {
        $emailErr = "Email is required ❌";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format ❌";
    } else {
        $email = $_POST["email"];
    }

    // Password validation
    if (empty($_POST["password"])) {
        $passErr = "Password is required ❌";
    } elseif (strlen($_POST["password"]) < 6) {
        $passErr = "Password must be at least 6 characters 🔑";
    } else {
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // hash password
    }

    // If no errors → save to DB
    if ($nameErr == "" && $emailErr == "" && $passErr == "") {
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            $success = "Registration Successful 🎉";
        } else {
            $success = "Error: " . $conn->error;
        }
    }
}
// loggin 
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // find user in DB
    $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // verify password
        if (password_verify($password, $user["password"])) {
            $_SESSION["username"] = $user["name"]; // session created

            // if user clicked "remember me" → create cookie
            if (isset($_POST["remember"])) {
                setcookie("username", $user["name"], time() + 3600, "/"); // 1 hour
            }

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "❌ Wrong password!";
        }
    } else {
        $error = "❌ No user found with that email.";
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
        <input type="email" placeholder="Email" name="email">
        <input type="password" placeholder="Password" name="password">
        <button>Login</button>
      </form>

      <!-- Signup -->
      <form class="form" id="signupForm">
        <h2>Sign Up</h2>
        <input type="text" placeholder="Name" name="name">
        <input type="email" placeholder="Email" name="email">
        <input type="password" placeholder="Password" name="password">
        <button>Register</button>
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
