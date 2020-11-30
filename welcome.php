<?php
// Start the Session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

function debugToConsole($msg) { 
    echo "<script>console.log(".json_encode($msg).")</script>";
}

//Sets Connection
$con = mysqli_connect("localhost:3306", "root", "","demo");

//If Post Was Made to Add to Cart
if (isset($_POST["add"])){
    if (isset($_SESSION["cart"])){
        $item_array_id = array_column($_SESSION["cart"],"product_id");
        
	if (!in_array($_GET["id"],$item_array_id)){
            $count = count($_SESSION["cart"]);
            $item_array = array(
                'product_id' => $_GET["id"],
                'item_name' => $_POST["hidden_name"],
                'product_price' => $_POST["hidden_price"],
                'item_quantity' => $_POST["quantity"],
            );

            $_SESSION["cart"][$count] = $item_array;
            echo '<script>window.location="welcome.php"</script>';
        }
	
	else{
            $index = array_search($_GET["id"], $item_array_id);
            $quantity = $_SESSION["cart"][$index]["item_quantity"];
            $_SESSION["cart"][$index]["item_quantity"] = $quantity + $_POST["quantity"];
            echo '<script>window.location="welcome.php"</script>';
        }
    }

    else{
        $item_array = array(
            'product_id' => $_GET["id"],
            'item_name' => $_POST["hidden_name"],
            'product_price' => $_POST["hidden_price"],
            'item_quantity' => $_POST["quantity"],
        );

        $_SESSION["cart"][0] = $item_array;
    }
}

//If an Action was Made
if (isset($_GET["action"])){
    
	if ($_GET["action"] == "delete"){
        foreach ($_SESSION["cart"] as $keys => $value){
            
	if ($value["product_id"] == $_GET["id"]){
                unset($_SESSION["cart"][$keys]);
                echo '<script>window.location="welcome.php"</script>';
            }
        }
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; background-color: #e4b61a;}
        @import url('https://fonts.googleapis.com/css?family=Titillium+Web');

        *{
            font-family: 'Titillium Web', sans-serif;
        }
        .product{
            border: 2px solid black;
            margin: -1px 19px 3px -1px;
            padding: 10px;
            text-align: center;
            background-color: #fcc602;
        }
        table, th, tr{
            text-align: center;
            border: 2px solid black;
        }
        .title2{
            text-align: center;
            color: #fcc602;
            background-color: #fcc602;
            padding: 2%;
			border-style: solid;
        }
        h2{
            text-align: center;
            color: #fcc602;
            background-color: #fcc602;
            padding: 2%;
			border-style: solid;
        }
        table th{
            background-color: #fcc602;
            border: 2px solid black;
        }
        .column{
            width: calc(100% / 5);
            display: inline-block;
            
        }
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
            <a class="nav-link disabled" style="color:#fcc602" href="#" tabindex="-1" aria-disabled="true">User: <?php echo htmlspecialchars($_SESSION["username"]);?></a>
        </div>
        </div>
		<div>
		<img src="logoIcon.png" alt="logo icon" width="50" height="50">
		</div>
    </nav>
    <div class="container" style="width: 80%;border-style: double;">
	<h2 style="color: black; text-decoration: underline;">Items:</h2>
        <?php
            $query = "SELECT * FROM product ORDER BY id ASC ";
            $result = mysqli_query($con,$query);
            mysqli_close($con);
            if(mysqli_num_rows($result) > 0) {

                while ($row = mysqli_fetch_array($result)) {

                    ?>
                    <div class="column">

                        <form method="post" action="welcome.php?action=add&id=<?php echo $row["id"]; ?>">

                            <div class="product" class="row" style="margin-top: 5px;">
                                <img src="<?php echo $row["image"]; ?>" class="img-fluid" alt="product image">
                                <h5 class="text-dark"><?php echo $row["pname"]; ?></h5>
                                <h5 class="showPrice" style="color: #FF4500;"><?php echo "$"; echo $row["price"]; ?></h5>
                                <input type="text" name="quantity" class="form-control" value="1">
                                <input type="hidden" name="hidden_name" value="<?php echo $row["pname"]; ?>">
                                <input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>">
                                <input type="submit" name="add" style="margin-top: 5px;" class="btn btn-dark" value="Add to Cart">
                            </div>
                        </form>
                    </div>
                    <?php
                }
            }
        ?>

        <div style="clear: both"></div>
        <h3 class="title2" style="color: black; text-decoration: underline;">Cart:</h3>
        <div class="table-responsive">
            <table class="table table-bordered">
            <tr>
                <th width="30%">Product Name</th>
                <th width="10%">Quantity</th>
                <th width="13%">Price Details</th>
                <th width="10%">Total Price</th>
                <th width="17%">Remove Item</th>
            </tr>

            <?php
                if(!empty($_SESSION["cart"])){
                    $total = 0;
                    foreach ($_SESSION["cart"] as $key => $value) {
                        ?>
                        <tr>
                            <td><?php echo $value["item_name"]; ?></td>
                            <td><?php echo $value["item_quantity"]; ?></td>
                            <td>$ <?php echo $value["product_price"]; ?></td>
                            <td>
                                $ <?php echo number_format($value["item_quantity"] * $value["product_price"], 2); ?></td>
                            <td><a href="welcome.php?action=delete&id=<?php echo $value["product_id"]; ?>"><span
                                        class="text-danger">Remove Item</span></a></td>

                        </tr>
                        <?php
                        $total = $total + ($value["item_quantity"] * $value["product_price"]);
                    }
                        ?>
 						<?php
						$couponValid = "No Coupon Code Applied.";
						$couponBool = false;
						if(isset($_POST['submit']))
						{
							switch($_POST['promo'])
							{
								//$5 Off coupon code
								case 'freeFive':
								$total = $total - 5;
								$couponBool = true;
								break;
								//20% Off coupon code
								case '20Off':
								$total = $total - (.2 * $total);
								$couponBool = true;
								break;
								
								default:
								$couponBool = false;
								break;
							}
						}
						if (isset($_POST['submit']))
						{
							if ($couponBool == true)
							{
								echo '<span style="color:#6e6;text-align:center;">Coupon Code: </span>';
								echo $_POST['promo'];
								echo '<span style="color:#6e6;text-align:center;"> has been applied. </span>';
							}
							else
							{
								echo '<span style="color:#ff5932;text-align:center;"> Invalid Coupon Code. (case-sensitive)</span>';
							}

						}
						else
						{
							//if user has not entered a coupon code yet, display "no coupon code applied"
							echo '<span style="color:#ff5932;text-align:center;">' .$couponValid. '</span>';
						}
						?>
                        <tr>
							<td colspan="0" align="right">Coupon Code (1 Per Order):</td>
							<th align="right">
							<!--form to enter coupon code -->
							<form method="post" action="welcome.php">
							<input type="text" id="promo" name="promo">
							<input type="submit" name="submit" value="submit">
							</form>
							</th>
                            <td colspan="1" align="right">Total</td>
                            <th align="right">$ <?php echo number_format($total, 2); ?></th>
                            <td></td>
                        </tr>
                        <?php
                    }
                    //print_r($_SESSION);
                ?>
            </table>
        </div>

    </div>
</body>
</html>