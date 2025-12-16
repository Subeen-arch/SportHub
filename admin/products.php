<?php
include '../components/connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
   header('location:admin_login.php');
   exit();
}

$admin_id = $_SESSION['admin_id'];

/* 🟢 DELETE PRODUCT */
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];

   $select = $conn->prepare("SELECT image_01 FROM products WHERE id = ?");
   $select->execute([$delete_id]);
   $product = $select->fetch(PDO::FETCH_ASSOC);

   if ($product && file_exists('../uploaded_img/' . $product['image_01'])) {
      unlink('../uploaded_img/' . $product['image_01']);
   }

   $conn->prepare("DELETE FROM products WHERE id = ?")->execute([$delete_id]);
   $conn->prepare("DELETE FROM cart WHERE pid = ?")->execute([$delete_id]);
   $conn->prepare("DELETE FROM wishlist WHERE pid = ?")->execute([$delete_id]);

   header('location:products.php');
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
<?php include '../components/admin_header.php'; ?>

<section class="show-products">
   <div class="heading-product">
      <h1 class="heading">Products Added</h1>
      <a href="add_product.php" class="btn-product">Add Product</a>
   </div>

   <div class="box-container">
      <?php
      $select_products = $conn->prepare("SELECT * FROM products ORDER BY id DESC");
      $select_products->execute();

      if ($select_products->rowCount() > 0) {
         while ($fetch = $select_products->fetch(PDO::FETCH_ASSOC)) {
      ?>
      <div class="box">
         <img src="../uploaded_img/<?= htmlspecialchars($fetch['image_01']); ?>" alt="">
         <div class="name"><?= htmlspecialchars($fetch['name']); ?></div>
         <div class="category"><b>Category:</b> <?= htmlspecialchars($fetch['category']); ?></div>
         <div class="price">Nrs: <span><?= htmlspecialchars($fetch['price']); ?></span> /-</div>
         <div class="details"><span><?= htmlspecialchars(substr($fetch['details'], 0, 100)); ?><?= strlen($fetch['details']) > 100 ? '...' : ''; ?></span></div>
         <div class="flex-btn">
            <a href="update_product.php?update=<?= $fetch['id']; ?>" class="option-btn">Update</a>
            <a href="products.php?delete=<?= $fetch['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
         </div>
      </div>
      <?php
         }
      } else {
         echo '<p class="empty">No products added yet!</p>';
      }
      ?>
   </div>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>
