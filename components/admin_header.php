<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!-- SIDEBAR -->
<div class="sidebar">
   <h2 class="sidebar-title">Admin <span>Panel</span></h2>

   <ul class="sidebar-links">
      <li><a href="../admin/dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
      <li><a href="../admin/products.php"><i class="fas fa-box"></i> Products</a></li>
      <li><a href="../admin/placed_orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
      <li><a href="../admin/admin_accounts.php"><i class="fas fa-user-shield"></i> Admins</a></li>
      <li><a href="../admin/users_accounts.php"><i class="fas fa-users"></i> Users</a></li>
      <li><a href="../admin/messages.php"><i class="fas fa-envelope"></i> Messages</a></li>
   </ul>
</div>

<!-- TOP HEADER -->
<header class="header">

   <section class="flex">

      <div class="left-section">
         <div id="menu-btn" class="fas fa-bars"></div>
         <h3 class="page-title"><?= $pageTitle ?? "Admin Dashboard"; ?></h3>
      </div>

      <div class="right-section">

         <div class="profile-icon" id="user-btn">
            <i class="fas fa-user"></i>
         </div>

         <div class="profile">
            <?php
               $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
               $select_profile->execute([$admin_id]);
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <p><?= $fetch_profile['name']; ?></p>
            <a href="../admin/update_profile.php" class="btn">Update profile</a>
            <a href="../components/admin_logout.php" class="delete-btn" onclick="return confirm('Logout?');">Logout</a>
         </div>

      </div>
   </section>

</header>
