<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Booking</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
</head>
<body>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include "config.php";
    $DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

    if(mysqli_connect_errno()){
        echo "<p>Error: Unable to connect to MySQL - " . mysqli_connect_error() . "</p>";
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $roomId = $_POST['room'];
        $customerId = $_POST['customer'];
        $checkin = $_POST['checkin'];
        $checkout = $_POST['checkout'];
        $phone = $_POST['phone'];
        $extras = $_POST['extras'];

        // Prepare and bind
        $stmt = $DBC->prepare("INSERT INTO booking ( roomId, clientId, checkInDate, checkoutDate, phone, bookingExtras) VALUES ( ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $roomId, $customerId, $checkin, $checkout, $phone, $extras);

        // Execute the query
        if ($stmt->execute()) {
            echo "<p>Booking successfully made!</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }

        // Close the statement
        $stmt->close();
    }
    ?>

    <h1>Make a Booking</h1>
    <h3>
        <a href="listbookings.php">Return to the Bookings Listings</a> |
        <a href="index.php">Return to the main page</a>
    </h3>

    <form action="makeBooking.php" method="post">
        <!-- Room selection -->
        <label for="room">Room (name, type, beds):</label>
        <select name="room" id="room" required>
            <option value="">Select a room</option>
            <?php
            // Fetch rooms from the database
            $roomQuery = "SELECT roomId, roomName FROM room";
            $roomResult = mysqli_query($DBC, $roomQuery);
            while ($roomRow = mysqli_fetch_assoc($roomResult)) {
                echo "<option value='" . htmlspecialchars($roomRow['roomId']) . "'>" . htmlspecialchars($roomRow['roomName']) . "</option>";
            }
            mysqli_free_result($roomResult);
            ?>
        </select>
        <br>

        <!-- Customer selection -->
        <label for="customer">Customer:</label>
        <select name="customer" id="customer" required>
            <option value="">Select a customer</option>
            <?php
            // Fetch customers from the database
            $customerQuery = "SELECT customerID, CONCAT(firstName, ' ', lastName) AS fullName FROM customer";
            $customerResult = mysqli_query($DBC, $customerQuery);
            while ($customerRow = mysqli_fetch_assoc($customerResult)) {
                echo "<option value='" . htmlspecialchars($customerRow['customerID']) . "'>" . htmlspecialchars($customerRow['fullName']) . "</option>";
            }
            mysqli_free_result($customerResult);
            ?>
        </select>
        <br>

        <!-- Check-in and Check-out dates -->
        <label for="checkin">Check-in date:</label>
        <input type="text" name="checkin" id="checkin" class="myDatePicker" required>
        <br>

        <label for="checkout">Check-out date:</label>
        <input type="text" name="checkout" id="checkout" class="myDatePicker" required>
        <br>

        <!-- Contact Number -->
        <label for="phone">Contact Number:</label>
        <input type="tel" name="phone" id="phone" placeholder="(+64) 123-1234" pattern="\(\+64\) \d{3}-\d{4}" required>
        <br>

        <!-- Booking Extras -->
        <label for="extras">Booking Extras:</label>
        <textarea name="extras" id="extras" rows="4"></textarea>
        <br>

        <!-- Submit and Cancel -->
        <button type="submit">Add Booking</button>
        <!-- <a href="listbookings.php">Cancel</a> -->
    </form>

    <h3>Search for room availability</h3>
    <form id="searchForm">
        <label for="from">From:</label>
        <input type="text" id="from" name="from" class="myDatePicker">
        <label for="to">To:</label>
        <input type="text" id="to" name="to" class="myDatePicker">
        <button type="button" onclick="searchAvailability()">Search</button>
    </form>

    <!-- Placeholder for search results -->
    <div id="searchResults"></div>

    <script>
        $( ".myDatePicker" ).datepicker({
            numberOfMonths: 2,
            changeMonth: true,
            dateFormat: "yy-mm-dd",
            minDate: new Date()
        });

        $( function() {
            var dateFormat = "yy-mm-dd",
                from = $( "#from" )
                    .datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        numberOfMonths: 2
                    })
                    .on( "change", function() {
                        to.datepicker( "option", "minDate", getDate( this ) );
                    }),
                to = $( "#to" ).datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 2
                })
                .on( "change", function() {
                    from.datepicker( "option", "maxDate", getDate( this ) );
                });

            function getDate( element ) {
                var date;
                try {
                    date = $.datepicker.parseDate( dateFormat, element.value );
                } catch( error ) {
                    date = null;
                }
                return date;
            }
        });

        function searchAvailability() {
            var from = $('#from').val();
            var to = $('#to').val();

            if (from && to) {
                $.ajax({
                    url: 'searchavailability.php',
                    type: 'GET',
                    data: {
                        from: from,
                        to: to
                    },
                    success: function(data) {
                        $('#searchResults').html(data);
                    },
                    error: function() {
                        $('#searchResults').html('<p>Error occurred while fetching the availability data.</p>');
                    }
                });
            } else {
                $('#searchResults').html('<p>Please select both From and To dates.</p>');
            }
        }
    </script>
</body>
</html>
