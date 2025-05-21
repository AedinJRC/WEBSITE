<?php 
include 'config.php';

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

* {
   margin: 0;
   padding: 0;
   box-sizing: border-box;
   text-decoration: none;
}

:root {
    --maroonColor: #80050d;
    --yellowColor: #efb954;
    --whiteColor: #ffffff;
    --light-gray: #ebebeb;
  }

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
        color: black;
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
        border-color: #80050d;
        outline: none;
    }

    .search-form button { 
    padding: 1.56vh 2.6vh;
    background-color: var(--whiteColor);
    color: var(--maroonColor); 
    border: .4vh solid var(--maroonColor);
    border-radius: 1vh;
    font-size: 1.6vh;
    cursor: pointer;
    transition: 
        background-color 0.3s ease,
        color 0.3s ease,
        box-shadow 0.3s ease,
        transform 0.2s ease;
    width: 100%;
    max-width: 15vh;
}

.search-form button:hover {
    color: var(--maroonColor);
    box-shadow: 0 0.8vh 1.6vh rgba(128, 5, 13, 0.3);
    transform: scale(1.03);
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
    border-left: 0.65vh solid #800000; /* Maroon or themed color */
    border-radius: 1.3vh;
    padding: 2.6vh;
    width: 46.8vh;
    box-shadow: 0 0.8vh 1.6vh rgba(0, 0, 0, 0.06); /* softer initial shadow */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 3.9vh;
    box-sizing: border-box;
}

/* Hover effect with maroon shadow */
.card:hover {
    transform: translateY(-0.65vh);
    box-shadow: 0 1.3vh 2.6vh rgba(128, 5, 13, 0.3); /* Maroon shadow */
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



    .table-container {
    overflow-x: auto;
    margin-top: 5vh;
    max-width: 95%;
    background-color: #f9f9f9;
    padding: 2vh;
    border-radius: 1.2vh;
    box-shadow: 0 0.4vh 1vh rgba(0, 0, 0, 0.1);
    font-family: Arial, sans-serif;
}

.table-container h2 {
    text-align: center;
    color: #2c3e50;
    font-size: 2.5vh;
    margin-bottom: 2vh;
}

table {
      width: 100%;
      max-width: 100%;
      border-collapse: collapse;
      font-size: 1.4vh;
      margin-top: 2vh;
      table-layout: fixed; /* Ensures cells are evenly spaced */
      word-wrap: break-word; /* Prevents text overflow */
    }

thead th {
    background-color: #ecf0f1;
    text-align: left;
    padding: 1.2vh;
    font-weight: bold;
    position: sticky;
    top: 0;
    z-index: 1;
}

tbody td {
    padding: 1vh;
    border-bottom: 0.2vh solid #e1e1e1;
}

tbody tr:nth-child(even) {
    background-color: #f4f6f7;
}

tbody tr:hover {
    background-color: #eaf2f8;
    transition: background 0.3s ease;
}

.hover-button {
    margin-bottom: 2vh;
    font-size: 1.6vh;
    padding: 1vh 2vh;
    border: 1vh solid var(--maroonColor);
    color: var(--maroonColor);
    border-radius: 1vh;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s ease;
}

/* Hover effect */
.hover-button:hover {
    color: #ffffff;
    border-color: #80050d; /* Darker maroon for the border */
    transform: scale(1.05); /* Slight zoom effect */
}
</style>
</head>
<body>

<div class="report-container">
   <h1>Vehicle Monitoring Summary Report</h1>
   <p style="text-align:center; font-weight: bold; font-size: 2.3vh; color: #80050d; margin-bottom: 2vh;">
    Total Reports: <?php echo $result->num_rows; ?>
</p>
<div style="text-align: center;">
  <button onclick="printTable()" class="hover-button" style="margin-bottom: 2vh; font-size: 1.6vh; padding: 1vh 2vh; border: .4vh solid var(--maroonColor); color: var(--maroonColor); border-radius: 1vh; cursor: pointer;">
    🖨️ Print Table
  </button>
</div>

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
 <!-- end of .cards-container -->

 <div class="table-container" style="display: none;">
    <h2>Tabular Summary View</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Department</th>
                <th>Activity</th>
                <th>Purpose</th>
                <th>Date Filed</th>
                <th>Budget No</th>
                <th>Vehicle</th>
                <th>Driver</th>
                <th>Destination</th>
                <th>Departure</th>
                <th>Passenger Count</th>
                <th>Transportation Cost</th>
                <th>Total Cost</th>
                <th>Immediate Head</th>
                <th>GSO Assistant</th>
                <th>GSO Director</th>
                <th>Accounting</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $result->data_seek(0);
        while($row = $result->fetch_assoc()) {
            $transportation_cost = str_replace('Transportation Cost:', '', $row["transportation_cost"]);

            echo "<tr>";
            echo "<td>".$row["name"]."</td>";
            echo "<td>".$row["department"]."</td>";
            echo "<td>".$row["activity"]."</td>";
            echo "<td>".$row["purpose"]."</td>";
            echo "<td>".$row["date_filed"]."</td>";
            echo "<td>".$row["budget_no"]."</td>";
            echo "<td>".$row["vehicle"]."</td>";
            echo "<td>".$row["driver"]."</td>";
            echo "<td>".$row["destination"]."</td>";
            echo "<td>".$row["departure"]."</td>";
            echo "<td>".$row["passenger_count"]."</td>";
            echo "<td>".$transportation_cost."</td>";
            echo "<td>₱".number_format($row["total_cost"], 2)."</td>";
            echo "<td>".$row["immediatehead_status"]."</td>";
            echo "<td>".$row["gsoassistant_status"]."</td>";
            echo "<td>".$row["gsodirector_status"]."</td>";
            echo "<td>".$row["accounting_status"]."</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>



<script>
function printTable() {
    const tableContent = document.querySelector('.table-container').innerHTML;
    const printWindow = window.open('', '', 'height=800,width=1000');
    printWindow.document.write('<html><head><title>Print Table</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body { font-family: Arial, sans-serif; color: #2c3e50; text-align: center; }');
    printWindow.document.write('.header-container { display: flex; align-items: center; justify-content: center; gap: 10px; border-bottom: 2px solid #2980b9; padding: 1vh; margin-bottom: 2vh; }');
    printWindow.document.write('.header-logo { width: 60px; height: 60px; }');
    printWindow.document.write('.header-content { text-align: center; }');
    printWindow.document.write('.csa-title { font-size: 2em; color: #80050d; margin: 0; font-family: "Old English Text MT", serif; }');
    printWindow.document.write('.csa-subtitle { font-size: 1em; color: #7f8c8d; margin: 0; }');
    printWindow.document.write('table { width: 100%; max-width: 100%; border-collapse: collapse; font-size: 1.4vh; margin-top: 2vh; table-layout: fixed; word-wrap: break-word; }');
printWindow.document.write('th, td { border: 1px solid #999; padding: 1vh; overflow: hidden; text-overflow: ellipsis; }');
    printWindow.document.write('th { background-color: #ecf0f1; }');
    printWindow.document.write('tr:nth-child(even) { background-color: #f4f6f7; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');

    printWindow.document.write('<div class="header-container">');
    printWindow.document.write('<img src="PNG/CSA_Logo.png" alt="CSA Logo" class="header-logo">');
    printWindow.document.write('<div class="header-content">');
    printWindow.document.write('<h1 class="csa-title">Colegio San Agustin</h1>');
    printWindow.document.write('<h2 class="csa-subtitle">COLLEGE DEPARTMENT</h2>');
    printWindow.document.write('<p class="csa-subtitle">Southwoods Ecocentrum, San Francisco, 4024 Biñan City, Laguna, Philippines</p>');
    printWindow.document.write('<p class="csa-subtitle">Tel. No. (+63 2) 8478-0167 • Fax No. (+63 2) 8478-0180</p>');
    printWindow.document.write('<p class="csa-subtitle">VERITAS • CARITAS • UNITAS</p>');
    printWindow.document.write('</div>');
    printWindow.document.write('<img src="PNG/GSO_Logo.png" alt="GSO Logo" class="header-logo">');
    printWindow.document.write('</div>');

    printWindow.document.write(tableContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>



</body>
</html>
