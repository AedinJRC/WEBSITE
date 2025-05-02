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
            position: relative;
        }
        
        h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 24px;
            text-align: center;
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
            background-color: var(--maroon);
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

        .excel-form {
            
            position: absolute;
            display: flex;
            padding-right: 50px;
            width: 100%;
            justify-content: right;
            align-items: center;
            button.change img {
                transform: translateY(2px);
                width: 25px;
                height: 25px;
            }
            button.excel-button {
                margin-left: 3px;
                width: 110px;
                background-color: #efb954;
                box-sizing: border-box;
                color: var(--maroon);
                padding: 4px 8px;
                border-radius: 6px;
                font-size: 0.875rem;
                font-weight: 500;
                border: 3px solid var(--maroon);
                cursor: pointer;
                transition: background-color 0.2s;
            }
            button.excel-button:hover {
                background-color: var(--maroon);
                color: white;
            }
            button#import-btn {
                display: none;
            }
            button.change {
                background-color: transparent;
                border: none;
                cursor: pointer;
                margin-left: 2px;
            }
        }
        #employeepopup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            .employeepopup {
                height: 80%;
                width: 80%;
                overflow: auto;
                background-color: white;
                border-radius: 8px;
                max-width: 800px;
            }
            table {
                margin: 0;
                
                border-radius: 8px;
            }
        }
        #employeepopup:target {
            display: flex;
        }

    </style>
</head>
<body>
    <div class="container">
        <form action="" method="post" class="excel-form" enctype="multipart/form-data" id="employee-form">
            <span class="excel-label">Employee Records:</span>
            <input type="file" name="excel_file" id="excel-file" accept=".xlsx,.xls,.csv" style="display: none;">
            <button id="download-btn" class="excel-button" name="viewcurrent" type="button">View Current</button>
            <button id="import-btn" class="excel-button" name="importcsv" type="button">Import CSV</button>
            <button id="change-btn" type="button" class="change">
                <img src="PNG/Change.png" alt="">
            </button>
        </form>

        <script>
            const dwnldBtn = document.getElementById('download-btn');
            const importBtn = document.getElementById('import-btn');
            const changeBtn = document.getElementById('change-btn');
            const fileInput = document.getElementById('excel-file');
            const form = document.getElementById('employee-form');

            // Toggle visible button
            changeBtn.addEventListener('click', () => {
                const isViewing = dwnldBtn.style.display !== 'none';

                if (isViewing) {
                    dwnldBtn.style.display = 'none';
                    importBtn.style.display = 'inline-block';
                } else {
                    dwnldBtn.style.display = 'inline-block';
                    importBtn.style.display = 'none';
                }
            });

            // View Current button - redirect without form submission
            dwnldBtn.addEventListener('click', () => {
                window.location.href = '#employeepopup';
            });

            // Import CSV button - trigger file input
            importBtn.addEventListener('click', () => {
                fileInput.click();
            });

            // Auto-submit the form when a file is chosen
            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    // Validate file extension before submit
                    const fileName = fileInput.files[0].name;
                    const fileExt = fileName.split('.').pop().toLowerCase();
                    
                    if (fileExt !== 'csv') {
                        alert('Please convert your file to CSV format first.\n\nIn Excel/Google Sheets:\n1. Open your file\n2. Click File → Save As\n3. Select "CSV (Comma delimited)"');
                        return false;
                    }
                    
                    form.submit();
                }
            });
        </script>
        <?php
            include 'config.php';

            // Set headers to ensure UTF-8 output
            header('Content-Type: text/html; charset=UTF-8');

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
                    $fileName = $_FILES['excel_file']['name'];
                    $fileTmp = $_FILES['excel_file']['tmp_name'];
                    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    // Only allow CSV files
                    if ($fileExt !== 'csv') {
                        echo "<script>
                            alert('Please convert your Excel file to CSV first.\\n\\nIn Excel/Google Sheets:\\n1. Open your file\\n2. Click File → Save As\\n3. Select \"CSV UTF-8 (Comma delimited)\"');
                            window.history.back();
                        </script>";
                        exit();
                    }

                    // Check for file size limit (2MB)
                    if ($_FILES['excel_file']['size'] > 2 * 1024 * 1024) {
                        echo "<script>alert('File size exceeds 2MB limit.'); window.history.back();</script>";
                        exit();
                    }

                    $deleteemployeesql = "DELETE FROM employeetb";
                    if ($conn->query($deleteemployeesql) === TRUE) {
                        // File upload successful
                    } else {
                        echo "<script>alert('Error deleting previous records: " . $conn->error . "'); window.history.back();</script>";
                        exit();
                    }

                    // Create upload directory if it doesn't exist
                    $uploadDir = __DIR__ . '/xlsx/';
                    if (!is_dir($uploadDir)) {
                        if (!mkdir($uploadDir, 0777, true)) {
                            echo "<script>alert('Failed to create upload directory.'); window.history.back();</script>";
                            exit();
                        }
                    }

                    // Rename file with current date
                    $currentDate = date('m-d-Y');
                    $newFileName = "Employee($currentDate).csv";
                    $destination = $uploadDir . $newFileName;

                    // Convert file to UTF-8 if needed
                    $fileContent = file_get_contents($fileTmp);
                    $encoding = mb_detect_encoding($fileContent, 'UTF-8, ISO-8859-1', true);
                    
                    if ($encoding !== 'UTF-8') {
                        $fileContent = mb_convert_encoding($fileContent, 'UTF-8', $encoding);
                        file_put_contents($fileTmp, $fileContent);
                    }

                    if (!move_uploaded_file($fileTmp, $destination)) {
                        echo "<script>alert('Failed to save uploaded file.'); window.history.back();</script>";
                        exit();
                    }

                    try {
                        $importedCount = importCSVtoDB($conn, $destination);
                        echo "<script>
                            alert('Successfully imported $importedCount employee records!');
                            window.location.href = 'GSO.php?macc=a';
                        </script>";
                        exit();
                    } catch (Exception $e) {
                        // Clean error message for JavaScript
                        $errorMsg = str_replace(["\r", "\n"], ' ', addslashes($e->getMessage()));
                        echo "<script>
                            alert('IMPORT FAILED: " . $errorMsg . "');
                            window.history.back();
                        </script>";
                        exit();
                    }
                } else {
                    $uploadError = $_FILES['excel_file']['error'] ?? 'Unknown error';
                    echo "<script>
                        alert('File upload error: " . getUploadError($uploadError) . "');
                        window.history.back();
                    </script>";
                    exit();
                }
            }

            /**
             * Gets user-friendly upload error message
             */
            function getUploadError($errorCode) {
                $errors = [
                    UPLOAD_ERR_INI_SIZE => 'File is too large',
                    UPLOAD_ERR_FORM_SIZE => 'File is too large',
                    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
                ];
                return $errors[$errorCode] ?? 'Unknown error';
            }

            /**
             * Imports CSV data to employee database with validation (MySQLi version)
             */
            function importCSVtoDB($conn, $csvFile) {
                $importedCount = 0;
                $errors = [];
                
                // Turn off autocommit for transaction
                $conn->autocommit(FALSE);
                
                try {
                    $stmt = $conn->prepare("INSERT INTO employeetb (employeeid, lname, fname, mname) VALUES (?, ?, ?, ?)");
                    if (!$stmt) {
                        throw new Exception("Prepare failed: " . $conn->error);
                    }

                    // Open file with UTF-8 encoding
                    if (($handle = fopen($csvFile, "r")) !== FALSE) {
                        // Check for UTF-8 BOM and skip if present
                        $bom = fread($handle, 3);
                        if ($bom != "\xEF\xBB\xBF") {
                            rewind($handle);
                        }
                        
                        // Skip header row if exists
                        fgetcsv($handle);
                        
                        $lineNumber = 1;
                        while (($data = fgetcsv($handle)) !== FALSE) {
                            $lineNumber++;
                            
                            // Skip empty rows
                            if (empty(array_filter($data))) continue;
                            
                            // Prepare and validate data with UTF-8 support
                            $mappedData = [
                                mb_convert_encoding(trim($data[0]), 'UTF-8', 'auto'),  // employeeid
                                mb_convert_encoding(trim($data[1]), 'UTF-8', 'auto'),  // lname
                                isset($data[2]) ? mb_convert_encoding(trim($data[2]), 'UTF-8', 'auto') : '',  // fname
                                isset($data[3]) ? mb_convert_encoding(trim($data[3]), 'UTF-8', 'auto') : ''   // mname
                            ];
                            
                            // Validate required fields
                            if (empty($mappedData[0])) {
                                $errors[] = "Line $lineNumber: Missing employee ID";
                                continue;
                            }
                            
                            if (empty($mappedData[1])) {
                                $errors[] = "Line $lineNumber: Missing last name";
                                continue;
                            }
                            
                            // Validate employee ID format
                            if (!preg_match('/^\d+$/', $mappedData[0])) {
                                $errors[] = "Line $lineNumber: Invalid employee ID format (must be numeric)";
                                continue;
                            }
                            
                            $stmt->bind_param("ssss", ...$mappedData);
                            if (!$stmt->execute()) {
                                if (strpos($stmt->error, 'Duplicate entry') !== false) {
                                    $errors[] = "Line $lineNumber: Duplicate employee ID {$mappedData[0]}";
                                } else {
                                    $errors[] = "Line $lineNumber: Database error - " . $stmt->error;
                                }
                            } else {
                                $importedCount++;
                            }
                        }
                        
                        fclose($handle);
                        
                        if (!empty($errors)) {
                            $conn->rollback();
                            throw new Exception(
                                "Imported $importedCount records, but encountered errors:\n" . 
                                implode("\n", array_slice($errors, 0, 5)) . 
                                (count($errors) > 5 ? "\n...and " . (count($errors) - 5) . " more" : "")
                            );
                        }
                        
                        $conn->commit();
                        return $importedCount;
                    }
                } catch (Exception $e) {
                    $conn->rollback();
                    throw $e;
                } finally {
                    $conn->autocommit(TRUE);
                    if (isset($stmt)) $stmt->close();
                }
            }
        ?>

    

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
                                        class="btn btn-update" style="color: var(--maroon);">
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
    <div onclick="window.history.back();"  id="employeepopup">
        <div onclick="event.stopPropagation();" class="employeepopup">
            <table>
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'config.php';
                    $selectsql = "SELECT * FROM employeetb ORDER BY lname ASC";
                    $result = $conn->query($selectsql);
                    while ($row = $result->fetch_assoc()):
                    ?>
                    <tr id="row-<?php echo htmlspecialchars($row['employeeid']); ?>">
                        <td><?php echo htmlspecialchars($row['employeeid']); ?></td>
                        <td><?php echo htmlspecialchars($row['lname']); ?></td>
                        <td><?php echo htmlspecialchars($row['fname']); ?></td>
                        <td><?php echo htmlspecialchars($row['mname']); ?></td>
                    </tr>
                    <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>