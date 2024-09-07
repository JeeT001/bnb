<?php
// Include the database configuration file
include "config.php";

// Check if the booking ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid booking ID.";
    exit;
}

$bookingId = $_GET['id']; // Get the booking ID from the URL

// Connect to the database
$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

// Check if the connection was successful
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL - " . mysqli_connect_error();
    exit;
}

// Fetch the booking details for confirmation
$query = "SELECT booking.bookingId, room.roomName, booking.checkInDate, booking.checkoutDate, booking.phone, booking.bookingExtras, booking.roomReview
          FROM booking 
          JOIN room ON booking.roomId = room.roomId 
          JOIN customer ON booking.clientId = customer.customerID 
          WHERE booking.bookingId = $bookingId";
          
$result = mysqli_query($DBC, $query);

if ($row = mysqli_fetch_assoc($result)) {
    // Display the booking details and ask for confirmation
    echo "<h1>Room Details View</h1>";
    echo "<h3><a href='listbookings.php'>[Return to the Bookings Listings]</a> | <a href='index.php'>[Return to the main page]</a></h3>";


    echo "<fieldset>
             <legend>Booking Detail</legend>
             <p>Room Name: {$row['roomName']}</p>
             <p>Check-in Date: {$row['checkInDate']}</p>
             <p>Checkout Date: {$row['checkoutDate']}</p>
             <p>Contact number: {$row['phone']}</p>
             <p>Extras: {$row['bookingExtras']}</p>
             <p>Room Review: {$row['roomReview']}</p>
          </fieldset>";

    echo "<h2>Are you sure you want to delete the booking?</h2    >";


    echo "<form method='post' action='deletebooking.php'>
            <input type='hidden' name='bookingId' value='$bookingId'>
            <button type='submit'>Delete</button>
            <a href='listbookings.php'>Cancel</a>
          </form>";
} else {
    echo "Booking not found.";
}


mysqli_close($DBC);
?>
