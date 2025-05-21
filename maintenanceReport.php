<?php
include 'config.php';

// Fetch inspected vehicles
$sql = "SELECT DISTINCT c.plate_number, c.brand, c.model, c.year_model, c.color, c.transmission
        FROM carstb c
        INNER JOIN vehicle_checklists vc ON c.plate_number = vc.plate_number
        ORDER BY c.brand, c.model";
$vehicles = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Maintenance Report</title>
<style>
:root {
    --primary-color: rgb(90, 10, 17);
    --secondary-color: rgb(196, 18, 48);
    --accent-color: #e74c3c;
    --light-color: #ecf0f1;
    --dark-color: #2c3e50;
    --success-color: #2ecc71;
    --warning-color: #f39c12;
    --danger-color: #e74c3c;
    --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f5f7fa;
    color: #333;
    line-height: 1.6;
}

.container {
    max-width: 1200px;
    margin: 30px auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: var(--shadow);
}

.header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.header h1 {
    color: var(--dark-color);
    margin-bottom: 10px;
    font-size: 2.2rem;
}

.header h2 {
    color: var(--primary-color);
    font-size: 1.5rem;
    font-weight: bold;
}

.vehicle-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 20px;
}

.vehicle-item {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: var(--shadow);
    transition: var(--transition);
    border-left: 4px solid var(--primary-color);
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.vehicle-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    border-left-color: var(--secondary-color);
}

.vehicle-info {
    flex: 1;
    text-align: left; /* Added to align vehicle details to the left */
}

.vehicle-item h3 {
    color: var(--dark-color);
    margin-bottom: 5px;
    font-size: 1.2rem;
    text-align: left; /* Added to align vehicle name to the left */
}

.vehicle-details {
    color: #666;
    font-size: 0.9rem;
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    justify-content: flex-start; /* Changed to align details to the left */
}

.vehicle-details span {
    display: inline-block;
    margin-right: 15px;
}

.vehicle-details .plate {
    font-weight: bold;
    color: var(--primary-color);
}

.vehicle-status {
    background: var(--light-color);
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--dark-color);
}

.no-vehicles {
    text-align: center;
    padding: 30px;
    color: #666;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(5px);
    transition: var(--transition);
}

.modal-content {
    background: white;
    margin: 5% auto;
    padding: 30px;
    width: 80%;
    max-width: 900px;
    border-radius: 10px;
    box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
    position: relative;
    animation: modalopen 0.4s;
    max-height: 80vh;
    overflow-y: auto;
}

@keyframes modalopen {
    from { opacity: 0; transform: translateY(-50px); }
    to { opacity: 1; transform: translateY(0); }
}

.close {
    position: absolute;
    top: 15px;
    right: 25px;
    font-size: 28px;
    font-weight: bold;
    color: #aaa;
    cursor: pointer;
    transition: var(--transition);
}

.close:hover {
    color: var(--danger-color);
    transform: rotate(90deg);
}

@media (max-width: 768px) {
    .vehicle-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .vehicle-details {
        flex-direction: column;
        gap: 5px;
    }
    
    .vehicle-details span {
        margin-right: 0;
    }
    
    .modal-content {
        width: 95%;
        margin: 10% auto;
        padding: 20px 15px;
    }
}
</style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Maintenance Report</h1>
        <h2>Inspected Vehicles</h2>
    </div>
    
    <div class="vehicle-list">
        <?php if ($vehicles->num_rows > 0): ?>
            <?php while($vehicle = $vehicles->fetch_assoc()): ?>
                <div class="vehicle-item" onclick="showModal('<?php echo $vehicle['plate_number']; ?>')">
                    <div class="vehicle-info">
                        <h3><?php echo $vehicle['brand'].' '.$vehicle['model']; ?></h3>
                        <div class="vehicle-details">
                            <span class="plate"><?php echo $vehicle['plate_number']; ?></span>
                            <span>Year: <?php echo $vehicle['year_model']; ?></span>
                            <span>Color: <?php echo $vehicle['color']; ?></span>
                            <span>Transmission: <?php echo $vehicle['transmission']; ?></span>
                        </div>
                    </div>
                    <div class="vehicle-status">
                        View Inspection
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-vehicles">
                <p>No inspected vehicles found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="modal-body"></div>
    </div>
</div>

<script>
function showModal(plateNumber) {
    fetch('fetch_inspection.php?plate_number=' + plateNumber)
    .then(response => response.text())
    .then(data => {
        document.getElementById('modal-body').innerHTML = data;
        document.getElementById('modal').style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
    });
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
    document.body.style.overflow = 'auto'; // Re-enable scrolling
}

window.onclick = function(event) {
    if (event.target == document.getElementById('modal')) {
        closeModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});
</script>

</body>
</html>