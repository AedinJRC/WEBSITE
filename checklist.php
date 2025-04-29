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

// Initialize message variables
$showPopup = false;
$popupMessage = '';
$isError = false;

// Handle form submission
if(isset($_POST["save_checklist"])) {
    $plate_number = $_POST["plate_number"];
    
    // Save checklist items
    foreach($_POST['checks'] as $check_id => $status) {
        $sql = "INSERT INTO vehicle_checklists 
                (plate_number, check_id, status) 
                VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $plate_number, $check_id, $status);
        
        if(!$stmt->execute()) {
            $isError = true;
            $popupMessage = "Error saving checklist: " . $stmt->error;
        }
        $stmt->close();
    }
    
    if(!$isError) {
        $showPopup = true;
        $popupMessage = "Vehicle checklist saved successfully!";
    }
}

// Get vehicles
$vehicles = $conn->query("SELECT * FROM carstb ORDER BY brand, model");

// Checklist items
$checklist_items = [
    1 => "Engine oil level and condition",
    2 => "Coolant level",
    3 => "Brake fluid level",
    4 => "Power steering fluid",
    5 => "Tire pressure and condition",
    6 => "Lights (headlights, brake, signals)",
    7 => "Wiper blades condition",
    8 => "Battery condition",
    9 => "Brake system inspection",
    10 => "Emergency equipment (spare tire, jack)"
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Maintenance Checklist</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color:rgb(12, 42, 71);
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #34495e;
            font-size: 14px;
        }
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #f8f9fa;
        }
        select:focus {
            outline: none;
            border-color:rgb(8, 47, 73);
            box-shadow: 0 0 0 2px rgba(52,152,219,0.2);
        }
        .checklist-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 14px;
        }
        .checklist-table th {
            background-color:rgb(105, 11, 39);
            color: white;
            padding: 12px;
            text-align: center;
            font-weight: 600;
        }
        .checklist-table td {
            border: 1px solid #e0e0e0;
            padding: 12px;
            text-align: center;
            vertical-align: middle;
        }
        .checklist-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .checklist-table tr:hover {
            background-color: #f1f5f9;
        }
        .status-option {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        input[type="radio"] {
            transform: scale(1.2);
            cursor: pointer;
        }
        .btn {
            background-color:rgb(107, 10, 47);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            display: block;
            width: 200px;
            margin: 25px auto 0;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color:rgb(87, 5, 5);
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 25px;
            border: none;
            width: 80%;
            max-width: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.2s;
        }
        .close:hover {
            color: #555;
        }
        .success {
            color: #27ae60;
            font-weight: 600;
        }
        .error {
            color: #e74c3c;
            font-weight: 600;
        }
        .vehicle-info {
            font-size: 13px;
            color: #7f8c8d;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vehicle Maintenance Checklist</h1>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="plate_number">Vehicle:</label>
                <select name="plate_number" id="plate_number" required>
                    <option value="">-- Select Vehicle --</option>
                    <?php while($vehicle = $vehicles->fetch_assoc()): ?>
                        <option value="<?php echo $vehicle['plate_number']; ?>">
                            <?php echo $vehicle['brand'].' '.$vehicle['model'].' ('.$vehicle['plate_number'].')'; ?>
                            <span class="vehicle-info">
                                <?php echo $vehicle['year_model'].' • '.$vehicle['color'].' • '.$vehicle['transmission']; ?>
                            </span>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <table class="checklist-table">
                <thead>
                    <tr>
                        <th width="52%">Checklist Item</th>
                        <th width="16%">Good</th>
                        <th width="16%">Fair</th>
                        <th width="16%">Bad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($checklist_items as $id => $item): ?>
                        <tr>
                            <td><?php echo $item; ?></td>
                            <td>
                                <div class="status-option">
                                    <input type="radio" name="checks[<?php echo $id; ?>]" value="Good" required>
                                </div>
                            </td>
                            <td>
                                <div class="status-option">
                                    <input type="radio" name="checks[<?php echo $id; ?>]" value="Fair">
                                </div>
                            </td>
                            <td>
                                <div class="status-option">
                                    <input type="radio" name="checks[<?php echo $id; ?>]" value="Bad">
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <button type="submit" name="save_checklist" class="btn">Save Checklist</button>
        </form>
    </div>

    <!-- Modal Popup -->
    <div id="messageModal" class="modal" style="<?php echo $showPopup ? 'display: block;' : 'display: none;'; ?>">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('messageModal').style.display='none'">&times;</span>
            <p class="<?php echo $isError ? 'error' : 'success'; ?>"><?php echo $popupMessage; ?></p>
        </div>
    </div>

    <script>
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('messageModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>