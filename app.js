function toggleDropdown(button) {
  button.nextElementSibling.classList.toggle('show');
  button.classList.toggle('rotate');
}

function toggleSidebar(button) {
  const sidebar = event.currentTarget.parentElement;
  sidebar.classList.toggle('active');

  // Save sidebar state to localStorage
  if (sidebar.classList.contains('active')) {
    localStorage.setItem('sidebarState', 'open');
  } else {
    localStorage.setItem('sidebarState', 'closed');
  }
}

function closeSidebar() {
  const sidebar = document.querySelector('.sidebar');
  sidebar.classList.remove('active');
  localStorage.setItem('sidebarState', 'closed');
}

function openSidebar() {
  const sidebar = document.querySelector('.sidebar');
  sidebar.classList.add('active');
  localStorage.setItem('sidebarState', 'open');
}

function validatePasswords() {
  const password = document.getElementById('password').value;
  const retypePassword = document.getElementById('retype-password').value;
  
  if (password !== retypePassword) {
    alert('Passwords do not match!');
    return false; // Prevent form submission
  }
  return true;
}

function toggleMenu() {
  const menu = document.getElementById('navMenu');
  const actions = document.getElementById('navActions');
  menu.classList.toggle('show');
  actions.classList.toggle('show');
}

function scrollToSection(sectionId, event) {
  if (event) event.preventDefault(); // Pigilan ang default behavior ng <a href="#">
  const section = document.getElementById(sectionId);
  if (section) {
    section.scrollIntoView({ behavior: "smooth" });
  } else {
    console.error("Section not found:", sectionId);
  }
}

// Restore sidebar state on page load
document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.querySelector('.sidebar');
  if (localStorage.getItem('sidebarState') === 'closed') {
    sidebar.classList.remove('active');
  } else {
    sidebar.classList.add('active'); // Optional: only needed if your HTML doesn't already include `.active`
  }
});
