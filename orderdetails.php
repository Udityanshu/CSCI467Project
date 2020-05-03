<html>
<html><head><title> Group 1A Order Details Page
</title></head>

<body bgcolor='FFA07A'>
<center>
  <?php
    try //connection to maraiabd
    {
      $dsn1 = "mysql:host=courses;dbname=z1853066"; //connection
      include("pswrds.php"); //import the password files
      $pdo1 = new PDO($dsn1, $username1, $password1); //command to connect 
    }

    catch(PDOexception $exception1) //if error then catcha and show it
    {
      echo "Database connection failed: " . $exception1->getMessage(); //the error code
    }

    try //connection to the blitz server 
    {
      $connection2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
      include("pswrds.php"); // include the password file
      $pdo2 = new PDO($connection2, $username2, $password2);
    }

    catch(PDOexception $exception2) //catch this error if there is one and display it
    {
      echo "Database connection failed: " . $exception2->getMessage(); //the error 
    }

    $sql1 = "SELECT ordersID, custid, finalprice, date, status FROM orders";
								//show data from orders table
    if (array_key_exists('sql', $_REQUEST))
    {
      $sql1 = "";
      $asarray = unserialize(base64_decode($_REQUEST['sql']));

      foreach($asarray as $word) //for each one 
      {
        $sql1 = ($sql1 . $word . " "); //put them together
      }

      $sql1 = substr($sql1, 0, -1); //decrement
    }

    $toarray = explode(" ", $sql1); //explode the array 

    echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/orders.php>";
      // here is the viewing all orders and return button
	$a2s= base64_encode(serialize($toarray));
      echo "<input type=hidden name='sql'
                   value=$a2s/>"; 
      echo "<input type=hidden name='viewall'
                   value='View All Orders'/>"; //view all orders button
      echo "<input type=hidden name='search' // hidden search
                   value='R'/>";
      echo "<input type=submit name='orders' //return to orrders search
                   value='Return to Orders Search'/>";
    echo "</form>";

    $orderid = 0;
    if (array_key_exists('oid', $_REQUEST))
    {
      $orderid = intval($_REQUEST['oid']);
    }

    $sqlord = "SELECT * FROM orders WHERE ordersID = $orderid"; //searching with order id and
								//displaying the rresults
    $queryord = $pdo1->query($sqlord);
    $arrayord = $queryord->fetchAll(PDO::FETCH_ASSOC);

    $order = $arrayord[0];
    $customer = $arrayord[0]['custid'];

    echo "<h3>Order Details</h3>";
    echo "<table border=5, cellspacing=5, cellpadding=5>";
echo '<tbody style="background-color:#FF726F">';
//table settings here
      echo "<tr>"; //headers for the table
        echo "<th>Order ID</th>";
        echo "<th>Customer ID</th>";
        echo "<th>Status</th>";
        echo "<th>Total Weight (lbs)</th>";
        echo "<th>Additional Charges ($)</th>";
        echo "<th>Price ($) </th>";
        echo "<th>Total Price ($)</th>";
        echo "<th>Date of Order</th>";
      echo "</tr>";

      echo "<tr>";
        foreach($order as $data)
        {
          echo "<td>";
            echo "$data"; //data for the table in for loop
          echo "</td>";
        }
      echo "</tr>";
    echo "</table>";

   echo "<h3>Item Details</h3>";
   $sqlprod = "SELECT productID, quantity FROM ordereditems
              WHERE orderID = $orderid"; //select statement to show where orderid results
   $queryprod = $pdo1->query($sqlprod);
   $arrayprod = $queryprod->fetchAll(PDO::FETCH_ASSOC);

   echo "<table border=5, cellspacing=5, cellpadding=5>";
echo '<tbody style="background-color:#FF726F">';
//table settings above
     echo "<tr>"; //table headers
       echo "<th>Product #</th>";
       echo "<th>Product Name</th>";
       echo "<th>Amount Ordered</th>";
       echo "<th>Price ($)</th>";
       echo "<th>Total Price ($)</th>";
       echo "<th>Weight (lbs)</th>";
       echo "<th>Added Weight</th>";
     echo "</tr>";

    foreach($arrayprod as $ordereditem)
    {
      $databdesc= "SELECT description, price, weight FROM parts WHERE number = $ordereditem[productID]"; //select statement to show what the user rquested
      $querydesc= $pdo2->query($databdesc);
      $arraydesc = $querydesc ->fetchAll(PDO::FETCH_ASSOC);
      $description = $arraydesc[0]['description']; //desc
      $weight = $arraydesc[0]['weight']; //weight
      $price = $arraydesc[0]['price']; //price

      echo "<tr>";
        echo "<td>";
          echo "$ordereditem[productID]"; //id
        echo "</td>";

        echo "<td>";
          echo "$description"; //desc
        echo "</td>";

        echo "<td>";
          echo "$ordereditem[quantity]"; //amount ordered

        echo "</td>";




        echo "<td>";
          echo "$price"; //price of each  
        echo "</td>";

        $trueprice = ($ordereditem['quantity'] * $price);
        echo "<td>";
          echo "$trueprice"; //price of total
        echo "</td>";

        echo "<td>";
          echo "$weight"; //wiehgt of each
        echo "</td>";

        $trueweight = ($ordereditem['quantity'] * $weight); //total weight
        echo "<td>";
          echo "$trueweight";
        echo "</td>";
      echo "</tr>";
    }

   echo "</table>"; //end of results table


   echo "<h3>Customer Information</h3>"; //cutsomer information for the order

   $internetcustomer = "SELECT name, email, address, ccnum FROM customer WHERE customerID = $customer";
	//show the customer information where the orderid= orderid
	//invocie adn address here
   $querycust = $pdo1->query($internetcustomer);
   $arraycust = $querycust->fetchAll(PDO::FETCH_ASSOC);

   echo "<table border=5, cellspacing=5, cellpadding=5>";
echo '<tbody style="background-color:#FF726F">';
//table settings here
     echo "<tr>"; //table headers
       echo "<th>Customer Name</th>";
       echo "<th>Customer Email</th>";
       echo "<th>Customer Address</th>";
       echo "<th>Credit Card #</th>";
     echo "</tr>";

     echo "<tr>";
       foreach($arraycust as $cust)
       {
         echo "<td>";
           echo "$cust[name]"; //customer name
         echo "</td>";

         echo "<td>";
           echo "$cust[email]"; //email on file
         echo "</td>";

         echo "<td>";
           echo "$cust[address]"; //address on file
         echo "</td>";

         $star = "************";
         $censor = substr_replace($cust['ccnum'], $star, 0, -4); //cc number BUT *** for beginning digits
         echo "<td>";
           echo "$censor";
         echo "</td>";
       }
     echo "</tr>";
   echo "</table>"; //end of customer informaiton table
  ?>
</html>
<footer>
<br><br><br>
Thank You For Shopping With Us
<br>
For more information during COVID-19 Please Contact US!
<br>
CSCI 467 - Group 1A

</footer>
