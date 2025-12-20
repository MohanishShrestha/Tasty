<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:home.php');
    exit;
}
$user_id = $_SESSION['user_id'];

// Fetch user profile
$fetch_profile = [];
$select_profile = $conn->prepare("SELECT * FROM users WHERE id = ?");
$select_profile->execute([$user_id]);
if ($select_profile->rowCount() > 0) {
    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
}

// Fetch table number
$fetch_number = [];
$select_number = $conn->prepare("SELECT * FROM table_number WHERE user_id = ?");
$select_number->execute([$user_id]);
if ($select_number->rowCount() > 0) {
    $fetch_number = $select_number->fetch(PDO::FETCH_ASSOC);
}

// Fetch cart
$cart_items = [];
$grand_total = 0;
$select_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
$select_cart->execute([$user_id]);
if ($select_cart->rowCount() > 0) {
    while ($row = $select_cart->fetch(PDO::FETCH_ASSOC)) {
        $cart_items[] = $row['name'] . ' (' . $row['price'] . ' x ' . $row['quantity'] . ')';
        $grand_total += $row['price'] * $row['quantity'];
    }
}

$total_products = implode(', ', $cart_items);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Save table number
    if (isset($_POST['save_table'])) {
        $table_number = htmlspecialchars($_POST['table_number']);
        $check = $conn->prepare("SELECT * FROM table_number WHERE user_id = ?");
        $check->execute([$user_id]);

        if ($check->rowCount() > 0) {
            $update = $conn->prepare("UPDATE table_number SET number = ? WHERE user_id = ?");
            $update->execute([$table_number, $user_id]);
        } else {
            $insert = $conn->prepare("INSERT INTO table_number (number, user_id) VALUES (?, ?)");
            $insert->execute([$table_number, $user_id]);
        }

        header("Location: checkout.php?success=1");
        exit;
    }

    //  Place order
    if (isset($_POST['submit'])) {
        $name = htmlspecialchars($_POST['name']);
        $number = htmlspecialchars($_POST['number']);
        $total_products = htmlspecialchars($_POST['total_products']);
        $total_price = htmlspecialchars($_POST['total_price']);
        $method = 'cash on delivery';

        $check_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $check_cart->execute([$user_id]);

        if ($check_cart->rowCount() > 0) {
            $insert_order = $conn->prepare("INSERT INTO orders(user_id, name, number, total_products, total_price, method) VALUES(?,?,?,?,?,?)");
            $insert_order->execute([$user_id, $name, $number, $total_products, $total_price, $method]);

            $delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $delete_cart->execute([$user_id]);

            $message[] = 'order placed successfully!';
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
                        <p><span class="name" style="background-color: white; padding: 5px 10px;"><?= htmlspecialchars($fetch_cart['name']); ?></span>
                            <span class="price">Rs <?= htmlspecialchars($fetch_cart['price']); ?> x <?= htmlspecialchars($fetch_cart['quantity']); ?></span>
                        </p>
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

            <input type="hidden" name="total_products" value="<?= htmlspecialchars($total_products); ?>">
            <input type="hidden" name="total_price" value="<?= htmlspecialchars($grand_total); ?>">
            <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_profile['name'] ?? ''); ?>">
            <input type="hidden" name="number" value="<?= htmlspecialchars($fetch_profile['number'] ?? ''); ?>">

            <div class="user-info">
                <h3>Your Info</h3>
                <p><i class="fas fa-user"></i><span><?= htmlspecialchars($fetch_profile['name'] ?? ''); ?></span></p>
                <p><i class="fas fa-phone"></i><span><?= htmlspecialchars($fetch_profile['number'] ?? ''); ?></span></p>
                <p><i class="fas fa-envelope"></i><span><?= htmlspecialchars($fetch_profile['email'] ?? ''); ?></span></p>
                <a href="update_profile.php" class="btn">Update Info</a>

                <h3>Table Number</h3>
                <p>
                    <label for="table_number">
                        <i class="fas fa-map-marker-alt"></i>
                        <select name="table_number" id="table_number" required>
                            <option value=""><?= isset($fetch_number['number']) ? 'Current: Table ' . htmlspecialchars($fetch_number['number']) : 'Select your table number' ?></option>
                            <?php
                            for ($i = 1; $i <= 20; $i++) {
                                $selected = (isset($fetch_number['number']) && $fetch_number['number'] == $i) ? 'selected' : '';
                                echo "<option value=\"$i\" $selected>Table $i</option>";
                            }
                            ?>
                        </select>
                    </label>
                </p>
                <br><br>
                <button type="submit" name="save_table" class="btn">Save Table Number</button>
                <?php if (isset($_GET['success'])): ?>
                    <p style="color:green;">Table number saved!</p>
                <?php endif; ?>

                <br><br>
                <button type="submit" name="submit" class="btn" style="width:100%; background:var(--red); color:var(--white);">Place Order</button>
            </div>
        </form>
    </section>


    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>
</body>

</html>