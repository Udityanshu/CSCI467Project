<!--Checkout.php-->

<html>
  <?php
	echo '<center>';
	echo "<body bgcolor='FFA07A'>";
	echo'<title> 1A CSCI 467 Checkout - Z1853066</title>';

$cartitems = array();		//initialize the variable as an array() 
  if (array_key_exists('cart', $_REQUEST) || array_key_exists('placeorder', $_REQUEST))		//maintain the cart array
  {
    $cartitems = unserialize(base64_decode($_REQUEST['cart']));
  }

  $itemprices = 0;
  $totalweight = 0;
  $addfees = 0;
  $finalprice = 0;
  if (array_key_exists('gotocheckout', $_REQUEST) || array_key_exists('placeorder', $_REQUEST))		//if array for checkout
  {
    $itemprices = round(floatval($_REQUEST['itemprices']), 2);
    $totalweight = round(floatval($_REQUEST['totalweight']), 2);
    $addfees = round(floatval($_REQUEST['addfees']), 2);
    $finalprice = round(floatval($_REQUEST['finalprice']), 2);
  }

  $name = "x";			//credentials prototype
  $email = "x@xmail.com";
  $address = "x streat";
  $cnumber = "xxxxxxxxxxxxxxxx";
  $cxp = "xx/xxxx";
	
  if (array_key_exists('placeorder', $_REQUEST))	//if exists and requested
  {
    $name = $_REQUEST['name'];
    $email = $_REQUEST['email'];
    $address = $_REQUEST['address'];
    $cnumber = $_REQUEST['cnumber'];
    $cexp = $_REQUEST['cexp'];
  }

  try
  {
    $dsn1 = "mysql:host=courses;dbname=z1853066";	//connecting to the database
    include("pswrds.php");		//using password file
    $pdo1 = new PDO($dsn1, $username1, $password1);	//stating username and password
  }

  catch (PDOexception $exception1)	//error handler
  {
    echo "Database connection failed: " . $exception1->getMessage();	//throw an error
  }

  try
  {
    $connection2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";	//connecting to the database
    include("pswrds.php");			//using password file
    $pdo2 = new PDO($connection2, $username2, $password2);	//stating username and password
  }

  catch (PDOexception $exception2)
  {
    echo "Database connection failed: " . $exception2->getMessage();	//throw an error
  }

  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/cartpage.php>";  //cart button to submit the cartpage.php
    $a2s = base64_encode(serialize($cartitems));	//serializing the array
    echo "<input type=hidden name='cart'
                 value=$a2s/>";
    echo "<input type=submit name='backtocart'
                 value='Go Back To Your Cart'/>";	//pointting back to the cart
  echo "</form>";



  if (!(array_key_exists('placeorder', $_REQUEST)))
  {
    echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/checkoutpage.php>"; //on checkout page
      $a2s13 = base64_encode(serialize($cartitems));	//serializing the array
      echo "<input type=hidden name='cart'	//button for entering necessary credentials
                   value=$a2s13/>";
      echo "<input type=hidden name='itemprices'
                   value=$itemprices/>";
      echo "<input type=hidden name='totalweight'
                   value=$totalweight/>";
      echo "<input type=hidden name='addfees'
                   value=$addfees/>";
      echo "<input type=hidden name='finalprice'
                   value=$finalprice/>";
      echo "<h1>Order Checkout: </h1>";
      echo "Your Name: ";
      echo "<input type=text name='name'
                        placeholder='Your Name Here' required/> <br><br>";
      echo "Your Email: ";
      echo "<input type=text name='email'
                        placeholder='Your Email Here' required/> <br>  <br>";
      echo "Your Address: ";
      echo "<input type=text name='address'
                        placeholder='Your Address Here' required/> <br>  <br>";
      echo "Credit Card Number: ";
      echo "<input type=text name='cnumber'
                        placeholder='Correct Format' required/> <br>";
      echo  '<small>(The format is "6011 1234 4321 1234")</small>';
	echo "<br><br>";


echo "Card Expiration Date: ";
      echo "<input type=text name='cexp'
                        placeholder='Expiration Date' required/> <br>";
	echo '<small> (The format is "MM/YYYY")</small><br>';
      echo "<br> <input type=submit name='placeorder'
                        value='Confirm Your Details'/>";
    echo "</form>";
  }

  else
  {
    //check if the customer already exists
    $sql1 = "SELECT * FROM customer WHERE name = '$name' AND email = '$email'";	//select from customers where it matches from DB
    $query1 = $pdo1->query($sql1);
    $matchcust = $query1->fetchAll(PDO::FETCH_ASSOC);
    $customerid = 0;
    if (count($matchcust) == 0)
    {
      //add customer to database
      $sql2 = "INSERT INTO customer (name, email, address, ccnum, ccexp)
               VALUES ('$name', '$email', '$address', '$cnumber', '$cexp')";
      $query2 = $pdo1->query($sql2);
      $customerid = $pdo1->lastInsertId();
    }

    else
    {
      //grab the customer id
      $customerid = $matchcust[0]['customerID'];

      $sql4 = "UPDATE customer SET address = '$address', ccnum = '$cnumber', ccexp = '$cexp'
               WHERE customerID = '$customerid'";
      $query4 = $pdo1->query($sql4);
    }

    $ordersid = 0;
    //place the order into the orders table
    $orderstatus = 'A';
    $date = date("Y-m-d");
    $sql3 = "INSERT INTO orders (custid, status, totalweight, addfees, totalprice, finalprice, date)
             VALUES ('$customerid', '$orderstatus', '$totalweight', '$addfees', '$itemprices', '$finalprice', '$date')";
    $query3 = $pdo1->query($sql3);

    $ordersid = $pdo1->lastInsertId();

    //place each item into the ordered items table
    foreach($cartitems as $citem)
    {
      $quantity = $citem['qntty'];
      $prodid = $citem['prdctnum'];
      $sql5 = "INSERT INTO ordereditems (orderID, quantity, productID)
               VALUES ('$ordersid', '$quantity', '$prodid')";
      $query5 = $pdo1->query($sql5);
    }


    //generate a random vendor id
    function randstring($n)
    {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $randomString = '';

      for ($i = 0; $i < $n; $i++)
      {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
      }

      return $randomString;
    }

    function randnum($n)
    {
      $characters = '0123456789';
      $randomString = '';

      for ($i = 0; $i < $n; $i++)
      {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
      }

      return $randomString;
    }

    $vendorid = ("VE" . randnum(3) . "-" . randnum(2));
    $transactionid = (randnum(3) . "-" . randnum(6) . "-" . randnum(3));

    //send data to the credit cart authorization system
    $url = 'http://blitz.cs.niu.edu/CreditCard/';
    $data = array(
	'vendor' => $vendorid,
	'trans' => $transactionid,
	'cc' => $cnumber,
	'name' => $name,
	'exp' => $cexp,
	'amount' => $finalprice);

    $options = array(
     'http' => array(
        'header' => array('Content-type: application/json', 'Accept: application/json'),
        'method' => 'POST',
        'content'=> json_encode($data)
      )
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    $nobracket1 = explode("{", $result);
    $nobracket2 = explode("}", $nobracket1[1]);
    $nocommas = explode(",", $nobracket2[0]);
    $quotes = array();
    $values = array();
    foreach($nocommas as $pair)
    {
      $kyval = explode(":", $pair);
      array_push($quotes, $kyval[0]);
      array_push($values, $kyval[1]);
    }

    $keys = array();
    foreach($quotes as $kval)
    {
      $noquotes = explode('"', $kval);
      array_push($keys, $noquotes[1]);
    }

    $resultarray = array_combine($keys, $values);

    //check the result
    if (array_key_exists('errors', ($resultarray)))
    {
      //delete all entries
      $sqldel = "DELETE FROM ordereditems WHERE orderID = $ordersid";
      $querydel = $pdo1->query($sqldel);

      //delete order
      $sqldel2 = "DELETE FROM orders WHERE ordersID = $ordersid";
      $querydel2 = $pdo1->query($sqldel2);

      echo "There was a problem processing your order.<br>";
      echo "ERROR: $resultarray[errors] <br>";
      echo "Please head back to your cart and try again.";
    }

    else
    {
      //if the order was successful, reduce the inventory for that item
      foreach ($cartitems as $inventoryitem)
      {
        $quantty = $inventoryitem['qntty'];
        $refid = $inventoryitem['prdctnum'];

        $databquantity= "SELECT quantity FROM inventory WHERE productID = $refid";
        $queryquan = $pdo1->query($databquantity);
        $quanrow = $queryquan->fetchAll(PDO::FETCH_ASSOC);
        $foundquantity = $quanrow[0]['quantity'];
        $newquantity = ($foundquantity - $quantty);

        $sqlinv = "UPDATE inventory SET quantity = $newquantity WHERE productID = $refid";
        $queryinv = $pdo1->query($sqlinv);
      }

      //send email
      $subject = "Confirm Order Number $ordersid";
      $message = "This message confirms that you made a purchase with confirmation code $resultarray[authorization]";
      $headers = "From: <noreply@group1A.com>";
      mail($email, $subject, $message, $headers);

      echo "<h1>Thank you for your order!</h1>";
      echo "Your confirmation number is: $resultarray[authorization] <br>";
      echo "An email has been sent to you at $email.";

      $cartitems = array();
      echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/catalogpage.php>";
        echo "<input type=submit name='gotocatelog'
                     value='Clear And Go Back To Catalog'/>";
      echo "</form>";
    }
  }

echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";

  ?>

<footer>
Thank You For Shopping With Us
<br>
COVID-19 Has NOT Affected Our Shipping Times!
For more information during COVID-19 Please Contact US!
<br>
CSCI 467 - Group 1A
</footer>
</html>

