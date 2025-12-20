<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}


if ($user_id) {
   // Step 1: Get all product names in cart for the user
   $cart_stmt = $conn->prepare("SELECT name FROM cart WHERE user_id = ?");
   $cart_stmt->execute([$user_id]);
   $cart_names = $cart_stmt->fetchAll(PDO::FETCH_COLUMN);

   if (!empty($cart_names)) {
      // Step 2: Get distinct categories of those product names from products table
      $placeholders = rtrim(str_repeat('?,', count($cart_names)), ',');
      $category_stmt = $conn->prepare("SELECT DISTINCT category FROM products WHERE name IN ($placeholders)");
      $category_stmt->execute($cart_names);
      $categories = $category_stmt->fetchAll(PDO::FETCH_COLUMN);

      if (!empty($categories)) {
         // Step 3: Exclude products already in the cart
         $category_placeholders = rtrim(str_repeat('?,', count($categories)), ',');
         $params = $categories;

         if (!empty($cart_names)) {
            $exclude_placeholders = rtrim(str_repeat('?,', count($cart_names)), ',');
            $params = array_merge($params, $cart_names);
            $exclude_clause = "AND name NOT IN ($exclude_placeholders)";
         } else {
            $exclude_clause = "";
         }

         // Step 4: Get recommended products
         $recommend_stmt = $conn->prepare("
            SELECT * FROM products 
            WHERE category IN ($category_placeholders)
            $exclude_clause
            LIMIT 6
         ");
         $recommend_stmt->execute($params);
         $recommend_products = $recommend_stmt->fetchAll(PDO::FETCH_ASSOC);
      }
   }
}




include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/stylerg.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="category">
      <h1 class="title">category</h1>
      <div class="box-container">
         <a href="category.php?category=veg" class="box">
            <img src="images/veg.jpg" alt="">
            <h3>Veg</h3>
         </a>
         <a href="category.php?category=Non-veg" class="box">
            <img src="images/home-img-3.png" alt="">
            <h3>Non-Veg</h3>
         </a>
      </div>
   </section>

   <?php
   $stmt = $conn->query("SELECT COUNT(*) FROM orders");
   $order_count = $stmt->fetchColumn();

   if ($order_count == 0):
   ?>

      <section class="products">
         <h1 class="title">New Items</h1>
         <div class="box-container">

            <?php
            $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
            $select_products->execute();
            if ($select_products->rowCount() > 0) {
               while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
                  <form action="" method="post" class="box">
                     <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                     <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                     <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                     <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
                     <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
                     <button type="submit" class="fas fa-utensils" name="add_to_cart"></button>
                     <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
                     <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"><?= $fetch_products['category']; ?></a>
                     <div class="name"><?= $fetch_products['name']; ?></div>
                     <div class="flex">
                        <div class="price"><span>Rs </span><?= $fetch_products['price']; ?></div>
                        <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
                     </div>
                  </form>
            <?php
               }
            } else {
               echo '<p class="empty">no products added yet!</p>';
            }
            ?>

         </div>

         <div class="more-btn">
            <a href="products.php" class="btn">View all</a>
         </div>
      </section>

   <?php else: ?>
      <?php if (!empty($recommend_products)) : ?>
         <section class="products">
            <h1 class="title">You Might Also Like</h1>
            <div class="box-container">
               <?php foreach ($recommend_products as $product): ?>
                  <form action="home.php" method="post" class="box">
                     <input type="hidden" name="pid" value="<?= $product['id']; ?>">
                     <input type="hidden" name="name" value="<?= $product['name']; ?>">
                     <input type="hidden" name="price" value="<?= $product['price']; ?>">
                     <input type="hidden" name="image" value="<?= $product['image']; ?>">
                     <a href="quick_view.php?pid=<?= $product['id']; ?>" class="fas fa-eye"></a>
                     <button type="submit" class="fas fa-utensils" name="add_to_cart"></button>
                     <img src="uploaded_img/<?= $product['image']; ?>" alt="">
                     <a href="category.php?category=<?= $product['category']; ?>" class="cat"><?= $product['category']; ?></a>
                     <div class="name"><?= $product['name']; ?></div>
                     <div class="flex">
                        <div class="price"><span>Rs </span><?= $product['price']; ?></div>
                        <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
                     </div>
                  </form>
               <?php endforeach; ?>
            </div>
         </section>
      <?php endif; ?>


   <?php endif; ?>

   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>
</body>

</html>