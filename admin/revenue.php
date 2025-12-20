<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
};

// if (isset($_POST['update_payment'])) {

//    $order_id = $_POST['order_id'];
//    $payment_status = $_POST['payment_status'];
//    $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
//    $update_status->execute([$payment_status, $order_id]);

//    if (strtolower($payment_status) === 'completed') {

//       // Fetch only the updated order
//       $sql = "SELECT total_products FROM orders WHERE id = ?";
//       $stmt = $conn->prepare($sql);
//       $stmt->execute([$order_id]);

//       if ($stmt->rowCount() > 0) {
//          $row = $stmt->fetch(PDO::FETCH_ASSOC);
//          $order_items = $row['total_products'];

//          // Split items by comma
//          $items = explode(',', $order_items);

//          foreach ($items as $item) {
//             $item = trim($item);

//             // Match format: name (price x quantity)
//             if (preg_match('/^(.*?)\s*\((\d+)\s*x\s*(\d+)\)$/', $item, $matches)) {
//                $product = trim($matches[1]);
//                $unit_price = (int)$matches[2];
//                $quantity = (int)$matches[3];
//                $tp = $unit_price * $quantity;

//                // Insert into revenue table
//                $stmt_insert = $conn->prepare("INSERT INTO revenue (product_name, price, quantity, total_price, order_id) VALUES (?, ?, ?, ?, ?)");
//                $stmt_insert->execute([$product, $unit_price, $quantity, $tp, $order_id]);
//             }
//          }
//       }
//    }

//    // $total_price = $unit_price * $quantity;
//    // $stmt_insert = $conn->prepare("INSERT INTO revenue (product_name, quantity, unit_price, total_price, order_id) VALUES (?, ?, ?, ?, ?)");
//    // $stmt_insert->execute([$product, $quantity, $unit_price, $total_price, $order_id]);


//    $message[] = 'payment status updated!';
// }

// if (isset($_GET['delete'])) {
//    $delete_id = $_GET['delete'];
//    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
//    $delete_order->execute([$delete_id]);
//    header('location:placed_orders.php');
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Placed Orders</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      table {
         width: 100%;
         border-collapse: collapse;
         margin-top: 20px;
         background: #f1eaea;
      }

      th,
      td {
         border: 1px solid #a8a0a0;
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

      .btn1 {
         padding: 0.7rem;
         padding-left: 2rem;
         padding-right: 2rem;
         border-radius: 5px;
         text-decoration: none;
         color: #fff;
         background-color: #3498db;
         cursor: pointer;
         margin: 0 20px;
      }

      .btn1:hover {
         background-color: #2980b9;
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

      label,
      input[type="date"] {
         margin: 3.5rem;
         font-weight: bold;
         font-size: 1.9rem;
         color: #ff7a3c;

      }

      h2 {
         text-align: center;
         font-weight: bold;
         font-size: 2.5rem;
         color: #5845c2ff;
         margin-top: 2rem;
      }

      form {
         margin: 2rem;
         margin-left: 25rem;
      }
   </style>
</head>

<body>

   <?php include '../components/admin_header.php'; ?>

   <!-- Filter Form -->
   <form method="POST">
      <label>From: <input type="date" name="start_date" required></label>
      <label>To: <input type="date" name="end_date" required></label>
      <input type="submit" name="filter" value="Filter" class="btn1">
   </form>

   <?php
   if (isset($_POST['filter'])) {
      $start_date = $_POST['start_date'] . ' 00:00:00';
      $end_date = $_POST['end_date'] . ' 23:59:59';
      $startd = $_POST['start_date'];
      $endd = $_POST['end_date'];

      $stmt_summary = $conn->prepare("
         SELECT product_name, 
                SUM(quantity) AS total_sold, 
                SUM(total_price) AS total_price
         FROM revenue
         WHERE date BETWEEN ? AND ?
         GROUP BY product_name
         ORDER BY total_sold DESC
      ");
      $stmt_summary->execute([$start_date, $end_date]);
      $sum = 0;

      if ($stmt_summary->rowCount() > 0) {
         echo "<h2>Product Sales Summary from $startd to $endd</h2>";
         echo "<table>
            <tr>
               <th>Product</th>
               <th>Total Quantity Sold</th>
               <th>Total Revenue (Rs)</th>
            </tr>";
         while ($row = $stmt_summary->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
               <td>{$row['product_name']}</td>
               <td>{$row['total_sold']}</td>
               <td>Rs {$row['total_price']}</td>
            </tr>";

            $sum = $sum + $row['total_price'];
         }
         echo "<tr>
        <td></td>
        <td><span style='font-weight: bold;
         font-size: 1.9rem;
         color: #ff7a3c;'>Grand Total: </span></td>
        <td>Rs {$sum}</td>
      </tr>";

         echo "</table>";
      } else {
         echo "<p class='empty'>No product summary found in the selected range.</p>";
      }
   }
   ?>



   <!-- Custom JS -->
   <script src="../js/admin_script.js"></script>

</body>

</html>