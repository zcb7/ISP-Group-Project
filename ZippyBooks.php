<!-- ZippyBooks.php
     A PHP Script That Creates a Website Using PHP and a Database
-->
<html>
<head>
    <title> Allan's ZippyBook's Bookstore </title>
    <style type = "text/css">
    td, th, table {
		border: thick solid black;
	}
	table.center {
		margin-left: auto; 
		margin-right: auto;
		background-color: lightblue;
	}
	h2 {
		font-size: 50px;
	}
	body {
		background-color: orange;
	}
	p {
		font-weight: bold;
		font-size: 20px;
	}
    </style>
</head>
<body>
	<form action = "ZippyBooks.php" method = "post">
		<h2 style="text-align:center;"> ZippyBook's Bookstore </h2>
		<p>
		<table class="center">
			<tr>
				<th> Book_id </th>
				<th> Book_title </th>
				<th> Book_price </th>
				<th> Book_quantity </th>
				<th> Book_flag </th>
			</tr>
			<tr>
				<td><input style="text-align:center" type = "text"  name = "id" size = "16" value = "0" /></td>
				<td><input style="text-align:center" type = "text"  name = "title" size = "16" value = "N/A Title" /></td>
				<td><input style="text-align:center" ype = "text"  name = "price" size = "16" value = "0" /></td>
				<td><input style="text-align:center" type = "text"  name = "quantity" size = "16" value = "0" /></td>
				<td><input style="text-align:center" type = "text"  name = "flag" size = "16" value = "0" /></td>
			</tr>
		</table>
		</p>
      
		<p style="text-align:center;">
			<input type = "radio"  name = "action"  value = "display" checked = "checked" /> Display All Records
			<input type = "radio"  name = "action"  value = "insert" /> Add a New Record
			<input type = "radio"  name = "action"  value = "update" /> Update Price
			<input type = "radio"  name = "action"  value = "delete" /> Delete an Existing Record
			<br><br>
			<input type = "reset"  value = "Reset Form" />
			<input type = "submit"  value = "Execute SQL" />
		</p>
	</form>

	<?php
    
		// Get input data
		$id = $_POST["id"] ?? ' ';
		$title = $_POST["title"] ?? ' ';
		$price = $_POST["price"] ?? ' ';
		$quantity = $_POST["quantity"] ?? ' ';
		$flag = $_POST["flag"] ?? ' ';
		$action = $_POST["action"] ?? "display";
    
		// Set Any Blank Values to Zero
		if ($id == "") $id = 0;
		if ($price == "") $price = 0;
		if ($quantity == "") $quantity = 0;

		// Connect to MySQL
		$db = mysqli_connect("localhost:3306", "root", "","isp",);
		if (!$db) {
			print "Error - Could not connect to MySQL";
			exit;
		}

		// Select the database
		$er = mysqli_select_db($db,"isp");
		if (!$er) {
			print "Error - Could not select the database";
			exit;
		}

		// Prints the Action of the Query
		if($action == "display")
			$query = "";
		else if ($action == "insert")
			$query = "insert into Books values($id, '$title', $price, $quantity, $flag)";
		else if ($action == "update")
			$query = "update Books set price = $price where Book_id = $id";
		else if ($action == "delete")
			$query = "delete from Books where Book_id = $id";

		if($query != ""){
			trim($query);
			$query_html = htmlspecialchars($query);
			print "<p align='center'> <b> The query is: </b> " . $query_html . "<br /> </p>";   
			$result = mysqli_query($db,$query);
			
			if (!$result) {
				print "Error - the query could not be executed";
				$error = mysqli_error();
				print "<p>" . $error . "</p>";
			}
		}
    
		// Final Display of All Entries
		$query = "SELECT * FROM Books";
		$result = mysqli_query($db,$query);
		
		if (!$result) {
			print "Error - the query could not be executed";
			$error = mysqli_error();
			print "<p>" . $error . "</p>";
			exit;
		}

		// Gets and Prints the Number of Rows(Books)
		$num_rows = mysqli_num_rows($result);
		print "<table class='center'><caption> <h2 style='text-align:center;'> Total Books ($num_rows) </h2> </caption>";
		print "<tr align = 'center'>";

		$row = mysqli_fetch_array($result);
		$num_fields = mysqli_num_fields($result);

		// Produce the Columns' Labels Based on the Key(Book_id)
		$keys = array_keys($row);
		for ($index = 0; $index < $num_fields; $index++) 
			print "<th>" . $keys[2 * $index + 1] . "</th>";
		print "</tr>";
    
		// Outputs the Values of All Fields in the Rows
		for ($row_num = 0; $row_num < $num_rows; $row_num++) {
			print "<tr align = 'center'>";
			$values = array_values($row);
			for ($index = 0; $index < $num_fields; $index++){
				$value = htmlspecialchars($values[2 * $index + 1]);
				print "<th>" . $value . "</th> ";
			}
		print "</tr>";
			$row = mysqli_fetch_array($result);
		}
		print "</table>";
	?>
</body>
</html>
