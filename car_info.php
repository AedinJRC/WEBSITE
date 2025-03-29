<?php 
include 'config.php'; 

// Handle Delete Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_plate_number'])) {
    $plate_number = $_POST['delete_plate_number'];
    $delete_query = "DELETE FROM carstb WHERE plate_number = ?";
    
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("s", $plate_number);
    
    if ($stmt->execute()) {
        echo "<script>alert('Car deleted successfully!'); window.location.href='';</script>";
    } else {
        echo "<script>alert('Failed to delete car.');</script>";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Car List</title>
    <style>
      body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        :root {
            --maroonColor: #80050d;
            --yellowColor: #efb954;
            --primaryColor: #007bff;
            --hoverColor: #0056b3;
        }

        h2 {
            color: var(--maroonColor);
            margin-bottom: 2vh;
            font-size: 3.5vh;
        }

        .table-section {
            background: #fff;
            padding: 2.5vh;
            margin: 1.5vh auto;
            width: 96%;
            max-width: 140vh;
            box-shadow: 0 0.8vh 1.8vh rgba(0, 0, 0, 0.1);
            border-radius: 2vh;
            overflow: hidden;
        }

        .table-container {
            max-height: 55vh;
            overflow-x: auto;
            overflow-y: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.2vh;
            min-width: 90vh;
        }

        th, td {
            padding: 2vh;
            text-align: center;
            font-size: 2.5vh;
            border-bottom: 0.25vh solid #ddd;
        }

        th {
            background-color: var(--maroonColor);
            color: white;
            position: sticky;
            top: 0;
            z-index: 2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        td img {
            border-radius: 0.8vh;
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
            max-width: 12vh;
            height: auto;
        }

        td img:hover {
            transform: scale(1.1);
        }

        .action {
            font-size: 2.5vh;
            padding: 2vh;
        }

        .action-buttons {
            display: flex;
            flex-direction: column; /* Stack buttons vertically */
            align-items: center;
            gap: 0.8vh; /* Spacing between buttons */
        }

        .edit-btn, .delete-btnplt {
            cursor: pointer;
            border: none;
            padding: 1.8vh 2.5vh;
            font-size: 2.5vh;
            border-radius: 0.8vh;
            transition: transform 0.2s ease-in-out;
            background: none;  /* Ensure no background color */
        }

        .edit-btn {
            color: var(--yellowColor);
        }

        .delete-btnplt {
            color: var(--maroonColor);
        }

        /* Slightly increase the size when hovered */
        .edit-btn:hover, .delete-btnplt:hover {
            transform: scale(1.15);
        }
    
        .sidebar-expanded .table-section {
    width: 85%; /* Reduce table width */
    transition: width 0.3s ease-in-out;
}

    /* Modal Styling */
      .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal .content {
            background: white;
            width: 95%;
            max-width: 50vh;
            padding: 2.5vh;
            border-radius: 1.2vh;
            box-shadow: 0 0.8vh 1.5vh rgba(0, 0, 0, 0.2);
            position: relative;
            animation: fadeIn 0.3s ease-in-out;
        }

        .modal {
            position: absolute;
            top: 1.2vh;
            right: 2vh;
            font-size: 2.2vh;
            cursor: pointer;
            color: #555;
        }

        .close {
            position: absolute;
            top: 1.2vh;
            right: 2vh;
            font-size: 4.5vh;
            cursor: pointer;
            color: var(--maroonColor);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1.5vh;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        select {
            width: 100%;
            padding: 1vh;
            border: 0.12vh solid #ccc;
            border-radius: 0.6vh;
            font-size: 1.6vh;
        }

        input {
            padding: 1vh;
            border: 0.12vh solid #ccc;
            border-radius: 0.6vh;
            font-size: 1.6vh;
        }

        label {
            font-weight: bold;
            font-size: 1.5vh;
            margin-bottom: 0.4vh;
        }

        .row {
            display: flex;
            justify-content: space-between;
            gap: 2.2vh;
        }

        .row div {
            flex: 1;
        }

        .column {
            width: 40%;
            display: flex;
            flex-direction: column;
            gap: 1.8vh;
        }
        
        .save-btn {
            margin-top: 1.8vh;
            background: var(--maroonColor);
            color: white;
            padding: 1.5vh;
            font-size: 1.8vh;
            border: solid 0.25vh var(--maroonColor);
            border-radius: 0.6vh;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .save-btn:hover {
            background: white;
            color: var(--maroonColor);
            border: solid 0.25vh var(--maroonColor);
            border-radius: 1.8vh;
            font-weight: bold;
        }

        /* Image Modal */
        .modal-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100vh;
        }

        .modal-content-wrapper {
            position: relative;
            max-width: 70%;
            max-height: 70%;
            margin: auto;
            z-index: 1001;
        }

        .modal-content {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

    
    </style>
</head>
<body>
    <section class="table-section">
        <h2>CAR INFORMATION</h2>
        <div class="table-container">
            <table>
                <tr>
                    <th>Image</th>
                    <th>Plate Number</th>
                    <th>Color</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Capacity</th>
                    <th>Body Type</th>
                    <th>Transmission</th>
                    <th>Registration</th>
                    <th>Action</th>
                </tr>
                <?php 
if ($conn) { // Check if connection is successful
    $result = $conn->query("SELECT * FROM carstb");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><img src='" . htmlspecialchars($row['image']) . "' alt='Car Image' onclick='openImageModal(this.src)'></td>";
            echo "<td>" . htmlspecialchars($row['plate_number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['color']) . "</td>";
            echo "<td>" . htmlspecialchars($row['brand']) . "</td>";
            echo "<td>" . htmlspecialchars($row['model']) . "</td>";
            echo "<td>" . htmlspecialchars($row['capacity']) . "</td>";
            echo "<td>" . htmlspecialchars($row['body_type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['transmission']) . "</td>";
            echo "<td>" . date("F j, Y", strtotime($row['registration_from'])) . " to " . date("F j, Y", strtotime($row['registration_to'])) . "</td>";
            
            echo "<td class='action-buttons'>
    <span class='edit-btn' onclick='openEditModal(" . json_encode($row) . ")'>&#9998;</span>
    <form method='POST' action='' onsubmit='return confirmDelete()'>
        <input type='hidden' name='delete_plate_number' value='" . htmlspecialchars($row['plate_number']) . "'>
        <button type='submit' class='delete-btnplt'><i style'color:#80050d;' class='fas fa-trash'></i></button>
    </form>
</td>";
                            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No cars available.</td></tr>";
    }
} else {
    echo "<tr><td colspan='10'>Database connection failed.</td></tr>";
}
?>
            </table>
        </div>
    </section>

 <!-- Edit Modal -->
 <div id="editModal" class="modal">  
        <div class="content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h3>Edit Car Information</h3>
            <form action="car_info_update.php" method="POST">
                <input type="hidden" id="plate_number" name="plate_number">

                <div class="form-group">
            <label>Car Image:</label>
            <img id="editCarImage" src="" alt="Car Image" style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 5px;">
        </div>
                
                <div class="row">
        <!-- Left Column -->
        <div class="column">
            <div class="form-group">
                <label for="color">Color:</label>
                <input type="text" id="color" name="color" placeholder="Enter color">
            </div>

            <div class="form-group">
                <label for="brand">Brand:</label>
                <input type="text" id="brand" name="brand" placeholder="Enter brand">
            </div>

            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" id="model" name="model" placeholder="Enter model">
            </div>
        </div>

        <!-- Right Column -->
        <div class="column">
            <div class="form-group">
                <label for="capacity">Capacity:</label>
                <input type="text" id="capacity" name="capacity" placeholder="Enter capacity">
            </div>

            <div class="form-group">
                <label for="body_type">Body Type:</label>
                <select id="body_type" name="body_type">
                    <option value="Sedan">Sedan</option>
                    <option value="SUV">SUV</option>
                    <option value="Truck">Truck</option>
                    <option value="Van">Van</option>
                </select>
            </div>

            <div class="form-group">
                <label for="transmission">Transmission:</label>
                <select id="transmission" name="transmission">
                    <option value="Automatic">Automatic</option>
                    <option value="Manual">Manual</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="registration_from">Registration From:</label>
        <input type="date" id="registration_from" name="registration_from">
    </div>

    <div class="form-group">
        <label for="registration_to">Registration To:</label>
        <input type="date" id="registration_to" name="registration_to">
    </div>

    <button type="submit" class="save-btn">Save Changes</button>
</form>
        </div>
    </div>

 <!-- Image Modal -->
<div id="imageModal" class="modal">
    <div class="modal-container" onclick="closeImageModal()"> 
        <div class="modal-content-wrapper" onclick="event.stopPropagation();">
            <span class="close" onclick="closeImageModal()">&times;</span>
            <img id="modalImage" class="modal-content">
        </div>
    </div>
</div>

    <script>
function openImageModal(imgSrc) {
    let modal = document.getElementById("imageModal");
    let modalImg = document.getElementById("modalImage");

    modalImg.src = imgSrc;
    modal.style.display = "flex";
}

// Function to close the image modal
function closeImageModal() {
    document.getElementById("imageModal").style.display = "none";
}

// Function to open the edit modal
function openEditModal(data) {
    let modal = document.getElementById("editModal");

    document.getElementById("plate_number").value = data.plate_number || "";
    document.getElementById("color").value = data.color || "";
    document.getElementById("brand").value = data.brand || "";
    document.getElementById("model").value = data.model || "";
    document.getElementById("capacity").value = data.capacity || "";

    // Set selected value for Body Type
    document.getElementById("body_type").value = data.body_type || "Sedan";

    // Set selected value for Transmission
    document.getElementById("transmission").value = data.transmission || "Automatic";
     // Populate the registration dates
     document.getElementById("registration_from").value = data.registration_from || "";
    document.getElementById("registration_to").value = data.registration_to || "";

    document.getElementById("editCarImage").src = data.image || "";

    modal.style.display = "flex";
}

// Function to close modals when clicking outside
window.onclick = function(event) {
    let imageModal = document.getElementById("imageModal");
    let editModal = document.getElementById("editModal");

    if (event.target === imageModal) {
        closeImageModal();
    }
    
    if (event.target === editModal) {
        closeEditModal();
    }
};

// Function to close the edit modal
function closeEditModal() {
    let modal = document.getElementById("editModal");
    modal.style.display = "none";
    document.querySelector("#editModal form").reset();
}

function confirmDelete() {
            return confirm("Are you sure you want to delete this car?");
        }

        document.addEventListener("DOMContentLoaded", function () {
    let sidebar = document.getElementById("sidebar"); // Sidebar element
    let tableSection = document.querySelector(".table-section");

    document.getElementById("sidebarToggle").addEventListener("click", function () {
        sidebar.classList.toggle("sidebar-expanded"); // Toggle sidebar class

        if (sidebar.classList.contains("sidebar-expanded")) {
            tableSection.style.width = "85%"; // Reduce table width
        } else {
            tableSection.style.width = "96%"; // Reset table width
        }
    });
});

    </script>

</body>
</html>
