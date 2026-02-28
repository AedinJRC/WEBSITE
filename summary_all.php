<?php 
include 'config.php';

// --- SESSION VALIDATION ---
if (!isset($_SESSION['fname']) || !isset($_SESSION['lname'])) {
    header("Location: login.php");
    exit();
}

$full_name = $_SESSION['fname'] . ' ' . $_SESSION['lname']; // Combine fname and lname
$department = $_SESSION['department'];

// --- SEARCH & FILTER INITIALIZATION ---
$search = '';
$status_filter = '';
$month_filter = '';
$year_filter = '';

if (isset($_POST['search'])) {
    $search = $conn->real_escape_string($_POST['search']);
}

if (isset($_POST['status_filter']) && $_POST['status_filter'] != '') {
    $status_filter = $conn->real_escape_string($_POST['status_filter']);
}

if (isset($_POST['month_filter']) && $_POST['month_filter'] != '') {
    $month_filter = intval($_POST['month_filter']);
}

if (isset($_POST['year_filter']) && $_POST['year_filter'] != '') {
    $year_filter = intval($_POST['year_filter']);
}

// --- TOTAL COST QUERY ---
$total_query = "SELECT SUM(total_cost) AS total_spent FROM vrftb WHERE name = '$full_name'";
if ($status_filter != '') {
    $total_query .= " AND gsodirector_status = '$status_filter'";
}
if ($month_filter != '') {
    $total_query .= " AND MONTH(created_at) = '$month_filter'";
}
if ($year_filter != '') {
    $total_query .= " AND YEAR(created_at) = '$year_filter'";
}

$total_result = $conn->query($total_query);
$total_spent = 0;
if ($total_result && $row = $total_result->fetch_assoc()) {
    $total_spent = $row['total_spent'] ?? 0;
}

// --- DEPARTMENT TOTAL COST ---
$dept_total_query = "SELECT SUM(total_cost) AS dept_total FROM vrftb WHERE department = '$department'";
if ($month_filter != '') {
    $dept_total_query .= " AND MONTH(created_at) = '$month_filter'";
}
if ($year_filter != '') {
    $dept_total_query .= " AND YEAR(created_at) = '$year_filter'";
}
$dept_total_result = $conn->query($dept_total_query);
$dept_total = 0;
if ($dept_total_result && $row = $dept_total_result->fetch_assoc()) {
    $dept_total = $row['dept_total'] ?? 0;
}

// --- MAIN DATA QUERY ---
$sql = "
SELECT d.*, v.*
FROM vrftb v
LEFT JOIN vrf_detailstb d ON d.vrf_id = v.id
WHERE (v.user_cancelled IS NULL OR v.user_cancelled = 'No')
";

if ($_SESSION['role'] == 'Immediate Head') {
    $sql .= " AND v.department = '$department'";
} else {
    $sql .= " AND v.name = '$full_name'";
}

if ($status_filter != '') {
    $sql .= " AND v.gsodirector_status = '$status_filter'";
}

if ($month_filter != '') {
    $sql .= " AND MONTH(v.created_at) = '$month_filter'";
}
if ($year_filter != '') {
    $sql .= " AND YEAR(v.created_at) = '$year_filter'";
}

// Search filter
if ($search != '') {
    $sql .= " AND (
        v.id LIKE '%$search%' OR 
        v.name LIKE '%$search%' OR 
        v.department LIKE '%$search%' OR 
        v.activity LIKE '%$search%' OR 
        v.purpose LIKE '%$search%' OR 
        v.date_filed LIKE '%$search%' OR 
        v.budget_no LIKE '%$search%' OR 
        d.vehicle LIKE '%$search%' OR 
        d.driver LIKE '%$search%' OR 
        v.destination LIKE '%$search%' OR 
        d.departure LIKE '%$search%' OR 
        v.passenger_count LIKE '%$search%' OR 
        v.passenger_attachment LIKE '%$search%' OR 
        v.transportation_cost LIKE '%$search%' OR 
        v.total_cost LIKE '%$search%' OR 
        v.letter_attachment LIKE '%$search%' OR 
        v.gsoassistant_status LIKE '%$search%' OR 
        v.immediatehead_status LIKE '%$search%' OR 
        v.gsodirector_status LIKE '%$search%' OR 
        v.accounting_status LIKE '%$search%'
    )";
}

$sql .= " ORDER BY v.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vehicle Monitoring Summary Report</title>
   
<style>
/* MAIN WRAPPER */
.summary-report-wrapper {
    font-family: Arial, sans-serif;
}

/* Container */
.summary-report-wrapper .report-container {
    max-width: 1200px;
    margin: 20px auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

/* Title */
.summary-report-wrapper h1 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 28px;
}

/* FILTER FORM */
.summary-report-wrapper form {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-bottom: 25px;
    flex-wrap: wrap;
 
    padding: 15px 20px;
}

.summary-report-wrapper select {
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    min-width: 150px;
    transition: all 0.2s ease-in-out;
}

.summary-report-wrapper select:focus {
    border-color: #80050d;
    box-shadow: 0 0 5px rgba(128, 5, 13, 0.3);
    outline: none;
}

.summary-report-wrapper button {
    padding: 8px 18px;
    background-color: #80050d;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
}

.summary-report-wrapper button:hover {
    background-color: #a1081f;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}

/* Summary Box */
.summary-report-wrapper .summary-container {
    max-width: 400px;
    margin: 20px auto;
    padding: 15px 25px;
    background: #f9f6f6;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    text-align: center;
}

.summary-report-wrapper .summary-container p {
    margin: 10px 0;
}

.summary-report-wrapper .total-reports {
    font-weight: bold;
    font-size: 20px;
    color: #80050d;
}

.summary-report-wrapper .overall-cost,
.summary-report-wrapper .dept-cost {
    font-size: 18px;
    font-weight: bold;
}

.summary-report-wrapper h3 {
    margin-top: 15px;
    color: #5a0011;
}

.height {
    height: 50vh;
}
</style>

</head>
<body>

<div class="height">

<div class="summary-report-wrapper">
   <div class="report-container">
      <h1>Vehicle Monitoring Summary Report</h1>

      <!-- MONTH & YEAR FILTER FORM -->
      <form method="POST">
        <select name="month_filter">
            <option value="">Select Month</option>
            <?php
            for ($m = 1; $m <= 12; $m++) {
                $selected = ($month_filter == $m) ? 'selected' : '';
                echo "<option value='$m' $selected>" . date("F", mktime(0,0,0,$m,10)) . "</option>";
            }
            ?>
        </select>

        <select name="year_filter">
            <option value="">Select Year</option>
            <?php
            $currentYear = date("Y");
            for ($y = $currentYear; $y >= 2020; $y--) {
                $selected = ($year_filter == $y) ? 'selected' : '';
                echo "<option value='$y' $selected>$y</option>";
            }
            ?>
        </select>

        <button type="submit">Filter</button>
      </form>

      <div class="summary-container">
         <p class="total-reports">
            Total Reports: <?php echo $result->num_rows; ?>
         </p>

         <p class="overall-cost">
            <strong>My Total Cost:</strong>
            ₱<?php echo number_format($total_spent, 2); ?>
         </p>

         <h3>Total Cost for Department: 
            <?php echo htmlspecialchars($department); ?>
         </h3>

         <p class="dept-cost">
            ₱<?php echo number_format($dept_total, 2); ?>
         </p>
      </div>
   </div>
</div>
</div>

</body>
</html>