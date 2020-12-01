<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

function debugToConsole($msg) { 
    echo "<script>console.log(".json_encode($msg).")</script>";
}

$con = mysqli_connect("localhost:3306", "root", "","demo");

if (isset($_POST["remove"])){
    debugToConsole($_GET["id"]);
    $id = $_GET["id"];
    $sql = "DELETE FROM product WHERE id = '".$_GET["id"]."'";
    $con->query($sql);
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; background-color: grey;}
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
            <a class="nav-link disabled" style="color:rgb(218, 198, 18)" href="#" tabindex="-1" aria-disabled="true">User: <?php echo htmlspecialchars($_SESSION["username"]);?></a>
        </div>
        </div>
    </nav>
    <div class="container" style="width: 85%; text-align: center;">
        <?php
            $query = "SELECT * FROM product WHERE seller = '".$_SESSION["username"]."' ORDER BY id ASC ";
            $result = mysqli_query($con,$query);
            mysqli_close($con);
            if(mysqli_num_rows($result) > 0) {

                while ($row = mysqli_fetch_array($result)) {

                    ?>
                    <div class="column">

                        <form method="post" action="my-listings.php?action=remove&id=<?php echo $row["id"]; ?>">

                            <div class="product" class="row" style="margin-top: 5px;">
                                <img src="<?php echo $row["image"]; ?>" class="img-fluid" alt="product image">
                                <h5 class="text-dark"><?php echo $row["pname"]; ?></h5>
                                <h5 class="showPrice" class="text-dark"><?php echo "$"; echo $row["price"]; ?></h5>
                                <h5 class="showPrice" class="text-dark"><?php echo "Stock: "; echo $row["stock"]; ?></h5>
                                <input type="text" name="quantity" class="form-control" value="1">
                                <input type="hidden" name="hidden_name" value="<?php echo $row["pname"]; ?>">
                                <input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>">
                                <input type="submit" name="remove" style="margin-top: 5px;" class="btn btn-danger"
                                       value="Remove Listing">
                            </div>
                        </form>
                    </div>
                    <?php
                }
            }
			else
			{
				echo '<div>
					  <img src="logoIcon.png" alt="logo icon" width="400" height="300">
					  </div>';
				echo '<span style="color:red; border-style: double; font-size: 20pt; position: absolute; top: 50%; left: 50%; transform: translateX(-50%);">No Current Listings: Please click on "List Product" to start selling!</span>';
			}
        ?>

    </div>
</body>
</html>