<?php
    ob_start(); // Start output buffering

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

    // Handle Add Request
    if (isset($_POST['add'])) {
        $department = trim($_POST['department']);
        if (!empty($department)) {
            $insert_stmt = $conn->prepare("INSERT INTO departmentstb (department) VALUES (?)");
            $insert_stmt->bind_param("s", $department);
            if ($insert_stmt->execute()) {
                $message = "Department '$department' added successfully!";
                header("Location: ".$_SERVER['PHP_SELF']); // Refresh page
                exit();
            } else {
                $message = "Error adding department: " . $conn->error;
            }
            $insert_stmt->close();
        }
    }

    // Handle Delete Request
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM departmentstb WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = "Department deleted successfully!";
            header("Location: ".$_SERVER['PHP_SELF']); // Refresh page
            exit();
        } else {
            $message = "Error deleting department: " . $conn->error;
        }
        $stmt->close();
    }

    // Fetch records
    $result = $conn->query("SELECT * FROM departmentstb");

    ob_end_flush(); // End output buffering
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Department</title>
    <style>
        :root {
            --primary-color: #3498db;
            --danger-color: #e74c3c;
            --success-color: #2ecc71;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --border-radius: 6px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .dept-container {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        h2 {
            color: var(--dark-color);
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-color);
            font-weight: 600;
        }

        .add-form {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            align-items: flex-end;
        }

        .form-group {
            flex-grow: 1;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--dark-color);
        }

        input[type="text"] {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
        }

        input[type="text"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: var(--dark-color);
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: var(--transition);
            font-size: 14px;
        }

        .btn-add {
            background-color: var(--success-color);
            color: white;
            height: 40px;
        }

        .btn-add:hover {
            background-color: #27ae60;
        }

        .btn-delete {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        .dept-message {
            padding: 15px;
            margin: 20px 0;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
        }

        .dept-error {
            background-color: #fdecea;
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        .dept-success {
            background-color: #e8f5e9;
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .actions-cell {
            display: flex;
            justify-content: flex-end;
        }

        @media (max-width: 768px) {
            .add-form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .btn-add {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="dept-container">
        <h2>Manage Departments</h2>
        
        <?php if (!empty($message)): ?>
            <div class="dept-message <?php echo strpos($message, 'Error') === 0 ? 'dept-error' : 'dept-success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="add-form">
            <div class="form-group">
                <label for="dept_name">Department Name</label>
                <input type="text" name="department" id="dept_name" required placeholder="Enter department name">
            </div>
            <button type="submit" name="add" class="btn btn-add">Add Department</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Department</th>
                    <th class="actions-cell">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["department"]); ?></td>
                    <td class="actions-cell">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <button type="submit" name="delete" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this department?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php $conn->close(); ?>
</body>
</html>

