<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};

if (isset($_POST['send'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $comment = $_POST['comment'];
   $comment = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_message = $conn->prepare("SELECT * FROM `review` WHERE title = ?  AND review = ? AND comment = ?");
   $select_message->execute([$name,  $msg, $comment]);

   if ($select_message->rowCount() > 0) {
      $message[] = 'already sent message!';
   } else {

      $insert_message = $conn->prepare("INSERT INTO `review`(title, review, comment) VALUES(?,?,?)");
      $insert_message->execute([$name, $msg, $comment]);

      $message[] = 'sent message successfully!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

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
      <h3>leave review</h3>
      <p><a href="home.php">home</a> <span> / leave review</span></p>
   </div>

   <!-- contact section starts  -->

   <section class="contact">

      <div class="row">

         <!-- <div class="image">
         <img src="images/contact-img.svg" alt="">
      </div> -->

         <form action="" method="post">
            <h3>Leave Review!!</h3>
            <input type="text" name="name" maxlength="50" class="box" placeholder="Enter Title Here" required>
            <textarea name="msg" class="box" required placeholder="Enter review here" maxlength="500" cols="30" rows="10" required></textarea>
            <input type="text" name="comment" maxlength="50" class="box" placeholder="Enter comment">

            <input type="submit" value="send review" name="send" class="btn">
         </form>

      </div>

   </section>

   <!-- contact section ends -->










   <!-- footer section starts  -->
   <?php include 'components/footer.php'; ?>
   <!-- footer section ends -->








   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>