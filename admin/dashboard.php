<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">



</head>

<body>

   <?php include '../components/admin_header.php' ?>

   <!-- admin dashboard section starts  -->

   <section class="dashboard">

      <h1 class="heading">dashboard</h1>

      <table>
         <thead>
            <tr>
               <th>
                  <h1>Title</h1>
               </th>
               <th>
                  <h1>Details</h1>
               </th>
               <th>
                  <h1>Actions</h1>
               </th>
            </tr>
         </thead>
         <tbody>
            <tr>
               <td>
                  <h3>Welcome</h3>
               </td>
               <td>
                  <h3><?= $fetch_profile['name']; ?></h3>
               </td>
               <td><a href="update_profile.php" class="btn">
                     <h3>Update Profile</h3>
                  </a></td>
            </tr>

            <td>
               <h3>Revenue</h3>
            </td>
            <?php
            $total_revenue = 0;
            $select_revenue = $conn->prepare("SELECT * FROM `revenue`");
            $select_revenue->execute();
            while ($fetch_revenue = $select_revenue->fetch(PDO::FETCH_ASSOC)) {
               $total_revenue += $fetch_revenue['total_price'];
            }
            ?>
            <td><span>
                  <h3>Rs
               </span><?= $total_revenue; ?><span>/-</span></h3>
            </td>
            <td><a href="revenue.php" class="btn">
                  <h3>See Revenue</h3>
               </a></td>
            </tr>


            <tr>
               <td>
                  <h3>Total Pendings</h3>
               </td>
               <?php
               $total_pendings = 0;
               $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
               $select_pendings->execute(['pending']);
               while ($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
                  $total_pendings += $fetch_pendings['total_price'];
               }
               ?>
               <td><span>
                     <h3>Rs
                  </span><?= $total_pendings; ?><span>/-</span></h3>
               </td>
               <td><a href="placed_orders.php" class="btn">
                     <h3>See Orders</h3>
                  </a></td>
            </tr>

            <tr>
               <td>
                  <h3>Total Completes</h3>
               </td>
               <?php
               $total_completes = 0;
               $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
               $select_completes->execute(['completed']);
               while ($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)) {
                  $total_completes += $fetch_completes['total_price'];
               }
               ?>
               <td>
                  <h3><span>Rs </span><?= $total_completes; ?><span>/-</span></h3>
               </td>
               <td><a href="placed_orders.php" class="btn">
                     <h3>See Orders</h3>
                  </a></td>
            </tr>
            <tr>
               <td>
                  <h3>Total Orders</h3>
               </td>
               <?php
               $select_orders = $conn->prepare("SELECT * FROM `orders`");
               $select_orders->execute();
               $numbers_of_orders = $select_orders->rowCount();
               ?>
               <td>
                  <h3><?= $numbers_of_orders; ?></h3>
               </td>
               <td><a href="placed_orders.php" class="btn">
                     <h3>See Orders</h3>
                  </a></td>
            </tr>
            <tr>
               <td>
                  <h3>Products Added</h3>
               </td>
               <?php
               $select_products = $conn->prepare("SELECT * FROM `products`");
               $select_products->execute();
               $numbers_of_products = $select_products->rowCount();
               ?>
               <td>
                  <h3><?= $numbers_of_products; ?></h3>
               </td>
               <td><a href="products.php" class="btn">
                     <h3>See Products</h3>
                  </a></td>
            </tr>
            <tr>
               <td>
                  <h3>Users Accounts</h3>
               </td>
               <?php
               $select_users = $conn->prepare("SELECT * FROM `users`");
               $select_users->execute();
               $numbers_of_users = $select_users->rowCount();
               ?>
               <td>
                  <h3><?= $numbers_of_users; ?></h3>
               </td>
               <td>
                  <h3><a href="users_accounts.php" class="btn">See Users</a></h3>
               </td>
            </tr>
            <tr>
               <td>
                  <h3>Admins</h3>
               </td>
               <?php
               $select_admins = $conn->prepare("SELECT * FROM `admin`");
               $select_admins->execute();
               $numbers_of_admins = $select_admins->rowCount();
               ?>
               <td>
                  <h3><?= $numbers_of_admins; ?></h3>
               </td>
               <td>
                  <h3><a href="admin_accounts.php" class="btn">See Admins</a></h3>
               </td>
            </tr>
            <tr>
               <td>
                  <h3>New Messages</h3>
               </td>
               <?php
               $select_messages = $conn->prepare("SELECT * FROM `messages`");
               $select_messages->execute();
               $numbers_of_messages = $select_messages->rowCount();
               ?>
               <td>
                  <h3><?= $numbers_of_messages; ?></h3>
               </td>
               <td>
                  <h3><a href="messages.php" class="btn">See Messages</a></h3>
               </td>
            </tr>
         </tbody>
      </table>
      <!-- 
   <div class="box-container">

   <div class="box">
      <h3>welcome!</h3>
      <p><?= $fetch_profile['name']; ?></p>
      <a href="update_profile.php" class="btn">update profile</a>
   </div>

   <div class="box">
      <?php
      $total_pendings = 0;
      $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
      $select_pendings->execute(['pending']);
      while ($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
         $total_pendings += $fetch_pendings['total_price'];
      }
      ?>
      <h3><span>Rs</span><?= $total_pendings; ?><span>/-</span></h3>
      <p>total pendings</p>
      <a href="placed_orders.php" class="btn">see orders</a>
   </div>

   <div class="box">
      <?php
      $total_completes = 0;
      $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
      $select_completes->execute(['completed']);
      while ($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)) {
         $total_completes += $fetch_completes['total_price'];
      }
      ?>
      <h3><span>Rs </span><?= $total_completes; ?><span>/-</span></h3>
      <p>total completes</p>
      <a href="placed_orders.php" class="btn">see orders</a>
   </div>

   <div class="box">
      <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      $numbers_of_orders = $select_orders->rowCount();
      ?>
      <h3><?= $numbers_of_orders; ?></h3>
      <p>total orders</p>
      <a href="placed_orders.php" class="btn">see orders</a>
   </div>

   <div class="box">
      <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      $numbers_of_products = $select_products->rowCount();
      ?>
      <h3><?= $numbers_of_products; ?></h3>
      <p>products added</p>
      <a href="products.php" class="btn">see products</a>
   </div>

   <div class="box">
      <?php
      $select_users = $conn->prepare("SELECT * FROM `users`");
      $select_users->execute();
      $numbers_of_users = $select_users->rowCount();
      ?>
      <h3><?= $numbers_of_users; ?></h3>
      <p>users accounts</p>
      <a href="users_accounts.php" class="btn">see users</a>
   </div>

   <div class="box">
      <?php
      $select_admins = $conn->prepare("SELECT * FROM `admin`");
      $select_admins->execute();
      $numbers_of_admins = $select_admins->rowCount();
      ?>
      <h3><?= $numbers_of_admins; ?></h3>
      <p>admins</p>
      <a href="admin_accounts.php" class="btn">see admins</a>
   </div>

   <div class="box">
      <?php
      $select_messages = $conn->prepare("SELECT * FROM `messages`");
      $select_messages->execute();
      $numbers_of_messages = $select_messages->rowCount();
      ?>
      <h3><?= $numbers_of_messages; ?></h3>
      <p>new messages</p>
      <a href="messages.php" class="btn">see messages</a>
   </div> -->



   </section>

   <!-- admin dashboard section ends -->









   <!-- custom js file link  -->
   <script src="../js/admin_script.js"></script>

</body>

</html>