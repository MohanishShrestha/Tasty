<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_users->execute([$delete_id]);
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_order->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);
   header('location:users_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Accounts</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      table {
         width: 100%;
         border-collapse: collapse;
         margin-top: 20px;
      }

      th, td {
         border: 1px solid #ddd;
         padding: 10px;
         text-align: center;
         font-size: 2rem;
      }

      th {
         background-color: #f4f4f4;
         text-align: center;
         font-size: 2.5rem;
      }

      .actions {
         text-align: center;
      }

      .delete-btn {
         background-color: #e74c3c;
         color: #fff;
         padding: 5px 10px;
         border-radius: 5px;
         text-decoration: none;
      }

      .delete-btn:hover {
         background-color: #c0392b;
      }

      .empty {
         text-align: center;
         margin: 20px 0;
         color: gray;
      }
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- Users Accounts Section Starts -->

<section class="accounts">

   <h1 class="heading">User Accounts</h1>

   <?php
      $select_account = $conn->prepare("SELECT * FROM `users`");
      $select_account->execute();

      if ($select_account->rowCount() > 0) {
   ?>
   <table>
      <thead>
         <tr>
            <th>User ID</th>
            <th>Username</th>
            <th class="actions">Actions</th>
         </tr>
      </thead>
      <tbody>
         <?php
            while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {
         ?>
         <tr>
            <td><?= $fetch_accounts['id']; ?></td>
            <td><?= $fetch_accounts['name']; ?></td>
            <td class="actions">
               <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Delete this account?');">Delete</a>
            </td>
         </tr>
         <?php
            }
         ?>
      </tbody>
   </table>
   <?php
      } else {
         echo '<p class="empty">No accounts available</p>';
      }
   ?>

</section>

<!-- Users Accounts Section Ends -->

<!-- Custom JS File Link -->
<script src="../js/admin_script.js"></script>

</body>
</html>
