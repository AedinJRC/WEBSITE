<?php
include("config.php");

// Select all users
$getUsers = "SELECT employeeid, pword FROM usertb";
$result = mysqli_query($conn, $getUsers);

$updated = 0;

while ($row = mysqli_fetch_assoc($result))
{
    $employeeid = $row['employeeid'];
    $password = $row['pword'];

    // Check if password is already hashed
    if (!password_get_info($password)['algo'])
    {
        // Hash old plain-text password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update database
        $update = "UPDATE usertb SET pword = ? WHERE employeeid = ?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param("ss", $hashedPassword, $employeeid);
        $stmt->execute();
        $stmt->close();

        $updated++;
    }
}

echo "<h2>Password migration completed.</h2>";
echo "<p>Updated accounts: $updated</p>";
?>