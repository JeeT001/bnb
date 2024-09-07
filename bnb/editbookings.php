<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include "config.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid booking ID.";
    exit;
}

$bookingId = $_GET['id'];

// Connect to the database
$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);
if (!$DBC) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch booking details
$query = "SELECT * FROM booking WHERE bookingId = $bookingId";
$result = mysqli_query($DBC, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $selectedRoomId = $row['roomId']; // Assuming roomId is stored in the booking table

    // Fetch all available rooms
    $roomsQuery = "SELECT roomId, roomName, roomType, beds FROM room";
    $roomsResult = mysqli_query($DBC, $roomsQuery);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $checkInDate = $_POST['checkInDate'];
        $checkoutDate = $_POST['checkoutDate'];
        $bookingExtras = $_POST['bookingExtras'];
        $roomReview = $_POST['roomReview'];
        $roomId = $_POST['roomId'];

        // Update the booking with the new details
        $updateQuery = "UPDATE booking SET checkInDate = '$checkInDate', checkoutDate = '$checkoutDate', 
                        bookingExtras = '$bookingExtras', roomReview = '$roomReview', roomId = '$roomId'
                        WHERE bookingId = $bookingId";

        if (mysqli_query($DBC, $updateQuery)) {
            echo "Booking updated successfully.";
            header("Location: listbookings.php");
            exit;
        } else {
            echo "Error updating booking: " . mysqli_error($DBC);
        }
    }

    ?>
    <h1>Edit a Booking</h1>

    <h3>
        <a href="listbookings.php">[Return to the Bookings Listings]</a> | 
        <a href="index.php">[Return to the main page]</a>
    </h3> 
    <form method="POST">
        <label for="roomId">Room (name, type, beds):</label>
        <select name="roomId" id="roomId">
            <option value="">Select a Room</option>
            <?php
            // Loop through each room and populate the dropdown
            while ($room = mysqli_fetch_assoc($roomsResult)) {
                // Mark the currently booked room as selected
                $selected = ($room['roomId'] == $selectedRoomId) ? "selected" : "";
                echo "<option value='" . $room['roomId'] . "' $selected>" . 
                     $room['roomName'] . " (" . $room['roomType'] . ", " . $room['beds'] . " beds)" . 
                     "</option>";
            }
            ?>
        </select>
        <br><br>

        <label for="checkInDate">Check-in date:</label>
        <input type="date" name="checkInDate" value="<?php echo $row['checkInDate']; ?>" required>
        <br><br>

        <label for="checkoutDate">Check-out date:</label>
        <input type="date" name="checkoutDate" value="<?php echo $row['checkoutDate']; ?>" required>
        <br><br>

        <label for="bookingExtras">Booking Extras:</label>
        <textarea name="bookingExtras"><?php echo $row['bookingExtras']; ?></textarea>
        <br><br>

        <label for="roomReview">Room Review:</label>
        <textarea name="roomReview"><?php echo $row['roomReview']; ?></textarea>
        <br><br>

        <label for="contactNumber">Contact Number</label>
        <input placeholder="+64 1234567" type="tel" name="contactNumber" id="contactNumber" value="<?php echo $booking['contactNumber']; ?>">
        <br><br>

        <button type="submit">Update</button>
    </form>

    <?php
} else {
    echo "Booking not found.";
}

mysqli_close($DBC);
?>
