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

        /* Enhanced Terms and Conditions Modal */
        .terms-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            overflow-y: auto;
            padding: 20px;
            box-sizing: border-box;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .terms-content {
            background-color: #ffffff;
            margin: 50px auto;
            padding: 30px;
            width: 90%;
            max-width: 700px;
            border-radius: 10px;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
            animation: slideUp 0.4s ease;
        }

        @keyframes slideUp {
            from { 
                transform: translateY(20px);
                opacity: 0;
            }
            to { 
                transform: translateY(0);
                opacity: 1;
            }
        }

        .terms-header {
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 15px;
            margin-bottom: 20px;
            text-align: center;
        }

        .terms-header h2 {
            margin: 0 0 5px 0;
            font-size: 28px;
            color: #7D192E;
            font-weight: 600;
        }

        .terms-header p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .terms-header p strong {
            color: #333;
            font-weight: 600;
        }

        .terms-body {
            margin: 25px 0;
            font-size: 14px;
            color: #444;
            line-height: 1.7;
        }

        .terms-body p {
            margin-bottom: 15px;
        }

        .terms-body h3 {
            margin: 25px 0 10px 0;
            font-size: 17px;
            color: #7D192E;
            font-weight: 600;
        }

        .terms-body ul {
            margin: 10px 0 15px 20px;
            padding-left: 15px;
        }

        .terms-body li {
            margin-bottom: 8px;
        }

        .terms-checkbox {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 25px 0;
            padding: 15px 0;
            border-top: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
        }

        .terms-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            accent-color: #7D192E;
            cursor: pointer;
        }

        .terms-checkbox label {
            font-size: 15px;
            color: #333;
            cursor: pointer;
        }

        .terms-footer {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .terms-btn {
            background-color: #7D192E;
            color: #ffffff;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            min-width: 120px;
        }

        .terms-btn:hover {
            background-color: #5a1121;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .terms-btn:active {
            transform: translateY(0);
        }

        .terms-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .terms-btn:disabled:hover {
            background-color: #cccccc;
        }

        #termsDecline {
            background-color: #f0f0f0;
            color: #333;
        }

        #termsDecline:hover {
            background-color: #e0e0e0;
        }

        /* Scrollbar styling for modal */
        .terms-content::-webkit-scrollbar {
            width: 8px;
        }

        .terms-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .terms-content::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .terms-content::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
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
                <h2>Privacy Policy</h2>
                <p>Colegio San Agustin - Biñan<br>Department of General Services</p>
                <p><strong>Effective Date: <?php echo date('F j, Y'); ?></strong></p>
            </div>
            <div class="terms-body">
                <p>Colegio San Agustin - Biñan (CSA-Biñan), through its Department of General Services, is committed to protecting the privacy and personal data of all users of the Vehicle Reservation and Monitoring System. 
                This Privacy Policy explains how we collect, use, store, disclose, and protect your information in compliance with the Data Privacy Act of 2012 (Republic Act No. 10173) and relevant institutional policies.</p>
                
                <h3>1. Information We Collect</h3>
                <p>We collect the following types of information:</p>
                <p><strong>a. Personal Information:</strong></p>
                <ul>
                    <li>Full Name</li>
                    <li>Employee or Student ID Number</li>
                    <li>Department or Office Affiliation</li>
                    <li>Contact Information (email address, phone number)</li>
                </ul>
                <p><strong>b. Reservation Details:</strong></p>
                <ul>
                    <li>Purpose of vehicle use</li>
                    <li>Destination</li>
                    <li>Date and time of reservation</li>
                    <li>Duration of use</li>
                </ul>
                <p><strong>c. Vehicle Monitoring Data:</strong></p>
                <ul>
                    <li>Assigned vehicle and driver</li>
                    <li>Time of departure and return</li>
                    <li>Fuel consumption and mileage</li>
                    <li>GPS tracking data (if applicable)</li>
                </ul>

                <h3>2. Purpose of Data Collection</h3>
                <p>The data is collected for the following purposes:</p>
                <ul>
                    <li>To process and manage vehicle reservation requests</li>
                    <li>To ensure efficient and equitable allocation of vehicles</li>
                    <li>To monitor the use of institutional vehicles for safety, accountability, and operational efficiency</li>
                    <li>To maintain accurate records for administrative and audit purposes</li>
                </ul>
                
                <h3>3. Data Sharing and Disclosure</h3>
                <p>Your information may be shared only under the following circumstances:</p>
                <ul>
                    <li>With authorized personnel within CSA-Biñan who need the information to perform their official duties</li>
                    <li>When required by law or legal process</li>
                    <li>In emergency situations where health or safety is at risk</li>
                    <li>With third-party service providers under strict confidentiality agreements</li>
                </ul>
                
                <h3>4. Data Retention</h3>
                <p>Personal data will be retained only for as long as necessary to fulfill the purposes for which it was collected, or as required by law and institutional policies.</p>
                
                <h3>5. Data Security</h3>
                <p>CSA-Biñan uses appropriate technical and organizational measures to protect your personal data, including:</p>
                <ul>
                    <li>Secure storage and access controls</li>
                    <li>Regular system audits</li>
                    <li>Authorized access only to designated personnel</li>
                    <li>Encryption of sensitive data</li>
                </ul>
                
                <h3>6. Your Rights</h3>
                <p>You have the right to:</p>
                <ul>
                    <li>Access and review your personal data</li>
                    <li>Request correction of inaccurate or outdated data</li>
                    <li>Withdraw your consent at any time (subject to service limitations)</li>
                    <li>Lodge a complaint with the CSA-Biñan Data Protection Officer (DPO) or the National Privacy Commission (NPC)</li>
                </ul>
                
                <h3>7. Inquiries and Complaints</h3>
                <p>For questions, concerns, or data privacy-related complaints, you may contact:<br>
                Department of General Services<br>
                Colegio San Agustin - Biñan<br>
                📧 csabinancampus2020@gmail.com<br>
                📞 Tel. Nos. (+632) 8478-0167 to 71 ▪ DL (+632) 8478-0172 ▪ Fax No. (+632) 8478-0180</p>
            </div>
            <div class="terms-checkbox">
                <input type="checkbox" id="termsAgree" required>
                <label for="termsAgree">I have read and agree to the Privacy Policy and Terms of Service</label>
            </div>
            <div class="terms-footer">
                <button type="button" class="terms-btn" id="termsAccept" disabled>Accept</button>
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

            // HASH PASSWORD FIRST
            $hashedPassword = password_hash($pword, PASSWORD_DEFAULT);

            $insertUser = "INSERT INTO usertb 
            (employeeid, ppicture, fname, lname, pword, department) 
            VALUES (?, ?, ?, ?, ?, ?)";

            $stmtInsert = $conn->prepare($insertUser);
            $stmtInsert->bind_param(
                "ssssss",
                $employeeid,
                $ppicture,
                $fname,
                $lname,
                $hashedPassword, 
                $departmen
            );

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

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const termsAgree = document.getElementById('termsAgree');
            const termsAccept = document.getElementById('termsAccept');
            
            // Update button state when checkbox changes
            termsAgree.addEventListener('change', function() {
                termsAccept.disabled = !this.checked;
            });
            
            // Handle accept button click
            termsAccept.addEventListener('click', function() {
                document.getElementById('signupForm').submit();
            });
            
            // Handle decline button click
            document.getElementById('termsDecline').addEventListener('click', function() {
                document.getElementById('termsModal').style.display = 'none';
                alert('You must accept the Privacy Policy and Terms of Service to create an account.');
            });
        });
    </script>
</body>
</html>