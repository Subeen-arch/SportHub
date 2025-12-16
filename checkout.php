<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
}

if (isset($_POST['order'])) {

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);

   $address = 'flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . 
              $_POST['state'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);

   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if ($check_cart->rowCount() > 0) {

      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price)
                                      VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'order placed successfully!';
   } else {
      $message[] = 'your cart is empty';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="checkout-orders">

      <form action="" method="POST">

         <h3>Your Orders</h3>

         <div class="display-orders">
            <?php
            $grand_total = 0;
            $cart_items = [];

            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);

            if ($select_cart->rowCount() > 0) {
               while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                  $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ') - ';
                  $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
               }
               $total_products = implode($cart_items);

               foreach ($cart_items as $item) {
                  echo "<p>$item</p>";
               }
            } else {
               echo '<p class="empty">your cart is empty!</p>';
            }
            ?>

            <input type="hidden" name="total_products" value="<?= $total_products; ?>">
            <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
            <div class="grand-total">grand total : <span>Rs <?= $grand_total; ?>/-</span></div>
         </div>

         <h3>Place Your Order</h3>

         <div class="flex">

            <div class="inputBox">
               <span>Your Name :</span>
               <input type="text" name="name" placeholder="enter your name" class="box" maxlength="20" required>
            </div>

            <div class="inputBox">
               <span>Your Number :</span>
               <input type="number" name="number" placeholder="enter your number" class="box" 
                      onkeypress="if(this.value.length == 10) return false;" required>
            </div>

            <div class="inputBox">
               <span>Your Email :</span>
               <input type="email" name="email" placeholder="enter your email" class="box" maxlength="50" required>
            </div>

            <div class="inputBox">
               <span>Payment Method :</span>
               <select name="method" class="box" required>
                  <option value="cash on delivery">Cash on Delivery</option>
                  <option value="paytm">Esewa</option>
                  <option value="paypal">Khalti</option>
               </select>
            </div>

            <div class="inputBox">
               <span>Address Line 1 :</span>
               <input type="text" name="flat" placeholder="e.g. flat number" class="box" required>
            </div>

            <div class="inputBox">
               <span>Address Line 2 :</span>
               <input type="text" name="street" placeholder="e.g. street name" class="box" required>
            </div>

            <div class="inputBox">
               <span>City :</span>
               <input type="text" name="city" placeholder="e.g. Kathmandu" class="box" required>
            </div>

            <div class="inputBox">
               <span>State :</span>
               <input type="text" name="state" placeholder="e.g. Bagmati" class="box" required>
            </div>

            <div class="inputBox">
               <span>Country :</span>
               <input type="text" name="country" placeholder="e.g. Nepal" class="box" required>
            </div>

            <div class="inputBox">
               <span>Pin Code :</span>
               <input type="number" name="pin_code"
                      placeholder="e.g. 123456" 
                      onkeypress="if(this.value.length == 6) return false;" 
                      class="box" required>
            </div>

         </div>

         <input type="submit" name="order" 
                class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" 
                value="Place Order">

      </form>

      <!-- PAYMENT MODAL -->
      <div id="payment-modal" class="payment-modal hidden">

         <div class="payment-overlay" onclick="closePaymentModal()"></div>

         <div class="payment-container">
            <div class="payment-header">
               <img id="pay-logo" src="" class="pay-logo">
               <h2 id="pay-title">Payment</h2>
            </div>

            <div class="payment-body">

               <p>Amount to Pay:</p>
               <h3>Rs <?= $grand_total; ?></h3>

               <label>Mobile Number 
                  <span style="color:#777">(Demo: 9840318943)</span>
               </label>
               <input type="text" id="payment-number" class="pay-input" maxlength="10" required>

               <label>Payment PIN 
                  <span style="color:#777">(Demo: 1234)</span>
               </label>
               <input type="password" id="payment-pin" class="pay-input" maxlength="4" required>

               <button type="button" class="confirm-btn" onclick="confirmPayment()">Confirm Payment</button>
               <button type="button" class="cancel-btn" onclick="closePaymentModal()">Cancel</button>

            </div>

         </div>
      </div>

   </section>

   <script src="js/script.js"></script>

</body>

</html>
