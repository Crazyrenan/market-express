<?php
session_start();

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the barangID is set and not empty
    if (isset($_POST["barangID"]) && !empty($_POST["barangID"])) {
        // Sanitize the input
        $barangID = htmlspecialchars($_POST["barangID"]);

        // Perform the deletion from the cartdetail table
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "ebiz";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind the delete statement
        $stmt = $conn->prepare("DELETE FROM cartdetail WHERE barangID = ?");
        $stmt->bind_param("s", $barangID);

        // Execute the delete statement
        if ($stmt->execute()) {
            // Deletion successful
            echo json_encode(array("status" => "success"));
        } else {
            // Deletion failed
            echo json_encode(array("status" => "error", "message" => "Failed to delete item."));
        }

        // Close the statement and database connection
        $stmt->close();
        $conn->close();
    } else {
        // Invalid or empty barangID
        echo json_encode(array("status" => "error", "message" => "Invalid barangID."));
    }
} else {
    // Invalid request method
    echo json_encode(array("status" => "error", "message" => "Invalid request method."));
}
?>
