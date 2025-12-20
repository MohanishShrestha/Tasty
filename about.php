<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

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
   <h3>about us</h3>
   <p><a href="home.php">Home</a> <span> / About</span></p>
</div>

<!-- about section starts  -->

<section class="about">

   <div class="row">

      <!-- <div class="image">
         <img src="images/ecom.jfif" alt="">
      </div> -->

      <div class="content">
         <h3>why choose us?</h3>
         <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Deserunt, neque debitis incidunt qui ipsum sed doloremque a molestiae in veritatis ullam similique sunt aliquam dolores dolore? Quasi atque debitis nobis!</p>
         <a href="products.php" class="btn">Our Products</a>
      </div>

   </div>

</section>

<!-- about section ends -->




<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->=


<!-- custom js file link  -->
<script src="js/script.js"></script>


</body>
</html>