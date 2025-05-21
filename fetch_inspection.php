<?php
include 'config.php';

// Get plate number from request
$plate_number = $_GET['plate_number'] ?? '';

// Define checklist items
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

// Get checklist data - including inspected_by
$checklist_sql = "SELECT v1.check_id, v1.status, v1.checked_at, v1.inspected_by 
                  FROM vehicle_checklists v1
                  INNER JOIN (
                      SELECT check_id, MAX(checked_at) as latest_check
                      FROM vehicle_checklists
                      WHERE plate_number = ?
                      GROUP BY check_id
                  ) v2 ON v1.check_id = v2.check_id AND v1.checked_at = v2.latest_check
                  WHERE v1.plate_number = ?";
$checklist_stmt = $conn->prepare($checklist_sql);
$checklist_stmt->bind_param("ss", $plate_number, $plate_number);
$checklist_stmt->execute();
$checklist_result = $checklist_stmt->get_result();

// Organize checklist data
$checklist_data = [];
$inspector_name = '';
while($row = $checklist_result->fetch_assoc()) {
    if (!isset($checklist_data[$row['check_id']])) {
        $checklist_data[$row['check_id']] = $row;
        // Get the inspector name from the first record (they should all be the same for a given inspection)
        if (empty($inspector_name)) {
            $inspector_name = $row['inspected_by'];
        }
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
    padding: 20px;
}

.vehicle-header {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
    text-align: left;
}

.vehicle-header h2 {
    color: #2c3e50;
    margin-bottom: 10px;
    text-align: center;
}

.vehicle-details {
    display: flex;
    justify-content: flex-start;
    gap: 20px;
    margin-bottom: 10px;
    font-size: 14px;
    flex-wrap: wrap;
    text-align: center;
}

.detail-label {
    font-weight: 600;
    color: #7f8c8d;
    text-align: center;
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
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
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
    
    .inspection-table {
        font-size: 13px;
    }
    
    .inspection-table th, 
    .inspection-table td {
        padding: 8px;
    }
}
</style>

<div class="inspection-report">
    <div class="vehicle-header">
        <h2><?php echo htmlspecialchars($vehicle['brand'] . ' ' . $vehicle['model']); ?></h2>

        <?php if ($recent_inspection['last_inspection'] && !empty($inspector_name)): ?>
            <div class="inspection-info">
                <div><span class="detail-label">Last Inspection:</span> <?php echo date('M d, Y h:i A', strtotime($recent_inspection['last_inspection'])); ?></div>
                <div><span class="detail-label">Inspected By:</span> <?php echo htmlspecialchars($inspector_name); ?></div>
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
                            if ($status == 'Good') {
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
                            echo htmlspecialchars($checklist_data[$id]['status']);
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