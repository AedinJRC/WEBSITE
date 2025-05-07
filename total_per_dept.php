<?php 
// Include your database connection
include('config.php');

// Sanitize input
$search = isset($_POST['search']) ? $conn->real_escape_string($_POST['search']) : '';

$sqlDeptSummary = "
    SELECT department, COUNT(*) AS totalReports, SUM(total_cost) AS totalCost 
    FROM vrftb 
    WHERE gsodirector_status = 'Approved' AND (
        id LIKE '%$search%' OR 
        name LIKE '%$search%' OR 
        department LIKE '%$search%' OR 
        activity LIKE '%$search%' OR 
        purpose LIKE '%$search%' OR 
        date_filed LIKE '%$search%' OR 
        budget_no LIKE '%$search%' OR 
        vehicle LIKE '%$search%' OR 
        driver LIKE '%$search%' OR 
        destination LIKE '%$search%' OR 
        departure LIKE '%$search%' OR 
        passenger_count LIKE '%$search%' OR 
        passenger_attachment LIKE '%$search%' OR 
        transportation_cost LIKE '%$search%' OR 
        total_cost LIKE '%$search%' OR 
        letter_attachment LIKE '%$search%' OR 
        gsoassistant_status LIKE '%$search%' OR 
        immediatehead_status LIKE '%$search%' OR 
        accounting_status LIKE '%$search%'
    )
    GROUP BY department
";

$resultDeptSummary = $conn->query($sqlDeptSummary);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Department Summary - Transportation Reservation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>  
    :root {
        --primary-color: #3498db;
        --primary-dark: #2980b9;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f7f9fb;
        color: #333;
        margin: 0;
        padding: 2vh;
    }

    h2 {
        text-align: center;
        color: #2c3e50;
        font-size: 2.5vh;
    }

    form {
        text-align: center;
        margin: 2vh auto;
        max-width: 600px;
    }

    input[type="text"],
    input[type="submit"] {
        padding: 1vh;
        font-size: 2vh;
        border-radius: 0.5vh;
        border: 1px solid #ccc;
        margin: 0.5vh;
    }

    input[type="text"] {
        width: 70%;
        max-width: 400px;
    }

    input[type="submit"] {
        background-color: var(--primary-color);
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
        background-color: var(--primary-dark);
    }

    table {
        width: 100%;
        max-width: 800px;
        margin: 3vh auto;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 0.5vh 1vh rgba(0, 0, 0, 0.1);
    }

    th, td {
        padding: 1.5vh 2vh;
        border: 1px solid #ddd;
        text-align: center;
        font-size: 2vh;
    }

    th {
        background-color: var(--primary-color);
        color: #fff;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .no-results {
        text-align: center;
        margin-top: 5vh;
        font-size: 2vh;
        color: #888;
    }

    .cost-green {
        color: green;
        font-weight: bold;
    }

    @media (max-width: 900px) {
        table {
            border: 0;
        }

        thead {
            display: none;
        }

        tr {
            display: block;
            margin-bottom: 2vh;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 0.8vh;
            box-shadow: 0 0.2vh 0.5vh rgba(0, 0, 0, 0.05);
        }

        td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 2vh;
            padding: 1.2vh 2vh;
            border-bottom: 1px solid #eee;
            position: relative;
        }

        td::before {
            content: attr(data-label);
            font-weight: bold;
            color: #555;
            flex: 1;
            margin-right: 2vh;
            text-align: left;
        }

        td:last-child {
            border-bottom: none;
        }
    }
    </style>
</head>
<body>

<h2>Transportation Reservation Department Summary<br><small>(Approved by GSO Director)</small></h2>

<!-- Search Form -->
<form method="POST">
    <input type="text" name="search" placeholder="Search by department, name, vehicle, etc." value="<?= htmlspecialchars($search) ?>">
    <input type="submit" value="Search">
</form>

<?php if ($resultDeptSummary->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Department</th>
                <th>Total Approved Reports</th>
                <th>Total Approved Cost</th>
            </tr>
        </thead>
        <tbody>
            <?php while($deptRow = $resultDeptSummary->fetch_assoc()): ?>
                <tr>
                    <td data-label="Department"><?= htmlspecialchars($deptRow['department']) ?></td>
                    <td data-label="Total Approved Reports"><?= $deptRow['totalReports'] ?></td>
                    <td data-label="Total Approved Cost"><span class="cost-green">₱<?= number_format($deptRow['totalCost'], 2) ?></span></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="no-results">No approved department summary found.</p>
<?php endif; ?>

</body>
</html>
