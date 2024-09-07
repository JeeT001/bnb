<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Bookings</title>
</head>
<body>

    <?php


    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    include "config.php";
    $DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

    if(mysqli_connect_errno()){
       echo "Error: Unable to connect to MySQL - " . mysqli_connect_error();
       exit; // Stop processing the page further
    }

    // SQL query to retrieve booking data
    $query = "SELECT booking.bookingId, room.roomName, booking.checkInDate, booking.checkoutDate, customer.firstName, customer.lastName 
              FROM booking 
              JOIN room ON booking.roomId = room.roomId 
              JOIN customer ON booking.clientId = customer.customerID";

    $result = mysqli_query($DBC, $query);

    if ($result) {
        echo "<h1>Current Bookings</h1>";
        echo "<h3>
            <a href='makeBooking.php'>[Make Bookings]</a>
            <a href='index.php'>[Return to the main page]</a>
        </h3>";

        echo "<table border='1'>
            <thead>
                <tr>
                    <th>Booking (Room, Dates)</th>
                    <th>Customer</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>";

        // Fetch and display each row of data
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['roomName']} (Check-in: {$row['checkInDate']}, Check-out: {$row['checkoutDate']})</td>
                    <td>{$row['firstName']} {$row['lastName']}</td>
                    <td><a href='bookingDetails.php?id={$row['bookingId']}'>View</a> | <a href='editbookings.php?id={$row['bookingId']}'>Edit</a> | <a href='deletebookingconfirm.php?id={$row['bookingId']}'>Delete</a> | <a href='managereview.php?id={$row['bookingId']}'>Manage Review</a> </td>
                  </tr>";
        }

        echo "</tbody>
        </table>";

        // Free the result set
        mysqli_free_result($result);
    } else {
        echo "<p>No bookings found.</p>";
    }

    // Close the database connection
    mysqli_close($DBC);
    ?>

</body>
</html>
