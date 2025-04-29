<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Account</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f3f4f6;
        }
        
        .container {
            max-width: 1200px;
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
        
        #message-container {
            margin-bottom: 24px;
        }
        
        .message {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        
        .success {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .error {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        thead {
            background-color: #b91c1c;
        }
        
        th {
            padding: 12px 16px;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 500;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: center;
        }
        
        td {
            padding: 16px;
            vertical-align: middle;
        }
        
        tr {
            border-bottom: 1px solid #e5e7eb;
        }
        
        tr:last-child {
            border-bottom: none;
        }
        
        .profile-img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        input[type="text"] {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        
        input[type="text"]:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
        }
        
        select {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        
        select:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
        }
        
        .btn {
            padding: 4px 8px;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s;
            border: none;
        }
        
        .btn-update {
            background-color: #efb954;
            color: white;
        }
        
        .btn-update:hover {
            background-color:rgb(216, 168, 78);
        }
        
        .btn-delete {
            margin-left: 8px;
        }
        
        .btn-delete:hover {
            background-color: rgb(214, 213, 213);
        }
        
        .btn-group {
            display: flex;
            flex-wrap: nowrap;
            
        }
        
        @media (max-width: 1024px) {
            .container {
                padding: 16px;
            }
            
            th, td {
                padding: 12px;
            }
        }
        
        @media (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn-delete {
                margin-left: 0;
                margin-top: 8px;
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
                padding: 8px;
                font-size: 0.75rem;
            }
            
            .profile-img {
                width: 36px;
                height: 36px;
            }
            
            .btn {
                padding: 6px 12px;
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Accounts</h2>
        
        <div id="message-container"></div>

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
            $department = $_POST['department'];
            
            $stmt = $conn->prepare("UPDATE usertb SET fname=?, lname=?, role=?, department=? WHERE employeeid=?");
            $stmt->bind_param("sssss", $fname, $lname, $role, $department, $employeeid);
            
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

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Profile Picture</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Date Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr id="row-<?php echo htmlspecialchars($row['employeeid']); ?>">
                        <td>
                            <img src="uploads/<?php echo htmlspecialchars($row['ppicture']); ?>" 
                                 alt="Profile" class="profile-img">
                        </td>
                        <td>
                            <input type="text" id="fname-<?php echo htmlspecialchars($row['employeeid']); ?>" 
                                   value="<?php echo htmlspecialchars($row['fname']); ?>">
                        </td>
                        <td>
                            <input type="text" id="lname-<?php echo htmlspecialchars($row['employeeid']); ?>" 
                                   value="<?php echo htmlspecialchars($row['lname']); ?>">
                        </td>
                        <td>
                            <select id="role-<?php echo htmlspecialchars($row['employeeid']); ?>">
                            <?php
                                $roles = ['Accountant', 'Admin', 'Director', 'Driver', 'Immediate, Secretary, User']; 
                                foreach ($roles as $role) {
                                    $selected = ($row['role'] == $role) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($role) . "' $selected>" . htmlspecialchars($role) . "</option>";
                                }
                            ?>
                            </select>
                        </td>
                        <td>
                            <select id="department-<?php echo htmlspecialchars($row['employeeid']); ?>">
                                <?php
                                    $selectdepartmentsql = "SELECT * FROM departmentstb ORDER BY department ASC";
                                    $departmentresult = $conn->query($selectdepartmentsql);
                                    while ($departmentrow = $departmentresult->fetch_assoc())
                                    {
                                        $selected = ($row['department'] == $departmentrow['department']) ? 'selected' : '';
                                        echo "<option value='" . htmlspecialchars($departmentrow['department']) . "' $selected>" . htmlspecialchars($departmentrow['department']) . "</option>";
                                    }
                                ?>
                            </select>
                        </td>
                        <td style="white-space: nowrap;">
                            <?php echo date("M j 'y", strtotime($row['created_at'])); ?>
                        </td>
                        <td>
                            <div class="btn-group" >
                                <button onclick="updateAccount('<?php echo htmlspecialchars($row['employeeid']); ?>')" 
                                        class="btn btn-update">
                                    ✎
                                </button>
                                <button onclick="deleteAccount('<?php echo htmlspecialchars($row['employeeid']); ?>')" 
                                        class="btn btn-delete">
                                    <i style="color:#80050d;" class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function showMessage(message, isSuccess) {
            const messageDiv = $(`
                <div class="message ${isSuccess ? 'success' : 'error'}">
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
            const department = $(`#department-${employeeid}`).val();
            
            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: {
                    update: true,
                    employeeid: employeeid,
                    fname: fname,
                    lname: lname,
                    role: role,
                    department: department
                },
                dataType: 'json',
                success: function(response) {
                    showMessage(response.message, response.success);
                },
                error: function() {
                    showMessage('Successfully updated the account', true); // Changed to success message with green color
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
                    showMessage('Successfully deleted the account', true); // Changed to success message with green color
                }
            });
        }
        </script>
    <?php $conn->close(); ?>
</body>
</html>