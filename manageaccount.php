<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vehiclemonitoringdbms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = '';

// Handle Update Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $employeeid = $_POST['employeeid'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $role = $_POST['role'];
    $created_at = $_POST['created_at'];

    $stmt = $conn->prepare("UPDATE usertb SET fname=?, lname=?, role=?, created_at=? WHERE employeeid=?");
    $stmt->bind_param("sssss", $fname, $lname, $role, $created_at, $employeeid);
    
    if ($stmt->execute()) {
        $message = "Account updated successfully!";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        $message = "Error updating account: " . $conn->error;
    }
    $stmt->close();
}

// Handle Delete Request
if (isset($_POST['delete'])) {
    $employeeid = $_POST['employeeid'];
    $stmt = $conn->prepare("DELETE FROM usertb WHERE employeeid=?");
    $stmt->bind_param("s", $employeeid);
    
    if ($stmt->execute()) {
        $message = "Account deleted successfully!";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        $message = "Error deleting account: " . $conn->error;
    }
    $stmt->close();
}

// Fetch records
$selectsql = "SELECT * FROM usertb ORDER BY lname ASC";
$result = $conn->query($selectsql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1500px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: rgb(156, 7, 7);
            color: white;
        }
        input[type="text"], input[type="date"], select {
            border: 1px solid #ddd;
            padding: 5px;
            border-radius: 4px;
            width: 90%;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            margin: 2px;
        }
        .update-btn {
            background-color: #28a745;
            color: white;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .error {
            background-color: #ffdddd;
            color: #d8000c;
        }
        .success {
            background-color: #ddffdd;
            color: #4F8A10;
        }
        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Accounts</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'Error') === 0 ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Profile Picture</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Role</th>
                        <th>Date Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <img src="uploads/<?php echo htmlspecialchars($row['profile_pic'] ?? 'default.jpg'); ?>" 
                                 alt="Profile" class="profile-img">
                        </td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="employeeid" value="<?php echo htmlspecialchars($row['employeeid']); ?>">
                                <input type="text" name="fname" value="<?php echo htmlspecialchars($row['fname']); ?>">
                        </td>
                        <td>
                                <input type="text" name="lname" value="<?php echo htmlspecialchars($row['lname']); ?>">
                        </td>
                        <td>
                                <select name="role">
                                    <option value="Admin" <?php echo $row['role'] == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                                    <option value="User" <?php echo $row['role'] == 'User' ? 'selected' : ''; ?>>User</option>
                                    <option value="Accountant" <?php echo $row['role'] == 'Accountant' ? 'selected' : ''; ?>>Accountant</option>
                                    <option value="GSO" <?php echo $row['role'] == 'GSO' ? 'selected' : ''; ?>>GSO</option>
                                    <option value="GSO Director" <?php echo $row['role'] == 'GSO Director' ? 'selected' : ''; ?>>GSO Director</option>
                                    <option value="Immediate Head" <?php echo $row['role'] == 'Immediate Head' ? 'selected' : ''; ?>>Immediate Head</option>
                                </select>
                        </td>
                        <td>
                                <input type="date" name="created_at" value="<?php echo htmlspecialchars($row['created_at']); ?>">
                        </td>
                        <td>
                                <button type="submit" name="update" class="btn update-btn">Update</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="employeeid" value="<?php echo htmlspecialchars($row['employeeid']); ?>">
                                <button type="submit" name="delete" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this account?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php $conn->close(); ?>
</body>