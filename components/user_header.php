<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <section class="flex">

      <!-- for logo -->
      <!-- <img src="./images/logo.png" alt=""> -->
      <a href="home.php" class="logo">Tasty</a>

      <!-- navbar -->
      <nav class="navbar">
         <a href="home.php">Home</a>
         <a href="products.php">Menu</a>
         <a href="orders.php">Orders</a>
         <a href="about.php">About us</a>
         <a href="contact.php">Leave Review</a>
      </nav>

      <div class="icons">
         <?php
         // count item in cart
         $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $count_cart_items->execute([$user_id]);
         $total_cart_items = $count_cart_items->rowCount();
         ?>

         <!-- navbar other  -->
         <a href="search.php"><i class="fas fa-search"></i></a>
         <a href="cart.php"><i class="fas fa-utensils"></i><span>(<?= $total_cart_items; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>


      <!-- check if the user is login or not and display according to it -->
      <div class="profile">
         <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$user_id]);

         if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
            <p class="name"><?= $fetch_profile['name']; ?></p>
            <div class="flex">
               <a href="profile.php" class="btn">profile</a>
               <a href="components/user_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
            </div>
            <p class="account">
               <a href="login.php">login</a> or
               <a href="register1.php">register</a>
            </p>
         <?php
         } else {
         ?>
            <p class="name">please login first!</p>
            <a href="login.php" class="btn">login</a>
         <?php
         }
         ?>
      </div>

   </section>

</header>