<?php
include('function.php');
error_reporting(0);

$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$categories = getCategories($mysqli);
$products = getProducts($mysqli, $categoryFilter);
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
  <title>Gadget Tutor</title>
  <!-- Meta tags, viewport -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Stylesheets -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/owl.carousel.css">
  <link rel="stylesheet" href="assets/css/owl.transitions.css">
  <link rel="stylesheet" href="assets/css/slick.css">
  <link rel="stylesheet" href="assets/css/bootstrap-slider.min.css">
  <link rel="stylesheet" href="assets/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/switcher/css/switcher.css" media="all" />
  <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/red.css" title="red" media="all" data-default-color="true" />
  <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/orange.css" title="orange" media="all" />
  <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/blue.css" title="blue" media="all" />
  <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/pink.css" title="pink" media="all" />
  <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/green.css" title="green" media="all" />
  <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/purple.css" title="purple" media="all" />
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
  <style>
    /* Custom CSS for product cards */
    body {
      background-image: url('photo/okay.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      font-family: 'Lato', sans-serif;
      color: #333;
      margin: 0;
      padding: 0;
    }

    .product-item {
      margin-bottom: 30px;
      border: 1px solid #ddd;
      border-radius: 5px;
      transition: box-shadow 0.3s;
      height: 100%; /* Ensure the product items take full height */
    }

    .product-item:hover {
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    .product-item .product-image {
      height: 250px; /* Fixed height for the product image container */
      overflow: hidden; /* Ensure overflow is hidden to prevent stretching */
    }

    .product-item .product-image img {
      width: 100%;
      height: auto;
      object-fit: cover; /* Maintain aspect ratio and cover entire container */
      border-top-left-radius: 5px;
      border-top-right-radius: 5px;
    }

    .product-item .product-info {
      padding: 15px;
      background-color: #fff;
      border-top: 1px solid #ddd;
      border-bottom-left-radius: 5px;
      border-bottom-right-radius: 5px;
    }

    .product-item .product-info h6 {
      margin-top: 0;
      font-size: 18px;
      font-weight: bold;
    }

    .product-item .product-info p {
      font-size: 14px;
      color: #555;
      margin-bottom: 0;
    }

    .search-form {
      margin-bottom: 30px;
    }

    .search-form .input-group {
      display: flex;
      align-items: center; /* Align items vertically */
    }

    .search-form select {
      flex: 1;
      margin-right: 10px;
    }

    .search-form button {
      flex: 0 0 auto;
    }

    .products-section {
      padding: 50px 0;
    }

    .section-title {
      margin-bottom: 40px;
      text-align: center;
    }

    .section-title h2 {
      font-size: 36px;
      font-weight: bold;
      background: linear-gradient(90deg, #ff6a00, #ee0979);
      -webkit-background-clip: text;
      -webkit-text-fill-color: white;
      padding: 10px 20px;
      display: inline-block;
      border-radius: 5px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
      animation: glow 1.5s infinite;
    }

    @keyframes glow {
      0% {
        text-shadow: 0 0 5px #ff6a00, 0 0 10px #ff6a00, 0 0 20px #ff6a00, 0 0 40px #ee0979, 0 0 80px #ee0979;
      }
      50% {
        text-shadow: 0 0 10px #ff6a00, 0 0 20px #ff6a00, 0 0 30px #ff6a00, 0 0 60px #ee0979, 0 0 120px #ee0979;
      }
      100% {
        text-shadow: 0 0 5px #ff6a00, 0 0 10px #ff6a00, 0 0 20px #ff6a00, 0 0 40px #ee0979, 0 0 80px #ee0979;
      }
    }

    .toggle-button {
      text-align: center;
      margin-bottom: 20px;
    }

    .list-view .product-item {
      display: flex;
      flex-direction: row;
    }

    .list-view .product-item .product-image,
    .list-view .product-item .product-info {
      flex: 1;
    }

    .list-view .product-item .product-image img {
      max-width: 150px;
      margin-right: 15px;
    }

    @media (max-width: 768px) {
      .product-item {
        margin-bottom: 30px;
      }
      .list-view .product-item {
        flex-direction: column;
      }
      .list-view .product-item .product-image img {
        max-width: 100%;
        margin-right: 0;
      }
    }

    .custom-button {
      display: inline-block;
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      text-align: center;
      border-radius: 5px;
      font-size: 16px;
      font-weight: bold;
      transition: background-color 0.3s, transform 0.3s;
    }

    .custom-button:hover {
      background-color: #0056b3;
      transform: scale(1.05);
    }
  </style>

</head>
<body>

<?php include('includes/headermain.php'); ?>
<div class="ts-main-content">

  <section id="products" class="products-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12 section-title">
          <h2>Gadget Details</h2>
        </div>
      </div>

      <!-- Search form -->
      <div class="row justify-content-center search-form">
        <div class="col-md-8 col-lg-6">
          <form method="GET">
            <div class="input-group">
              <select name="category" id="category" class="form-control">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                  <option value="<?= htmlspecialchars($category['C_Name']); ?>" <?= ($category['C_Name'] == $categoryFilter) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($category['C_Name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Search</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Display products based on search -->
      <div class="row justify-content-center product-list">
        <?php if (count($products) > 0): ?>
          <?php foreach ($products as $product): ?>
            <div class="col-md-4 col-sm-6">
              <div class="product-item">
                <div class="product-image">
                  <img src="admin/<?= htmlspecialchars($product->P_Image); ?>" alt="Product Image">
                </div>
                <div class="product-info">
                  <h6><?= htmlspecialchars($product->P_Name); ?></h6>
                  <p><strong>Description:</strong> <?= htmlspecialchars($product->P_Desc); ?></p>
                  <div class="text-center">
                  <br>
                    <a href="user_login.php" class="custom-button">Get More Details?</a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-md-12 text-center"><p>No products found.</p></div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <div id="back-top" class="back-top">
    <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i></a>
  </div>

</div>

<!-- Scripts -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/interface.js"></script>
<script src="assets/switcher/js/switcher.js"></script>
<script src="assets/js/bootstrap-slider.min.js"></script>
<script src="assets/js/slick.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>

</body>
</html>
