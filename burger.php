<?php
session_start(); // Start the session
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navigation</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  
  <style>
  :root {
    --maroonColor: #80050d;
    --yellowColor: #efb954;
    --whiteColor: #ffffff;
    --light-gray: #ebebeb;
  }

  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  body {
    font-family: Arial, sans-serif;
    background: var(--whiteColor);
    color: var(--maroonColor);
  }

  main {
    padding-top: 100px;
  }

  nav {
  background: var(--whiteColor);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 5px 15px;
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 50;
  height: 50px;
  border: 2px solid var(--maroonColor); /* Added border */
}

  nav .logo {
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
  }

  nav .mainMenu {
    display: flex;
    list-style: none;
    align-items: center;
  }

  nav .mainMenu li {
    position: relative;
  }

  nav .mainMenu li a, 
  nav .mainMenu li button.dropdown-btn {
    display: flex;
    align-items: center;
    padding: 8px 10px;
    text-decoration: none;
    text-transform: uppercase;
    color: black;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 14px;
    transition: 0.3s ease;
  }

  nav .mainMenu li a:hover, 
  nav .mainMenu li button.dropdown-btn:hover {
    background: white;
    color: var(--maroonColor);
  }

  nav .openMenu {
  font-size: 1.2rem; /* or 1rem, or even smaller */
  display: none;
  cursor: pointer;
}

nav .openMenu i {
  color: var(--maroonColor);
}

  nav .closeMenu {
    display: none;
    font-size: 2rem;
    position: absolute;
    top: 20px;
    right: 20px;
    cursor: pointer;
  }

  .dropdown-container {
    display: none;
    flex-direction: column;
    background: lightgrey;
    position: absolute;
    top: 100%;
    left: 0;
    min-width: 220px;
    z-index: 20;
  }

  .dropdown-container a {
    color: white;
    padding: 8px 12px;
    font-size: 16px;
    text-align: left;
  }

  .dropdown-container a:hover {
    background: var(--yellowColor);
    color: var(--maroonColor);
  }

  .icons {
    display: flex;
    gap: 10px;
    padding: 10px 20px;
    justify-content: center;
    background: var(--light-gray);
  }

  .icons i {
    color: black;
    cursor: pointer;
    transition: 0.3s;
  }

  .icons i:hover {
    color: var(--maroonColor);
  }

 

  .logo_burger {
  display: flex;
  align-items: center;
}

.logo_burger img {
  width: 40px; /* adjust size ng logo */
  margin-right: 10px;
}

.logo_burger a {
  color: var(--maroonColor);
  font-size: 20px;
  text-decoration: none;
  font-weight: bold;
}

/* Profile Section */
#logout {
  display: flex;
  align-items: center;
}

#profile {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
}

#profile-text {
  color: white;
}

#name {
  font-size: 16px;
  font-weight: bold;
}

#role {
  font-size: 14px;
}

#logout-button button {
  background: none;
  border: none;
  cursor: pointer;
}

#logout-img {
  width: 20px;
  height: 20px;
}


/* Add this to your existing CSS */
#logout-mobile {
  
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 10px;
  background: var(--light-gray);
  margin-top: 10px;
  border-radius: 5px;
}

nav .mainMenu.show #logout-mobile {
  display: flex; /* Show when the mobile menu is open */
  align-items: center;
}

#logout-mobile {
  display: flex;
  align-items: center;
  margin-top: 15px;
}

#profile {
  width: 100px; /* increase size */
  height: 100px;
  border-radius: 50%;
  display: block;
  margin: 20px auto; /* centers it horizontally and adds space around */
  object-fit: cover; /* keeps image aspect ratio and fills the box */
}

#profile-text {
  color: var(--maroonColor);
}

#name {
  font-size: 16px;
  font-weight: bold;
}

#role {
  font-size: 14px;
}

#logout-button button {
  background: none;
  border: none;
  cursor: pointer;
}

#logout-img {
  width: 20px;
  height: 20px;
}

 /* Mobile Styles */
 @media (max-width: 800px) {
    nav .mainMenu {
      flex-direction: column;
      position: fixed;
      background: var(--light-gray);
      top: -100%;
      left: 0;
      width: 100%;
      height: 100vh;
      justify-content: center;
      align-items: center;
      transition: top 0.3s ease;
    }

    nav .mainMenu.show {
      top: 0;
    }

    nav .openMenu {
      display: block;
    }

    nav .closeMenu {
      display: block;
    }

    nav .mainMenu li {
      width: 100%;
      text-align: center;
    }

    nav .mainMenu li a, 
    nav .mainMenu li button.dropdown-btn {
      justify-content: center;
    }

    .dropdown-container {
      position: relative;
    }
  }

  @media (min-width: 801px) {
    nav .openMenu,
    nav .closeMenu {
      display: none;
    }

    nav .mainMenu {
      flex-direction: row;
    }
  }

  @media (min-width: 992px) {
    .mobile-nav {
      display: none;
    }
}

  /* MOBILE VIEW ONLY */
  @media (max-width: 800px) {
    /* Show mobile menu */

    .sidebar {
      display: none;
    }
    nav .mainMenu {
      height: 100vh;
      position: fixed;
      top: -100%;
      right: 0;
      left: 0;
      z-index: 10;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      background: var(--light-gray);
      transition: top 0.5s ease;
    }

    nav .openMenu {
      display: block; /* Show hamburger */
    }

    nav .mainMenu .closeMenu {
      display: block; /* Show close "X" */
      position: absolute;
      top: 20px;
      right: 20px;
    }

    nav .mainMenu li {
      width: 100%;
      text-align: center;
    }

    nav .mainMenu li a, 
    nav .mainMenu li button.dropdown-btn {
      width: 100%;
      justify-content: center;
    }

    .dropdown-container {
      position: relative;
      font-weight: bold;
      top: 0;
      background: lightgrey;
    }
  }

  /* DESKTOP AND LARGER SCREENS: Hide mobile-specific elements */
  @media (min-width: 801px) {
    nav .openMenu,
    nav .mainMenu .closeMenu {
      display: none; /* Hide hamburger and close button on desktop */
    }
  }

/* Mobile Styles */
@media (max-width: 800px) {
  nav .mainMenu {
    flex-direction: column;
    position: fixed;
    background: var(--light-gray);
    top: -100%;
    left: 0;
    width: 100%;
    height: 100vh;
    justify-content: center;
    align-items: center;
    transition: top 0.3s ease;
  }
  nav .mainMenu.show {
    top: 0;
  }

  nav .openMenu {
    display: block;
  }

  nav .closeMenu {
    display: block;
  }

  /* Logout section */
  nav .mainMenu.show #logout-mobile {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
}


@media (min-width: 768px) and (max-width: 1024px) {
  nav {
    height: 60px;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .sidebar {
    display: none;
  }

  nav .openMenu {
    display: block;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--maroonColor);
  }

  nav .mainMenu {
    flex-direction: column;
    position: fixed;
    background: var(--light-gray);
    top: -100%;
    left: 0;
    width: 100%;
    height: 100vh;
    justify-content: flex-start;
    align-items: center;
    text-align: center;
    padding-top: 100px;
    transition: top 0.3s ease;
    z-index: 100;
  }

  nav .mainMenu.show {
    top: 0;
  }

  nav .mainMenu li {
    width: 100%;
    display: flex;
    justify-content: center;
    margin: 10px 0;
  }

  ul.dropdown-container{
    width: 100%;
    display: flex;
    justify-content: center;
    margin: 10px 0;
  }

  nav .mainMenu li a,
  nav .mainMenu li button.dropdown-btn {
    justify-content: center;
    width: auto;
    text-align: center;
    font-size: 18px;
    padding: 12px 20px;
    color: black;
    background: none;
    border: none;
    cursor: pointer;
  }

  nav .mainMenu li a:hover,
  nav .mainMenu li button.dropdown-btn:hover {
    color: var(--maroonColor);
    background: white;
  }

  nav .closeMenu {
    display: block !important; /* Force the close button to show */
    font-size: 2rem;
    position: absolute;
    top: 20px;
    right: 20px;
    cursor: pointer;
    color: var(--maroonColor);
    z-index: 110; /* Ensure it's above other items */
  }

  #profile {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin: 10px auto;
    display: block;
  }

  #profile-text {
    color: var(--maroonColor);
    text-align: center;
  }

  #name,
  #role {
    display: block;
    font-size: 16px;
    font-weight: bold;
  }

  .icons {
    gap: 15px;
    justify-content: center;
    padding: 20px;
  }

  #logout-mobile {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 20px;
  }

  main {
    padding-top: 70px;
  }
}



  </style>
</head>

<body>

<nav>
<div class="logo_burger">
                <img src="PNG/CSA_Logo.png" alt="Logo">  <!-- Add your image file here -->
                <a href="#">CSA</a>
            </div>
  <div class="openMenu"><i class="fa fa-bars"></i></div>

  <ul class="mainMenu">
  <img id="profile" src="uploads/<?php echo $_SESSION['ppicture']; ?>" alt="<?php echo $_SESSION['ppicture']; ?>">
  <div id="profile-text">

        <span id="name"><?php echo $_SESSION['lname'] . " " . $_SESSION['fname']; ?></span>
      </div>

      <div id="profile-text">
        <span id="role"><?php echo $_SESSION['role']; ?></span>
      </div> 
  <br>
  <br>
  <br>
    <li><a href="GSO.php">Home</a></li>

    <li>
      <button class="dropdown-btn">Calendar <i class="fa fa-caret-down"></i></button>
      <ul class="dropdown-container">
        <li><a href="GSO.php?vres=a">Vehicle Reservation Form</a></li>
        <li><a href="GSO.php?vsch=a">Vehicle Schedules</a></li>
      </ul>
    </li>

    <li>
      <button class="dropdown-btn">Requests <i class="fa fa-caret-down"></i></button>
      <ul class="dropdown-container">
        <li><a href="GSO.php?papp=a">Pending Approval</a></li>
        <li><a href="GSO.php?rapp=a">Reservation Approved</a></li>
        <li><a href="GSO.php?creq=a">Cancelled Requests</a></li>
      </ul>
    </li>

    <li>
      <button class="dropdown-btn">Vehicles <i class="fa fa-caret-down"></i></button>
      <ul class="dropdown-container">
        <li><a href="GSO.php?mveh=a">Manage Vehicle</a></li>
        <li><a href="GSO.php?aveh=a">Add Vehicle</a></li>
        <li><a href="GSO.php?mche=a">Maintenance Checklist</a></li>
      </ul>
    </li>

    <li>
      <button class="dropdown-btn">Accounts <i class="fa fa-caret-down"></i></button>
      <ul class="dropdown-container">
        <li><a href="GSO.php?macc=a">Manage Accounts</a></li>
        <li><a href="GSO.php?mdep=a">Manage Departments</a></li>
      </ul>
    </li>

    <li>
      <button class="dropdown-btn">Report <i class="fa fa-caret-down"></i></button>
      <ul class="dropdown-container">
        <li><a href="GSO.php?srep=a">Summary Report</a></li>
        <li><a href="GSO.php?mrep=a"><span>Maintenance Report</span></a></li>
      </ul>
    </li>

<br>

    <div id="logout-mobile">

      <a href="index.php" id="logout-button">
        <button>
          <img id="logout-img" src="PNG/Logout.png" alt="Logout">
        </button>
      </a>
    </div>

    <div class="closeMenu"><i class="fa fa-times"></i></div>

  </ul>
</nav>

<script>
  // Mobile menu toggle
  const openMenu = document.querySelector('.openMenu');
  const closeMenu = document.querySelector('.closeMenu');
  const mainMenu = document.querySelector('.mainMenu');

  openMenu.addEventListener('click', () => {
    mainMenu.classList.add('show');
  });

  closeMenu.addEventListener('click', () => {
    mainMenu.classList.remove('show');
  });

  // Dropdown toggle
  const dropdownBtns = document.querySelectorAll('.dropdown-btn');
  dropdownBtns.forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      const dropdown = this.nextElementSibling;
      document.querySelectorAll('.dropdown-container').forEach(dc => {
        if (dc !== dropdown) {
          dc.style.display = 'none';
        }
      });
      dropdown.style.display = dropdown.style.display === 'flex' ? 'none' : 'flex';
    });
  });

  // Close dropdowns when clicking outside
  window.addEventListener('click', function () {
    document.querySelectorAll('.dropdown-container').forEach(drop => {
      drop.style.display = 'none';
    });
  });

  // Prevent closing dropdown when clicking on button
  document.querySelectorAll('.dropdown-btn').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
    });
  });
</script>

</body>
</html>
