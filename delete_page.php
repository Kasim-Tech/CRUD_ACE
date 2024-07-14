<?php
include 'header.php';
include 'dbcon.php';

// Handle delete operation
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM `students` WHERE `id` = '$id'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Failed: " . mysqli_error($connection));
    } else {
        header("Location: index.php?message=Student deleted successfully&alertType=error");
        exit();
    }
}
?>
