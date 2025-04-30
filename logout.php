<!DOCTYPE html>
<html>
<head>
    <title>Session Timeout</title>
    <style>
      body
      {
         background-color: #f1f1f1;
         font-family: Arial, sans-serif;
         margin: 5% 10%;
         padding: 0;
         h1
         {
            color: maroon;
            font-size: 4vw;
            margin-bottom: 5vw;
         }
         p
         {
            margin-left: 1vw;
            font-size: 1.3vw;
            width: 50%;
         }
         img
         {
            position: absolute;
            top: 15vw;
            right: 10%;
            width: 30vw;
         }
         button
         {
            margin: 1vw 2vw;
            background-color: maroon;
            color: white;
            padding: .5vw 1vw;
            font-size: 1.5vw;
            border: 5px solid maroon;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.05s;
         }
         button:hover
         {
            transform: scale(1.1);
         }
      }
    </style>
</head>
<body>
    <h1>Session Timed Out</h1>
    <p>
      <?php
        // Define your timeout duration (must match the one in your main page)
        if (isset($_SESSION['timeout'])) {
            echo "Session expired. Please log in again.";
        } else {
         $timeoutSeconds = $_SESSION['timeout'] ?? 0; // Default to 0 if not set

         if ($timeoutSeconds > 0) {
            $timeoutMinutes = floor($timeoutSeconds / 60);
            $timeoutSeconds = $timeoutSeconds % 60;
            echo "Session expired after $timeoutMinutes minutes and $timeoutSeconds seconds of inactivity.";
         } else {
            echo "Session expired. Please log in again.";
         }
        }
      ?>
    </p>
    <p>Click below to return to the main page and log in.</p>
    <a href="index.php">
      <button>Go Back</button>
    </a>
    <img src="PNG/timeout.png" alt="timeout image">
</body>
</html>
