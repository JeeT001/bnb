<?php
include "config.php"; //load in any variables
$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE) or die();

//do some simple validation to check if sq contains a string
$sq = $_GET['sq'];
$searchresult = '';
if (isset($sq) and !empty($sq) and strlen($sq) < 31) {
    $sq = strtolower($sq);
    $query = "SELECT customerID,firstname,lastname FROM customer WHERE lastname LIKE '$sq%' ORDER BY lastname";
    $result = mysqli_query($DBC,$query);
    $rowcount = mysqli_num_rows($result); 
        //makes sure we have customers
    if ($rowcount > 0) {  
        $rows=[]; //start an empty array
        
        //append each row in the query result to our empty array until there are no more results                    
        while ($row = mysqli_fetch_assoc($result)) {            
            $rows[] = $row; 
        }

        $searchresult = json_encode($rows);
        //this line is cruicial for the browser to understand what data is being sent
        header('Content-Type: text/json; charset=utf-8');
    } else echo "<tr><td colspan=3><h2>No Customers found!</h2></td></tr>";
} else echo "<tr><td colspan=3> <h2>Invalid search query</h2>";
mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done

echo  $searchresult;