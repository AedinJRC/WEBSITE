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
    max-width: 800px;
    margin: 0 auto;
}

.vehicle-header {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
    text-align: center;
}

.vehicle-header h2 {
    color: #2c3e50;
    margin-bottom: 10px;
}

.vehicle-details {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-bottom: 10px;
    font-size: 14px;
    flex-wrap: wrap;
}

.detail-label {
    font-weight: 600;
    color: #7f8c8d;
}

.inspection-info {
    text-align: center;
    margin-bottom: 20px;
    font-size: 14px;
    color: #666;
}

.inspection-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    font-size: 14px;
}

.inspection-table th {
    background-color: rgb(105, 10, 18);
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
}

.status-fair {
    color: rgb(219, 86, 9);
    font-weight: 600;
}

.status-bad {
    color: rgb(167, 13, 13);
    font-weight: 600;
}

@media (max-width: 600px) {
    .vehicle-details {
        flex-direction: column;
        gap: 5px;
    }
}
</style>

<div class="inspection-report">
    <div class="vehicle-header">
        <h2><?php echo htmlspecialchars($vehicle['brand'] . ' ' . $vehicle['model']); ?></h2>
        <div class="vehicle-details">
            <div><span class="detail-label">Plate:</span> <?php echo htmlspecialchars($vehicle['plate_number']); ?></div>
            <div><span class="detail-label">Year:</span> <?php echo htmlspecialchars($vehicle['year_model']); ?></div>
            <div><span class="detail-label">Color:</span> <?php echo htmlspecialchars($vehicle['color']); ?></div>
        </div>
        
        <?php if ($recent_inspection['last_inspection']): ?>
            <div class="inspection-info">
                <span class="detail-label">Last Inspection:</span> 
                <?php echo date('M d, Y h:i A', strtotime($recent_inspection['last_inspection'])); ?>
            </div>
        <?php endif; ?>
    </div>

    <table class="inspection-table">
        <thead>
            <tr>
                <th width="70%">Checklist Item</th>
                <th width="30%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($checklist_items as $id => $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item); ?></td>
                    <td class="<?php 
                        if (isset($checklist_data[$id])) {
                            $status = $checklist_data[$id]['status'];
                            if ($status == 'Good' || $status == 'Yes') {
                                echo 'status-good';
                            } elseif ($status == 'Fair') {
                                echo 'status-fair';
                            } else {
                                echo 'status-bad';
                            }
                        }
                    ?>">
                        <?php 
                        if (isset($checklist_data[$id])) {
                            $status = $checklist_data[$id]['status'];
                            if ($status == 'Yes') {
                                echo 'Good';
                            } else {
                                echo htmlspecialchars($status);
                            }
                        } else {
                            echo 'Not checked';
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$conn->close();
?>