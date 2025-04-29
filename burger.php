<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>burat</title>

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
  main
  {
    padding-top: 100px;
  }

  nav {
  background: var(--maroonColor);
  color: var(--whiteColor);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 5px 15px; /* originally 10px 20px */
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 50;
  height: 50px; /* optional: fixed height if you want */
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
  padding: 8px 10px; /* originally 15px */
  text-decoration: none;
  text-transform: uppercase;
  color: black;
  font-size: 14px; /* originally 18px */
  background: none;
  border: none;
  cursor: pointer;
  transition: 0.2s ease;
  }

  nav .mainMenu li a:hover, 
  nav .mainMenu li button.dropdown-btn:hover {
    background: white;
    color: var(--maroonColor);
  }

  nav .openMenu {
    font-size: 2rem;
    margin: 20px;
    display: none; /* Hide hamburger by default */
    cursor: pointer;
  }

  nav .mainMenu .closeMenu {
    display: none; /* Hide close button by default */
  }

  .icons i {
    color: black;
    transition: 0.3s;
    cursor: pointer;
  }

  .icons {
    display: flex;
    gap: 10px;
    padding: 10px 20px;
    justify-content: center;
    background: var(--light-gray);
  }

  nav .logo {
  margin: 2px;
  cursor: pointer;
  text-transform: uppercase;
  font-size: 18px; /* originally 24px */
  font-weight: bold;
  color: white;
}

  .dropdown-container {
    display: none;
    flex-direction: column;
    background: var(--maroonColor);
    position: absolute;
    top: 100%;
    left: 0;
    min-width: 220px;
    z-index: 20;
  }

  .dropdown-container li {
    width: 100%;
  }

  .dropdown-container li a {
    display: block;
    padding: 8px 12px; /* make it smaller too */
    color: var(--whiteColor);
    font-size: 16px;
    text-transform: none;
    text-align: left;
  }

  .dropdown-container li a:hover {
    background: var(--yellowColor);
    color: var(--maroonColor);
  }

  .fa-facebook:hover,
  .fa-twitter:hover,
  .fa-instagram:hover,
  .fa-github:hover {
    color: #80050d;
  }

  .dropdown-btn img {
    width: 20px;
    height: 20px;
    margin-right: 8px;
    vertical-align: middle;
  }

  .dropdown-btn i {
    margin-left: 5px;
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
      top: 0;
      background: var(--maroonColor);
    }
  }

  /* DESKTOP AND LARGER SCREENS: Hide mobile-specific elements */
  @media (min-width: 801px) {
    nav .openMenu,
    nav .mainMenu .closeMenu {
      display: none; /* Hide hamburger and close button on desktop */
    }

    nav .mainMenu {
      display: flex !important; /* Ensure horizontal menu is visible */
      flex-direction: row !important;
    }
  }
</style>
</head>

<body>

<nav class="mobile-nav">
    <div class="logo">JABOL</div>
    <div class="openMenu"><i class="fa fa-bars"></i></div>
    <ul class="mainMenu">
      <li><a href="#">Home</a></li>
      <li>
        <button onclick="toggleDropdown(this)" class="dropdown-btn">
          Calendar
          <i class="fa fa-caret-down"></i>
        </button>
        <ul class="dropdown-container">
          <li><a href="GSO.php?vres=a">Vehicle Reservation Form</a></li>
          <li><a href="GSO.php?vsch=a">Vehicle Schedules</a></li>
          <li><a href="GSO.php?dsch=a">Driver Schedules</a></li>
        </ul>
      </li>
      <li>
        <button onclick="toggleDropdown(this)" class="dropdown-btn">
          Requests
          <i class="fa fa-caret-down"></i>
        </button>
        <ul class="dropdown-container">
          <li><a href="GSO.php?papp=a">Pending Approval</a></li>
          <li><a href="GSO.php?rapp=a">Reservation Approved</a></li>
          <li><a href="GSO.php?creq=a">Cancelled Requests</a></li>
        </ul>
      </li>

      <li>
                     <button onclick="toggleDropdown(this)" class="dropdown-btn" id="vehicle">
                        <span>Vehicles</span>
                        <i class="fa fa-caret-down"></i>
                     </button>
                     <ul class="dropdown-container">
                        <div>
                           <li><a href="GSO.php?mveh=a"><span>Manage Vehicle</span></a></li>
                           <li><a href="GSO.php?aveh=a"><span>Add Vehicle</span></a></li>
                           <li><a href="GSO.php?mche=a"><span>Maintenance Checklist</span></a></li>
                        </div>
                     </ul>
                  </li>

                  <li>
                     <button onclick="toggleDropdown(this)" class="dropdown-btn" id="account">
                        <span>Accounts</span>
                        <i class="fa fa-caret-down"></i>
                     </button>
                     <ul class="dropdown-container">
                        <div>
                           <li><a href="GSO.php?macc=a"><span>Manage Accounts</span></a></li>
                           <li><a href="GSO.php?mdep=a"><span>Manage Departments</span></a></li>
                        </div>
                     </ul>
                  </li>

                  <li>
                     <button onclick="toggleDropdown(this)" class="dropdown-btn" id="report">
                        <span>Report</span>
                        <i class="fa fa-caret-down"></i>
                     </button>
                     <ul class="dropdown-container">
                        <div>
                           <li><a href="GSO.php?srep=a"><span>Summary Report</span></a></li>
                        </div>
                     </ul>
                  </li>

                  <div class="closeMenu"><i class="fa fa-times"></i></div>
      <span class="icons">
        <i class="fab fa-facebook"></i>
        <i class="fab fa-instagram"></i>
        <i class="fab fa-twitter"></i>
        <i class="fab fa-github"></i>
      </span>
    </ul>

   
  </nav>

  <script>
    const mainMenu = document.querySelector('.mainMenu');
    const closeMenu = document.querySelector('.closeMenu');
    const openMenu = document.querySelector('.openMenu');
    const menuItems = document.querySelectorAll('nav .mainMenu li a');
    const dropdownBtns = document.querySelectorAll('.dropdown-btn');

    openMenu.addEventListener('click', show);
    closeMenu.addEventListener('click', hide);

    menuItems.forEach(item => {
      item.addEventListener('click', hide);
    });

    function show() {
      mainMenu.style.top = '0';
    }
    function hide() {
      mainMenu.style.top = '-100%';
      closeAllDropdowns();
    }

    function toggleDropdown(btn) {
      const dropdown = btn.nextElementSibling;
      const isOpen = dropdown.style.display === 'flex';
      closeAllDropdowns();
      if (!isOpen) {
        dropdown.style.display = 'flex';
      }
    }

    function closeAllDropdowns() {
      const allDropdowns = document.querySelectorAll('.dropdown-container');
      allDropdowns.forEach(dropdown => {
        dropdown.style.display = 'none';
      });
    }
  </script>

</body>
</html>
