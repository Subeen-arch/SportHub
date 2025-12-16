<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if ($select_user->rowCount() > 0) {
      $message[] = 'email already exists!';
   } else {
      if ($pass != $cpass) {
         $message[] = 'confirm password not matched!';
      } else {
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $cpass]);
         $message[] = 'registered successfully, login now please!';
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="form-container">

      <form action="" method="post">
         <h3 class="form_title">register now</h3>

         <input type="text" name="name" required placeholder="Enter your username" maxlength="20" class="box">

         <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="box"
            oninput="this.value = this.value.replace(/\s/g, '')">

         <!-- PHONE NUMBER FIELD ADDED -->
         <div class="phone-container" style="display:flex; gap:10px; width:100%;">
            <select id="country" class="box" style="width:30%;">
               <option value="977">+977</option>
               <option value="91">+91</option>
            </select>

            <input type="text" id="phone" placeholder="Phone number" maxlength="10"
               class="box" style="width:70%;"
               oninput="this.value = this.value.replace(/[^0-9]/g, '')">
         </div>

         <p id="phone-error" style="color:red; font-size:14px;"></p>
         <!-- END PHONE -->

         <input type="password" id="pass" name="pass" required placeholder="Enter your password" maxlength="20"
            class="box" oninput="this.value = this.value.replace(/\s/g, '')">

         <input type="password" id="cpass" name="cpass" required placeholder="Confirm your password" maxlength="20"
            class="box" oninput="this.value = this.value.replace(/\s/g, '')">

         <p id="pass-error" style="color:red; font-size:14px;"></p>

         <input type="submit" value="register now" class="btn" name="submit">
         <p>Already have an account? <span><a href="user_login.php" class="login_text">Login now</a></span></p>
      </form>

   </section>

   <script src="js/script.js"></script>
<script>
   const form = document.querySelector("form");

   // inputs
   const emailInput = document.querySelector("input[name='email']");
   const pass = document.getElementById("pass");
   const cpass = document.getElementById("cpass");
   const errorMsg = document.getElementById("pass-error");
   const phone = document.getElementById("phone");
   const country = document.getElementById("country");
   const phoneError = document.getElementById("phone-error");

   form.addEventListener("submit", (e) => {

      /* --------------------- EMAIL VALIDATION --------------------- */
      const emailVal = emailInput.value.trim();
      const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[A-Za-z]{2,}$/;

      if (!emailRegex.test(emailVal)) {
         e.preventDefault();
         alert("Please enter a valid email like: example@gmail.com");
         return;
      }

      /* --------------------- PHONE VALIDATION --------------------- */
      const num = phone.value.trim();
      const code = country.value;

      if (num.length !== 10) {
         e.preventDefault();
         phoneError.textContent = "Phone number must be exactly 10 digits!";
         return;
      } else {
         phoneError.textContent = "";
      }

      /* --------------------- PASSWORD VALIDATION --------------------- */
      const password = pass.value;
      const confirm = cpass.value;

      if (password.length < 8) {
         e.preventDefault();
         errorMsg.textContent = "Password must be at least 8 characters!";
         return;
      }

      if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
         e.preventDefault();
         errorMsg.textContent = "Password must include at least one special character!";
         return;
      }

      if (password !== confirm) {
         e.preventDefault();
         errorMsg.textContent = "Passwords do not match!";
         return;
      }

      errorMsg.textContent = "";
   });
</script>


</body>

</html>
