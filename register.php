<?php
session_start ();
include "db.php";

// log out handle
if (isset($_GET["logout"])) {
    session_destroy();
    setcookie("rememberUser", "", time() - 3600, "/");
    header("Location: login.php");
    exit();
}

// login handle
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $POST["password"];

    $hashedPass = password_hash($password, PASSWORD_DEFAULT);

    // db handle
    $sql = "INSERT INTO register ('username', 'email', 'password' VALUES ('$username', '$email', '$password')";

    if ($conn -> querry($sql) === TRUE) {
        header("Location: login.php");
    } else {
        echo "error" . $conn->error;
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
    <form class="form" id="signupForm">
      <h2>Sign Up</h2>
      <input type="text" placeholder="Name" name="username">
      <input type="email" placeholder="Email" username="email">
      <input type="password" placeholder="Password" name="password">
      <button>Register</button>
    </form>

    <form class="form active" id="loginForm" method="POST">
      <h2>Login</h2>
      <input type="username" placeholder="username" name="username">
      <input type="password" placeholder="Password" name="password">
      <button>Login</button>
    </form>


    <div class="toggle" onclick="toggleForms()">Switch to Sign Up</div>
  </div>

  <script>
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');
    const toggleBtn = document.querySelector('.toggle');
    let isLogin = true;

    function toggleForms() {
      if (isLogin) {
        loginForm.classList.remove('active');
        signupForm.classList.add('active');
        toggleBtn.textContent = "Switch to Login";
      } else {
        signupForm.classList.remove('active');
        loginForm.classList.add('active');
        toggleBtn.textContent = "Switch to Sign Up";
      }
      isLogin = !isLogin;
    }
  </script>
</body>
</html>