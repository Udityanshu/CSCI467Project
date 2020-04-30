<!-- Cart page -->
<html><head><title>
      View Your Cart
    </title>
  </head>
<body bgcolor='FFA07A'>
<center>

  <?php
  //Attempt to connect to MariaDB Database
  try
  {
    $dsn1 = "mysql:host=courses;dbname=z1853066";     //connecting to the database
    include("pswrds.php");          //using password file
    $pdo1 = new PDO($dsn1, $username1, $password1);   //stating the username and password 
  }

  catch (PDOexception $exception1)
  {
    echo "Database connection failure: " . $exception1->getMessage();   //if connection fails throw and error message
  }

  try
  {
    $connection2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";        //connecting to the second database
    include("pswrds.php"); //using password file
    $pdo2 = new PDO($connection2, $username2, $password2);        //stating the username and password
  }

  catch (PDOexception $exception2)        //Catch and handle expection if there is one
  {
    echo "Database connection failure: " . $exception2->getMessage();   //throw an error message
  }

  $cartitems = array();             //create the array 

  if (array_key_exists('cart', $_REQUEST))
  {
    $cartitems = unserialize(base64_decode($_REQUEST['cart']));   //unserialzie array
  }

  if (isset($_POST['changequantity']))    //if change quantity is selected on the cart page
  {
    $ind = intval($_POST['index']);
    $quan = intval($_POST['quantity']);
    $cartitems[$ind]['qntty'] = $quan; //chane the quant
  }

  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/catalogpage.php>"; //button for going back to catalog
    $a2s4 = base64_encode(serialize($cartitems));
    echo "<input type=hidden name='cart'
                 value=$a2s4/>";
    echo "<input type=submit name='button5'
                 value='Return To The Catalog'/>"; //return back to catalog page 
  echo "</form>";

  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/cartpage.php>";
    echo "<input type=submit name='button8'
                 value='Clear Your Cart'/>";    //cleaning the cart and resubmit to cart.php
  echo "</form>";

  if (array_key_exists('pnum', $_REQUEST))
  {
    echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/moredetails.php>"; //items page for more details
      echo "<input type=hidden name='pnum'
                   value=$_REQUEST[pnum]/>";
      $a2s5 = base64_encode(serialize($cartitems));
      echo "<input type=hidden name='cart'
                   value=$a2s5/>";
      echo "<input type=submit name='button6'
                   value='Return to Previous Page (Additional Product Information Page)'/>"; //button for going back to prodcut information
    echo "</form>";
  }

  if (!empty($cartitems))
  {
    $plist = array();         //defining arrays
    $calclist = array();  
    $calcentry = array(); 
    foreach($cartitems as $citem)
    {
      $sql1 = "SELECT * FROM parts WHERE number = $citem[prdctnum]";   //query for parts with product num
      $query1 = $pdo2->query($sql1);
      $rows = $query1->fetchAll(PDO::FETCH_ASSOC);
      array_push($plist, $rows[0]);
    }

    $count = 0;
    $itemprices = 0;
    $totalweight = 0;
    $addfees = 0;
    $finalprice = 0;
    foreach($cartitems as $centry)
    {
      $sql2 = "SELECT quantity FROM inventory WHERE productID = $centry[prdctnum]"; //showing the quantity
      $query2 = $pdo1->query($sql2);
      $rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
      $numitems = $rows2[0]['quantity'];

      $pentry = $plist[$count];
      $calcentry = array("pid"=>($centry["prdctnum"]),
                   "des"=>($pentry["description"]),
                   "qty"=>($centry["qntty"]),
                   "tpr"=>($centry["qntty"] * $pentry["price"]),
                   "twe"=>($centry["qntty"] * $pentry["weight"]),
                   "mqy"=>($numitems));
      array_push($calclist, $calcentry);

      //increment the summary variables
      $itemprices += ($centry["qntty"] * $pentry["price"]);
      $totalweight += ($centry["qntty"] * $pentry["weight"]);

      $count++;
    }

    //set the additional fees
    $sqladd = "SELECT * FROM admin ORDER BY bracket ASC";
    $queryadd = $pdo1->query($sqladd);
    $weightarray = $queryadd->fetchAll(PDO::FETCH_ASSOC);

    //set default charge
    if (count($weightarray) == 0)
    {
      $addfees = 0;
    }

    else
    {
      //$weightarray = $queryadd->fetchAll(PDO::FETCH_ASSOC);

      //set the additional fees
      foreach($weightarray as $weightbracket)
      {
        if ($totalweight <= $weightbracket['bracket'])
        {
          $addfees = $weightbracket['charge'];
          break;
        }
      }
    }

    $itemprices = round($itemprices, 2);
    $totalweight = round($totalweight, 2);
    $addfees = round($addfees, 2);
    $finalprice = round(($itemprices + $addfees), 2);

    echo "<h1>Review Your Items</h1>";    //header

    echo "<table border=3, cellspacing=5, cellpadding=5>";  //designing the border
echo '<tbody style="background-color:#FF726f">';


      echo "<tr>";            //headers for table here
        echo "<th>Product ID</th>";
        echo "<th>Product Name</th>";
        echo "<th>Quantity Ordered</th>";
        echo "<th>Added Price ($)</th>";
        echo "<th>Added Weight (lbs)</th>";
        echo "<th>Change Quantity</th>";
        echo "<th>Delete?</th>";
        echo "<th>Go To Item Details</th>";
      echo "</tr>";

      $countcount = 0;
      foreach($calclist as $cinfo)
      {
        echo "<tr>";
        foreach($cinfo as $label=>$data)
        {
          if ($label != "mqy")
          {
            echo "<td>$data</td>"; //the data for the product
          }
        }

          echo "<td>";
            echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/cartpage.php>"; //rendoring to cart page

              if (array_key_exists('pnum', $_REQUEST))       //what is currently in the cart
              {
                echo "<input type=hidden name='pnum'
                             value=$_REQUEST[pnum]/>";
              }

              $a2s9 = base64_encode(serialize($cartitems));
              echo "<input type=hidden name='cart'
                           value=$a2s9/>";
              echo "<input type=hidden name='index'
                           value=$countcount/>";
              echo "<input type=number name='quantity'
                           min=1 max='$cinfo[mqy]'/>";
              echo "<input type=submit name='changequantity' //changin quantity
                           value='Change The Amount'/>";
            echo "</form>"; //end of changes made in the currrent caart
          echo "</td>";

          echo "<td>";
            echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/cartpage.php>";
              if (array_key_exists('pnum', $_REQUEST))
              {
                echo "<input type=hidden name='pnum'
                             value=$_REQUEST[pnum]/>";
              }

              $cartarray = array();
              foreach($cartitems as $cartitem)
              {
               if ($cartitem['prdctnum'] != $cinfo['pid'])
               {
                 array_push($cartarray, $cartitem);
               }
              }
              $a2s8 = base64_encode(serialize($cartarray));
              echo "<input type=hidden name='cart'
                           value=$a2s8/>";
              echo "<input type=submit name=$cinfo[pid]
                           value='Delete'/>";
            echo "</form>";
          echo "</td>";
          echo "<td>";
            echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/moredetails.php>";
              $a2s11 = base64_encode(serialize($plist[$countcount]));
              echo "<input type=hidden name='pnum'
                           value=$a2s11/>";
              $a2s10 = base64_encode(serialize($cartitems));
              echo "<input type=hidden name='cart'
                          value=$a2s10/>";
              echo "<input type=submit name='revisit'
                           value='View Item'/>";
            echo "</form>";
          echo "</td>";
        echo "</tr>";
        $countcount++;
      }
    echo "</table>";

    echo "<h2>Total Order Statistics</h2>";         //order and desgining of the table
    echo "<table border=3, cellspacing=3, cellpadding=5>";
echo '<tbody style="background-color:#FF726f">';


      echo "<tr>";                  //table headers
        echo "<th>Total Price ($) </th>";
        echo "<th>Total Weight (lbs)</th>";
        echo "<th>Additional Fees ($) </th>";
        echo "<th>Final Price ($) </th>";
      echo "</tr>";
      echo "<tr>";
        echo "<td>$itemprices</td>";
        echo "<td>$totalweight</td>";
        echo "<td>$addfees</td>";
        echo "<td>$finalprice</td>";
      echo "</tr>";
    echo "</table>";

    echo "<h2>Ready to Order? Continue to Cart.</h2>";
      //moving to the checkoutpage and pulling everything over
    echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/checkoutpage.php>";

      $a2s12 = base64_encode(serialize($cartitems));
      echo "<input type=hidden name='cart'
                   value=$a2s12/>";
      echo "<input type=hidden name='itemprices'
                   value=$itemprices/>";
      echo "<input type=hidden name='totalweight'
                   value=$totalweight/>";
      echo "<input type=hidden name='addfees'
                   value=$addfees/>";
      echo "<input type=hidden name='finalprice'
                   value=$finalprice/>";
      echo "<input type=submit name='gotocheckout'
                   value='Go To Checkout'/>";
    echo "</form>";
  }

  else
  {
    echo "Your Cart is empty";
  }
  ?>
</html>



<footer>

Thank You For Shopping With Us
<br>
COVID-19 Has NOT Affected Our Shipping Time!
<br>
For more information during COVID-19 Please Contact US!
<br>
CSCI 467 - Group 1A

</footer>
