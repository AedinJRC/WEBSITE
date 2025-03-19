<?php
include 'config.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car List</title>
    <style>
       body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 10px;
        text-align: center;
    }

    :root {
        --maroonColor: #80050d;
        --yellowColor: #efb954;
    }

    h2 {
        color: var(--maroonColor);
    }

    .table-section {
        background: #fff;
        padding: 15px;
        margin: 10px auto;
        width: 95%;
        max-width: 1200px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 15px;
        overflow: hidden;
    }

    .table-container {
        max-height: 400px;
        overflow-x: auto;
        overflow-y: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        min-width: 600px;
    }

    table, th, td {
        border: 1px solid #ccc;
    }

    th, td {
        padding: 10px;
        text-align: center;
        white-space: nowrap;
        font-size: 16px;
    }

    th {
        background-color: var(--maroonColor);
        color: white;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    td img {
        border-radius: 5px;
        cursor: pointer;
        transition: transform 0.2s;
        max-width: 80px;
        height: auto;
    }

    td img:hover {
        transform: scale(1.1);
    }

    /* Responsive Table */
    @media (max-width: 768px) {
        .table-container {
            overflow-x: auto;
            max-width: 100%;
        }

        table {
            min-width: 100%;
            font-size: 14px;
        }

        th, td {
            padding: 8px;
        }

        td img {
            max-width: 60px;
        }
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
    }

    .modal-content {
        width: 90%;
        max-width: 500px;
        max-height: 80vh;
        border-radius: 8px;
        overflow: hidden;
    }

    .close {
        position: absolute;
        top: 10px;
        right: 25px;
        color: white;
        font-size: 30px;
        font-weight: bold;
        cursor: pointer;
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
                    <th>Transmission Type</th>
                    <th>Registration</th>
                </tr>
                <?php
                $result = $conn->query("SELECT * FROM cars");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><img src='" . htmlspecialchars($row['image']) . "' onclick='openModal(this.src)'></td>";
                        echo "<td>" . htmlspecialchars($row['plate_number']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['color']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['brand']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['model']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['capacity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['body_type']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['transmission']) . "</td>";
                        echo "<td>" . date("F j, Y", strtotime($row['registration_from'])) . " to " . date("F j, Y", strtotime($row['registration_to'])) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No cars available.</td></tr>";
                }
                ?>
            </table>
        </div>
    </section>

    <div id="imageModal" class="modal" onclick="closeModal()">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImg">
    </div>

    <script>
        window.onload = function() {
            document.getElementById("imageModal").style.display = "none";
        };

        function openModal(src) {
            let modal = document.getElementById("imageModal");
            let modalImg = document.getElementById("modalImg");

            modalImg.src = src;
            modal.style.display = "flex";
        }

        function closeModal() {
            document.getElementById("imageModal").style.display = "none";
        }
    </script>
</body>
</html>
