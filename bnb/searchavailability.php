<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "config.php";
$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

if (mysqli_connect_errno()) {
    echo "<p>Error: Unable to connect to MySQL - " . mysqli_connect_error() . "</p>";
    exit;
}

if (isset($_GET['from']) && isset($_GET['to'])) {
    $from = $_GET['from'];
    $to = $_GET['to'];

    // Fetch available rooms based on dates
    $query = "
        SELECT roomId, roomName, roomType, beds 
        FROM room 
        WHERE roomId NOT IN (
            SELECT roomId FROM booking 
            WHERE ('$from' <= checkoutDate AND '$to' >= checkInDate)
        )
    ";

    $result = mysqli_query($DBC, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1'>
            <tr><th>Room#</th><th>Room name</th><th>Room type</th><th>Beds</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>" . htmlspecialchars($row['roomId']) . "</td>
                <td>" . htmlspecialchars($row['roomName']) . "</td>
                <td>" . htmlspecialchars($row['roomType']) . "</td>
                <td>" . htmlspecialchars($row['beds']) . "</td>
              </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No rooms available for the selected dates.</p>";
    }

    mysqli_free_result($result);
}
?>
