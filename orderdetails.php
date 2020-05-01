<!-- orderdetails.php -->

<html>
  <?php
    try
    {
      $dsn1 = "mysql:host=courses;dbname=z1853066";           // Connect to database on MariaDB using this login credentials
      include("pswrds.php");                                  // include pswrds.php to match the given password                                    
      $pdo1 = new PDO($dsn1, $username1, $password1);         // use credentials username1 and password1 to establish connection
    }

    catch(PDOexception $exception1)
    {
      echo "Database connection failed: " . $exception1->getMessage();        // print this error message if connection is unsuccessful
    }

    try
    {
      $connection2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";            // connect to blitz.cs.niu.edu for legacy DB
      include("pswrds.php");                                                  // include pswrds.php to match the given password
      $pdo2 = new PDO($connection2, $username2, $password2);                  // use credentials username2 and password2 to establish connection
    }

    catch(PDOexception $exception2)
    {
      echo "Database connection failed: " . $exception2->getMessage();        // print this error message if connection is unsuccessful
    }

    $sql1 = "SELECT ordersID, custid, finalprice, date, status FROM orders";  // order ID, custID, final price, date of purcase and status of order will show from orders table in database
    if (array_key_exists('sql', $_REQUEST))
    {
      $sql1 = "";
      $asarray = unserialize(base64_decode($_REQUEST['sql']));                // unserialize value from array

      foreach($asarray as $word)
      {
        $sql1 = ($sql1 . $word . " ");
      }

      $sql1 = substr($sql1, 0, -1);
    }

    $toarray = explode(" ", $sql1);

    echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/orders.php>";    // submit data to orders.php using POST method
      $a2s= base64_encode(serialize($toarray));     // re-serialize items to an array
      echo "<input type=hidden name='sql'
                   value=$a2s1/>";
      echo "<input type=hidden name='viewall'       // option for user of this interface to view all customer orders
                   value='View All Orders'/>";
      echo "<input type=hidden name='search'        // option for user of this interface to search for customer orders
                   value='R'/>";
      echo "<input type=submit name='orders'
                   value='Return to Search'/>";     // to return to search field
    echo "</form>";

    $orderid = 0;
    if (array_key_exists('oid', $_REQUEST))         // if requested for a valid order id
    {
      $orderid = intval($_REQUEST['oid']);
    }

    $sqlord = "SELECT * FROM orders WHERE ordersID = $orderid";       // select the requested order id from database
    $queryord = $pdo1->query($sqlord);
    $arrayord = $queryord->fetchAll(PDO::FETCH_ASSOC);                // fetch all orders with this id

    $order = $arrayord[0];
    $customer = $arrayord[0]['custid'];                               // pull up customer with this id

    echo "<h3>Order Details</h3>";                                    // These specify the column headers for table "Order Details"
    echo "<table border=3>";                                          // table border formatting
      echo "<tr>";
        echo "<th>Order ID</th>";
        echo "<th>Customer ID</th>";
        echo "<th>Status</th>";
        echo "<th>Total Weight</th>";
        echo "<th>Additional Charges</th>";
        echo "<th>Price</th>";
        echo "<th>Total Price</th>";
        echo "<th>Date of Order</th>";
      echo "</tr>";

      echo "<tr>";
        foreach($order as $data)
        {
          echo "<td>";
            echo "$data";           // display the appropriate data in the table cell
          echo "</td>";
        }
      echo "</tr>";
    echo "</table>";                // end of table

   echo "<h3>Item Details</h3>";
   $sqlprod = "SELECT productID, quantity FROM ordereditems       // select the requested product id and availble quantity from database for this order ID
              WHERE orderID = $orderid";
   $queryprod = $pdo1->query($sqlprod);
   $arrayprod = $queryprod->fetchAll(PDO::FETCH_ASSOC);           // fetch info for all orders with this id

   echo "<table border=3>";                                       // table border formatting
     echo "<tr>";                                                 // These specify the column headers for table "Item Details"
       echo "<th>Product Number</th>";
       echo "<th>Product Description</th>";
       echo "<th>Quantity Ordered</th>";
       echo "<th>Product Price</th>";
       echo "<th>Added Price</th>";
       echo "<th>Product Weight</th>";
       echo "<th>Added Weight</th>";
     echo "</tr>";

    foreach($arrayprod as $ordereditem)                           // this loop for each order item
    {
      $databdesc= "SELECT description, price, weight FROM parts WHERE number = $ordereditem[productID]";  // select data for product with this ID
      $querydesc= $pdo2->query($databdesc);
      $arraydesc = $querydesc ->fetchAll(PDO::FETCH_ASSOC);
      $description = $arraydesc[0]['description'];                // add data: description, weight and price for product with this ID
      $weight = $arraydesc[0]['weight'];
      $price = $arraydesc[0]['price'];

      echo "<tr>";
        echo "<td>";
          echo "$ordereditem[productID]";                         // display product ID in cell
        echo "</td>";

        echo "<td>";
          echo "$description";                                    // display item description
        echo "</td>";

        echo "<td>";
          echo "$ordereditem[quantity]";                          // display quantity that user has requested
        echo "</td>";

        echo "<td>";
          echo "$price";                                          // display item's price
        echo "</td>";

        $trueprice = ($ordereditem['quantity'] * $price);         // multiply unit price for 1 item with the quantity ordered...
        echo "<td>";
          echo "$trueprice";                                      // ... to get actual price
        echo "</td>";

        echo "<td>";
          echo "$weight";
        echo "</td>";

        $trueweight = ($ordereditem['quantity'] * $weight);       // multiply unit weight for 1 item with the quantity ordered...
        echo "<td>";
          echo "$trueweight";                                     // ... to get the actual weight for all products in order
        echo "</td>";
      echo "</tr>";
    }

   echo "</table>";                                               // end of table


   echo "<h3>Customer Details</h3>";

   $internetcustomer = "SELECT name, email, address, ccnum FROM customer WHERE customerID = $customer";   // select customer info from database for viewing on page
   $querycust = $pdo1->query($internetcustomer);
   $arraycust = $querycust->fetchAll(PDO::FETCH_ASSOC);

   echo "<table border=3>";
     echo "<tr>";                                             // These specify the column headers for table "Customer Details"
       echo "<th>Customer Name</th>";
       echo "<th>Customer Email</th>";
       echo "<th>Customer Address</th>";
       echo "<th>Credit Card</th>";
     echo "</tr>";

     echo "<tr>";
       foreach($arraycust as $cust)                           // For each customer, display the following info...
       {
         echo "<td>";
           echo "$cust[name]";                                // customers name
         echo "</td>";

         echo "<td>";
           echo "$cust[email]";                               // customers email id
         echo "</td>";

         echo "<td>";
           echo "$cust[address]";                             // customers given mailing address
         echo "</td>";

         $star = "************";
         $censor = substr_replace($cust['ccnum'], $star, 0, -4);
         echo "<td>";
           echo "$censor";
         echo "</td>";
       }
     echo "</tr>";
   echo "</table>";                                           // end of table
  ?>
</html>

