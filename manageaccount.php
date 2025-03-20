<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vehiclemonitoringdbms";

$conn = mysqli_connect($servername, $username, $password, $dbname);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employeeid'], $_POST['fname'], $_POST['lname'], $_POST['role'], $_POST['created_at'])) {
    $employeeid = $_POST['employeeid'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $role = $_POST['role'];
    $created_at = $_POST['created_at'];

    $sql = "UPDATE usertb SET fname='$fname', lname='$lname', role='$role', created_at='$created_at' WHERE employeeid='$employeeid'";
    mysqli_query($conn, $sql);
}

mysqli_close($conn);
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
        input[type="file"], input[type="text"], input[type="date"], select {
            border: none;
            background: transparent;
            outline: none;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .edit-btn {
            background-color: #28a745;
            color: white;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Accounts</h2>
        <div class="table-container">
            <?php
                include "config.php";
                $selectsql = "SELECT * FROM usertb ORDER BY lname ASC";
                $resultsql = $conn->query($selectsql);
                
                
            ?>
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
                    <tr>
                        <td><img src="uploads/" alt=""></td>
                        <td><input type="text" placeholder="First Name"></td>
                        <td><input type="text" placeholder="Last Name"></td>
                        <td>
                            <select>
                                <option>Admin</option>
                                <option>User</option>
                                <option>Accountant</option>
                                <option>GSO</option>
                                <option>GSO Director</option>
                                <option>Immediate Head</option>
                            </select>
                        </td>
                        <td><input type="date"></td>
                        <td>
                            <button class="btn edit-btn">Edit</button>
                            <button class="btn delete-btn">Delete</button>
                        </td>
                    </tr>
                        <?php
                        while ($row = $resultsql->fetch_assoc()) 
                        {
                            echo "<tr>";
                                echo "<td><img src='uploads/' alt=''></td>";
                                echo "<td><input type='text' value='" . $row['fname'] . "'></td>";
                                echo "<td><input type='text' value='" . $row['lname'] . "'></td>";
                                echo "<td><input type='text' value='" . $row['role'] . "'></td>";
                                echo "<td><input type='text' value='" . $row['created_at'] . "'></td>";
                            echo "</tr>";
                        }
                        ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
