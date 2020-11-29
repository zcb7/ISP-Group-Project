<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$product = $image_url = $price = "";
$product_err = $image_err = $price_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["new_product"]))){
        $product_err = "Please enter the new product.";     
    } elseif(strlen(trim($_POST["new_product"])) < 6){
        $product_err = "Product must have atleast 6 characters.";
    } else{
        $product = trim($_POST["new_product"]);
    }
    
    // Validate image URL
    if(empty(trim($_POST["image_url"]))){
        $image_err = "Please enter the image URL.";     
    } elseif(strlen(trim($_POST["image_url"])) < 6){
        $image_err = "Image URL must have atleast 6 characters.";
    } else{
        $image_url = trim($_POST["image_url"]);
    }

    // Validate price
    if(empty(trim($_POST["price"]))){
        $price_err = "Please enter the price.";     
    } elseif(trim($_POST["price"]) < 0){
        $price_err = "Price must be greater than 0.";
    } else{
        $price = trim($_POST["price"]);
    }
        
    // Check input errors before inserting in database
    if(empty($product_err) && empty($image_err) && empty($price_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO product (pname, image, price, seller) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_product, $param_image, $param_price, $param_seller);

            $param_product = $product;
            $param_image = $image_url;
            $param_price = $price;
            $param_seller = $_SESSION["username"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; background-color: #e4b61a;}
        .wrapper{ width: 350px; padding: 20px; margin: auto; border-style: double; border-width: thick;}
        .cart-icon
        {
            color:black;
            
        }

        .cart-icon:hover
        {
            color:rgb(218, 198, 18);
            
        }

        .cart-icon:active
        {
            color:rgb(218, 198, 18);
            
        }
    </style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
		<div class="navbar-header">
		<a class="navbar-brand" href="welcome.php" style="color: #fcc602;">Universal Mart</a>
		</div>
            <a class="nav-link" href="list-item.php">List Product</a>
            <a class="nav-link" href="my-listings.php">My Listings</a>
            <a class="nav-link" href="reset-password.php">Reset Password</a>
            <a class="nav-link" href="logout.php">Logout</a>
            <a class="nav-link disabled" style="color:rgb(218, 198, 18)" href="#" tabindex="-1" aria-disabled="true">User: <?php echo htmlspecialchars($_SESSION["username"]); ?></a>
        </div>
        </div>
</nav>
    <div class="wrapper">
        <h2>List Product</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($product_err)) ? 'has-error' : ''; ?>">
                <label>Product Name</label>
                <input name="new_product" class="form-control" value="<?php echo $product; ?>">
                <span class="help-block"><?php echo $product_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($image_err)) ? 'has-error' : ''; ?>">
                <label>Image URL</label>
                <input name="image_url" class="form-control">
                <span class="help-block"><?php echo $image_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($price_err)) ? 'has-error' : ''; ?>">
                <label>Price ($)</label>
                <input name="price" class="form-control">
                <span class="help-block"><?php echo $price_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-dark" value="Submit">
                <a class="btn btn-link" href="welcome.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>