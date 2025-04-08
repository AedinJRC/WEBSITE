<?php
$servername = "localhost"; // Change if needed
$username = "root"; // Change for production
$password = ""; // Your MySQL password
$dbname = "testdb"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!empty($_POST['names'])) {
    $stmt = $conn->prepare("INSERT INTO users (name) VALUES (?)");

    foreach ($_POST['names'] as $name) {
        $stmt->bind_param("s", $name);
        $stmt->execute();
    }

    echo "Records inserted successfully.";
} else {
    echo "No names were provided.";
}

// Close connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Multiple Names</title>
    <script>
        function addField() {
            let container = document.getElementById("input-container");
            let input = document.createElement("input");
            input.type = "text";
            input.name = "names[]"; // Use an array name
            input.placeholder = "Enter Name";
            container.appendChild(document.createElement("br"));
            container.appendChild(input);
        }
    </script>
</head>
<body>
    <form action="insert.php" method="POST">
        <div id="input-container">
            <input type="text" name="names[]" placeholder="Enter Name" required>
        </div>
        <br>
        <button type="button" onclick="addField()">Add More</button>
        <br><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
