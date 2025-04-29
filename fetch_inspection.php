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

// Get plate number from request
$plate_number = $_GET['plate_number'] ?? '';

// Define checklist items (should match the items in your checklist form)
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

// Get vehicle details
$vehicle_sql = "SELECT * FROM carstb WHERE plate_number = ?";
$vehicle_stmt = $conn->prepare($vehicle_sql);
$vehicle_stmt->bind_param("s", $plate_number);
$vehicle_stmt->execute();
$vehicle_result = $vehicle_stmt->get_result();
$vehicle = $vehicle_result->fetch_assoc();
$vehicle_stmt->close();

// Get checklist data for this vehicle
$checklist_sql = "SELECT check_id, status, checked_at 
                  FROM vehicle_checklists 
                  WHERE plate_number = ? 
                  ORDER BY checked_at DESC";
$checklist_stmt = $conn->prepare($checklist_sql);
$checklist_stmt->bind_param("s", $plate_number);
$checklist_stmt->execute();
$checklist_result = $checklist_stmt->get_result();

// Organize checklist data by check_id (latest status for each item)
$checklist_data = [];
while($row = $checklist_result->fetch_assoc()) {
    if (!isset($checklist_data[$row['check_id']])) {
        $checklist_data[$row['check_id']] = $row;
    }
}
$checklist_stmt->close();

// Get the most recent inspection date
$recent_sql = "SELECT MAX(checked_at) as last_inspection 
               FROM vehicle_checklists 
               WHERE plate_number = ?";
$recent_stmt = $conn->prepare($recent_sql);
$recent_stmt->bind_param("s", $plate_number);
$recent_stmt->execute();
$recent_result = $recent_stmt->get_result();
$recent_inspection = $recent_result->fetch_assoc();
$recent_stmt->close();
?>

<style>
.inspection-report {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
}

.vehicle-header {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.vehicle-header h2 {
    color: #2c3e50;
    margin-bottom: 5px;
}

.vehicle-details {
    display: flex;
    gap: 15px;
    margin-bottom: 10px;
    font-size: 14px;
}

.detail-label {
    font-weight: 600;
    color: #7f8c8d;
}

.inspection-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 14px;
}

.inspection-table th {
    background-color:rgb(105, 10, 18);
    color: white;
    padding: 12px;
    text-align: center;
    font-weight: 600;
}

.inspection-table td {
    border: 1px solid #e0e0e0;
    padding: 12px;
    text-align: center;
}

.inspection-table tr:nth-child(even) {
    background-color: #f8f9fa;
}

.status-good {
    color: #2ecc71;
    font-weight: 600;
    text-align: center;
}

.status-fair, .status-bad {
    color: #e74c3c;
    font-weight: 600;
    text-align: center;
}

.last-inspection {
    margin-top: 20px;
    font-size: 14px;
    color: #7f8c8d;
    text-align: right;
}



</style>

<div class="inspection-report">
    <div class="vehicle-header">
        <h2><?php echo htmlspecialchars($vehicle['brand'] . ' ' . $vehicle['model']); ?></h2>
        <div class="vehicle-details">
            <div><span class="detail-label">Plate Number:</span> <?php echo htmlspecialchars($vehicle['plate_number']); ?></div>
            <div><span class="detail-label">Year:</span> <?php echo htmlspecialchars($vehicle['year_model']); ?></div>
            <div><span class="detail-label">Color:</span> <?php echo htmlspecialchars($vehicle['color']); ?></div>
        </div>
    </div>

    <table class="inspection-table">
        <thead>
            <tr>
                <th width="60%">Checklist Item</th>
                <th width="20%">Status</th>
                <th width="20%">Last Checked</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($checklist_items as $id => $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item); ?></td>
                    <td class="<?php 
                        if (isset($checklist_data[$id])) {
                            echo $checklist_data[$id]['status'] == 'Yes' ? 'status-good' : 'status-bad';
                        }
                    ?>">
                        <?php 
                        if (isset($checklist_data[$id])) {
                            echo htmlspecialchars($checklist_data[$id]['status']);
                        } else {
                            echo 'Not checked';
                        }
                        ?>
                    </td>
                    <td>
                        <?php 
                        if (isset($checklist_data[$id])) {
                            echo htmlspecialchars(date('M d, Y H:i', strtotime($checklist_data[$id]['checked_at'])));
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($recent_inspection['last_inspection']): ?>
        <div class="last-inspection">
            Last full inspection: <?php echo date('M d, Y H:i', strtotime($recent_inspection['last_inspection'])); ?>
        </div>
    <?php endif; ?>
</div>

<?php
$conn->close();
?>