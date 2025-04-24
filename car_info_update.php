<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $plate_number = $_POST['plate_number'];
    $color = $_POST['color'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $year_model = $_POST['year_model'];
    $capacity = $_POST['capacity'];
    $body_type = $_POST['body_type'];
    $transmission = $_POST['transmission'];
    $registration_from = $_POST['registration_from'];
    $registration_to = $_POST['registration_to'];

    $sql = "UPDATE carstb SET color=?, brand=?, model=?, year_model=?, capacity=?, body_type=?, transmission=?, registration_from=?, registration_to=? WHERE plate_number=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $color, $brand, $model, $year_model, $capacity, $body_type, $transmission, $registration_from, $registration_to, $plate_number);

    if ($stmt->execute()) {
        echo "<script>alert('Successfully changed'); window.location.href='GSO.php?mveh=a';</script>";
    } else {
        echo "<script>alert('Update failed: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
