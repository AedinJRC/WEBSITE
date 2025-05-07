<?php 
// --- DATABASE CONNECTION --- 
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

// --- SEARCH FUNCTIONALITY ---
$search = '';
$status_filter = '';  // Default to empty if no filter is selected

if (isset($_POST['search'])) {
    $search = $conn->real_escape_string($_POST['search']); // Sanitize search input
}

if (isset($_POST['status_filter']) && $_POST['status_filter'] != '') {
    $status_filter = $conn->real_escape_string($_POST['status_filter']);
}

// Build the SQL query based on search and status filter
$sql = "SELECT * FROM vrftb WHERE ";
if ($status_filter != '') {
    $sql .= "gsodirector_status = '$status_filter' AND ";
}
$sql .= "(
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
    gsodirector_status LIKE '%$search%' OR 
    accounting_status LIKE '%$search%' )";

// Execute the query
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vehicle Monitoring Summary Report</title>
    <style> 
<style>  
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f7f7f7;
        margin: 0;
        padding: 0;
    }

    .report-container {
        max-width: 1200px;
        margin: auto;
        background: #fff;
        padding: 5.2vh;
        border-radius: 1vh;
        box-shadow: 0 0.52vh 2.6vh rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        margin-bottom: 2.6vh;
        color: #2c3e50;
        font-size: 4.2vh;
        font-weight: 600;
    }

    .search-form {
        text-align: center;
        margin-bottom: 5.2vh;
    }

    .search-form input[type="text"], .search-form select {
        padding: 1.56vh 2.34vh;
        font-size: 2.08vh;
        width: 100%;
        max-width: 58.5vh;
        margin: 1.3vh 0;
        border-radius: 1vh;
        border: 0.13vh solid #ddd;
        transition: border-color 0.3s ease;
    }

    .search-form input[type="text"]:focus, .search-form select:focus {
        border-color: #2c3e50;
        outline: none;
    }

    .search-form button {
        padding: 1.56vh 2.6vh;
        background-color: #2c3e50;
        color: white;
        border: none;
        border-radius: 1vh;
        font-size: 2.08vh;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 100%;
        max-width: 19.5vh;
    }

    .search-form button:hover {
        background-color: #16a085;
    }

    .cards-container {
        display: flex;
        flex-wrap: wrap;
        gap: 2.6vh;
        justify-content: center;
    }

    .card {
        background: #ffffff;
        border: 0.13vh solid #ddd;
        border-left: 0.65vh solid #2c3e50;
        border-radius: 1.3vh;
        padding: 2.6vh;
        width: 46.8vh;
        box-shadow: 0 0.52vh 1.04vh rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
        margin-bottom: 3.9vh;
    }

    .card:hover {
        transform: translateY(-0.65vh);
    }

    .card h2 {
        font-size: 2.6vh;
        margin-top: 0;
        color: #2c3e50;
    }

    .card p {
        margin: 0.78vh 0;
        font-size: 1.82vh;
        line-height: 1.5;
        color: #333;
    }

    .numeric {
        font-weight: bold;
        color: #16a085;
    }

    .status-approved {
        color: green;
        font-weight: bold;
    }

    .status-pending {
        color: orange;
        font-weight: bold;
    }

    .status-rejected {
        color: red;
        font-weight: bold;
    }

    .status-group span {
        display: inline-block;
        margin-right: 0.65vh;
    }
</style>
</head>
<body>

<div class="report-container">
   <h1>Vehicle Monitoring Summary Report</h1>
<p style="text-align:center; font-size: 2.3vh; color: #2c3e50; margin-bottom: 2vh;">
    Total Reports: <?php echo $result->num_rows; ?>
</p>
   <br>

   <div class="search-form">
    <form method="POST" action="">
        <div class="form-group">
            <input type="text" name="search" placeholder="Search by any field..." value="<?php echo $search; ?>" />
        </div>
        <div class="form-group">
            <select name="status_filter">
                <option value="">All Statuses</option>
                <option value="Approved" <?php if(isset($_POST['status_filter']) && $_POST['status_filter']=='Approved') echo 'selected'; ?>>Approved</option>
                <option value="Clicked" <?php if(isset($_POST['status_filter']) && $_POST['status_filter']=='Clicked') echo 'selected'; ?>>Clicked</option>
                <option value="Pending" <?php if(isset($_POST['status_filter']) && $_POST['status_filter']=='Pending') echo 'selected'; ?>>Pending</option>
                <option value="Rejected" <?php if(isset($_POST['status_filter']) && $_POST['status_filter']=='Rejected') echo 'selected'; ?>>Rejected</option>
            </select>
        </div>
        <div class="form-group">
            <button type="submit">Search</button>
        </div>
    </form>
</div>

    <div class="cards-container">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $gsoassistant_class = ($row["gsoassistant_status"] == 'Approved') ? 'status-approved' : (($row["gsoassistant_status"] == 'Pending') ? 'status-pending' : 'status-rejected');
                $immediatehead_class = ($row["immediatehead_status"] == 'Approved') ? 'status-approved' : (($row["immediatehead_status"] == 'Pending') ? 'status-pending' : 'status-rejected');
                $gsodirector_class = ($row["gsodirector_status"] == 'Approved') ? 'status-approved' : (($row["gsodirector_status"] == 'Pending') ? 'status-pending' : 'status-rejected');
                $accounting_class = ($row["accounting_status"] == 'Approved') ? 'status-approved' : (($row["accounting_status"] == 'Pending') ? 'status-pending' : 'status-rejected');
            
                echo "<div class='card'>";
                echo "<h2>".$row["name"]." - ".$row["department"]."</h2>";
                echo "<p><strong>Activity:</strong> ".$row["activity"]."</p>";
                echo "<p><strong>Purpose:</strong> ".$row["purpose"]."</p>";
                echo "<p><strong>Date Filed:</strong> ".$row["date_filed"]."</p>";
                echo "<p><strong>Budget No:</strong> ".$row["budget_no"]."</p>";
                echo "<p><strong>Vehicle:</strong> ".$row["vehicle"]."</p>";
                echo "<p><strong>Driver:</strong> ".$row["driver"]."</p>";
                echo "<p><strong>Destination:</strong> ".$row["destination"]."</p>";
                echo "<p><strong>Departure:</strong> ".$row["departure"]."</p>";
                echo "<p><strong>Passenger Count:</strong> <span class='numeric'>".$row["passenger_count"]."</span></p>";
                echo "<p><strong>Passenger Attachment:</strong> ".$row["passenger_attachment"]."</p>";
                echo "<p><strong>Transportation Cost:</strong> ".$row["transportation_cost"]."</p>";
                echo "<p><strong>Total Cost:</strong> <span class='numeric'>₱".number_format($row["total_cost"], 2)."</span></p>";
                echo "<p><strong>Letter Attachment:</strong> ".$row["letter_attachment"]."</p>";
                echo "<div class='status-group'>";
                echo "<p><strong>Status:</strong> 
                         <span class='$immediatehead_class'>Immediate Head: ".$row["immediatehead_status"]."</span><br>
                        <span class='$gsoassistant_class'>GSO Assistant: ".$row["gsoassistant_status"]."</span><br>
                        <span class='$gsodirector_class'>GSO Director: ".$row["gsodirector_status"]."</span><br>
                        <span class='$accounting_class'>Accounting: ".$row["accounting_status"]."</span>
                      </p>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p style='text-align:center;'>No records found for the search criteria.</p>";
        }
        $conn->close();
        ?>
    </div>
</div>

</body>
</html>
