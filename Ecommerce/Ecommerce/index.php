<?php include 'includes/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Featured Products</h1>
        <div class="row">
            <?php
     $sql = "SELECT * FROM products";
     $result = $conn->query($sql);
     
     if ($result->num_rows > 0) {
         while ($row = $result->fetch_assoc()) {
             echo '
             <div class="col-md-4 mb-4">
                 <div class="card product-card">
                     <img src="assets/images/' . $row['image'] . '" class="card-img-top" alt="' . $row['name'] . '">
                     <div class="card-body">
                         <h5 class="card-title">' . $row['name'] . '</h5>
                         <p class="card-text">$' . $row['price'] . '</p>
                         <a href="product.php?id=' . $row['id'] . '" class="btn btn-primary">View Product</a>
                     </div>
                 </div>
             </div>';
         }
     } else {
         echo "<p class='text-center'>No products found.</p>";
     }
     ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>