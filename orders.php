<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:home.php');
};

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/stylerg.css">

</head>

<body>

   <!-- header section starts  -->
   <?php include 'components/user_header.php'; ?>
   <!-- header section ends -->

   <div class="heading">
      <h3>Orders</h3>
      <p><a href="html.php">home</a> <span> / Orders</span></p>
   </div>

   <section class="orders">

      <h1 class="title">your orders</h1>

      <div class="table-container">
         <?php
         if ($user_id == '') {
            echo '<p class="empty">please login to see your orders</p>';
         } else {
            $select_orders = $conn->prepare("SELECT o.*, t.number AS table_number 
                                 FROM orders o
                                 LEFT JOIN table_number t ON o.user_id = t.user_id 
                                 WHERE o.user_id = ?");
            $select_orders->execute([$user_id]);


            if ($select_orders->rowCount() > 0) {
               echo '<table border="1" cellpadding="10" cellspacing="0" class="orders-table">';
               echo '<thead>
        <tr>
            <th>Placed On</th>
            <th>Name</th>
            <th>Table Number</th>
            <th>Payment Method</th>
            <th>Ordered food</th>
            <th>Total Price</th>
            <th>Payment Status</th>
        </tr>
    </thead>';
               echo '<tbody>';
               while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                  echo '<tr>
            <td>' . $fetch_orders['placed_on'] . '</td>
            <td>' . $fetch_orders['name'] . '</td>
            <td>' . $fetch_orders['table_number'] . '</td>
            <td>' . $fetch_orders['method'] . '</td>
            <td>' . $fetch_orders['total_products'] . '</td>
            <td>Rs ' . $fetch_orders['total_price'] . '/-</td>
            <td style="color:' . ($fetch_orders['payment_status'] == 'pending' ? 'red' : 'green') . '">' . $fetch_orders['payment_status'] . '</td>
        </tr>';
               }
               echo '</tbody></table>';
            } else {
               echo '<p class="empty">no orders placed yet!</p>';
            }
         }
         ?>
      </div>


   </section>










   <!-- footer section starts  -->
   <?php include 'components/footer.php'; ?>
   <!-- footer section ends -->






   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>