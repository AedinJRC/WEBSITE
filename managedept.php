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

    // Handle Add Request
    if (isset($_POST['add'])) {
        $department = trim($_POST['department']);
        if (!empty($department)) {
            $insert_stmt = $conn->prepare("INSERT INTO departmentstb (department) VALUES (?)");
            $insert_stmt->bind_param("s", $department);
            if ($insert_stmt->execute()) {
                $message = "Department '$department' added successfully!";
                header("Location: ".$_SERVER['PHP_SELF']);
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
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            $message = "Error deleting department: " . $conn->error;
        }
        $stmt->close();
    }

    // Fetch records
    $result = $conn->query("SELECT * FROM departmentstb");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
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

        h1, h2, h3 {
            font-family: 'Poppins', sans-serif;
            color: var(--primary-color);
        }

        h2 {
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-color);
            color: var(--primary-color);
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 25px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: var(--border-radius);
        }

        .form-row {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }

        input[type="text"] {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
        }

        input[type="text"]:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
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
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background-color: #f5f7fa;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn i {
            margin-right: 8px;
        }

        .add-btn {
            background-color: var(--success-color);
            color: white;
        }

        .add-btn:hover {
            background-color: #219653;
            transform: translateY(-2px);
        }

        .delete-btn {
            background-color: var(--danger-color);
            color: white;
            padding: 8px 15px;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
        }

        .message i {
            margin-right: 10px;
            font-size: 20px;
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

        .actions-cell {
            display: flex;
            gap: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
        }

        .empty-state i {
            font-size: 50px;
            margin-bottom: 20px;
            color: #bdc3c7;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            
            .form-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            input[type="text"] {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h2><i class=></i> Manage Departments</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'Error') === 0 ? 'error' : 'success'; ?>">
                <i class="fas <?php echo strpos($message, 'Error') === 0 ? 'fa-exclamation-circle' : 'fa-check-circle'; ?>"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="form-group">
            <form method="POST">
                <div class="form-row">
                    <input type="text" name="department" id="department" placeholder="Enter new department name" required>
                    <button type="submit" name="add" class="btn add-btn">
                        <i class="fas fa-plus"></i> Add Department
                    </button>
                </div>
            </form>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th><i class=></i> Department Name</th>
                        <th><i class="fas fa-cog"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["department"]); ?></td>
                        <td class="actions-cell">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <button type="submit" name="delete" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this department?')">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-building"></i>
                <h3>No Departments Found</h3>
                <p>Add your first department using the form above</p>
            </div>
        <?php endif; ?>
    </div>
    <?php $conn->close(); ?>
</body>
</html>

