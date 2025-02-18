<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>GSO</title>
   <link rel="stylesheet" href="styles.css">
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
            <button onclick="toggleDropdown(this)" class="dropdown-btn">
               <img src="PNG/Pie.png" alt="Requests">
               <span>Requests</span>
               <img src="PNG/Down.png" alt="DropDown">
            </button>
            <ul class="dropdown-container">
               <div>
                  <li><a href="GSO.php"><span>Pending Approval</span></a></li>
                  <li><a href="GSO.php"><span>Reservation Approved</span></a></li>
                  <li><a href="GSO.php"><span>Cancelled Requests</span></a></li>
               </div>
            </ul>
         </li>
         <li>
            <button onclick="toggleDropdown(this)" class="dropdown-btn">
               <img src="PNG/Calendar.png" alt="Calendar">
               <span>Calendar</span>
               <img src="PNG/Down.png" alt="DropDown">
            </button>
            <ul class="dropdown-container">
               <div>
                  <li><a href="GSO.php"><span>Vehicle Reservation Form</span></a></li>
                  <li><a href="GSO.php"><span>Vehicle Schedules</span></a></li>
                  <li><a href="GSO.php"><span>Driver Schedules</span></a></li>
               </div>
            </ul>
         </li>
         <li>
            <button onclick="toggleDropdown(this)" class="dropdown-btn">
               <img src="PNG/File.png" alt="Report">
               <span>Report</span>
               <img src="PNG/Down.png" alt="DropDown">
            </button>
            <ul class="dropdown-container">
               <div>
                  <li><a href="GSO.php"><span>Maintenance Report</span></a></li>
                  <li><a href="GSO.php"><span>Summary Report</span></a></li>
               </div>
            </ul>
         </li>
      </ul>
   </nav>
   <main onclick="closeSidebar()">
      <?php
         
      ?>
   </main>
</body>
</html>
