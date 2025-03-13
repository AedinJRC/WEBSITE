function toggleDropdown(button) {
  button.nextElementSibling.classList.toggle('show');
  button.classList.toggle('rotate');
}

function toggleSidebar(button) {
  event.currentTarget.parentElement.classList.toggle('active');
}

function closeSidebar() {
  document.querySelector('.sidebar').classList.remove('active');
}

function openSidebar() {
  document.querySelector('.sidebar').classList.add('active');
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

