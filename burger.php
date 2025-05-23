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
  padding: 5px 10px;
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 50;
  height: 50px;
  border: 2px solid var(--maroonColor);
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
  font-size: 1.3rem;
  display: none;
  cursor: pointer;
  color: var(--maroonColor);
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
  z-index: 101;
}

.dropdown-container {
  display: none;
  list-style-type: none;
  padding-left: 1em;
  background-color: #f9f9f9;
}

.dropdown-container a {
  color: white;
  padding: 8px 12px;
  font-size: 16px;
  text-align: center;
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
  width: 30px;
  margin-right: 8px;
}

.logo_burger a {
  color: var(--maroonColor);
  font-size: 18px;
  text-decoration: none;
  font-weight: bold;
}

/* Profile */
#logout {
  display: flex;
  align-items: center;
}

#profile {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
  object-fit: cover;
}

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

#profile-text {
  color: var(--maroonColor);
  text-align: center;
}

#name {
  font-size: 14px;
  font-weight: bold;
}

#role {
  font-size: 12px;
}

#logout-button button {
  background: none;
  border: none;
  cursor: pointer;
}

#logout-img {
  width: 18px;
  height: 18px;
}

/* Tablet and Mobile */
@media (max-width: 1024px) {
  nav {
    flex-wrap: wrap;
    height: auto;
  }

  nav .openMenu {
    display: block;
  }

  nav .mainMenu {
    flex-direction: column;
    position: fixed;
    top: -100%;
    left: 0;
    width: 100%;
    height: 100vh;
    overflow-y: auto;
    justify-content: flex-start;
    align-items: center;
    background: var(--light-gray);
    transition: top 0.3s ease;
    z-index: 100;
    padding-top: 80px;
  }

  nav .mainMenu.show {
    top: 0;
  }

  nav .mainMenu li {
    width: 100%;
    text-align: center;
    margin: 10px 0;
  }

  nav .mainMenu li a,
  nav .mainMenu li button.dropdown-btn {
    width: 100%;
    justify-content: center;
    padding: 12px;
    font-size: clamp(12px, 4vw, 16px);
  }

  nav .closeMenu {
    display: block !important;
    font-size: 1.8rem;
    position: absolute;
    top: 15px;
    right: 15px;
    color: var(--maroonColor);
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

  #profile {
    width: clamp(50px, 25vw, 80px);
    height: clamp(50px, 25vw, 80px);
  }

  .icons {
    justify-content: center;
    gap: 10px;
    padding: 10px;
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

@media (max-width: 400px) {
  .logo_burger a {
    font-size: 16px;
  }

  nav .openMenu {
    font-size: 1.2rem;
  }

  nav .closeMenu {
    font-size: 1.6rem;
  }

  #name {
    font-size: 13px;
  }

  #role {
    font-size: 11px;
  }
}

@media (max-width: 767px) {
  .sidebar {
    display: none !important;
  }
}

/* Optional: Hide on tablets too */
@media (min-width: 768px) and (max-width: 1024px) {
  .sidebar {
    display: none !important;
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

  openMenu?.addEventListener('click', () => {
    mainMenu?.classList.add('show');
  });

  closeMenu?.addEventListener('click', () => {
    mainMenu?.classList.remove('show');
  });

  // Dropdown toggle
  const dropdownBtns = document.querySelectorAll('.dropdown-btn');

  dropdownBtns.forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      const dropdown = this.nextElementSibling;

      // Close all other dropdowns
      document.querySelectorAll('.dropdown-container').forEach(dc => {
        if (dc !== dropdown) {
          dc.style.display = 'none';
        }
      });

      // Toggle current dropdown
      const isOpen = dropdown.style.display === 'block';
      dropdown.style.display = isOpen ? 'none' : 'block';
    });
  });

  // Close dropdowns when clicking outside
  document.addEventListener('click', function () {
    document.querySelectorAll('.dropdown-container').forEach(drop => {
      drop.style.display = 'none';
    });
  });
</script>

</body>
</html>
