<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vehiclemonitoringdbms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variables
$showPopup = false;
$popupMessage = '';
$isError = false;

if(isset($_POST["sigbtn"])) {
    // Get form data
    $employeeid = $_POST["employee-number"];
    $fname = $_POST["first-name"];
    $lname = $_POST["last-name"];
    $pword = $_POST["password"]; // Storing in plain text (INSECURE)
    
    // Handle file upload
    $ppicture = 'default_avatar.png'; // Default value
    if(isset($_FILES["ppicture"]) && $_FILES["ppicture"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        if(!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES["ppicture"]["name"], PATHINFO_EXTENSION);
        $new_filename = "avatar_" . $employeeid . "." . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if(move_uploaded_file($_FILES["ppicture"]["tmp_name"], $target_file)) {
            $ppicture = $target_file;
        }
    }
    
    // Prepare and execute SQL statement
    $signupinsert = "INSERT INTO usertb (employeeid, ppicture, fname, lname, pword, role) 
                    VALUES (?, ?, ?, ?, ?, 'User')";
    
    $stmt = $conn->prepare($signupinsert);
    $stmt->bind_param("sssss", $employeeid, $ppicture, $fname, $lname, $pword);
    
    if($stmt->execute()) {
        $showPopup = true;
        $popupMessage = "Registration successful!";
    } else {
        $showPopup = true;
        $isError = true;
        $popupMessage = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup Page</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f1f0f0;
            margin: 0;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            font-weight: normal;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 300px;
        }

        label {
            display: block;
            text-align: left;
            font-weight: normal;
            margin-bottom: 5px;
            font-size: 14px;
            width: 150%;
        }

        input {
            width: 150%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .btn {
            background-color: #7D192E;
            color: white;
            padding: 10px;
            border: none;
            width: 150%;
            border-radius: 5px;
            font-size: 10px;
            cursor: pointer;
            box-sizing: border-box;
        }

        .btn:hover {
            background-color: #5a1121;
        }

        #preview {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 1px solid #ccc;
        }

        #password-error {
            display: none;
            color: red;
            font-size: 12px;
            margin-top: -10px;
            margin-bottom: 10px;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 5px;
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Welcome</h1>
    <form method="post" enctype="multipart/form-data" onsubmit="return validatePasswords()">
        <img id="preview" src="default_avatar.png" alt="Avatar Preview">

        <label for="ppicture">Upload Avatar</label>
        <input type="file" name="ppicture" id="ppicture" accept="image/*" onchange="previewImage(this)">

        <label for="employee">Employee No.</label>
        <input type="text" id="employee" name="employee-number" required maxlength="10">

        <label for="first-name">First Name</label>
        <input type="text" id="first-name" name="first-name" required maxlength="30">

        <label for="last-name">Last Name</label>
        <input type="text" id="last-name" name="last-name" required maxlength="20">

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required maxlength="100">

        <label for="retype-password">Re-type Password</label>
        <input type="password" id="retype-password" name="retype-password" required maxlength="100">

        <div id="password-error">Passwords do not match!</div>

        <button type="submit" class="btn" name="sigbtn">LET'S START !</button>
    </form>

    <!-- Modal Popup -->
    <div id="messageModal" class="modal" style="<?php echo $showPopup ? 'display: block;' : 'display: none;'; ?>">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('messageModal').style.display='none'">&times;</span>
            <p class="<?php echo $isError ? 'error' : 'success'; ?>"><?php echo $popupMessage; ?></p>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function validatePasswords() {
            const password = document.getElementById("password").value;
            const retype = document.getElementById("retype-password").value;
            const error = document.getElementById("password-error");

            if (password !== retype) {
                error.style.display = "block";
                return false;
            } else {
                error.style.display = "none";
                return true;
            }
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('messageModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>