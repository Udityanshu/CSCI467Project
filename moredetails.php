<!-- details.php-->
<html>
<center>
<body bgcolor='FFA07A'>


  <?php
// Connecting to the Mariadb Server
  try
  {
    $dsn1 = "mysql:host=courses;dbname=z1853066";
    include("pswrds.php"); //using logon  page

    $pdo1 = new PDO($dsn1, $username1, $password1);
  }
//Catch adn  display the error if there is one
  catch (PDOexeption $exception1)
  {
    echo "Database connection failed: " . $exception1->getMessage();
  

}
// Conncting to the server that holds data
  try
  {
    $connection2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
    include("pswrds.php"); //using login page
    $pdo2 = new PDO($connection2, $username2, $password2);
  }
//Catch and dispaly the error if there is one 
  catch (PDOexception $exception2)
  {
    echo "Database connection failed: " . $exception2->getMessage();
  }

  //initialzize the cart
  $cartitems = array(); //make the array to be used holding data 

//Make surre the cart is populated
  if (array_key_exists('cart', $_REQUEST))
  {
    $cartitems = unserialize(base64_decode($_REQUEST['cart'])); //unserialze the cart o be used
  }

  //check for the existence of an item
  if (array_key_exists('pnum', $_REQUEST))
  {
    $chosen_item = unserialize(base64_decode($_REQUEST['pnum'])); //retrieve the hidden product number to be used to display 
  }

  else
  {
    $chosen_item = array("number"=>"-1", "description"=>"NO ITEM CHOSEN", "price"=>"-1", "weight"=>"-1", "https://www.google.com/url?sa=i&source=images&cd=&ved=2ahUKEwiE0Pb2ht7lAhVHLKwKHcwBA-MQjRx6BAgBEAQ&url=https%3A%2F%2Fwww.vectorstock.com%2Froyalty-free-vector%2Fnot-available-flat-icon-vector-12770007&psig=AOvVaw3CzGK2ABM54vY1_P-FI8gw&ust=1573420670639053"=>"pictureURL");
  }

  if (isset($_POST['addcart'])) //from other page if added then push into array
  {
    $citem = array("prdctnum"=>$chosen_item['number'], "qntty"=>$_POST['quantity']);
    array_push($cartitems, $citem);
  }
  ?>

  <head><title> Additional Product Information
    </title>
  <head>

  <?php
  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/catalogpage.php>"; //moving from details page back to catalog page
//based on sbutton go back to catalog and reserialze the carrt again
    $a2s= base64_encode(serialize($cartitems));
    echo "<input type=hidden name='cart'
                 value=$a2s/>";
    echo "<input type=submit name='button3'
                 value='Return to Catelog'/>";
  echo "</form>"; //end of going back to catalog page

  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/cartpage.php>"; //moving to cart if they added someetihng and then want to go to cart
    echo "<input type=hidden name='pnum' //save pnum
                 value=$_REQUEST[pnum]/>";
    $a2s= base64_encode(serialize($cartitems)); //serizlize again
    echo "<input type=hidden name='cart' //
                 value=$a2s/>";
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
    $sql1 = "SELECT quantity FROM inventory WHERE productID = $chosen_item[number]"; //get the quantity
    $query1 = $pdo1->query($sql1); //query
    $rows = $query1->fetchAll(PDO::FETCH_ASSOC); //get all
    $numitems = $rows[0]['quantity'];

    //create a table with the appropriate information
    echo "<img src=$chosen_item[pictureURL]
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
        echo "<td>$chosen_item[number]</td>"; //prodcut number
        echo "<td>$chosen_item[description]</td>"; //product descpirtion
        echo "<td>$chosen_item[price]</td>"; //price
        echo "<td>$chosen_item[weight]</td>"; //weight of product
        echo "<td>$numitems</td>"; //amount on hand
      echo "</tr>";
    echo "</table>";
  ?>

  <?php
  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/moredetails.php>"; //posting to details page
    echo "<input type=hidden name='pnum'
                 value=$_REQUEST[pnum]/>"; //pnum
    echo "<input type=hidden name='cart'
                 value=$_REQUEST[cart]/>"; //cartt

    $found = False;

    foreach($cartitems as $cartcheck)
    {
      if ($cartcheck['prdctnum'] == $chosen_item['number'])
      {
        $found = True;
      }
    }

    if ($found == False)
    {
      echo "<h5>Quantity You Would Like To Purchase:</h5>";
      echo "<input type=number name='quantity' //var name
                 min=1 max='$numitems'/>"; //adding it
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

