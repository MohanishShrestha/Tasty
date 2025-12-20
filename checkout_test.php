<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    header('location:home.php');
    exit;
}

// Fetch table number
$fetch_number = [];
$select_number = $conn->prepare("SELECT * FROM table_number WHERE user_id = ?");
$select_number->execute([$user_id]);
if ($select_number->rowCount() > 0) {
    $fetch_number = $select_number->fetch(PDO::FETCH_ASSOC);
}

$fetch_productId = [];
$select_productId = $conn->prepare("SELECT * FROM product WHERE user_id = ?");
$select_productId->execute([$user_id]);
if ($select_productId->rowCount() > 0) {
    $fetch_productId = $select_productId->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['submit'])) {
        $name = htmlspecialchars($_POST['name']);
        $number = htmlspecialchars($_POST['number']);
        // $email = htmlspecialchars($_POST['email']);
        // $address = htmlspecialchars($_POST['address']);
        $total_products = htmlspecialchars($_POST['total_products']);
        $total_price = htmlspecialchars($_POST['total_price']);

        $check_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $check_cart->execute([$user_id]);

        if ($check_cart->rowCount() > 0) {
            if (empty($address)) {
                $method = 'cash on delivery'; // Default payment method
                $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, total_products, total_price, method) VALUES(?,?,?,?,?,?)");
                $insert_order->execute([$user_id, $name, $number, $total_products, $total_price, $method]);


                $delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
                $delete_cart->execute([$user_id]);

                $message[] = 'order placed successfully!';
            }
        } else {
            $message[] = 'your cart is empty';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/stylerg.css">
</head>

<body>
    <?php include 'components/user_header.php'; ?>

    <div class="heading">
        <h3>Total Food Items</h3>
        <p><a href="home.php">Home</a> <span> / Food</span></p>
    </div>

    <section class="checkout">
        <h1 class="title">Summary</h1>
        <form action="" method="post">
            <div class="cart-items">
                <h3>Food Items</h3>
                <?php
                $grand_total = 0;
                $cart_items = [];
                $select_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
                $select_cart->execute([$user_id]);

                if ($select_cart->rowCount() > 0) {
                    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ')';
                        $grand_total += $fetch_cart['price'] * $fetch_cart['quantity'];
                ?>
                        <p><span class="name"><?= htmlspecialchars($fetch_cart['name']); ?></span><span
                                class="price">Rs <?= htmlspecialchars($fetch_cart['price']); ?> x <?= htmlspecialchars($fetch_cart['quantity']); ?></span></p>
                <?php
                    }
                    $total_products = implode(', ', $cart_items);
                } else {
                    echo '<p class="empty">Your cart is empty!</p>';
                }



                ?>
                <p class="grand-total"><span class="name">Grand Total :</span><span class="price">Rs <?= $grand_total; ?></span></p>
                <a href="cart.php" class="btn">View your items</a>
            </div>

            <input type="hidden" name="total_products" value="<?= htmlspecialchars($total_products ?? ''); ?>">
            <input type="hidden" name="total_price" value="<?= htmlspecialchars($grand_total); ?>">
            <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_profile['name'] ?? ''); ?>">
            <input type="hidden" name="number" value="<?= htmlspecialchars($fetch_profile['number'] ?? ''); ?>">
            <!-- <input type="hidden" name="email" value="<?= htmlspecialchars($fetch_profile['email'] ?? ''); ?>"> -->
            <!-- <input type="hidden" name="address" value="<?= htmlspecialchars($fetch_profile['address'] ?? ''); ?>"> -->

            <div class="user-info">
                <h3>Your Info</h3>
                <p><i class="fas fa-user"></i><span><?= htmlspecialchars($fetch_profile['name'] ?? ''); ?></span></p>
                <p><i class="fas fa-phone"></i><span><?= htmlspecialchars($fetch_profile['number'] ?? ''); ?></span></p>
                <p><i class="fas fa-envelope"></i><span><?= htmlspecialchars($fetch_profile['email'] ?? ''); ?></span></p>
                <a href="update_profile.php" class="btn">Update Info</a>
                <h3>Table number</h3>
                <p><i class="fas fa-map-marker-alt"></i><span><?= htmlspecialchars($fetch_number['number'] ?? 'Please enter your table number'); ?></span></p>
                <a href="update_address.php" class="btn">Table number set</a>
                <button type="submit" class="btn" name="submit" style="width:100%; background:var(--red); color:var(--white);">Place Order</button>
            </div>
        </form>
    </section>

    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>
</body>

</html>