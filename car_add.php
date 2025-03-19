<?php  
include 'config.php'; // Ensure database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST['plate-number'], $_POST['color'], $_POST['brand'], $_POST['model'], 
        $_POST['year-model'], $_POST['body-type'], $_POST['transmission'], 
        $_POST['registration-from'], $_POST['registration-to'], $_POST['capacity']) 
        && isset($_FILES["car-image"])
    ) {
        $plateNumber = htmlspecialchars($_POST['plate-number']);
        $color = htmlspecialchars($_POST['color']);
        $brand = htmlspecialchars($_POST['brand']);
        $model = htmlspecialchars($_POST['model']);
        $yearModel = htmlspecialchars($_POST['year-model']);
        $bodyType = htmlspecialchars($_POST['body-type']);
        $transmission = htmlspecialchars($_POST['transmission']);
        $registrationFrom = htmlspecialchars($_POST['registration-from']);
        $registrationTo = htmlspecialchars($_POST['registration-to']);
        $capacity = htmlspecialchars($_POST['capacity']);
        $createdAt = date("Y-m-d H:i:s"); // Automatically save date and time

        // Image upload handling
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = basename($_FILES["car-image"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["car-image"]["tmp_name"], $targetFilePath)) {
                try {
                    // Insert data into database
                    $stmt = $conn->prepare("INSERT INTO cars (plate_number, color, brand, model, year_model, body_type, transmission, registration_from, registration_to, capacity, image, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssssssssss", $plateNumber, $color, $brand, $model, $yearModel, $bodyType, $transmission, $registrationFrom, $registrationTo, $capacity, $targetFilePath, $createdAt);
                    
                    if ($stmt->execute()) {
                        echo "<script>
                                alert('Car added successfully!');
                                window.location.href='car_add.php';
                              </script>";
                    }

                    $stmt->close();
                } catch (mysqli_sql_exception $e) {
                    if ($e->getCode() == 1062) { // Duplicate entry error code
                        echo "<script>
                                alert('Error: Plate number already exists!');
                                window.location.href='car_add.php';
                              </script>";
                    } else {
                        echo "<script>
                                alert('Database error: " . addslashes($e->getMessage()) . "');
                                window.location.href='car_add.php';
                              </script>";
                    }
                }
            } else {
                echo "<script>
                        alert('Error uploading image.');
                        window.history.back();
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Invalid image format. Only JPG, JPEG, PNG & GIF allowed.');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('All fields are required.');
                window.history.back();
              </script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a New Car</title>
    <style>
        :root {
            --maroonColor: #80050d;
            --yellowColor: #efb954;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-section {
            background-color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 450px;
            border-radius: 8px;
            overflow-y: auto;
            max-height: 90vh;
        }

        h2 {
            margin-bottom: 15px;
            text-transform: uppercase;
            text-align: center;
            font-weight: bold;
            color: var(--maroonColor);
            font-size: 20px;
        }

        .form-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    width: 90%;
    max-width: 450px;
    background: white;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.form-group {
    position: relative;
    display: flex;
    flex-direction: column;
}

/* Style for labels */
.form-group label {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 14px;
    color: #888;
    transition: 0.3s;
    pointer-events: none;
    background: white;
    padding: 0 5px;
}

/* Floating Label for Select */
.form-group input:valid + label,
.form-group:focus-within label {
    top: 5px;
    font-size: 12px;
    color: var(--maroonColor);
}

/* Ensuring the label works for input fields */
.form-group input,.form-group select
{
    width: 100%;
    padding: 12px;
    font-size: 14px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    outline: none;
    background: transparent;
}

/* Placeholder transparency to make sure label moves up */
.form-group input::placeholder {
    color: transparent;
}

/* Floating Label for Date Inputs */
.form-group input[type="date"]:valid + label {
    top: 5px;
    font-size: 12px;
    color: var(--maroonColor);
}



         /* Move label up when select is focused or has a value */
         .form-group select:focus,
        .form-group select:valid {
            border-color: var(--maroon);
        }

        .form-group select:focus ~ label,
        .form-group select.has-value ~ label {
            top: 0;
            font-size: 13px;
            color: var(--maroonColor);
            background: white;
            padding: 0 0.3vw;
        }

/* Make full-width elements span both columns */
.full-width {
    grid-column: span 2;
}

.button-container {
    display: flex;
    justify-content: center;
    margin-top: 15px; /* Adjust spacing */
}

button.submit-btn {
    background-color: var(--maroonColor);
    color: white;
    padding: 12px 54px; /* Adjusted padding */
    font-size: 16px;
    border: 2px solid transparent;
    border-radius: 10px;
    cursor: pointer;
    text-transform: uppercase;
    font-weight: bold;
    transition: background-color 0.3s ease, transform 0.2s ease;
    width: auto; /* Prevent full width */
}

button.submit-btn:hover {
    border: 2px solid var(--yellowColor);
    background-color: transparent;
    color: var(--maroonColor);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}


/* Image Preview */
#image-preview {
    width: 100%;
    max-width: 300px;
    height: auto;
    object-fit: contain;
    margin: 5px auto;
    border-radius: 8px;
    display: none;
    cursor: pointer;
}

/* Custom File Input Styling */
.custom-file-container {
    display: flex;
    justify-content: center;
    margin-bottom: 10px;
}

.custom-file-input {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50%; /* Adjust width as needed */
    max-width: 200px;
    padding: 12px;
    border: 2px dashed var(--maroonColor);
    border-radius: 8px;
    background-color: #fff;
    cursor: pointer;
    text-align: center;
    transition: 0.3s ease-in-out;
}

.custom-file-input:hover {
    background-color: #f9f9f9;
}

.custom-file-input input[type="file"] {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.upload-btn {
    font-size: 14px;
    font-weight: bold;
    color: var(--maroonColor);
}


        @media (max-width: 480px) {
            .form-section {
                padding: 10px;
                max-width: 90%;
            }

            h2 {
                font-size: 16px;
            }

            .form-container {
                grid-template-columns: 1fr;
            }

            .form-group label {
                font-size: 12px;
            }

            .form-group input,
            .form-group select {
                padding: 6px;
                font-size: 12px;
            }

            button.submit-btn {
                font-size: 12px;
                padding: 8px;
                border-radius: 6px;
            }

            #image-preview {
                max-width: 200px;
            }
        }

         /* Modal styles */
         .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            max-width: 90%;
            max-height: 90%;
            border-radius: 8px;
        }

        .close-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
        }

        .registration-box {
    border: 2px solid  #ddd;
    padding: 15px;
    border-radius: 8px;
    background-color: white;
    display: flex;
    flex-direction: column;
}

.date-range-container {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-top: 8px;
}

.date-input {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.date-input label {
    font-size: 14px;
    font-weight: bold;
    color: var(--maroonColor);
}

.date-input input {
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ddd;
    border-radius: 4px;
    width: 100%;
    box-sizing: border-box;
}

/* Responsive design */
@media (max-width: 480px) {
    .date-range-container {
        flex-direction: column;
        gap: 5px;
    }
}
    </style>
</head>
<body>
<section class="form-section">
    <h2>Add a New Car</h2>
    <img id="image-preview" src="#" alt="Car Image Preview">
    <form action="#" method="POST" id="add-car-form" enctype="multipart/form-data">
        <div class="form-group full-width">
            <div class="custom-file-container">
                <div class="custom-file-input">
                    <span class="upload-btn">Upload Image</span>
                    <input type="file" id="car-image" name="car-image" accept="image/*" required>
                </div>
            </div>
            <img id="image-preview" alt="Image Preview">
        </div>

        <div class="form-container">
                                <div class="form-group">
                                    <input type="text" id="plate-number" name="plate-number" placeholder=" " required>
                                    <label for="plate-number">Plate Number</label>
                                </div>
                                <div class="form-group">
                                    <input type="text" id="color" name="color" placeholder=" " required>
                                    <label for="color">Color</label>
                                </div>
                                <div class="form-group">
                                    <input type="text" id="brand" name="brand" placeholder=" " required>
                                    <label for="brand">Brand</label>
                                </div>
                                <div class="form-group">
                                    <input type="text" id="model" name="model" placeholder=" " required>
                                    <label for="model">Model</label>
                                </div>
                                <div class="form-group">
                                    <input type="number" id="year-model" name="year-model" min="1900" max="2099" placeholder=" " required>
                                    <label for="year-model">Year Model</label>
                                </div>
                                <div class="form-group">
                                    <input type="number" id="capacity" name="capacity" min="1" placeholder=" " required>
                                    <label for="capacity">Car Capacity</label>
                                </div>
                                <div class="form-group">
                            <select id="transmission" name="transmission" required>
                                <option value="" selected disabled> </option>
                                <option value="Automatic">Automatic</option>
                                <option value="Manual">Manual</option>
                            </select>
                            <label for="transmission">Transmission Type:</label>
                        </div>

                        <div class="form-group">
                            <select id="body-type" name="body-type" required>
                                <option value="" selected disabled> </option>
                                <option value="Sedan">Sedan</option>
                                <option value="SUV">SUV</option>
                                <option value="Truck">Truck</option>
                            </select>
                            <label for="body-type">Body Type</label>
                        </div>

                    <div class="form-group full-width">
                        <input type="date" id="registration-from" name="registration-from" required>
                        <label for="registration-from">Registration From:</label>
                    </div>

                    <div class="form-group full-width">
                        <input type="date" id="registration-to" name="registration-to" required>
                        <label for="registration-to">Registration To:</label>
                     </div>
                    </div>
                    <div class="button-container">
                            <button class="submit-btn">Submit</button>
                        </div>
    </form>
 

</section>

    <!-- Modal for Image Popup -->
    <div id="image-modal" class="modal">
        <span class="close-btn">&times;</span>
        <img class="modal-content" id="full-image">
    </div>

    <script>
     document.getElementById("car-image").addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById("image-preview");
                    preview.src = e.target.result;
                    preview.style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        });

        // Image popup modal functionality
        const imagePreview = document.getElementById("image-preview");
        const modal = document.getElementById("image-modal");
        const fullImage = document.getElementById("full-image");
        const closeModal = document.querySelector(".close-btn");

        imagePreview.addEventListener("click", function() {
            fullImage.src = imagePreview.src;
            modal.style.display = "flex";
        });

        closeModal.addEventListener("click", function() {
            modal.style.display = "none";
        });

        modal.addEventListener("click", function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });

         // Add 'has-value' class when an option is selected
         document.querySelectorAll("select").forEach(select => {
            select.addEventListener("change", function() {
                if (this.value !== "") {
                    this.classList.add("has-value");
                } else {
                    this.classList.remove("has-value");
                }
            });
        });
    </script>
</body>
</html>