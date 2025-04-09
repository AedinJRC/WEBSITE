<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vehiclemonitoringdbms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

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

$selectsql = "SELECT * FROM usertb ORDER BY lname ASC";
$result = $conn->query($selectsql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --border-radius: 8px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        h2 {
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-color);
            color: var(--primary-color);
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }

        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
        }

        .error {
            background-color: #fdecea;
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        .success {
            background-color: #e8f5e9;
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: rgb(156, 7, 7);
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background-color: rgb(235, 236, 240);
        }

        input[type="text"], input[type="date"], select {
            border: 1px solid #ddd;
            padding: 8px;
            border-radius: var(--border-radius);
            width: 100%;
            font-size: 14px;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            border-radius: var(--border-radius);
            margin: 2px;
            font-weight: 500;
            transition: var(--transition);
        }

        .update-btn {
            background-color: var(--success-color);
            color: white;
        }

        .update-btn:hover {
            background-color: #2ecc71;
        }

        .delete-btn {
            background-color: var(--danger-color);
            color: white;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class=></i> Manage Accounts</h2>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'Error') === 0 ? 'error' : 'success'; ?>">
                <i class="fas <?php echo strpos($message, 'Error') === 0 ? 'fa-exclamation-circle' : 'fa-check-circle'; ?>"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <form method="POST">
                        <td><input type="text" name="employeeid" value="<?php echo htmlspecialchars($row['employeeid']); ?>" readonly></td>
                        <td><input type="text" name="fname" value="<?php echo htmlspecialchars($row['fname']); ?>"></td>
                        <td><input type="text" name="lname" value="<?php echo htmlspecialchars($row['lname']); ?>"></td>
                        <td>
                            <select name="role">
                                <option value="Admin" <?php if ($row['role'] == 'Admin') echo 'selected'; ?>>Admin</option>
                                <option value="Employee" <?php if ($row['role'] == 'Employee') echo 'selected'; ?>>Employee</option>
                            </select>
                        </td>
                        <td><input type="date" name="created_at" value="<?php echo htmlspecialchars($row['created_at']); ?>"></td>
                        <td>
                            <button type="submit" name="update" class="btn update-btn"><i class=></i> Update</button>
                            <button type="submit" name="delete" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this account?')"><i class=></i> Delete</button>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php $conn->close(); ?>
</body>
</html>

