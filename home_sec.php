<?php   
include('config.php');

$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'Pending';
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT * FROM vrftb 
        WHERE immediatehead_status = 'Approved' 
          AND gsodirector_status = ? 
          AND departure >= CURDATE()";

$params = [];
$param_types = "s";  // for statusFilter

$params[] = $statusFilter;

if ($searchTerm !== '') {
    // Add search condition - searching across multiple columns
    $sql .= " AND (
        name LIKE ? OR 
        department LIKE ? OR 
        purpose LIKE ? OR 
        destination LIKE ? OR 
        vehicle LIKE ? OR 
        driver LIKE ?
    )";
    // Add six search params (one for each field), all with same type 's'
    $param_types .= "ssssss";
    $searchWildcard = "%" . $searchTerm . "%";
    for ($i = 0; $i < 6; $i++) {
        $params[] = $searchWildcard;
    }
}

$sql .= " ORDER BY STR_TO_DATE(departure, '%Y-%m-%d %H:%i:%s') ASC";

$stmt = $conn->prepare($sql);

// Dynamically bind params
$stmt->bind_param($param_types, ...$params);
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

  .Seen {
  color: var(--green);
  font-weight: 600;
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


  .filter-search-form {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding: 0.75rem 1rem;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    font-family: Arial, sans-serif;
  }

  .filter-group, .search-group {
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .filter-group label {
    font-weight: 600;
    color: #333;
    white-space: nowrap;
  }

  select {
    padding: 0.4rem 0.6rem;
    font-size: 1rem;
    border-radius: 5px;
    border: 1px solid #ccc;
    background-color: white;
    cursor: pointer;
    transition: border-color 0.2s ease;
  }
  select:focus {
    border-color:var(--maroon);
    outline: none;
  }

  input[type="search"] {
    padding: 0.5rem 0.75rem;
    font-size: 1rem;
    border-radius: 5px;
    border: 1px solid #ccc;
    width: 200px;
    transition: border-color 0.2s ease;
  }
  input[type="search"]:focus {
    border-color: var(--maroon);
    outline: none;
  }

  button[type="submit"] {
    padding: 0.5rem 1.2rem;
     background-color: var(--white);
   border: 2px solid var(--maroon);
    border-radius: 5px;
    color: var(--maroon);
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.3s ease, border-radius 0.3s ease;
  }
 button[type="submit"]:hover {
  transform: translateY(-3px);
  border-radius: 15px;
}
  /* Screen-reader only */
  .sr-only {
    position: absolute;
    width: 1px; 
    height: 1px; 
    padding: 0; 
    margin: -1px; 
    overflow: hidden; 
    clip: rect(0,0,0,0); 
    border: 0;
  }


  

.image-card-container {
  display: flex;
  justify-content: center;
  gap: 3vh;
  margin-top: 5vh;
  flex-wrap: wrap;
}

.image-card {
  width: 34vh;
  height: 38vh; /* Adjusted to include image + text */
  border-radius: 1.5vh;
  box-shadow: 0 0.8vh 2vh rgba(0, 0, 0, 0.2);
  background-color: #ffffff;
  text-align: center;
  transition: transform 0.3s, box-shadow 0.3s;
  cursor: pointer;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.image-card img {
  width: 100%;
  height: 30vh;  /* Fixed height for consistent layout */
  object-fit: cover;
  border-top-left-radius: 1.5vh;
  border-top-right-radius: 1.5vh;
}

.card-text {
  height: 8vh; /* Fixed height for text */
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2vh;
  font-weight: bold;
  color: #333;
  background-color: #e1e6eb;
  border-top: 0.1vh solid #ddd;
}

.image-card:hover {
  transform: translateY(-1vh) scale(1.05);
  box-shadow: 0 1.2vh 3vh rgba(0, 0, 0, 0.25);
}

.fade-out {
  opacity: 0;
  transition: opacity 0.5s ease;
}





</style>
</head>
<body>

<div class="container" role="main" aria-label="Vehicle Requests Overview">
  <h2 class="section-title">Vehicle Requests Overview</h2>
  <p class="section-subtitle">Viewing requests with status</p>

  <!-- Filter and Search Form -->
<form method="get" action="" class="filter-search-form">
  <!-- Left side: Filter label + select -->
  <div class="filter-group">
    <label for="status">Filter by GSODirector Status:</label>
    <select id="status" name="status" aria-label="Filter vehicle requests by status" onchange="this.form.submit()">
      <option value="Pending" <?= $statusFilter == 'Pending' ? 'selected' : '' ?>>Pending</option>
      <option value="Approved" <?= $statusFilter == 'Approved' ? 'selected' : '' ?>>Approved</option>
      <option value="Rejected" <?= $statusFilter == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
    </select>
  </div>

  <!-- Right side: Search input + button -->
  <div class="search-group">
    <label for="search" class="sr-only">Search requests</label>
    <input 
      type="search" 
      id="search" 
      name="search" 
      placeholder="Search all fields..." 
      value="<?= htmlspecialchars($searchTerm) ?>" 
      aria-label="Search vehicle requests"
    />
    <button type="submit">Search</button>
  </div>
</form>

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
          <th scope="col">GSO Assistant</th>
          <th scope="col">Accounting</th>
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

<div class="image-card-container">
  <a href="GSO.php?mveh=a" class="image-card">
    <img src="PNG/check_s.png" alt="Dashboard">
    <div class="card-text">Manage Vehicle</div>
  </a>
  
  <a href="GSO.php?aveh=a" class="image-card">
    <img src="PNG/add_v.png" alt="Add Vehicle">
    <div class="card-text">Add Vehicle</div>
  </a>

  <a href="GSO.php?mche=a" class="image-card">
    <img src="PNG/main_t.png" alt="Vehicle List">
    <div class="card-text">Maintenance Vehicle</div>
  </a>

    <a href="GSO.php?papp=a" class="image-card">
    <img src="PNG/pen.png" alt="Vehicle List">
    <div class="card-text">Pending Approval</div>
  </a>
</div>

<script>
  function filterStatus() {
    const selected = document.getElementById('status').value;
    window.location.href = '?status=' + encodeURIComponent(selected);
  }

   // Attach click listener to all image-card links
  document.querySelectorAll('.image-card').forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault(); // prevent instant navigation

      // Add fade-out class to body or container
      document.body.classList.add('fade-out');

      // Delay the navigation to allow the transition to play
      setTimeout(() => {
        window.location.href = this.href;
      }, 500); // Match the CSS transition duration
    });
  });
</script>

</body>
</html>
