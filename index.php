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

    <title>Vehicle Reservation and Maintenance System</title>
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
    background-image: url('PNG/Background.png');
    background-repeat: no-repeat;       /* Prevents tiling */
    background-size: cover;             /* Scales image to cover entire background */
    background-position: center center; /* Centers the image */
}

.vh_1 {
    height: 100vh;
}


.navbar { 
    position: fixed;
    width: 100%;
    background: white;
    padding: 1vh 1vh; /* 15px 20px */
    border: 0.3vh solid #80050d;
    border-radius: 0;
    z-index: 1000;
    box-shadow: 0 0.28vh 1.4vh rgba(0, 0, 0, 0.1); /* 2px 10px */
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
    width: 9.1vh; /* 65px */
    height: auto;
    margin-right: 1.4vh; /* 10px */
    margin-left: 2.8vh; /* 20px */
}

.logo a {
    font-size: 4.1vh; /* 29px */
    font-weight: 600;
    color: #80050d;
}

.nav-links {
    list-style: none;
    display: flex;
    gap: 2.8vh; /* 20px */
    justify-content: center;
}

.nav-links li a {
    color: black;
    font-size: 1.8vh; /* 16px */
    font-weight: bold;
    transition: color 0.3s ease;
}

.nav-links li a:hover {
    color: #80050d;
}

.nav-actions {
    text-align: center;
}

.nav-actions button {
    border: none;
    border-radius: 1.4vh; /* 10px */
    padding: 1.4vh; /* 10px */
    margin: 0 2.8vh; /* 0 20px */
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.nav-actions button:hover {
    background-color: #efb954;
    color: #80050d;
}

.nav-actions a {
    color: #80050d;
    font-size: 1.8vh; /* 15px */
    font-weight: bold;
    transition: color 0.3s ease;
}

.nav-actions a:hover {
    color: #efb954;
}

.nav-actions button.signup {
    background-color: #80050d;
    border: 0.42vh solid #80050d; /* 3px */
    padding: 1vh; /* 10px */
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.nav-actions a button.signup {
    color: #efb954;
    font-size: 1.8vh; /* 15px */
    font-weight: bold;
    text-decoration: none;
}

.nav-actions button.signup:hover {
    background-color: #efb954;
}

.nav-actions a button.signup:hover {
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
    
    .navbar {
    padding: 5px 2px; /* Smaller padding */
    font-size: 10px;    /* Optional: reduce font size */
  }

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

  .nav-actions a {
    margin: 5px 0;
  }

  .nav-actions .signup {
    margin-left: 0; /* Ensure no left offset */
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
        width: 100%
        
    }
}



/* Header Section */
.header { 
    display: grid;
    grid-template-columns: 1fr 1fr;
    height: 65vh; /* Height already in vh */
    margin-top: 2vh; /* Adjusted margin to vh */
    border-radius: 1.5vh; /* Converted border radius to vh */
    overflow: hidden;
}

.left-section {
    display: flex;
    align-items: center;
    gap: 3vw; /* Converted gap to vw for better proportional spacing */
    padding: 2vh; /* Converted padding to vh */
    color: #80050d;
}

.red-bar {
    width: 1.5vw; /* Converted width to vw for responsiveness */
    height: 45vh; /* Converted height to vh */
    border-radius: 1.5vh; /* Converted border radius to vh */
    background-color: #80050d;
    margin-left: 5vw; /* Adjusted margin to vw */
}

.text-section {
    max-width: 35vw; /* Converted max-width to vw */
}

.text-section span {
    font-size: 2vh; /* Converted font size to vh */
    text-transform: uppercase;
    letter-spacing: 0.3vh; /* Adjusted letter-spacing to vh */
    color: gray;
}

.text-section .title {
    font-size: 8vh; /* Converted font size to vh */
    color: #80050d;
    font-weight: bold;
    width: 130%;
}


/* Main Container */
.main-container {
    width: 100%;
    max-width: 1700px;
    margin: 0 auto;
    padding: 0 20px
}
.right-section {
    display: flex;
    justify-content: flex-end;
    padding-right: 100px;
    align-items: center;
}

.right-section img {
    width: 25vw; /* Adjusted width to be 10% of viewport width */
    height: auto; /* Maintains the aspect ratio of the logo */
}

/* Five Logo Section */
.five_logo {
    display: flex;
    justify-content: space-around;
    align-items: center;
    background-color: #fff;
    padding: 0.65vh; /* 5px ≈ 0.65vh on a 768px height screen */
    margin-top: 2.6vh; /* 20px ≈ 2.6vh */
    border: 0.26vh solid #80050d; /* 2px ≈ 0.26vh */
    border-radius: 1.3vh; /* 10px ≈ 1.3vh */
}

.five_logo img {
    max-width: 10.4vh; /* 80px ≈ 10.4vh */
    height: auto;
    border: 0.13vh solid #80050d; /* 1px ≈ 0.13vh */
    border-radius: 6.5vh; /* 50% remains unchanged as it's relative */
    padding: 0.65vh; /* 5px ≈ 0.65vh */
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
        justify-content: center;
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
    .text-section .title {
        transform: translateX(-10%)
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
        margin-bottom: -10px;
    }

    .text-section .title {
        font-size: 50px;
        display: flex;
        text-align: center;
        justify-content: center;
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
    padding: 2vh;
}

/* Box container */
.box1 {
    background: white;
    padding: 4vh 4.5vh; /* reduced padding */
    border: 0.4vh solid #efb954;
    border-radius: 2.5vh;
    width: 90%;
    max-width: 120vh; /* smaller max width */
    height: auto;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 10vh; /* smaller gap */
}

/* Introduce Section */
.introduce {
    flex: 1;
    max-width: 60vh;
    text-align: left;
}

.introduce h1 {
    font-size: clamp(3.5vh, 5vw, 6vh); /* smaller font */
    font-weight: bold;
    color: #80050d;
    
}

.introduce h2 {
    font-size: clamp(1.8vh, 2vw, 3.2vh); /* smaller font */
    color: #333;
    line-height: 1.4;
    max-width: 55vh;
    padding-top: 1vh;
}

/* Image Section */
.intro_pic {
    flex-shrink: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 35%; /* slightly narrower */
}

.intro_pic img {
    width: 100%;
    max-width: 50vh; /* smaller image */
    height: auto;
    margin-right: 4%;
}

/* Button Container */
.intro_butt_container {
    margin-top: 3vh;
    margin-left: 4%;
}

/* Button styles */
.intro_butt {
    background-color: #80050d;
    border: #efb954 0.2vh solid;
    padding: 1.2vh 1.6vh; /* smaller button size */
    font-size: 1.5vh; /* smaller font */
    font-weight: bold;
    cursor: pointer;
    border-radius: 1vh;
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
    border: #80050d 0.2vh solid;
    transform: scale(1.05);
}

.intro_butt:hover a {
    color: #80050d;
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
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.create_pic img {
    max-width: 25vh; /* Smaller image */
    height: auto;
}
.box2 {
    background: white;
    padding: 4vh 4.5vh;
    border: 0.4vh solid #efb954;
    border-radius: 2.5vh;
    width: 95%;
    max-width: 120vh; /* 700px ≈ 93.5vh */
    min-height: 60vh;
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Image Section */
.create_pic {
    display: flex;
    justify-content: center;
    align-items: center;
  
}


/* Heading */
.create_acc h1 {
    font-size: clamp(2vh, 3vw, 4.5vh); /* Smaller font */
    font-weight: bold;
    color: #80050d;
    margin: 1vh 0;
}

.create_butt_container {
    margin-top: 4.5vh; /* Less vertical space */
}

.line {
    width: 150%;
    border: 0;
    border-top: 0.3vh solid rgba(110, 103, 91, 0.4); /* Thinner line */
    margin: 1.5vh 0;
}

/* Responsive Styles */
@media (max-width: 1500px) {
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
    max-width: 8vh;
    height: auto;
    display: block;
    margin: 0 auto 1vh;
}

.container_land {
    max-width: 130vh;
    margin: auto;
    padding: 3vh 1vh;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

footer {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    background-color: #f9f9f9;
    padding: 2vh;
    color: #333;
    border-top: 0.2vh solid var(--maroonColor);
    font-size: clamp(1.2vh, 1.5vw, 2vh);
}

footer .column {
    flex: 1;
    min-width: 25vh;
    text-align: left;
    padding: 1vh;
}

footer .column:first-child {
    text-align: center;
    flex: 1.2;
}

footer .column .socials {
    display: flex;
    justify-content: center;
    gap: 1.2vh;
    margin-top: 1.6vh;
    flex-wrap: wrap;
}

footer .column .socials a {
    color: black;
    border: 0.1vh solid var(--maroonColor);
    padding: 1vh;
    font-size: 1.8vh;
    border-radius: 50%;
    width: 4.5vh;
    height: 4.5vh;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

footer .column .socials a:hover {
    color: #fff;
    background-color: var(--yellowColor);
}

footer .column h4 {
    color: var(--maroonColor);
    margin-bottom: 1.2vh;
    font-size: clamp(1.4vh, 2vw, 2vh);
    font-weight: 600;
}

footer .column > a {
    display: block;
    color: #666;
    text-decoration: none;
    margin-bottom: 1vh;
    transition: all 0.3s ease;
    font-size: clamp(1.3vh, 1.8vw, 1.6vh);
}

footer .column > a:hover {
    color: var(--yellowColor);
}

.copyright {
    text-align: center;
    font-size: clamp(1.2vh, 1.2vw, 1.5vh);
    color: #aaa;
    margin-top: 2vh;
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
    padding: 20px; 
}

.GSO_ins { 
    display: flex;
    justify-content: center;
    align-items: center; 
    width: 100%; 
    padding: 1vh;
    margin-top: 40vh; /* Already using vh */
    flex-wrap: wrap;
}

.GSO_ins img {
    max-width: 100%;
    height: auto;
    display: block; 
    border: 0.5vh solid #efb954;
    border-radius: 4vh;
    padding: 2vh;
    max-height: 50vh;
}

.box4 {
    padding: 5vw;
    width: 90%; 
    max-width: 1200px;
    min-height: auto;
    display: flex;
    align-items: center;
    text-align: center;
    flex-wrap: wrap;
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
                </a>
            </li>
            <li>
                <a href="
                    <?php
                        if(isset($_GET["log"]) and !empty($_GET["log"]) or isset($_GET["sig"]) and !empty($_GET["sig"]))
                        echo "index.php#about";
                        else
                        echo "#\" onclick=\"scrollToSection('about', event)";
                    ?>
                    ">About
                </a>
            </li>
            <li>
            <a href="
                    <?php
                        if(isset($_GET["log"]) and !empty($_GET["log"]) or isset($_GET["sig"]) and !empty($_GET["sig"]))
                        echo "index.php#contact";
                        else
                        echo "#\" onclick=\"scrollToSection('contact', event)";
                    ?>
                    ">Contact
                </a>
            </li>
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
                        <span>General Services Office</span>
                        <div class="title">Vehicle Reservation and Monitoring System</div>
                    </div>
                </div>
                <div class="right-section">
                    <img src="PNG/GSO_Logo.png" alt="Logo">
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
                    <h2>An online platform for CSA-Biñan employees to manage their school vehicle reservation.</h2>
                    <div class="create_butt_container">
                        <button class="intro_butt"><a href="test.php">Click here</a></button>
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
                            $_SESSION["department"] = $loginrow["department"];
                            $_SESSION["created_at"] = $loginrow["created_at"];
                            $_SESSION["updated_at"] = $loginrow["updated_at"];
                            $logincount = mysqli_num_rows($loginquery);
                            if($logincount == 1)
                                header("location: GSO.php");
                        }
                        else
                        {
                            echo "<script>alert('Invalid login credentials.')</script>";
                        }
                    }
                ?>
            </div>
            
        <?php
    }
    function signup() 
    {
        include("signup.php");
    }
?>
