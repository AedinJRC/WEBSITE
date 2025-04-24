<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Department</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f3f4f6;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 24px;
        }
        
        h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 24px;
        }
        
        .alert {
            padding: 16px;
            margin-bottom: 24px;
            border-radius: 8px;
        }
        
        .alert.success {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .alert.error {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        
        .form-group {
            margin-bottom: 32px;
        }
        
        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }
        
        .input-group {
            display: flex;
            width: 100%;
        }
        
        input[type="text"] {
            flex-grow: 1;
            padding: 10px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px 0 0 8px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        input[type="text"]:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
        }
        
        button[type="submit"].add-btn {
            padding: 10px 16px;
            background-color: #16a34a;
            color: white;
            font-weight: 500;
            border: none;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        button[type="submit"].add-btn:hover {
            background-color: #15803d;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background-color: #b91c1c;
        }
        
        th {
            padding: 12px 24px;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 500;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        td {
            padding: 16px 24px;
            font-weight: 600;
            color: #111827;
        }
        
        tr {
            border-bottom: 1px solid #e5e7eb;
        }
        
        tr:last-child {
            border-bottom: none;
        }
        
        .delete-btn {
            padding: 6px 12px;
            background-color: #dc2626;
            color: white;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .delete-btn:hover {
            background-color: #b91c1c;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 16px;
            }
            
            th, td {
                padding: 12px 16px;
            }
        }
        
        @media (max-width: 640px) {
            body {
                padding: 12px;
            }
            
            h2 {
                font-size: 1.25rem;
            }
            
            th, td {
                padding: 8px 12px;
                font-size: 0.875rem;
            }
            
            .input-group {
                flex-direction: column;
            }
            
            input[type="text"] {
                border-radius: 8px;
                margin-bottom: 8px;
            }
            
            button[type="submit"].add-btn {
                border-radius: 8px;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Departments</h2>
        
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
                        $message = "Department $department added successfully!";
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
                } else {
                    $message = "Error deleting department: " . $conn->error;
                }
                $stmt->close();
            }

            // Fetch records
            $result = $conn->query("SELECT * FROM departmentstb");
        ?>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo strpos($message, 'Error') === 0 ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="form-group">
            <label for="dept_name"><strong>Add Department</strong></label>
            <div class="input-group">
                <input type="text" name="department" id="dept_name" required placeholder="Enter Department Name">
                <button type="submit" name="add" class="add-btn">Add</button>
            </div>
        </form>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["department"]); ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <button type="submit" name="delete" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php $conn->close(); ?>
    </div>
</body>
</html>