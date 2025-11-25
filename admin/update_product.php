<?php
include '../components/connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
   header('location:admin_login.php');
   exit();
}

if (isset($_GET['update'])) {
   $update_id = $_GET['update'];

   $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
   $stmt->execute([$update_id]);
   if ($stmt->rowCount() > 0) {
      $product = $stmt->fetch(PDO::FETCH_ASSOC);
   } else {
      header('location:products.php');
      exit();
   }
}

if (isset($_POST['update_product'])) {
   $pid = $_POST['pid'];
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT);
   $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);

   $update_stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, details = ? WHERE id = ?");
   $update_stmt->execute([$name, $price, $details, $pid]);

   // Handle optional new image upload
   if (!empty($_FILES['image_01']['name'])) {
      $image = $_FILES['image_01']['name'];
      $image_tmp = $_FILES['image_01']['tmp_name'];
      $image_size = $_FILES['image_01']['size'];
      $image_path = '../uploaded_img/' . $image;

      if ($image_size > 2000000) {
         $message[] = 'Image size too large!';
      } else {
         // Get old image
         $stmt = $conn->prepare("SELECT image_01 FROM products WHERE id = ?");
         $stmt->execute([$pid]);
         $old = $stmt->fetch(PDO::FETCH_ASSOC);

         if ($old && file_exists('../uploaded_img/' . $old['image_01'])) {
            unlink('../uploaded_img/' . $old['image_01']);
         }

         move_uploaded_file($image_tmp, $image_path);

         $update_image = $conn->prepare("UPDATE products SET image_01 = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);
      }
   }

   $message[] = 'Product updated successfully!';
   header('location:products.php');
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Product</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="update-product">
   <h1 class="heading">Update Product</h1>

   <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $product['id']; ?>">

      <div class="inputBox">
         <span>Product Name</span>
         <input type="text" name="name" class="box" required value="<?= htmlspecialchars($product['name']); ?>">
      </div>

      <div class="inputBox">
         <span>Product Price</span>
         <input type="number" name="price" class="box" required min="0" value="<?= htmlspecialchars($product['price']); ?>">
      </div>

      <div class="inputBox">
         <span>Product Details</span>
         <textarea name="details" class="box" required><?= htmlspecialchars($product['details']); ?></textarea>
      </div>

      <div class="inputBox">
         <span>Current Image</span>
         <img src="../uploaded_img/<?= $product['image_01']; ?>" alt="" class="image-preview">
      </div>

      <div class="inputBox">
         <span>Upload New Image (optional)</span>
         <input type="file" name="image_01" class="box" accept="image/*">
      </div>

      <div class="flex-btn">
         <input type="submit" name="update_product" value="Update Product" class="btn">
         <a href="products.php" class="option-btn">Go Back</a>
      </div>
   </form>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>
