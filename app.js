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