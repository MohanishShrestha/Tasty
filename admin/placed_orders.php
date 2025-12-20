<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
};

if (isset($_POST['update_payment'])) {

   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_status->execute([$payment_status, $order_id]);

   if (strtolower($payment_status) === 'completed') {

      // Fetch only the updated order
      $sql = "SELECT total_products FROM orders WHERE id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->execute([$order_id]);

      if ($stmt->rowCount() > 0) {
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         $order_items = $row['total_products'];

         // Split items by comma
         $items = explode(',', $order_items);

         foreach ($items as $item) {
            $item = trim($item);

            // Match format: name (price x quantity)
            if (preg_match('/^(.*?)\s*\((\d+)\s*x\s*(\d+)\)$/', $item, $matches)) {
               $product = trim($matches[1]);
               $unit_price = (int)$matches[2];
               $quantity = (int)$matches[3];
               $tp = $unit_price * $quantity;

               // Insert into revenue table
               $stmt_insert = $conn->prepare("INSERT INTO revenue (product_name, price, quantity, total_price, order_id) VALUES (?, ?, ?, ?, ?)");
               $stmt_insert->execute([$product, $unit_price, $quantity, $tp, $order_id]);
            }
         }
      }
   }

   // $total_price = $unit_price * $quantity;
   // $stmt_insert = $conn->prepare("INSERT INTO revenue (product_name, quantity, unit_price, total_price, order_id) VALUES (?, ?, ?, ?, ?)");
   // $stmt_insert->execute([$product, $quantity, $unit_price, $total_price, $order_id]);


   $message[] = 'payment status updated!';
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Placed Orders</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      table {
         width: 100%;
         border-collapse: collapse;
         margin-top: 20px;
         background: rgb(241, 234, 234);
      }

      th,
      td {
         border: 1px solid rgb(168, 160, 160);
         padding: 10px;
         font-size: 1.5rem;
         text-align: center;

      }

      th {
         background-color: #f4f4f4;
         font-weight: bold;
         font-size: 2rem;
         color: #ff7a3c;
      }

      .actions {
         display: flex;
         gap: 10px;
         justify-content: center;
      }

      .btn,
      .delete-btn {
         padding: 5px 10px;
         border-radius: 5px;
         text-decoration: none;
         color: #fff;
         background-color: #3498db;
         cursor: pointer;
      }

      .delete-btn {
         background-color: #e74c3c;
      }

      .btn:hover {
         background-color: #2980b9;
      }

      .delete-btn:hover {
         background-color: #c0392b;
      }

      .empty {
         text-align: center;
         margin: 20px 0;
         color: gray;
      }

      select {
         padding: 5px;
         border-radius: 5px;
      }
   </style>
</head>

<body>

   <?php include '../components/admin_header.php'; ?>

   <!-- placed orders section starts -->

   <section class="placed-orders">

      <h1 class="heading">Placed Orders</h1>

      <?php
      $select_orders = $conn->prepare("SELECT o.*, t.number AS table_number 
                                 FROM orders o
                                 LEFT JOIN table_number t ON o.user_id = t.user_id");
      $select_orders->execute();

      if ($select_orders->rowCount() > 0) {
      ?>
         <table>
            <thead>
               <tr>
                  <th>User ID</th>
                  <th>Placed On</th>
                  <th>Name</th>
                  <th>Number</th>
                  <th>Table Number</th>
                  <th>Total Products</th>
                  <th>Total Price</th>
                  <th>Payment Method</th>
                  <th>Payment Status</th>
                  <th>Actions</th>
               </tr>
            </thead>
            <tbody>
               <?php
               while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
               ?>
                  <tr>
                     <td><?= $fetch_orders['user_id']; ?></td>
                     <td><?= $fetch_orders['placed_on']; ?></td>
                     <td><?= $fetch_orders['name']; ?></td>
                     <td><?= $fetch_orders['number']; ?></td>
                     <td><?= $fetch_orders['table_number']; ?></td>
                     <td><?= $fetch_orders['total_products']; ?></td>
                     <td>Rs <?= $fetch_orders['total_price']; ?>/-</td>
                     <td><?= $fetch_orders['method']; ?></td>
                     <td>
                        <form action="" method="POST">
                           <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                           <select name="payment_status">
                              <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
                              <option value="pending">Pending</option>
                              <option value="completed">Completed</option>
                           </select>
                           <input type="submit" value="Update" class="btn" name="update_payment">
                        </form>
                     </td>
                     <td>
                        <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Delete this order?');">Delete</a>
                     </td>
                  </tr>
               <?php
               }
               ?>
            </tbody>
         </table>
      <?php
      } else {
         echo '<p class="empty">No orders placed yet!</p>';
      }
      ?>

   </section>

   <!-- placed orders section ends -->

   <!-- custom js file link -->
   <script src="../js/admin_script.js"></script>

</body>

</html>