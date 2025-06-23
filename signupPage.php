<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up - Study Hive</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
    }

    .navbar {
      background-color: #660066;
      position: sticky;
      top: 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 10px;
      height: 60px;
    }

    .navbar .logo {
      height: 60px;
    }

    .navbar ul {
      list-style: none;
      display: flex;
      margin: 0;
      padding: 0;
    }

    .navbar li {
      margin-left: 10px;
    }

    .navbar a {
      text-decoration: none;
      color: white;
      padding: 14px 16px;
      display: block;
      font-size: 14px;
      font-weight: bold;
      text-transform: uppercase;
    }

    .navbar a:hover {
      background-color: #990099;
    }

    .topic {
      background-color: #ec97ec;
      font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
      font-size: 230%;
      color: #5e1b5e;
      text-align: center;
      height: 400px;
      padding-top: 5px;
    }

    .topic img {
      width: 245px;
      height: auto;
      margin-bottom: 5px;
      margin-top: 5px;
    }

    .back-btn {
      display: inline-block;
      margin-top: 20px;
      margin-left: 20px;
      font-size: 16px;
      color: #660066;
      text-decoration: none;
      font-weight: bold;
    }

    .container {
      display: flex;
      justify-content: center;
      padding: 50px;
    }

    .signup-box {
      background-color: #ffe0ff;
      padding: 40px;
      border-radius: 10px;
      width: 400px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #4b004b;
      margin-bottom: 30px;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="file"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .gender-options {
      margin-top: 5px;
    }

    .gender-options label {
      font-weight: normal;
      margin-right: 15px;
    }

    .form-actions {
      text-align: center;
      margin-top: 25px;
    }

    input[type="submit"],
    input[type="reset"] {
      background-color: #cc66cc;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      margin: 5px;
    }

    input[type="submit"]:hover,
    input[type="reset"]:hover {
      background-color: #b94cb9;
    }

    .login-text {
      margin-top: 15px;
      font-size: 14px;
      color: #333;
    }

    .login-text a {
      color: #660066;
      font-weight: bold;
      text-decoration: none;
    }

    .login-text a:hover {
      text-decoration: underline;
    }

    .footer {
      background-color: #660066;
      color: white;
      text-align: center;
      padding: 10px;
      margin-top: 40px;
    }

    .match-status {
      font-size: 13px;
      margin-top: 5px;
      font-weight: bold;
    }

    .match-status.good {
      color: green;
    }

    .match-status.bad {
      color: red;
    }
  </style>
</head>
<body>

<!-- header -->
<div class="navbar">
  <img src="images/whiteLogo.png" alt="Logo" class="logo">
  <ul>
    <li><a href="loginPage.php">Login</a></li>
    <li><a href="signupPage.php">Sign Up</a></li>
  </ul>
</div>

<div class="topic">
  <img src="images/whiteLogo.png" alt="logo" class="logo">
  <h2>WELCOME TO STUDY HIVE</h2>
</div>

<a class="back-btn" href="index.php">← Back</a>

<!-- register form -->
<div class="container">
  <form action="user.php" method="post" enctype="multipart/form-data" class="signup-box" id="signupForm">
    <h2>Create Your Account</h2>

    <label for="user_Fname">Full Name:</label>
    <input type="text" name="user_Fname" id="user_Fname" placeholder="Enter your full name" required>

    <label for="email">Email Address:</label>
    <input type="email" name="email" id="email" placeholder="Enter your email address" required>

    <label for="phone">Phone Number:</label>
    <input type="text" name="phone" id="phone" placeholder="e.g. 0123456789 or 441234567890" required>

    <label>Gender:</label>
    <div class="gender-options">
      <label><input type="radio" name="gender" value="female" required> Female</label>
      <label><input type="radio" name="gender" value="male"> Male</label>
    </div>

    <label for="user_Name">Username:</label>
    <input type="text" name="user_Name" id="user_Name" placeholder="Enter your username" required>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" placeholder="Enter your password" required
           minlength="8" pattern=".{8,}" title="Password must be at least 8 characters long">

    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" placeholder="Re-enter your password" required>
    <div id="matchStatus" class="match-status"></div>

    <label for="profile_picture">Profile Picture:</label>
    <input type="file" name="profile_picture" id="profile_picture" required>

    <div class="form-actions">
      <input type="reset" value="CLEAR FORM">
      <input type="submit" name="submit" value="REGISTER">

      <p class="login-text">
        Already have an account? <a href="loginPage.php">Login</a>.
      </p>
    </div>
  </form>
</div>

<!-- footer -->
<?php include("footer.php"); ?>

<script>
  // Password strength check
  document.getElementById('password').addEventListener('input', function () {
    this.setCustomValidity(this.value.length < 8 ? 'Password must be at least 8 characters long.' : '');
  });

  // Phone number validation
  const phoneInput = document.getElementById('phone');
  phoneInput.addEventListener('input', function () {
    const phone = this.value.trim();
    if (!/^\d+$/.test(phone)) {
      this.setCustomValidity('Phone number must contain digits only.');
    } else if (phone.length < 10 || phone.length > 15) {
      this.setCustomValidity('Phone number must be between 10 to 15 digits.');
    } else {
      this.setCustomValidity('');
    }
  });

  // Confirm password live check
  const password = document.getElementById('password');
  const confirm = document.getElementById('confirm_password');
  const matchStatus = document.getElementById('matchStatus');

  function checkMatch() {
    if (confirm.value === "") {
      matchStatus.textContent = "";
      matchStatus.className = "match-status";
    } else if (confirm.value === password.value) {
      matchStatus.textContent = "✅ Password match";
      matchStatus.className = "match-status good";
    } else {
      matchStatus.textContent = "❌ Password do not match";
      matchStatus.className = "match-status bad";
    }
  }

  confirm.addEventListener('input', checkMatch);
  password.addEventListener('input', checkMatch);

  // Final form check before submit
  document.getElementById('signupForm').addEventListener('submit', function (e) {
    const phone = phoneInput.value.trim();
    if (!/^\d+$/.test(phone)) {
      e.preventDefault();
      alert("❌ Phone number must contain digits only.");
      phoneInput.focus();
    } else if (phone.length < 10 || phone.length > 15) {
      e.preventDefault();
      alert("❌ Phone number must be between 10 to 15 digits.");
      phoneInput.focus();
    } else if (password.value !== confirm.value) {
      e.preventDefault();
      alert("❌ Password and Confirm Password must match.");
      confirm.focus();
    }
  });
</script>

</body>
</html>
