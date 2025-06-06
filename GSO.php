<?php
   date_default_timezone_set('Asia/Manila');
   session_start();
   ob_start();
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
                  .icon
                  {
                     div
                     {
                        span.title
                        {
                           transform: translateX(-2.1vw);
                        }
                     }
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
            elseif(isset($_GET["srep"]) and !empty($_GET["srep"]))
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
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="calendar">
                     <img src="PNG/Calendar.png" alt="Calendar">
                     <span>Calendar</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?vsch=a"><span>Vehicle Schedules</span></a></li>
                        <li><a href="GSO.php?vres=a"><span>Vehicle Reservation Form</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="requests">
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
                        <li><a href="GSO.php?papp=a"><span>Pending Approval </span>
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
                        <li><a href="GSO.php?rapp=a"><span>Reservation Approved</span></a></li>
                        <li><a href="GSO.php?creq=a"><span>Cancelled Requests</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="vehicle">
                     <img src="PNG/Vehicle.png" alt="Vehicle">
                     <span>Vehicles</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?mveh=a"><span>Manage Vehicle</span></a></li>
                        <li><a href="GSO.php?aveh=a"><span>Add Vehicle</span></a></li>
                        <li><a href="GSO.php?mche=a"><span>Maintenance Checklist</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="account">
                     <img src="PNG/Account.png" alt="Report">
                     <span>Accounts</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?macc=a"><span>Manage Accounts</span></a></li>
                        <li><a href="GSO.php?mdep=a"><span>Manage Departments</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="report">
                     <img src="PNG/File.png" alt="Report">
                     <span>Report</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?srep=a"><span>Summary Report</span></a></li>
                        <li><a href="GSO.php?mrep=a"><span>Maintenance Report</span></a></li>
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
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="calendar">
                     <img src="PNG/Calendar.png" alt="Calendar">
                     <span>Calendar</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?vsch=a"><span>Vehicle Schedules</span></a></li>
                        <li><a href="GSO.php?vres=a"><span>Vehicle Reservation Form</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="requests">
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
                        <li><a href="GSO.php?papp=a"><span>Pending Approval </span>
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
                        <li><a href="GSO.php?rapp=a"><span>Reservation Approved</span></a></li>
                        <li><a href="GSO.php?creq=a"><span>Cancelled Requests</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="report">
                     <img src="PNG/File.png" alt="Report">
                     <span>Report</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?srep=a"><span>Summary Report</span></a></li>
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
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="calendar">
                     <img src="PNG/Calendar.png" alt="Calendar">
                     <span>Calendar</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?vsch=a"><span>Vehicle Schedules</span></a></li>
                        <li><a href="GSO.php?vres=a"><span>Vehicle Reservation Form</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="requests">
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
                        <li><a href="GSO.php?papp=a"><span>Pending Approval </span>
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
                        <li><a href="GSO.php?rapp=a"><span>Reservation Approved</span></a></li>
                        <li><a href="GSO.php?creq=a"><span>Cancelled Requests</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="vehicle">
                     <img src="PNG/Vehicle.png" alt="Vehicle">
                     <span>Vehicles</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?mveh=a"><span>Manage Vehicle</span></a></li>
                        <li><a href="GSO.php?aveh=a"><span>Add Vehicle</span></a></li>
                        <li><a href="GSO.php?mche=a"><span>Maintenance Checklist</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="account">
                     <img src="PNG/Account.png" alt="Report">
                     <span>Accounts</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?macc=a"><span>Manage Accounts</span></a></li>
                        <li><a href="GSO.php?mdep=a"><span>Manage Departments</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="report">
                     <img src="PNG/File.png" alt="Report">
                     <span>Report</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?srep=a"><span>Summary Report</span></a></li>
                        <li><a href="GSO.php?mrep=a"><span>Maintenance Report</span></a></li>
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
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="calendar">
                     <img src="PNG/Calendar.png" alt="Calendar">
                     <span>Calendar</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?vsch=a"><span>Vehicle Schedules</span></a></li>
                        <li><a href="GSO.php?vres=a"><span>Vehicle Reservation Form</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="requests">
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
                        <li><a href="GSO.php?papp=a"><span>Pending Approval </span>
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
                        <li><a href="GSO.php?rapp=a"><span>Reservation Approved</span></a></li>
                        <li><a href="GSO.php?creq=a"><span>Cancelled Requests</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="vehicle">
                     <img src="PNG/Vehicle.png" alt="Vehicle">
                     <span>Vehicles</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?mveh=a"><span>Manage Vehicle</span></a></li>
                        <li><a href="GSO.php?aveh=a"><span>Add Vehicle</span></a></li>
                        <li><a href="GSO.php?mche=a"><span>Maintenance Checklist</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="account">
                     <img src="PNG/Account.png" alt="Report">
                     <span>Accounts</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?macc=a"><span>Manage Accounts</span></a></li>
                        <li><a href="GSO.php?mdep=a"><span>Manage Departments</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="report">
                     <img src="PNG/File.png" alt="Report">
                     <span>Report</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?srep=a"><span>Summary Report</span></a></li>
                        <li><a href="GSO.php?mrep=a"><span>Maintenance Report</span></a></li>
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
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="calendar">
                     <img src="PNG/Calendar.png" alt="Calendar">
                     <span>Calendar</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?vsch=a"><span>Vehicle Schedules</span></a></li>
                        <li><a href="GSO.php?vres=a"><span>Vehicle Reservation Form</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="requests">
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
                        <li><a href="GSO.php?papp=a"><span>Pending Approval </span>
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
                        <li><a href="GSO.php?rapp=a"><span>Reservation Approved</span></a></li>
                        <li><a href="GSO.php?creq=a"><span>Cancelled Requests</span></a></li>
                     </div>
                  </ul>
               </li
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="report">
                     <img src="PNG/File.png" alt="Report">
                     <span>Report</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?srep=a"><span>Summary Report</span></a></li>
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
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="calendar">
                     <img src="PNG/Calendar.png" alt="Calendar">
                     <span>Calendar</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?vsch=a"><span>Vehicle Schedules</span></a></li>
                        <li><a href="GSO.php?vres=a"><span>Vehicle Reservation Form</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="report">
                     <img src="PNG/File.png" alt="Report">
                     <span>Report</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?srep=a"><span>Summary Report</span></a></li>
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
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="vehicle">
                     <img src="PNG/Vehicle.png" alt="Vehicle">
                     <span>Vehicles</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?mche=a"><span>Maintenance Checklist</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="report">
                     <img src="PNG/File.png" alt="Report">
                     <span>Report</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?srep=a"><span>Summary Report</span></a></li>
                        <li><a href="GSO.php?mrep=a"><span>Maintenance Report</span></a></li>
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
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="calendar">
                     <img src="PNG/Calendar.png" alt="Calendar">
                     <span>Calendar</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?vsch=a"><span>Vehicle Schedules</span></a></li>
                        <li><a href="GSO.php?vres=a"><span>Vehicle Reservation Form</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="requests">
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
                        <li><a href="GSO.php?papp=a"><span>Pending Approval </span>
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
                        <li><a href="GSO.php?creq=a"><span>Cancelled Requests</span>
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
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="vehicle">
                     <img src="PNG/Vehicle.png" alt="Vehicle">
                     <span>Vehicles</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?mveh=a"><span>Manage Vehicle</span></a></li>
                        <li><a href="GSO.php?aveh=a"><span>Add Vehicle</span></a></li>
                        <li><a href="GSO.php?mche=a"><span>Maintenance Checklist</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="account">
                     <img src="PNG/Account.png" alt="Report">
                     <span>Accounts</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?macc=a"><span>Manage Accounts</span></a></li>
                        <li><a href="GSO.php?mdep=a"><span>Manage Departments</span></a></li>
                     </div>
                  </ul>
               </li>
               <li>
                  <button onclick="toggleDropdown(this)" class="dropdown-btn" id="report">
                     <img src="PNG/File.png" alt="Report">
                     <span>Report</span>
                     <img src="PNG/Down.png" alt="DropDown">
                  </button>
                  <ul class="dropdown-container">
                     <div>
                        <li><a href="GSO.php?srep=a"><span>Summary Report</span></a></li>
                        <li><a href="GSO.php?mrep=a"><span>Maintenance Report</span></a></li>
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

    <a href="index.php">
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
            $defaultTime = 12000; // 3 seconds for reservation approved
         } elseif (isset($_GET["creq"]) and !empty($_GET["creq"])) {
            $defaultTime = 12000; // 3 seconds for cancelled requests
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
</body>
</html>
<?php
function home()
{
    if ($_SESSION['role'] == 'User') {
      include 'calendar.php';
    } elseif (in_array($_SESSION['role'], ['Secretary', 'Admin', 'Director'])) {
      include 'home_sec.php';
    } else {
      include 'calendar.php';
    }

   }
   function vehicleReservationForm()
   {
      ?>
         <div class="vres">
            <form class="vehicle-reservation-form" action="" method="post" enctype="multipart/form-data">
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
                     <div class="input-container">
                        <?php
                           if ($_SESSION['role'] != 'Secretary') 
                           {
                              ?>
                                 <input name="vrfname" value="<?php if($_SESSION['role']!="Secretary") {echo $_SESSION['fname']." ".$_SESSION['lname'];}?>" type="text" id="name" readonly> 
                              <?php
                           }
                           else
                           {
                              ?>
                                 <select name="vrfname" id="department" required>
                                    <option value="" disabled selected></option>
                                    <?php
                                       include 'config.php';
                                       $selectdepartment = "SELECT * FROM usertb ORDER BY lname ASC";
                                       $resultdepartment = $conn->query($selectdepartment);
                                       if ($resultdepartment->num_rows > 0) {
                                          while($rowdepartment = $resultdepartment->fetch_assoc()) {
                                             echo "<option value='".$rowdepartment['lname'].", ".$rowdepartment['fname']."'>".$rowdepartment['lname'].", ".$rowdepartment['fname']."</option>";
                                          }
                                       }
                                    ?>
                                 </select>
                              <?php    
                           }
                        ?>
                        
                        <label for="name">NAME:</label>
                     </div>
                     <div class="input-container">
                        <?php
                           if ($_SESSION['role'] != 'Secretary') 
                           {
                              ?>
                                 <input name="vrfdepartment" value="<?php echo $_SESSION['department'] ?>" type="text" id="department" readonly>
                              <?php
                           }
                           else
                           {
                              ?>
                                 <select name="vrfdepartment" id="department" required>
                                    <option value="" disabled selected></option>
                                    <?php
                                       include 'config.php';
                                       $selectdepartment = "SELECT * FROM departmentstb ORDER BY department ASC";
                                       $resultdepartment = $conn->query($selectdepartment);
                                       if ($resultdepartment->num_rows > 0) {
                                          while($rowdepartment = $resultdepartment->fetch_assoc()) {
                                             echo "<option value='".$rowdepartment['department']."'>".$rowdepartment['department']."</option>";
                                          }
                                       }
                                    ?>
                                 </select>
                              <?php    
                           }
                        ?>
                        <label for="department">DEPARTMENT:</label>
                     </div>
                     <div class="input-container">
                        <input name="vrfactivity" type="text" id="activity" required>
                        <label for="activity">ACTIVITY:</label>
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
                  <div class="vrf-details-column">
                     <div class="input-container">
                        <input style="" name="vrfdate_filed" type="date" value="<?php echo date("Y-m-d"); ?>" id="dateFiled" required readonly>
                        <label for="dateFiled">DATE FILED:</label>
                     </div>
                     <div class="input-container">
                        <input name="vrfbudget_no" type="number" id="budgetNo">
                        <label for="budgetNo">BUDGET No.:</label>
                     </div>
                     <div class="input-container">
                        <?php
                           if ($_SESSION['role'] != 'Secretary') {
                              ?>
                                 <button type=button style="border:0;"><input style="cursor: not-allowed;" name=" vrfdriver" type="text" id="vehicelUsed" readonly></button>
                              <?php
                           }
                           else
                           {
                              ?>
                                 <select name="vrfvehicle" id="vehicleUsed" required>
                                    <option value="" disabled selected></option>
                                 </select>
                                 <script>
                                    const vehicles = [
                                       <?php
                                       include("config.php");
                                       $selectvehicle = "SELECT * FROM carstb";
                                       $resultvehicle = $conn->query($selectvehicle);
                                       $vehicleArray = [];

                                       if ($resultvehicle->num_rows > 0) {
                                          while($rowvehicle = $resultvehicle->fetch_assoc()) {
                                             $plate_number = $rowvehicle['plate_number'];
                                             $brand = addslashes($rowvehicle['brand']);
                                             $model = addslashes($rowvehicle['model']);

                                             $vehicleArray[] = "{ plate_number: \"$plate_number\", brand: \"$brand\", model: \"$model\" }";
                                          }
                                       }

                                       echo implode(",\n", $vehicleArray);
                                       ?>
                                    ];
                                 </script>
                              <?php
                           }
                        ?>
                        <label for="vehicleUsed">VEHICLE TO BE USED:</label>
                     </div>
                     <div class="input-container">
                        <?php
                           if ($_SESSION['role'] != 'Secretary') {
                              ?>
                                 <button type=button style="border:0;"><input style="cursor: not-allowed;" name=" vrfdriver" type="text" id="vehicelUsed" readonly></button>
                              <?php
                           }
                           else
                           {
                              ?>
                                 <select name="vrfdriver" id="driver" required>
                                    <option value="" disabled selected></option>
                                    <?php
                                       include 'config.php';
                                       $selectdriver = "SELECT * FROM usertb WHERE role='Driver' ORDER BY fname ASC";
                                       $resultdriver = $conn->query($selectdriver);
                                       if ($resultdriver->num_rows > 0) {
                                          while($rowdriver = $resultdriver->fetch_assoc()) {
                                             echo "<option value='"."Mr. ".$rowdriver['fname']." ".$rowdriver['lname']."'>"."Mr. ".$rowdriver['fname']." ".$rowdriver['lname']."</option>";
                                          }
                                       }
                                    ?>
                                 </select>
                              <?php
                           }
                        ?>
                        <label for="driver">DRIVER:</label>
                     </div>
                  </div>
               </div>
               <span class="address">
                  <span>DESTINATION (PLEASE SPECIFY PLACE AND ADDRESS):</span>
                  <textarea name="vrfdestination" maxlength="255" type="text" id="destination" required></textarea>
               </span>
               <div class="vrf-details" style="margin-top:13px;">
                  <div class="input-container">
                     <?php
                        if ($_SESSION['role'] == 'Admin') {
                           $date=date("Y-m-d\T06:00");
                        }
                        else
                        {
                           $date=date("Y-m-d\T06:00", strtotime("+7 days"));
                        }
                     ?>
                     <input name="vrfdeparture" value="<?php echo $date; ?>" type="datetime-local" id="departureDate" required min='<?php echo $date; ?>'>
                     <label for="departureDate">DATE/TIME OF DEPARTURE:</label>
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
                  <span class="address" style="margin-top:-20px">
                     <span style="text-align:center;">TRANSPORTATION COST</span>
                     <span style="transform: translateX(60px);">
                        <textarea style="cursor:not-allowed;" name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost" readonly></textarea>
                        <div class="input-container">
                           <a href="#"><input name="vrftotal_cost" type="number" id="totalCost"  style="padding-left:17px;cursor: not-allowed;" step="0.01" min="0" readonly></a>
                           <label for="total_cost" style="margin-left:13px">TOTAL COST</label>
                           <div>
                              <label id="pesoSign">₱</label>
                           </div>
                        </div>
                     </span>
                     <div class="subbtn-container">
                        <input type="file" name="vrfletter_attachment" class="attachment" id="fileInput">
                        <label for="fileInput" class="attachment-label"><img class="attachment-img" src="PNG/File.png" for="fileInput" alt="">LETTER ATTACHMENT</label>
                        <button class="subbtn" type="submit" name="vrfsubbtn">Submit</button>
                     </div>
                  </span>
               </div>
            </form>
            <script>
            // Map plate number last digit to coding day
            function getCodingDay(plateNumber) {
               const lastDigit = plateNumber.trim().slice(-1);
               switch (lastDigit) {
                  case '1':
                  case '2':
                        return 'Monday';
                  case '3':
                  case '4':
                        return 'Tuesday';
                  case '5':
                  case '6':
                        return 'Wednesday';
                  case '7':
                  case '8':
                        return 'Thursday';
                  case '9':
                  case '0':
                        return 'Friday';
                  default:
                        return 'Invalid';
               }
            }

            // Listen to departureDate changes
            document.getElementById('departureDate').addEventListener('change', function() {
               const selectedDate = this.value;
               const selectedDay = new Date(selectedDate).toLocaleDateString('en-US', { weekday: 'long' });

               const vehicleSelect = document.getElementById('vehicleUsed');

               // Clear existing options
               vehicleSelect.innerHTML = '<option value="" disabled selected></option>';

               // Loop through vehicles and add only allowed ones
               vehicles.forEach(vehicle => {
                  const codingDay = getCodingDay(vehicle.plate_number);

                  if (codingDay !== selectedDay) {
                        const option = document.createElement('option');
                        option.value =  vehicle.brand + " " + vehicle.model;
                        option.textContent = vehicle.brand + " " + vehicle.model;
                        vehicleSelect.appendChild(option);
                  }
               });
            });
            </script>
            <?php
               include 'config.php';
               if (isset($_POST['vrfsubbtn'])) {
                   $id = htmlspecialchars($_POST['vrfid']);
                   $name = htmlspecialchars($_POST['vrfname']);
                   $department = htmlspecialchars($_POST['vrfdepartment']);
                   $activity = htmlspecialchars($_POST['vrfactivity']);
                   $purpose = htmlspecialchars($_POST['vrfpurpose']);
                   $date_filed = htmlspecialchars($_POST['vrfdate_filed']);
                   $budget_no = htmlspecialchars($_POST['vrfbudget_no']);
                   $vehicle = isset($_POST['vrfvehicle']) && !empty($_POST['vrfvehicle']) ? htmlspecialchars($_POST['vrfvehicle']) : null;
                   $driver = isset($_POST['vrfdriver']) && !empty($_POST['vrfdriver']) ? htmlspecialchars($_POST['vrfdriver']) : null;
                   $destination = htmlspecialchars($_POST['vrfdestination']);
                   $departure = htmlspecialchars($_POST['vrfdeparture']);
                   $transportation_cost = htmlspecialchars($_POST['vrftransportation_cost']);
                   $passenger_count = isset($_POST['vrfpassenger_count']) ? htmlspecialchars($_POST['vrfpassenger_count']) : null;
               
                   // File upload directory
                   $targetDir = "uploads/";
                   if (!is_dir($targetDir)) {
                       mkdir($targetDir, 0777, true);
                   }
               
                   $allowedTypes = ['docx', 'pdf'];
               
                   // Handle letter attachment (optional)
                   $letterFileName = null;
                   if (!empty($_FILES["vrfletter_attachment"]["name"])) {
                       $letterFileName = basename($_FILES["vrfletter_attachment"]["name"]);
                       $letterFilePath = $targetDir . $letterFileName;
                       $letterFileType = strtolower(pathinfo($letterFilePath, PATHINFO_EXTENSION));
               
                       if (!in_array($letterFileType, $allowedTypes)) {
                           echo "<script>
                                   alert('Invalid file type for letter attachment. Only Word Documents or PDFs are allowed.');
                                   window.history.back();
                                 </script>";
                           exit;
                       }
               
                       if (!move_uploaded_file($_FILES["vrfletter_attachment"]["tmp_name"], $letterFilePath)) {
                           echo "<script>
                                   alert('Failed to upload the letter attachment.');
                                   window.history.back();
                                 </script>";
                           exit;
                       }
                   }
               
                   // Handle passenger attachment (optional)
                   $passengerFileName = null;
                   if (!empty($_FILES["vrfpassenger_attachment"]["name"])) {
                       $passengerFileName = basename($_FILES["vrfpassenger_attachment"]["name"]);
                       $passengerFilePath = $targetDir . $passengerFileName;
                       $passengerFileType = strtolower(pathinfo($passengerFilePath, PATHINFO_EXTENSION));
               
                       if (!in_array($passengerFileType, $allowedTypes)) {
                           echo "<script>
                                   alert('Invalid file type for passenger attachment. Only Word Documents or PDFs are allowed.');
                                   window.history.back();
                                 </script>";
                           exit;
                       }
               
                       move_uploaded_file($_FILES["vrfpassenger_attachment"]["tmp_name"], $passengerFilePath);
                   }
               
                   try {
                       // Insert into vrftb
                       $stmt = $conn->prepare("INSERT INTO vrftb 
                           (id, name, department, activity, purpose, date_filed, budget_no, vehicle, driver, destination, departure, transportation_cost, passenger_count, letter_attachment, passenger_attachment) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                       $stmt->bind_param(
                           "sssssssssssssss", 
                           $id, $name, $department, $activity, $purpose, $date_filed, $budget_no, $vehicle, $driver, $destination, $departure, $transportation_cost, $passenger_count, $letterFileName, $passengerFileName
                       );
                       $stmt->execute();
               
                       // Insert passengers if provided
                       if (!empty($_POST['vrfpassenger_name']) && !empty($_POST['vrfid'])) {
                           $stmt = $conn->prepare("INSERT INTO passengerstb (vrfid, passenger_name) VALUES (?, ?)");
                           foreach ($_POST['vrfpassenger_name'] as $passenger_name) {
                               $stmt->bind_param("ss", $id, $passenger_name);
                               $stmt->execute();
                           }
                       }
               
                       // Count and update passenger count if not provided
                       if (empty($_POST['vrfpassenger_count'])) {
                           $stmt = $conn->prepare("SELECT COUNT(*) AS passenger_count FROM passengerstb WHERE vrfid = ?");
                           $stmt->bind_param("s", $id);
                           $stmt->execute();
                           $result = $stmt->get_result();
                           $row = $result->fetch_assoc();
                           $passenger_count = $row['passenger_count'];
               
                           // Update vrftb with passenger count
                           $stmt = $conn->prepare("UPDATE vrftb SET passenger_count = ? WHERE id = ?");
                           $stmt->bind_param("is", $passenger_count, $id);
                           $stmt->execute();
                       }
               
                       echo "<script>alert('Reservation submitted!');</script>";
                       exit;
               
                   } catch (Exception $e) {
                       echo "<script>
                               alert('Error: " . addslashes($e->getMessage()) . "');
                               window.history.back();
                             </script>";
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
         <input class="search" type="text" id="search" placeholder="Search reservation">
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
                                 <div><div class="title">Activity:</div><div class="dikoalam"><?php echo $rowvrf['activity']; ?></div></div>
                                 <div><div class="title">Purpose:</div><div class="dikoalam"><?php echo $rowvrf['purpose']; ?></div></div>
                                 <div><div class="title">Budget No.:</div><div class="dikoalam"><?php echo $rowvrf['budget_no']; ?></div></div>
                              </div>
                              <div>
                                 <div><div class="title">Departure Date:</div><div class="dikoalam"><?php echo (new DateTime($rowvrf['departure']))->format("F j, Y"); ?></div></div>
                                 <div><div class="title">Departure Time:</div><div class="dikoalam"><?php echo (new DateTime($rowvrf['departure']))->format("g:iA"); ?></div></div>
                                 <div><div class="title">Destination:</div><div class="dikoalam"><?php echo $rowvrf['destination']; ?></div></div>
                              </div>
                              <div>
                                 <div><div class="title">Driver:</div><div class="dikoalam">
                                    <?php 
                                       $employeeid = $rowvrf['driver'];
                                       $selectdriver = "SELECT * FROM usertb WHERE employeeid = '$employeeid'";
                                       $resultdriver = $conn->query($selectdriver);
                                       if ($resultdriver->num_rows > 0) {
                                          $rowdriver = $resultdriver->fetch_assoc();
                                          echo "Mr. ".$rowdriver['fname']." ".$rowdriver['lname'];
                                       } else {
                                          echo $rowvrf['driver'];
                                       } 
                                    ?>
                                 </div></div>
                                 <div><div class="title">Vehicle to be used:</div><div class="dikoalam">
                                    <?php 
                                       echo $rowvrf['vehicle']; 
                                    ?>
                                 </div></div>
                                 <div><div class="title">Passenger count:</div><div class="dikoalam"><?php echo $rowvrf['passenger_count'] ?></div></div>
                              </div>
                           </div>
                        </div>
                     </a>
                     <div id="vrespopup">
                        <div class="vres">
                           <form class="vehicle-reservation-form" action="" method="post" enctype="multipart/form-data">
                              <a href="GSO.php?papp=a" class="closepopup">×</a>
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
                                    <div class="input-container">
                                       <input type="text" name="vrfpurpose" value="<?php echo $rowvrfid['purpose'] ?>" id="purpose" required readonly>
                                       <label for="purpose">PURPOSE:</label>
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
                                    <?php
                                       if (!in_array($_SESSION['role'], ['Secretary', 'Director'])) {
                                          ?>
                                             <div class="input-container">
                                                <?php
                                                   if($rowvrf['vehicle'] == "" OR $rowvrf['vehicle'] == null)
                                                   {
                                                      ?>
                                                         <a href="#vrespopup">      
                                                      <?php
                                                   }
                                                ?>
                                                   <input type="text" name="vrfvehicle" value="<?php 
                                                   $plate_number = $rowvrfid['vehicle']; 
                                                   $selectvehicle = "SELECT * FROM carstb WHERE plate_number = '$plate_number'";
                                                   $resultvehicle = $conn->query($selectvehicle);
                                                   if ($resultvehicle->num_rows > 0) {
                                                      $rowvehicle = $resultvehicle->fetch_assoc();
                                                      echo $rowvehicle['brand']." ".$rowvehicle['model'];
                                                   } else {
                                                      echo $rowvrfid['vehicle'];
                                                   }
                                                   ?>" placeholder=" " id="vehicleUsed" readonly>
                                                <?php
                                                   if($rowvrf['vehicle'] == "" OR $rowvrf['vehicle'] == null)
                                                   {
                                                      ?>
                                                         </a>     
                                                      <?php
                                                   }
                                                ?>
                                                <label for="vehicleUsed">VEHICLE TO BE USED:</label>
                                             </div>
                                             <div class="input-container">
                                                <?php
                                                   if($rowvrf['vehicle'] == "" OR $rowvrf['vehicle'] == null)
                                                   {
                                                      ?>
                                                         <a href="#vrespopup">      
                                                      <?php
                                                   }
                                                ?>
                                                   <input type="text" name="vrfdriver" value="<?php 
                                                   $employeeid = $rowvrfid['driver'];
                                                   $selectdriver = "SELECT * FROM usertb WHERE employeeid = '$employeeid'";
                                                   $resultdriver = $conn->query($selectdriver);
                                                   if ($resultdriver->num_rows > 0) {
                                                      $rowdriver = $resultdriver->fetch_assoc();
                                                      echo "Mr. ".$rowdriver['fname']." ".$rowdriver['lname'];
                                                   } else {
                                                      echo $rowvrfid['driver'];
                                                   }
                                                   ?>" id="driver" readonly>
                                                <?php
                                                   if($rowvrf['vehicle'] == "" OR $rowvrf['vehicle'] == null)
                                                   {
                                                      ?>
                                                         </a>     
                                                      <?php
                                                   }
                                                ?>
                                                <label for="driver">DRIVER:</label>
                                             </div>
                                          <?php
                                       }
                                       else
                                       {
                                          ?>
                                             <div class="input-container">
                                                <select name="vrfvehicle" id="vehicleUsed" required>
                                                   <?php
                                                      if ($_SESSION['role'] == "Secretary") {
                                                         ?>
                                                            <option value="" disabled selected></option>
                                                         <?php
                                                      }
                                                      else {
                                                         ?>
                                                            <option value="<?php echo $rowvrfid['vehicle']; ?>"><?php echo $rowvrfid['vehicle']; ?></option>
                                                         <?php
                                                      }
                                                      include("config.php");
                                                      if ($_SESSION['role'] == "Secretary") {
                                                         $vehicle = $rowvrfid['vehicle'];
                                                         $stmt = $conn->prepare("SELECT * FROM carstb");
                                                      }
                                                      elseif ($_SESSION['role'] == "Director") {
                                                         $vehicle = $rowvrfid['vehicle'];
                                                         $stmt = $conn->prepare("SELECT * FROM carstb WHERE CONCAT(brand, ' ', model) != ?");
                                                         $stmt->bind_param("s", $vehicle);
                                                      }
                                                      $stmt->execute();
                                                      $resultvehicle = $stmt->get_result();
                                                      if ($resultvehicle->num_rows > 0) {
                                                         while($rowvehicle = $resultvehicle->fetch_assoc()) {
                                                            $departure=$rowvrf['departure'];
                                                            $plate_number=$rowvehicle['plate_number'];
                                                            $day = date('l', strtotime($departure));
                                                            $last_digit=substr(str_replace(' ', '', $plate_number), -1);
                                                            switch ($last_digit) {
                                                               case '1':
                                                               case '2':
                                                                  $coding_day = 'Monday';
                                                                  break;
                                                               case '3':
                                                               case '4':
                                                                  $coding_day = 'Tuesday';
                                                                  break;
                                                               case '5':
                                                               case '6':
                                                                  $coding_day = 'Wednesday';
                                                                  break;
                                                               case '7':
                                                               case '8':
                                                                  $coding_day = 'Thursday';
                                                                  break;
                                                               case '9':
                                                               case '0':
                                                                  $coding_day = 'Friday';
                                                                  break;
                                                               default:
                                                                  $coding_day = 'Invalid'; // Safety check
                                                                  break;
                                                            }
                                                            if ($day != $coding_day) {
                                                               ?>
                                                                  <option value="<?php echo $rowvehicle['brand']." ".$rowvehicle['model']; ?>"><?php echo $rowvehicle['brand']." ".$rowvehicle['model']; ?></option>
                                                               <?php
                                                            }
                                                         }
                                                      }
                                                   ?>
                                                </select>
                                                <label for="vehicleUsed">VEHICLE TO BE USED:</label>
                                             </div>
                                             <div class="input-container">
                                                <select name="vrfdriver" id="driver" required>
                                                   <?php
                                                      if ($_SESSION['role'] == "Secretary") {
                                                         ?>
                                                            <option value="" disabled selected></option>
                                                         <?php
                                                      }
                                                      else {
                                                         ?>
                                                            <option value="<?php echo $rowvrfid['driver']; ?>"><?php echo $rowvrfid['driver']; ?></option>
                                                         <?php
                                                      }
                                                      include 'config.php';
                                                      if ($_SESSION['role'] == "Secretary") {
                                                         $driver = $rowvrfid['driver'];
                                                         $stmt = $conn->prepare("SELECT * FROM userstb");
                                                      }
                                                      elseif ($_SESSION['role'] == "Director") {
                                                         $driver = $rowvrfid['driver'];
                                                         $stmt = $conn->prepare("SELECT * FROM userstb WHERE CONCAT('Mr. ', fname, ' ', lname) != ?");
                                                         $stmt->bind_param("s", $driver);
                                                      }
                                                      $stmt->execute();
                                                      $resultdriver = $stmt->get_result();
                                                      if ($resultdriver->num_rows > 0) {
                                                         while($rowdriver = $resultdriver->fetch_assoc()) {
                                                            ?>
                                                            <option value="<?php echo "Mr. ".$rowdriver['fname']." ".$rowdriver['lname']; ?>">
                                                               <?php echo "Mr. ".$rowdriver['fname']." ".$rowdriver['lname']; ?>
                                                            </option>
                                                            <?php
                                                         }
                                                      }
                                                   ?>
                                                </select>
                                                <label for="driver">DRIVER:</label>
                                             </div>
                                          <?php
                                       }
                                    ?>
                                 </div>
                              </div>
                              <span class="address">
                                 <span>DESTINATION (PLEASE SPECIFY PLACE AND ADDRESS):</span>
                                 <textarea name="vrfdestination" maxlength="255" type="text"  id="destination" required readonly><?php echo $rowvrfid['destination'] ?></textarea>
                              </span>
                              <div class="vrf-details" style="margin-top:13.33px;">
                                 <div class="input-container">
                                    <input name="vrfdeparture" value="<?php echo $rowvrfid['departure']; ?>" type="datetime-local" id="departureDate" required readonly>
                                    <label for="departureDate">DATE/TIME OF DEPARTURE:</label>
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
                                 <span class="address" style="margin-top:-1.8vw">
                                    <span style="text-align:center">TRANSPORTATION COST</span>
                                    <?php
                                       if($_SESSION['role'] == "Accountant")
                                       {
                                          ?>
                                             <span style="transform: translateX(60px);">
                                             <textarea name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost" required></textarea>
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
                                             <textarea name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost" readonly><?php echo $rowvrf['transportation_cost'] ?></textarea>
                                             <div class="input-container">   
                                                <?php
                                                   if($rowvrf['total_cost'] == 0.00)
                                                   {
                                                      ?>
                                                         <a href="#vrespopup">      
                                                      <?php
                                                   }
                                                ?>
                                                   <input name="vrftotal_cost" type="number" id="totalCost" value="<?php if($rowvrf['total_cost'] == 0.00) {echo"";} else {echo $rowvrf['total_cost'];}?>" style="padding-left:1.5vw;" step="0.01" min="0" readonly>
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
                                                   <label <?php if($rowvrf['total_cost'] != 0.00)echo "style=\"visibility:visible;\"" ?> id="pesoSign">₱</label>
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
                        </div>
                     </div>
                  <?php
               }
               if ($_SERVER["REQUEST_METHOD"] == "POST") {
                  if (isset($_POST['vrfappbtn'])) {
                     $id = htmlspecialchars($_GET['vrfid']);
                     if($_SESSION['role']=='Immediate Head' OR $_SESSION['role']=='Director')
                     {
                        $updateStatus = "UPDATE vrftb SET $status='Approved' WHERE id = ?";
                     }
                     elseif($_SESSION['role']=='Secretary' OR $_SESSION['role']=='Director')
                     {
                        $vehicle = $_POST['vrfvehicle'];
                        $driver = $_POST['vrfdriver'];
                        $updateStatus = "UPDATE vrftb SET vehicle='$vehicle', driver='$driver', $status='Approved' WHERE id = ?";
                     }
                     elseif($_SESSION['role']=='Accountant')
                     {
                        $total_cost = $_POST['vrftotal_cost'];
                        $transportation_cost = $_POST['vrftransportation_cost'];
                        $updateStatus = "UPDATE vrftb SET transportation_cost='$transportation_cost', total_cost='$total_cost', $status='Approved' WHERE id = ?";
                     }
                     $stmt = $conn->prepare($updateStatus);
                     if ($stmt) {
                        $stmt->bind_param("s", $id);
                        $stmt->execute();
                        $stmt->close();
                        echo "<script>
                                 alert('Reservation approved');
                                 window.history.back();
                              </script>";
                     } else {
                        echo "<script>
                                 alert('Error updating status.');
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
            }
         ?>
      <?php
   }
   function reservationApproved()
   {
      ?>
         <input class="search" type="text" id="search" placeholder="Search reservation">
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
         <?php
            $selectvrf = "SELECT * FROM vrftb WHERE gsoassistant_status='Approved' AND immediatehead_status='Approved' AND gsodirector_status='Approved' AND accounting_status='Approved' ORDER BY date_filed DESC, id DESC";
            $resultvrf = $conn->query($selectvrf);
            if ($resultvrf->num_rows > 0) {
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
                                 <div><div class="title">Activity:</div><div class="dikoalam"><?php echo $rowvrf['activity']; ?></div></div>
                                 <div><div class="title">Purpose:</div><div class="dikoalam"><?php echo $rowvrf['purpose']; ?></div></div>
                                 <div><div class="title">Budget No.:</div><div class="dikoalam"><?php echo $rowvrf['budget_no']; ?></div></div>
                              </div>
                              <div>
                                 <div><div class="title">Departure Date:</div><div class="dikoalam"><?php echo (new DateTime($rowvrf['departure']))->format("F j, Y"); ?></div></div>
                                 <div><div class="title">Departure Time:</div><div class="dikoalam"><?php echo (new DateTime($rowvrf['departure']))->format("g:iA"); ?></div></div>
                                 <div><div class="title">Destination:</div><div class="dikoalam"><?php echo $rowvrf['destination']; ?></div></div>
                              </div>
                              <div>
                                 <div><div class="title">Driver:</div><div class="dikoalam">
                                    <?php 
                                       $employeeid = $rowvrf['driver'];
                                       $selectdriver = "SELECT * FROM usertb WHERE employeeid = '$employeeid'";
                                       $resultdriver = $conn->query($selectdriver);
                                       if ($resultdriver->num_rows > 0) {
                                          $rowdriver = $resultdriver->fetch_assoc();
                                          echo $rowdriver['fname']." ".$rowdriver['lname'];
                                       } else {
                                          echo $rowvrf['driver'];
                                       } 
                                    ?>
                                 </div></div>
                                 <div><div class="title">Vehicle to be used:</div><div class="dikoalam"><?php echo $rowvrf['vehicle']; ?></div></div>
                                 <div><div class="title">Passenger count:</div><div class="dikoalam"><?php echo $rowvrf['passenger_count'] ?></div></div>
                              </div>
                           </div>
                        </div>
                     </a>
                     <div id="vrespopup">
                        <div class="vres">
                           <form class="vehicle-reservation-form" action="" method="post" enctype="multipart/form-data">
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
                                    <div class="input-container">
                                       <input type="text" name="vrfpurpose" value="<?php echo $rowvrfid['purpose'] ?>" id="purpose" required readonly>
                                       <label for="purpose">PURPOSE:</label>
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
                                    <?php
                                       if (!in_array($_SESSION['role'], ['Secretary', 'Director'])) {
                                          ?>
                                             <div class="input-container">
                                                <?php
                                                   if($rowvrf['vehicle'] == "" OR $rowvrf['vehicle'] == null)
                                                   {
                                                      ?>
                                                         <a href="#vrespopup">      
                                                      <?php
                                                   }
                                                ?>
                                                   <input type="text" name="vrfvehicle" value="<?php 
                                                   $plate_number = $rowvrfid['vehicle']; 
                                                   $selectvehicle = "SELECT * FROM carstb WHERE plate_number = '$plate_number'";
                                                   $resultvehicle = $conn->query($selectvehicle);
                                                   if ($resultvehicle->num_rows > 0) {
                                                      $rowvehicle = $resultvehicle->fetch_assoc();
                                                      echo $rowvehicle['brand']." ".$rowvehicle['model'];
                                                   } else {
                                                      echo $rowvrfid['vehicle'];
                                                   }
                                                   ?>" placeholder=" " id="vehicleUsed" readonly>
                                                <?php
                                                   if($rowvrf['vehicle'] == "" OR $rowvrf['vehicle'] == null)
                                                   {
                                                      ?>
                                                         </a>     
                                                      <?php
                                                   }
                                                ?>
                                                <label for="vehicleUsed">VEHICLE TO BE USED:</label>
                                             </div>
                                             <div class="input-container">
                                                <?php
                                                   if($rowvrf['vehicle'] == "" OR $rowvrf['vehicle'] == null)
                                                   {
                                                      ?>
                                                         <a href="#vrespopup">      
                                                      <?php
                                                   }
                                                ?>
                                                   <input type="text" name="vrfdriver" value="<?php 
                                                   $employeeid = $rowvrfid['driver'];
                                                   $selectdriver = "SELECT * FROM usertb WHERE employeeid = '$employeeid'";
                                                   $resultdriver = $conn->query($selectdriver);
                                                   if ($resultdriver->num_rows > 0) {
                                                      $rowdriver = $resultdriver->fetch_assoc();
                                                      echo "Mr. ".$rowdriver['fname']." ".$rowdriver['lname'];
                                                   } else {
                                                      echo $rowvrfid['driver'];
                                                   }
                                                   ?>" id="driver" readonly>
                                                <?php
                                                   if($rowvrf['vehicle'] == "" OR $rowvrf['vehicle'] == null)
                                                   {
                                                      ?>
                                                         </a>     
                                                      <?php
                                                   }
                                                ?>
                                                <label for="driver">DRIVER:</label>
                                             </div>
                                          <?php
                                       }
                                       else
                                       {
                                          ?>
                                             <div class="input-container">
                                                <select name="vrfvehicle" id="vehicleUsed" required>
                                                   <option value="<?php echo $rowvrfid['vehicle']; ?>"><?php echo $rowvrfid['vehicle']; ?></option>
                                                   <?php 
                                                      include("config.php");
                                                      $vehicle = $rowvrfid['vehicle'];
                                                      $stmt = $conn->prepare("SELECT * FROM carstb WHERE CONCAT(brand, ' ', model) != ?");
                                                      $stmt->bind_param("s", $vehicle);
                                                      $stmt->execute();
                                                      $resultvehicle = $stmt->get_result();
                                                      if ($resultvehicle->num_rows > 0) {
                                                         while($rowvehicle = $resultvehicle->fetch_assoc()) {
                                                            $departure=$rowvrf['departure'];
                                                            $plate_number=$rowvehicle['plate_number'];
                                                            $day = date('l', strtotime($departure));
                                                            $last_digit=substr(str_replace(' ', '', $plate_number), -1);
                                                            switch ($last_digit) {
                                                               case '1':
                                                               case '2':
                                                                  $coding_day = 'Monday';
                                                                  break;
                                                               case '3':
                                                               case '4':
                                                                  $coding_day = 'Tuesday';
                                                                  break;
                                                               case '5':
                                                               case '6':
                                                                  $coding_day = 'Wednesday';
                                                                  break;
                                                               case '7':
                                                               case '8':
                                                                  $coding_day = 'Thursday';
                                                                  break;
                                                               case '9':
                                                               case '0':
                                                                  $coding_day = 'Friday';
                                                                  break;
                                                               default:
                                                                  $coding_day = 'Invalid'; // Safety check
                                                                  break;
                                                            }
                                                            if ($day != $coding_day) {
                                                               ?>
                                                                  <option value="<?php echo $rowvehicle['brand']." ".$rowvehicle['model']; ?>"><?php echo $rowvehicle['brand']." ".$rowvehicle['model']; ?></option>
                                                               <?php
                                                            }
                                                         }
                                                      }
                                                   ?>
                                                </select>
                                                <label for="vehicleUsed">VEHICLE TO BE USED:</label>
                                             </div>
                                             <div class="input-container">
                                                <select name="vrfdriver" id="driver" required>   
                                                <option value="<?php echo $rowvrfid['driver']; ?>"><?php echo $rowvrfid['driver']; ?></option> 
                                                   <?php   
                                                      include 'config.php';
                                                      $driver = $rowvrfid['driver'];
                                                      $stmt = $conn->prepare("SELECT * FROM usertb WHERE role = 'Driver' AND CONCAT('Mr. ',fname, ' ', lname) != ?");
                                                      $stmt->bind_param("s", $driver);
                                                      $stmt->execute();
                                                      $resultdriver = $stmt->get_result();
                                                      if ($resultdriver->num_rows > 0) {
                                                         while($rowdriver = $resultdriver->fetch_assoc()) {
                                                            ?>
                                                               <option value="<?php echo "Mr. ".$rowdriver['fname']." ".$rowdriver['lname']; ?>"><?php echo "Mr. ".$rowdriver['fname']." ".$rowdriver['lname']; ?></option>
                                                            <?php
                                                         }
                                                      }
                                                   ?>
                                                </select>
                                                <label for="driver">DRIVER:</label>
                                             </div>
                                          <?php
                                       }
                                    ?>
                                 </div>
                              </div>
                              <span class="address">
                                 <span>DESTINATION (PLEASE SPECIFY PLACE AND ADDRESS):</span>
                                 <textarea name="vrfdestination" maxlength="255" type="text"  id="destination" required readonly><?php echo $rowvrfid['destination'] ?></textarea>
                              </span>
                              <div class="vrf-details" style="margin-top:1vw;">
                                 <div class="input-container">
                                    <input name="vrfdeparture" value="<?php echo $rowvrfid['departure']; ?>" type="datetime-local" id="departureDate" required readonly>
                                    <label for="departureDate">DATE/TIME OF DEPARTURE:</label>
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
                                 <span class="address" style="margin-top:-1.8vw">
                                    <!-- <span style="text-align:center;">TRANSPORTATION COST</span>
                                    <span style="transform: translateX(60px);">
                                       <textarea style="cursor:not-allowed;" name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost" readonly></textarea>
                                       <div class="input-container">
                                          <a href="#"><input name="vrftotal_cost" type="number" id="totalCost"  style="padding-left:1.3vw;cursor: not-allowed;" step="0.01" min="0" readonly></a>
                                          <label for="total_cost" style="margin-left:13px">TOTAL COST</label>
                                          <div>
                                             <label id="pesoSign">₱</label>
                                          </div>
                                       </div>
                                    </span> -->
                                    <span style="text-align:center">TRANSPORTATION COST</span>
                                    <span style="transform: translateX(60px);">
                                       <textarea name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost" readonly><?php echo $rowvrf['transportation_cost'] ?></textarea>
                                       <div class="input-container">   
                                          <?php
                                             if($rowvrf['total_cost'] == 0.00)
                                             {
                                                ?>
                                                   <a href="#vrespopup">      
                                                <?php
                                             }
                                          ?>
                                             <input name="vrftotal_cost" type="number" id="totalCost" value="<?php echo $rowvrf['total_cost']; ?>" style="padding-left:1.5vw;" step="0.01" min="0" readonly>
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
                                             <label <?php if($rowvrf['total_cost'] != 0.00)echo "style=\"visibility:visible;color:black;font-weight:100;\"" ?> id="pesoSign">₱</label>
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
                                       <?php
                                          if (in_array($_SESSION['role'], ['Secretary', 'Director'])) {
                                             ?>
                                                <button class="subbtn" type="submit" name="vrfeditbtn">Edit</button>
                                             <?php
                                          }
                                       ?>
                                    </div>
                                 </span>
                              </div>
                           </form>
                        </div>
                     </div>
                  <?php
               }
            }
         ?>
      <?php
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
         if (isset($_POST['vrfeditbtn']) AND isset($_GET['vrfid'])) {
            $id = htmlspecialchars($_GET['vrfid']); // ID from URL
            $vehicle = htmlspecialchars($_POST['vrfvehicle']); // Vehicle from form
            $driver = htmlspecialchars($_POST['vrfdriver']);   // Driver from form

            // Prepare and bind
            $stmt = $conn->prepare("UPDATE vrftb SET vehicle = ?, driver = ? WHERE id = ?");
            $stmt->bind_param("ssi", $vehicle, $driver, $id);

            if ($stmt->execute()) {
               echo "<script>alert('Edited successfully.');</script>";
            } else {
               echo "<script>alert('Error updating record: " . addslashes($stmt->error) . "');</script>";
            }
            $stmt->close();
         }
      }
   }
   function cancelledRequests()
   {
      ?>
         <input class="search" type="text" id="search" placeholder="Search reservation">
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
         <?php
            $selectvrf = "SELECT * FROM vrftb WHERE gsoassistant_status='Rejected' OR immediatehead_status='Rejected' OR gsodirector_status='Rejected' OR accounting_status='Rejected' OR user_cancelled='Yes' ORDER BY date_filed DESC, id DESC";
            $resultvrf = $conn->query($selectvrf);
            if ($resultvrf->num_rows > 0) {
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
                        ?> <div class="info-box" style="background-color:#eeeeee;"> <?php 
                     }
                        ?>
                           <div class="pending">
                              <?php
                                 if($rowvrf[$status] == "Pending" OR $rowvrf['user_cancelled'] == 'Yes')
                                 {
                                    echo '<div class="circle"></div>';
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
                                 <div><div class="title">Activity:</div><div class="dikoalam"><?php echo $rowvrf['activity']; ?></div></div>
                                 <div><div class="title">Purpose:</div><div class="dikoalam"><?php echo $rowvrf['purpose']; ?></div></div>
                                 <div><div class="title">Budget No.:</div><div class="dikoalam"><?php echo $rowvrf['budget_no']; ?></div></div>
                              </div>
                              <div>
                                 <div><div class="title">Departure Date:</div><div class="dikoalam"><?php echo (new DateTime($rowvrf['departure']))->format("F j, Y"); ?></div></div>
                                 <div><div class="title">Departure Time:</div><div class="dikoalam"><?php echo (new DateTime($rowvrf['departure']))->format("g:iA"); ?></div></div>
                                 <div><div class="title">Destination:</div><div class="dikoalam"><?php echo $rowvrf['destination']; ?></div></div>
                              </div>
                              <div>
                                 <div><div class="title">Driver:</div><div class="dikoalam"><?php echo $rowvrf['driver']; ?></div></div>
                                 <div><div class="title">Vehicle to be used:</div><div class="dikoalam"><?php echo $rowvrf['vehicle']; ?></div></div>
                                 <div><div class="title">Passenger count:</div><div class="dikoalam"><?php echo $rowvrf['passenger_count'] ?></div></div>
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
                                    <div class="input-container">
                                       <input type="text" name="vrfpurpose" value="<?php echo $rowvrfid['purpose'] ?>" id="purpose" required readonly>
                                       <label for="purpose">PURPOSE:</label>
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
                                       <?php
                                          if($rowvrf['vehicle'] == "" OR $rowvrf['vehicle'] == null)
                                          {
                                             ?>
                                                <a href="#vrespopup">      
                                             <?php
                                          }
                                       ?>
                                          <input type="text" name="vrfvehicle" value="<?php 
                                          $plate_number = $rowvrfid['vehicle']; 
                                          $selectvehicle = "SELECT * FROM carstb WHERE plate_number = '$plate_number'";
                                          $resultvehicle = $conn->query($selectvehicle);
                                          if ($resultvehicle->num_rows > 0) {
                                             $rowvehicle = $resultvehicle->fetch_assoc();
                                             echo $rowvehicle['brand']." ".$rowvehicle['model'];
                                          } else {
                                             echo $rowvrfid['vehicle'];
                                          }
                                          ?>" placeholder=" " id="vehicleUsed" readonly>
                                       <?php
                                          if($rowvrf['vehicle'] == "" OR $rowvrf['vehicle'] == null)
                                          {
                                             ?>
                                                </a>     
                                             <?php
                                          }
                                       ?>
                                       <label for="vehicleUsed">VEHICLE TO BE USED:</label>
                                    </div>
                                    <div class="input-container">
                                       <?php
                                          if($rowvrf['vehicle'] == "" OR $rowvrf['vehicle'] == null)
                                          {
                                             ?>
                                                <a href="#vrespopup">      
                                             <?php
                                          }
                                       ?>
                                          <input type="text" name="vrfdriver" value="<?php 
                                          $employeeid = $rowvrfid['driver'];
                                          $selectdriver = "SELECT * FROM usertb WHERE employeeid = '$employeeid'";
                                          $resultdriver = $conn->query($selectdriver);
                                          if ($resultdriver->num_rows > 0) {
                                             $rowdriver = $resultdriver->fetch_assoc();
                                             echo "Mr. ".$rowdriver['fname']." ".$rowdriver['lname'];
                                          } else {
                                             echo $rowvrfid['driver'];
                                          }
                                          ?>" id="driver" readonly>
                                       <?php
                                          if($rowvrf['vehicle'] == "" OR $rowvrf['vehicle'] == null)
                                          {
                                             ?>
                                                </a>     
                                             <?php
                                          }
                                       ?>
                                       <label for="driver">DRIVER:</label>
                                    </div>
                                 </div>
                              </div>
                              <span class="address">
                                 <span>DESTINATION (PLEASE SPECIFY PLACE AND ADDRESS):</span>
                                 <textarea name="vrfdestination" maxlength="255" type="text"  id="destination" required readonly><?php echo $rowvrfid['destination'] ?></textarea>
                              </span>
                              <div class="vrf-details" style="margin-top:1vw;">
                                 <div class="input-container">
                                    <input name="vrfdeparture" value="<?php echo $rowvrfid['departure']; ?>" type="datetime-local" id="departureDate" required readonly>
                                    <label for="departureDate">DATE/TIME OF DEPARTURE:</label>
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
                                 <span class="address" style="margin-top:-1.8vw">
                                    <span style="text-align:center">TRANSPORTATION COST</span>
                                    <span style="transform: translateX(60px);">
                                    <textarea name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost" readonly><?php echo $rowvrf['transportation_cost'] ?></textarea>
                                    <div class="input-container">   
                                       <?php
                                          if($rowvrfid['total_cost'] == 0.00)
                                          {
                                             ?>
                                                <a href="#vrespopup">      
                                             <?php
                                          }
                                       ?>
                                          <input name="vrftotal_cost" type="number" id="totalCost" value="<?php echo $rowvrfid['total_cost']; ?>" style="padding-left:1.5vw;" step="0.01" min="0" readonly>
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
                        </div>
                     </div>
                  <?php
               }
            }
         ?>
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
     
        if ($_SESSION['role'] == 'User') {
      include 'summary_all.php';
    } elseif (in_array($_SESSION['role'], ['Secretary', 'Admin', 'Director','Driver'])) {
      include 'summary_rep.php';
    } else {
      include 'summary_all.php';
    }

         
  
   }
   function maintenanceReport()
   {
      include 'maintenanceReport.php';
   }
?>