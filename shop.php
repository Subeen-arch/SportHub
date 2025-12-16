<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

  <section class="products">

   <h1 class="heading">latest products</h1>

   <div class="products-grid">

      <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if ($select_products->rowCount() > 0) {
         while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
          <form action="" method="post" class="productCard-new">

             <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
             <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
             <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
             <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">

             <button class="productCard-new-wishlist" type="submit" name="add_to_wishlist">
                <i class="fas fa-heart"></i>
             </button>

             <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="productCard-new-view">
                <i class="fas fa-eye"></i>
             </a>

             <div class="productCard-new-imageBox">
                <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
             </div>

             <div class="productCard-new-content">
                <h3 class="productCard-new-title"><?= $fetch_product['name']; ?></h3>

                <p class="productCard-new-desc">
                   <?= substr($fetch_product['details'], 0, 70) ?>...
                </p>

                <div class="productCard-new-price">
                   NRP. <?= $fetch_product['price']; ?>
                </div>

                <div class="productCard-new-bottom">
                   <input type="number" name="qty" min="1" max="99" class="productCard-new-qty" value="1">
                   <input type="submit" value="Add to Cart" name="add_to_cart" class="productCard-new-btn">
                </div>
             </div>

          </form>
            <?php
         }
      } else {
         echo '<p class="empty">no products found!</p>';
      }
      ?>

   </div>

</section>

   <script src="js/script.js"></script>

</body>

</html>