<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup Page</title>
    <style>
        /* Reset and base styles */
        .signup-container {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }

        .signup-header {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .signup-logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .signup-title {
            font-size: 20px;
            margin-bottom: 15px;
        }

        /* Form styles with minimal gaps */
        .signup-form-group {
            margin-bottom: 8px;
        }

        .signup-form-group label {
            display: block;
            margin-bottom: 2px;
            font-weight: normal;
        }

        .signup-form-group input, .signup-form-group select {
            width: 450px;
            height: 38px;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }

        /* Custom file input styling */
        .signup-file-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 8px;
        }

        .signup-file-container input[type="file"] {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        .custom-file-upload {
            display: inline-block;
            padding: 6px 12px;
            background-color: #7D192E;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            width: auto;
            text-align: center;
        }

        .custom-file-upload:hover {
            background-color: #5a1121;
        }

        .signup-btn {
            background-color: #7D192E;
            color: white;
            border: none;
            width: 450px;
            height: 38px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        .signup-btn:hover {
            background-color: #5a1121;
        }

        .signup-avatar-container {
            text-align: center;
            margin-bottom: 10px;
        }

        #signup-preview {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #ccc;
            margin-bottom: 5px;
        }

        .signup-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 14px;
            width: 450px;
        }

        #signup-password-error {
            color: red;
            font-size: 12px;
            margin-top: 2px;
            display: none;
        }

        /* Terms and Conditions Modal */
        .terms-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
            z-index: 1000;
            overflow-y: auto;
        }

        .terms-content {
            background-color: #fff;
            margin: 50px auto;
            padding: 20px;
            width: 80%;
            max-width: 800px;
            border-radius: 5px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .terms-header {
            text-align: center;
            margin-bottom: 20px;
            color: #7D192E;
        }

        .terms-body {
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .terms-footer {
            text-align: center;
        }

        .terms-btn {
            background-color: #7D192E;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 10px;
        }

        .terms-btn:hover {
            background-color: #5a1121;
        }

        .terms-checkbox {
            margin: 20px 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .terms-checkbox input {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <form id="signupForm" class="signup-form" method="post" enctype="multipart/form-data" onsubmit="event.preventDefault(); return signupValidatePasswords();">
            
            <div class="signup-avatar-container">
                <img id="signup-preview" src="uploads/default_avatar.png" alt="Avatar Preview">
                <div class="signup-file-container">
                    <label for="signup-ppicture" class="custom-file-upload">Upload Profile</label>
                    <input type="file" name="signup_ppicture" id="signup-ppicture" accept="image/*" onchange="signupPreviewImage(this)">
                </div>
            </div>

            <div class="signup-form-group">
                <label for="signup-employee">Employee No.</label>
                <input type="text" id="signup-employee" name="signup_employee-number" required maxlength="10">
            </div>

            <div class="signup-form-group">
                <label for="signup-first-name">First Name</label>
                <input type="text" id="signup-first-name" name="signup_first-name" required maxlength="30">
            </div>

            <div class="signup-form-group">
                <label for="signup-middle-name">Middle Name</label>
                <input type="text" id="signup-middle-name" name="signup_middle-name" maxlength="30">
            </div>

            <div class="signup-form-group">
                <label for="signup-last-name">Last Name</label>
                <input type="text" id="signup-last-name" name="signup_last-name" required maxlength="20">
            </div>

            <div class="signup-form-group">
                <label for="signup-department">Department</label>
                <select name="signup_department" id="department" required>
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
                        $conn->close();
                    ?>
                </select>
            </div>

            <div class="signup-form-group">
                <label for="signup-password">Password</label>
                <input type="password" id="signup-password" name="signup_password" required maxlength="100">
            </div>

            <div class="signup-form-group">
                <label for="signup-retype-password">Re-type Password</label>
                <input type="password" id="signup-retype-password" name="signup_retype-password" required maxlength="100">
                <div id="signup-password-error">Passwords do not match!</div>
            </div>

            <button type="submit" class="signup-btn" name="signup_sigbtn">LET'S START !</button>
        </form>
    </div>

    <!-- Terms and Conditions Modal -->
    <div id="termsModal" class="terms-modal">
        <div class="terms-content">
            <div class="terms-header">
                <h2>Vehicle Reservation & Monitoring System Terms and Conditions</h2>
                <p>Colegio San Agustin - Biñan<br>Department of General Services</p>
                <p><strong>Effective Date: <?php echo date('F j, Y'); ?></strong></p>
            </div>
            <div class="terms-body">
                <p>By accessing and using the Vehicle Reservation and Monitoring System of Colegio San Agustin - Biñan (CSA-Biñan), you agree to comply with the following terms and conditions. These rules are established to ensure the safe, efficient, and accountable use of CSA-Biñan's official vehicles.</p>
                
                <h3>1. Eligibility</h3>
                <ul>
                    <li>The system is available to CSA-Biñan faculty, staff, administrators, and authorized students for official school-related functions only.</li>
                    <li>Reservation requests must be made using your CSA-Biñan credentials.</li>
                </ul>
                
                <h3>2. Proper Use of Vehicles</h3>
                <ul>
                    <li>Reserved vehicles must be used strictly for the stated purpose and destination.</li>
                    <li>Personal use of school vehicles is strictly prohibited.</li>
                    <li>Users must adhere to traffic laws, institutional policies, and safety regulations at all times.</li>
                </ul>
                
                <h3>3. Reservation Process</h3>
                <ul>
                    <li>All reservations must be made through the official system at least [X] days in advance.</li>
                    <li>Confirmation is subject to vehicle availability and approval by the Department of General Services.</li>
                    <li>Users must accurately complete all required fields in the reservation form.</li>
                </ul>
                
                <h3>4. Cancellations and No-Shows</h3>
                <ul>
                    <li>If plans change, users must cancel reservations at least 24 hours before the scheduled time.</li>
                    <li>Unjustified no-shows may result in suspension of access to the system.</li>
                </ul>
                
                <h3>5. Monitoring and Reporting</h3>
                <ul>
                    <li>Vehicles may be equipped with GPS tracking systems for safety and accountability.</li>
                    <li>Users must submit a post-trip report if required, including any incidents, delays, or concerns during the trip.</li>
                </ul>
                
                <h3>6. Damage, Accidents, and Liability</h3>
                <ul>
                    <li>Any accidents, damage, or irregularities must be reported immediately to the Department of General Services.</li>
                    <li>Users may be held liable for damages caused by negligence or unauthorized use.</li>
                </ul>
                
                <h3>7. Data Privacy</h3>
                <ul>
                    <li>Personal and trip-related information will be collected and used in accordance with our Privacy Policy.</li>
                    <li>CSA-Biñan is committed to protecting your data under the Data Privacy Act of 2012.</li>
                </ul>
                
                <h3>8. Violations and Sanctions</h3>
                <p>Violation of these terms may result in:</p>
                <ul>
                    <li>Temporary or permanent suspension from the system</li>
                    <li>Administrative sanctions under CSA-Biñan's employee/student code of conduct</li>
                    <li>Possible legal action, depending on the severity of the violation</li>
                </ul>
                
                <h3>9. Amendments</h3>
                <p>CSA-Biñan reserves the right to update or modify these terms at any time. Users will be notified of changes via official channels.</p>
                
                <h3>10. Contact Information</h3>
                <p>For concerns, clarifications, or reports, please contact:<br>
                Department of General Services<br>
                Colegio San Agustin - Biñan<br>
                📧 [Insert Email Address]<br>
                📞 [Insert Phone Number]</p>
            </div>
            <div class="terms-checkbox">
                <input type="checkbox" id="termsAgree" required>
                <label for="termsAgree">I have read and agree to the Terms and Conditions</label>
            </div>
            <div class="terms-footer">
                <button type="button" class="terms-btn" id="termsAccept">Accept</button>
                <button type="button" class="terms-btn" id="termsDecline">Decline</button>
            </div>
        </div>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup_employee-number'])) {
        // Process the form submission after terms acceptance
        include 'config.php';

        $employeeid = $_POST['signup_employee-number'];
        $fname = $_POST['signup_first-name'];
        $mname = $_POST['signup_middle-name'];
        $lname = $_POST['signup_last-name']; 
        $pword = $_POST['signup_password'];
        $departmen = $_POST['signup_department'];

        if (isset($_FILES['signup_ppicture']) && $_FILES['signup_ppicture']['error'] === 0) {
            $filename = uniqid() . "_" . basename($_FILES['signup_ppicture']['name']);
            $target_directory = "uploads/";
            $target_file = $target_directory . $filename;
            move_uploaded_file($_FILES['signup_ppicture']['tmp_name'], $target_file);
            $ppicture = $filename;
        } else {
            $ppicture = "default_avatar.png";
        }

        $fnameProcessed = strtolower(str_replace(['-', ' '], '', $fname));
        $mnameProcessed = strtolower(str_replace(['-', ' '], '', $mname));
        $lnameProcessed = strtolower(str_replace(['-', ' '], '', $lname));

        $checkEmployee = "SELECT employeeid FROM employeetb 
                        WHERE employeeid = ?
                        AND LOWER(REPLACE(REPLACE(fname, '-', ''), ' ', '')) = ?
                        AND LOWER(REPLACE(REPLACE(mname, '-', ''), ' ', '')) = ?
                        AND LOWER(REPLACE(REPLACE(lname, '-', ''), ' ', '')) = ?";
        $stmtEmp = $conn->prepare($checkEmployee);
        $stmtEmp->bind_param("ssss", $employeeid, $fnameProcessed, $mnameProcessed, $lnameProcessed);
        $stmtEmp->execute();
        $stmtEmp->store_result();

        if ($stmtEmp->num_rows == 0) {
            echo "<script>
                alert('Employee not found in records! Please check your Employee Number, First Name, Middle Name, and Last Name.');
                window.history.back();
            </script>";
        } else {
            $checkUser = "SELECT employeeid FROM usertb WHERE employeeid = ?";
            $stmtUser = $conn->prepare($checkUser);
            $stmtUser->bind_param("s", $employeeid);
            $stmtUser->execute();
            $stmtUser->store_result();

            if ($stmtUser->num_rows > 0) {
                echo "<script>
                    alert('Employee Number already registered! Please log in.');
                    window.location.href = 'index.php?log=a';
                </script>";
            } else {
                $insertUser = "INSERT INTO usertb (employeeid, ppicture, fname, lname, pword, department) 
                            VALUES (?, ?, ?, ?, ?, ?)";
                $stmtInsert = $conn->prepare($insertUser);
                $stmtInsert->bind_param("ssssss", $employeeid, $ppicture, $fname, $lname, $pword, $departmen);

                if ($stmtInsert->execute()) {
                    echo "<script>
                        alert('Account created successfully! You can now log in.');
                        window.location.href = 'index.php?log=a'; 
                    </script>";
                } else {
                    echo "<script>
                        alert('Something went wrong during signup. Please try again.');
                        window.history.back();
                    </script>";
                }

                $stmtInsert->close();
            }

            $stmtUser->close();
        }

        $stmtEmp->close();
        $conn->close();
    }
    ?>

    <script>
        function signupPreviewImage(input) {
            const preview = document.getElementById('signup-preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function signupValidatePasswords() {
            const password = document.getElementById("signup-password").value;
            const retype = document.getElementById("signup-retype-password").value;
            const error = document.getElementById("signup-password-error");

            if (password !== retype) {
                error.style.display = "block";
                return false;
            } else {
                error.style.display = "none";
                
                // Show terms modal instead of submitting form
                document.getElementById('termsModal').style.display = 'block';
                return false;
            }
        }

        // Terms and Conditions handling
        document.getElementById('termsAccept').addEventListener('click', function() {
            if (document.getElementById('termsAgree').checked) {
                // Submit the form
                document.getElementById('signupForm').submit();
            } else {
                alert('You must agree to the Terms and Conditions to proceed.');
            }
        });

        document.getElementById('termsDecline').addEventListener('click', function() {
            document.getElementById('termsModal').style.display = 'none';
            alert('You must accept the Terms and Conditions to create an account.');
        });
    </script>
</body>
</html>