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

        /* Hide the default file input */
        .signup-file-container input[type="file"] {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        /* Small custom file upload button */
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

        /* Button styles */
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

        /* Avatar upload */
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

        /* Footer links */
        .signup-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 14px;
            width: 450px;
        }

        /* Error message */
        #signup-password-error {
            color: red;
            font-size: 12px;
            margin-top: 2px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="signup-container">

        <form class="signup-form" method="post" enctype="multipart/form-data" onsubmit="return signupValidatePasswords()">
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
                return true;
            }
        }
    </script>
</body>
</html>