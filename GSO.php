<?php
   date_default_timezone_set('Asia/Manila');
   session_start();
   ob_start();
   
   // Handle logout
   if (isset($_GET['logout']) && $_GET['logout'] == 1) {
      session_destroy();
      header("Location: index.php");
      exit();
   }
   
   if ($_SESSION['role'] == null) {
      header("Location: logout.php");
      exit();
   }
   if ($_SESSION['role'] != 'Secretary') {
      $inactive = 3600; // 1 hour
      if (isset($_SESSION['timeout'])) {
         $session_life = time() - $_SESSION['timeout'];
         if ($session_life > $inactive) {
               session_destroy();
               header("Location: logout.php");
               exit();
         }
      }
      $_SESSION['timeout'] = time();
   }

   // Handle AJAX search requests
   if (isset($_GET['ajax_search'])) {
      include 'config.php';
      header('Content-Type: application/json');
      
      $searchTerm = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
      $pageType = isset($_GET['page']) ? $_GET['page'] : '';
      
      // Build search query (searches in name, department, activity, purpose, budget_no, destination)
      $searchFilter = "";
      if (!empty($searchTerm)) {
         $searchFilter = " AND (name LIKE '%$searchTerm%' OR department LIKE '%$searchTerm%' OR activity LIKE '%$searchTerm%' OR purpose LIKE '%$searchTerm%' OR budget_no LIKE '%$searchTerm%' OR destination LIKE '%$searchTerm%' OR id LIKE '%$searchTerm%')";
      }
      
      $results = [];
      
      if ($pageType === 'pending') {
         // Same logic as pendingApproval function
         if($_SESSION['role']=='Secretary'||$_SESSION['role']=='Admin') {
            $status2 = "(immediatehead_status='Approved' AND gsoassistant_status='Pending') OR (immediatehead_status='Approved' AND gsoassistant_status='Seen')";
            $status="gsoassistant_status";
         } elseif($_SESSION['role']=='Immediate Head') {
            $status2 = "department='". $_SESSION['department'] ."' AND ((immediatehead_status='Pending') OR (immediatehead_status='Seen'))";
            $status="immediatehead_status";
         } elseif($_SESSION['role']=='Director') {
            $status2 = "(accounting_status='Approved' AND gsodirector_status='Pending') OR (accounting_status='Approved' AND gsodirector_status='Seen')";
            $status="gsodirector_status";
         } elseif($_SESSION['role']=='Accountant') {
            $status2 = "(gsoassistant_status='Approved' AND accounting_status='Pending') OR (gsoassistant_status='Approved' AND accounting_status='Seen')";
            $status="accounting_status";
         }
         
         $query = "SELECT * FROM vrftb WHERE $status2 $searchFilter ORDER BY date_filed DESC, id DESC";
         
      } elseif ($pageType === 'approved') {
         // Same logic as reservationApproved function
         if($_SESSION['role']=='Secretary') {
            $status='gsoassistant_status';
         } elseif($_SESSION['role']=='Immediate Head') {
            $status='immediatehead_status';
         } elseif($_SESSION['role']=='Director') {
            $status='gsodirector_status';
         } elseif($_SESSION['role']=='Accountant') {
            $status='accounting_status';
         }
         
         $showOld = isset($_GET['show_old']) && $_GET['show_old'] == 1;
         if ($showOld) {
            $query = "SELECT * FROM vrftb WHERE gsoassistant_status='Approved' AND immediatehead_status='Approved' AND gsodirector_status='Approved' AND accounting_status='Approved' $searchFilter ORDER BY date_filed DESC, id DESC";
         } else {
            $query = "SELECT * FROM vrftb WHERE gsoassistant_status='Approved' AND immediatehead_status='Approved' AND gsodirector_status='Approved' AND accounting_status='Approved' AND updated_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH) $searchFilter ORDER BY date_filed DESC, id DESC";
         }
         
      } elseif ($pageType === 'cancelled') {
         // Same logic as cancelledRequests function
         if($_SESSION['role']=='Secretary') {
            $status='gsoassistant_status';
         } elseif($_SESSION['role']=='Immediate Head') {
            $status='immediatehead_status';
         } elseif($_SESSION['role']=='Director') {
            $status='gsodirector_status';
         } elseif($_SESSION['role']=='Accountant') {
            $status='accounting_status';
         }
         
         $showOld = isset($_GET['show_old']) && $_GET['show_old'] == 1;
         if ($showOld) {
            $query = "SELECT * FROM vrftb WHERE gsoassistant_status='Rejected' OR immediatehead_status='Rejected' OR gsodirector_status='Rejected' OR accounting_status='Rejected' OR user_cancelled='Yes' $searchFilter ORDER BY date_filed DESC, id DESC";
         } else {
            $query = "SELECT * FROM vrftb WHERE updated_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH) AND (gsoassistant_status='Rejected' OR immediatehead_status='Rejected' OR gsodirector_status='Rejected' OR accounting_status='Rejected' OR user_cancelled='Yes') $searchFilter ORDER BY date_filed DESC, id DESC";
         }
      }
      
      if (isset($query)) {
         $result = $conn->query($query);
         if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
               $results[] = $row;
            }
         }
      }
      
      echo json_encode($results);
      exit;
   }
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Vehicle Reservation and Maintenance System</title>
      <link rel="stylesheet" href="styles.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
      <style>
         <?php
            if(isset($_GET["papp"]) and !empty($_GET["papp"]))
            {
               ?> 
                  #requests 
                  { 
                     background-color: white; font-weight: bold;
                  }
               <?php
            }
            elseif(isset($_GET["rapp"]) and !empty($_GET["rapp"]))
            {
               ?> #requests { background-color: white; font-weight: bold;} <?php
            }
            elseif(isset($_GET["creq"]) and !empty($_GET["creq"]))
            {
               ?> #requests { background-color: white; font-weight: bold;} <?php
            }
            elseif(isset($_GET["aveh"]) and !empty($_GET["aveh"]))
            {
               ?> #vehicle { background-color: white; font-weight: bold;} <?php
            }
            elseif(isset($_GET["mveh"]) and !empty($_GET["mveh"]))
            {
               ?> 
                  #vehicle 
                  { 
                     background-color: white; font-weight: bold;
                  } 
                  .delete-btnplt 
                  {
                     i 
                     {
                        color: #80050d;
                     }
                  }
                  label 
                  {
                     color: #333333;
                  }
               <?php
            }
            elseif(isset($_GET["eveh"]) and !empty($_GET["eveh"]))
            {
               ?> #vehicle { background-color: white; font-weight: bold;} <?php
            }
            elseif(isset($_GET["vres"]) and !empty($_GET["vres"]))
            {
               ?> #calendar { background-color: white; font-weight: bold;} <?php
            }
            elseif(isset($_GET["vsch"]) and !empty($_GET["vsch"]))
            {
               ?> #calendar { background-color: white; font-weight: bold;} <?php
            }
            elseif(isset($_GET["dsch"]) and !empty($_GET["dsch"]))
            {
               ?> #calendar { background-color: white; font-weight: bold;} <?php
            }
            elseif(isset($_GET["mche"]) and !empty($_GET["mche"]))
            {
               ?> #vehicle { background-color: white; font-weight: bold;} <?php
            }
            elseif(isset($_GET["macc"]) and !empty($_GET["macc"]))
            {
               ?> #account { background-color: white; font-weight: bold;} <?php
            }
            elseif(isset($_GET["mdep"]) and !empty($_GET["mdep"]))
            {
               ?> #account { background-color: white; font-weight: bold;} <?php
            }
            elseif(isset($_GET["srep"]) and !empty($_GET["srep"]))
            {
               ?> #report { background-color: white; font-weight: bold;} <?php
            }
            elseif(isset($_GET["mrep"]) and !empty($_GET["mrep"]))
            {
               ?> #report { background-color: white; font-weight: bold;} <?php
            }
            else
            { 
               ?> #home div { background-color: white; font-weight: bold;} <?php
            }
         ?>
      </style>
      <script type="text/javascript" src="app.js" defer></script>
   </head>
<body>

<div id="burger-container"></div>

<script>
if (window.innerWidth < 992) {
    fetch('burger.php')
    .then(response => response.text())
    .then(data => {
        var container = document.getElementById('burger-container');
        container.innerHTML = data;

        var scripts = container.querySelectorAll('script');
        scripts.forEach(function(script) {
            var newScript = document.createElement('script');
            if (script.src) {
                newScript.src = script.src;
            } else {
                newScript.textContent = script.textContent;
            }
            document.body.appendChild(newScript);
        });
    });
}

</script>

<nav class="sidebar active">
      <button onclick="toggleSidebar()" id="logo">
         <img src="PNG/GSO_Logo.png" alt="">
         <span class="logo">GSO</span>
      </button>
      <li id="home">
         <a href="GSO.php" class="icon">
            <div>
               <img src="PNG/Home.png" alt="Home">
               <span class="title">Home</span>
            </div>
         </a>
      </li>
      <?php
      if($_SESSION['role'] == "Secretary" OR $_SESSION['role'] == "Admin")
      {
         ?>
            <ul class="nav-list">
               <li style="height: 2.5vw;"></li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="calendar" title="Calendar" data-id="calendar">
                     <img src="PNG/Calendar.png" alt="Calendar">
                     <span>Calendar</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?vsch=a" title="Vehicle Schedules"><span>Vehicle Schedules</span></a></li>
                        <li><a href="GSO.php?vres=a" title="Vehicle Reservation Form"><span>Vehicle Reservation Form</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="requests" title="Requests" data-id="requests">
                     <img src="PNG/Pie.png" alt="Requests">
                     <span>Requests</span>
                     <?php
                        if($_SESSION['role']=='Secretary'||$_SESSION['role']=='Admin')
                        {
                           $status2 = "(immediatehead_status='Approved' AND gsoassistant_status='Pending') OR (immediatehead_status='Approved' AND gsoassistant_status='Seen')";
                           $status="gsoassistant_status";
                        }
                        elseif($_SESSION['role']=='Immediate Head')
                        {
                           $status2 = "department='". $_SESSION['department'] ."' AND ((immediatehead_status='Pending') OR (immediatehead_status='Seen'))";
                           $status="immediatehead_status";
                        }
                        elseif($_SESSION['role']=='Director')
                        {
                           $status2 = "(accounting_status='Approved' AND gsodirector_status='Pending') OR (accounting_status='Approved' AND gsodirector_status='Seen')";
                           $status="gsodirector_status";
                        }
                        else if($_SESSION['role']=='Accountant')
                        {
                           $status2 = "(gsoassistant_status='Approved' AND accounting_status='Pending') OR (gsoassistant_status='Approved' AND accounting_status='Seen')";
                           $status="accounting_status";
                        }
                        else if ($_SESSION['role'] == 'User') {
                           $status2 = "name='" . $_SESSION['name'] . "' AND department='" . $_SESSION['department'] . "' AND ((immediatehead_status='Pending') OR (gsoassistant_status='Pending') OR (gsodirector_status='Pending') OR (accounting_status='Pending') OR (immediatehead_status='Seen') OR (gsoassistant_status='Seen') OR (gsodirector_status='Seen') OR (accounting_status='Seen'))";
                           $status = "immediatehead_status"; // This can be any status column since we're checking for all pending/seen statuses   
                        }
                        include 'config.php';
                        $selectpending = "SELECT * FROM vrftb WHERE $status2";
                        $resultpending = $conn->query($selectpending);
                        $pending_count = $resultpending->num_rows;
                        if ($pending_count > 0) {
                           ?>
                              <div id="pending-notif"></div>
                           <?php
                        }
                     ?>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?papp=a" title="Pending Approval"><span>Pending Approval </span>
                           <?php
                              if($pending_count>0)
                              {
                                  ?>
                                    <span id="pending-number"><?php
                                       echo $pending_count;
                                    ?></span>
                                 <?php
                              }
                              else
                              {
                                
                              }
                           ?>
                        </a></li>
                        <li><a href="GSO.php?rapp=a" title="Reservation Approved"><span>Reservation Approved</span></a></li>
                        <li><a href="GSO.php?creq=a" title="Cancelled Requests"><span>Cancelled Requests</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="vehicle" title="Vehicles" data-id="vehicle">
                     <img src="PNG/Vehicle.png" alt="Vehicle">
                     <span>Vehicles</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?mveh=a" title="Manage Vehicle"><span>Manage Vehicle</span></a></li>
                        <li><a href="GSO.php?aveh=a" title="Add Vehicle"><span>Add Vehicle</span></a></li>
                        <li><a href="GSO.php?mche=a" title="Maintenance Checklist"><span>Maintenance Checklist</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="account" title="Accounts" data-id="account">
                     <img src="PNG/Account.png" alt="Report">
                     <span>Accounts</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?macc=a" title="Manage Accounts"><span>Manage Accounts</span></a></li>
                        <li><a href="GSO.php?mdep=a" title="Manage Departments"><span>Manage Departments</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="report" title="Report" data-id="report">
                     <img src="PNG/File.png" alt="Report">
                     <span>Report</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                       <?php
                                 if ($_SESSION["role"] != "Secretary" && $_SESSION["role"] != "Director") {
                                 ?>
                                    <li>
                                       <a href="GSO.php?srep=a" title="Summary Report">
                                             <span>Summary Report</span>
                                       </a>
                                    </li>
                                 <?php
                                 }
                                 ?>
                        <li><a href="GSO.php?mrep=a" title="Maintenance Report"><span>Maintenance Report</span></a></li>
                     </div>
                  </ul>
               </li>
            </ul>
         <?php
      }
      elseif($_SESSION['role'] == "Immediate Head")
      {
         ?>
            <ul class="nav-list">
               <li style="height: 2.5vw;"></li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="calendar" title="Calendar" data-id="calendar">
                     <img src="PNG/Calendar.png" alt="Calendar">
                     <span>Calendar</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?vsch=a" title="Vehicle Schedules"><span>Vehicle Schedules</span></a></li>
                        <li><a href="GSO.php?vres=a" title="Vehicle Reservation Form"><span>Vehicle Reservation Form</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="requests" title="Requests" data-id="requests">
                     <img src="PNG/Pie.png" alt="Requests">
                     <span>Requests</span>
                     <?php
                        if($_SESSION['role']=='Secretary'||$_SESSION['role']=='Admin')
                        {
                           $status2 = "(immediatehead_status='Approved' AND gsoassistant_status='Pending') OR (immediatehead_status='Approved' AND gsoassistant_status='Seen')";
                           $status="gsoassistant_status";
                        }
                        elseif($_SESSION['role']=='Immediate Head')
                        {
                           $status2 = "department='". $_SESSION['department'] ."' AND ((immediatehead_status='Pending') OR (immediatehead_status='Seen'))";
                           $status="immediatehead_status";
                        }
                        elseif($_SESSION['role']=='Director')
                        {
                           $status2 = "(accounting_status='Approved' AND gsodirector_status='Pending') OR (accounting_status='Approved' AND gsodirector_status='Seen')";
                           $status="gsodirector_status";
                        }
                        else if($_SESSION['role']=='Accountant')
                        {
                           $status2 = "(gsoassistant_status='Approved' AND accounting_status='Pending') OR (gsoassistant_status='Approved' AND accounting_status='Seen')";

                           $status="accounting_status";
                        }
                        else if ($_SESSION['role'] == 'User') {
                           $status2 = "name='" . $_SESSION['name'] . "' AND department='" . $_SESSION['department'] . "' AND ((immediatehead_status='Pending') OR (gsoassistant_status='Pending') OR (gsodirector_status='Pending') OR (accounting_status='Pending') OR (immediatehead_status='Seen') OR (gsoassistant_status='Seen') OR (gsodirector_status='Seen') OR (accounting_status='Seen'))";
                           $status = "immediatehead_status"; // This can be any status column since we're checking for all pending/seen statuses   
                        }
                        include 'config.php';
                        $selectpending = "SELECT * FROM vrftb WHERE $status2";
                        $resultpending = $conn->query($selectpending);
                        $pending_count = $resultpending->num_rows;
                        if ($pending_count > 0) {
                           ?>
                              <div id="pending-notif"></div>
                           <?php
                        }
                     ?>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?papp=a" title="Pending Approval"><span>Pending Approval </span>
                           <?php
                              if($pending_count>0)
                              {
                                 ?>
                                    <span id="pending-number"><?php
                                       echo $pending_count;
                                    ?></span>
                                 <?php
                              }
                              else
                              {
                                 
                              }
                           ?>
                        </a></li>
                        <li><a href="GSO.php?rapp=a" title="Reservation Approved"><span>Reservation Approved</span></a></li>
                        <li><a href="GSO.php?creq=a" title="Cancelled Requests"><span>Cancelled Requests</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="report" title="Report" data-id="report">
                     <img src="PNG/File.png" alt="Report">
                     <span>Report</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?srep=a" title="Summary Report"><span>Summary Report</span></a></li>
                     </div>
                  </ul>
               </li>
            </ul>
         <?php
      }
      elseif($_SESSION == "Accountant")
      {
         ?>
            <ul class="nav-list">
               <li style="height: 2.5vw;"></li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="calendar" title="Calendar" data-id="calendar">
                     <img src="PNG/Calendar.png" alt="Calendar">
                     <span>Calendar</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?vsch=a" title="Vehicle Schedules"><span>Vehicle Schedules</span></a></li>
                        <li><a href="GSO.php?vres=a" title="Vehicle Reservation Form"><span>Vehicle Reservation Form</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="requests" title="Requests" data-id="requests">
                     <img src="PNG/Pie.png" alt="Requests">
                     <span>Requests</span>
                     <?php
                        if($_SESSION['role']=='Secretary'||$_SESSION['role']=='Admin')
                        {
                           $status2 = "(immediatehead_status='Approved' AND gsoassistant_status='Pending') OR (immediatehead_status='Approved' AND gsoassistant_status='Seen')";
                           $status="gsoassistant_status";
                        }
                        elseif($_SESSION['role']=='Immediate Head')
                        {
                           $status2 = "department='". $_SESSION['department'] ."' AND ((immediatehead_status='Pending') OR (immediatehead_status='Seen'))";
                           $status="immediatehead_status";
                        }
                        elseif($_SESSION['role']=='Director')
                        {
                           $status2 = "(accounting_status='Approved' AND gsodirector_status='Pending') OR (accounting_status='Approved' AND gsodirector_status='Seen')";
                           $status="gsodirector_status";
                        }
                        else if($_SESSION['role']=='Accountant')
                        {
                           $status2 = "(gsoassistant_status='Approved' AND accounting_status='Pending') OR (gsoassistant_status='Approved' AND accounting_status='Seen')";

                           $status="accounting_status";
                        }
                        else if ($_SESSION['role'] == 'User') {
                           $status2 = "name='" . $_SESSION['name'] . "' AND department='" . $_SESSION['department'] . "' AND ((immediatehead_status='Pending') OR (gsoassistant_status='Pending') OR (gsodirector_status='Pending') OR (accounting_status='Pending') OR (immediatehead_status='Seen') OR (gsoassistant_status='Seen') OR (gsodirector_status='Seen') OR (accounting_status='Seen'))";
                           $status = "immediatehead_status"; // This can be any status column since we're checking for all pending/seen statuses   
                        }
                        include 'config.php';
                        $selectpending = "SELECT * FROM vrftb WHERE $status2";
                        $resultpending = $conn->query($selectpending);
                        $pending_count = $resultpending->num_rows;
                        if ($pending_count > 0) {
                           ?>
                              <div id="pending-notif"></div>
                           <?php
                        }
                     ?>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?papp=a" title="Pending Approval"><span>Pending Approval </span>
                           <?php
                              if($pending_count>0)
                              {
                                 ?>
                                    <span id="pending-number"><?php
                                       echo $pending_count;
                                    ?></span>
                                 <?php
                              }
                              else
                              {
                                 
                              }
                           ?>
                        </a></li>
                        <li><a href="GSO.php?rapp=a" title="Reservation Approved"><span>Reservation Approved</span></a></li>
                        <li><a href="GSO.php?creq=a" title="Cancelled Requests"><span>Cancelled Requests</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="vehicle" title="Vehicles" data-id="vehicle">
                     <img src="PNG/Vehicle.png" alt="Vehicle">
                     <span>Vehicles</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?mveh=a" title="Manage Vehicle"><span>Manage Vehicle</span></a></li>
                        <li><a href="GSO.php?aveh=a" title="Add Vehicle"><span>Add Vehicle</span></a></li>
                        <li><a href="GSO.php?mche=a" title="Maintenance Checklist"><span>Maintenance Checklist</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="account" title="Accounts" data-id="account">
                     <img src="PNG/Account.png" alt="Report">
                     <span>Accounts</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?macc=a" title="Manage Accounts"><span>Manage Accounts</span></a></li>
                        <li><a href="GSO.php?mdep=a" title="Manage Departments"><span>Manage Departments</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="report" title="Report" data-id="report">
                     <img src="PNG/File.png" alt="Report">
                     <span>Report</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?srep=a" title="Summary Report"><span>Summary Report</span></a></li>
                        <li><a href="GSO.php?mrep=a" title="Maintenance Report"><span>Maintenance Report</span></a></li>
                     </div>
                  </ul>
               </li>
            </ul>
         <?php
      }
      elseif($_SESSION == "Director")
      {
         ?>
            <ul class="nav-list">
               <li style="height: 2.5vw;"></li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="calendar" title="Calendar" data-id="calendar">
                     <img src="PNG/Calendar.png" alt="Calendar">
                     <span>Calendar</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?vsch=a" title="Vehicle Schedules"><span>Vehicle Schedules</span></a></li>
                        <li><a href="GSO.php?vres=a" title="Vehicle Reservation Form"><span>Vehicle Reservation Form</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="requests" title="Requests" data-id="requests">
                     <img src="PNG/Pie.png" alt="Requests">
                     <span>Requests</span>
                     <?php
                        if($_SESSION['role']=='Secretary'||$_SESSION['role']=='Admin')
                        {
                           $status2 = "(immediatehead_status='Approved' AND gsoassistant_status='Pending') OR (immediatehead_status='Approved' AND gsoassistant_status='Seen')";
                           $status="gsoassistant_status";
                        }
                        elseif($_SESSION['role']=='Immediate Head')
                        {
                           $status2 = "department='". $_SESSION['department'] ."' AND ((immediatehead_status='Pending') OR (immediatehead_status='Seen'))";
                           $status="immediatehead_status";
                        }
                        elseif($_SESSION['role']=='Director')
                        {
                           $status2 = "(accounting_status='Approved' AND gsodirector_status='Pending') OR (accounting_status='Approved' AND gsodirector_status='Seen')";
                           $status="gsodirector_status";
                        }
                        else if($_SESSION['role']=='Accountant')
                        {
                           $status2 = "(gsoassistant_status='Approved' AND accounting_status='Pending') OR (gsoassistant_status='Approved' AND accounting_status='Seen')";

                           $status="accounting_status";
                        }
                        else if ($_SESSION['role'] == 'User') {
                           $status2 = "name='" . $_SESSION['name'] . "' AND department='" . $_SESSION['department'] . "' AND ((immediatehead_status='Pending') OR (gsoassistant_status='Pending') OR (gsodirector_status='Pending') OR (accounting_status='Pending') OR (immediatehead_status='Seen') OR (gsoassistant_status='Seen') OR (gsodirector_status='Seen') OR (accounting_status='Seen'))";
                           $status = "immediatehead_status"; // This can be any status column since we're checking for all pending/seen statuses   
                        }
                        include 'config.php';
                        $selectpending = "SELECT * FROM vrftb WHERE $status2";
                        $resultpending = $conn->query($selectpending);
                        $pending_count = $resultpending->num_rows;
                        if ($pending_count > 0) {
                           ?>
                              <div id="pending-notif"></div>
                           <?php
                        }
                     ?>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?papp=a" title="Pending Approval"><span>Pending Approval </span>
                           <?php
                              if($pending_count>0)
                              {

                              }
                              else
                              {
                                 ?>
                                    <span id="pending-number"><?php
                                       echo $pending_count;
                                    ?></span>
                                 <?php
                              }
                           ?>
                        </a></li>
                        <li><a href="GSO.php?rapp=a" title="Reservation Approved"><span>Reservation Approved</span></a></li>
                        <li><a href="GSO.php?creq=a" title="Cancelled Requests"><span>Cancelled Requests</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="vehicle" title="Vehicles" data-id="vehicle">
                     <img src="PNG/Vehicle.png" alt="Vehicle">
                     <span>Vehicles</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?mveh=a" title="Manage Vehicle"><span>Manage Vehicle</span></a></li>
                        <li><a href="GSO.php?aveh=a" title="Add Vehicle"><span>Add Vehicle</span></a></li>
                        <li><a href="GSO.php?mche=a" title="Maintenance Checklist"><span>Maintenance Checklist</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="account" title="Accounts" data-id="account">
                     <img src="PNG/Account.png" alt="Report">
                     <span>Accounts</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?macc=a" title="Manage Accounts"><span>Manage Accounts</span></a></li>
                        <li><a href="GSO.php?mdep=a" title="Manage Departments"><span>Manage Departments</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="report" title="Report" data-id="report">
                     <img src="PNG/File.png" alt="Report">
                     <span>Report</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?srep=a" title="Summary Report"><span>Summary Report</span></a></li>
                        <li><a href="GSO.php?mrep=a" title="Maintenance Report"><span>Maintenance Report</span></a></li>
                     </div>
                  </ul>
               </li>
            </ul>
         <?php
      }
      elseif($_SESSION['role'] == "Accountant")
      {
         ?>
            <ul class="nav-list">
               <li style="height: 2.5vw;"></li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="calendar" title="Calendar" data-id="calendar">
                     <img src="PNG/Calendar.png" alt="Calendar">
                     <span>Calendar</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?vsch=a" title="Vehicle Schedules"><span>Vehicle Schedules</span></a></li>
                        <li><a href="GSO.php?vres=a" title="Vehicle Reservation Form"><span>Vehicle Reservation Form</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="requests" title="Requests" data-id="requests">
                     <img src="PNG/Pie.png" alt="Requests">
                     <span>Requests</span>
                     <?php
                        if($_SESSION['role']=='Secretary'||$_SESSION['role']=='Admin')
                        {
                           $status2 = "(immediatehead_status='Approved' AND gsoassistant_status='Pending') OR (immediatehead_status='Approved' AND gsoassistant_status='Seen')";
                           $status="gsoassistant_status";
                        }
                        elseif($_SESSION['role']=='Immediate Head')
                        {
                           $status2 = "department='". $_SESSION['department'] ."' AND ((immediatehead_status='Pending') OR (immediatehead_status='Seen'))";
                           $status="immediatehead_status";
                        }
                        elseif($_SESSION['role']=='Director')
                        {
                           $status2 = "(accounting_status='Approved' AND gsodirector_status='Pending') OR (accounting_status='Approved' AND gsodirector_status='Seen')";
                           $status="gsodirector_status";
                        }
                        else if($_SESSION['role']=='Accountant')
                        {
                           $status2 = "(gsoassistant_status='Approved' AND accounting_status='Pending') OR (gsoassistant_status='Approved' AND accounting_status='Seen')";

                           $status="accounting_status";
                        }
                        else if ($_SESSION['role'] == 'User') {
                           $status2 = "name='" . $_SESSION['name'] . "' AND department='" . $_SESSION['department'] . "' AND ((immediatehead_status='Pending') OR (gsoassistant_status='Pending') OR (gsodirector_status='Pending') OR (accounting_status='Pending') OR (immediatehead_status='Seen') OR (gsoassistant_status='Seen') OR (gsodirector_status='Seen') OR (accounting_status='Seen'))";
                           $status = "immediatehead_status"; // This can be any status column since we're checking for all pending/seen statuses   
                        }
                        include 'config.php';
                        $selectpending = "SELECT * FROM vrftb WHERE $status2";
                        $resultpending = $conn->query($selectpending);
                        $pending_count = $resultpending->num_rows;
                        if ($pending_count > 0) {
                           ?>
                              <div id="pending-notif"></div>
                           <?php
                        }
                     ?>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?papp=a" title="Pending Approval"><span>Pending Approval </span>
                           <?php
                              if($pending_count>0)
                              {
                                 ?>
                                    <span id="pending-number"><?php
                                       echo $pending_count;
                                    ?></span>
                                 <?php
                              }
                              else
                              {

                              }
                           ?>
                        </a></li>
                        <li><a href="GSO.php?rapp=a" title="Reservation Approved"><span>Reservation Approved</span></a></li>
                        <li><a href="GSO.php?creq=a" title="Cancelled Requests"><span>Cancelled Requests</span></a></li>
                     </div>
                  </ul>
               </li
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="report" title="Report" data-id="report">
                     <img src="PNG/File.png" alt="Report">
                     <span>Report</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?srep=a" title="Summary Report"><span>Summary Report</span></a></li>
                     </div>
                  </ul>
               </li>
            </ul>
         <?php
      } 
      elseif($_SESSION['role'] == "User" )
      {
         ?>
            <ul class="nav-list">
               <li style="height: 2.5vw;"></li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="calendar" title="Calendar" data-id="calendar">
                     <img src="PNG/Calendar.png" alt="Calendar">
                     <span>Calendar</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?vsch=a" title="Vehicle Schedules"><span>Vehicle Schedules</span></a></li>
                        <li><a href="GSO.php?vres=a" title="Vehicle Reservation Form"><span>Vehicle Reservation Form</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="report" title="Report" data-id="report">
                     <img src="PNG/File.png" alt="Report">
                     <span>Report</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?srep=a" title="Summary Report"><span>Summary Report</span></a></li>
                     </div>
                  </ul>
               </li>
            </ul>
         <?php
      } 
      elseif($_SESSION['role'] == "Driver")
      {
         ?>
            <ul class="nav-list">
               <li style="height: 2.5vw;"></li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="vehicle" title="Vehicles" data-id="vehicle">
                     <img src="PNG/Vehicle.png" alt="Vehicle">
                     <span>Vehicles</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?mche=a" title="Maintenance Checklist"><span>Maintenance Checklist</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="report" title="Report" data-id="report">
                     <img src="PNG/File.png" alt="Report">
                     <span>Report</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?srep=a" title="Summary Report"><span>Summary Report</span></a></li>
                        <li><a href="GSO.php?mrep=a" title="Maintenance Report"><span>Maintenance Report</span></a></li>
                     </div>
                  </ul>
               </li>
            </ul>
         <?php
      } 
      elseif ($_SESSION['role']=="Director")
      {
         ?>
            <ul class="nav-list">
               <li style="height: 2.5vw;"></li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="calendar" title="Calendar" data-id="calendar">
                     <img src="PNG/Calendar.png" alt="Calendar">
                     <span>Calendar</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?vsch=a" title="Vehicle Schedules"><span>Vehicle Schedules</span></a></li>
                        <li><a href="GSO.php?vres=a" title="Vehicle Reservation Form"><span>Vehicle Reservation Form</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="requests" title="Requests" data-id="requests">
                     <img src="PNG/Pie.png" alt="Requests">
                     <span>Requests</span>
                     <?php
                        if($_SESSION['role']=='Secretary'||$_SESSION['role']=='Admin')
                        {
                           $status2 = "(immediatehead_status='Approved' AND gsoassistant_status='Pending') OR (immediatehead_status='Approved' AND gsoassistant_status='Seen')";
                           $status="gsoassistant_status";
                        }
                        elseif($_SESSION['role']=='Immediate Head')
                        {
                           $status2 = "department='". $_SESSION['department'] ."' AND ((immediatehead_status='Pending') OR (immediatehead_status='Seen'))";
                           $status="immediatehead_status";
                        }
                        elseif($_SESSION['role']=='Director')
                        {
                           $status2 = "(accounting_status='Approved' AND gsodirector_status='Pending') OR (accounting_status='Approved' AND gsodirector_status='Seen')";
                           $status="gsodirector_status";
                        }
                        else if($_SESSION['role']=='Accountant')
                        {
                           $status2 = "(gsoassistant_status='Approved' AND accounting_status='Pending') OR (gsoassistant_status='Approved' AND accounting_status='Seen')";
                           $status="accounting_status";
                        }
                        else if ($_SESSION['role'] == 'User') {
                           $status2 = "name='" . $_SESSION['name'] . "' AND department='" . $_SESSION['department'] . "' AND ((immediatehead_status='Pending') OR (gsoassistant_status='Pending') OR (gsodirector_status='Pending') OR (accounting_status='Pending') OR (immediatehead_status='Seen') OR (gsoassistant_status='Seen') OR (gsodirector_status='Seen') OR (accounting_status='Seen'))";
                           $status = "immediatehead_status"; // This can be any status column since we're checking for all pending/seen statuses   
                        }
                        include 'config.php';
                        $selectpending = "SELECT * FROM vrftb WHERE $status2";
                        $resultpending = $conn->query($selectpending);
                        $pending_count = $resultpending->num_rows;
                        $selectvrfc = "SELECT * FROM vrftb WHERE updated_at >= DATE_SUB(NOW(), INTERVAL 1 DAY) AND (gsoassistant_status='Rejected' OR immediatehead_status='Rejected' OR gsodirector_status='Rejected' OR accounting_status='Rejected' OR user_cancelled='Yes') ORDER BY date_filed DESC, id DESC";
                        $resultvrfc = $conn->query($selectvrfc);
                        $cancel_count = $resultvrfc->num_rows;
                        if ($pending_count > 0) {
                           ?>
                              <div id="pending-notif"></div>
                           <?php
                        }
                     ?>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?papp=a" title="Pending Approval"><span>Pending Approval </span>
                           <?php
                              if($pending_count>0)
                              {
                                 ?>
                                    <span id="pending-number"><?php
                                       echo $pending_count;
                                    ?></span>
                                 <?php
                              }
                              else
                              {

                              }
                           ?>
                        </a></li>
                        <li><a href="GSO.php?rapp=a"><span>Reservation Approved </span></a></li>
                        <li><a href="GSO.php?creq=a" title="Cancelled Requests"><span>Cancelled Requests</span>
                           <?php
                              if($cancel_count>0)
                              {
                                 ?>
                                    <span id="pending-number"><?php
                                       echo $cancel_count;
                                    ?></span>
                                 <?php
                              }
                              else
                              {

                              }
                           ?></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="vehicle" title="Vehicles" data-id="vehicle">
                     <img src="PNG/Vehicle.png" alt="Vehicle">
                     <span>Vehicles</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?mveh=a" title="Manage Vehicle"><span>Manage Vehicle</span></a></li>
                        <li><a href="GSO.php?aveh=a" title="Add Vehicle"><span>Add Vehicle</span></a></li>
                        <li><a href="GSO.php?mche=a" title="Maintenance Checklist"><span>Maintenance Checklist</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="account" title="Accounts" data-id="account">
                     <img src="PNG/Account.png" alt="Report">
                     <span>Accounts</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?macc=a" title="Manage Accounts"><span>Manage Accounts</span></a></li>
                        <li><a href="GSO.php?mdep=a" title="Manage Departments"><span>Manage Departments</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="report" title="Report" data-id="report">
                     <img src="PNG/File.png" alt="Report">
                     <span>Report</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?srep=a" title="Summary Report"><span>Summary Report</span></a></li>
                        <li><a href="GSO.php?mrep=a" title="Maintenance Report"><span>Maintenance Report</span></a></li>
                     </div>
                  </ul>
               </li>
            </ul>
         <?php
      }
      include 'config.php'; // Your DB connection

      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['ppicture'])) {
          if ($_FILES['ppicture']['error'] === 0) {
              $filename = uniqid() . "_" . basename($_FILES['ppicture']['name']);
              $target_directory = "uploads/";
              $target_file = $target_directory . $filename;

              if (move_uploaded_file($_FILES['ppicture']['tmp_name'], $target_file)) {
                  $employeeid = $_SESSION['employeeid']; // Assumes employee ID is stored in session

                  // Update picture in database
                  $updateQuery = "UPDATE usertb SET ppicture = ? WHERE employeeid = ?";
                  $stmt = $conn->prepare($updateQuery);
                  $stmt->bind_param("ss", $filename, $employeeid);

                  if ($stmt->execute()) {
                      $_SESSION['ppicture'] = $filename; // Update session with new filename
                  } else {
                      echo "<script>alert('Failed to update profile picture in the database.');</script>";
                  }

                  $stmt->close();
              } else {
                  echo "<script>alert('Failed to upload image file.');</script>";
              }
          } else {
              echo "<script>alert('Error uploading file.');</script>";
          }

          $conn->close();
      }
      ?>
<div id="logout">
    <form id="uploadForm" method="POST" enctype="multipart/form-data">
        <!-- Profile Picture -->
        <img id="picture"
             src="uploads/<?php echo $_SESSION['ppicture']; ?>"
             alt="Profile Picture"
            style="cursor: pointer; width: 5vw; height: 5vw; object-fit: cover; border-radius: 50%; border: 2px solid #ccc;"
             title="Click to change profile picture">

        <!-- Hidden File Input -->
        <input type="file" name="ppicture" id="ppictureInput" accept="image/*" style="display: none;"
               onchange="document.getElementById('uploadForm').submit();">
    </form>

    <script>
        document.getElementById("picture").addEventListener("click", function(event) {
            event.preventDefault();
            let confirmChange = confirm("Do you want to change your profile picture?");
            if (confirmChange) {
                document.getElementById("ppictureInput").click();
            }
        });
    </script>

    <div id="profile-text">
        <span id="name"><?php echo $_SESSION['lname']." ".$_SESSION['fname']; ?></span>
        <span id="role"><?php echo $_SESSION['role']; ?></span>
    </div>

    <a href="GSO.php?logout=1">
        <button type="button">
            <img id="logout-img" src="PNG/Logout.png" alt="Logout">
        </button>
    </a>
</div>
   </nav>

   <?php
      if(isset($_GET["papp"]) and !empty($_GET["papp"]))
      {
         ?>
            <main>
         <?php
      }
      else
      {
         ?>
            <main>
         <?php
      }
   ?>
      <?php
         if(isset($_GET["papp"]) and !empty($_GET["papp"]))
         pendingApproval();
         elseif(isset($_GET["rapp"]) and !empty($_GET["rapp"]))
         reservationApproved();
         elseif(isset($_GET["creq"]) and !empty($_GET["creq"]))
         cancelledRequests();
         elseif(isset($_GET["aveh"]) and !empty($_GET["aveh"]))
         addVehicle();
         elseif(isset($_GET["mveh"]) and !empty($_GET["mveh"]))
         manageVehicle();
         elseif(isset($_GET["mche"]) and !empty($_GET["mche"]))
         maintenanceChecklist();
         elseif(isset($_GET["vres"]) and !empty($_GET["vres"]))
         vehicleReservationForm();
         elseif(isset($_GET["vsch"]) and !empty($_GET["vsch"]))
         vehicleSchedules();
         elseif(isset($_GET["macc"]) and !empty($_GET["macc"]))
         manageAccount();
         elseif(isset($_GET["mdep"]) and !empty($_GET["mdep"]))
         manageDepartment();
         elseif(isset($_GET["srep"]) and !empty($_GET["srep"]))
         summaryReport();
         elseif(isset($_GET["mrep"]) and !empty($_GET["mrep"]))
         maintenanceReport();
         else
         home();
      ?>
   </main>
   <script>
      let timer;

      // Dynamically set inactivity time based on PHP session or GET parameters
      const inactivityTime = <?php 
         // Default inactivity time
         if (isset($_GET["vsch"]) and !empty($_GET["vsch"])) {
            $defaultTime = 12000; // 3 seconds for vehicle schedules
         } elseif (isset($_GET["mveh"]) and !empty($_GET["mveh"])) {
            $defaultTime = 60000; // 3 seconds for manage vehicle
         } elseif (isset($_GET["aveh"]) and !empty($_GET["aveh"])) {
            $defaultTime = 60000; // 3 seconds for manage vehicle
         } elseif (isset($_GET["macc"]) and !empty($_GET["macc"])) {
            $defaultTime = 60000; // 3 seconds for manage account
         } elseif (isset($_GET["srep"]) and !empty($_GET["srep"])) {
            $defaultTime = 12000; // 3 seconds for summary report
         } elseif (isset($_GET["mrep"]) and !empty($_GET["mrep"])) {
            $defaultTime = 12000; // 3 seconds for maintenance report
         } elseif (isset($_GET["mche"]) and !empty($_GET["mche"])) {
            $defaultTime = 60000; // 3 seconds for maintenance checklist
         } elseif (isset($_GET["mdep"]) and !empty($_GET["mdep"])) {
            $defaultTime = 60000; // 3 seconds for manage department
         } elseif (isset($_GET["vres"]) and !empty($_GET["vres"])) {
            $defaultTime = 60000; // 3 seconds for vehicle reservation form
         } elseif (isset($_GET["rapp"]) and !empty($_GET["rapp"])) {
            $defaultTime = 3000; // 3 seconds for reservation approved
         } elseif (isset($_GET["creq"]) and !empty($_GET["creq"])) {
            $defaultTime = 3000; // 3 seconds for cancelled requests
         } elseif (isset($_GET["papp"]) and !empty($_GET["papp"])) {
            $defaultTime = 12000; // 3 seconds for pending approval
         } else {
            $defaultTime = 12000;
         }
         
         echo $defaultTime; // Output the inactivity time
      ?>;

      function resetTimer() {
         clearTimeout(timer);
         timer = setTimeout(() => {
               location.reload(); // Refreshes the page (triggers PHP session check)
         }, inactivityTime);
      }

      window.onload = resetTimer;
      document.onmousemove = resetTimer;
      document.onkeydown = resetTimer; // Use keydown instead of keypress
      document.onscroll = resetTimer;
      document.onclick = resetTimer;
      // On DOM load, check each field and toggle .has-content if it has a value
      document.addEventListener('DOMContentLoaded', function() {
      var fields = document.querySelectorAll('.input-container input, .input-container select');
      function updateField(el) {
         if (el.value.trim() !== '') {
            el.classList.add('has-content');
         } else {
            el.classList.remove('has-content');
         }
      }
      fields.forEach(function(field) {
         // Initial check on page load
         updateField(field);
         // On user input or change, update class
         field.addEventListener('input', function() { updateField(field); });
         field.addEventListener('change', function() { updateField(field); });
      });
      });
   </script>
   <script>
   function flashTitlePending(pageTitle, newTitle) {
      document.title = (document.title === pageTitle) ? newTitle : pageTitle;
   }
</script>

<?php
if($_SESSION['role'] != 'User')
   {
      $hasNew = false;
      $count = 0;

      $selectvrf = "SELECT * FROM vrftb 
         WHERE $status2 
         ORDER BY date_filed DESC, id DESC";

      $resultvrf = $conn->query($selectvrf);

      if ($resultvrf && $resultvrf->num_rows > 0) {
         $hasNew = true;
         $count = $resultvrf->num_rows;
      }
      ?>

      <?php if ($hasNew): ?>
      <script>
         const originalTitle = document.title;
         const newTitle = "(<?php echo $count; ?>) Pending Approval | VRF";

         setInterval(function () {
            flashTitlePending(originalTitle, newTitle);
         }, 1000);
      </script>
      <?php endif; 
   }
?>

</body>
</html>
<?php
function home()
{
if ($_SESSION['role'] == 'User') {
    include 'home_sec.php';
    echo '<div style="margin-top: 100vh;">'; // <-- space between home and calendar
    include 'calendar.php';
    echo '</div>';
} elseif (in_array($_SESSION['role'], ['Secretary', 'Admin', 'Director'])) {
    include 'home_sec.php';
} else {
    include 'home_sec.php';
    echo '<div style="margin-top: 10vh;">'; // <-- space here too
    include 'calendar.php';
    echo '</div>';
}

   }
   function vehicleReservationForm()
   {
      ?>
         <div class="vres">
            <form class="vehicle-reservation-form" action="GSO.php?vres=a" method="post" enctype="multipart/form-data">
               <img src="PNG/CSA_Logo.png" alt="">
               <span class="header">
                  <span id="csab">Colegio San Agustin-Biñan</span>
                  <span id="swe">Southwoods Ecocentrum, Brgy. San Francisco, 4024 Biñan City, Philippines</span>
                  <span id="vrf">VEHICLE RESERVATION FORM</span>
                  <span id="fid">
                     <span id="fid">Form ID:</span>
                     <?php
                        include 'config.php';
                        $selectvrfid = "SELECT * FROM vrftb ORDER BY id DESC LIMIT 1";
                        $resultvrfid = $conn->query($selectvrfid);
                        if ($resultvrfid->num_rows > 0) {
                           $rowvrfid = $resultvrfid->fetch_assoc();
                           if(substr($rowvrfid['id'], 0, 9) == date("Y-md"))
                           {
                              if (strlen((string) (substr($rowvrfid['id'], -2) + 1)) == 1)
                                 $vrfid =  '0'.substr($rowvrfid['id'], -2)+1;
                              else
                                 $vrfid =  substr($rowvrfid['id'], -2)+1;
                           }
                           else
                           {
                              $vrfid = "01";
                           }
                        }
                        else
                        {
                           $vrfid = "01";
                        }
                     ?>
                     <input name="vrfid" type="text" value="<?php echo date("Y-md").$vrfid ?>" readonly>
                  </span>
               </span>
               <div class="vrf-details">
                  <div class="vrf-details-column">
                     <?php
                     include_once 'config.php';

                     // Preload users (for Secretary logic)
                     $users = [];
                     $resUsers = $conn->query("SELECT fname, lname, department FROM usertb ORDER BY lname ASC");
                     while ($row = $resUsers->fetch_assoc()) {
                     $users[] = $row;
                     }

                     // Preload departments
                     $departments = [];
                     $resDepts = $conn->query("SELECT department FROM departmentstb ORDER BY department ASC");
                     while ($row = $resDepts->fetch_assoc()) {
                     $departments[] = $row['department'];
                     }
                     ?>

                     <!-- NAME -->
                     <div class="input-container">
                     <?php if ($_SESSION['role'] != 'Secretary') { ?>
                        <input name="vrfname"
                              value="<?php echo $_SESSION['fname'].' '.$_SESSION['lname']; ?>"
                              type="text"
                              id="name"
                              readonly>
                     <?php } else { ?>
                        <select name="vrfname" id="sec_name" required>
                           <option value="" disabled selected></option>
                           <?php foreach ($users as $u): ?>
                           <option value="<?php echo htmlspecialchars($u['fname'].' '.$u['lname']); ?>"
                                    data-dept="<?php echo htmlspecialchars($u['department']); ?>">
                              <?php echo htmlspecialchars($u['fname'].' '.$u['lname']); ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     <?php } ?>
                     <label for="name">NAME:</label>
                     </div>

                     <!-- DEPARTMENT -->
                     <div class="input-container">
                     <?php if ($_SESSION['role'] != 'Secretary') { ?>
                        <input name="vrfdepartment"
                              value="<?php echo $_SESSION['department']; ?>"
                              type="text"
                              id="department"
                              readonly>
                     <?php } else { ?>
                        <select name="vrfdepartment" id="sec_department" required>
                           <option value="" disabled selected></option>
                           <?php foreach ($departments as $dept): ?>
                           <option value="<?php echo htmlspecialchars($dept); ?>">
                              <?php echo htmlspecialchars($dept); ?>
                           </option>
                           <?php endforeach; ?>
                        </select>
                     <?php } ?>
                     <label for="department">DEPARTMENT:</label>
                     </div>

                     <?php if ($_SESSION['role'] == 'Secretary') { ?>
                     <script>
                     const nameSelect = document.getElementById("sec_name");
                     const deptSelect = document.getElementById("sec_department");

                     // Keep a copy of all name options (for filtering)
                     const allNameOptions = Array.from(nameSelect.options);

                     // If NAME picked first → auto-fill & lock department
                     nameSelect.addEventListener("change", () => {
                        const selected = nameSelect.options[nameSelect.selectedIndex];
                        const dept = selected.getAttribute("data-dept");

                        if (dept) {
                           deptSelect.value = dept; // lock department after auto-fill
                        }
                     });

                     // If DEPARTMENT picked first → filter names by department
                     deptSelect.addEventListener("change", () => {
                        const dept = deptSelect.value;

                        // Reset name options
                        nameSelect.innerHTML = "";
                        allNameOptions.forEach(opt => {
                           if (!opt.value) {
                           nameSelect.appendChild(opt.cloneNode(true));
                           return;
                           }
                           if (opt.getAttribute("data-dept") === dept) {
                           nameSelect.appendChild(opt.cloneNode(true));
                           }
                        });
                     });
                     </script>
                     <?php } ?>
                     <div class="input-container">
                        <input name="vrfactivity" type="text" id="activity" required>
                        <label for="activity">ACTIVITY:</label>
                     </div>
                  </div>
                  <div class="vrf-details-column">
                     <div class="input-container">
                        <input style="" name="vrfdate_filed" type="date" value="<?php echo date("Y-m-d"); ?>" id="dateFiled" required readonly>
                        <label for="dateFiled">DATE FILED:</label>
                     </div>
                     <div class="input-container">
                        <input name="vrfbudget_no" type="number" id="budgetNo" required>
                        <label for="budgetNo">BUDGET No.:</label>
                     </div>
                     <div class="input-container">
                        <select name="vrfpurpose" id="purpose" required>
                           <option value="" disabled selected></option>
                           <option value="School Related">School Related</option>
                           <option value="Official Business">Official Business</option>
                           <option value="Personal">Personal</option>
                        </select>
                        <label for="purpose">PURPOSE:</label>
                     </div>
                  </div>
                  
               </div>
               <span class="address">
                  <span>DESTINATION (PLEASE SPECIFY PLACE AND ADDRESS):</span>
                  <textarea name="vrfdestination" maxlength="255" type="text" id="destination" required></textarea>
               </span>
               <?php
                  // drivers
                  $drivers = [];
                  $q = mysqli_query($conn, "SELECT * FROM usertb WHERE role='driver'");
                  while ($r = mysqli_fetch_assoc($q)) {
                     $drivers[] = $r;
                  }

                  // vehicles
                  $vehicles = [];
                  $q = mysqli_query($conn, "SELECT *FROM carstb");
                  while ($r = mysqli_fetch_assoc($q)) {
                     $vehicles[] = $r;
                  }

                  // option strings
                  $driverOptions = "";
                  foreach ($drivers as $d) {
                     $driverOptions .= "<option value='{$d['employeeid']}'>"
                        . htmlspecialchars($d['fname'] .' '.$d['lname'] ) .
                     "</option>";
                  }

                  $vehicleOptions = "";
                  foreach ($vehicles as $v) {
                     $vehicleOptions .= "<option value='{$v['plate_number']}'>"
                        . htmlspecialchars($v['brand'].' '.$v['model']) .
                     "</option>";
                  }
                  // existing bookings (vehicle + driver conflicts)
                  $existingBookings = [];
                  $q = mysqli_query(
                     $conn,
                     "SELECT departure, `return`, vehicle, driver FROM vrf_detailstb"
                  );

                  while ($r = mysqli_fetch_assoc($q)) {
                     $existingBookings[] = [
                        'departure' => $r['departure'],
                        'return'    => $r['return'],
                        'vehicle'   => $r['vehicle'],
                        'driver'    => $r['driver']
                     ];
                  }

                  // Set departure minimum date based on role
                  if ($_SESSION['role'] == 'Secretary' OR $_SESSION['role'] == 'GSO_Director' OR $_SESSION['role'] == 'Admin') {
                     $date=date("Y-m-d\T06:00");
                  }
                  else
                  {
                     $date=date("Y-m-d\T06:00", strtotime("+7 days"));
                  }
               ?>
               <div class="details-container">

                  <input type="radio" id="rn1" name="mytabs" checked>
                  <label class="tab-name" for="rn1">1</label>

                  <div class="tab">
                     <div class="input-container-2">
                        <input name="vrfdeparture[0]" type="datetime-local" id="departure-1" min="<?php echo $date; ?>" required>
                        <label for="departure-1">DEPARTURE:</label>
                     </div>

                     <div class="input-container-2">
                        <input name="vrfreturn[0]" type="datetime-local" id="return-1" min="<?php echo $date; ?>" required>
                        <label for="return-1">RETURN:</label>
                     </div>

                     <div class="input-container-2">
                        <select name="vrfvehicle[0]" id="vehicle-1" required>
                           <option value="" disabled selected></option>
                           <?= $vehicleOptions ?>
                        </select>
                        <label for="vehicle-1">VEHICLE:</label>
                     </div>

                     <div class="input-container-2">
                        <select name="vrfdriver[0]" id="driver-1" required>
                           <option value="" disabled selected></option>
                           <?= $driverOptions ?>
                        </select>
                        <label for="driver-1">DRIVER:</label>
                     </div>
                  </div>

                  <label class="tab-name" id="tab-remover" for="remove-tab" style="display:none;">-</label>
                  <button type="button" id="remove-tab" class="remove-tab"></button>

                  <label class="tab-name" id="tab-adder" for="add-tab">+</label>
                  <button type="button" id="add-tab" class="add-tab"></button>
               </div>
               <script>
                  document.addEventListener("DOMContentLoaded", () => {
                     /* ================== GLOBALS ================== */
                     const container = document.querySelector(".details-container");
                     const addBtn = document.getElementById("add-tab");
                     const removeBtn = document.getElementById("remove-tab");
                     const removeLabel = document.getElementById("tab-remover");

                     const vehicleOptions = <?= json_encode($vehicleOptions) ?>;
                     const driverOptions  = <?= json_encode($driverOptions) ?>;
                     const dbBookings     = <?= json_encode($existingBookings) ?>;

                     let tabCount = container.querySelectorAll('input[type="radio"][name="mytabs"]').length;

                     /* ================== UTILS ================== */
                     function attachFloatingLabelLogic(scope = document) {
                        scope.querySelectorAll(".input-container-2 input, .input-container-2 select")
                        .forEach(el => {
                              if (el.value) el.classList.add("has-content");
                              const evt = el.tagName === "SELECT" ? "change" : "input";
                              el.addEventListener(evt, () => el.value ? el.classList.add("has-content") : el.classList.remove("has-content"));
                        });
                     }

                     function overlaps(aStart, aEnd, bStart, bEnd) {
                        return aStart < bEnd && bStart < aEnd;
                     }

                     function isCodingBanned(plate, date) {
                        if (!plate || !date) return false;
                        const m = plate.match(/(\d)(?!.*\d)/);
                        if (!m) return false;
                        const lastDigit = parseInt(m[1], 10);
                        const day = date.getDay(); // 0=Sun ... 6=Sat
                        const CODING = {1:[1,2],2:[3,4],3:[5,6],4:[7,8],5:[9,0]};
                        return CODING[day]?.includes(lastDigit) ?? false;
                     }

                     function updateRemoveVisibility() {
                        removeLabel.style.display = tabCount >= 2 ? "inline-block" : "none";
                     }

                     function getTabs() {
                        return document.querySelectorAll(".tab");
                     }

                     /* ================== CREATE ELEMENTS ================== */
                     function createSelect(name, label, index, options) {
                        const id = name.replace(/^vrf/, '');
                        return `
                              <div class="input-container-2">
                                 <select name="${name}[${index-1}]" id="${id}-${index}" required>
                                    <option value="" disabled selected></option>
                                    ${options}
                                 </select>
                                 <label for="${id}-${index}">${label}:</label>
                              </div>
                        `;
                     }

                     function createDateTime(name, label, index) {
                        const id = name.replace(/^vrf/, '');
                        return `
                              <div class="input-container-2">
                                 <input type="datetime-local" name="${name}[${index-1}]" id="${id}-${index}" required>
                                 <label for="${id}-${index}">${label}:</label>
                              </div>
                        `;
                     }

                     /* ================== RETURN = DEPARTURE LOGIC ================== */
                     function attachReturnMinLogic(tabIndex) {
                        const depEl = document.getElementById(`departure-${tabIndex}`);
                        const retEl = document.getElementById(`return-${tabIndex}`);
                        if (!depEl || !retEl) return;

                        depEl.addEventListener("change", () => {
                              if (depEl.value) {
                                 retEl.min = depEl.value;
                                 if (retEl.value && retEl.value < depEl.value) {
                                    retEl.value = depEl.value;
                                 }
                              }
                        });
                     }

                     function attachReturnMinToAllTabs() {
                        const tabs = getTabs();
                        tabs.forEach((tab, i) => attachReturnMinLogic(i + 1));
                     }

                     /* ================== FILTER VEHICLES & DRIVERS ================== */
                     function filter(index) {
                        const depEl = document.getElementById(`departure-${index}`);
                        const retEl = document.getElementById(`return-${index}`);
                        if (!depEl || !retEl || !depEl.value || !retEl.value) return;

                        const depDate = new Date(depEl.value);
                        const retDate = new Date(retEl.value);

                        const tabs = getTabs();
                        tabs.forEach((tab, i) => {
                              if (i === index - 1) return;
                              tab._dep = document.getElementById(`departure-${i+1}`)?.value ? new Date(document.getElementById(`departure-${i+1}`).value) : null;
                              tab._ret = document.getElementById(`return-${i+1}`)?.value ? new Date(document.getElementById(`return-${i+1}`).value) : null;
                              tab._vehicle = document.getElementById(`vehicle-${i+1}`)?.value || null;
                              tab._driver  = document.getElementById(`driver-${i+1}`)?.value || null;
                        });

                        // VEHICLES
                        const vSelect = document.getElementById(`vehicle-${index}`);
                        if (vSelect) {
                              [...vSelect.options].forEach(opt => {
                                 if (!opt.value) return;
                                 let reason = "";
                                 if (isCodingBanned(opt.value, depDate)) reason = "Coding restriction";

                                 tabs.forEach(tab => {
                                    if (!reason && tab._vehicle === opt.value && tab._dep && tab._ret) {
                                          if (overlaps(depDate, retDate, tab._dep, tab._ret)) reason = "Time conflict (current request)";
                                    }
                                 });

                                 dbBookings.forEach(b => {
                                    if (!reason && b.vehicle === opt.value) {
                                          const dbDep = new Date(b.departure);
                                          const dbRet = new Date(b.return);
                                          if (overlaps(depDate, retDate, dbDep, dbRet)) reason = "Time conflict (existing booking)";
                                    }
                                 });

                                 opt.disabled = !!reason;
                                 opt.title = reason;
                              });
                        }

                        // DRIVERS
                        const dSelect = document.getElementById(`driver-${index}`);
                        if (dSelect) {
                              [...dSelect.options].forEach(opt => {
                                 if (!opt.value) return;
                                 let reason = "";

                                 tabs.forEach(tab => {
                                    if (!reason && tab._driver === opt.value && tab._dep && tab._ret) {
                                          if (overlaps(depDate, retDate, tab._dep, tab._ret)) reason = "Time conflict (current request)";
                                    }
                                 });

                                 dbBookings.forEach(b => {
                                    if (!reason && b.driver === opt.value) {
                                          const dbDep = new Date(b.departure);
                                          const dbRet = new Date(b.return);
                                          if (overlaps(depDate, retDate, dbDep, dbRet)) reason = "Time conflict (existing booking)";
                                    }
                                 });

                                 opt.disabled = !!reason;
                                 opt.title = reason;
                              });
                        }
                     }

                     function refreshAllTabs() {
                        const count = getTabs().length;
                        for (let i = 1; i <= count; i++) filter(i);
                     }

                     function attachChangeEvents(index) {
                        ["departure", "return", "vehicle", "driver"].forEach(name => {
                              const el = document.getElementById(`${name}-${index}`);
                              if (el) el.addEventListener("change", refreshAllTabs);
                        });
                     }

                     /* ================== INITIAL SETUP ================== */
                     attachFloatingLabelLogic();
                     attachReturnMinToAllTabs();
                     refreshAllTabs();
                     attachChangeEvents(1);
                     updateRemoveVisibility();

                     /* ================== ADD TAB ================== */
                     addBtn.addEventListener("click", () => {
                        tabCount++;
                        const tabId = `rn${tabCount}`;

                        const template = `
                              <input type="radio" id="${tabId}" name="mytabs">
                              <label class="tab-name" for="${tabId}">${tabCount}</label>
                              <div class="tab">
                                 ${createDateTime("vrfdeparture", "DEPARTURE", tabCount)}
                                 ${createDateTime("vrfreturn", "RETURN", tabCount)}
                                 ${createSelect("vrfvehicle", "VEHICLE", tabCount, vehicleOptions)}
                                 ${createSelect("vrfdriver", "DRIVER", tabCount, driverOptions)}
                              </div>
                        `;

                        container.insertAdjacentHTML("beforeend", template);

                        const tab = container.querySelector(`label[for="${tabId}"]`)?.nextElementSibling;
                        if (tab) attachFloatingLabelLogic(tab);

                        attachReturnMinLogic(tabCount);
                        attachChangeEvents(tabCount);

                        document.getElementById(tabId).checked = true;
                        updateRemoveVisibility();

                        setTimeout(refreshAllTabs, 0);
                     });

                     /* ================== REMOVE TAB ================== */
                     removeBtn.addEventListener("click", () => {
                        if (tabCount <= 1) return;

                        const radio = document.getElementById(`rn${tabCount}`);
                        const label = container.querySelector(`label[for="rn${tabCount}"]`);
                        const tab = label?.nextElementSibling;

                        radio?.remove();
                        label?.remove();
                        tab?.remove();

                        tabCount--;
                        const prev = document.getElementById(`rn${tabCount}`);
                        if (prev) prev.checked = true;

                        updateRemoveVisibility();
                        setTimeout(refreshAllTabs, 0);
                     });
                  });
               </script>
               <span class="bottom-details">
                  <div class="vrf-details">
                     <div class="input-container">
                        <?php
                           if ($_SESSION['role'] == 'Secretary' OR $_SESSION['role'] == 'GSO_Director' OR $_SESSION['role'] == 'Admin') {
                              $date=date("Y-m-d\T06:00");
                           }
                           else
                           {
                              $date=date("Y-m-d\T06:00", strtotime("+7 days"));
                           }
                        ?>
                        
                        <div class="passenger-container">
                           <span>NAME OF PASSENGER/S</span>
                           <div id="passengerList">
                              <button type="button" id="attachmentButton" onclick="useAttachment()"><img class="attachment-img" src="PNG/File.png" for="fileInput" alt="">USE ATTACHMENT</button>
                              <button type="button" id="addButton" onclick="addPassenger()">&plus;</button>
                           </div>
                        </div>
                        <script>
                           function addPassenger() 
                           {
                              const passengerList = document.getElementById("passengerList");
                              const addButton = document.getElementById("addButton");
                              const attachmentButton = document.getElementById("attachmentButton");

                              // Hide "USE ATTACHMENT" button when "+" button is Seen
                              attachmentButton.style.display = "none";

                              // Get all current passenger entries
                              const inputContainers = passengerList.querySelectorAll(".passenger-entry");
                              
                              // Hide the remove button of the previous last passenger (if exists)
                              if (inputContainers.length > 0) 
                              {
                                 const lastContainer = inputContainers[inputContainers.length - 1];
                                 const lastRemoveButton = lastContainer.querySelector("button");
                                 if (lastRemoveButton) lastRemoveButton.style.display = "none";
                              }

                              // Determine new passenger number
                              const passengerCount = inputContainers.length + 1;

                              const inputContainer = document.createElement("div");
                              inputContainer.classList.add("input-container", "passenger-entry"); // Added passenger-entry class
                              inputContainer.style.position = "relative";

                              const input = document.createElement("input");
                              input.type = "text";
                              input.name = "vrfpassenger_name[]";
                              input.required = true;

                              // Add focus event to show placeholder
                              input.addEventListener("focus", function () 
                              {
                                 input.placeholder = "LNAME, Fname MI.";
                              });

                              // Add blur event to remove placeholder when unfocused
                              input.addEventListener("blur", function () 
                              {
                                 input.placeholder = "";
                              });

                              const label = document.createElement("label");
                              label.textContent = `PASSENGER#${passengerCount}`;

                              const removeButton = document.createElement("button");
                              removeButton.classList.add("remove-passenger");
                              removeButton.style = "position:absolute; transform:translateX(224px)";
                              removeButton.type = "button";
                              removeButton.textContent = "×";
                              removeButton.onclick = function () 
                              {
                                 inputContainer.remove();
                                 updateRemoveButtons();
                                 updatePassengerLabels();
                                 
                                 // Show attachment button if no passengers remain
                                 const remainingPassengers = passengerList.querySelectorAll(".passenger-entry");
                                 if (remainingPassengers.length === 0) {
                                    attachmentButton.style.display = "inline-block";
                                 }
                              };

                              inputContainer.appendChild(input);
                              inputContainer.appendChild(label);
                              inputContainer.appendChild(removeButton);

                              // Insert before the add button
                              passengerList.insertBefore(inputContainer, addButton);

                              updateRemoveButtons();
                              updatePassengerLabels();
                           }

                           function updateRemoveButtons() 
                           {
                              const inputContainers = document.querySelectorAll(".passenger-entry");

                              // Show the remove button only for the last input container
                              inputContainers.forEach((container, index) => 
                              {
                                 const removeButton = container.querySelector("button");
                                 if (removeButton) 
                                    removeButton.style.display = (index === inputContainers.length - 1) ? "inline-block" : "none";
                              });
                           }

                           function updatePassengerLabels() 
                           {
                              const inputContainers = document.querySelectorAll(".passenger-entry");
                              inputContainers.forEach((container, index) => 
                              {
                                 const label = container.querySelector("label");
                                 if (label) 
                                    label.textContent = `PASSENGER#${index + 1}`;
                              });
                           }

                           function useAttachment() 
                           {
                              const passengerList = document.getElementById("passengerList");
                              const addButton = document.getElementById("addButton");
                              const attachmentButton = document.getElementById("attachmentButton");

                              // Hide buttons
                              addButton.style.display = "none";
                              attachmentButton.style.display = "none";

                              // Create a container for attachment input
                              const inputContainer = document.createElement("div");
                              inputContainer.classList.add("input-container"); // No passenger-entry class here!
                              inputContainer.style= "transform: translateY(7px); display:flex; flex-direction:row;";

                              const attachmentInput = document.createElement("input");
                              attachmentInput.type = "file";
                              attachmentInput.name = "vrfpassenger_attachment";
                              attachmentInput.required = true;
                              attachmentInput.style = "width:190px; border-top-right-radius:0; border-bottom-right-radius:0;";

                              const numberInput = document.createElement("input");
                              numberInput.type = "number";
                              numberInput.name = "vrfpassenger_count";
                              numberInput.required = true;
                              numberInput.style = "text-align:center; width:50px; border-top-left-radius:0; border-bottom-left-radius:0;";

                              const label = document.createElement("label");
                              label.textContent = `PASSENGER COUNT`;

                              // Create a remove button
                              const removeButton = document.createElement("button");
                              removeButton.textContent = "×";
                              removeButton.style = "position:absolute; transform:translateY(30px)";
                              removeButton.onclick = function () 
                              {
                                 passengerList.removeChild(inputContainer);
                                 // Show buttons again
                                 addButton.style.display = "inline-block";
                                 attachmentButton.style.display = "inline-block";
                              };

                              // Append elements
                              inputContainer.appendChild(attachmentInput);
                              inputContainer.appendChild(numberInput);
                              inputContainer.appendChild(label);
                              inputContainer.appendChild(removeButton);
                              passengerList.appendChild(inputContainer);
                           }
                        </script>
                     </div>   
                     <span class="address">
                        <span style="text-align:center;">TRANSPORTATION COST</span>
                        <span style="transform: translateX(60px);">
                           <textarea style="cursor:not-allowed;" name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost" readonly></textarea>
                           <div class="input-container">
                              <a href="#"><input name="vrftotal_cost" type="number" id="totalCost"  style="padding-left:17px;cursor: not-allowed;" step="0.01" min="0" readonly></a>
                              <label for="total_cost">TOTAL COST</label>
                              <div>
                                 <label id="pesoSign">?</label>
                              </div>
                           </div>
                        </span>
                        <div class="subbtn-container">
                           <input type="file" name="vrfletter_attachment" class="attachment" id="fileInput">
                           <label for="fileInput" class="attachment-label" id="attachmentLabel" <?php echo !empty($_FILES["vrfletter_attachment"]["name"]) ? 'style="color: maroon;"' : ''; ?>><img class="attachment-img" src="PNG/File.png" for="fileInput" alt="">LETTER ATTACHMENT</label>
                           <button class="subbtn" type="submit" name="vrfsubbtn">Submit</button>
                        </div>
                        <script>
                           document.getElementById('fileInput').addEventListener('change', function() {
                              const label = document.getElementById('attachmentLabel');
                              if (this.files.length > 0) {
                                 label.style.color = 'maroon';
                              } else {
                                 label.style.color = '';
                              }
                           });
                        </script>
                     </span>
                  </div>
               </span>
            </form>
            
            <script>
            document.querySelector('.vehicle-reservation-form').addEventListener('submit', function (e) {
               // Re-enable any selected options that may have been disabled by filtering logic
               this.querySelectorAll('select').forEach(sel => {
                  const selected = sel.options[sel.selectedIndex];
                  if (selected && selected.disabled) selected.disabled = false;
               });

               // Also re-enable any selects themselves if you ever disable the <select> element
               this.querySelectorAll('select[disabled]').forEach(s => s.disabled = false);
            });
            </script>
            <?php
               include 'config.php';


               if (isset($_POST['vrfsubbtn'])) {
                  $id = htmlspecialchars($_POST['vrfid']);
                  $originalId = $id; // Store original ID to check if it was changed
                  
                  // Check if ID already exists, increment if needed (handles refresh/concurrent submissions)
                  $checkStmt = $conn->prepare("SELECT id FROM vrftb WHERE id = ?");
                  while (true) {
                     $checkStmt->bind_param("s", $id);
                     $checkStmt->execute();
                     $result = $checkStmt->get_result();
                     if ($result->num_rows == 0) break; // ID is unique, use it
                     
                     // ID exists, increment the last 2 digits
                     $lastTwo = (int)substr($id, -2) + 1;
                     $id = substr($id, 0, -2) . str_pad($lastTwo, 2, '0', STR_PAD_LEFT);
                  }
                  $checkStmt->close();
                  
                  // Show alert if ID was changed due to duplicate
                  if ($id !== $originalId) {
                     echo "<script>alert('Another request with ID " . htmlspecialchars($originalId) . " was found. Your request ID has been changed to " . htmlspecialchars($id) . ".');</script>";
                  }
                  
                  $name = htmlspecialchars($_POST['vrfname']);
                  $department = htmlspecialchars($_POST['vrfdepartment']);
                  $activity = htmlspecialchars($_POST['vrfactivity']);
                  $purpose = htmlspecialchars($_POST['vrfpurpose']);
                  $date_filed = htmlspecialchars($_POST['vrfdate_filed']);
                  $budget_no = htmlspecialchars($_POST['vrfbudget_no']);
                  $destination = htmlspecialchars($_POST['vrfdestination']);
                  $transportation_cost = htmlspecialchars($_POST['vrftransportation_cost']);
                  $passenger_count = isset($_POST['vrfpassenger_count']) ? htmlspecialchars($_POST['vrfpassenger_count']) : null;
                  $immediatehead_status = 'Pending'; // default
                  if (isset($_SESSION['role']) && $_SESSION['role'] === 'Immediate Head') {
                     $immediatehead_status = 'Approved';
                  }


                  // File upload directory
                  $targetDir = "uploads/";
                  if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

                  $allowedTypes = ['docx', 'pdf'];

                  // Handle letter attachment
                  $letterFileName = null;
                  if (!empty($_FILES["vrfletter_attachment"]["name"])) {
                     $letterFileName = basename($_FILES["vrfletter_attachment"]["name"]);
                     $letterFilePath = $targetDir . $letterFileName;
                     $letterFileType = strtolower(pathinfo($letterFilePath, PATHINFO_EXTENSION));
                     if (!in_array($letterFileType, $allowedTypes)) {
                           echo "<script>alert('Invalid file type for letter attachment. Only Word Documents or PDFs are allowed.');window.history.back();</script>";
                           exit;
                     }
                     move_uploaded_file($_FILES["vrfletter_attachment"]["tmp_name"], $letterFilePath);
                  }

                  // Handle passenger attachment
                  $passengerFileName = null;
                  if (!empty($_FILES["vrfpassenger_attachment"]["name"])) {
                     $passengerFileName = basename($_FILES["vrfpassenger_attachment"]["name"]);
                     $passengerFilePath = $targetDir . $passengerFileName;
                     $passengerFileType = strtolower(pathinfo($passengerFilePath, PATHINFO_EXTENSION));
                     if (!in_array($passengerFileType, $allowedTypes)) {
                           echo "<script>alert('Invalid file type for passenger attachment. Only Word Documents or PDFs are allowed.');window.history.back();</script>";
                           exit;
                     }
                     move_uploaded_file($_FILES["vrfpassenger_attachment"]["tmp_name"], $passengerFilePath);
                  }

                  try {
                     $conn->begin_transaction();

                     // ===== INSERT vrftb =====
                     $stmt = $conn->prepare("INSERT INTO vrftb 
                           (id, name, department, activity, purpose, date_filed, budget_no, destination, transportation_cost, passenger_count, letter_attachment, passenger_attachment, immediatehead_status) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                     $stmt->bind_param(
                           "sssssssssssss",
                           $id, $name, $department, $activity, $purpose, $date_filed, $budget_no, $destination, $transportation_cost, $passenger_count, $letterFileName, $passengerFileName, $immediatehead_status
                     );
                     if (!$stmt->execute()) throw new Exception("VRF insert failed: " . $stmt->error);

                     // ===== INSERT passengers =====
                     if (!empty($_POST['vrfpassenger_name'])) {
                           $passengerStmt = $conn->prepare("INSERT INTO passengerstb (vrfid, passenger_name) VALUES (?, ?)");
                           foreach ($_POST['vrfpassenger_name'] as $passenger_name) {
                              $passengerStmt->bind_param("ss", $id, $passenger_name);
                              if (!$passengerStmt->execute()) throw new Exception("Passenger insert failed: " . $passengerStmt->error);
                           }
                     }

                     // ===== COUNT & UPDATE passenger_count if missing =====
                     if (empty($_POST['vrfpassenger_count'])) {
                           $stmt2 = $conn->prepare("SELECT COUNT(*) AS passenger_count FROM passengerstb WHERE vrfid = ?");
                           $stmt2->bind_param("s", $id);
                           $stmt2->execute();
                           $result = $stmt2->get_result();
                           $row = $result->fetch_assoc();
                           $passenger_count = $row['passenger_count'];

                           $stmt2 = $conn->prepare("UPDATE vrftb SET passenger_count = ? WHERE id = ?");
                           $stmt2->bind_param("is", $passenger_count, $id);
                           if (!$stmt2->execute()) throw new Exception("Updating passenger count failed: " . $stmt2->error);
                     }
                     // ===== INSERT vrf_detailstb =====
                     if (!empty($_POST['vrfdeparture']) && is_array($_POST['vrfdeparture'])) {
                           $detail_stmt = $conn->prepare(
                              "INSERT INTO vrf_detailstb (vrf_id, departure, `return`, vehicle, driver) VALUES (?, ?, ?, ?, ?)"
                           );
                           if (!$detail_stmt) throw new Exception("Prepare for vrf_detailstb failed.");

                           foreach ($_POST['vrfdeparture'] as $idx => $dep_raw) {
                              $ret_raw = $_POST['vrfreturn'][$idx]  ?? null;
                              $vehicle  = $_POST['vrfvehicle'][$idx] ?? null;
                              $driver   = $_POST['vrfdriver'][$idx]  ?? null;

                              if (!$dep_raw || !$ret_raw || !$vehicle || !$driver) continue;

                              // convert datetime-local to MySQL DATETIME
                              $dep = (new DateTime($dep_raw))->format('Y-m-d H:i:s');
                              $ret = (new DateTime($ret_raw))->format('Y-m-d H:i:s');

                              $detail_stmt->bind_param("sssss", $id, $dep, $ret, $vehicle, $driver);
                              if (!$detail_stmt->execute()) throw new Exception("Detail insert failed for tab {$idx}: " . $detail_stmt->error);
                           }
                           $detail_stmt->close();
                     }

                     $conn->commit();
                     echo "<script>alert('Reservation submitted successfully!');</script>";

                  } catch (Exception $e) {
                     if (isset($conn)) $conn->rollback();
                     echo "<script>alert('Error submitting reservation: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
                     exit;
                  }
               }
            ?>

         </div>
      <?php
   }
   function vehicleSchedules()
   {
      include("calendar.php");
   }
   function addVehicle()
   {
      include 'car_add.php';
   }
   function manageVehicle()
   {
      ?>
         <div class="gawanimatley" style="all:unset;">
            <?php
               include 'car_info.php';
            ?>
         </div>
      <?php
   }
   function pendingApproval()
   {
      ?>
         <input class="search" type="text" id="search" data-page="pending" placeholder="Search reservation">
         <div class="maintitle">
            <h1>Pending Approval</h1>
            <?php
               if($_SESSION['role']=='Secretary'||$_SESSION['role']=='Admin')
               {
                  $status2 = "(immediatehead_status='Approved' AND gsoassistant_status='Pending') OR (immediatehead_status='Approved' AND gsoassistant_status='Seen')";
                  $status="gsoassistant_status";
               }
               elseif($_SESSION['role']=='Immediate Head')
               {
                  $status2 = "department='". $_SESSION['department'] ."' AND ((immediatehead_status='Pending') OR (immediatehead_status='Seen'))";
                  $status="immediatehead_status";
               }
               elseif($_SESSION['role']=='Director')
               {
                  $status2 = "(accounting_status='Approved' AND gsodirector_status='Pending') OR (accounting_status='Approved' AND gsodirector_status='Seen')";
                  $status="gsodirector_status";
               }
               else if($_SESSION['role']=='Accountant')
               {
                  $status2 = "(gsoassistant_status='Approved' AND accounting_status='Pending') OR (gsoassistant_status='Approved' AND accounting_status='Seen')";

                  $status="accounting_status";
               }
               include 'config.php';
               $selectvrf = "SELECT * FROM vrftb WHERE updated_at >= DATE_SUB(NOW(), INTERVAL 1 DAY) AND $status2 ORDER BY date_filed DESC, id DESC";
               $resultvrf = $conn->query($selectvrf);
               if ($resultvrf->num_rows > 0) {
                  $rowvrf = $resultvrf->fetch_assoc();
                  echo '<p>New</p>';
               }
            ?>
         </div>
         <div class="whitespace"></div>
         <div class="whitespace2"></div>
         <div id="pending-results">
         <?php
            include 'config.php';
            $selectvrf = "SELECT * FROM vrftb WHERE $status2 ORDER BY date_filed DESC, id DESC";
            $resultvrf = $conn->query($selectvrf);
            if ($resultvrf->num_rows > 0) {
               while($rowvrf = $resultvrf->fetch_assoc()) {
                  ?>
                     <a href="GSO.php?papp=a&vrfid=<?php echo $rowvrf['id']; ?>#vrespopup" class="link" style="text-decoration:none;">
                  <?php
                     if (isset($_GET['vrfid'])) {
                        include 'config.php';
                        $selectstatus = "SELECT $status FROM vrftb WHERE id = ?";
                        $stmt = $conn->prepare($selectstatus);
                        $stmt->bind_param("s", $_GET['vrfid']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $rowvrf2 = $result->fetch_assoc();
                        $stmt->close();
                        if ($rowvrf2[$status] == 'Pending') {
                           $updatevrf = "UPDATE vrftb SET $status='Seen', updated_at = updated_at WHERE id = ?";
                           $stmt = $conn->prepare($updatevrf);
                           if ($stmt) {
                              $stmt->bind_param("s", $_GET['vrfid']);
                              $stmt->execute();
                              $stmt->close();
                           } 
                        }
                     }
                     if($rowvrf[$status] != "Seen")
                     { 
                        ?> <div class="info-box"> <?php 
                     }
                     else
                     { 
                        ?> <div class="info-box" style="background-color:#eeeeee;"> <?php 
                     }
                        ?>
                           <div class="pending">
                              <?php
                                 if($rowvrf[$status] == "Pending")
                                 {
                                    echo '<div class="circle"></div>';
                                 }
                              ?>
                              <span class="time">
                                 <?php
                                    $updated_at = strtotime($rowvrf['updated_at']);
                                    $now = time();
                                    $interval = $now - $updated_at;
                                    
                                    if ($interval < 60) {
                                        echo 'Just now';
                                    } elseif ($interval < 3600) {
                                        $minutes = floor($interval / 60);
                                        echo $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
                                    } elseif ($interval < 86400) {
                                        $hours = floor($interval / 3600);
                                        echo $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
                                    } else {
                                        $days = floor($interval / 86400);
                                        echo $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
                                    }
                                 ?>
                              </span>
                           </div>
                           <div class="info-heading">
                              <?php
                                 $name = $rowvrf['name'];
                                 $selectppicture = $conn->prepare("SELECT * FROM usertb WHERE CONCAT(fname, ' ', lname) = ?");
                                 $selectppicture->bind_param("s", $name);
                                 $selectppicture->execute();
                                 $resultppicture = $selectppicture->get_result();
                                 

                                 if ($resultppicture->num_rows > 0) {
                                    $rowppicture = $resultppicture->fetch_assoc();
                                    if ($rowppicture['ppicture'] != null) {
                                       $profilePicture = $rowppicture['ppicture'];
                                    } else {
                                       $profilePicture = "default.png";
                                    } 
                                 } else {
                                    $profilePicture = "default.png";
                                 }
                              ?>
                              <img src="uploads/<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile">
                              <span class="info-heading-text">
                                 <span class="name"><?php echo $rowvrf['name'] ?></span>
                                 <span class="department"><?php echo $rowvrf['department'] ?></span>
                                 <span class="date"><?php echo "Date: ".date("m/d/Y", strtotime($rowvrf['date_filed']));?></span>
                              </span>
                           </div>
                           <div class="info-details">
                              <div>
                                 <div><div class="title">Activity</div></div>
                                 <div><div class="title">Purpose:</div></div>
                                 <div><div class="title">Budget No.:</div></div>
                              </div>
                              <div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['activity']; ?></div></div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['purpose']; ?></div></div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['budget_no']; ?></div></div>
                              </div>
                              <div>
                                 <div><div class="title">Destination:</div></div>
                                 <div><div class="title">Passenger count:</div></div>
                                 <div><div class="title">Vehicle to be used:</div></div>
                              </div>
                              <div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['destination']; ?></div></div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['passenger_count'] ?></div></div>
                                 <div><div class="dikoalam">
                                    <?php 
                                       $vrfid = $rowvrf['id']; 
                                       $selectdetails = "SELECT * FROM vrf_detailstb WHERE vrf_id = '$vrfid'";
                                       $resultdetails = $conn->query($selectdetails);
                                       if ($resultdetails->num_rows > 0) {
                                          $vehicles = [];
                                          while($rowdetails = $resultdetails->fetch_assoc()) {
                                             $plate_number = $rowdetails['vehicle']; 
                                             $selectvehicle = "SELECT * FROM carstb WHERE plate_number = '$plate_number'";
                                             $resultvehicle = $conn->query($selectvehicle);
                                             if ($resultvehicle->num_rows > 0) {
                                                while($rowvehicle = $resultvehicle->fetch_assoc()) {
                                                   $vehicles[] = $rowvehicle['brand']." ".$rowvehicle['model'];
                                                }
                                             } else {
                                                $vehicles[] = $rowdetails['vehicle'];
                                             }
                                          }
                                          echo implode(", ", $vehicles);
                                       } else {
                                          echo "N/A";
                                       }
                                    ?>
                                 </div></div>
                              </div>
                           </div>
                        </div>
                     </a>
                     <div id="vrespopup">
                        <div class="vres">
                           <form class="vehicle-reservation-form" method="post" enctype="multipart/form-data">
                              <a href="GSO.php?papp=a" class="closepopup">&times</a>
                              <img src="PNG/CSA_Logo.png" alt="">
                              <span class="header">
                                 <span id="csab">Colegio San Agustin-Biñan</span>
                                 <span id="swe">Southwoods Ecocentrum, Brgy. San Francisco, 4024 Biñan City, Philippines</span>
                                 <span id="vrf">VEHICLE RESERVATION FORM</span>
                                 <span id="fid">
                                    <span id="fid">Form ID:
                                       <?php
                                          include 'config.php';
                                          $selectvrfid = "SELECT * FROM vrftb WHERE id = '".$_GET['vrfid']."'";
                                          $resultvrfid = $conn->query($selectvrfid);
                                          $resultvrfid->num_rows > 0;
                                          $rowvrfid = $resultvrfid->fetch_assoc();
                                          echo $rowvrfid['id'];
                                          $letter=$rowvrfid['letter_attachment']
                                       ?>
                                    </span>
                                 </span>
                              </span>
                              <div class="vrf-details">
                                 <div class="vrf-details-column">
                                    <div class="input-container">
                                       <input name="vrfname" value="<?php echo $rowvrfid['name'] ?>" type="text" id="name" required readonly>
                                       <label for="name">NAME:</label>
                                    </div>
                                    <div class="input-container">
                                       <input name="vrfdepartment" value="<?php echo $rowvrfid['department'] ?>" type="text"  id="department" required readonly>
                                       <label for="department">DEPARTMENT:</label>
                                    </div>
                                    <div class="input-container">
                                       <input name="vrfactivity" value="<?php echo $rowvrfid['activity'] ?>" type="text" id="activity" required readonly>
                                       <label for="activity">ACTIVITY:</label>
                                    </div> 
                                 </div>
                                 <div class="vrf-details-column">
                                    <div class="input-container">
                                       <input name="vrfdate_filed" type="date" value="<?php echo $rowvrfid['date_filed']; ?>" id="dateFiled" required readonly>
                                       <label for="dateFiled">DATE FILED:</label>
                                    </div>
                                    <div class="input-container">
                                       <input name="vrfbudget_no" type="number" id="budgetNo" required readonly value="<?php echo $rowvrfid['budget_no']; ?>">
                                       <label for="budgetNo">BUDGET No.:</label>
                                    </div>
                                    <div class="input-container">
                                       <input type="text" name="vrfpurpose" value="<?php echo $rowvrfid['purpose'] ?>" id="purpose" required readonly>
                                       <label for="purpose">PURPOSE:</label>
                                    </div>
                                 </div>
                              </div>
                              <span class="address">
                                 <span>DESTINATION (PLEASE SPECIFY PLACE AND ADDRESS):</span>
                                 <textarea name="vrfdestination" maxlength="255" type="text"  id="destination" required readonly><?php echo $rowvrfid['destination'] ?></textarea>
                              </span>
                              <?php
                                 include 'config.php';
                                 $selectvrfdetails = "SELECT * FROM vrf_detailstb WHERE vrf_id = '".$_GET['vrfid']."'";
                                 $resultvrfdetails = $conn->query($selectvrfdetails);
                                 
                                 // Get all drivers and vehicles
                                 $drivers = [];
                                 $driverOptions = "";
                                 $q = mysqli_query($conn, "SELECT * FROM usertb WHERE role='driver'");
                                 while ($r = mysqli_fetch_assoc($q)) {
                                    $drivers[] = $r;
                                    $driverOptions .= "<option value='{$r['employeeid']}'>" . htmlspecialchars($r['fname'] .' '.$r['lname'] ) . "</option>";
                                 }

                                 $vehicles = [];
                                 $vehicleOptions = "";
                                 $q = mysqli_query($conn, "SELECT * FROM carstb");
                                 while ($r = mysqli_fetch_assoc($q)) {
                                    $vehicles[] = $r;
                                    $vehicleOptions .= "<option value='{$r['plate_number']}'>" . htmlspecialchars($r['brand'].' '.$r['model']) . "</option>";
                                 }
                                 
                                 // Get existing bookings for conflict detection
                                 $existingBookings = [];
                                 $q = mysqli_query(
                                    $conn,
                                    "SELECT departure, `return`, vehicle, driver FROM vrf_detailstb WHERE vrf_id != '".$_GET['vrfid']."'"
                                 );
                                 while ($r = mysqli_fetch_assoc($q)) {
                                    $existingBookings[] = [
                                       'departure' => $r['departure'],
                                       'return'    => $r['return'],
                                       'vehicle'   => $r['vehicle'],
                                       'driver'    => $r['driver']
                                    ];
                                 }
                              ?>
                              <div class="details-container" 
                                   data-vehicle-options="<?php echo htmlspecialchars($vehicleOptions); ?>"
                                   data-driver-options="<?php echo htmlspecialchars($driverOptions); ?>"
                                   data-existing-bookings="<?php echo htmlspecialchars(json_encode($existingBookings)); ?>">
                                 <?php
                                    if ($resultvrfdetails->num_rows > 0) {
                                       $tab_number = 1;
                                       while($rowvrfdetails = $resultvrfdetails->fetch_assoc()) {
                                          ?>
                                             <input type="radio" id="rn<?php echo $tab_number ?>" name="mytabs" <?php echo ($tab_number == 1) ? 'checked' : ''; ?>>
                                             <label class="tab-name" for="rn<?php echo $tab_number ?>"><?php echo $tab_number ?></label>
                                             <div class="tab">
                                                <div class="input-container-2">
                                                   <input name="vrfdeparture[<?php echo $tab_number - 1 ?>]" type="datetime-local" id="departure-<?php echo $tab_number ?>" value="<?php echo $rowvrfdetails['departure']; ?>" required <?php echo (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])) ? '' : 'readonly'; ?>>
                                                   <label for="departure-<?php echo $tab_number ?>">DEPARTURE:</label>
                                                </div>

                                                <div class="input-container-2">
                                                   <input name="vrfreturn[<?php echo $tab_number - 1 ?>]" type="datetime-local" id="return-<?php echo $tab_number ?>" value="<?php echo $rowvrfdetails['return']; ?>" required <?php echo (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])) ? '' : 'readonly'; ?>>
                                                   <label for="return-<?php echo $tab_number ?>">RETURN:</label>
                                                </div>

                                                <div class="input-container-2">
                                                   <?php if (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])): ?>
                                                      <select name="vrfvehicle[<?php echo $tab_number - 1 ?>]" id="vehicle-<?php echo $tab_number ?>" required>
                                                         <option value="" disabled selected></option>
                                                         <?php foreach ($vehicles as $v): ?>
                                                            <option value="<?php echo $v['plate_number']; ?>" <?php echo ($rowvrfdetails['vehicle'] === $v['plate_number']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($v['brand'] . " " . $v['model']); ?></option>
                                                         <?php endforeach; ?>
                                                      </select>
                                                      <label for="vehicle-<?php echo $tab_number ?>">VEHICLE:</label>
                                                   <?php else: ?>
                                                      <?php
                                                         $selectvehicle = "SELECT brand, model FROM carstb WHERE plate_number = ?";
                                                         $stmtVehicle = $conn->prepare($selectvehicle);
                                                         $stmtVehicle->bind_param("s", $rowvrfdetails['vehicle']);
                                                         $stmtVehicle->execute();
                                                         $resultVehicle = $stmtVehicle->get_result();
                                                         $vehicleDisplay = $rowvrfdetails['vehicle'];
                                                         if ($resultVehicle->num_rows > 0) {
                                                            $rowVehicle = $resultVehicle->fetch_assoc();
                                                            $vehicleDisplay = $rowVehicle['brand'] . " " . $rowVehicle['model'];
                                                         }
                                                         $stmtVehicle->close();
                                                      ?>
                                                      <input name="vrfvehicle[<?php echo $tab_number - 1 ?>]" type="text" id="vehicle-<?php echo $tab_number ?>" value="<?php echo htmlspecialchars($vehicleDisplay); ?>" required readonly>
                                                      <input type="hidden" name="vrfvehicle_actual[<?php echo $tab_number - 1 ?>]" value="<?php echo htmlspecialchars($rowvrfdetails['vehicle']); ?>">
                                                      <label for="vehicle-<?php echo $tab_number ?>">VEHICLE:</label>
                                                   <?php endif; ?>
                                                </div>

                                                <div class="input-container-2">
                                                   <?php if (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])): ?>
                                                      <select name="vrfdriver[<?php echo $tab_number - 1 ?>]" id="driver-<?php echo $tab_number ?>" required>
                                                         <option value="" disabled selected></option>
                                                         <?php foreach ($drivers as $d): ?>
                                                            <option value="<?php echo htmlspecialchars($d['employeeid']); ?>" <?php echo ($rowvrfdetails['driver'] === $d['employeeid']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($d['fname'] . " " . $d['lname']); ?></option>
                                                         <?php endforeach; ?>
                                                      </select>
                                                      <label for="driver-<?php echo $tab_number ?>">DRIVER:</label>
                                                   <?php else: ?>
                                                      <?php
                                                         $selectdriver = "SELECT fname, lname FROM usertb WHERE employeeid = ?";
                                                         $stmtDriver = $conn->prepare($selectdriver);
                                                         $stmtDriver->bind_param("s", $rowvrfdetails['driver']);
                                                         $stmtDriver->execute();
                                                         $resultDriver = $stmtDriver->get_result();
                                                         $driverDisplay = $rowvrfdetails['driver'];
                                                         if ($resultDriver->num_rows > 0) {
                                                            $rowDriver = $resultDriver->fetch_assoc();
                                                            $driverDisplay = $rowDriver['fname'] . " " . $rowDriver['lname'];
                                                         }
                                                         $stmtDriver->close();
                                                      ?>
                                                      <input name="vrfdriver[<?php echo $tab_number - 1 ?>]" type="text" id="driver-<?php echo $tab_number ?>" value="<?php echo htmlspecialchars($driverDisplay); ?>" required readonly>
                                                      <input type="hidden" name="vrfdriver_actual[<?php echo $tab_number - 1 ?>]" value="<?php echo htmlspecialchars($rowvrfdetails['driver']); ?>">
                                                      <label for="driver-<?php echo $tab_number ?>">DRIVER:</label>
                                                   <?php endif; ?>
                                                </div>
                                             </div>
                                          <?php
                                          $tab_number++;
                                       }
                                    }
                                 ?>
                                 <?php if (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])): ?>
                                    <label class="tab-name" id="tab-remover-<?php echo $_GET['vrfid']; ?>" for="remove-tab-<?php echo $_GET['vrfid']; ?>" style="display:none;">-</label>
                                    <button type="button" id="remove-tab-<?php echo $_GET['vrfid']; ?>" class="remove-tab"></button>

                                    <label class="tab-name" id="tab-adder-<?php echo $_GET['vrfid']; ?>" for="add-tab-<?php echo $_GET['vrfid']; ?>">+</label>
                                    <button type="button" id="add-tab-<?php echo $_GET['vrfid']; ?>" class="add-tab"></button>
                                 <?php endif; ?>
                              </div>
                              <?php if (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])): ?>
                                 <!-- Empty script tag - handler moved outside loop -->
                              <?php endif; ?>
                              <div class="vrf-details">
                                 <div class="input-container">
                                    <div class="passenger-container">
                                       <span>NAME OF PASSENGER/S</span>
                                       <div id="passengerList">
                                          <?php
                                             if ($rowvrfid['passenger_attachment'] == null) {
                                                $selectpassenger = "SELECT * FROM passengerstb WHERE vrfid = '".$_GET['vrfid']."'";
                                                $resultpassenger = $conn->query($selectpassenger);
                                                if ($resultpassenger->num_rows > 0) {
                                                   $passenger_number = 1;
                                                   while($rowpassenger = $resultpassenger->fetch_assoc()) {
                                                      ?>
                                                         <div class="input-container" style="position:relative;">
                                                            <input type="text" name="vrfpassenger_name[]" value="<?php echo $rowpassenger['passenger_name']; ?>" required readonly>
                                                            <label for="passengerName">PASSENGER#<?php echo $passenger_number ?></label>
                                                            <button class="remove-passenger" type="button" style="position:absolute; transform:translateX(224px);display:none;">&times</button>
                                                         </div>
                                                      <?php
                                                      $passenger_number++;
                                                   }
                                                }
                                             } else {
                                                ?>
                                                   <div class="input-container" style="transform: translateY(7px); display: flex; flex-direction: row;">
                                                      <a href="uploads/<?php echo $rowvrfid['passenger_attachment'] ?>" target="_blank"><input type="text" value="<?php echo $rowvrfid['passenger_attachment'] ?>" name="vrfpassenger_attachment" required style="cursor:pointer; border-color:black; width: 190px; border-top-right-radius: 0; border-bottom-right-radius: 0;"></a>
                                                      <input readonly type="number" value="<?php echo $rowvrfid['passenger_count'] ?>" name="vrfpassenger_count" required style=" border-color:black; text-align: center; width: 50px; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                      <label for="passengerCount">PASSENGER NAMES</label>
                                                   </div>

                                                <?php
                                             }
                                          ?>
                                       </div>
                                    </div>
                                    
                                 </div>   
                                 <span class="address">
                                    <span style="text-align:center">TRANSPORTATION COST</span>
                                    <?php
                                       if($_SESSION['role'] == "Accountant")
                                       {
                                          ?>
                                             <span style="transform: translateX(60px);">
                                             <textarea name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost"></textarea>
                                             <div class="input-container">
                                                <input name="vrftotal_cost" type="number" id="totalCost"  style="padding-left:1.5vw;" step="0.01" min="0" required>
                                                <label for="total_cost" style="margin-left:1vw">TOTAL COST</label>
                                                <div>
                                                   <label id="pesoSign">₱</label>
                                                </div>
                                             </div>
                                             </span>
                                          <?php
                                       }
                                       else
                                       {
                                          ?>
                                             <span style="transform: translateX(60px);">
                                             <textarea name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost" readonly><?php echo $rowvrfid['transportation_cost'] ?></textarea>
                                             <div class="input-container">   
                                                <?php
                                                   if($rowvrfid['total_cost'] == 0.00)
                                                   {
                                                      ?>
                                                         <a href="#vrespopup">      
                                                      <?php
                                                   }
                                                ?>
                                                   <input name="vrftotal_cost" type="number" id="totalCost" value="<?php if($rowvrfid['total_cost'] == 0.00) {echo"";} else {echo $rowvrf['total_cost'];}?>" style="padding-left:1.5vw;" step="0.01" min="0" readonly>
                                                <?php
                                                   if($rowvrfid['total_cost'] == 0.00)
                                                   {
                                                      ?>
                                                         </a>      
                                                      <?php
                                                   }
                                                ?>
                                                <label for="total_cost" style="margin-left:1vw">TOTAL COST</label>
                                                <div>
                                                   <label <?php if($rowvrfid['total_cost'] != 0.00)echo "style=\"visibility:visible;\"" ?> id="pesoSign">₱</label>
                                                </div>
                                             </div>
                                             </span>
                                          <?php
                                       }
                                    ?>
                                    <script>
                                       const input = document.getElementById("totalCost");
                                       const pesoSign = document.getElementById("pesoSign");

                                       function updatePesoVisibility() {
                                          if (document.activeElement === input || input.checkValidity()) {
                                             pesoSign.style.visibility = "visible";
                                          } else {
                                             pesoSign.style.visibility = "hidden";
                                          }
                                       }

                                       input.addEventListener("input", function () {
                                          const value = this.value;
                                          if (value.includes('.')) {
                                             const [whole, decimal = ''] = value.split('.');
                                             if (decimal.length > 2) {
                                                this.value = `${whole}.${decimal.slice(0, 2)}`;
                                             }
                                          }
                                          updatePesoVisibility();
                                       });

                                       input.addEventListener("focus", updatePesoVisibility);
                                       input.addEventListener("blur", updatePesoVisibility);
                                    </script>
                                    <div class="subbtn-container">
                                       <input type="file" name="vrfletter_attachment" class="attachment" id="fileInput">
                                       <?php
                                       
                                          if($letter != "")
                                          {
                                             ?>
                                                <a href="uploads/<?php echo $rowvrfid['letter_attachment']; ?>" target="_blank"><label  class="attachment-label"><img class="attachment-img" src="PNG/File.png" for="fileInput" alt="">LETTER ATTACHMENT</label></a>
                                             <?php
                                          }
                                       ?>
                                       <button class="rejbtn" type="submit" name="vrfrejbtn">Reject</button>
                                       <button class="appbtn" type="submit" name="vrfappbtn">Approve</button>
                                    </div>
                                 </span>
                              </div>
                           </form>
                           <script>
                              // Form submission validation
                              document.querySelector('.vehicle-reservation-form').addEventListener('submit', (e) => {
                                 if (e.target.querySelector('[name="vrfappbtn"]') === document.activeElement) {
                                    // Re-enable disabled options so they get submitted
                                    document.querySelectorAll('select').forEach(select => {
                                       Array.from(select.options).forEach(opt => {
                                          if (opt.disabled && opt.selected) {
                                             opt.disabled = false;
                                          }
                                       });
                                    });
                                    
                                    const container = document.querySelector('.details-container');
                                    const tabs = container.querySelectorAll('.tab');
                                    
                                    let hasIncompleteTab = false;
                                    tabs.forEach((tab, idx) => {
                                       const dep = tab.querySelector('[name*="vrfdeparture"]')?.value || '';
                                       const ret = tab.querySelector('[name*="vrfreturn"]')?.value || '';
                                       const vehicle = tab.querySelector('[name*="vrfvehicle"]')?.value || '';
                                       const driver = tab.querySelector('[name*="vrfdriver"]')?.value || '';
                                       
                                       // If any field has value, all must have values
                                       if ((dep || ret || vehicle || driver) && !(dep && ret && vehicle && driver)) {
                                          hasIncompleteTab = true;
                                       }
                                    });
                                    
                                    if (hasIncompleteTab) {
                                       e.preventDefault();
                                       alert('Please complete all fields in each tab before approving.');
                                    }
                                 }
                              });
                           </script>
                           <script>
                              // On DOM load, check each field and toggle .has-content if it has a value
                              document.addEventListener('DOMContentLoaded', function() {
                              var fields = document.querySelectorAll('.input-container input, .input-container select, .input-container-2 input, .input-container-2 select');
                              function updateField(el) {
                                 if (el.value.trim() !== '') {
                                    el.classList.add('has-content');
                                 } else {
                                    el.classList.remove('has-content');
                                 }
                              }
                              fields.forEach(function(field) {
                                 // Initial check on page load
                                 updateField(field);
                                 // On user input or change, update class
                                 field.addEventListener('input', function() { updateField(field); });
                                 field.addEventListener('change', function() { updateField(field); });
                              });
                              });
                           </script>
                        </div>
                     </div>
                  <?php
               }
            }
            ?>
            </div>
            <?php
               if ($_SERVER["REQUEST_METHOD"] == "POST") {
                  if (isset($_POST['vrfappbtn'])) {
                     $id = htmlspecialchars($_GET['vrfid']);
                     try {
                        $conn->begin_transaction();
                        // Handle GSO Secretary and Immediate Head updates to vrf_detailstb
                        if (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])) {
                           include 'config.php';
                           
                           // First, delete existing records for this VRF
                           $deleteStmt = $conn->prepare("DELETE FROM vrf_detailstb WHERE vrf_id = ?");
                           $deleteStmt->bind_param("s", $id);
                           $deleteStmt->execute();
                           $deleteStmt->close();
                           if (!empty($_POST['vrfdeparture']) && is_array($_POST['vrfdeparture'])) {
                              $detail_stmt = $conn->prepare(
                                 "INSERT INTO vrf_detailstb (vrf_id, departure, `return`, vehicle, driver) VALUES (?, ?, ?, ?, ?)"
                              );
                              if (!$detail_stmt) throw new Exception("Prepare for vrf_detailstb failed.");

                              foreach ($_POST['vrfdeparture'] as $idx => $dep_raw) {
                                 $ret_raw = $_POST['vrfreturn'][$idx]  ?? null;
                                 $vehicle  = $_POST['vrfvehicle'][$idx] ?? null;
                                 $driver   = $_POST['vrfdriver'][$idx]  ?? null;

                                 if (!$dep_raw || !$ret_raw || !$vehicle || !$driver) continue;

                                 // convert datetime-local to MySQL DATETIME
                                 $dep = (new DateTime($dep_raw))->format('Y-m-d H:i:s');
                                 $ret = (new DateTime($ret_raw))->format('Y-m-d H:i:s');

                                 $detail_stmt->bind_param("sssss", $id, $dep, $ret, $vehicle, $driver);
                                 if (!$detail_stmt->execute()) throw new Exception("Detail insert failed for tab {$idx}: " . $detail_stmt->error);
                              }
                              $detail_stmt->close();
                           }      
                        }
                        include 'config.php';
                        if($_SESSION['role']=='Immediate Head' OR $_SESSION['role']=='Director')
                        {
                           $updateStatus = "UPDATE vrftb SET $status='Approved' WHERE id = ?";
                        }
                        elseif($_SESSION['role']=='Secretary')
                        {
                           $updateStatus = "UPDATE vrftb SET $status='Approved' WHERE id = ?";
                        }
                        elseif($_SESSION['role']=='Accountant')
                        {
                           $total_cost = $_POST['vrftotal_cost'];
                           $transportation_cost = $_POST['vrftransportation_cost'];
                           $updateStatus = "UPDATE vrftb SET transportation_cost='$transportation_cost', total_cost='$total_cost', $status='Approved' WHERE id = ?";
                        }
                        
                        $stmt = $conn->prepare($updateStatus);
                        if (!$stmt) throw new Exception("Prepare update statement failed.");
                        $stmt->bind_param("s", $id);
                        if (!$stmt->execute()) throw new Exception("Update failed: " . $stmt->error);
                        $stmt->close();
                        
                        $conn->commit();
                        echo "<script>
                                 alert('Reservation approved');
                                 window.history.back();
                              </script>";
                     } catch (Exception $e) {
                        $conn->rollback();
                        echo "<script>
                                 alert('Error: " . addslashes($e->getMessage()) . "');
                              </script>";
                     }
                  } elseif (isset($_POST['vrfrejbtn'])) {
                     $id = htmlspecialchars($_GET['vrfid']);
                     $updateStatus = "UPDATE vrftb SET $status='Rejected' WHERE id = ?";
                     $stmt = $conn->prepare($updateStatus);
                     if ($stmt) {
                        $stmt->bind_param("s", $id);
                        $stmt->execute();
                        $stmt->close();
                        echo "<script>
                                 alert('Reservation rejected');
                                 window.history.back();
                              </script>";
                     } else {
                        echo "<script>
                                 alert('Error updating status.');
                              </script>";
                     }
                  }
               }
         ?>
         <script>
            // Comprehensive tab management with filtering and floating labels for pendingApproval
            document.addEventListener("DOMContentLoaded", () => {
               /* ================== UTILS ================== */
               function overlaps(aStart, aEnd, bStart, bEnd) {
                  return aStart < bEnd && bStart < aEnd;
               }

               function isCodingBanned(plate, date) {
                  if (!plate || !date) return false;
                  const m = plate.match(/(\d)(?!.*\d)/);
                  if (!m) return false;
                  const lastDigit = parseInt(m[1], 10);
                  const day = date.getDay();
                  const CODING = {1:[1,2],2:[3,4],3:[5,6],4:[7,8],5:[9,0]};
                  return CODING[day]?.includes(lastDigit) ?? false;
               }

               function attachFloatingLabelLogic(scope = document) {
                  scope.querySelectorAll(".input-container-2 input, .input-container-2 select").forEach(el => {
                     if (el.value) el.classList.add("has-content");
                     const evt = el.tagName === "SELECT" ? "change" : "input";
                     el.addEventListener(evt, () => el.value ? el.classList.add("has-content") : el.classList.remove("has-content"));
                  });
               }

               function getTabs(container) {
                  return container.querySelectorAll(".tab");
               }

               /* ================== FILTERING LOGIC ================== */
               function filter(container, index) {
                  const depEl = container.querySelector(`#departure-${index}`);
                  const retEl = container.querySelector(`#return-${index}`);
                  if (!depEl || !retEl || !depEl.value || !retEl.value) return;

                  const depDate = new Date(depEl.value);
                  const retDate = new Date(retEl.value);

                  // Build tab cache
                  const tabs = getTabs(container);
                  tabs.forEach((tab, i) => {
                     tab._dep = container.querySelector(`#departure-${i+1}`)?.value ? new Date(container.querySelector(`#departure-${i+1}`).value) : null;
                     tab._ret = container.querySelector(`#return-${i+1}`)?.value ? new Date(container.querySelector(`#return-${i+1}`).value) : null;
                     tab._vehicle = container.querySelector(`#vehicle-${i+1}`)?.value || null;
                     tab._driver = container.querySelector(`#driver-${i+1}`)?.value || null;
                  });

                  // Get existing bookings from page (stored in container or query DB)
                  const existingBookings = JSON.parse(container.dataset.existingBookings || '[]');

                  // FILTER VEHICLES
                  const vSelect = container.querySelector(`#vehicle-${index}`);
                  if (vSelect) {
                     [...vSelect.options].forEach(opt => {
                        if (!opt.value) return;
                        let reason = "";
                        if (isCodingBanned(opt.value, depDate)) reason = "Coding restriction";

                        tabs.forEach(tab => {
                           if (!reason && tab._vehicle === opt.value && tab._dep && tab._ret) {
                              if (overlaps(depDate, retDate, tab._dep, tab._ret)) reason = "Time conflict (current request)";
                           }
                        });

                        existingBookings.forEach(b => {
                           if (!reason && b.vehicle === opt.value) {
                              const dbDep = new Date(b.departure);
                              const dbRet = new Date(b.return);
                              if (overlaps(depDate, retDate, dbDep, dbRet)) reason = "Time conflict (existing booking)";
                           }
                        });

                        opt.disabled = !!reason;
                        opt.title = reason;
                     });
                  }

                  // FILTER DRIVERS
                  const dSelect = container.querySelector(`#driver-${index}`);
                  if (dSelect) {
                     [...dSelect.options].forEach(opt => {
                        if (!opt.value) return;
                        let reason = "";

                        tabs.forEach(tab => {
                           if (!reason && tab._driver === opt.value && tab._dep && tab._ret) {
                              if (overlaps(depDate, retDate, tab._dep, tab._ret)) reason = "Time conflict (current request)";
                           }
                        });

                        existingBookings.forEach(b => {
                           if (!reason && b.driver === opt.value) {
                              const dbDep = new Date(b.departure);
                              const dbRet = new Date(b.return);
                              if (overlaps(depDate, retDate, dbDep, dbRet)) reason = "Time conflict (existing booking)";
                           }
                        });

                        opt.disabled = !!reason;
                        opt.title = reason;
                     });
                  }
               }

               function refreshAllTabs(container) {
                  const count = getTabs(container).length;
                  for (let i = 1; i <= count; i++) filter(container, i);
               }

               function attachReturnMinLogic(container, tabIndex) {
                  const depEl = container.querySelector(`#departure-${tabIndex}`);
                  const retEl = container.querySelector(`#return-${tabIndex}`);
                  if (!depEl || !retEl) return;

                  depEl.addEventListener("change", () => {
                     if (depEl.value) {
                        retEl.min = depEl.value;
                        if (retEl.value && retEl.value < depEl.value) {
                           retEl.value = depEl.value;
                        }
                     }
                  });
               }

               function attachChangeEvents(container, index) {
                  ["departure", "return", "vehicle", "driver"].forEach(name => {
                     const el = container.querySelector(`#${name}-${index}`);
                     if (el) el.addEventListener("change", () => refreshAllTabs(container));
                  });
               }

               /* ================== INITIALIZE EXISTING TABS ================== */
               document.querySelectorAll(".details-container").forEach(container => {
                  attachFloatingLabelLogic(container);
                  refreshAllTabs(container);
                  const tabCount = getTabs(container).length;
                  for (let i = 1; i <= tabCount; i++) {
                     attachReturnMinLogic(container, i);
                     attachChangeEvents(container, i);
                  }
               });

               /* ================== ADD TAB BUTTONS ================== */
               document.querySelectorAll("[id^='add-tab-']").forEach(addBtn => {
                  addBtn.addEventListener("click", () => {
                     const vrfId = addBtn.id.replace("add-tab-", "");
                     const container = addBtn.closest(".details-container");
                     const removeLabel = document.getElementById(`tab-remover-${vrfId}`);
                     
                     let tabCount = getTabs(container).length;
                     tabCount++;
                     const tabId = `rn${tabCount}`;

                     const vehicleOptions = container.dataset.vehicleOptions || "";
                     const driverOptions = container.dataset.driverOptions || "";

                     const template = `
                        <input type="radio" id="${tabId}" name="mytabs">
                        <label class="tab-name" for="${tabId}">${tabCount}</label>
                        <div class="tab">
                           <div class="input-container-2">
                              <input type="datetime-local" name="vrfdeparture[${tabCount-1}]" id="departure-${tabCount}" required>
                              <label for="departure-${tabCount}">DEPARTURE:</label>
                           </div>
                           <div class="input-container-2">
                              <input type="datetime-local" name="vrfreturn[${tabCount-1}]" id="return-${tabCount}" required>
                              <label for="return-${tabCount}">RETURN:</label>
                           </div>
                           <div class="input-container-2">
                              <select name="vrfvehicle[${tabCount-1}]" id="vehicle-${tabCount}" required>
                                 <option value="" disabled selected></option>
                                 ${vehicleOptions}
                              </select>
                              <label for="vehicle-${tabCount}">VEHICLE:</label>
                           </div>
                           <div class="input-container-2">
                              <select name="vrfdriver[${tabCount-1}]" id="driver-${tabCount}" required>
                                 <option value="" disabled selected></option>
                                 ${driverOptions}
                              </select>
                              <label for="driver-${tabCount}">DRIVER:</label>
                           </div>
                        </div>
                     `;

                     removeLabel.insertAdjacentHTML("beforebegin", template);
                     const newTab = container.querySelector(`label[for="${tabId}"]`)?.nextElementSibling;
                     if (newTab) attachFloatingLabelLogic(newTab);

                     attachReturnMinLogic(container, tabCount);
                     attachChangeEvents(container, tabCount);

                     document.getElementById(tabId).checked = true;
                     removeLabel.style.display = tabCount >= 2 ? "inline-block" : "none";
                     
                     setTimeout(() => refreshAllTabs(container), 0);
                  });
               });

               /* ================== REMOVE TAB BUTTONS ================== */
               document.querySelectorAll("[id^='remove-tab-']").forEach(removeBtn => {
                  removeBtn.addEventListener("click", () => {
                     const vrfId = removeBtn.id.replace("remove-tab-", "");
                     const container = removeBtn.closest(".details-container");
                     const removeLabel = document.getElementById(`tab-remover-${vrfId}`);
                     
                     let tabCount = getTabs(container).length;
                     if (tabCount <= 1) return;

                     const radio = container.querySelector(`#rn${tabCount}`);
                     const label = container.querySelector(`label[for="rn${tabCount}"]`);
                     const tab = label?.nextElementSibling;

                     radio?.remove();
                     label?.remove();
                     tab?.remove();

                     tabCount--;
                     const prev = container.querySelector(`#rn${tabCount}`);
                     if (prev) prev.checked = true;

                     removeLabel.style.display = tabCount >= 2 ? "inline-block" : "none";
                     setTimeout(() => refreshAllTabs(container), 0);
                  });
               });
            });
         </script>
      <?php
   }
   function reservationApproved()
   {
      ?>
         <input class="search" type="text" id="search" data-page="approved" placeholder="Search reservation">
         <div class="maintitle">
            <h1>Reservation Approved</h1>
            <?php
               if($_SESSION['role']=='Secretary')
               {
                  $status='gsoassistant_status';
               }
               elseif($_SESSION['role']=='Immediate Head')
               {
                  $status='immediatehead_status';
               }
               elseif($_SESSION['role']=='Director')
               {
                  $status='gsodirector_status';
               }
               else if($_SESSION['role']=='Accountant')
               {
                  $status='accounting_status';
               }
               include 'config.php';
               $selectvrf = "SELECT * FROM vrftb WHERE updated_at >= DATE_SUB(NOW(), INTERVAL 1 DAY) AND gsoassistant_status='Approved' AND immediatehead_status='Approved' AND gsodirector_status='Approved' AND accounting_status='Approved' ORDER BY date_filed DESC, id DESC";
               $resultvrf = $conn->query($selectvrf);
               if ($resultvrf->num_rows > 0) {
                  $rowvrf = $resultvrf->fetch_assoc();
                  echo '<p>New</p>';
               }
            ?>
         </div>
         <div class="whitespace"></div>
         <div class="whitespace2"></div>
         <div style="text-align: center; margin-bottom: 2vh;">
            <a href="GSO.php?rapp=a&show_old=<?php echo isset($_GET['show_old']) && $_GET['show_old'] == 1 ? '0' : '1'; ?>" style="text-decoration: none;">
               <button type="button" style="padding: 0.8vh 1.6vh; background-color: <?php echo isset($_GET['show_old']) && $_GET['show_old'] == 1 ? '#80050d' : '#ffffff'; ?>; color: <?php echo isset($_GET['show_old']) && $_GET['show_old'] == 1 ? '#ffffff' : '#80050d'; ?>; border: 0.2vh solid #80050d; border-radius: 0.8vh; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">
                  <?php echo isset($_GET['show_old']) && $_GET['show_old'] == 1 ? 'Showing All Records' : 'Show Records Within Month'; ?>
               </button>
            </a>
         </div>
         <?php
            $showOld = isset($_GET['show_old']) && $_GET['show_old'] == 1;
            if ($showOld) {
               $selectvrf = "SELECT * FROM vrftb WHERE gsoassistant_status='Approved' AND immediatehead_status='Approved' AND gsodirector_status='Approved' AND accounting_status='Approved' ORDER BY date_filed DESC, id DESC";
            } else {
               $selectvrf = "SELECT * FROM vrftb WHERE gsoassistant_status='Approved' AND immediatehead_status='Approved' AND gsodirector_status='Approved' AND accounting_status='Approved' AND updated_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH) ORDER BY date_filed DESC, id DESC";
            }
            $resultvrf = $conn->query($selectvrf);
            if ($resultvrf->num_rows > 0) {
            ?>
            <div id="approved-results">
            <?php
               while($rowvrf = $resultvrf->fetch_assoc()) {
                  ?>
                     <a href="GSO.php?rapp=a&vrfid=<?php echo $rowvrf['id']; ?>#vrespopup" class="link" style="text-decoration:none;">
                  <?php
                     if($rowvrf[$status] != "Seen")
                     { 
                        ?> <div class="info-box"> <?php 
                     }
                     else
                     { 
                        ?> <div class="info-box" style="background-color:#eeeeee;"> <?php 
                     }
                        ?>
                           <div class="pending">
                              <?php
                                 if($rowvrf[$status] == "Pending")
                                 {
                                    echo '<div class="circle"></div>';
                                 }
                              ?>
                              <span class="time">
                                 <?php
                                    $updated_at = strtotime($rowvrf['updated_at']);
                                    $now = time();
                                    $interval = $now - $updated_at;
                                    
                                    if ($interval < 60) {
                                        echo 'Just now';
                                    } elseif ($interval < 3600) {
                                        $minutes = floor($interval / 60);
                                        echo $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
                                    } elseif ($interval < 86400) {
                                        $hours = floor($interval / 3600);
                                        echo $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
                                    } else {
                                        $days = floor($interval / 86400);
                                        echo $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
                                    }
                                 ?>
                              </span>
                           </div>
                           <div class="info-heading">
                           <?php
                              $name = $rowvrf['name'];
                              $selectppicture = $conn->prepare("SELECT * FROM usertb WHERE CONCAT(fname, ' ', lname) = ?");
                              $selectppicture->bind_param("s", $name);
                              $selectppicture->execute();
                              $resultppicture = $selectppicture->get_result();

                              if ($resultppicture->num_rows > 0) {
                                 $rowppicture = $resultppicture->fetch_assoc();
                                 if ($rowppicture['ppicture'] != null) {
                                    $profilePicture = $rowppicture['ppicture'];
                                 } else {
                                    $profilePicture = "default.png";
                                 } 
                              } else {
                                 $profilePicture = "default.png";
                              }
                           ?>
                           <img src="uploads/<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile">
                              <span class="info-heading-text">
                                 <span class="name"><?php echo $rowvrf['name'] ?></span>
                                 <span class="department"><?php echo $rowvrf['department'] ?></span>
                                 <span class="date"><?php echo "Date: ".date("m/d/Y", strtotime($rowvrf['date_filed']));?></span>
                              </span>
                           </div>
                           <div class="info-details">
                              <div>
                                 <div><div class="title">Activity</div></div>
                                 <div><div class="title">Purpose:</div></div>
                                 <div><div class="title">Budget No.:</div></div>
                              </div>
                              <div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['activity']; ?></div></div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['purpose']; ?></div></div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['budget_no']; ?></div></div>
                              </div>
                              <div>
                                 <div><div class="title">Destination:</div></div>
                                 <div><div class="title">Passenger count:</div></div>
                                 <div><div class="title">Vehicle to be used:</div></div>
                              </div>
                              <div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['destination']; ?></div></div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['passenger_count'] ?></div></div>
                                 <div><div class="dikoalam">
                                    <?php 
                                       $vrfid = $rowvrf['id']; 
                                       $selectdetails = "SELECT * FROM vrf_detailstb WHERE vrf_id = '$vrfid'";
                                       $resultdetails = $conn->query($selectdetails);
                                       if ($resultdetails->num_rows > 0) {
                                          $vehicles = [];
                                          while($rowdetails = $resultdetails->fetch_assoc()) {
                                             $plate_number = $rowdetails['vehicle']; 
                                             $selectvehicle = "SELECT * FROM carstb WHERE plate_number = '$plate_number'";
                                             $resultvehicle = $conn->query($selectvehicle);
                                             if ($resultvehicle->num_rows > 0) {
                                                while($rowvehicle = $resultvehicle->fetch_assoc()) {
                                                   $vehicles[] = $rowvehicle['brand']." ".$rowvehicle['model'];
                                                }
                                             } else {
                                                $vehicles[] = $rowdetails['vehicle'];
                                             }
                                          }
                                          echo implode(", ", $vehicles);
                                       } else {
                                          echo "N/A";
                                       }
                                    ?>
                                 </div></div>
                              </div>
                           </div>
                        </div>
                     </a>
                     <div id="vrespopup">
                        <div class="vres">
                           <form class="vehicle-reservation-form" method="post" enctype="multipart/form-data">
                              <a href="GSO.php?rapp=a" class="closepopup">×</a>
                              <img src="PNG/CSA_Logo.png" alt="">
                              <span class="header">
                                 <span id="csab">Colegio San Agustin-Biñan</span>
                                 <span id="swe">Southwoods Ecocentrum, Brgy. San Francisco, 4024 Biñan City, Philippines</span>
                                 <span id="vrf">VEHICLE RESERVATION FORM</span>
                                 <span id="fid">
                                    <span id="fid">Form ID:
                                       <?php
                                          include 'config.php';
                                          $selectvrfid = "SELECT * FROM vrftb WHERE id = '".$_GET['vrfid']."'";
                                          $resultvrfid = $conn->query($selectvrfid);
                                          $resultvrfid->num_rows > 0;
                                          $rowvrfid = $resultvrfid->fetch_assoc();
                                          echo $rowvrfid['id'];
                                       ?>
                                    </span>
                                 </span>
                              </span>
                              <div class="vrf-details">
                                 <div class="vrf-details-column">
                                    <div class="input-container">
                                       <input name="vrfname" value="<?php echo $rowvrfid['name'] ?>" type="text" id="name" required readonly>
                                       <label for="name">NAME:</label>
                                    </div>
                                    <div class="input-container">
                                       <input name="vrfdepartment" value="<?php echo $rowvrfid['department'] ?>" type="text"  id="department" required readonly>
                                       <label for="department">DEPARTMENT:</label>
                                    </div>
                                    <div class="input-container">
                                       <input name="vrfactivity" value="<?php echo $rowvrfid['activity'] ?>" type="text" id="activity" required readonly>
                                       <label for="activity">ACTIVITY:</label>
                                    </div> 
                                 </div>
                                 <div class="vrf-details-column">
                                    <div class="input-container">
                                       <input name="vrfdate_filed" type="date" value="<?php echo $rowvrfid['date_filed']; ?>" id="dateFiled" required readonly>
                                       <label for="dateFiled">DATE FILED:</label>
                                    </div>
                                    <div class="input-container">
                                       <input name="vrfbudget_no" type="number" id="budgetNo" required readonly value="<?php echo $rowvrfid['budget_no']; ?>">
                                       <label for="budgetNo">BUDGET No.:</label>
                                    </div>
                                    <div class="input-container">
                                       <input type="text" name="vrfpurpose" value="<?php echo $rowvrfid['purpose'] ?>" id="purpose" required readonly>
                                       <label for="purpose">PURPOSE:</label>
                                    </div>
                                 </div>
                              </div>
                              <span class="address">
                                 <span>DESTINATION (PLEASE SPECIFY PLACE AND ADDRESS):</span>
                                 <textarea name="vrfdestination" maxlength="255" type="text"  id="destination" required readonly><?php echo $rowvrfid['destination'] ?></textarea>
                              </span>
                              <?php
                                 include 'config.php';
                                 $selectvrfdetails = "SELECT * FROM vrf_detailstb WHERE vrf_id = '".$_GET['vrfid']."'";
                                 $resultvrfdetails = $conn->query($selectvrfdetails);
                                 
                                 // Get all drivers and vehicles
                                 $drivers = [];
                                 $driverOptions = "";
                                 $q = mysqli_query($conn, "SELECT * FROM usertb WHERE role='driver'");
                                 while ($r = mysqli_fetch_assoc($q)) {
                                    $drivers[] = $r;
                                    $driverOptions .= "<option value='{$r['employeeid']}'>" . htmlspecialchars($r['fname'] .' '.$r['lname'] ) . "</option>";
                                 }

                                 $vehicles = [];
                                 $vehicleOptions = "";
                                 $q = mysqli_query($conn, "SELECT * FROM carstb");
                                 while ($r = mysqli_fetch_assoc($q)) {
                                    $vehicles[] = $r;
                                    $vehicleOptions .= "<option value='{$r['plate_number']}'>" . htmlspecialchars($r['brand'].' '.$r['model']) . "</option>";
                                 }
                                 
                                 // Get existing bookings for conflict detection
                                 $existingBookings = [];
                                 $q = mysqli_query(
                                    $conn,
                                    "SELECT departure, `return`, vehicle, driver FROM vrf_detailstb WHERE vrf_id != '".$_GET['vrfid']."'"
                                 );
                                 while ($r = mysqli_fetch_assoc($q)) {
                                    $existingBookings[] = [
                                       'departure' => $r['departure'],
                                       'return'    => $r['return'],
                                       'vehicle'   => $r['vehicle'],
                                       'driver'    => $r['driver']
                                    ];
                                 }
                              ?>
                              <div class="details-container" 
                                   data-vehicle-options="<?php echo htmlspecialchars($vehicleOptions); ?>"
                                   data-driver-options="<?php echo htmlspecialchars($driverOptions); ?>"
                                   data-existing-bookings="<?php echo htmlspecialchars(json_encode($existingBookings)); ?>">
                                 <?php
                                    if ($resultvrfdetails->num_rows > 0) {
                                       $tab_number = 1;
                                       while($rowvrfdetails = $resultvrfdetails->fetch_assoc()) {
                                          ?>
                                             <input type="radio" id="rn<?php echo $tab_number ?>" name="mytabs" <?php echo ($tab_number == 1) ? 'checked' : ''; ?>>
                                             <label class="tab-name" for="rn<?php echo $tab_number ?>"><?php echo $tab_number ?></label>
                                             <div class="tab">
                                                <div class="input-container-2">
                                                   <input name="vrfdeparture[<?php echo $tab_number - 1 ?>]" type="datetime-local" id="departure-<?php echo $tab_number ?>" value="<?php echo $rowvrfdetails['departure']; ?>" required <?php echo (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])) ? '' : 'readonly'; ?>>
                                                   <label for="departure-<?php echo $tab_number ?>">DEPARTURE:</label>
                                                </div>

                                                <div class="input-container-2">
                                                   <input name="vrfreturn[<?php echo $tab_number - 1 ?>]" type="datetime-local" id="return-<?php echo $tab_number ?>" value="<?php echo $rowvrfdetails['return']; ?>" required <?php echo (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])) ? '' : 'readonly'; ?>>
                                                   <label for="return-<?php echo $tab_number ?>">RETURN:</label>
                                                </div>

                                                <div class="input-container-2">
                                                   <?php if (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])): ?>
                                                      <select name="vrfvehicle[<?php echo $tab_number - 1 ?>]" id="vehicle-<?php echo $tab_number ?>" required>
                                                         <option value="" disabled selected></option>
                                                         <?php foreach ($vehicles as $v): ?>
                                                            <option value="<?php echo $v['plate_number']; ?>" <?php echo ($rowvrfdetails['vehicle'] === $v['plate_number']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($v['brand'] . " " . $v['model']); ?></option>
                                                         <?php endforeach; ?>
                                                      </select>
                                                      <label for="vehicle-<?php echo $tab_number ?>">VEHICLE:</label>
                                                   <?php else: ?>
                                                      <?php
                                                         $selectvehicle = "SELECT brand, model FROM carstb WHERE plate_number = ?";
                                                         $stmtVehicle = $conn->prepare($selectvehicle);
                                                         $stmtVehicle->bind_param("s", $rowvrfdetails['vehicle']);
                                                         $stmtVehicle->execute();
                                                         $resultVehicle = $stmtVehicle->get_result();
                                                         $vehicleDisplay = $rowvrfdetails['vehicle'];
                                                         if ($resultVehicle->num_rows > 0) {
                                                            $rowVehicle = $resultVehicle->fetch_assoc();
                                                            $vehicleDisplay = $rowVehicle['brand'] . " " . $rowVehicle['model'];
                                                         }
                                                         $stmtVehicle->close();
                                                      ?>
                                                      <input name="vrfvehicle[<?php echo $tab_number - 1 ?>]" type="text" id="vehicle-<?php echo $tab_number ?>" value="<?php echo htmlspecialchars($vehicleDisplay); ?>" required readonly>
                                                      <input type="hidden" name="vrfvehicle_actual[<?php echo $tab_number - 1 ?>]" value="<?php echo htmlspecialchars($rowvrfdetails['vehicle']); ?>">
                                                      <label for="vehicle-<?php echo $tab_number ?>">VEHICLE:</label>
                                                   <?php endif; ?>
                                                </div>

                                                <div class="input-container-2">
                                                   <?php if (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])): ?>
                                                      <select name="vrfdriver[<?php echo $tab_number - 1 ?>]" id="driver-<?php echo $tab_number ?>" required>
                                                         <option value="" disabled selected></option>
                                                         <?php foreach ($drivers as $d): ?>
                                                            <option value="<?php echo htmlspecialchars($d['employeeid']); ?>" <?php echo ($rowvrfdetails['driver'] === $d['employeeid']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($d['fname'] . " " . $d['lname']); ?></option>
                                                         <?php endforeach; ?>
                                                      </select>
                                                      <label for="driver-<?php echo $tab_number ?>">DRIVER:</label>
                                                   <?php else: ?>
                                                      <?php
                                                         $selectdriver = "SELECT fname, lname FROM usertb WHERE employeeid = ?";
                                                         $stmtDriver = $conn->prepare($selectdriver);
                                                         $stmtDriver->bind_param("s", $rowvrfdetails['driver']);
                                                         $stmtDriver->execute();
                                                         $resultDriver = $stmtDriver->get_result();
                                                         $driverDisplay = $rowvrfdetails['driver'];
                                                         if ($resultDriver->num_rows > 0) {
                                                            $rowDriver = $resultDriver->fetch_assoc();
                                                            $driverDisplay = $rowDriver['fname'] . " " . $rowDriver['lname'];
                                                         }
                                                         $stmtDriver->close();
                                                      ?>
                                                      <input name="vrfdriver[<?php echo $tab_number - 1 ?>]" type="text" id="driver-<?php echo $tab_number ?>" value="<?php echo htmlspecialchars($driverDisplay); ?>" required readonly>
                                                      <input type="hidden" name="vrfdriver_actual[<?php echo $tab_number - 1 ?>]" value="<?php echo htmlspecialchars($rowvrfdetails['driver']); ?>">
                                                      <label for="driver-<?php echo $tab_number ?>">DRIVER:</label>
                                                   <?php endif; ?>
                                                </div>
                                             </div>
                                          <?php
                                          $tab_number++;
                                       }
                                    }
                                 ?>
                              </div>
                              <div class="vrf-details">
                                 <div class="input-container">
                                    <div class="passenger-container">
                                       <span>NAME OF PASSENGER/S</span>
                                       <div id="passengerList">
                                          <?php
                                             if ($rowvrfid['passenger_attachment'] == null) {
                                                $selectpassenger = "SELECT * FROM passengerstb WHERE vrfid = '".$_GET['vrfid']."'";
                                                $resultpassenger = $conn->query($selectpassenger);
                                                if ($resultpassenger->num_rows > 0) {
                                                   $passenger_number = 1;
                                                   while($rowpassenger = $resultpassenger->fetch_assoc()) {
                                                      ?>
                                                         <div class="input-container" style="position:relative;">
                                                            <input type="text" name="vrfpassenger_name[]" value="<?php echo $rowpassenger['passenger_name']; ?>" required readonly>
                                                            <label for="passengerName">PASSENGER#<?php echo $passenger_number ?></label>
                                                            <button class="remove-passenger" type="button" style="position:absolute; transform:translateX(224px);display:none;">×</button>
                                                         </div>
                                                      <?php
                                                      $passenger_number++;
                                                   }
                                                }
                                             } else {
                                                ?>
                                                   <div class="input-container" style="transform: translateY(0.5vw); display: flex; flex-direction: row;">
                                                      <a href="uploads/<?php echo $rowvrfid['passenger_attachment'] ?>" target="_blank"><input type="text" value="<?php echo $rowvrfid['passenger_attachment'] ?>" name="vrfpassenger_attachment" required style="cursor:pointer; border-color:black; width: 190px; border-top-right-radius: 0; border-bottom-right-radius: 0;"></a>
                                                      <input readonly type="number" value="<?php echo $rowvrfid['passenger_count'] ?>" name="vrfpassenger_count" required style=" border-color:black; text-align: center; width: 50px; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                      <label for="passengerCount">PASSENGER NAMES</label>
                                                   </div>

                                                <?php
                                             }
                                          ?>
                                       </div>
                                    </div>
                                    
                                 </div>   
                                 <span class="address">
                                    <!-- <span style="text-align:center;">TRANSPORTATION COST</span>
                                    <span style="transform: translateX(60px);">
                                       <textarea style="cursor:not-allowed;" name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost" readonly></textarea>
                                       <div class="input-container">
                                          <a href="#"><input name="vrftotal_cost" type="number" id="totalCost"  style="padding-left:1.3vw;cursor: not-allowed;" step="0.01" min="0" readonly></a>
                                          <label for="total_cost" style="margin-left:13px">TOTAL COST</label>
                                          <div>
                                             <label id="pesoSign">?</label>
                                          </div>
                                       </div>
                                    </span> -->
                                    <span style="text-align:center">TRANSPORTATION COST</span>
                                    <span style="transform: translateX(60px);">
                                       <textarea name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost" readonly><?php echo $rowvrfid['transportation_cost'] ?></textarea>
                                       <div class="input-container">   
                                          <?php
                                             if($rowvrfid['total_cost'] == 0.00)
                                             {
                                                ?>
                                                   <a href="#vrespopup">      
                                                <?php
                                             }
                                          ?>
                                             <input name="vrftotal_cost" type="number" id="totalCost" value="<?php if($rowvrfid['total_cost'] != 0.00) echo $rowvrfid['total_cost']; ?>" style="padding-left:1.5vw;" step="0.01" min="0" readonly>
                                          <?php
                                             if($rowvrfid['total_cost'] == 0.00)
                                             {
                                                ?>
                                                   </a>      
                                                <?php
                                             }
                                          ?>
                                          <label for="total_cost" style="margin-left:1vw">TOTAL COST</label>
                                          <div>
                                             <label <?php if($rowvrfid['total_cost'] != 0.00)echo "style=\"visibility:visible;color:black;font-weight:100;\"" ?> id="pesoSign">₱</label>
                                          </div>
                                       </div>
                                    </span>
                                    <script>
                                       function updatePesoVisibility() {
                                          if (document.activeElement === input || input.checkValidity()) {
                                             pesoSign.style.visibility = "visible";
                                          } else {
                                             pesoSign.style.visibility = "hidden";
                                          }
                                       }

                                       input.addEventListener("input", function () {
                                          const value = this.value;
                                          if (value.includes('.')) {
                                             const [whole, decimal = ''] = value.split('.');
                                             if (decimal.length > 2) {
                                                this.value = `${whole}.${decimal.slice(0, 2)}`;
                                             }
                                          }
                                          updatePesoVisibility();
                                       });

                                       input.addEventListener("focus", updatePesoVisibility);
                                       input.addEventListener("blur", updatePesoVisibility);
                                    </script>
                                    <div class="subbtn-container">
                                       <a style="transform:translate(0,1vw)" href="uploads/<?php echo $rowvrfid['letter_attachment']; ?>" target="_blank"><label  class="attachment-label"><img class="attachment-img" src="PNG/File.png" for="fileInput" alt="">LETTER ATTACHMENT</label></a>
                                    </div>
                                 </span>
                              </div>
                           </form>
                           <script>
                              // Form submission validation
                              document.querySelector('.vehicle-reservation-form').addEventListener('submit', (e) => {
                                 if (e.target.querySelector('[name="vrfappbtn"]') === document.activeElement) {
                                    // Re-enable disabled options so they get submitted
                                    document.querySelectorAll('select').forEach(select => {
                                       Array.from(select.options).forEach(opt => {
                                          if (opt.disabled && opt.selected) {
                                             opt.disabled = false;
                                          }
                                       });
                                    });
                                    
                                    const container = document.querySelector('.details-container');
                                    const tabs = container.querySelectorAll('.tab');
                                    
                                    let hasIncompleteTab = false;
                                    tabs.forEach((tab, idx) => {
                                       const dep = tab.querySelector('[name*="vrfdeparture"]')?.value || '';
                                       const ret = tab.querySelector('[name*="vrfreturn"]')?.value || '';
                                       const vehicle = tab.querySelector('[name*="vrfvehicle"]')?.value || '';
                                       const driver = tab.querySelector('[name*="vrfdriver"]')?.value || '';
                                       
                                       // If any field has value, all must have values
                                       if ((dep || ret || vehicle || driver) && !(dep && ret && vehicle && driver)) {
                                          hasIncompleteTab = true;
                                       }
                                    });
                                    
                                    if (hasIncompleteTab) {
                                       e.preventDefault();
                                       alert('Please complete all fields in each tab before approving.');
                                    }
                                 }
                              });
                           </script>
                           <script>
                              // On DOM load, check each field and toggle .has-content if it has a value
                              document.addEventListener('DOMContentLoaded', function() {
                              var fields = document.querySelectorAll('.input-container input, .input-container select, .input-container-2 input, .input-container-2 select');
                              function updateField(el) {
                                 if (el.value.trim() !== '') {
                                    el.classList.add('has-content');
                                 } else {
                                    el.classList.remove('has-content');
                                 }
                              }
                              fields.forEach(function(field) {
                                 // Initial check on page load
                                 updateField(field);
                                 // On user input or change, update class
                                 field.addEventListener('input', function() { updateField(field); });
                                 field.addEventListener('change', function() { updateField(field); });
                              });
                              });
                           </script>
                        </div>
                     </div>
                  <?php
               }
            }
            ?>
            </div>
      <?php
   }
   function cancelledRequests()
   {
      ?>
         <input class="search" type="text" id="search" data-page="cancelled" placeholder="Search reservation">
         <div class="maintitle">
            <h1>Cancelled Requests</h1>
            <?php
               if($_SESSION['role']=='Secretary')
               {
                  $status='gsoassistant_status';
               }
               elseif($_SESSION['role']=='Immediate Head')
               {
                  $status='immediatehead_status';
               }
               elseif($_SESSION['role']=='Director')
               {
                  $status='gsodirector_status';
               }
               else if($_SESSION['role']=='Accountant')
               {
                  $status='accounting_status';
               }
               include 'config.php';
               $selectvrf = "SELECT * FROM vrftb WHERE updated_at >= DATE_SUB(NOW(), INTERVAL 1 DAY) AND (gsoassistant_status='Rejected' OR immediatehead_status='Rejected' OR gsodirector_status='Rejected' OR accounting_status='Rejected' OR user_cancelled='Yes') ORDER BY date_filed DESC, id DESC";
               $resultvrf = $conn->query($selectvrf);
               if ($resultvrf->num_rows > 0) {
                  $rowvrf = $resultvrf->fetch_assoc();
                  echo '<p>New</p>';
               }
            ?>
         </div>
         <div class="whitespace"></div>
         <div class="whitespace2"></div>
         <div style="text-align: center; margin-bottom: 2vh;">
            <a href="GSO.php?creq=a&show_old=<?php echo isset($_GET['show_old']) && $_GET['show_old'] == 1 ? '0' : '1'; ?>" style="text-decoration: none;">
               <button type="button" style="padding: 0.8vh 1.6vh; background-color: <?php echo isset($_GET['show_old']) && $_GET['show_old'] == 1 ? '#80050d' : '#ffffff'; ?>; color: <?php echo isset($_GET['show_old']) && $_GET['show_old'] == 1 ? '#ffffff' : '#80050d'; ?>; border: 0.2vh solid #80050d; border-radius: 0.8vh; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">
                  <?php echo isset($_GET['show_old']) && $_GET['show_old'] == 1 ? 'Showing All Records' : 'Show Records Within Month'; ?>
               </button>
            </a>
         </div>
         <?php
            $showOld = isset($_GET['show_old']) && $_GET['show_old'] == 1;
            if ($showOld) {
               $selectvrf = "SELECT * FROM vrftb WHERE gsoassistant_status='Rejected' OR immediatehead_status='Rejected' OR gsodirector_status='Rejected' OR accounting_status='Rejected' OR user_cancelled='Yes' ORDER BY date_filed DESC, id DESC";
            } else {
               $selectvrf = "SELECT * FROM vrftb WHERE updated_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH) AND (gsoassistant_status='Rejected' OR immediatehead_status='Rejected' OR gsodirector_status='Rejected' OR accounting_status='Rejected' OR user_cancelled='Yes') ORDER BY date_filed DESC, id DESC";
            }
            $resultvrf = $conn->query($selectvrf);
            if ($resultvrf->num_rows > 0) {
            ?>
            <div id="cancelled-results">
            <?php
               while($rowvrf = $resultvrf->fetch_assoc()) {
                  ?>
                     <a href="GSO.php?creq=a&vrfid=<?php echo $rowvrf['id']; ?>#vrespopup" class="link" style="text-decoration:none;">
                  <?php
                     if($rowvrf[$status] != "Seen")
                     { 
                        ?> <div class="info-box"> <?php 
                     }
                     else
                     { 
                        ?> <div class="info-box"> <?php 
                     }
                        ?>
                           <div class="pending">
                              <?php
                                 if($rowvrf[$status] == "Pending" OR $rowvrf['user_cancelled'] == 'Yes')
                                 {
                                    echo '';
                                 }
                              ?>
                              <span class="time">
                                 <span class="rejected">
                                    <?php
                                       if($rowvrf['user_cancelled'] == 'No')
                                       {
                                          ?>
                                             <span>Rejected By:</span>
                                             <span class="rejected-by">
                                                <?php
                                                   if($rowvrf['gsoassistant_status'] == "Rejected")
                                                   {
                                                      echo "GSO Secretary";
                                                   }
                                                   elseif($rowvrf['immediatehead_status'] == "Rejected")
                                                   {
                                                      echo "Immediate Head";
                                                   }
                                                   elseif($rowvrf['gsodirector_status'] == "Rejected")
                                                   {
                                                      echo "GSO Director";
                                                   }
                                                   elseif($rowvrf['accounting_status'] == "Rejected")
                                                   {
                                                      echo "Accounting";
                                                   }
                                                ?>
                                             </span>
                                          <?php 
                                       }
                                       else
                                       {
                                          ?>
                                             <span>Cancelled By:</span>
                                             <span class="rejected-by">User</span>
                                          <?php
                                       }
                                    ?>
                                 </span>
                                 <?php
                                    $updated_at = strtotime($rowvrf['updated_at']);
                                    $now = time();
                                    $interval = $now - $updated_at;
                                    
                                    if ($interval < 60) {
                                        echo 'Just now';
                                    } elseif ($interval < 3600) {
                                        $minutes = floor($interval / 60);
                                        echo $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
                                    } elseif ($interval < 86400) {
                                        $hours = floor($interval / 3600);
                                        echo $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
                                    } else {
                                        $days = floor($interval / 86400);
                                        echo $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
                                    }
                                 ?>
                              </span>
                           </div>
                           <div class="info-heading">
                              <?php
                                 $name = $rowvrf['name'];
                                 $selectppicture = $conn->prepare("SELECT * FROM usertb WHERE CONCAT(fname, ' ', lname) = ?");
                                 $selectppicture->bind_param("s", $name);
                                 $selectppicture->execute();
                                 $resultppicture = $selectppicture->get_result();

                                 if ($resultppicture->num_rows > 0) {
                                    $rowppicture = $resultppicture->fetch_assoc();
                                    if ($rowppicture['ppicture'] != null) {
                                       $profilePicture = $rowppicture['ppicture'];
                                    } else {
                                       $profilePicture = "default.png";
                                    } 
                                 } else {
                                    $profilePicture = "default.png";
                                 }
                              ?>
                              <img src="uploads/<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile">  
                              <span class="info-heading-text">
                                 <span class="name"><?php echo $rowvrf['name'] ?></span>
                                 <span class="department"><?php echo $rowvrf['department'] ?></span>
                                 <span class="date"><?php echo "Date: ".date("m/d/Y", strtotime($rowvrf['date_filed']));?></span>
                              </span>
                           </div>
                           <div class="info-details">
                              <div>
                                 <div><div class="title">Activity</div></div>
                                 <div><div class="title">Purpose:</div></div>
                                 <div><div class="title">Budget No.:</div></div>
                              </div>
                              <div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['activity']; ?></div></div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['purpose']; ?></div></div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['budget_no']; ?></div></div>
                              </div>
                              <div>
                                 <div><div class="title">Destination:</div></div>
                                 <div><div class="title">Passenger count:</div></div>
                                 <div><div class="title">Vehicle to be used:</div></div>
                              </div>
                              <div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['destination']; ?></div></div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['passenger_count'] ?></div></div>
                                 <div><div class="dikoalam">
                                    <?php 
                                       $vrfid = $rowvrf['id']; 
                                       $selectdetails = "SELECT * FROM vrf_detailstb WHERE vrf_id = '$vrfid'";
                                       $resultdetails = $conn->query($selectdetails);
                                       if ($resultdetails->num_rows > 0) {
                                          $vehicles = [];
                                          while($rowdetails = $resultdetails->fetch_assoc()) {
                                             $plate_number = $rowdetails['vehicle']; 
                                             $selectvehicle = "SELECT * FROM carstb WHERE plate_number = '$plate_number'";
                                             $resultvehicle = $conn->query($selectvehicle);
                                             if ($resultvehicle->num_rows > 0) {
                                                while($rowvehicle = $resultvehicle->fetch_assoc()) {
                                                   $vehicles[] = $rowvehicle['brand']." ".$rowvehicle['model'];
                                                }
                                             } else {
                                                $vehicles[] = $rowdetails['vehicle'];
                                             }
                                          }
                                          echo implode(", ", $vehicles);
                                       } else {
                                          echo "N/A";
                                       }
                                    ?>
                                 </div></div>
                              </div>
                           </div>
                        </div>
                     </a>
                     <div id="vrespopup">
                        <div class="vres">
                           <form class="vehicle-reservation-form" method="post" enctype="multipart/form-data">
                              <a href="GSO.php?creq=a" class="closepopup">×</a>
                              <img src="PNG/CSA_Logo.png" alt="">
                              <span class="header">
                                 <span id="csab">Colegio San Agustin-Biñan</span>
                                 <span id="swe">Southwoods Ecocentrum, Brgy. San Francisco, 4024 Biñan City, Philippines</span>
                                 <span id="vrf">VEHICLE RESERVATION FORM</span>
                                 <span id="fid">
                                    <span id="fid">Form ID:
                                       <?php
                                          include 'config.php';
                                          $selectvrfid = "SELECT * FROM vrftb WHERE id = '".$_GET['vrfid']."'";
                                          $resultvrfid = $conn->query($selectvrfid);
                                          $resultvrfid->num_rows > 0;
                                          $rowvrfid = $resultvrfid->fetch_assoc();
                                          echo $rowvrfid['id'];
                                       ?>
                                    </span>
                                 </span>
                              </span>
                              <div class="vrf-details">
                                 <div class="vrf-details-column">
                                    <div class="input-container">
                                       <input name="vrfname" value="<?php echo $rowvrfid['name'] ?>" type="text" id="name" required readonly>
                                       <label for="name">NAME:</label>
                                    </div>
                                    <div class="input-container">
                                       <input name="vrfdepartment" value="<?php echo $rowvrfid['department'] ?>" type="text"  id="department" required readonly>
                                       <label for="department">DEPARTMENT:</label>
                                    </div>
                                    <div class="input-container">
                                       <input name="vrfactivity" value="<?php echo $rowvrfid['activity'] ?>" type="text" id="activity" required readonly>
                                       <label for="activity">ACTIVITY:</label>
                                    </div> 
                                 </div>
                                 <div class="vrf-details-column">
                                    <div class="input-container">
                                       <input name="vrfdate_filed" type="date" value="<?php echo $rowvrfid['date_filed']; ?>" id="dateFiled" required readonly>
                                       <label for="dateFiled">DATE FILED:</label>
                                    </div>
                                    <div class="input-container">
                                       <input name="vrfbudget_no" type="number" id="budgetNo" required readonly value="<?php echo $rowvrfid['budget_no']; ?>">
                                       <label for="budgetNo">BUDGET No.:</label>
                                    </div>
                                    <div class="input-container">
                                       <input type="text" name="vrfpurpose" value="<?php echo $rowvrfid['purpose'] ?>" id="purpose" required readonly>
                                       <label for="purpose">PURPOSE:</label>
                                    </div>
                                 </div>
                              </div>
                              <span class="address">
                                 <span>DESTINATION (PLEASE SPECIFY PLACE AND ADDRESS):</span>
                                 <textarea name="vrfdestination" maxlength="255" type="text"  id="destination" required readonly><?php echo $rowvrfid['destination'] ?></textarea>
                              </span>
                              <?php
                                 include 'config.php';
                                 $selectvrfdetails = "SELECT * FROM vrf_detailstb WHERE vrf_id = '".$_GET['vrfid']."'";
                                 $resultvrfdetails = $conn->query($selectvrfdetails);
                                 
                                 // Get all drivers and vehicles
                                 $drivers = [];
                                 $driverOptions = "";
                                 $q = mysqli_query($conn, "SELECT * FROM usertb WHERE role='driver'");
                                 while ($r = mysqli_fetch_assoc($q)) {
                                    $drivers[] = $r;
                                    $driverOptions .= "<option value='{$r['employeeid']}'>" . htmlspecialchars($r['fname'] .' '.$r['lname'] ) . "</option>";
                                 }

                                 $vehicles = [];
                                 $vehicleOptions = "";
                                 $q = mysqli_query($conn, "SELECT * FROM carstb");
                                 while ($r = mysqli_fetch_assoc($q)) {
                                    $vehicles[] = $r;
                                    $vehicleOptions .= "<option value='{$r['plate_number']}'>" . htmlspecialchars($r['brand'].' '.$r['model']) . "</option>";
                                 }
                                 
                                 // Get existing bookings for conflict detection
                                 $existingBookings = [];
                                 $q = mysqli_query(
                                    $conn,
                                    "SELECT departure, `return`, vehicle, driver FROM vrf_detailstb WHERE vrf_id != '".$_GET['vrfid']."'"
                                 );
                                 while ($r = mysqli_fetch_assoc($q)) {
                                    $existingBookings[] = [
                                       'departure' => $r['departure'],
                                       'return'    => $r['return'],
                                       'vehicle'   => $r['vehicle'],
                                       'driver'    => $r['driver']
                                    ];
                                 }
                              ?>
                              <div class="details-container" 
                                   data-vehicle-options="<?php echo htmlspecialchars($vehicleOptions); ?>"
                                   data-driver-options="<?php echo htmlspecialchars($driverOptions); ?>"
                                   data-existing-bookings="<?php echo htmlspecialchars(json_encode($existingBookings)); ?>">
                                 <?php
                                    if ($resultvrfdetails->num_rows > 0) {
                                       $tab_number = 1;
                                       while($rowvrfdetails = $resultvrfdetails->fetch_assoc()) {
                                          ?>
                                             <input type="radio" id="rn<?php echo $tab_number ?>" name="mytabs" <?php echo ($tab_number == 1) ? 'checked' : ''; ?>>
                                             <label class="tab-name" for="rn<?php echo $tab_number ?>"><?php echo $tab_number ?></label>
                                             <div class="tab">
                                                <div class="input-container-2">
                                                   <input name="vrfdeparture[<?php echo $tab_number - 1 ?>]" type="datetime-local" id="departure-<?php echo $tab_number ?>" value="<?php echo $rowvrfdetails['departure']; ?>" required <?php echo (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])) ? '' : 'readonly'; ?>>
                                                   <label for="departure-<?php echo $tab_number ?>">DEPARTURE:</label>
                                                </div>

                                                <div class="input-container-2">
                                                   <input name="vrfreturn[<?php echo $tab_number - 1 ?>]" type="datetime-local" id="return-<?php echo $tab_number ?>" value="<?php echo $rowvrfdetails['return']; ?>" required <?php echo (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])) ? '' : 'readonly'; ?>>
                                                   <label for="return-<?php echo $tab_number ?>">RETURN:</label>
                                                </div>

                                                <div class="input-container-2">
                                                   <?php if (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])): ?>
                                                      <select name="vrfvehicle[<?php echo $tab_number - 1 ?>]" id="vehicle-<?php echo $tab_number ?>" required>
                                                         <option value="" disabled selected></option>
                                                         <?php foreach ($vehicles as $v): ?>
                                                            <option value="<?php echo $v['plate_number']; ?>" <?php echo ($rowvrfdetails['vehicle'] === $v['plate_number']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($v['brand'] . " " . $v['model']); ?></option>
                                                         <?php endforeach; ?>
                                                      </select>
                                                      <label for="vehicle-<?php echo $tab_number ?>">VEHICLE:</label>
                                                   <?php else: ?>
                                                      <?php
                                                         $selectvehicle = "SELECT brand, model FROM carstb WHERE plate_number = ?";
                                                         $stmtVehicle = $conn->prepare($selectvehicle);
                                                         $stmtVehicle->bind_param("s", $rowvrfdetails['vehicle']);
                                                         $stmtVehicle->execute();
                                                         $resultVehicle = $stmtVehicle->get_result();
                                                         $vehicleDisplay = $rowvrfdetails['vehicle'];
                                                         if ($resultVehicle->num_rows > 0) {
                                                            $rowVehicle = $resultVehicle->fetch_assoc();
                                                            $vehicleDisplay = $rowVehicle['brand'] . " " . $rowVehicle['model'];
                                                         }
                                                         $stmtVehicle->close();
                                                      ?>
                                                      <input name="vrfvehicle[<?php echo $tab_number - 1 ?>]" type="text" id="vehicle-<?php echo $tab_number ?>" value="<?php echo htmlspecialchars($vehicleDisplay); ?>" required readonly>
                                                      <input type="hidden" name="vrfvehicle_actual[<?php echo $tab_number - 1 ?>]" value="<?php echo htmlspecialchars($rowvrfdetails['vehicle']); ?>">
                                                      <label for="vehicle-<?php echo $tab_number ?>">VEHICLE:</label>
                                                   <?php endif; ?>
                                                </div>

                                                <div class="input-container-2">
                                                   <?php if (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])): ?>
                                                      <select name="vrfdriver[<?php echo $tab_number - 1 ?>]" id="driver-<?php echo $tab_number ?>" required>
                                                         <option value="" disabled selected></option>
                                                         <?php foreach ($drivers as $d): ?>
                                                            <option value="<?php echo htmlspecialchars($d['employeeid']); ?>" <?php echo ($rowvrfdetails['driver'] === $d['employeeid']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($d['fname'] . " " . $d['lname']); ?></option>
                                                         <?php endforeach; ?>
                                                      </select>
                                                      <label for="driver-<?php echo $tab_number ?>">DRIVER:</label>
                                                   <?php else: ?>
                                                      <?php
                                                         $selectdriver = "SELECT fname, lname FROM usertb WHERE employeeid = ?";
                                                         $stmtDriver = $conn->prepare($selectdriver);
                                                         $stmtDriver->bind_param("s", $rowvrfdetails['driver']);
                                                         $stmtDriver->execute();
                                                         $resultDriver = $stmtDriver->get_result();
                                                         $driverDisplay = $rowvrfdetails['driver'];
                                                         if ($resultDriver->num_rows > 0) {
                                                            $rowDriver = $resultDriver->fetch_assoc();
                                                            $driverDisplay = $rowDriver['fname'] . " " . $rowDriver['lname'];
                                                         }
                                                         $stmtDriver->close();
                                                      ?>
                                                      <input name="vrfdriver[<?php echo $tab_number - 1 ?>]" type="text" id="driver-<?php echo $tab_number ?>" value="<?php echo htmlspecialchars($driverDisplay); ?>" required readonly>
                                                      <input type="hidden" name="vrfdriver_actual[<?php echo $tab_number - 1 ?>]" value="<?php echo htmlspecialchars($rowvrfdetails['driver']); ?>">
                                                      <label for="driver-<?php echo $tab_number ?>">DRIVER:</label>
                                                   <?php endif; ?>
                                                </div>
                                             </div>
                                          <?php
                                          $tab_number++;
                                       }
                                    }
                                 ?>
                              </div>
                              <div class="vrf-details">
                                 <div class="input-container">
                                    <div class="passenger-container">
                                       <span>NAME OF PASSENGER/S</span>
                                       <div id="passengerList">
                                          <?php
                                             if ($rowvrfid['passenger_attachment'] == null) {
                                                $selectpassenger = "SELECT * FROM passengerstb WHERE vrfid = '".$_GET['vrfid']."'";
                                                $resultpassenger = $conn->query($selectpassenger);
                                                if ($resultpassenger->num_rows > 0) {
                                                   $passenger_number = 1;
                                                   while($rowpassenger = $resultpassenger->fetch_assoc()) {
                                                      ?>
                                                         <div class="input-container" style="position:relative;">
                                                            <input type="text" name="vrfpassenger_name[]" value="<?php echo $rowpassenger['passenger_name']; ?>" required readonly>
                                                            <label for="passengerName">PASSENGER#<?php echo $passenger_number ?></label>
                                                            <button class="remove-passenger" type="button" style="position:absolute; transform:translateX(224px);display:none;">×</button>
                                                         </div>
                                                      <?php
                                                      $passenger_number++;
                                                   }
                                                }
                                             } else {
                                                ?>
                                                   <div class="input-container" style="transform: translateY(0.5vw); display: flex; flex-direction: row;">
                                                      <a href="uploads/<?php echo $rowvrfid['passenger_attachment'] ?>" target="_blank"><input type="text" value="<?php echo $rowvrfid['passenger_attachment'] ?>" name="vrfpassenger_attachment" required style="cursor:pointer; border-color:black; width: 190px; border-top-right-radius: 0; border-bottom-right-radius: 0;"></a>
                                                      <input readonly type="number" value="<?php echo $rowvrfid['passenger_count'] ?>" name="vrfpassenger_count" required style=" border-color:black; text-align: center; width: 50px; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                      <label for="passengerCount">PASSENGER NAMES</label>
                                                   </div>

                                                <?php
                                             }
                                          ?>
                                       </div>
                                    </div>
                                    
                                 </div>   
                                 <span class="address">
                                    <span style="text-align:center">TRANSPORTATION COST</span>
                                    <span style="transform: translateX(60px);">
                                    <textarea name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost" readonly><?php echo $rowvrfid['transportation_cost'] ?></textarea>
                                    <div class="input-container">   
                                       <?php
                                          if($rowvrf['total_cost'] == 0.00)
                                          {
                                             ?>
                                                <a href="#vrespopup">      
                                             <?php
                                          }
                                       ?>
                                          <input name="vrftotal_cost" type="number" id="totalCost" value="<?php if($rowvrfid['total_cost'] != 0.00) echo $rowvrfid['total_cost']; ?>" style="padding-left:1.5vw;" step="0.01" min="0" readonly>
                                       <?php
                                          if($rowvrf['total_cost'] == 0.00)
                                          {
                                             ?>
                                                </a>      
                                             <?php
                                          }
                                       ?>
                                       <label for="total_cost" style="margin-left:1vw">TOTAL COST</label>
                                       <div>
                                          <label <?php if($rowvrfid['total_cost'] != 0.00)echo "style=\"visibility:visible;color:black;font-weight:100;\"" ?> id="pesoSign">₱</label>
                                       </div>
                                    </div>
                                    </span>
                                    <script>
                                       function updatePesoVisibility() {
                                          if (document.activeElement === input || input.checkValidity()) {
                                             pesoSign.style.visibility = "visible";
                                          } else {
                                             pesoSign.style.visibility = "hidden";
                                          }
                                       }

                                       input.addEventListener("input", function () {
                                          const value = this.value;
                                          if (value.includes('.')) {
                                             const [whole, decimal = ''] = value.split('.');
                                             if (decimal.length > 2) {
                                                this.value = `${whole}.${decimal.slice(0, 2)}`;
                                             }
                                          }
                                          updatePesoVisibility();
                                       });

                                       input.addEventListener("focus", updatePesoVisibility);
                                       input.addEventListener("blur", updatePesoVisibility);
                                    </script>
                                    <div class="subbtn-container">
                                       <a style="transform:translate(0,1vw)" href="uploads/<?php echo $rowvrfid['letter_attachment']; ?>" target="_blank"><label  class="attachment-label"><img class="attachment-img" src="PNG/File.png" for="fileInput" alt="">LETTER ATTACHMENT</label></a>
                                       <!-- <button class="appbtn changeschedulebtn" type="submit" name="vrfchangeschedbtn">Change Schedule</button> -->
                                    </div>
                                 </span>
                              </div>
                           </form>
                           <?php
                              if (isset($_POST['vrfchangeschedbtn'])) {

                              }
                           ?>
                           <script>
                              // On DOM load, check each field and toggle .has-content if it has a value
                              document.addEventListener('DOMContentLoaded', function() {
                              var fields = document.querySelectorAll('.input-container input, .input-container select');
                              function updateField(el) {
                                 if (el.value.trim() !== '') {
                                    el.classList.add('has-content');
                                 } else {
                                    el.classList.remove('has-content');
                                 }
                              }
                              fields.forEach(function(field) {
                                 // Initial check on page load
                                 updateField(field);
                                 // On user input or change, update class
                                 field.addEventListener('input', function() { updateField(field); });
                                 field.addEventListener('change', function() { updateField(field); });
                              });
                              });
                           </script>
                           <script>
                              // Form submission validation
                              document.querySelector('.vehicle-reservation-form').addEventListener('submit', (e) => {
                                 if (e.target.querySelector('[name="vrfappbtn"]') === document.activeElement) {
                                    // Re-enable disabled options so they get submitted
                                    document.querySelectorAll('select').forEach(select => {
                                       Array.from(select.options).forEach(opt => {
                                          if (opt.disabled && opt.selected) {
                                             opt.disabled = false;
                                          }
                                       });
                                    });
                                    
                                    const container = document.querySelector('.details-container');
                                    const tabs = container.querySelectorAll('.tab');
                                    
                                    let hasIncompleteTab = false;
                                    tabs.forEach((tab, idx) => {
                                       const dep = tab.querySelector('[name*="vrfdeparture"]')?.value || '';
                                       const ret = tab.querySelector('[name*="vrfreturn"]')?.value || '';
                                       const vehicle = tab.querySelector('[name*="vrfvehicle"]')?.value || '';
                                       const driver = tab.querySelector('[name*="vrfdriver"]')?.value || '';
                                       
                                       // If any field has value, all must have values
                                       if ((dep || ret || vehicle || driver) && !(dep && ret && vehicle && driver)) {
                                          hasIncompleteTab = true;
                                       }
                                    });
                                    
                                    if (hasIncompleteTab) {
                                       e.preventDefault();
                                       alert('Please complete all fields in each tab before approving.');
                                    }
                                 }
                              });
                           </script>
                           <script>
                              // On DOM load, check each field and toggle .has-content if it has a value
                              document.addEventListener('DOMContentLoaded', function() {
                              var fields = document.querySelectorAll('.input-container input, .input-container select, .input-container-2 input, .input-container-2 select');
                              function updateField(el) {
                                 if (el.value.trim() !== '') {
                                    el.classList.add('has-content');
                                 } else {
                                    el.classList.remove('has-content');
                                 }
                              }
                              fields.forEach(function(field) {
                                 // Initial check on page load
                                 updateField(field);
                                 // On user input or change, update class
                                 field.addEventListener('input', function() { updateField(field); });
                                 field.addEventListener('change', function() { updateField(field); });
                              });
                              });
                           </script>
                        </div>
                     </div>
                  <?php
               }
            }
            ?>
            </div>
      <?php
   }
   function maintenanceChecklist()
   {
         include 'checklist.php';
   }
   function manageAccount()
   {
         include 'manageaccount.php';
      
   }
   function manageDepartment()
   {
         include 'managedept.php';
      
   }
   function summaryReport()
   {
      include 'summary_all.php';

      ?>
         <input class="search" type="text" id="search" data-page="approved" placeholder="Search reservation">
         <div class="maintitle">
            <h1>Reservation Approved</h1>
            <?php
               if($_SESSION['role']=='Secretary')
               {
                  $status='gsoassistant_status';
               }
               elseif($_SESSION['role']=='Immediate Head')
               {
                  $status='immediatehead_status';
               }
               elseif($_SESSION['role']=='Director')
               {
                  $status='gsodirector_status';
               }
               else if($_SESSION['role']=='Accountant')
               {
                  $status='accounting_status';
               }
               include 'config.php';
               $selectvrf = "SELECT * FROM vrftb WHERE updated_at >= DATE_SUB(NOW(), INTERVAL 1 DAY) AND gsoassistant_status='Approved' AND immediatehead_status='Approved' AND gsodirector_status='Approved' AND accounting_status='Approved' ORDER BY date_filed DESC, id DESC";
               $resultvrf = $conn->query($selectvrf);
               if ($resultvrf->num_rows > 0) {
                  $rowvrf = $resultvrf->fetch_assoc();
                  echo '<p>New</p>';
               }
            ?>
         </div>
         <div class="whitespace"></div>
         <div class="whitespace2"></div>
         <div style="text-align: center; margin-bottom: 2vh;">
            <a href="GSO.php?rapp=a&show_old=<?php echo isset($_GET['show_old']) && $_GET['show_old'] == 1 ? '0' : '1'; ?>" style="text-decoration: none;">
               <button type="button" style="padding: 0.8vh 1.6vh; background-color: <?php echo isset($_GET['show_old']) && $_GET['show_old'] == 1 ? '#80050d' : '#ffffff'; ?>; color: <?php echo isset($_GET['show_old']) && $_GET['show_old'] == 1 ? '#ffffff' : '#80050d'; ?>; border: 0.2vh solid #80050d; border-radius: 0.8vh; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">
                  <?php echo isset($_GET['show_old']) && $_GET['show_old'] == 1 ? 'Showing All Records' : 'Show Records Within Month'; ?>
               </button>
            </a>
         </div>
         <?php
            $showOld = isset($_GET['show_old']) && $_GET['show_old'] == 1;
            if ($showOld) {
               $selectvrf = "SELECT * FROM vrftb WHERE gsoassistant_status='Approved' AND immediatehead_status='Approved' AND gsodirector_status='Approved' AND accounting_status='Approved' ORDER BY date_filed DESC, id DESC";
            } else {
               $selectvrf = "SELECT * FROM vrftb WHERE gsoassistant_status='Approved' AND immediatehead_status='Approved' AND gsodirector_status='Approved' AND accounting_status='Approved' AND updated_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH) ORDER BY date_filed DESC, id DESC";
            }
            $resultvrf = $conn->query($selectvrf);
            if ($resultvrf->num_rows > 0) {
            ?>
            <div id="approved-results">
            <?php
               while($rowvrf = $resultvrf->fetch_assoc()) {
                  ?>
                     <a href="GSO.php?rapp=a&vrfid=<?php echo $rowvrf['id']; ?>#vrespopup" class="link" style="text-decoration:none;">
                  <?php
                     if($_SESSION['role'] != 'User')
                     {
                        if($rowvrf[$status] != "Seen")
                        { 
                           ?> <div class="info-box"> <?php 
                        }
                     }
                     else
                     { 
                        ?> <div class="info-box" <?php if ($_SESSION['role'] != 'User') echo "style='background-color:#eeeeee;'"; ?>> <?php 
                     }
                        ?>
                           <div class="pending">
                              <?php
                                 if($_SESSION['role'] != 'User')
                                 {
                                    if($rowvrf[$status] == "Pending")
                                    {
                                       echo '<div class="circle"></div>';
                                    }
                                 }
                              ?>
                              <span class="time">
                                 <?php
                                    $updated_at = strtotime($rowvrf['updated_at']);
                                    $now = time();
                                    $interval = $now - $updated_at;
                                    
                                    if ($interval < 60) {
                                        echo 'Just now';
                                    } elseif ($interval < 3600) {
                                        $minutes = floor($interval / 60);
                                        echo $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
                                    } elseif ($interval < 86400) {
                                        $hours = floor($interval / 3600);
                                        echo $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
                                    } else {
                                        $days = floor($interval / 86400);
                                        echo $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
                                    }
                                 ?>
                              </span>
                           </div>
                           <div class="info-heading">
                           <?php
                              $name = $rowvrf['name'];
                              $selectppicture = $conn->prepare("SELECT * FROM usertb WHERE CONCAT(fname, ' ', lname) = ?");
                              $selectppicture->bind_param("s", $name);
                              $selectppicture->execute();
                              $resultppicture = $selectppicture->get_result();

                              if ($resultppicture->num_rows > 0) {
                                 $rowppicture = $resultppicture->fetch_assoc();
                                 if ($rowppicture['ppicture'] != null) {
                                    $profilePicture = $rowppicture['ppicture'];
                                 } else {
                                    $profilePicture = "default.png";
                                 } 
                              } else {
                                 $profilePicture = "default.png";
                              }
                           ?>
                           <img src="uploads/<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile">
                              <span class="info-heading-text">
                                 <span class="name"><?php echo $rowvrf['name'] ?></span>
                                 <span class="department"><?php echo $rowvrf['department'] ?></span>
                                 <span class="date"><?php echo "Date: ".date("m/d/Y", strtotime($rowvrf['date_filed']));?></span>
                              </span>
                           </div>
                           <div class="info-details">
                              <div>
                                 <div><div class="title">Activity</div></div>
                                 <div><div class="title">Purpose:</div></div>
                                 <div><div class="title">Budget No.:</div></div>
                              </div>
                              <div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['activity']; ?></div></div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['purpose']; ?></div></div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['budget_no']; ?></div></div>
                              </div>
                              <div>
                                 <div><div class="title">Destination:</div></div>
                                 <div><div class="title">Passenger count:</div></div>
                                 <div><div class="title">Vehicle to be used:</div></div>
                              </div>
                              <div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['destination']; ?></div></div>
                                 <div><div class="dikoalam"><?php echo $rowvrf['passenger_count'] ?></div></div>
                                 <div><div class="dikoalam">
                                    <?php 
                                       $vrfid = $rowvrf['id']; 
                                       $selectdetails = "SELECT * FROM vrf_detailstb WHERE vrf_id = '$vrfid'";
                                       $resultdetails = $conn->query($selectdetails);
                                       if ($resultdetails->num_rows > 0) {
                                          $vehicles = [];
                                          while($rowdetails = $resultdetails->fetch_assoc()) {
                                             $plate_number = $rowdetails['vehicle']; 
                                             $selectvehicle = "SELECT * FROM carstb WHERE plate_number = '$plate_number'";
                                             $resultvehicle = $conn->query($selectvehicle);
                                             if ($resultvehicle->num_rows > 0) {
                                                while($rowvehicle = $resultvehicle->fetch_assoc()) {
                                                   $vehicles[] = $rowvehicle['brand']." ".$rowvehicle['model'];
                                                }
                                             } else {
                                                $vehicles[] = $rowdetails['vehicle'];
                                             }
                                          }
                                          echo implode(", ", $vehicles);
                                       } else {
                                          echo "N/A";
                                       }
                                    ?>
                                 </div></div>
                              </div>
                           </div>
                        </div>
                     </a>
                     <div id="vrespopup">
                        <div class="vres">
                           <form class="vehicle-reservation-form" method="post" enctype="multipart/form-data">
                              <a href="GSO.php?rapp=a" class="closepopup">×</a>
                              <img src="PNG/CSA_Logo.png" alt="">
                              <span class="header">
                                 <span id="csab">Colegio San Agustin-Biñan</span>
                                 <span id="swe">Southwoods Ecocentrum, Brgy. San Francisco, 4024 Biñan City, Philippines</span>
                                 <span id="vrf">VEHICLE RESERVATION FORM</span>
                                 <span id="fid">
                                    <span id="fid">Form ID:
                                       <?php
                                          include 'config.php';
                                          $selectvrfid = "SELECT * FROM vrftb WHERE id = '".$_GET['vrfid']."'";
                                          $resultvrfid = $conn->query($selectvrfid);
                                          $resultvrfid->num_rows > 0;
                                          $rowvrfid = $resultvrfid->fetch_assoc();
                                          echo $rowvrfid['id'];
                                       ?>
                                    </span>
                                 </span>
                              </span>
                              <div class="vrf-details">
                                 <div class="vrf-details-column">
                                    <div class="input-container">
                                       <input name="vrfname" value="<?php echo $rowvrfid['name'] ?>" type="text" id="name" required readonly>
                                       <label for="name">NAME:</label>
                                    </div>
                                    <div class="input-container">
                                       <input name="vrfdepartment" value="<?php echo $rowvrfid['department'] ?>" type="text"  id="department" required readonly>
                                       <label for="department">DEPARTMENT:</label>
                                    </div>
                                    <div class="input-container">
                                       <input name="vrfactivity" value="<?php echo $rowvrfid['activity'] ?>" type="text" id="activity" required readonly>
                                       <label for="activity">ACTIVITY:</label>
                                    </div> 
                                 </div>
                                 <div class="vrf-details-column">
                                    <div class="input-container">
                                       <input name="vrfdate_filed" type="date" value="<?php echo $rowvrfid['date_filed']; ?>" id="dateFiled" required readonly>
                                       <label for="dateFiled">DATE FILED:</label>
                                    </div>
                                    <div class="input-container">
                                       <input name="vrfbudget_no" type="number" id="budgetNo" required readonly value="<?php echo $rowvrfid['budget_no']; ?>">
                                       <label for="budgetNo">BUDGET No.:</label>
                                    </div>
                                    <div class="input-container">
                                       <input type="text" name="vrfpurpose" value="<?php echo $rowvrfid['purpose'] ?>" id="purpose" required readonly>
                                       <label for="purpose">PURPOSE:</label>
                                    </div>
                                 </div>
                              </div>
                              <span class="address">
                                 <span>DESTINATION (PLEASE SPECIFY PLACE AND ADDRESS):</span>
                                 <textarea name="vrfdestination" maxlength="255" type="text"  id="destination" required readonly><?php echo $rowvrfid['destination'] ?></textarea>
                              </span>
                              <?php
                                 include 'config.php';
                                 $selectvrfdetails = "SELECT * FROM vrf_detailstb WHERE vrf_id = '".$_GET['vrfid']."'";
                                 $resultvrfdetails = $conn->query($selectvrfdetails);
                                 
                                 // Get all drivers and vehicles
                                 $drivers = [];
                                 $driverOptions = "";
                                 $q = mysqli_query($conn, "SELECT * FROM usertb WHERE role='driver'");
                                 while ($r = mysqli_fetch_assoc($q)) {
                                    $drivers[] = $r;
                                    $driverOptions .= "<option value='{$r['employeeid']}'>" . htmlspecialchars($r['fname'] .' '.$r['lname'] ) . "</option>";
                                 }

                                 $vehicles = [];
                                 $vehicleOptions = "";
                                 $q = mysqli_query($conn, "SELECT * FROM carstb");
                                 while ($r = mysqli_fetch_assoc($q)) {
                                    $vehicles[] = $r;
                                    $vehicleOptions .= "<option value='{$r['plate_number']}'>" . htmlspecialchars($r['brand'].' '.$r['model']) . "</option>";
                                 }
                                 
                                 // Get existing bookings for conflict detection
                                 $existingBookings = [];
                                 $q = mysqli_query(
                                    $conn,
                                    "SELECT departure, `return`, vehicle, driver FROM vrf_detailstb WHERE vrf_id != '".$_GET['vrfid']."'"
                                 );
                                 while ($r = mysqli_fetch_assoc($q)) {
                                    $existingBookings[] = [
                                       'departure' => $r['departure'],
                                       'return'    => $r['return'],
                                       'vehicle'   => $r['vehicle'],
                                       'driver'    => $r['driver']
                                    ];
                                 }
                              ?>
                              <div class="details-container" 
                                   data-vehicle-options="<?php echo htmlspecialchars($vehicleOptions); ?>"
                                   data-driver-options="<?php echo htmlspecialchars($driverOptions); ?>"
                                   data-existing-bookings="<?php echo htmlspecialchars(json_encode($existingBookings)); ?>">
                                 <?php
                                    if ($resultvrfdetails->num_rows > 0) {
                                       $tab_number = 1;
                                       while($rowvrfdetails = $resultvrfdetails->fetch_assoc()) {
                                          ?>
                                             <input type="radio" id="rn<?php echo $tab_number ?>" name="mytabs" <?php echo ($tab_number == 1) ? 'checked' : ''; ?>>
                                             <label class="tab-name" for="rn<?php echo $tab_number ?>"><?php echo $tab_number ?></label>
                                             <div class="tab">
                                                <div class="input-container-2">
                                                   <input name="vrfdeparture[<?php echo $tab_number - 1 ?>]" type="datetime-local" id="departure-<?php echo $tab_number ?>" value="<?php echo $rowvrfdetails['departure']; ?>" required <?php echo (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])) ? '' : 'readonly'; ?>>
                                                   <label for="departure-<?php echo $tab_number ?>">DEPARTURE:</label>
                                                </div>

                                                <div class="input-container-2">
                                                   <input name="vrfreturn[<?php echo $tab_number - 1 ?>]" type="datetime-local" id="return-<?php echo $tab_number ?>" value="<?php echo $rowvrfdetails['return']; ?>" required <?php echo (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])) ? '' : 'readonly'; ?>>
                                                   <label for="return-<?php echo $tab_number ?>">RETURN:</label>
                                                </div>

                                                <div class="input-container-2">
                                                   <?php if (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])): ?>
                                                      <select name="vrfvehicle[<?php echo $tab_number - 1 ?>]" id="vehicle-<?php echo $tab_number ?>" required>
                                                         <option value="" disabled selected></option>
                                                         <?php foreach ($vehicles as $v): ?>
                                                            <option value="<?php echo $v['plate_number']; ?>" <?php echo ($rowvrfdetails['vehicle'] === $v['plate_number']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($v['brand'] . " " . $v['model']); ?></option>
                                                         <?php endforeach; ?>
                                                      </select>
                                                      <label for="vehicle-<?php echo $tab_number ?>">VEHICLE:</label>
                                                   <?php else: ?>
                                                      <?php
                                                         $selectvehicle = "SELECT brand, model FROM carstb WHERE plate_number = ?";
                                                         $stmtVehicle = $conn->prepare($selectvehicle);
                                                         $stmtVehicle->bind_param("s", $rowvrfdetails['vehicle']);
                                                         $stmtVehicle->execute();
                                                         $resultVehicle = $stmtVehicle->get_result();
                                                         $vehicleDisplay = $rowvrfdetails['vehicle'];
                                                         if ($resultVehicle->num_rows > 0) {
                                                            $rowVehicle = $resultVehicle->fetch_assoc();
                                                            $vehicleDisplay = $rowVehicle['brand'] . " " . $rowVehicle['model'];
                                                         }
                                                         $stmtVehicle->close();
                                                      ?>
                                                      <input name="vrfvehicle[<?php echo $tab_number - 1 ?>]" type="text" id="vehicle-<?php echo $tab_number ?>" value="<?php echo htmlspecialchars($vehicleDisplay); ?>" required readonly>
                                                      <input type="hidden" name="vrfvehicle_actual[<?php echo $tab_number - 1 ?>]" value="<?php echo htmlspecialchars($rowvrfdetails['vehicle']); ?>">
                                                      <label for="vehicle-<?php echo $tab_number ?>">VEHICLE:</label>
                                                   <?php endif; ?>
                                                </div>

                                                <div class="input-container-2">
                                                   <?php if (in_array($_SESSION['role'], ['Secretary', 'Immediate Head'])): ?>
                                                      <select name="vrfdriver[<?php echo $tab_number - 1 ?>]" id="driver-<?php echo $tab_number ?>" required>
                                                         <option value="" disabled selected></option>
                                                         <?php foreach ($drivers as $d): ?>
                                                            <option value="<?php echo htmlspecialchars($d['employeeid']); ?>" <?php echo ($rowvrfdetails['driver'] === $d['employeeid']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($d['fname'] . " " . $d['lname']); ?></option>
                                                         <?php endforeach; ?>
                                                      </select>
                                                      <label for="driver-<?php echo $tab_number ?>">DRIVER:</label>
                                                   <?php else: ?>
                                                      <?php
                                                         $selectdriver = "SELECT fname, lname FROM usertb WHERE employeeid = ?";
                                                         $stmtDriver = $conn->prepare($selectdriver);
                                                         $stmtDriver->bind_param("s", $rowvrfdetails['driver']);
                                                         $stmtDriver->execute();
                                                         $resultDriver = $stmtDriver->get_result();
                                                         $driverDisplay = $rowvrfdetails['driver'];
                                                         if ($resultDriver->num_rows > 0) {
                                                            $rowDriver = $resultDriver->fetch_assoc();
                                                            $driverDisplay = $rowDriver['fname'] . " " . $rowDriver['lname'];
                                                         }
                                                         $stmtDriver->close();
                                                      ?>
                                                      <input name="vrfdriver[<?php echo $tab_number - 1 ?>]" type="text" id="driver-<?php echo $tab_number ?>" value="<?php echo htmlspecialchars($driverDisplay); ?>" required readonly>
                                                      <input type="hidden" name="vrfdriver_actual[<?php echo $tab_number - 1 ?>]" value="<?php echo htmlspecialchars($rowvrfdetails['driver']); ?>">
                                                      <label for="driver-<?php echo $tab_number ?>">DRIVER:</label>
                                                   <?php endif; ?>
                                                </div>
                                             </div>
                                          <?php
                                          $tab_number++;
                                       }
                                    }
                                 ?>
                              </div>
                              <div class="vrf-details">
                                 <div class="input-container">
                                    <div class="passenger-container">
                                       <span>NAME OF PASSENGER/S</span>
                                       <div id="passengerList">
                                          <?php
                                             if ($rowvrfid['passenger_attachment'] == null) {
                                                $selectpassenger = "SELECT * FROM passengerstb WHERE vrfid = '".$_GET['vrfid']."'";
                                                $resultpassenger = $conn->query($selectpassenger);
                                                if ($resultpassenger->num_rows > 0) {
                                                   $passenger_number = 1;
                                                   while($rowpassenger = $resultpassenger->fetch_assoc()) {
                                                      ?>
                                                         <div class="input-container" style="position:relative;">
                                                            <input type="text" name="vrfpassenger_name[]" value="<?php echo $rowpassenger['passenger_name']; ?>" required readonly>
                                                            <label for="passengerName">PASSENGER#<?php echo $passenger_number ?></label>
                                                            <button class="remove-passenger" type="button" style="position:absolute; transform:translateX(224px);display:none;">×</button>
                                                         </div>
                                                      <?php
                                                      $passenger_number++;
                                                   }
                                                }
                                             } else {
                                                ?>
                                                   <div class="input-container" style="transform: translateY(0.5vw); display: flex; flex-direction: row;">
                                                      <a href="uploads/<?php echo $rowvrfid['passenger_attachment'] ?>" target="_blank"><input type="text" value="<?php echo $rowvrfid['passenger_attachment'] ?>" name="vrfpassenger_attachment" required style="cursor:pointer; border-color:black; width: 190px; border-top-right-radius: 0; border-bottom-right-radius: 0;"></a>
                                                      <input readonly type="number" value="<?php echo $rowvrfid['passenger_count'] ?>" name="vrfpassenger_count" required style=" border-color:black; text-align: center; width: 50px; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                      <label for="passengerCount">PASSENGER NAMES</label>
                                                   </div>

                                                <?php
                                             }
                                          ?>
                                       </div>
                                    </div>
                                    
                                 </div>   
                                 <span class="address">
                                    <!-- <span style="text-align:center;">TRANSPORTATION COST</span>
                                    <span style="transform: translateX(60px);">
                                       <textarea style="cursor:not-allowed;" name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost" readonly></textarea>
                                       <div class="input-container">
                                          <a href="#"><input name="vrftotal_cost" type="number" id="totalCost"  style="padding-left:1.3vw;cursor: not-allowed;" step="0.01" min="0" readonly></a>
                                          <label for="total_cost" style="margin-left:13px">TOTAL COST</label>
                                          <div>
                                             <label id="pesoSign">?</label>
                                          </div>
                                       </div>
                                    </span> -->
                                    <span style="text-align:center">TRANSPORTATION COST</span>
                                    <span style="transform: translateX(60px);">
                                       <textarea name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost" readonly><?php echo $rowvrfid['transportation_cost'] ?></textarea>
                                       <div class="input-container">   
                                          <?php
                                             if($rowvrfid['total_cost'] == 0.00)
                                             {
                                                ?>
                                                   <a href="#vrespopup">      
                                                <?php
                                             }
                                          ?>
                                             <input name="vrftotal_cost" type="number" id="totalCost" value="<?php if($rowvrfid['total_cost'] != 0.00) echo $rowvrfid['total_cost']; ?>" style="padding-left:1.5vw;" step="0.01" min="0" readonly>
                                          <?php
                                             if($rowvrfid['total_cost'] == 0.00)
                                             {
                                                ?>
                                                   </a>      
                                                <?php
                                             }
                                          ?>
                                          <label for="total_cost" style="margin-left:1vw">TOTAL COST</label>
                                          <div>
                                             <label <?php if($rowvrfid['total_cost'] != 0.00)echo "style=\"visibility:visible;color:black;font-weight:100;\"" ?> id="pesoSign">₱</label>
                                          </div>
                                       </div>
                                    </span>
                                    <script>
                                       function updatePesoVisibility() {
                                          if (document.activeElement === input || input.checkValidity()) {
                                             pesoSign.style.visibility = "visible";
                                          } else {
                                             pesoSign.style.visibility = "hidden";
                                          }
                                       }

                                       input.addEventListener("input", function () {
                                          const value = this.value;
                                          if (value.includes('.')) {
                                             const [whole, decimal = ''] = value.split('.');
                                             if (decimal.length > 2) {
                                                this.value = `${whole}.${decimal.slice(0, 2)}`;
                                             }
                                          }
                                          updatePesoVisibility();
                                       });

                                       input.addEventListener("focus", updatePesoVisibility);
                                       input.addEventListener("blur", updatePesoVisibility);
                                    </script>
                                    <div class="subbtn-container">
                                       <a style="transform:translate(0,1vw)" href="uploads/<?php echo $rowvrfid['letter_attachment']; ?>" target="_blank"><label  class="attachment-label"><img class="attachment-img" src="PNG/File.png" for="fileInput" alt="">LETTER ATTACHMENT</label></a>
                                    </div>
                                 </span>
                              </div>
                           </form>
                           <script>
                              // Form submission validation
                              document.querySelector('.vehicle-reservation-form').addEventListener('submit', (e) => {
                                 if (e.target.querySelector('[name="vrfappbtn"]') === document.activeElement) {
                                    // Re-enable disabled options so they get submitted
                                    document.querySelectorAll('select').forEach(select => {
                                       Array.from(select.options).forEach(opt => {
                                          if (opt.disabled && opt.selected) {
                                             opt.disabled = false;
                                          }
                                       });
                                    });
                                    
                                    const container = document.querySelector('.details-container');
                                    const tabs = container.querySelectorAll('.tab');
                                    
                                    let hasIncompleteTab = false;
                                    tabs.forEach((tab, idx) => {
                                       const dep = tab.querySelector('[name*="vrfdeparture"]')?.value || '';
                                       const ret = tab.querySelector('[name*="vrfreturn"]')?.value || '';
                                       const vehicle = tab.querySelector('[name*="vrfvehicle"]')?.value || '';
                                       const driver = tab.querySelector('[name*="vrfdriver"]')?.value || '';
                                       
                                       // If any field has value, all must have values
                                       if ((dep || ret || vehicle || driver) && !(dep && ret && vehicle && driver)) {
                                          hasIncompleteTab = true;
                                       }
                                    });
                                    
                                    if (hasIncompleteTab) {
                                       e.preventDefault();
                                       alert('Please complete all fields in each tab before approving.');
                                    }
                                 }
                              });
                           </script>
                           <script>
                              // On DOM load, check each field and toggle .has-content if it has a value
                              document.addEventListener('DOMContentLoaded', function() {
                              var fields = document.querySelectorAll('.input-container input, .input-container select, .input-container-2 input, .input-container-2 select');
                              function updateField(el) {
                                 if (el.value.trim() !== '') {
                                    el.classList.add('has-content');
                                 } else {
                                    el.classList.remove('has-content');
                                 }
                              }
                              fields.forEach(function(field) {
                                 // Initial check on page load
                                 updateField(field);
                                 // On user input or change, update class
                                 field.addEventListener('input', function() { updateField(field); });
                                 field.addEventListener('change', function() { updateField(field); });
                              });
                              });
                           </script>
                        </div>
                     </div>
                  <?php
               }
            }
            ?>
            </div>
      <?php
  
   }
   function maintenanceReport()
   {
      include 'maintenanceReport.php';
   }

   // Helper function to render a single reservation item
   function renderReservationItem($rowvrf, $status, $pageType) {
      $conn = new mysqli('localhost', 'root', '', 'vrms');
      $updated_at = strtotime($rowvrf['updated_at']);
      $now = time();
      $interval = $now - $updated_at;
      
      if ($interval < 60) {
          $timeAgo = 'Just now';
      } elseif ($interval < 3600) {
          $minutes = floor($interval / 60);
          $timeAgo = $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
      } elseif ($interval < 86400) {
          $hours = floor($interval / 3600);
          $timeAgo = $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
      } else {
          $days = floor($interval / 86400);
          $timeAgo = $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
      }
      
      $pageParam = $pageType === 'pending' ? 'papp' : ($pageType === 'approved' ? 'rapp' : 'creq');
      $linkHref = "GSO.php?" . $pageParam . "=a&vrfid=" . $rowvrf['id'] . "#vrespopup";
      
      // Get profile picture
      $name = $rowvrf['name'];
      $selectppicture = $conn->prepare("SELECT * FROM usertb WHERE CONCAT(fname, ' ', lname) = ?");
      $selectppicture->bind_param("s", $name);
      $selectppicture->execute();
      $resultppicture = $selectppicture->get_result();
      
      $profilePicture = "default.png";
      if ($resultppicture->num_rows > 0) {
         $rowppicture = $resultppicture->fetch_assoc();
         if ($rowppicture['ppicture'] != null) {
            $profilePicture = $rowppicture['ppicture'];
         }
      }
      
      $bgStyle = ($rowvrf[$status] == "Seen") ? 'style="background-color:#eeeeee;"' : '';
      $circleHTML = ($rowvrf[$status] == "Pending") ? '<div class="circle"></div>' : '';
      
      // Get vehicle details
      $vrfid = $rowvrf['id'];
      $selectdetails = "SELECT * FROM vrf_detailstb WHERE vrf_id = '$vrfid'";
      $resultdetails = $conn->query($selectdetails);
      $vehicles = [];
      if ($resultdetails->num_rows > 0) {
         while($rowdetails = $resultdetails->fetch_assoc()) {
            $plate_number = $rowdetails['vehicle'];
            $selectvehicle = "SELECT * FROM carstb WHERE plate_number = '$plate_number'";
            $resultvehicle = $conn->query($selectvehicle);
            if ($resultvehicle->num_rows > 0) {
               while($rowvehicle = $resultvehicle->fetch_assoc()) {
                  $vehicles[] = $rowvehicle['brand']." ".$rowvehicle['model'];
               }
            } else {
               $vehicles[] = $rowdetails['vehicle'];
            }
         }
      }
      $vehiclesHTML = !empty($vehicles) ? implode(", ", $vehicles) : "N/A";
      
      $dateFormatted = date("m/d/Y", strtotime($rowvrf['date_filed']));
      
      $html = <<<HTML
<a href="$linkHref" class="link" style="text-decoration:none;">
   <div class="info-box" $bgStyle>
      <div class="pending">
         $circleHTML
         <span class="time">$timeAgo</span>
      </div>
      <div class="info-heading">
         <img src="uploads/{$profilePicture}" alt="Profile">
         <span class="info-heading-text">
            <span class="name">{$rowvrf['name']}</span>
            <span class="department">{$rowvrf['department']}</span>
            <span class="date">Date: {$dateFormatted}</span>
         </span>
      </div>
      <div class="info-details">
         <div>
            <div><div class="title">Activity</div></div>
            <div><div class="title">Purpose:</div></div>
            <div><div class="title">Budget No.:</div></div>
         </div>
         <div>
            <div><div class="dikoalam">{$rowvrf['activity']}</div></div>
            <div><div class="dikoalam">{$rowvrf['purpose']}</div></div>
            <div><div class="dikoalam">{$rowvrf['budget_no']}</div></div>
         </div>
         <div>
            <div><div class="title">Destination:</div></div>
            <div><div class="title">Passenger count:</div></div>
            <div><div class="title">Vehicle to be used:</div></div>
         </div>
         <div>
            <div><div class="dikoalam">{$rowvrf['destination']}</div></div>
            <div><div class="dikoalam">{$rowvrf['passenger_count']}</div></div>
            <div><div class="dikoalam">$vehiclesHTML</div></div>
         </div>
      </div>
   </div>
</a>
HTML;
      return $html;
   }
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
   // Get all search inputs
   const searchInputs = document.querySelectorAll('input[data-page]');
   
   searchInputs.forEach(searchInput => {
      searchInput.addEventListener('input', function(e) {
         const searchTerm = this.value.trim();
         const pageType = this.getAttribute('data-page');
         const containerMap = {
            'pending': 'pending-results',
            'approved': 'approved-results',
            'cancelled': 'cancelled-results'
         };
         const containerId = containerMap[pageType];
         const container = document.getElementById(containerId);
         
         if (!container) return;
         
         // If search is empty, reload the page to show default results
         if (searchTerm === '') {
            location.reload();
            return;
         }
         
         // Make AJAX request
         fetch(`GSO.php?ajax_search=1&page=${pageType}&search=${encodeURIComponent(searchTerm)}` + (pageType !== 'pending' ? '&show_old=' + (new URLSearchParams(window.location.search).get('show_old') || '0') : ''))
            .then(response => response.json())
            .then(data => {
               // Clear existing results
               container.innerHTML = '';
               
               if (data.length === 0) {
                  container.innerHTML = '<p style="padding: 20px; text-align: center; color: #999;">No results found</p>';
                  return;
               }
               
               // Render each result
               data.forEach(item => {
                  const itemHTML = renderResultItem(item, pageType);
                  container.innerHTML += itemHTML;
               });
            })
            .catch(error => {
               console.error('Search error:', error);
            });
      });
   });
   
   // Function to render a single result item
   function renderResultItem(rowvrf, pageType) {
      const status = getStatusFieldForRole(pageType);
      const dateFormatted = new Date(rowvrf['date_filed']).toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' });
      const updated_at = new Date(rowvrf['updated_at']).getTime() / 1000;
      const now = Math.floor(Date.now() / 1000);
      const interval = now - updated_at;
      
      let timeAgo = 'Just now';
      if (interval >= 60 && interval < 3600) {
         const minutes = Math.floor(interval / 60);
         timeAgo = minutes + ' minute' + (minutes > 1 ? 's' : '') + ' ago';
      } else if (interval >= 3600 && interval < 86400) {
         const hours = Math.floor(interval / 3600);
         timeAgo = hours + ' hour' + (hours > 1 ? 's' : '') + ' ago';
      } else if (interval >= 86400) {
         const days = Math.floor(interval / 86400);
         timeAgo = days + ' day' + (days > 1 ? 's' : '') + ' ago';
      }
      
      const pageParam = pageType === 'pending' ? 'papp' : (pageType === 'approved' ? 'rapp' : 'creq');
      const linkHref = `GSO.php?${pageParam}=a&vrfid=${rowvrf['id']}#vrespopup`;
      const bgStyle = (rowvrf[status] == "Seen") ? 'style="background-color:#eeeeee;"' : '';
      const circleHTML = (rowvrf[status] == "Pending") ? '<div class="circle"></div>' : '';
      
      return `
         <a href="${linkHref}" class="link" style="text-decoration:none;">
            <div class="info-box" ${bgStyle}>
               <div class="pending">
                  ${circleHTML}
                  <span class="time">${timeAgo}</span>
               </div>
               <div class="info-heading">
                  <img src="uploads/default.png" alt="Profile">
                  <span class="info-heading-text">
                     <span class="name">${rowvrf['name']}</span>
                     <span class="department">${rowvrf['department']}</span>
                     <span class="date">Date: ${dateFormatted}</span>
                  </span>
               </div>
               <div class="info-details">
                  <div>
                     <div><div class="title">Activity</div></div>
                     <div><div class="title">Purpose:</div></div>
                     <div><div class="title">Budget No.:</div></div>
                  </div>
                  <div>
                     <div><div class="dikoalam">${rowvrf['activity']}</div></div>
                     <div><div class="dikoalam">${rowvrf['purpose']}</div></div>
                     <div><div class="dikoalam">${rowvrf['budget_no']}</div></div>
                  </div>
                  <div>
                     <div><div class="title">Destination:</div></div>
                     <div><div class="title">Passenger count:</div></div>
                     <div><div class="title">Vehicle to be used:</div></div>
                  </div>
                  <div>
                     <div><div class="dikoalam">${rowvrf['destination']}</div></div>
                     <div><div class="dikoalam">${rowvrf['passenger_count']}</div></div>
                     <div><div class="dikoalam">N/A</div></div>
                  </div>
               </div>
            </div>
         </a>
      `;
   }
   
   // Get the status field based on role and page type
   function getStatusFieldForRole(pageType) {
      // This is a simplified version - in production, you'd want to pass this from PHP
      // For now, we'll use common field names
      const statusMap = {
         'pending': 'gsoassistant_status',
         'approved': 'gsoassistant_status',
         'cancelled': 'gsoassistant_status'
      };
      return statusMap[pageType];
   }
});
</script>
<?php
?>





