<html>
<center>
<body bgcolor='FFA07A'>


  <?php
// Connecting to the Mariadb Server
  try
  {
    $dsn1 = "mysql:host=courses;dbname=z1853066";
    include("Project8Apswrd.php"); //using logon  page

    $pdo1 = new PDO($dsn1, $username1, $password1);
  }
//Catch adn  display the error if there is one
  catch (PDOexeption $e1)
  {
    echo "Database connection failed: " . $e1->getMessage();
  

}
// Conncting to the server that holds data
  try
  {
    $dsn2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
    include("Project8Apswrd.php"); //using login page
    $pdo2 = new PDO($dsn2, $username2, $password2);
  }
//Catch and dispaly the error if there is one 
  catch (PDOexception $e2)
  {
    echo "Database connection failed: " . $e2->getMessage();
  }

  //initialzize the cart
  $cartcontents = array(); //make the array to be used holding data 

//Make surre the cart is populated
  if (array_key_exists('cart', $_REQUEST))
  {
    $cartcontents = unserialize(base64_decode($_REQUEST['cart'])); //unserialze the cart o be used
  }

  //check for the existence of an item
  if (array_key_exists('pnum', $_REQUEST))
  {
    $chosenitem = unserialize(base64_decode($_REQUEST['pnum'])); //retrieve the hidden product number to be used to display 
  }

  else
  {
    $chosenitem = array("number"=>"-1", "description"=>"NO ITEM CHOSEN", "price"=>"-1", "weight"=>"-1", "https://www.google.com/url?sa=i&source=images&cd=&ved=2ahUKEwiE0Pb2ht7lAhVHLKwKHcwBA-MQjRx6BAgBEAQ&url=https%3A%2F%2Fwww.vectorstock.com%2Froyalty-free-vector%2Fnot-available-flat-icon-vector-12770007&psig=AOvVaw3CzGK2ABM54vY1_P-FI8gw&ust=1573420670639053"=>"pictureURL");
  }

  if (isset($_POST['addcart'])) //from other page if added then push into array
  {
    $citem = array("prdctnum"=>$chosenitem['number'], "qntty"=>$_POST['quantity']);
    array_push($cartcontents, $citem);
  }
  ?>

  <head><title> Additional Product Information
    </title>
  <head>

  <?php
  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/catelog.php>"; //moving from details page back to catalog page
//based on sbutton go back to catalog and reserialze the carrt again
    $arraytostring1 = base64_encode(serialize($cartcontents));
    echo "<input type=hidden name='cart'
                 value=$arraytostring1/>";
    echo "<input type=submit name='button3'
                 value='Return to Catelog'/>";
  echo "</form>"; //end of going back to catalog page

  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/cart.php>"; //moving to cart if they added someetihng and then want to go to cart
    echo "<input type=hidden name='pnum' //save pnum
                 value=$_REQUEST[pnum]/>";
    $arraytostring1 = base64_encode(serialize($cartcontents)); //serizlize again
    echo "<input type=hidden name='cart' //
                 value=$arraytostring1/>";
    echo "<input type=submit name='button4' 
                 value='Your Cart'/>"; //moving to ccart
  echo "</form>"; //end of moving to cart
  ?>

  <h1>Additional Product Information</h1>
Thank You For Shopping With Us
<br>
For more information during COVID-19 Please Contact US!
<br><br>






  <?php
    //get the quantity for the item
    $sql1 = "SELECT quantity FROM inventory WHERE productID = $chosenitem[number]"; //get the quantity
    $query1 = $pdo1->query($sql1); //query
    $rows = $query1->fetchAll(PDO::FETCH_ASSOC); //get all
    $itemquant = $rows[0]['quantity'];

    //create a table with the appropriate information
    echo "<img src=$chosenitem[pictureURL]
           style=width:500px;height=500px>";
	echo "<br><br>";
    echo "<table border=3,cellspacing=10, cellpadding=3>";
echo '<tbody style="background-color:#FF726f">';


      echo "<tr>"; //headers for table
        echo "<th>Product #</th>";
        echo "<th>Product Description</th>";
        echo "<th>Product Price ($)</th>";
        echo "<th>Product Weight (lbs) </th>";
        echo "<th>Amount In Stock</th>";
      echo "</tr>";

      echo "<tr>";
        echo "<td>$chosenitem[number]</td>"; //prodcut number
        echo "<td>$chosenitem[description]</td>"; //product descpirtion
        echo "<td>$chosenitem[price]</td>"; //price
        echo "<td>$chosenitem[weight]</td>"; //weight of product
        echo "<td>$itemquant</td>"; //amount on hand
      echo "</tr>";
    echo "</table>";
  ?>

  <?php
  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/details.php>"; //posting to details page
    echo "<input type=hidden name='pnum'
                 value=$_REQUEST[pnum]/>"; //pnum
    echo "<input type=hidden name='cart'
                 value=$_REQUEST[cart]/>"; //cartt

    $found = False;

    foreach($cartcontents as $cartcheck)
    {
      if ($cartcheck['prdctnum'] == $chosenitem['number'])
      {
        $found = True;
      }
    }

    if ($found == False)
    {
      echo "<h5>Quantity You Would Like To Purchase:</h5>";
      echo "<input type=number name='quantity' //var name
                 min=1 max='$itemquant'/>"; //adding it
      echo "<input type=submit name='addcart'
                 value='Add To Your Cart'/>"; //
    }

    else
    {
      echo "You added this item to your cart. Please edit your decision there.";
    }
    echo "</form>";
  ?>

</html>


<footer>

Thank You For Shopping With Us
<br>
For more information during COVID-19 Please Contact US!
<br>
CSCI 467 - Group 1A

</footer>
