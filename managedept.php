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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Department</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-5">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md overflow-hidden p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Manage Departments</h2>
        
        <?php if (!empty($message)): ?>
            <div class="p-4 mb-6 rounded-lg <?php echo strpos($message, 'Error') === 0 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="mb-8">
            <label for="dept_name" class="block text-sm font-medium text-gray-700 mb-2"><strong>Add Department</strong></label>
            <div class="flex">
                <input type="text" name="department" id="dept_name1" required placeholder="Enter Department Name" 
                    class="flex-grow px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                <button type="submit" name="add" class="px-4 py-2 bg-green-600 text-white font-medium rounded-r-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Add
                </button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-red-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Department
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">
                            <?php echo htmlspecialchars($row["department"]); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form method="POST" class="inline">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <button type="submit" name="delete" class="px-3 py-1 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    Delete
                                </button>
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
</html>
