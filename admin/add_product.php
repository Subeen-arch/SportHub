<?php
include '../components/connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
   header('location:admin_login.php');
   exit();
}

$admin_id = $_SESSION['admin_id'];

/* 🟢 ADD NEW CATEGORY */
if (isset($_POST['add_category'])) {
   $new_category = filter_var($_POST['new_category'], FILTER_SANITIZE_STRING);
   if (!empty($new_category)) {
      $check = $conn->prepare("SELECT * FROM categories WHERE name = ?");
      $check->execute([$new_category]);
      if ($check->rowCount() > 0) {
         $message[] = 'Category already exists!';
      } else {
         $insert = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
         $insert->execute([$new_category]);
         $message[] = 'New category added successfully!';
      }
   }
}

/* 🟢 ADD NEW PRODUCT */
if (isset($_POST['add_product'])) {
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
   $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
   $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);

   $image_01 = filter_var($_FILES['image_01']['name'], FILTER_SANITIZE_STRING);
   $image_tmp = $_FILES['image_01']['tmp_name'];
   $image_size = $_FILES['image_01']['size'];
   $image_folder = '../uploaded_img/' . $image_01;

   $check = $conn->prepare("SELECT * FROM products WHERE name = ?");
   $check->execute([$name]);

   if ($check->rowCount() > 0) {
      $message[] = 'Product name already exists!';
   } else {
      if ($image_size > 2000000) {
         $message[] = 'Image size is too large!';
      } else {
         $insert = $conn->prepare("INSERT INTO products (name, category, details, price, image_01) VALUES (?, ?, ?, ?, ?)");
         $insert->execute([$name, $category, $details, $price, $image_01]);

         move_uploaded_file($image_tmp, $image_folder);
         $message[] = 'New product added successfully!';
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Product</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
<?php include '../components/admin_header.php'; ?>

<section class="add-products">
    <div class="heading-product">
        <h1 class="heading">Add Product</h1>
    </div>
   

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>Product Name (required)</span>
            <input type="text" name="name" class="box" placeholder="Enter product name" required maxlength="100">
         </div>

         <div class="inputBox">
            <span>Product Category (required)</span>
            <select name="category" class="box" required>
               <option value="" disabled selected>Select category</option>
               <?php
               $categories = $conn->prepare("SELECT * FROM categories ORDER BY name ASC");
               $categories->execute();
               while ($cat = $categories->fetch(PDO::FETCH_ASSOC)) {
                  echo '<option value="' . htmlspecialchars($cat['name']) . '">' . htmlspecialchars($cat['name']) . '</option>';
               }
               ?>
            </select>
         </div>

         <div class="inputBox">
            <span>Product Price (required)</span>
            <input type="number" min="0" class="box" required placeholder="Enter product price" name="price">
         </div>

         <div class="inputBox">
            <span>Product Image (required)</span>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
         </div>

         <div class="inputBox">
            <span>Product Details (required)</span>
            <textarea name="details" placeholder="Enter product details" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
      </div>
      <input type="submit" value="Add Product" class="btn" name="add_product">
   </form>
</section>

<section class="add-category">
   <h1 class="heading">Add New Category</h1>
   <form action="" method="post">
      <input type="text" name="new_category" class="box" placeholder="Enter new category name" required>
      <input type="submit" value="Add Category" name="add_category" class="btn">
   </form>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>
