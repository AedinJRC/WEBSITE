<?php
   session_start();
   ob_start();
   $inactive = 3600; // 3600 Seconds = 1 Hour
   if(isset($_SESSION['timeout']) ) {
      $session_life = time() - $_SESSION['timeout'];
      if($session_life > $inactive) { 
         session_destroy(); 
         header("Location: logout.php"); 
      }
   }
   $_SESSION['timeout'] = time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>GSO</title>
   <link rel="stylesheet" href="styles.css">
   <style>
      <?php
         if(isset($_GET["papp"]) and !empty($_GET["papp"]))
         {
            ?> #requests { background-color: white; } <?php
         }
         elseif(isset($_GET["rapp"]) and !empty($_GET["rapp"]))
         {
            ?> #requests { background-color: white; } <?php
         }
         elseif(isset($_GET["creq"]) and !empty($_GET["creq"]))
         {
            ?> #requests { background-color: white; } <?php
         }
         elseif(isset($_GET["vres"]) and !empty($_GET["vres"]))
         {
            ?> #calendar { background-color: white; } <?php
         }
         elseif(isset($_GET["vsch"]) and !empty($_GET["vsch"]))
         {
            ?> #calendar { background-color: white; } <?php
         }
         elseif(isset($_GET["dsch"]) and !empty($_GET["dsch"]))
         {
            ?> #calendar { background-color: white; } <?php
         }
         elseif(isset($_GET["mrep"]) and !empty($_GET["mrep"]))
         {
            ?> #report { background-color: white; } <?php
         }
         elseif(isset($_GET["srep"]) and !empty($_GET["srep"]))
         {
            ?> #report { background-color: white; } <?php
         }
         else
         { 
            ?> #home div { background-color: white; } <?php
         }
      ?>
   </style>
   <script type="text/javascript" src="app.js" defer></script>
</head>
<body>
   <nav class="sidebar active" onclick="openSidebar()">
      <button onclick="toggleSidebar()" id="logo">
         <img src="PNG/GSO_Logo.png" alt="">
         <span class="logo">GSO</span>
      </button>
      <ul>
         <li id="home">
            <div>
               <a href="GSO.php" class="icon">
                  <img src="PNG/Home.png" alt="Home">
                  <span class="title">Home</span>
               </a>
            </div>
         </li>
         <li>
            <button onclick="toggleDropdown(this)" class="dropdown-btn" id="requests">
               <img src="PNG/Pie.png" alt="Requests">
               <span>Requests</span>
               <img src="PNG/Down.png" alt="DropDown">
            </button>
            <ul class="dropdown-container">
               <div>
                  <li><a href="GSO.php?papp=a"><span>Pending Approval</span></a></li>
                  <li><a href="GSO.php?rapp=a"><span>Reservation Approved</span></a></li>
                  <li><a href="GSO.php?creq=a"><span>Cancelled Requests</span></a></li>
               </div>
            </ul>
         </li>
         <li>
            <button onclick="toggleDropdown(this)" class="dropdown-btn" id="calendar">
               <img src="PNG/Calendar.png" alt="Calendar">
               <span>Calendar</span>
               <img src="PNG/Down.png" alt="DropDown">
            </button>
            <ul class="dropdown-container">
               <div>
                  <li><a href="GSO.php?vres=a"><span>Vehicle Reservation Form</span></a></li>
                  <li><a href="GSO.php?vsch=a"><span>Vehicle Schedules</span></a></li>
                  <li><a href="GSO.php?dsch=a"><span>Driver Schedules</span></a></li>
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
                  <li><a href="GSO.php?mrep=a"><span>Maintenance Report</span></a></li>
                  <li><a href="GSO.php?srep=a"><span>Summary Report</span></a></li>
               </div>
            </ul>
         </li>
      </ul>
   </nav>
   <main onclick="closeSidebar()">
      <?php
         if(isset($_GET["papp"]) and !empty($_GET["papp"]))
         pendingApproval();
         elseif(isset($_GET["rapp"]) and !empty($_GET["rapp"]))
         reservationApproved();
         elseif(isset($_GET["creq"]) and !empty($_GET["creq"]))
         cancelledRequests();
         elseif(isset($_GET["vres"]) and !empty($_GET["vres"]))
         vehicleReservationForm();
         elseif(isset($_GET["vsch"]) and !empty($_GET["vsch"]))
         vehicleSchedules();
         elseif(isset($_GET["dsch"]) and !empty($_GET["dsch"]))
         driverSchedules();
         elseif(isset($_GET["mrep"]) and !empty($_GET["mrep"]))
         maintenanceReport();
         elseif(isset($_GET["srep"]) and !empty($_GET["srep"]))
         summaryReport();
         else
         home();
      ?>
   </main>
</body>
</html>
<?php
   function home()
   {
      ?>
         <div class="home">
            <h1>Welcome to GSO</h1>
            <p>Government Service Office</p>
         </div>
      <?php
   }
   function pendingApproval()
   {
      ?>
         <h1>Pending Approval</h1>
         <span>New</span>
         <table>
            <tr>

            </tr>
            
         </table>
      <?php
   }
   function reservationApproved()
   {
      ?>
         
      <?php
   }
   function cancelledRequests()
   {
      ?>
         
      <?php
   }
   function vehicleReservationForm()
   {
      ?>
         
      <?php
   }
   function vehicleSchedules()
   {
      ?>
         
      <?php
   }
   function driverSchedules()
   {
      ?>
         
      <?php
   }
   function maintenanceReport()
   {
      ?>
         
      <?php
   }
   function summaryReport()
   {
      ?>
         
      <?php
   }
?>