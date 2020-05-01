<!-- moredetails.php-->
<html>
<center>
<body bgcolor='FFA07A'>					<!-- set the background color as light salmon to match theme -->


  <?php
// Connecting to the Mariadb Server
  try
  {
    $dsn1 = "mysql:host=courses;dbname=z1853066";	// Connect to database on MariaDB using this login credentials
    include("pswrds.php"); 				// include pswrds.php to match the given password

    $pdo1 = new PDO($dsn1, $username1, $password1);	// use credentials username1 and password1 to establish connection
  }

  catch (PDOexeption $exception1)
  {
    echo "Database connection failed: " . $exception1->getMessage();	// print this error message if connection is unsuccessful
  

}

  try
  {
    $connection2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";	// connect to blitz.cs.niu.edu for legacy DB
    include("pswrds.php"); 						// include pswrds.php to match the given password
    $pdo2 = new PDO($connection2, $username2, $password2);		// use credentials username2 and password2 to establish connection
  }

  catch (PDOexception $exception2)
  {
    echo "Database connection failed: " . $exception2->getMessage();	// print this error message if connection is unsuccessful
  }

  //initialzize the cart
  $cartitems = array();				// establish an array to hold user's cart items

  if (array_key_exists('cart', $_REQUEST))	// Make sure the cart is populated
  {
    $cartitems = unserialize(base64_decode($_REQUEST['cart'])); 	// unserialze the cart to be used
  }

  if (array_key_exists('pnum', $_REQUEST))			// check to see if item is there
  {
    $chosen_item = unserialize(base64_decode($_REQUEST['pnum'])); 	// get the part number of item to display
  }

  else
  {		// display part number, description, price, weight and picture
    $chosen_item = array("number"=>"-1", "description"=>"NO ITEM CHOSEN", "price"=>"-1", "weight"=>"-1", "https://www.google.com/url?sa=i&source=images&cd=&ved=2ahUKEwiE0Pb2ht7lAhVHLKwKHcwBA-MQjRx6BAgBEAQ&url=https%3A%2F%2Fwww.vectorstock.com%2Froyalty-free-vector%2Fnot-available-flat-icon-vector-12770007&psig=AOvVaw3CzGK2ABM54vY1_P-FI8gw&ust=1573420670639053"=>"pictureURL");
  }

  if (isset($_POST['addcart'])) 
  {
    $citem = array("prdctnum"=>$chosen_item['number'], "qntty"=>$_POST['quantity']);
    array_push($cartitems, $citem);		// if more items are added to cart, they get placed into array
  }
  ?>

  <head><title> Additional Product Information
    </title>
  <head>

  <?php
  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/catalogpage.php>"; // submit data to invoiceview.php using POST method

    $a2s= base64_encode(serialize($cartitems));		// re-serialize items in cart
    echo "<input type=hidden name='cart'
                 value=$a2s/>";
    echo "<input type=submit name='button3'
                 value='Return to Catalog'/>";		// go back to product catalog
  echo "</form>"; 

  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/cartpage.php>"; // return to cart if user adds an item and wants to go back to cart
    echo "<input type=hidden name='pnum' 
                 value=$_REQUEST[pnum]/>";
    $a2s= base64_encode(serialize($cartitems)); 	// re-serialize the cart
    echo "<input type=hidden name='cart' //
                 value=$a2s/>";
    echo "<input type=submit name='button4' 
                 value='Your Cart'/>"; 			// button for user to view the contents of their cart
  echo "</form>"; //end of moving to cart
  ?>

  <h1>Additional Product Information</h1>
Thank You For Shopping With Us
<br>
For more information during COVID-19 Please Contact US!		<!-- footer -->
<br><br>






  <?php
    //get the quantity for the item
    $sql1 = "SELECT quantity FROM inventory WHERE productID = $chosen_item[number]"; 	// get the quantity available for this item
    $query1 = $pdo1->query($sql1); 
    $rows = $query1->fetchAll(PDO::FETCH_ASSOC); 		// for all items in inventory
    $numitems = $rows[0]['quantity'];

    echo "<img src=$chosen_item[pictureURL]
           style=width:500px;height=500px>";			// table and cell formatting
	echo "<br><br>";
    echo "<table border=3,cellspacing=10, cellpadding=3>";
echo '<tbody style="background-color:#FF726f">';		// set table color to peach red


      echo "<tr>"; 
        echo "<th>Product #</th>";				// These identify the table's column headers
        echo "<th>Product Description</th>";
        echo "<th>Product Price ($)</th>";
        echo "<th>Product Weight (lbs) </th>";
        echo "<th>Amount In Stock</th>";
      echo "</tr>";

      echo "<tr>";
        echo "<td>$chosen_item[number]</td>"; 			// product number
        echo "<td>$chosen_item[description]</td>"; 		// product descpirtion
        echo "<td>$chosen_item[price]</td>"; 			// price
        echo "<td>$chosen_item[weight]</td>"; 			// weight of product
        echo "<td>$numitems</td>"; 				// quantity of product available
      echo "</tr>";
    echo "</table>";						// end of table
  ?>

  <?php
  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/moredetails.php>"; // submit data using POST method
    echo "<input type=hidden name='pnum'
                 value=$_REQUEST[pnum]/>"; 			// show valid part number
    echo "<input type=hidden name='cart'
                 value=$_REQUEST[cart]/>";				

    $found = False;						// set initial condition to be false

    foreach($cartitems as $cartcheck)
    {
      if ($cartcheck['prdctnum'] == $chosen_item['number'])	// if cart's item number was found, set condition as true
      {
        $found = True;
      }
    }

    if ($found == False)
    {
      echo "<h5>Quantity You Would Like To Purchase:</h5>";	// asks the user how many items of this part they would like to add
      echo "<input type=number name='quantity' 			// creating a var called quantity for user to enter
                 min=1 max='$numitems'/>";			// adds the specified quantity of that item to user's cart
      echo "<input type=submit name='addcart'
                 value='Add To Your Cart'/>"; //
    }

    else
    {
      echo "You added this item to your cart. Please edit your decision there.";	// message for if user would like to edit their cart
    }
    echo "</form>";						// end of form
  ?>

</html>


<footer>

Thank You For Shopping With Us
<br>
For more information during COVID-19 Please Contact US!		<!-- footer -->
<br>
CSCI 467 - Group 1A

</footer>

