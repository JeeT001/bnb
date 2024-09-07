<?php
include "config.php";

// Check if the booking ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid booking ID.";
    exit;
}

$bookingId = $_GET['id']; // Get the booking ID from the URL

// Connect to the database
$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

// Fetch the booking details for display
$query = "SELECT booking.bookingId, room.roomname, booking.checkInDate, booking.checkoutDate, customer.firstname, customer.lastname, booking.bookingExtras, booking.roomReview
          FROM booking 
          JOIN room ON booking.roomId = room.roomID
          JOIN customer ON booking.clientId = customer.customerID 
          WHERE booking.bookingId = $bookingId";

$result = mysqli_query($DBC, $query);

if ($row = mysqli_fetch_assoc($result)) {
    echo "<h1>Booking Details View</h1>";
    echo "<h3><a href='listbookings.php'>[Return to the Bookings Listings]</a> | <a href='index.php'>[Return to the main page]</a></h3>";

    echo "<fieldset>
             <legend>Room Detail</legend>
             <p>Username: {$row['firstname']} {$row['lastname']}</p>
             <p>Check-in Date: {$row['checkInDate']}</p>
             <p>Checkout Date: {$row['checkoutDate']}</p>
             <p>Contact number: {$row['contactNumber']}</p>
             <p>Extras: {$row['bookingExtras']}</p>
             <p>Room Review: {$row['roomReview']}</p>
          </fieldset>";
} else {
    echo "Booking not found.";
}

// Close the database connection
mysqli_close($DBC);
?>
