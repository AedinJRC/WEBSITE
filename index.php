<?php
   session_start();
   ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FontAwesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <title>Vehicle Monitoring and Maintenance System</title>
    <style>
        
    
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    text-decoration: none;
}

html {
    scroll-behavior: smooth;
}

:root
         {
            --maroonColor: #80050d;
            --arrowSize: 5%;
            --yellowColor: #efb954;
         }

body {
    font-family: Arial, sans-serif;
    background-color: #f1f0f0;
}

.vh_1 {
    height: 100vh;
}

.vh_2 {
    height: 100vh;
}


.navbar {
    position: fixed; /* Fix navbar at the top */
    top: 0;
    left: 0;
    width: 100%;
    background: white;
    padding: 15px 20px;
    border: 2px solid #80050d;
    border-radius: 0; /* Remove border radius to make it full-width */
    z-index: 1000;
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1); /* Add a shadow for better visibility */
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
    width: 65px;
    height: auto;
    margin-right: 10px;
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
}

.nav-links li a {
    color: black;
    font-size: 16px;
    font-weight: bold;
    transition: color 0.3s ease;
}

.nav-links li a:hover {
    color: #80050d;
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
    background-color: #efb954;
    color: #80050d;
}

.nav-actions a {
    color: #80050d;
    font-size: 15px;
    font-weight: bold;
    transition: color 0.3s ease;
}

.nav-actions a:hover {
    color: #efb954;
}

.nav-actions button.signup {
    background-color: #80050d;
    border: 3px solid #80050d;
    padding: 10px;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.nav-actions a button.signup  {
    color: #efb954;
    font-size: 15px;
    font-weight: bold;
    text-decoration: none;
}

.nav-actions button.signup:hover {
    background-color: #efb954;
}

.nav-actions a button.signup:hover  {
    color: #80050d;
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

/* Responsive Navbar */
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

    .nav-actions {
        display: none;
    }

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



/* Header Section */
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
    align-items: center;
    gap: 45px;
    padding: 15px;
    background-color: #f9f9f9;
    color: #80050d;
}

.red-bar {
    width: 30px;
    height: 400px;
    border-radius: 10px;
    background-color: #80050d;
    margin-left: 80px;
}

.text-section {
    max-width: 600px;
}

@media (max-width: 1366px) {
    .text-section h1 {
        font-size: 65px;
        font-weight: bold;
        margin-bottom: 10px;
    }
}

@media (min-width: 1367px) {
    .text-section h1 {
        font-size: 80px;
        font-weight: bold;
        margin-bottom: 10px;
    }
}

.text-section span {
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: gray;
}


/* Main Container */
.main-container {
    width: calc(100% - 100px);
    max-width: 1700px;
    margin: 0 auto 0 100px;
    padding: 20px;
}
.right-section {
    display: flex;
    justify-content: flex-end;
    padding-right: 100px;
    align-items: center;
    background-color: #f9f9f9;
}
@media (max-width: 1366px) {
    .right-section img {
        max-width: 300px;
        border-radius: 50%;
        transition: 0.3s;
    }
}

@media (min-width: 1367px) {
    .right-section img {
        max-width: 400px;
        border-radius: 50%;
        transition: 0.3s;
    }
}
/* Five Logo Section */
.five_logo {
    display: flex;
    justify-content: space-around;
    align-items: center;
    background-color: #fff;
    padding: 5px;
    margin-top: 20px;
    border: 2px solid #80050d;
    border-radius: 10px;
}

.five_logo img {
    max-width: 80px;
    height: auto;
    border: 1px solid #80050d;
    border-radius: 50%;
    padding: 5px;
}

@media (max-width: 768px) {
    .main-container {
        width: 85%;
        margin: 0 auto;
        padding: 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .five_logo {
        padding: 10px;
        width: 100%; /* Ensuring five_logo takes full width */
        justify-content: space-around;
        align-items: center;
    }

    .five_logo img {
        max-width: 50px; /* Even smaller image on mobile */
        padding: 5px;
    }
}

/* Responsive Header & Five Logo */
@media (max-width: 768px) {
    .header {
        grid-template-columns: 1fr;
        height: auto;
        text-align: center;
        padding: 20px;
    }

    .left-section {
        flex-direction: column;
        align-items: center;
        gap: 20px;
        text-align: center;
    }

    .red-bar {
        height: 10px;
        width: 80px;
        margin: 0 auto;
    }

    .text-section h1 {
        font-size: 50px;
    }

    .text-section span {
        font-size: 12px;
    }

    .right-section {
        justify-content: center;
        text-align: center;
        padding: 20px;
    }

    .right-section img {
        max-width: 250px;
    }
}

/* ___________________________________*/
.vh_2 {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

/* Box container */
.box1 {
    background: white;
    padding: 30px 40px;
    border: 3px solid #efb954;
    border-radius: 35px;
    width: 90%; /* Adjust width dynamically */
    max-width: 1500px;
    height: auto; /* Allow height to adjust dynamically */
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap; /* Allow stacking on smaller screens */
    gap: 20px;
}

/* Introduce Section */
.introduce {
    flex: 1;
    max-width: 700px;
    text-align: left;
}

.introduce h1 {
    font-size: clamp(40px, 6vw, 70px); /* Responsive font scaling */
    font-weight: bold;
    color: #80050d;
    margin-left: 5%;
}

.introduce h2 {
    font-size: clamp(20px, 2vw, 34px);
    color: #333;
    line-height: 1.5;
    max-width: 600px;
    padding-top: 10px;
    margin-left: 5%;
}

/* Image Section */
.intro_pic {
    flex-shrink: 0;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    width: 40%;
}

.intro_pic img {
    width: 100%;
    max-width: 450px;
    height: auto;
    margin-right: 5%;
}

/* Button Container */
.intro_butt_container {
    margin-top: 40px;
    margin-left: 5%;
}

/* Button styles */
.intro_butt {
    background-color: #80050d;
    border: #efb954 3px solid;
    padding: 15px 30px;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    border-radius: 10px;
    display: inline-block;
    text-align: center;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.intro_butt a {
    text-decoration: none;
    color: white;
}

.intro_butt:hover {
    background-color: white;
    border: #80050d 3px solid;
    transform: scale(1.05);
}

.intro_butt:hover a {
    color: #80050d;
}

/* Responsive Adjustments */
@media (max-width: 1024px) {
    .box1 {
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 20px;
    }

    .introduce {
        max-width: 100%;
    }

    .introduce h1,
    .introduce h2 {
        margin-left: 0;
    }

    .intro_pic {
        width: 80%;
        justify-content: center;
        margin-right: 0;
    }

    .intro_pic img {
        max-width: 300px;
    }

    .intro_butt_container {
        margin-left: 0;
        text-align: center;
    }
}

@media (max-width: 768px) {
    .box1 {
        padding: 20px;
        width: 100%;
    }

    .introduce h1 {
        text-align: center;
    }

    .introduce h2 {
        text-align: center;
    }

    .intro_pic {
        width: 100%;
        justify-content: center;
    }

    .intro_pic img {
        max-width: 250px;
    }

    .intro_butt {
        padding: 12px 20px;
        font-size: 16px;
    }
}

@media (max-width: 480px) {
    .introduce h1 {
        font-size: 35px;
    }

    .introduce h2 {
        font-size: 20px;
    }

    .intro_pic img {
        max-width: 200px;
    }

    .intro_butt {
        padding: 10px 15px;
        font-size: 14px;
    }
}

/* _____________________________________ */
.create_acc { 
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.box2 {
    background: white;
    padding: 70px 80px; /* More padding for a larger feel */
    border: 5px solid #efb954; /* Thicker border for emphasis */
    border-radius: 40px;
    width: 95%; /* Takes almost full width */
    max-width: 1200px; /* Significantly increased width */
    min-height: 600px; /* Taller box */
    display: flex;
    align-items: center;
    text-align: center;
}

/* Image Section */
.create_pic {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}

.create_pic img {
    width: 100%;
    max-width: 250px; /* Adjust logo size */
    height: auto;
}

/* Heading */
.create_acc h1 {
    font-size: clamp(20px, 4vw, 50px); /* Responsive font size */
    font-weight: bold;
    color: #80050d;
    margin: 10px 0;
}

.create_butt_container {
    margin-top: 80px;
}

.line {
    width: 35%; /* Controls the width of the line */
    border: 0;
    border-top: 3px solid rgba(110, 103, 91, 0.4);
    margin: 20px 0; /* Adds space above and below the line */
}

/* Responsive Styles */
@media (max-width: 1200px) {
    .box2 {
        padding: 50px 60px; /* Adjust padding for smaller screens */
    }

    .create_acc h1 {
        font-size: clamp(20px, 4vw, 40px); /* Smaller font size for medium screens */
    }

    .create_pic img {
        max-width: 200px; /* Slightly smaller image */
    }

    .line {
        width: 50%; /* Adjust line width */
    }
}

@media (max-width: 768px) {
    .box2 {
        padding: 40px 50px; /* Smaller padding */
        min-height: 500px; /* Reduce height */
    }

    .create_acc h1 {
        font-size: clamp(18px, 5vw, 36px); /* Smaller font for smaller screens */
    }

    .create_pic img {
        max-width: 180px; /* Smaller image */
    }

    .line {
        width: 60%; /* Line width for smaller screens */
    }

    .create_butt_container {
        font-size: 4px;
        margin-top: 50px; /* Less margin */
    }
}

@media (max-width: 480px) {
    .box2 {
        padding: 30px 40px; /* Much smaller padding */
        min-height: 400px; /* Reduce height further */
    }

    .create_acc h1 {
        font-size: clamp(16px, 6vw, 32px); /* Even smaller font for mobile */
    }

    .create_pic img {
        max-width: 150px; /* Even smaller image */
    }

    .line {
        width: 70%; /* Line width for very small screens */
    }

    .create_butt_container {
        margin-top: 30px; /* Less margin */
        font-size: 4px;
    }
}


/* _____________________________________ */
.csa_down {
    max-width: 100px;
    height: auto;
    display: block;
    margin: 0 auto 1rem;
}

/* Responsive container */
.container_land {
    max-width: 1500px;
    margin: auto;
    padding: 5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap; /* Allows items to wrap on smaller screens */
    font-size: clamp(0.8rem, 2vw, 1.2rem);
}

/* Footer styles */
footer {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    background-color: #f9f9f9;
    padding: 1rem;
    color: #333;
    border-top: 3px solid var(--maroonColor);
    font-size: clamp(0.8rem, 2vw, 1.1rem);
}

/* Footer column responsiveness */
footer .column {
    flex: 1;
    min-width: 250px; /* Adjusted to avoid shrinking too much */
    text-align: left;
    padding: 10px;
}

/* Center first column */
footer .column:first-child {
    text-align: center;
    flex: 1.5;
}

/* Social media icons */
footer .column .socials {
    display: flex;
    justify-content: center;
    gap: 0.8rem;
    margin-top: 1rem;
    flex-wrap: wrap; /* Allows better wrapping on smaller screens */
}

footer .column .socials a {
    color: black;
    border: 1px solid var(--maroonColor);
    padding: 10px;
    font-size: clamp(1rem, 2vw, 1.25rem);
    border-radius: 50%;
    transition: all 0.3s ease;
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

footer .column .socials a:hover {
    color: #fff;
    background-color: var(--yellowColor);
}

/* Footer column headings */
footer .column h4 {
    color: var(--maroonColor);
    margin-bottom: 1rem;
    font-size: clamp(1rem, 2.5vw, 1.2rem);
    font-weight: 600;
}

/* Footer links */
footer .column > a {
    display: block;
    color: #666;
    text-decoration: none;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
    font-size: clamp(0.9rem, 2vw, 1rem);
}

footer .column > a:hover {
    color: var(--yellowColor);
}

/* Copyright Section */
.copyright {
    text-align: center;
    font-size: clamp(0.7rem, 1.5vw, 0.8rem);
    color: #aaa;
    margin-top: 1rem;
    width: 100%;
}

/* Responsive Adjustments */
@media (max-width: 1024px) {
    .container_land {
        padding: 3rem 1.5rem;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    footer {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    footer .column {
        min-width: 100%;
        text-align: center;
    }
}

@media (max-width: 768px) {
    .container_land {
        padding: 1rem .5rem;
    }

    footer .column {
        min-width: 100%;
        padding: 10px 0;
    }

    footer .column .socials {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .container_land {
        padding: 1rem .5rem;
    }

    footer {
        padding: .5rem;
    }

    footer .column h4 {
        font-size: clamp(0.9rem, 2vw, 1rem);
    }

    .copyright {
        font-size: clamp(0.6rem, 1.2vw, 0.7rem);
    }
}

/* _____________________________________ */
.vh_4 {
    height: 100vh;
    display: flex;
    justify-content: center; 
    align-items: center; 
    padding: 20px; /* Prevents elements from touching screen edges */
    box-sizing: border-box;
}

.GSO_ins {
    display: flex;
    justify-content: center;
    align-items: center; 
    width: 100%; 
    padding: 10px;
    margin-top: 30vh; /* Adjust dynamically based on viewport height */
    flex-wrap: wrap; /* Allows items to adjust in smaller screens */
}

.GSO_ins img {
    max-width: 100%; /* Ensures image scales properly */
    height: auto;
    display: block; 
    border: 5px solid #efb954; /* Thicker border for emphasis */
    border-radius: 40px;
    padding: 20px;
    max-height: 500px; /* Prevents the image from being too large */
}

.box4 {
    padding: 5vw; /* Makes padding responsive */
    width: 90%; 
    max-width: 1200px; 
    min-height: auto; /* Allows flexibility */
    display: flex;
    align-items: center;
    text-align: center;
    flex-wrap: wrap; /* Allows items to stack if needed */
}

/* Media Queries for different screen sizes */
@media (max-width: 1024px) {
    .box4 {
        padding: 40px; /* Reduce padding for smaller screens */
    }
    
    .GSO_ins img {
        max-width: 80%;
    }
}

@media (max-width: 768px) {
    .vh_4 {
        height: auto; /* Allows content to dictate height */
        padding: 10px;
    }

    .GSO_ins {
        margin-top: 5vh; /* Reduce margin for smaller screens */
        flex-direction: column; /* Stack items vertically */
    }

    .GSO_ins img {
        max-width: 90%; /* Ensures better fit on small screens */
        padding: 10px;
    }

    .box4 {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .GSO_ins img {
        max-width: 100%;
        padding: 5px;
    }

    .box4 {
        padding: 15px;
    }
}

<?php
    if(isset($_GET["log"]) and !empty($_GET["log"]))
    {
        ?>
            h1 {
                padding-top: 2rem;
                margin-bottom: 20px;
                font-weight: normal;
                text-align: center;
            }
            form {
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 100%;
                padding: 0 35%;
                padding-bottom: 5rem;
                text-align: center;
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
            .forgot-password {
                margin-top: 10px;
                font-size: 12px;
                color: grey;
            }
        <?php
    }
    elseif(isset($_GET["sig"]) and !empty($_GET["sig"]))
    {
        ?>
            h1 {
                padding-top: 2rem;
                margin-bottom: 20px;
                font-weight: normal;
                text-align: center;
            }
            form {
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 100%;
                padding: 0 30%;
                padding-bottom: 2rem;
                text-align: center;
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
        <?php
       
    }

?>
#space
{
    height:90px;
}

#welcome
{
    height: 64vh;
    display: flex;
    justify-content: center;
    align-items: center;
    
}





    </style>
    <script src="app.js" defer></script>
</head>
<body>

<div class="vh_1" id="home">
    <nav class="navbar">
        <div class="navdiv">
            <div class="logo">
                <img src="PNG/CSA_Logo.png" alt="Logo">  <!-- Add your image file here -->
                <a href="#">CSA</a>
            </div>
            <span class="menu-toggle" onclick="toggleMenu()">☰</span>
            <ul class="nav-links" id="navMenu">
            <li>
            <a href="
                <?php
                    if(isset($_GET["log"]) and !empty($_GET["log"]) or isset($_GET["sig"]) and !empty($_GET["sig"]))
                    echo "index.php";
                    else
                    echo "#\" onclick=\"scrollToSection('home', event)";
                ?>
                ">Home
            </a></li>
<li><a href="#" onclick="scrollToSection('about', event)">About</a></li>
<li><a href="#" onclick="scrollToSection('contact', event)">Contact</a></li>
            </ul>
            <div class="nav-actions" id="navActions">
                <a href="index.php?log=a">LOGIN</a>
                        <a href="
                            <?php
                                if(isset($_GET["log"]) and !empty($_GET["log"]))
                                echo "index.php?sig=a";
                                else
                                echo "#\" onclick=\"scrollToSection('vh_3', event)";
                            ?>
                        ">
                        <button class="signup">
                            SIGNUP
                        </button>
                    </a>
            </div>
        </div>
        
    </nav>
    <div id="space"></div>
    <?php
        if(isset($_GET["log"]) and !empty($_GET["log"]))
        login();
        elseif(isset($_GET["sig"]) and !empty($_GET["sig"]))
        signup();
        else
        index();
    ?>
<footer>
    <div class="column" id="contact">
        <img src="PNG/CSA_Logo.png" class="csa_down" alt="CSA Logo">
        <div class="socials">
            <a href="#"><i class="fa-brands fa-youtube"></i></a>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
            <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
        </div>
    </div>

    <div class="column">
        <h4>About Us</h4>
        <a href="#">Blogs</a>
        <a href="#">Channels</a>
        <a href="#">Sponsors</a>
    </div>

    <div class="column">
        <h4>Contact</h4>
        <a href="#">Contact Us</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms & Conditions</a>
    </div>

    <div class="copyright">
        Copyright © 2025 Maynard Matley. All Rights Reserved.
    </div>
</footer>
</body>
</html>
<?php
    function index() {
        ?>
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
                <div class="five_logo">
                <img src="PNG/Clinic_Logo.png" alt="Logo 1">
                <img src="PNG/College_Logo.png" alt="Logo 2">
                <img src="PNG/GTC_Logo.png" alt="Logo 3">
                <img src="PNG/HR_Logo.png" alt="Logo 4">
                <img src="PNG/VPSA_Logo.png" alt="Logo 5">
                </div>
            </div>

        </div>

        <div class="vh_2" id="about">
            <div class="box1">
                <div class="introduce">
                    <h1>Introducing Good Solution</h1>
                    <h2>An online, for CSA-Biñan employees to manage their school vehicle reservation.</h2>
                    <div class="intro_butt_container">
                        <button class="intro_butt"><a href="#">Try now</a></button>
                    </div>
                </div>
                <div class="intro_pic">
                    <img src="PNG/cpu.png" alt="Logo">
                </div>
        
            
            </div>
        </div>


        <div class="vh_2" id="vh_3">
            <div class="box2">
                <div class="create_acc">
                    <div class="create_pic">
                        <img src="PNG/GSO logo.png" alt="Logo">
                    </div>
                    <h1>To create account</h1>
                    <div class="create_butt_container">
                        <button class="intro_butt"><a href="index.php?sig=a">Sign up now</a></button>
                    </div>
                    <hr class="line">
                </div>
            </div>
        </div>

        <div class="vh_4" id="vh_4">
            <div class="box4">
                    <div class="GSO_ins">
                        <img src="PNG/GSO_ins.jpg" alt="Logo">
                    </div>
            </div>
        </div>

        <?php
    }
    function login() {
        ?>
             <div id="welcome">
                <form method="post" action="index.php?log=a">
                <h1>Welcome</h1>
                    <label for="employee">Employee No.</label>
                    <input type="text" id="employee" name="employee-number" required>
                    
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    
                    <button type="submit" class="btn" name="logbtn">LET'S START !</button>
                    <p class="forgot-password">Forgot your password?</p>
                </form>
                <?php
                    if(isset($_POST["logbtn"]))
                    {
                        include("config.php");
                        $loginselect = "SELECT * FROM usertb WHERE employeeid = '".$_POST["employee-number"]."' AND pword = '".$_POST["password"]."'";
                        $loginquery = mysqli_query($conn, $loginselect);
                        $loginrow = mysqli_fetch_array($loginquery);
                        if($loginrow)
                        {
                            $_SESSION["employeeid"] = $loginrow["employeeid"];
                            $_SESSION["ppicture"] = $loginrow["ppicture"];
                            $_SESSION["fname"] = $loginrow["fname"];
                            $_SESSION["lname"] = $loginrow["lname"];
                            $_SESSION["role"] = $loginrow["role"];
                            $_SESSION["created_at"] = $loginrow["created_at"];
                        }
                        else
                        {
                            echo "<script>alert('Invalid login credentials.')</script>";
                        }
                        $logincount = mysqli_num_rows($loginquery);
                        if($logincount == 1)
                        {
                            if($loginrow["role"]=="Admin")
                            {
                                header("location: gso.php");
                            }
                            else
                            {
                                header("location: user.php");
                            }
                        }
                    }
                ?>
            </div>
            
        <?php
    }
    function signup() {
        ?>
            <div id="welcome">
                <form method="post" action="index.php?sig=a" onsubmit="return validatePasswords()">
                    <h1>Welcome</h1>
                    <label for="employee">Employee No.</label>
                    <input type="text" id="employee" name="employee-number" required>
                    
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" name="first-name" required>
                    
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" name="last-name" required>
                    
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    
                    <label for="retype-password">Re-type Password</label>
                    <input type="password" id="retype-password" name="retype-password" required>
                    
                    <button type="submit" class="btn" name="sigbtn">LET'S START !</button>
                </form>
                <?php
                    if(isset($_POST["sigbtn"]))
                    {
                        include("config.php");
                        $signupinsert = "INSERT INTO usertb(employeeid, ppicture, fname, lname, pword, created_by) VALUES('".$_POST["employee-number"]."', '".$_POST["first-name"]."', '".$_POST["last-name"]."', '".$_POST["password"]."')";
                        $signupquery = mysqli_query($conn, $signupinsert);
                    }
                ?>
            </div>
        <?php
    }
?>
