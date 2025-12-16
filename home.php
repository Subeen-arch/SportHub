<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <div class="home-bg">

      <section class="home">

         <div class="swiper home-slider">

            <div class="swiper-wrapper">

               <div class="swiper-slide slide">
                  <div class="image">
                     <img src="images/home-img-1.png" alt="">
                  </div>
                  <div class="content">
                     <span>upto 50% off</span>
                     <h3>latest nike football</h3>
                     <a href="shop.php" class="btn">Shop now</a>
                  </div>
               </div>

               <div class="swiper-slide slide">
                  <div class="image">
                     <img src="images/home-img-2.png" alt="">
                  </div>
                  <div class="content">
                     <span>upto 50% off</span>
                     <h3>latest Cricket kit</h3>
                     <a href="shop.php" class="btn">shop now</a>
                  </div>
               </div>

               <div class="swiper-slide slide">
                  <div class="image">
                     <img src="images/home-img-3.png" alt="">
                  </div>
                  <div class="content">
                     <span>upto 70% off</span>
                     <h3>latest Basketball</h3>
                     <a href="shop.php" class="btn">shop now</a>
                  </div>
               </div>

            </div>

            <div class="swiper-pagination"></div>

         </div>

      </section>

   </div>

   <section class="category">

      <h1 class="heading">shop by category</h1>

      <div class="swiper category-slider">

         <div class="swiper-wrapper">

            <a href="category.php?category=football" class="swiper-slide slide">
               <img src="images/icon-1.png" alt="">
               <h3>Football</h3>
            </a>

            <a href="category.php?category=cricket" class="swiper-slide slide">
               <img src="images/icon-2.png" alt="">
               <h3>Cricket</h3>
            </a>

            <a href="category.php?category=basketball" class="swiper-slide slide">
               <img src="images/icon-3.png" alt="">
               <h3>Basketball</h3>
            </a>

            <a href="category.php?category=badminton" class="swiper-slide slide">
               <img src="images/icon-4.png" alt="">
               <h3>Badminton</h3>
            </a>

            <a href="category.php?category=fitness" class="swiper-slide slide">
               <img src="images/icon-5.png" alt="">
               <h3>Fitness</h3>
            </a>

            <a href="category.php?category=volleyball" class="swiper-slide slide">
               <img src="images/icon-6.png" alt="">
               <h3>Volleyball</h3>
            </a>

            <a href="category.php?category=Table tennis" class="swiper-slide slide">
               <img src="images/icon-7.png" alt="">
               <h3>Table tennis</h3>
            </a>

         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>

   <!-- LATEST PRODUCTS SECTION -->
   <section class="home-products">
      <h1 class="heading">latest products</h1>

      <div class="products-grid">

         <?php
         // ⭐ FIX APPLIED: latest products first
         $select_products = $conn->prepare("SELECT * FROM `products` ORDER BY id DESC LIMIT 12");
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
            echo '<p class="empty">no products added yet!</p>';
         }
         ?>

      </div>
   </section>

   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
   <script src="js/script.js"></script>

   <script>
      var swiper = new Swiper(".home-slider", {
         loop: true,
         spaceBetween: 20,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
      });

      var swiper = new Swiper(".category-slider", {
         loop: true,
         spaceBetween: 20,
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
         breakpoints: {
            0: {
               slidesPerView: 2,
            },
            650: {
               slidesPerView: 3,
            },
            768: {
               slidesPerView: 4,
            },
            1024: {
               slidesPerView: 5,
            },
         },
      });
   </script>

</body>

</html>
