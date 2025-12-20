<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `review` WHERE title = ?");
   $delete_message->execute([$delete_id]);
   header('location:messages.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      table {
         width: 100%;
         border-collapse: collapse;
         margin-top: 20px;
      }

      th,
      td {
         border: 1px solid #ddd;
         padding: 10px;
         text-align: center;
         font-size: 1.5rem;
      }

      th {
         background-color: #f4f4f4;
         font-weight: bold;
         text-align: center;
         font-size: 2rem;
      }

      .actions {
         text-align: center;
      }

      .delete-btn {
         padding: 5px 10px;
         border-radius: 5px;
         text-decoration: none;
         color: #fff;
         background-color: #e74c3c;
         cursor: pointer;
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

   <!-- messages section starts -->

   <section class="messages">

      <h1 class="heading">Messages</h1>

      <?php
      $select_messages = $conn->prepare("SELECT * FROM `review`");
      $select_messages->execute();
      if ($select_messages->rowCount() > 0) {
      ?>
         <table>
            <thead>
               <tr>
                  <th>title</th>
                  <th>review</th>
                  <th>comment</th>
                  <th class="actions">Actions</th>
               </tr>
            </thead>
            <tbody>
               <?php
               while ($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)) {
               ?>
                  <tr>
                     <td><?= $fetch_messages['title']; ?></td>
                     <td><?= $fetch_messages['review']; ?></td>
                     <td><?= $fetch_messages['comment']; ?></td>
                     <td class="actions">
                        <a href="messages.php?delete=<?= $fetch_messages['title']; ?>" class="delete-btn" onclick="return confirm('Delete this message?');">Delete</a>
                     </td>
                  </tr>
               <?php
               }
               ?>
            </tbody>
         </table>
      <?php
      } else {
         echo '<p class="empty">You have no messages</p>';
      }
      ?>

   </section>

   <!-- messages section ends -->

   <!-- custom js file link -->
   <script src="../js/admin_script.js"></script>

</body>

</html>