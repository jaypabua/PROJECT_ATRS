<?php
session_start(); // Start the session

include_once './config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/flights.css">
    <link rel="icon" href="./assets/images/favicon.jpg">
    <title>Skyline - Flight Search Results</title>
</head>
<body>

<header>
    <div class="logo">
        <img src="/assets/images/logo.jpg" alt="Airline Logo">
        <div class="title">
            <h1>Search Flight Results</h1>
        </div>
    </div>
    <nav>
    <ul>
        <li><a href="/dashboard.php">Dashboard</a></li>
        <li><a href="/index.php">Home</a></li>
        <li><a href="/offers.php">Offers</a></li>
        <?php
        if(isset($_SESSION['username'])) {
            // If the user is logged in, display a welcome message which will serve as the dropdown button
            echo '<li class="dropdown">'; // Add the "dropdown" class to the list item
            echo '<a class="dropbtn">Hello, ' . $_SESSION['username'] . '</a>'; // Change button to anchor tag
            echo '<div class="dropdown-content">';
            echo '<a href="#">Profile</a>';
            echo '<a href="logout.php" class="logout">Logout</a>';
            echo '</div>';
            echo '</li>';
        } else {
            // If the user is not logged in, display a login link
            echo '<li><a href="login.php">Login</a></li>';
        }
        ?>
    </ul>  
</nav>

</header> 

<main>
    <?php
    if ($conn) {
        // Retrieve user input from the form
        if(isset($_GET['locationInput']) && isset($_GET['destinationInput']) && isset($_GET['departureInput'])) {
            $location = $_GET['locationInput'];
            $destination = $_GET['destinationInput'];
            $departureDate = $_GET['departureInput'];

            // Perform a database query to search for flights based on user input
            $sql = "SELECT * FROM flights WHERE departure_location LIKE '%$location%' AND arrival_location LIKE '%$destination%'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output table header
                echo "<table>";
                echo "<tr><th>Flight Number</th><th>Departure</th><th>Departure Date</th><th>Departure-Time</th><th>Arrival</th><th>Arrival Date</th><th>Arrival-Time</th><th>Price</th><th>Book</th></tr>";

                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["flight_number"] . "</td>";
                    echo "<td>" . $row["departure_location"] . "</td>";
                    echo "<td>" . $departureDate . "</td>"; 
                    echo "<td>" . $row["Departure-Time"] . "</td>";
                    echo "<td>" . $row["arrival_location"] . "</td>";
                    echo "<td>" . $departureDate . "</td>"; 
                    echo "<td>" . $row["Arrival-Time"] . "</td>"; 
                    echo "<td>₱" . $row["price"] . "</td>";
                    // Check if user is logged in
                    if(isset($_SESSION['username'])) {
                        echo "<td><button class='book-now-button' data-flight-id='" . $row["flight_number"] . "'>Book Now</button></td>";
                    } else {
                        echo "<td><button disabled>Log in to Book</button></td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No flights found.";
            }
        }
    } else {
        echo "Connection failed: " . $conn->connect_error;
    }
    ?>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Skyline Airways PH</p>
</footer>
</body>
</html>
