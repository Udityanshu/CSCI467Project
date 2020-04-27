

<?php

echo '<title>Sean F - CSCI 467 Test - Z1837228</title>';

echo '<h2 align = "center">CSCI 467 Test - Sean F</h2>';

?>

<?php

$username = "student";
$password = "student";

?>

<?php

try
{									// This will attempt to throw an exception if something is incorrect, like a missing username
     $dsn = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
     $pdo = new PDO($dsn, $username, $password);
     echo "The connection was sucessful";
}

catch(PDOexeption $e)
{									// This will help to recognize what the error was, to let the user know that a connection was not made
     echo "Connection was not established: " . $e->getmessage();
}

echo "<p>This is a test</p>";

$username2 = "z1837228";
$password2 = "1996Feb14";

?>

<?php

try
{                                                                       // This will attempt to throw an exception if something is incorrect, like a missing username
     $dsn2 = "mysql:host=courses;dbname=z1837228";
     $pdo2 = new PDO($dsn2, $username2, $password2);
     echo "The connection was sucessful";
}

catch(PDOexeption $e)
{                                                                       // This will help to recognize what the error was, to let the user know that a connection was not made
     echo "Connection was not established: " . $e->getmessage();
}

echo "<p>This is another test</p>";

$sql = "SELECT number, description, price, weight, pictureURL FROM parts";

echo '<table width = "15%" border = "1" cellspacing = "6" cellpadding = "3">';		// Table formatting slider values
echo '<th> Number </th>';
echo '<th> Description </th>';
echo '<th> Price </th>';
echo '<th> Weight </th>';
echo '<th> Picture </th>';
echo '<th> Select </th>';

foreach ($pdo->query($sql) as $rows)				// This loop iterates through the rows and displays each cell's value to the user
{
     echo '<tr>';
     echo '<td>';
     echo $rows['number'];
     echo '<td>';
     echo $rows['description'];
     echo '</td>';
     echo '<td>';
     echo $rows['price'];
     echo '</td>';
     echo '<td>';
     echo $rows['weight'];
     echo '</td>';
     echo '<td>';
     print('<a href="'.$rows['pictureURL'].'">Link</a>');
     echo '</td>';
     echo '<td>';
     echo '<input type="checkbox" name="Value 1" unchecked>';
     echo '</td>';
     echo '</tr>';
}
echo '</table>';
echo '</br>';
echo "<form method=post action=http://students.cs.niu.edu/~z1817662/Project8A/cart.php>";
	//$arraytostring6 = base64_encode(serialize($cartcontents));
	//echo "<input type=hidden name='cart'
	//value=$arraytostring6/>";
    	echo "<input type=submit name='button7'
		value='Your Cart'/>";
	echo "</form>";


?>



