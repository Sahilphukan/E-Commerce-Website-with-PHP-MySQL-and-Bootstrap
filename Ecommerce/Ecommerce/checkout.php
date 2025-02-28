<?php
session_start(); // Start the session
include 'includes/db.php';

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $total_amount = 0;

    // Calculate the total amount
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $sql = "SELECT price FROM products WHERE id = $product_id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $total_amount += $row['price'] * $quantity;
    }

    // Insert the order into the database
    $sql = "INSERT INTO orders (user_id, total_amount) VALUES ($user_id, $total_amount)";
    if ($conn->query($sql)) {
        $order_id = $conn->insert_id; // Get the last inserted order ID

        // Insert order items
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $sql = "SELECT price FROM products WHERE id = $product_id";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $price = $row['price'];

            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $product_id, $quantity, $price)";
            $conn->query($sql);
        }

        // Clear the cart
        unset($_SESSION['cart']);

        // Redirect to the orders page with a success message
        header("Location: orders.php?message=ordered");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error placing order. Please try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Checkout</h1>
        <?php if (empty($_SESSION['cart'])): ?>
            <p class="text-center">Your cart is empty. <a href="index.php">Continue shopping</a></p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $product_id => $quantity) {
                        $sql = "SELECT * FROM products WHERE id = $product_id";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $subtotal = $row['price'] * $quantity;
                        $total += $subtotal;
                        echo '
                        <tr>
                            <td><img src="assets/images/' . $row['image'] . '" class="img-thumbnail" style="width: 100px; height: auto;" alt="' . $row['name'] . '"></td>
                            <td>' . $row['name'] . '</td>
                            <td>' . $quantity . '</td>
                            <td>$' . $row['price'] . '</td>
                            <td>$' . $subtotal . '</td>
                        </tr>';
                    }
                    ?>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Total</strong></td>
                        <td><strong>$<?php echo $total; ?></strong></td>
                    </tr>
                </tbody>
            </table>
            <form action="checkout.php" method="POST">
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Place Order</button>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>