<?php
session_start(); // Start the session
include 'includes/db.php';

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch order details
$order_id = $_GET['order_id'];
$sql = "SELECT * FROM orders WHERE id = $order_id AND user_id = {$_SESSION['user_id']}";
$result = $conn->query($sql);
$order = $result->fetch_assoc();

// Fetch order items
$sql = "SELECT order_items.*, products.name, products.image FROM order_items
        JOIN products ON order_items.product_id = products.id
        WHERE order_items.order_id = $order_id";
$items = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Order Details</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Order ID: <?php echo $order['id']; ?></h5>
                <p class="card-text">Total Amount: $<?php echo $order['total_amount']; ?></p>
                <p class="card-text">Status: <?php echo ucfirst($order['status']); ?></p>
                <p class="card-text">Date: <?php echo $order['created_at']; ?></p>
            </div>
        </div>
        <h3 class="mt-4">Order Items</h3>
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
                <?php while ($item = $items->fetch_assoc()): ?>
                    <tr>
                        <td><img src="assets/images/<?php echo $item['image']; ?>" class="img-thumbnail" style="width: 100px; height: auto;" alt="<?php echo $item['name']; ?>"></td>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo $item['price']; ?></td>
                        <td>$<?php echo $item['price'] * $item['quantity']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>