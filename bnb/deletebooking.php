<?php
include "config.php";

// Check if the booking ID is provided via POST
if (!isset($_POST['bookingId']) || empty($_POST['bookingId'])) {
    echo "Invalid booking ID.";
    exit;
}

$bookingId = $_POST['bookingId']; // Get the booking ID from the form submission

// Connect to the database
$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

// Check if the connection was successful
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL - " . mysqli_connect_error();
    exit;
}

// Prepare the SQL query to delete the booking
$query = "DELETE FROM booking WHERE bookingId = $bookingId";

// Execute the query
if (mysqli_query($DBC, $query)) {
    echo "Booking deleted successfully.";
    header("Location: listbookings.php");
    exit;
} else {
    // If the deletion fails, show an error message
    echo "Error deleting booking: " . mysqli_error($DBC);
}

// Close the database connection
mysqli_close($DBC);
?>
