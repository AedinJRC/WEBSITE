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
      <ul class="nav-list">
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
         <?php
            if ($_SESSION['role'] != 'User') {
               ?>
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
               <?php
            }
            if ($_SESSION['role'] == 'Admin') {
               ?>
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
                        </div>
                     </ul>
                  </li>
               <?php
            }
         ?>
      </ul>
      <div id="logout">
         <img id=profile src="uploads/<?php echo $_SESSION['ppicture']; ?>" alt="<?php echo $_SESSION['ppicture']; ?>">
         <div id="profile-text">
            <span id="name"><?php echo $_SESSION['lname']."\t".$_SESSION['fname']; ?></span>
            <span id="role"><?php echo $_SESSION['role'] ?></span>
         </div>
         <a href="index.php">
            <button>
               <img id=logout-img src="PNG/Logout.png" alt="Logout">
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
            <main onclick="closeSidebar()">
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
         elseif(isset($_GET["dsch"]) and !empty($_GET["dsch"]))
         driverSchedules();
         elseif(isset($_GET["macc"]) and !empty($_GET["macc"]))
         manageAccount();
         elseif(isset($_GET["mdep"]) and !empty($_GET["mdep"]))
         manageDepartment();
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
            <form class="vehicle-reservation-form" action="GSO.php?vres=a" method="post" enctype="multipart/form-data">
               <img src="PNG/CSA_Logo.png" alt="">
               <span class="header">
                  <span id="csab">Colegio San Agustin-Biñan</span>
                  <span id="swe">Southwoods Ecocentrum, Brgy. San Francisco, 4024 Biñan City, Philippines</span>
                  <span id="vrf">VEHICLE RESERVATION FORM</span>
                  <span>
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
                        <input name="vrfname" value="<?php if($_SESSION['role']!="Admin") {echo $_SESSION['fname']." ".$_SESSION['lname'];}?>" type="text" id="name" required>
                        <label for="name">NAME:</label>
                     </div>
                     <div class="input-container">
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
                        <input name="vrfdate_filed" type="date" value="<?php echo date("Y-m-d"); ?>" id="dateFiled" required readonly>
                        <label for="dateFiled">DATE FILED:</label>
                     </div>
                     <div class="input-container">
                        <input name="vrfbudget_no" type="number" id="budgetNo" required>
                        <label for="budgetNo">BUDGET No.:</label>
                     </div>
                     <div class="input-container">
                        <select name="vrfvehicle" id="vehicleUsed" required>
                           <option value="" disabled selected></option>
                           <?php
                              include 'config.php';
                              $selectvehicle = "SELECT * FROM carstb ORDER BY plate_number ASC";
                              $resultvehicle = $conn->query($selectvehicle);
                              while($rowvehicle = $resultvehicle->fetch_assoc()) {
                                 echo "<option value='".$rowvehicle['plate_number']."'>".$rowvehicle['plate_number']."</option>";
                              }
                           ?>
                        </select>
                        <label for="vehicleUsed">VEHICLE TO BE USED:</label>
                     </div>
                     <div class="input-container">
                        <select name="vrfdriver" id="driver" required>
                           <option value="" disabled selected></option>
                           <?php
                              include 'config.php';
                              $selectdriver = "SELECT * FROM usertb WHERE role='Driver' ORDER BY fname ASC";
                              $resultdriver = $conn->query($selectdriver);
                              if ($resultdriver->num_rows > 0) {
                                 while($rowdriver = $resultdriver->fetch_assoc()) {
                                    echo "<option value='".$rowdriver['employeeid']."'>"."Mr. ".$rowdriver['fname']." ".$rowdriver['lname']."</option>";
                                 }
                              }
                           ?>
                        </select>
                        <label for="driver">DRIVER:</label>
                     </div>
                  </div>
               </div>
               <span class="address">
                  <span>DESTINATION (PLEASE SPECIFY PLACE AND ADDRESS):</span>
                  <textarea name="vrfdestination" maxlength="255" type="text" id="destination" required></textarea>
               </span>
               <div class="vrf-details" style="margin-top:1vw;">
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

                           // Hide "USE ATTACHMENT" button when "+" button is clicked
                           attachmentButton.style.display = "none";

                           // Get all current input containers
                           const inputContainers = passengerList.querySelectorAll(".input-container");
                           
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
                           inputContainer.classList.add("input-container");
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
                           removeButton.style = "position:absolute; transform:translateX(16.8vw)";
                           removeButton.type = "button";
                           removeButton.textContent = "×";
                           removeButton.onclick = function () 
                           {
                              inputContainer.remove();
                              updateRemoveButtons();
                           };

                           inputContainer.appendChild(input);
                           inputContainer.appendChild(label);
                           inputContainer.appendChild(removeButton);

                           // Insert before the add button
                           passengerList.insertBefore(inputContainer, addButton);

                           updateRemoveButtons();
                        }
                        function updateRemoveButtons() 
                        {
                           const inputContainers = document.querySelectorAll(".input-container");

                           // Show the remove button only for the last input container
                           inputContainers.forEach((container, index) => 
                              {
                                 const removeButton = container.querySelector("button");
                                 if (removeButton) removeButton.style.display = (index === inputContainers.length - 1) ? "inline-block" : "none";
                              }
                           );
                        }
                        function useAttachment() {
                           const passengerList = document.getElementById("passengerList");
                           const addButton = document.getElementById("addButton");
                           const attachmentButton = document.getElementById("attachmentButton");

                           // Hide buttons
                           addButton.style.display = "none";
                           attachmentButton.style.display = "none";

                           // Create a container for attachment input
                           const inputContainer = document.createElement("div");
                           inputContainer.classList.add("input-container");
                           inputContainer.style= "transform: translateY(0.5vw);display:flex; flex-direction:row;";

                           const attachmentInput = document.createElement("input");
                           attachmentInput.type = "file";
                           attachmentInput.name = "vrfpassenger_attachment";
                           attachmentInput.required = true;
                           attachmentInput.style = "width:14vw;border-top-right-radius:0;border-bottom-right-radius:0;";

                           const numberInput = document.createElement("input");
                           numberInput.type = "number";
                           numberInput.name = "vrfpassenger_count";
                           numberInput.required = true;
                           numberInput.style = "text-align:center;width:4vw;border-top-left-radius:0;border-bottom-left-radius:0";
                           
                           const label = document.createElement("label");
                           label.textContent = `PASSENGER COUNT`;

                           // Create a remove button
                           const removeButton = document.createElement("button");
                           removeButton.textContent = "×";
                           removeButton.style = "position:absolute;transform:translateY(2.1vw)";
                           
                           removeButton.onclick = function () {
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
                  <span class="address" style="margin-top:-1.8vw">
                     <span style="text-align:center">TRANSPORTATION COST</span>
                     <textarea name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost" readonly></textarea>
                     <div class="subbtn-container">
                        <input type="file" name="vrfletter_attachment" class="attachment" id="fileInput">
                        <label for="fileInput" class="attachment-label"><img class="attachment-img" src="PNG/File.png" for="fileInput" alt="">LETTER ATTACHMENT</label>
                        <button class="subbtn" type="submit" name="vrfsubbtn">Submit</button>
                     </div>
                  </span>
               </div>
            </form>
            <?php
               include 'config.php';
               if ($_SERVER["REQUEST_METHOD"] == "POST") {
                  if (
                     isset($_POST['vrfname'], $_POST['vrfdepartment'], $_POST['vrfactivity'], $_POST['vrfpurpose'], 
                     $_POST['vrfdate_filed'], $_POST['vrfbudget_no'], $_POST['vrfvehicle'], 
                     $_POST['vrfdriver'], $_POST['vrfdestination'], $_POST['vrfdeparture'], 
                     $_POST['vrftransportation_cost']) 
                     && isset($_FILES["vrfletter_attachment"]) // Letter attachment is required
                     )   
                  {
                     $id = htmlspecialchars($_POST['vrfid']);
                     $name = htmlspecialchars($_POST['vrfname']);
                     $department = htmlspecialchars($_POST['vrfdepartment']);
                     $activity = htmlspecialchars($_POST['vrfactivity']);
                     $purpose = htmlspecialchars($_POST['vrfpurpose']);
                     $date_filed = htmlspecialchars($_POST['vrfdate_filed']);
                     $budget_no = htmlspecialchars($_POST['vrfbudget_no']);
                     $vehicle = htmlspecialchars($_POST['vrfvehicle']);
                     $driver = htmlspecialchars($_POST['vrfdriver']);
                     $destination = htmlspecialchars($_POST['vrfdestination']);
                     $departure = htmlspecialchars($_POST['vrfdeparture']);
                     $transportation_cost = htmlspecialchars($_POST['vrftransportation_cost']);
                     if(isset($_POST['vrfpassenger_count']) and !empty($_POST['vrfpassenger_count']))
                     {
                        $passenger_count = htmlspecialchars($_POST['vrfpassenger_count']);
                     }

                     // File upload directory
                     $targetDir = "uploads/";
                     if (!is_dir($targetDir)) {
                           mkdir($targetDir, 0777, true);
                     }

                     // Allowed file types
                     $allowedTypes = ['docx', 'pdf'];

                     // Handle letter attachment (Required)
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

                     $letterUploaded = move_uploaded_file($_FILES["vrfletter_attachment"]["tmp_name"], $letterFilePath);

                     // Handle passenger attachment (Optional)
                     $passengerFileName = null;
                     if (!empty($_FILES["vrfpassenger_attachment"]["name"])) {
                           $passengerFileName = basename($_FILES["vrfpassenger_attachment"]["name"]);
                           $passengerFilePath = $targetDir . $passengerFileName;
                           $passengerFileType = strtolower(pathinfo($passengerFilePath, PATHINFO_EXTENSION));

                           if (!in_array($passengerFileType, $allowedTypes)) {
                              echo "<script>
                                    alert('Invalid file type for letter attachment. Only Word Documents or PDFs are allowed.');
                                    window.history.back();
                                 </script>";
                              exit;
                           }

                           move_uploaded_file($_FILES["vrfpassenger_attachment"]["tmp_name"], $passengerFilePath);
                     }

                     if ($letterUploaded) {
                           try {
                              // Insert data into database
                              $stmt = $conn->prepare("INSERT INTO vrftb 
                                 (id, name, department, activity, purpose, date_filed, budget_no, vehicle, driver, destination, departure, transportation_cost, passenger_count, letter_attachment, passenger_attachment) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                              $stmt->bind_param(
                                 "sssssssssssssss", 
                                 $id, $name, $department, $activity, $purpose, $date_filed, $budget_no, $vehicle, $driver, $destination, $departure, $transportation_cost, $passenger_count, $letterFileName, $passengerFileName
                              );
                              $stmt->execute();
                              // Insert Passengers in passengertb
                              if (!empty($_POST['vrfpassenger_name']) && !empty($_POST['vrfid'])) {
                                 $stmt = $conn->prepare("INSERT INTO passengerstb (vrfid, passenger_name) VALUES (?, ?)");
                                 foreach ($_POST['vrfpassenger_name'] as $passenger_name) {
                                    $stmt->bind_param("ss", $_POST['vrfid'], $passenger_name); // Fix both bindings
                                    $stmt->execute();
                                 }
                              }
                              // Select the count of passengers for the given vrfid
                              $countpassenger = "SELECT COUNT(*) AS passenger_count FROM passengerstb WHERE vrfid = ?";
                              $stmt = $conn->prepare($countpassenger);
                              $stmt->bind_param("s", $id);
                              $stmt->execute();
                              $resultcountpassenger = $stmt->get_result();
                              $rowcountpassenger = $resultcountpassenger->fetch_assoc();

                              // Store passenger count
                              $passenger_count = $rowcountpassenger['passenger_count'];

                              // Update the vrftb table with the passenger count
                              $stmt = $conn->prepare("UPDATE vrftb SET passenger_count = ? WHERE id = ?");
                              $stmt->bind_param("is", $passenger_count, $id);
                              $stmt->execute();

                              // Success message and redirection
                              echo "<script>
                                       alert('Reservation successfully submitted!');
                                    </script>";
                              exit;
                           } catch (Exception $e) {
                              echo "<script>
                                       alert('Error: " . addslashes($e->getMessage()) . "');
                                       window.history.back();
                                    </script>";
                           }
                     } else {
                           echo "<script>
                                 alert('Failed to upload the letter attachment.');
                                 window.history.back();
                                 </script>";
                     }
                  } 
                  else 
                  {
                     echo "<script>
                              alert('Please fill in all required fields.');
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
            <p>New</p>
         </div>
         <div class="whitespace"></div>
         <div class="whitespace2"></div>
         <?php
            include 'config.php';
            $selectvrf = "SELECT * FROM vrftb WHERE gsoassistant_status!='Approved' ORDER BY date_filed DESC, id DESC";
            $resultvrf = $conn->query($selectvrf);
            if ($resultvrf->num_rows > 0) {
               while($rowvrf = $resultvrf->fetch_assoc()) {
                  ?>
                     <a href="GSO.php?papp=a&vrfid=<?php echo $rowvrf['id']; ?>#vrespopup" class="link" style="text-decoration:none;">
                  <?php
                     if (isset($_GET['vrfid'])) {
                        include 'config.php';
                        $updatevrf = "UPDATE vrftb SET gsoassistant_status='Clicked' WHERE id = ?";
                        $stmt = $conn->prepare($updatevrf);
                        if ($stmt) {
                           $stmt->bind_param("s", $_GET['vrfid']);
                           $stmt->execute();
                           $stmt->close();
                        }
                     }
                     if($rowvrf['gsoassistant_status'] != "Clicked")
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
                                 if($rowvrf['gsoassistant_status'] == "Pending")
                                 {
                                    echo '<div class="circle"></div>';
                                 }
                              ?>
                              <span class="time">1 hour ago</span>
                           </div>
                           <div class="info-heading">
                              <img src="uploads/Maynard.png" alt="Profile">
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
                           <form class="vehicle-reservation-form" action="GSO.php?vres=a" method="post" enctype="multipart/form-data">
                              <img src="PNG/CSA_Logo.png" alt="">
                              <span class="header">
                                 <span id="csab">Colegio San Agustin-Biñan</span>
                                 <span id="swe">Southwoods Ecocentrum, Brgy. San Francisco, 4024 Biñan City, Philippines</span>
                                 <span id="vrf">VEHICLE RESERVATION FORM</span>
                                 <span>
                                    <span id="fid">Form ID:</span>
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
                                       <input type="text" name="vrfvehicle" value="<?php echo $rowvrfid['vehicle'] ?>" id="vehicleUsed" required readonly>
                                       <label for="vehicleUsed">VEHICLE TO BE USED:</label>
                                    </div>
                                    <div class="input-container">
                                       <input type="text" name="vrfdriver" value="<?php echo $rowvrfid['driver'] ?>" id="driver" required readonly>
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
                                                            <button class="remove-passenger" type="button" style="position:absolute; transform:translateX(16.8vw);display:none;">×</button>
                                                         </div>
                                                      <?php
                                                      $passenger_number++;
                                                   }
                                                }
                                             } else {
                                                ?>
                                                   <div class="input-container" style="transform: translateY(0.5vw); display: flex; flex-direction: row;">
                                                      <input type="text" value="<?php echo $rowvrfid['passenger_attachment'] ?>" name="vrfpassenger_attachment" required style="border-color:black; width: 14vw; border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                                      <input type="number" value="<?php echo $rowvrfid['passenger_count'] ?>" name="vrfpassenger_count" required style="border-color:black; text-align: center; width: 4vw; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                      <label for="passengerCount">PASSENGER COUNT</label>
                                                   </div>

                                                <?php
                                             }
                                          ?>
                                       </div>
                                    </div>
                                    <script>
                                       function addPassenger() 
                                       {
                                          const passengerList = document.getElementById("passengerList");
                                          const addButton = document.getElementById("addButton");
                                          const attachmentButton = document.getElementById("attachmentButton");

                                          // Hide "USE ATTACHMENT" button when "+" button is clicked
                                          attachmentButton.style.display = "none";

                                          // Get all current input containers
                                          const inputContainers = passengerList.querySelectorAll(".input-container");
                                          
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
                                          inputContainer.classList.add("input-container");
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
                                          removeButton.style = "position:absolute; transform:translateX(16.8vw)";
                                          removeButton.type = "button";
                                          removeButton.textContent = "×";
                                          removeButton.onclick = function () 
                                          {
                                             inputContainer.remove();
                                             updateRemoveButtons();
                                          };

                                          inputContainer.appendChild(input);
                                          inputContainer.appendChild(label);
                                          inputContainer.appendChild(removeButton);

                                          // Insert before the add button
                                          passengerList.insertBefore(inputContainer, addButton);

                                          updateRemoveButtons();
                                       }
                                       function updateRemoveButtons() 
                                       {
                                          const inputContainers = document.querySelectorAll(".input-container");

                                          // Show the remove button only for the last input container
                                          inputContainers.forEach((container, index) => 
                                             {
                                                const removeButton = container.querySelector("button");
                                                if (removeButton) removeButton.style.display = (index === inputContainers.length - 1) ? "inline-block" : "none";
                                             }
                                          );
                                       }
                                       function useAttachment() {
                                          const passengerList = document.getElementById("passengerList");
                                          const addButton = document.getElementById("addButton");
                                          const attachmentButton = document.getElementById("attachmentButton");

                                          // Hide buttons
                                          addButton.style.display = "none";
                                          attachmentButton.style.display = "none";

                                          // Create a container for attachment input
                                          const inputContainer = document.createElement("div");
                                          inputContainer.classList.add("input-container");
                                          inputContainer.style= "transform: translateY(0.5vw);display:flex; flex-direction:row;";

                                          const attachmentInput = document.createElement("input");
                                          attachmentInput.type = "file";
                                          attachmentInput.name = "vrfpassenger_attachment";
                                          attachmentInput.required = true;
                                          attachmentInput.style = "width:14vw;border-top-right-radius:0;border-bottom-right-radius:0;";

                                          const numberInput = document.createElement("input");
                                          numberInput.type = "number";
                                          numberInput.name = "vrfpassenger_count";
                                          numberInput.required = true;
                                          numberInput.style = "text-align:center;width:4vw;border-top-left-radius:0;border-bottom-left-radius:0";
                                          
                                          const label = document.createElement("label");
                                          label.textContent = `PASSENGER COUNT`;

                                          // Create a remove button
                                          const removeButton = document.createElement("button");
                                          removeButton.textContent = "×";
                                          removeButton.style = "position:absolute;transform:translateY(2.1vw)";
                                          
                                          removeButton.onclick = function () {
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
                                 <span class="address" style="margin-top:-1.8vw">
                                    <span style="text-align:center">TRANSPORTATION COST</span>
                                    <textarea name="vrftransportation_cost" maxlength="255" type="text" id="transportation-cost" required></textarea>
                                    <div class="subbtn-container">
                                       <input type="file" name="vrfletter_attachment" class="attachment" id="fileInput">
                                       <label for="fileInput" class="attachment-label"><img class="attachment-img" src="PNG/File.png" for="fileInput" alt="">LETTER ATTACHMENT</label>
                                       <button class="rejbtn" type="submit" name="vrfsubbtn">Reject</button>
                                       <button class="appbtn" type="submit" name="vrfsubbtn">Approve</button>
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
      ?>
         
      <?php
   }
?>