<<<<<<< HEAD
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
        $department = $_POST['department'];
        if (!empty($department)) {
            $insert_stmt = $conn->prepare("INSERT INTO departmentstb (department) VALUES (?)");
            $insert_stmt->bind_param("s", $department);
            if ($insert_stmt->execute()) {
                $message = "Department '$department' added successfully!";
                // Refresh the page to show the new record
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
            // Refresh the page after deletion
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
    <title>Manage Department</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
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
        .btn {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .add-btn {
            background-color: #28a745;
            color: white;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        select {
            padding: 5px;
            margin-right: 10px;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Departments</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'Error') === 0 ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <label for="department">Add Department:</label>
            <select name="department" id="department" required>
                <option value="">Select Department</option>
                <option value="Preschool">Preschool</option>
                <option value="Grade School">Grade School</option>
                <option value="Junior High School">Junior High School</option>
                <option value="Senior High School">Senior High School</option>
                <option value="College">College</option>
            </select>
            <button type="submit" name="add" class="btn add-btn">Add</button>
        </form>
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
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <button type="submit" name="delete" class="btn delete-btn">Delete</button>
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
=======
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
        $department = $_POST['department'];
        if (!empty($department)) {
            $insert_stmt = $conn->prepare("INSERT INTO departmentstb (department) VALUES (?)");
            $insert_stmt->bind_param("s", $department);
            if ($insert_stmt->execute()) {
                $message = "Department '$department' added successfully!";
                // Refresh the page to show the new record
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
            // Refresh the page after deletion
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
    <title>Manage Department</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
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
        .btn {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .add-btn {
            background-color: #28a745;
            color: white;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        select {
            padding: 5px;
            margin-right: 10px;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Departments</h2>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'Error') === 0 ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <label for="department">Add Department:</label>
            <select name="department" id="department" required>
                <option value="">Select Department</option>
                <option value="Preschool">Preschool</option>
                <option value="Grade School">Grade School</option>
                <option value="Junior High School">Junior High School</option>
                <option value="Senior High School">Senior High School</option>
                <option value="College">College</option>
            </select>
            <button type="submit" name="add" class="btn add-btn">Add</button>
        </form>
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
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <button type="submit" name="delete" class="btn delete-btn">Delete</button>
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
>>>>>>> b17d8aaec21949e10024f65c975a61063e7b489c
