<!-- orders.php -->
<html><head><title> Group 1A Catalog Page
</title></head>
<center>
<body bgcolor='FFA07A'>

<center>
<html>


<h1> Orders Page: </h1>

  <?php
    try
    {
      $dsn1 = "mysql:host=courses;dbname=z1853066"; //connecting to database with ZID
      include("pswrds.php"); //propper password
      $pdo1 = new PDO($dsn1, $username1, $password1); //connecting to database
    }

    catch(PDOexception $exception1)
    {
      echo "Database connection failed: " . $exception1->getMessage(); //call the error message
    }

    try
    {
      $connection2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467"; //connecting to the second database
      include("pswrds.php"); //including the password
      $pdo2 = new PDO($connection2, $username2, $password2); //connecting to the second database
    }

    catch(PDOexception $exception2)
    {
      echo "Database connection failed: " . $exception2->getMessage(); //call the error message
    }

    //handle a search for date
    if (array_key_exists('searchdate', $_REQUEST)) //making sure that the array exists
    {
      $lowval = date('Y-m-d', strtotime($_REQUEST['lowerdate'])); //include the dates
      $highval = date('Y-m-d', strtotime($_REQUEST['higherdate'])); //include the dates

      $statement = "SELECT ordersID, custid, finalprice, date, status FROM orders
                    WHERE date BETWEEN \"$lowval\" AND \"$highval\""; //the sql statement 
      $_POST['sql'] = $statement;
    }

    //handle search for status
    else if (array_key_exists('searchstatus', $_REQUEST))
    {
      $statusval = $_REQUEST['status'];
      $statement = "SELECT ordersID, custid, finalprice, date, status FROM orders
                    WHERE status = \"$statusval\"";
      $_POST['sql'] = $statement;
    }

    //search for price
    else if (array_key_exists('searchprice', $_REQUEST))
    {
      $lownum = round(floatval($_REQUEST['lowerprice']), 2);
      $highnum = round(floatval($_REQUEST['higherprice']), 2);

      $statement = "SELECT ordersID, custid, finalprice, date, status FROM orders
                    WHERE finalprice BETWEEN $lownum AND $highnum";
      $_POST['sql'] = $statement;
    }

	//checking if the array exists already
    if (!array_key_exists('viewall', $_REQUEST) && !array_key_exists('search', $_REQUEST))
    {
      echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/orders.php>";
        echo "<input type=submit name='viewall'
                     value='View All Orders'/>"; //view the values
      echo "</form>";

      echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/weightcharge.php>";
        echo "<input type=submit name='goweights'
                     value='Adjust Charges'/>"; //view the charges
      echo "</form>";
    }

    else
    {
      echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/orders.php>";
        echo "<input type=submit name='closeall'
                     value='Close Orders'/>"; //close the orders
      echo "</form>";

	    //call the orders.php
      echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/orders.php>";
        echo "<input type=submit name='searchdate'
                     value='Search Dates Between'/>"; //search for the propper date
        echo "<input type=text name='lowerdate'
                     placeholder='Lower Bound' required/>"; 
        echo "<input type=text name='higherdate'
                     placeholder='Upper Bound' required/>";
        echo "<input type=hidden name='search' value='D'/>";
      echo "</form>";

	    //code required for the search status
      echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/orders.php>";
        echo "<input type=submit name='searchstatus'
                     value='Search by Status of      '/>";
        echo "<input type=text name='status'
                     placeholder='Status' required/>";
        echo "<input type=hidden name='search' value='S'/>";
      echo "</form>";

	    //code required for the searching of the price
      echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/orders.php>";
        echo "<input type=submit name='searchprice'
                    value='Search Prices Between'/>";
        echo "<input type=text name='lowerprice'
                     placeholder='Lower Bound' required/>";
        echo "<input type=text name='higherprice'
                     placeholder='Upper Bound' required/>";
        echo "<input type=hidden name='search' value='P'/>";
      echo "</form>";

	    //the sql statement to gather the appropriate information
      $sql1 = "SELECT ordersID, custid, finalprice, date, status FROM orders";
      if (array_key_exists('sql', $_POST) && array_key_exists('sql', $_POST))
      {
        $sql1 = $_POST['sql'];
      }

	    //throw error message if there is a problem
      if (array_key_exists('sql', $_REQUEST))
      {
        $sql1 = "";
        $asarray = unserialize(base64_decode($_REQUEST['sql']));

        foreach($asarray as $word)
        {
          $sql1 = ($sql1 . $word . " ");
        }

        $sql1 = substr($sql1, 0, -1);
      }

	    //call the query
      $query1 = $pdo1->query($sql1);
      $allorders = $query1->fetchAll(PDO::FETCH_ASSOC);

      $toarray = explode(" ", $sql1);

	    //creating design for the page
      echo "<table border=5,cellspacing=5,cellpadding=5>";
echo '<tbody style="background-color:#FF726F">'; //colorring
//talbe settings for the page
        echo "<tr>";
          echo "<th>Order ID</th>";
          echo "<th>Customer ID</th>";
          echo "<th>Price ($)</th>";
          echo "<th>Date</th>";
          echo "<th>Status</th>";
	echo "<th> More </th>";
        echo "</tr>";

        foreach($allorders as $order)
        {
          echo "<tr>";
            echo "<td>";
              echo "$order[ordersID]"; //call upon ordersID
            echo "</td>";

            echo "<td>";
              echo "$order[custid]"; //call upon custid
            echo "</td>";

            echo "<td>";
              echo "$order[finalprice]"; //call upon finalprice
            echo "</td>";

            echo "<td>";
              echo "$order[date]"; //call upon the date
            echo "</td>";

            echo "<td>";
              echo "$order[status]"; //call upon the status
            echo "</td>";

            echo "<td>";
              echo "<form method=post action =http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/orderdetails.php>";
                echo "<input type=hidden name='oid'
                             value=$order[ordersID]/>";
                $a2s= base64_encode(serialize($toarray));
                echo "<input type=hidden name='sql'
                             value=$a2s/>";
                echo "<input type=submit name=$order[ordersID]
                             value='See Details'/>";
              echo "</form>";
            echo "</td>";
          echo "</tr>";
        }
      echo "</table>";
    }
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
