

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="assets\stylesheets\style.css"> -->
    
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="assets\stylesheets\style.css">

</head>
<body>
    <!-- <h1>Hello</h1> -->
    <!-- Navbar -->
    <div class="container-fluid p-0">
        <!-- First Child -->
        
        <nav class="navbar navbar-expand-lg fixed-top bg-body-tertiary">
        <div class="container-fluid">
            <img class = "logo" src="./images/logo.png" alt="">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">Products</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#"><i class = 'bx bx-cart' id = "cart-icon"><sup>1</sup></i></a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">Total Price 250/-</a>
                </li>
                
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button> 
            </form>
            </div>
        </div>
    </nav>
    <!-- Second Child-->
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
        <ul class="navbar-nav me-auto">
                <li class="nav-item">
                <a class="nav-link" href="#">Welcome Guest</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="#">Login</a>
                </li>

        </ul>
    </nav>
    <!-- Third Child -->
    <div class="bg-light">
        <h3 class="text-center">PRODUCTS</h3>
    </div>
    <!-- Fourth Child -->
    <div class="row">
        <div class="col-md-10"></div>
        <div class="row">
            <div class="col-md-4 mb-2"><div class="card">
<img src="./images/product_2.jpg" class="card-img-top" alt="...">
<div class="card-body">
    <h5 class="card-title">Card title</h5>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
    <a href="#" class="btn btn-warning">Add to Cart</a>
</div>
</div></div>
            <div class="col-md-4 mb-2"><div class="card">
<img src="./images/product_3.jpg" class="card-img-top" alt="...">
<div class="card-body">
    <h5 class="card-title">Card title</h5>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
    <a href="#" class="btn btn-warning">Add to Cart</a>
</div>
</div></div>
            <div class="col-md-4 mb-2"><div class="card">
  <img src="./images/product_1.jpg" class="card-img-top" alt="Oops">
  <div class="card-body">
    <h5 class="card-title">Card title</h5>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
    <a href="#" class="btn btn-warning">Add to Cart</a>
  </div>
</div></div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-2"><div class="card">
<img src="./images/product_4.jpg" class="card-img-top" alt="...">
<div class="card-body">
    <h5 class="card-title">Card title</h5>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
    <a href="#" class="btn btn-warning">Add to Cart</a>
</div>
</div></div>
            <div class="col-md-4 mb-2"><div class="card">
<img src="./images/product_5.jpg" class="card-img-top" alt="...">
<div class="card-body">
    <h5 class="card-title">Card title</h5>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
    <a href="#" class="btn btn-warning">Add to Cart</a>
</div>
</div></div>
            <div class="col-md-4 mb-2"><div class="card">
  <img src="./images/product_6.jpg" class="card-img-top" alt="Oops">
  <div class="card-body">
    <h5 class="card-title">Card title</h5>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
    <a href="#" class="btn btn-warning">Add to Cart</a>
  </div>
</div></div>
        </div>
        <div class="col-md-2"></div>
    </div>
    <!-- Last child -->
    <div class="bg-body-tertiary p-3 text-center">All rights are reserved ©- Designed by Varsha-2023</div>
    </div>
<!-- <header>
    
    
  <div class = "nav head_container">
    <a href="#" class = "logo">Vendor Ecommerce</a>
    <i class = 'bx bx-cart' id = "cart-icon"></i>
  </div>
  <?php
    // echo"<pre>";print_r($_);echo "</pre>";exit();
    session_start();
    if (array_key_exists('fld_ai_id', $_COOKIE)) {
        $_SESSION['fld_ai_id'] = $_COOKIE['fld_ai_id'];
        echo "<h1>Welcome ".$_SESSION['fld_name']."</h1>";
    }
    if (array_key_exists('fld_ai_id', $_COOKIE) || array_key_exists('fld_ai_id', $_SESSION)) {
        echo "<div class = 'logout'><button class = 'button-logout'><a href = index.php?logout=1>Log out</a></button></div>";
    }
    else {
        header("Location: index.php");
    }
    // echo"<pre>";print_r($_SESSION);echo "</pre>";
    // echo"<pre>";print_r($_COOKIE);echo "</pre>";

    ?> 
  </header> -->

    <!-- <section class="shop container">
        <h2 class="section-title">Products</h2>
        <div class ="shop-content">
            <div class="product-box">
                <img src="images/product_1.jpg" alt="" class="product-img">
            </div>
        </div>
    </section> -->
    <!-- <a href="index.php">Back</a> -->
    <!-- <a href="login.php?logout = 1">Log out</a> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>