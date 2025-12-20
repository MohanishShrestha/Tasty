<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   header('location:home.php');
   exit;
}

if (isset($_POST['submit'])) {
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);

   // Check if a record already exists for this user
   $check = $conn->prepare("SELECT * FROM table_number WHERE user_id = ?");
   $check->execute([$user_id]);

   if ($check->rowCount() > 0) {
      // Update existing table number
      $update = $conn->prepare("UPDATE table_number SET number = ? WHERE user_id = ?");
      $update->execute([$number, $user_id]);
   } else {
      // Insert new table number
      $insert = $conn->prepare("INSERT INTO table_number (number, user_id) VALUES (?, ?)");
      $insert->execute([$number, $user_id]);
   }

   $message[] = 'Table number saved!';
   header('location:checkout.php');
   exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update address</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/stylerg.css">


</head>

<body>

   <?php include 'components/user_header.php' ?>

   <section class="form-container">

      <form action="" method="post">
         <h3>your table number</h3>
         <input type="number" name="number" class="box" placeholder="Add table number" required min="0" max="99" maxlength="10">
         <input type="submit" value="save table number" name="submit" class="btn">
      </form>

   </section>










   <?php include 'components/footer.php' ?>







   <!-- custom js file link  -->
   <script src="js/script.js"></script>

</body>

</html>