<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
        .form-group {
            width: 150%;
            margin-bottom: 15px;
            text-align: left;
        }
        label {
            display: block;
            font-weight: normal;
            margin-bottom: 5px;
            font-size: 14px;
        }
        input {
            width: 100%;
            padding: 10px;
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
        .profile-pic-container {
            width: 150%;
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .profile-pic-preview {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            display: "default_avatar.png";
        }
        .file-input-label {
            display: inline-block;
            padding: 8px 12px;
            background-color: #f1f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }
        .file-input-label:hover {
            background-color: #e0e0e0;
        }
        #profile-pic {
            display: none;
        }
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
    </style>
</head>
<body>
    <h1>Welcome</h1>
    <form method="post" action="" enctype="multipart/form-data" onsubmit="return validatePasswords()">
        <!-- Profile Picture Upload (Optional) -->
        <div class="profile-pic-container">
            <img id="preview" src="default_avatar.png" class="profile-pic-preview" alt="Profile Picture Preview">
            <label for="profile-pic" class="file-input-label">Choose Profile Picture (Optional)</label>
            <input type="file" id="profile-pic" name="profile-pic" accept="image/*" onchange="previewImage(this)">
        </div>
        
        <div class="form-group">
            <label for="employee">Employee No.</label>
            <input type="text" id="employee" name="employee" required>
        </div>
        
        <div class="form-group">
            <label for="first-name">First Name</label>
            <input type="text" id="first-name" name="first-name" required>
        </div>
        
        <div class="form-group">
            <label for="last-name">Last Name</label>
            <input type="text" id="last-name" name="last-name" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm-password" required>
            <div id="password-error" class="error-message">Passwords do not match!</div>
        </div>
        
        <button type="submit" class="btn" name="signup-btn">LET'S START !</button>
    </form>

    <script>
        function validatePasswords() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            const errorElement = document.getElementById('password-error');
            
            if (password !== confirmPassword) {
                errorElement.style.display = 'block';
                return false;
            }
            errorElement.style.display = 'none';
            return true;
        }
        
        function previewImage(input) {
            const preview = document.getElementById('preview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>