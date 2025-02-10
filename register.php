<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
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
            max-width: 400px;
        }
        label {
            display: block;
            text-align: left;
            font-weight: normal;
            margin-bottom: 5px;
            font-size: 14px;
            width: 100%;
        }
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .btn {
            background-color: #7D192E;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            border-radius: 5px;
            font-size: 10px;
            cursor: pointer;
            box-sizing: border-box;
        }
        .btn:hover {
            background-color: #5a1121;
        }
        .forgot-password {
            margin-top: 10px;
            font-size: 12px;
            color: grey;
        }
    </style>
</head>
<body>
    <h1>Welcome</h1>
    <form>
        <label for="employee">Employee No.</label>
        <input type="text" id="employee" name="employee" required>
        
        <label for="first-name">First Name</label>
        <input type="text" id="first-name" name="first-name" required>
        
        <label for="last-name">Last Name</label>
        <input type="text" id="last-name" name="last-name" required>
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        
        <label for="retype-password">Re-type Password</label>
        <input type="password" id="retype-password" name="retype-password" required>
        
        <button type="submit" class="btn">LET'S START !</button>
    </form>
    <p class="forgot-password">Forgot your password?</p>
</body>
</html>
