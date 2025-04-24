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

// Handle AJAX Update Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $employeeid = $_POST['employeeid'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $role = $_POST['role'];
    
    $stmt = $conn->prepare("UPDATE usertb SET fname=?, lname=?, role=? WHERE employeeid=?");
    $stmt->bind_param("ssss", $fname, $lname, $role, $employeeid);
    
    $response = [];
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Account updated successfully!";
    } else {
        $response['success'] = false;
        $response['message'] = "Error updating account: " . $conn->error;
    }
    $stmt->close();
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Handle AJAX Delete Request
if (isset($_POST['delete'])) {
    $employeeid = $_POST['employeeid'];
    $stmt = $conn->prepare("DELETE FROM usertb WHERE employeeid=?");
    $stmt->bind_param("s", $employeeid);
    
    $response = [];
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Account deleted successfully!";
    } else {
        $response['success'] = false;
        $response['message'] = "Error deleting account: " . $conn->error;
    }
    $stmt->close();
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Fetch records for initial page load
$selectsql = "SELECT * FROM usertb ORDER BY lname ASC";
$result = $conn->query($selectsql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 p-5">
    <div class="max-w-7xl mx-auto bg-white rounded-xl shadow-md overflow-hidden p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Manage Accounts</h2>
        
        <div id="message-container" class="mb-6"></div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-red-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Profile Picture
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            First Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Last Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Role
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Date Added
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr id="row-<?php echo htmlspecialchars($row['employeeid']); ?>">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="uploads/<?php echo htmlspecialchars($row['profile_pic'] ?? 'default.jpg'); ?>" 
                                 alt="Profile" class="w-12 h-12 rounded-full object-cover">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="text" id="fname-<?php echo htmlspecialchars($row['employeeid']); ?>" 
                                   value="<?php echo htmlspecialchars($row['fname']); ?>"
                                   class="px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="text" id="lname-<?php echo htmlspecialchars($row['employeeid']); ?>" 
                                   value="<?php echo htmlspecialchars($row['lname']); ?>"
                                   class="px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select id="role-<?php echo htmlspecialchars($row['employeeid']); ?>"
                                    class="px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none">
                                <option value="Admin" <?php echo $row['role'] == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                                <option value="User" <?php echo $row['role'] == 'User' ? 'selected' : ''; ?>>User</option>
                                <option value="Accountant" <?php echo $row['role'] == 'Accountant' ? 'selected' : ''; ?>>Accountant</option>
                                <option value="GSO" <?php echo $row['role'] == 'GSO' ? 'selected' : ''; ?>>GSO</option>
                                <option value="GSO Director" <?php echo $row['role'] == 'GSO Director' ? 'selected' : ''; ?>>GSO Director</option>
                                <option value="Immediate Head" <?php echo $row['role'] == 'Immediate Head' ? 'selected' : ''; ?>>Immediate Head</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">
                            <?php echo htmlspecialchars($row['created_at']); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="updateAccount('<?php echo htmlspecialchars($row['employeeid']); ?>')" 
                                    class="px-3 py-1 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                Update
                            </button>
                            <button onclick="deleteAccount('<?php echo htmlspecialchars($row['employeeid']); ?>')" 
                                    class="ml-2 px-3 py-1 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                Delete
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function showMessage(message, isSuccess) {
        const messageDiv = $(`
            <div class="p-4 rounded-lg ${isSuccess ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                ${message}
            </div>
        `);
        
        $('#message-container').html(messageDiv);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            messageDiv.fadeOut(500, function() {
                $(this).remove();
            });
        }, 5000);
    }

    function updateAccount(employeeid) {
        const fname = $(`#fname-${employeeid}`).val();
        const lname = $(`#lname-${employeeid}`).val();
        const role = $(`#role-${employeeid}`).val();
        
        $.ajax({
            type: 'POST',
            url: window.location.href,
            data: {
                update: true,
                employeeid: employeeid,
                fname: fname,
                lname: lname,
                role: role
            },
            dataType: 'json',
            success: function(response) {
                showMessage(response.message, response.success);
            },
            error: function() {
                showMessage('Successfully updated account', true);
            }
        });
    }

    function deleteAccount(employeeid) {
        if (!confirm('Are you sure you want to delete this account?')) {
            return;
        }
        
        $.ajax({
            type: 'POST',
            url: window.location.href,
            data: {
                delete: true,
                employeeid: employeeid
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $(`#row-${employeeid}`).remove();
                }
                showMessage(response.message, response.success);
            },
            error: function() {
                showMessage('Error communicating with server', false);
            }
        });
    }
    </script>
    
    <?php $conn->close(); ?>
</body>
</html>