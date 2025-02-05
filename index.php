<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Navigation Bar</title>
    <style>
    
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f1f0f0;
        }

        .navbar {
            background: white;
            padding: 15px 20px;
            border: 2px solid #80050d; 
            border-radius: 8px;
        }

        .navdiv {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 65px;  /* Set width for the image */
            height: auto;
            margin-right: 10px;  /* Add some space between the image and text */
            margin-left: 20px;
        }

        .logo a {
            font-size: 29px;
            font-weight: 600;
            color: #80050d;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
            justify-content: center;
            flex: 1;
        }

        .nav-links li a {
            color: black;
            font-size: 16px;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .nav-links li a:hover {
            color: #80050d; /* Change the color on hover */
        }

        .nav-actions button {
            border: none;
            border-radius: 10px;
            padding: 10px;
            margin: 0 20px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .nav-actions button:hover {
            background-color: #efb954; /* Change background color on hover */
            color: #80050d; /* Change text color on hover */
        }

        .nav-actions a {
            color: #80050d;
            font-size: 15px;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .nav-actions a:hover {
            color: #efb954; /* Change color on hover */
        }

        .nav-actions button.signup {
    background-color: #80050d;
    border: none;
    border-radius: 10px;
    padding: 10px;
    cursor: pointer;
    border: 3px solid #80050d; 
    transition: background-color 0.3s ease, color 0.3s ease;
}

.nav-actions button.signup a {
    color: #efb954; /* Change the text color to yellow */
    font-size: 15px;
    font-weight: bold;
    text-decoration: none; /* Remove underline from the link */
    box-sizing: border-box;
}

.nav-actions button.signup:hover {
background-color: #efb954;
}

.nav-actions button.signup:hover a {
    color: #80050d; /* Change the text color to red on hover */
}

        .menu-toggle {
            display: none;
            font-size: 24px;
            color: #80050d;
            cursor: pointer;
            position: absolute;
            top: 15px;
            right: 20px;
            user-select: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-links {
                flex-direction: column;
                display: none;
                width: 100%;
                background: white;
                text-align: center;
                padding: 10px 0;
            }

            .nav-links.show {
                display: flex;
            }

            .menu-toggle {
                display: block;
            }

            .navdiv {
                flex-direction: column;
                align-items: flex-start;
            }

            /* Hide buttons in navbar on small screens */
            .nav-actions {
                display: none;
            }

            /* Show buttons inside the toggle menu when it's open */
            .nav-actions.show {
                display: flex;
                flex-direction: column;
                gap: 10px;
                margin-top: 10px;
                align-items: center;
                width: 100%;
            }

            button {
                width: 80%;
                text-align: center;
            }
        }


        .main-container {
      width: 100%;
      max-width: 1200px;
    }

    .header {
    display: grid;
    grid-template-columns: 1fr 1fr;
    height: 70vh;
    margin-top: 20px;
    border-radius: 10px;
    overflow: hidden;
}

.left-section {
    display: flex;
    align-items: center; /* Aligns content vertically */
    gap: 15px; /* Space between red bar and text */
    padding: 20px;
    background-color: #f9f9f9;
    color: #80050d;
}

.red-bar {
    width: 30px;
    height: 300px;
    border-radius: 10px;
    background-color: #80050d;
    margin: 80px;
}

.text-section span {
    font-size: 38px; /* Adjust size for "Vehicle Monitoring and Maintenance" */
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: gray;
}

.text-section h1 {
    font-size: 70px;
    font-weight: bold;
    margin-bottom: 10px;
}

.text-section span {
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: gray;
}

.right-section {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f9f9f9;
}

.right-section img {
    max-width: 400px;
    border-radius: 50%;
}

    .footer {
      display: flex;
      justify-content: space-around;
      align-items: center;
      background-color: #fff;
      padding: 20px;
      margin-top: 20px;
      border: 2px solid #80050d;
      border-radius: 10px;
    }

    .footer img {
      max-width: 100px;
      height: auto;
      border: 1px solid #80050d;
      border-radius: 50%;
      padding: 5px;
    }

    @media (max-width: 768px) {
      .header {
        grid-template-columns: 1fr;
        height: auto;
      }

      .footer img {
        max-width: 70px;
      }
    }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navdiv">
            <div class="logo">
                <img src="PNG/CSA_Logo.png" alt="Logo">  <!-- Add your image file here -->
                <a href="#">CSA</a>
            </div>
            <span class="menu-toggle" onclick="toggleMenu()">☰</span>
            <ul class="nav-links" id="navMenu">
                <li><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
            <div class="nav-actions" id="navActions">
                <a href="#">LOGIN</a>
                <button class="signup"><a href="#">SIGNUP</a></button>
            </div>
        </div>
    </nav>
   
    <!-- Header Section -->
    <div class="header">
    <div class="left-section">
        <div class="red-bar"></div>
        <div class="text-section">
            <span>Vehicle Monitoring and Maintenance</span>
            <h1>General Services Office</h1>
        </div>
    </div>
    <div class="right-section">
        <img src="PNG/GSO Logo.png" alt="Logo">
    </div>
</div>
 

    <div class="main-container">
    <!-- Footer Section -->
    <div class="footer">
      <img src="logo1.png" alt="Logo 1">
      <img src="logo2.png" alt="Logo 2">
      <img src="logo3.png" alt="Logo 3">
      <img src="logo4.png" alt="Logo 4">
      <img src="logo5.png" alt="Logo 5">
    </div>
  </div>


    <script>
        function toggleMenu() {
            const menu = document.getElementById('navMenu');
            const actions = document.getElementById('navActions');
            menu.classList.toggle('show');
            actions.classList.toggle('show');
        }
    </script>
</body>
</html>
