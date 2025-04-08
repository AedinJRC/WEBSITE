<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

include 'config.php';

// Handle deletion of an event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_event_id'])) {
    $delete_event_id = $conn->real_escape_string($_POST['delete_event_id']);
    $delete_sql = "DELETE FROM events WHERE id = '$delete_event_id'";
    if ($conn->query($delete_sql) === TRUE) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "<script>alert('Error deleting event: " . $conn->error . "');</script>";
    }
}

// Insert event into the database
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['delete_event_id'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $start = $conn->real_escape_string($_POST['start']);
    $end = $conn->real_escape_string($_POST['end']);

    // Format the start and end datetime to ensure correct format
    $start = date('Y-m-d H:i:s', strtotime($start));
    $end = date('Y-m-d H:i:s', strtotime($end));

    // Generate a random color
    $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));

    $sql = "INSERT INTO events (title, description, start_datetime, end_datetime, color) 
            VALUES ('$title', '$description', '$start', '$end', '$color')";

    if ($conn->query($sql) === TRUE) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// Fetch events from the database
$events = [];
$result = $conn->query("SELECT * FROM events");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = [
            "id" => $row['id'],
            "title" => $row['title'],
            "description" => $row['description'],
            "start" => $row['start_datetime'],
            "end" => $row['end_datetime'],
            "color" => $row['color']
        ];
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Calendar</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>

.header-orange {
  color: #81182c;
}

#calendar-event-wrapper {
  display: flex; /* Flexbox layout */
  justify-content: center; /* Center horizontally */
  align-items: center; /* Center vertically */
  gap: 20px; /* Space between the two divs */
  height: 100vh; /* Full viewport height */
  margin: 0 auto; /* Center horizontally */
  padding: 20px;
  background-color: #E4E9F7; /* Optional: background for the entire section */
  box-sizing: border-box;
}

#calendar-container, #event-info {
  flex: 1; /* Allow flexible resizing */
  max-width: 600px; /* Limit max width of each panel */
}

#calendar-container {
  background-color: #ffffff;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  width: 850px;
  max-width: 100%;
  transition: transform 0.3s ease-in-out;
}

#calendar-container:hover {
  transform: translateY(-5px);
}

#calendar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

#month-year {
  font-size: 1.7rem;
  font-weight: 700;
  color: #81182c;
  letter-spacing: 1px;
  text-transform: uppercase;
}

button {
  background: #ffc53d;
  border: 2px solid #81182c;
  border-radius: 30px;
  font-weight: bold;
  font-size: 1.3rem;
  color: #81182c;
  cursor: pointer;
  transition: transform 0.2s ease, color 0.3s;
}

button:hover {
  background: white;
  color: #ffc53d;
  border: 2px solid #ffc53d;
  font-weight: bold;
  transform: scale(1.1);
}

#calendar {
  width: 100%;
  margin-top: 20px;
  border-collapse: collapse;
  font-size: 1rem;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
}

#calendar th,
#calendar td {
  text-align: center;
  padding: 15px;
  width: 50px;
  height: 50px;
  cursor: pointer;
  border-radius: 6px;
  transition: background 0.2s ease, transform 0.3s ease;
}

#calendar td:hover {
  background-color: #f0f0f0;
  transform: scale(1.05);
}

#calendar{
  cursor: pointer;
}




#calendar .event:hover {
  background-color: #ff7f7f;
  transform: scale(1.1);
}

/* Basic styles for modal */
    #event-modal {
      display: none;
      position: fixed;
      color: #81182c;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: #fff;
      border: 2px solid #81182c;
      border-radius: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      z-index: 1000;
      padding: 20px;
      width: 300px;
    }

    #event-modal-content h3 {
      margin: 0 0 10px;
    }

    #event-modal-content {
      display: flex;
      flex-direction: column;
    }

    #event-modal-content input,
    #event-modal-content textarea,
    #event-modal-content button {
      margin-bottom: 10px;
      width: 95%;
      padding: 8px;
    }

    #event-modal-content button {
      cursor: pointer;
    }

    #close-modal {
      background: #81182c;
      color: #ffc53d;
      border: none;
    }

/* Button to open modal */
#add-event-btn {
  background: #81182c;
  color: #ffc53d;
  font-weight: bold;
  padding: 10px 15px;
  border: none;
  cursor: pointer;
  font-size: 26px;
  margin: 10px 0;
  border-radius: 10px; /* Makes the border round */
}


/* Hover effect */
#add-event-btn:hover {
  border: 2px solid #81182c;
  color: #81182c;
  background: white;
  border-radius: 25px; /* Ensure border remains round on hover */
}

    /* Overlay */
    #modal-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 999;
    }

    #event-description {
  height: 100px; /* Default height */
  max-height: 300px; /* Maximum height it can expand to */
  resize: vertical; /* Allows resizing only in height */
}

#event-title,
#event-description,
#event-start,
#event-end {
  width: 100%;
  margin: 10px 0;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 1rem;
  transition: border-color 0.3s;
}

#event-title:focus,
#event-description:focus,
#event-start:focus,
#event-end:focus {
  border-color: #ff5722;
  outline: none;
}

#save-event {
  padding: 12px 20px;
  margin: 10px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

#save-event {
  background-color: #4caf50;
  color: white;
}

#save-event:hover {
  background-color: #43a047;
  transform: scale(1.05);
}





/* Event Info Panel */
#event-info {
  float: left;
  width: 30%;
  height: 600px;
  margin-left: 4%;
  border: 1px solid #ccc;
  padding: 15px;
  box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.1);
  overflow-y: auto;
  background-color: #f9f9f9;
  border-radius: 8px;
}

#event-info h4 {
  margin-bottom: 15px;
  color: #333;
  font-size: 1.3rem;
}

#event-details p {
  margin: 5px 0;
  color: #555;
}

.event-highlight {
  border-radius: 4px;
  padding: 5px;
  font-size: 1rem;
}

#event-info .event-details-title {
  font-weight: bold;
  color: #d48c09;
}

#event-info .event-details-value {
  font-style: italic;
  color: #666;
}

#calendar td.event {
  background-color: #ffb3b3;
  color: #81182c;
  font-weight: bold;
  border-radius: 6px;
  padding: 3px 5px;
  font-size: 0.9rem;
  cursor: pointer;
}

#calendar td.event:hover {
  background-color: #ff7f7f;
  transform: scale(1.1);
}

#calendar td .event {
    display: block;
    margin: 2px 0;
    padding: 2px 5px;
    border-radius: 4px;
    font-size: 0.8rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: white;
    font-weight: bold;  
}
.event-title {
color: white; 
font-weight: bold;           /* Prevents text from wrapping */
}

.delete-btn {
  background-color: #81182c;
  color: #ffc53d;
  border: none;
  padding: 10px 15px;
  border-radius: 5px;
  font-size: 15px;
  cursor: pointer;
}
.delete-btn:hover {
    color: #81182c;
}

.icon-button {
  background-color: #ffc53d; /* Blue background */
  color: #81182c; /* White icon color */
  border: none; /* Remove default border */
  padding: 10px 15px; /* Padding around the icon */
  font-size: 14px; /* Icon size */
  border-radius: 50%; /* Rounded buttons */
  cursor: pointer; /* Pointer on hover */
  transition: background-color 0.3s, transform 0.2s; /* Smooth transition for hover effects */
}

.icon-button:hover {
  background-color: white; /* Darker blue on hover */
  border: 2px solid #81182c;
  transform: scale(1.1); /* Slightly enlarge on hover */
}

.icon-button:focus {
  outline: none; /* Remove focus outline */
}

@media screen and (max-width: 768px) {
  #calendar-event-wrapper {
    flex-direction: column; /* Stack elements vertically */
    align-items: center;
    height: auto;
    padding: 10px;
  }

  #calendar-container, #event-info {
    width: 100%;
    max-width: 100%;
  }

  #calendar-container {
    padding: 10px;
  }

  #calendar th, #calendar td {
    padding: 8px; /* Smaller padding */
    font-size: 0.9rem; /* Reduce text size */
    height: 40px;
    width: 40px;
  }

  #calendar-header {
    flex-direction: column;
    text-align: center;
  }

  #month-year {
    font-size: 1.3rem; /* Adjust month title size */
  }

  button {
    font-size: 1rem;
    padding: 7px 10px;
  }

  #add-event-btn {
    font-size: 1rem;
    padding: 8px;
  }

  #event-info {
    height: auto;
    width: 100%;
    margin-top: 10px;
    padding: 10px;
  }

  #event-modal {
    width: 90%;
    padding: 15px;
  }

  input, textarea {
    font-size: 0.9rem;
    padding: 6px;
  }
}

@media screen and (max-width: 480px) {
  #calendar-container {
    padding: 5px;
  }

  #calendar th, #calendar td {
    padding: 5px;
    font-size: 0.8rem;
    height: 35px;
    width: 35px;
  }

  #month-year {
    font-size: 1.1rem;
  }

  button {
    font-size: 0.9rem;
    padding: 5px 8px;
  }

  #add-event-btn {
    font-size: 1rem;
    padding: 6px 8px;
  }

  #event-modal {
    width: 85%;
    padding: 10px;
  }

  input, textarea {
    font-size: 0.8rem;
    padding: 5px;
  }
}

@media screen and (max-width: 360px) {
  #calendar-container {
    padding: 3px;
  }

  #calendar th, #calendar td {
    padding: 3px;
    font-size: 0.7rem; /* Further reduce font */
    height: 30px;
    width: 30px;
  }

  #month-year {
    font-size: 1rem;
  }

  button {
    font-size: 0.8rem;
    padding: 4px 6px;
  }

  #add-event-btn {
    font-size: 0.9rem;
    padding: 5px 6px;
  }

  #event-modal {
    width: 80%;
    padding: 8px;
  }

  input, textarea {
    font-size: 0.7rem;
    padding: 4px;
  }
}




  </style>
</head>
<body>
<!--  <?php include '../side_nav_t/side_nav.php'; ?>   -->




<div id="calendar-event-wrapper">
<div id="calendar-container">
  <!-- Add Event Button -->
  <button id="add-event-btn"> <i class="fa fa-plus"></i></button>
  
  <div id="calendar-header">

  <button id="prev-month" class="icon-button">
  <i class="fas fa-arrow-left"></i>
</button>
<h2 id="month-year">December 2024</h2>
<button id="next-month" class="icon-button">
  <i class="fas fa-arrow-right"></i>
</button>

</div>
  <table id="calendar">
    <thead>
      <tr>
        <th>Sun</th>
        <th>Mon</th>
        <th>Tue</th>
        <th>Wed</th>
        <th>Thu</th>
        <th>Fri</th>
        <th>Sat</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>

<!-- Event Info Panel -->
<div id="event-info">
<h3 class="header-orange">Event Details</h3>

<div id="event-details">
  <?php if (!empty($events)) : ?>
    <?php foreach ($events as $event) : ?>
      <div class="event-highlight">
        <p class="event-details-title">Title: <span class="event-details-value"><?= $event['title'] ?></span></p>
        <p class="event-details-title">Description: <span class="event-details-value"><?= $event['description'] ?></span></p>
        <p class="event-details-title">Start: <span class="event-details-value"><?= $event['start'] ?></span></p>
        <p class="event-details-title">End: <span class="event-details-value"><?= $event['end'] ?></span></p>
        <!-- Delete Button -->
        <hr>
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <p>No events found.</p>
  <?php endif; ?>
</div>


</div>
</div>

<!-- Event Modal -->
<div id="modal-overlay"></div>
<div id="event-modal">
  <div id="event-modal-content">
    <h3>ADD EVENT</h3>
    <form id="event-form" method="POST" action="">
      <input type="text" name="title" id="event-title" placeholder="Event Title" required />
      <textarea name="description" id="event-description" placeholder="Event Description"></textarea>
      <label>Start Date and Time:</label>
      <input type="datetime-local" name="start" id="event-start" required />
      <label>End Date and Time:</label>
      <input type="datetime-local" name="end" id="event-end" required />
      <button type="submit">Save Event</button>
    </form>
    <button id="close-modal">Close</button>
  </div>
</div>

<script>  

// Calendar initialization
const calendar = document.getElementById('calendar');
const eventDetails = document.getElementById('event-details');
const monthYear = document.getElementById('month-year');
const prevMonthBtn = document.getElementById('prev-month');
const nextMonthBtn = document.getElementById('next-month');
const eventModal = document.getElementById('event-modal');
const addEventBtn = document.getElementById('add-event-btn');
const closeModalBtn = document.getElementById('close-modal');
const modalOverlay = document.getElementById('modal-overlay');
const eventForm = document.getElementById('event-form');
let currentDate = new Date();

const events = <?php echo json_encode($events); ?>; // PHP data to JavaScript

// Open Modal
addEventBtn.onclick = () => {
  eventModal.style.display = 'block';
  modalOverlay.style.display = 'block';
};

// Close Modal
closeModalBtn.onclick = () => {
  eventModal.style.display = 'none';
  modalOverlay.style.display = 'none';
};

// Close Modal by clicking overlay
modalOverlay.onclick = () => {
  eventModal.style.display = 'none';
  modalOverlay.style.display = 'none';
};

function renderCalendar() {
  const currentMonth = currentDate.getMonth();
  const currentYear = currentDate.getFullYear();
  const firstDayOfMonth = new Date(currentYear, currentMonth, 1);
  const lastDayOfMonth = new Date(currentYear, currentMonth + 1, 0);

  const daysInMonth = lastDayOfMonth.getDate();
  const startingDay = firstDayOfMonth.getDay();
  const calendarBody = calendar.querySelector('tbody');

  calendarBody.innerHTML = ''; // Clear the calendar

  // Fill in empty cells for the first week
  let row = document.createElement('tr');
  for (let i = 0; i < startingDay; i++) {
    row.appendChild(document.createElement('td'));
  }

  // Loop through the days of the month
  for (let day = 1; day <= daysInMonth; day++) {
    if ((startingDay + day - 1) % 7 === 0 && day !== 1) {
      calendarBody.appendChild(row);
      row = document.createElement('tr');
    }

    const dateCell = document.createElement('td');
    const currentDateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    dateCell.setAttribute('data-date', currentDateStr);
    dateCell.classList.add('calendar-date');
    dateCell.textContent = day;

    // Filter events for the current day
    const eventsForDay = events.filter(event => {
  const cellDateStr = currentDateStr; // Use already formatted YYYY-MM-DD
  const eventStartStr = new Date(event.start).toISOString().split('T')[0];
  const eventEndStr = new Date(event.end).toISOString().split('T')[0];
  return cellDateStr >= eventStartStr && cellDateStr <= eventEndStr;
});


    if (eventsForDay.length > 0) {
      eventsForDay.forEach(event => {
        const eventElement = document.createElement('div');
        eventElement.classList.add('event');
        eventElement.style.backgroundColor = event.color;
        eventElement.textContent = truncateText(event.title, 15);


        // Calculate the width for multi-day events
        const eventStart = new Date(event.start);
        const eventEnd = new Date(event.end);
        const eventStartDay = eventStart.getDate();
        const eventEndDay = eventEnd.getDate();

        // If event spans multiple days, adjust the event's span on the calendar
        if (eventEnd > eventStart) {
  for (let d = eventStartDay; d <= eventEndDay; d++) {
    const affectedCell = document.querySelector(`td[data-date="${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}"]`);
    if (affectedCell) {
      // Clear existing events before appending new ones
      affectedCell.innerHTML = ''; 
      
      const eventElement = document.createElement('div');
      eventElement.classList.add('event');
      eventElement.style.backgroundColor = event.color;
      eventElement.textContent = truncateText(event.title, 15);
      affectedCell.appendChild(eventElement);
    }
  }
}

        eventElement.onclick = () => {
          displayEventDetails(event); // Show details when clicked
        };

        dateCell.appendChild(eventElement);
      });
   // Highlight the cell for days with events
   dateCell.style.backgroundColor = eventsForDay[0].color;
      dateCell.classList.add('event-highlight');
    }

    row.appendChild(dateCell);
  }

  calendarBody.appendChild(row);
  monthYear.textContent = `${currentDate.toLocaleString('default', { month: 'long' })} ${currentYear}`;

  // Attach click listeners for day cells
  addDateClickListener();
  highlightEvents();
}

function truncateText(text, maxLength) {
  return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
}

events.forEach(event => {
  event.title = truncateText(event.title, 15); // Adjust max length as needed
});


function displayEventDetails(event) {
  eventDetails.innerHTML = `
    <h4>${event.title}</h4>
    <p><strong>Description:</strong> ${event.description}</p>
    <p><strong>Start:</strong> ${new Date(event.start).toLocaleString()}</p>
    <p><strong>End:</strong> ${new Date(event.end).toLocaleString()}</p>
    <form method="POST" action="">
      <input type="hidden" name="delete_event_id" value="${event.id}">
      <button type="submit" onclick="return confirm('Are you sure you want to delete this event?');">
        Delete Event
      </button>
    </form>
  `;
}


// Initialize the calendar on page load
document.addEventListener('DOMContentLoaded', () => {
  renderCalendar();
});

// Add navigation functionality for next and previous months
prevMonthBtn.onclick = () => {
  currentDate.setMonth(currentDate.getMonth() - 1);
  renderCalendar();
};

nextMonthBtn.onclick = () => {
  currentDate.setMonth(currentDate.getMonth() + 1);
  renderCalendar();
};

function highlightEvents() {
  const cells = document.querySelectorAll('#calendar td[data-date]');
  
  cells.forEach(cell => {
    cell.querySelectorAll('.event').forEach(e => e.remove()); // Clear previous highlights
    
    const cellDate = cell.getAttribute('data-date');
    const eventsForDate = events.filter(event => {
      const eventStartDate = new Date(event.start).toISOString().split('T')[0];
      return cellDate === eventStartDate; // Match only the start date
    });

    if (eventsForDate.length > 0) {
      eventsForDate.forEach(event => {
        const eventElement = document.createElement('div');
        eventElement.classList.add('event');
        eventElement.style.backgroundColor = event.color;
        eventElement.textContent = truncateText(event.title, 15);
        cell.appendChild(eventElement);
      });
    }
  });
}


function addDateClickListener() {
  const dateCells = document.querySelectorAll('.calendar-date');
  dateCells.forEach(cell => {
    cell.onclick = () => {
      const selectedDate = cell.getAttribute('data-date');
      displayEventsForDate(selectedDate);
    };
  });
}

function displayEventsForDate(selectedDate) {
  const eventsForDate = events.filter(event => {
    const eventStart = new Date(event.start).toISOString().split('T')[0];
    const eventEnd = new Date(event.end).toISOString().split('T')[0];
    return selectedDate >= eventStart && selectedDate <= eventEnd;
  });

  eventDetails.innerHTML = eventsForDate.length
    ? eventsForDate.map(event => `
      <div class="event-card">
        <h4>${event.title}</h4>
        <p><strong>Description:</strong> ${event.description}</p>
        <p><strong>Start:</strong> ${new Date(event.start).toLocaleString()}</p>
        <p><strong>End:</strong> ${new Date(event.end).toLocaleString()}</p>
        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this event?')">
          <input type="hidden" name="delete_event_id" value="${event.id}">
          <br>
          <button type="submit" class="delete-btn">Delete</button>
         <br>
          <br>
          </form>
        <hr>
      </div>
    `).join('')
    : '<p>No events for this date.</p>';
}


prevMonthBtn.onclick = () => {
  currentDate.setMonth(currentDate.getMonth() - 1);
  renderCalendar(currentDate);
};

nextMonthBtn.onclick = () => {
  currentDate.setMonth(currentDate.getMonth() + 1);
  renderCalendar(currentDate);
};

eventForm.onsubmit = () => {
  eventForm.submit();
};

renderCalendar(currentDate);
</script>

</body>
</html>
