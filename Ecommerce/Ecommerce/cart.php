<?php
session_start(); // Start the session
include 'includes/db.php';

// Handle product removal
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]); // Remove the product from the cart
        header("Location: cart.php?message=removed");
        exit();
    }
}

// Display success message
if (isset($_GET['message'])) {
    if ($_GET['message'] == 'added') {
        echo "<div class='alert alert-success'>Product added to cart!</div>";
    } elseif ($_GET['message'] == 'removed') {
        echo "<div class='alert alert-warning'>Product removed from cart.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Your Cart</h1>
        <?php if (empty($_SESSION['cart'])): ?>
            <p class="text-center">Your cart is empty.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Action</th>
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
                            <td><a href="cart.php?remove=' . $row['id'] . '" class="btn btn-danger btn-sm">Remove</a></td>
                        </tr>';
                    }
                    ?>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Total</strong></td>
                        <td><strong>$<?php echo $total; ?></strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <div class="text-end">
                <a href="checkout.php" class="btn btn-primary">Checkout</a>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>