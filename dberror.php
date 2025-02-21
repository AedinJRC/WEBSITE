<!DOCTYPE html>
<html>
<head>
    <title>Database Error</title>
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
    <h1>Database Connection Error</h1>
    <p>
        <?php
        if (isset($_GET['error'])) {
            echo htmlspecialchars($_GET['error']);
        } else {
            echo "An unknown error occurred.";
        }
        ?>
    </p>
    <p>That's all we know.</p>
    <a href="index.php">
      <button>Go Back</button>
    </a>
    <img src="dberror.png" alt="error image">
</body>
</html>