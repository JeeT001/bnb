<?php
include "config.php";


if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid booking ID.";
    exit;
}

$bookingId = $_GET['id']; 
// Get the booking ID from the URL

// Connect to the database
$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);


$query = "SELECT roomReview FROM booking WHERE bookingId = $bookingId";
$result = mysqli_query($DBC, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $roomReview = $row['roomReview'];
} else {
    echo "Booking not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission 
    $newReview = mysqli_real_escape_string($DBC, $_POST['roomReview']); // Sanitize input

    $updateQuery = "UPDATE booking SET roomReview = '$newReview' WHERE bookingId = $bookingId";
    if (mysqli_query($DBC, $updateQuery)) {
        echo "Review updated successfully.";
        header("Location: listbookings.php");
        exit;
    } else {
        echo "Error updating review: " . mysqli_error($DBC);
    }
}

// Display the review form
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit/Add Room Review</title>
</head>
<body>

    <h1>Edit/Add Room Review</h1>
    <h3><a href="listbookings.php">[Return to the Bookings Listings]</a> | <a href="index.php">[Return to the main page]</a></h3> 

    <form method="post" action="">
        <label for="roomReview">Room Review</label>
        <textarea name="roomReview" id="roomReview"><?php echo htmlspecialchars($roomReview); ?></textarea>
        <br><br>
        <button type="submit">Update</button>
    </form>

</body>
</html>

<?php

mysqli_close($DBC);
?>
