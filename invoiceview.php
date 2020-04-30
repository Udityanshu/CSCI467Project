<!-- invoice.php -->


<html><head><title>Invoice View Group 1A
    </title>
  </head>
<center>
<body bgcolor='FFA07A'>	// test 
<h1> Invoice View Please Enter An Order ID to Retrieve Information</h1>
  <?php
    require("connection.php"); //using conn.php file

    //try adding form to select order id:
    echo '<form action="invoiceview.php" method="post">'; //resubmiting back to invoice.php
      echo 'Order ID: <input type=text name=orderid id=orderid><br>'; //input box for order id number
      echo '<br><input type="submit" name="submit" value="Submit"><br>'; //submit button
    echo '</form>';

echo "<h2> Order Details:</h2>";
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $orderid = $_POST['orderid'];
        // 
	// The select staement to show items ordered for the ID given
        $statement= $pdo1->query('select * from ordereditems where orderID = '.$orderid);
        echo '<table border=5, cellspacing=5, cell padding=5>';
	echo '<tbody style="background-color:#FF726f">';
	//table settings and colors

        echo '<th>Order ID</th>';
        echo '<th>Product Name</th>';
        echo '<th>Unit Price ($)</th>';
        echo '<th>Amount On Hand</th>';

        //displaying the information from the query in the table
        while($row = $statement->fetch(PDO::FETCH_ASSOC))
        {
          $productid = $row['productID'];
          $stmtgetdescription = $pdo2->query('select * from parts where number = '.$productid);
          // 'select quantity from orderedItems where orderID'

          while($description = $stmtgetdescription->fetch(PDO::FETCH_ASSOC))
          {
            echo '<tr><td>';
            echo $row['orderID']; //order id num
            echo '</td><td> ';
            echo $description['description']; //prodcut name
            echo '</td><td> ';
            echo $description['price']; //price of products
            echo '</td><td> ';
            echo $row['quantity']; //amount in store cna be changed in admin view
            echo '</td></tr>';
          }

        } 

        echo '</table>';
	echo "<br><br>";
	echo "<h2>Total Order Stats:</h2>";



//Total Statistics for total order information here in this table
        $statement2 = $pdo1->query('select * from orders where ordersID = '.$orderid);
        while($row2 = $statement2->fetch(PDO::FETCH_ASSOC))
        {
            echo '<table border=5,cellspacing=5,cellpadding=5>';
	    echo '<tbody style="background-color:#FF726f">';
		//settings for the table color and size eetc

            echo '<tr><th>Net Price Of Products ($)</th><td>';
            echo $row2['totalprice'];
            echo '</td></tr>';

            echo '<tr><th>Shipping and Handling Fees</th><td>';
            echo $row2['addfees'];
            echo '</td></tr>';

            echo '<tr><th>Total Price ($)</th><td>';
            echo $row2['finalprice'];
            echo '</td></tr>';

            echo '</table>';
        }

    }
   
  ?>
</html>


<footer>
<br><br><br><br><br><br><br><br><br><br><br><br>
Thank You For Shopping With Us
<br>
For more information during COVID-19 Please Contact US!
<br>
CSCI 467 - Group 1A

</footer>

