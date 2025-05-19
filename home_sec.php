<?php  
include('config.php');

$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'Pending';

$sql = "SELECT * FROM vrftb 
        WHERE gsodirector_status = ? 
        AND departure >= CURDATE()
        ORDER BY STR_TO_DATE(departure, '%Y-%m-%d %H:%i:%s') ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $statusFilter);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Vehicle Requests</title>
<style>
 * {
   margin: 0;
   padding: 0;
   box-sizing: border-box;
   text-decoration: none;
}

  :root {
    --maroon: #80050d;
    --yellow: #efb954;
    --white: #fff;
    --gray-light: #f7f9fc;
    --gray-medium: #7f8c8d;
    --gray-dark: #2c3e50;
    --green: #27ae60;
    --orange: #f39c12;
    --red: #e74c3c;
    --shadow: rgba(0, 0, 0, 0.1);
  }

  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: var(--gray-light);
    color: var(--gray-dark);
  }

  .container {
  max-width: 150vh; /* Previously 1200vh, this is way smaller */
  background: var(--white);
  margin: 2vh auto;
  border-radius: 1.2vh;
  box-shadow: 0 0.8vh 2.4vh var(--shadow);
  padding: 2vh;
}

  h2.section-title {
    font-weight: 800;
    font-size: 2.25rem;
    color: var(--maroon);
    margin-bottom: 0.5vh;
  }

  p.section-subtitle {
    font-size: 1rem;
    color: var(--gray-medium);
    margin-bottom: 3vh;
  }

  .status-filter {
    margin-bottom: 2.5vh;
    display: flex;
    align-items: center;
    gap: 1vh;
  }
  .status-filter label {
    font-weight: 700;
    font-size: 1rem;
    color: var(--gray-dark);
  }
  .status-filter select {
    padding: 0.8vh 1.4vh;
    font-size: 1rem;
    border-radius: 0.8vh;
    border: 0.15vh solid var(--gray-medium);
    background-color: var(--white);
    transition: border-color 0.3s ease;
    cursor: pointer;
  }
  .status-filter select:hover,
  .status-filter select:focus {
    border-color: var(--maroon);
    outline: none;
  }

  /* Table styles */
  .table-responsive {
    overflow-x: auto;
    box-shadow: inset 0 0 0.8vh var(--shadow);
    border-radius: 1.2vh;
  }

table {
  width: 100%;
  border-collapse: collapse;
  min-width: unset; /* Remove fixed width */
  font-size: 0.75rem; /* Smaller font size */
}

  thead {
    background-color: #f4f6f8;
    box-shadow: inset 0 -0.1vh 0 #ddd;
  }

  th, td {
     padding: 0.8vh 1vh;
    border-bottom: 0.1vh solid #e1e6eb;
    text-align: left;
    vertical-align: middle;
  }

  th {
    font-weight: 700;
    color: var(--gray-dark);
    letter-spacing: 0.03em;
  }

  tbody tr:hover {
    background-color: #f0f4f9;
    cursor: default;
    transition: background-color 0.25s ease;
  }

  .departure-highlight {
    color: var(--maroon);
    font-weight: 700;
    white-space: nowrap;
  }

  .status-pill {
    padding: 0.6vh 1.4vh;
    border-radius: 3vh;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--white);
    text-transform: capitalize;
    display: inline-block;
    white-space: nowrap;
    transition: background-color 0.3s ease;
  }
  .Pending {
    background-color: var(--orange);
    box-shadow: 0 0.2vh 0.8vh rgba(243, 156, 18, 0.4);
  }
  .Approved {
    background-color: var(--green);
    box-shadow: 0 0.2vh 0.8vh rgba(39, 174, 96, 0.4);
  }
  .Rejected {
    background-color: var(--red);
    box-shadow: 0 0.2vh 0.8vh rgba(231, 76, 60, 0.4);
  }

  .no-data {
    text-align: center;
    font-style: italic;
    color: var(--gray-medium);
    padding: 3vh 0;
  }

  /* Responsive adjustments */
  @media (max-width: 960px) {
    body {
      padding: 3vh 1vh;
    }
    .container {
      padding: 2.5vh 2vh;
    }
    table {
      min-width: 70vh;
    }
  }

  /* Mobile - stack rows */
  @media (max-width: 768px) {
    .status-filter {
      flex-direction: column;
      align-items: flex-start;
      gap: 0.8vh;
    }
    table, thead, tbody, th, td, tr {
      display: block;
      width: 100%;
    }
    thead tr {
      position: absolute;
      top: -9999vh;
      left: -9999vh;
    }
    tr {
      margin-bottom: 2vh;
      background: var(--white);
      border-radius: 1.2vh;
      padding: 1.5vh 2vh;
      box-shadow: 0 0.2vh 1vh var(--shadow);
      border: none;
    }
    td {
      position: relative;
      padding-left: 5vh;
      border: none;
      border-bottom: 0.1vh solid #eee;
      text-align: left;
      white-space: normal;
      word-wrap: break-word;
      font-size: 0.9rem;
    }
    td:last-child {
      border-bottom: none;
    }
    td:before {
      position: absolute;
      top: 1.5vh;
      left: 2vh;
      width: 4.5vh;
      padding-right: 1vh;
      white-space: nowrap;
      font-weight: 700;
      color: var(--gray-dark);
      content: attr(data-label);
    }
    .departure-highlight {
      font-weight: 700;
      color: var(--maroon);
    }
  }
</style>

</head>
<body>
    

<div class="container" role="main" aria-label="Vehicle Requests Overview">
  <h2 class="section-title">Vehicle Requests Overview</h2>
  <p class="section-subtitle">Viewing requests with status

  <div class="status-filter">
    <label for="status">Filter by GSODirector Status:</label>
    <select id="status" onchange="filterStatus()" aria-label="Filter vehicle requests by status">
      <option value="Pending" <?= $statusFilter == 'Pending' ? 'selected' : '' ?>>Pending</option>
      <option value="Approved" <?= $statusFilter == 'Approved' ? 'selected' : '' ?>>Approved</option>
      <option value="Rejected" <?= $statusFilter == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
    </select>
  </div>

  <div class="table-responsive">
    <table role="table" aria-describedby="table-desc">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Department</th>
          <th scope="col">Purpose</th>
          <th scope="col">Destination</th>
          <th scope="col">Departure</th>
          <th scope="col">Vehicle</th>
          <th scope="col">Driver</th>
           <th scope="col">Accounting</th>
          <th scope="col">GSO Assistant</th>
          <th scope="col">GSO Director</th>
         
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td data-label="#"><?= htmlspecialchars($row['id']) ?></td>
            <td data-label="Name"><?= htmlspecialchars($row['name']) ?></td>
            <td data-label="Department"><?= htmlspecialchars($row['department']) ?></td>
            <td data-label="Purpose"><?= htmlspecialchars($row['purpose']) ?></td>
            <td data-label="Destination"><?= htmlspecialchars($row['destination']) ?></td>
            <td data-label="Departure" class="departure-highlight"><?= date("F j, Y h:i A", strtotime($row['departure'])) ?></td>
            <td data-label="Vehicle"><?= htmlspecialchars($row['vehicle']) ?></td>
            <td data-label="Driver"><?= htmlspecialchars($row['driver']) ?></td>
            <td data-label="GSO Assistant">
              <span class="status-pill <?= htmlspecialchars($row['gsoassistant_status']) ?>">
                <?= htmlspecialchars($row['gsoassistant_status']) ?>
              </span>
            </td>
             <td data-label="Accounting">
              <span class="status-pill <?= htmlspecialchars($row['accounting_status']) ?>">
                <?= htmlspecialchars($row['accounting_status']) ?>
              </span>
            </td>
            <td data-label="GSO Director">
              <span class="status-pill <?= htmlspecialchars($row['gsodirector_status']) ?>">
                <?= htmlspecialchars($row['gsodirector_status']) ?>
              </span>
            </td>
          </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="11" class="no-data">No <?= htmlspecialchars($statusFilter) ?> requests found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  function filterStatus() {
    const selected = document.getElementById('status').value;
    window.location.href = '?status=' + encodeURIComponent(selected);
  }
</script>

</body>
</html>
