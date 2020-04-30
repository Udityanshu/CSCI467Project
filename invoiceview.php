<!-- invoice.php -->


<html><head><title>Invoice View Group 1A
    </title>
  </head>
<center>
<body bgcolor='FFA07A'>	<!-- set the background color as light salmon to match theme --> 
<h1> Invoice View Please Enter An Order ID to Retrieve Information</h1>
  <?php
    require("connection.php"); // this file uses data from database (info in connection.php file)

    
    echo '<form action="invoiceview.php" method="post">'; // submit data to invoiceview.php using POST method
      echo 'Order ID: <input type=text name=orderid id=orderid><br>'; // field to input order id number
      echo '<br><input type="submit" name="submit" value="Submit"><br>'; // Button for worker to submit order number
    echo '</form>';

echo "<h2> Order Details:</h2>";
    if($_SERVER['REQUEST_METHOD'] == 'POST')	// check if data is being sent using POST method
    {
        $orderid = $_POST['orderid'];		// set order id to its equivalent
        $statement= $pdo1->query('select * from ordereditems where orderID = '.$orderid);  // pull item from DB where ID matches 
        echo '<table border=5, cellspacing=5, cell padding=5>';		// table and cell formatting
	echo '<tbody style="background-color:#FF726f">';		// table cell color set to peach red

        echo '<th>Order ID</th>';					// sets the column headers of the table to
        echo '<th>Product Name</th>';					// these titles
        echo '<th>Unit Price ($)</th>';
        echo '<th>Amount On Hand</th>';
	    
        while($row = $statement->fetch(PDO::FETCH_ASSOC))		// fetch information for query in the table
        {
          $productid = $row['productID'];
          $stmtgetdescription = $pdo2->query('select * from parts where number = '.$productid);
          // display the part from DB with the appropriate order ID

          while($description = $stmtgetdescription->fetch(PDO::FETCH_ASSOC))
          {
            echo '<tr><td>';
            echo $row['orderID']; // display the order ID into the table
            echo '</td><td> ';
            echo $description['description']; // put items description into the column for description
            echo '</td><td> ';
            echo $description['price']; // display the unit price in $
            echo '</td><td> ';
            echo $row['quantity']; // how much of this item is available in stock is displayed here
            echo '</td></tr>';
          }

        } 

        echo '</table>';
	echo "<br><br>";
	echo "<h2>Total Order Stats:</h2>";	// display below the total price and shipping fees for the order



        $statement2 = $pdo1->query('select * from orders where ordersID = '.$orderid);	// display the part from DB with the appropriate order ID
        while($row2 = $statement2->fetch(PDO::FETCH_ASSOC))	// fetch information for query in the table
        {
            echo '<table border=5,cellspacing=5,cellpadding=5>';	// table and cell formatting
	    echo '<tbody style="background-color:#FF726f">';		// table cell color set to peach red

            echo '<tr><th>Net Price Of Products ($)</th><td>';		// sets the column headers of the table to these titles
            echo $row2['totalprice'];					// displays the net price of the order
            echo '</td></tr>';

            echo '<tr><th>Shipping and Handling Fees</th><td>';		// sets the column headers of the table to these titles
            echo $row2['addfees'];					// displays the shipping/handling fee amounts
            echo '</td></tr>';

            echo '<tr><th>Total Price ($)</th><td>';			// sets the column headers of the table to these titles
            echo $row2['finalprice'];					// displays the total price of order (net + shipping)
            echo '</td></tr>';

            echo '</table>';						// end table
        }

    }
   
  ?>
</html>


<footer>
<br><br><br><br><br><br><br><br><br><br><br><br>
Thank You For Shopping With Us
<br>
For more information during COVID-19 Please Contact US!			<!-- footer -->
<br>
CSCI 467 - Group 1A

</footer>

