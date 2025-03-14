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
            ?> #requests { background-color: white; font-weight: bold;} <?php
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
         elseif(isset($_GET["dveh"]) and !empty($_GET["dveh"]))
         {
            ?> #vehicle { background-color: white; font-weight: bold;} <?php
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
   <nav class="sidebar active" onclick="openSidebar()">
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
      <ul>
         <li style="height: 2.5rem;"></li>
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
               </div>
            </ul>
         </li>
      </ul>
      <div id="logout">
         <img id=profile src="PNG/Maynard.png" alt="Profile">
         <div id="profile-text">
            <span id="name">Rodriguez Maynard</span>
            <span id="role">Admin</span>
         </div>
         <a href="index.php">
            <button>
               <img id=logout-img src="PNG/Logout.png" alt="Logout">
            </button>
         </a>
      </div>
   </nav>
   <main onclick="closeSidebar()">
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
         elseif(isset($_GET["dsch"]) and !empty($_GET["dsch"]))
         driverSchedules();
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
         
      <?php
   }
   function vehicleReservationForm()
   {
      ?>
         <div class="vres">
            <form class="vehicle-reservation-form" action="GSO.php?vres=a" method="post">
               <img src="PNG/CSA_Logo.png" alt="">
               <span class="header">
                  <span id="csab">Colegio San Agustin-Biñan</span>
                  <span id="swe">Southwoods Ecocentrum, Brgy. San Francisco, 4024 Biñan City, Philippines</span>
                  <span id="vrf">VEHICLE RESERVATION FORM</span>
               </span>
               <div class="vrf-details">
                  <div class="vrf-details-column">
                     <div class="input-container">
                           <input type="text" id="name" required>
                           <label for="name">NAME:</label>
                     </div>
                     <div class="input-container">
                           <select id="department" required>
                              <option value="" disabled selected></option>
                              <option value="HR">HR</option>
                              <option value="IT">IT</option>
                              <option value="Finance">Finance</option>
                              <option value="Operations">Operations</option>
                           </select>
                           <label for="department">DEPARTMENT:</label>
                     </div>
                     <div class="input-container">
                           <input type="text" id="activity" required>
                           <label for="activity">ACTIVITY:</label>
                     </div>
                     <div class="input-container">
                           <input type="number" id="budgetNo" required>
                           <label for="budgetNo">BUDGET No.:</label>
                     </div>
                  </div>
                  <div class="vrf-details-column">
                     <div class="input-container">
                           <input type="date" id="dateFiled" required>
                           <label for="dateFiled">DATE FILED:</label>
                     </div>
                     <script>
                        const today = new Date().toISOString().split('T')[0];
                        document.getElementById("dateFiled").value = today;
                     </script>
                     <div class="input-container">
                           <input type="number" id="totalPassengers" required>
                           <label for="totalPassengers">TOTAL PASSENGER/S:</label>
                     </div>
                     <div class="input-container">
                           <select id="vehicleUsed" required>
                              <option value="" disabled selected></option>
                              <option value="Van">Van</option>
                              <option value="Bus">Bus</option>
                              <option value="Car">Car</option>
                              <option value="Truck">Truck</option>
                           </select>
                           <label for="vehicleUsed">VEHICLE TO BE USED:</label>
                     </div>
                     <div class="input-container">
                           <select id="driver" required>
                              <option value="" disabled selected></option>
                              <option value="Driver A">Driver A</option>
                              <option value="Driver B">Driver B</option>
                              <option value="Driver C">Driver C</option>
                           </select>
                           <label for="driver">DRIVER:</label>
                     </div>
                  </div>
               </div>
               <span class="address">
                  <span>DESTINATION (PLEASE SPECIFY PLACE AND ADDRESS):</span>
                  <input type="text" id="destination" required>
               </span>
            </form>
         </div>
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
   function addVehicle()
   {
      ?>
         
      <?php
   }
   function manageVehicle()
   {
      ?>
         
      <?php
   }
   function pendingApproval()
   {
      ?>
         <input type="text" id="search" placeholder="Search reservation">
         <div class="maintitle">
            <h1>Pending Approval</h1>
            <p>New</p>
         </div>
         <div class="info-box">
            <span class="time">1 hour ago</span>
            <div class="circle"> </div>
            <div class="info-heading">
               <img src="PNG/Maynard.png" alt="Profile">
               <span class="info-heading-text">
                  <span class="name">Maynard Rodriguez</span>
                  <span class="department">College Department</span>
                  <span class="date">Date: 12/04/2024</span>
               </span>
            </div>
            <p class="info-details">I am writing to confirm the transportation arrangements for the upcoming activity, [ACTIVITY], organized by the [DEPARTMENT]. The trip is scheduled for departure on [DATE / TIME DEPARTURE] to [DESTINATION (PLEASE SPECIFY PLACE AND ADDRESS)], with a total of [TOTAL NO. OF PASSENGER/S] passengers, including [NAME OF PASSENGER/S]. The vehicle assigned for this trip is [VEHICLE TO BE USED], and the designated driver is [DRIVER]. This request is under Budget Number [BUDGET NO], with the transportation cost covered accordingly. The necessary form was filled out on [DATE FILLED].</p>
         </div>
         <div class="info-box">
            
         </div>
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
   function maintenanceChecklist()
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