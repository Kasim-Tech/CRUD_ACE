<?php
include('dbcon.php'); // Include your database connection file

if (isset($_POST['add_students'])) {
    $Name = $_POST['Name'];
    $Email = $_POST['Email'];
    $Contact = $_POST['Contact'];
    $Gender = $_POST['Gender'];
    $DateOfBirth = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['DateOfBirth'])));
    $State = $_POST['State'];
    $City = $_POST['City'];
    
    // File upload handling
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["Image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (!empty($_FILES["Image"]["tmp_name"]) && is_uploaded_file($_FILES["Image"]["tmp_name"])) {
        $check = getimagesize($_FILES["Image"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if ($_FILES["Image"]["size"] > 3 * 1024 * 1024) {
            echo "Sorry, your file is too large. Maximum size allowed is 3MB.";
            $uploadOk = 0;
        }

        $allowed_extensions = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowed_extensions)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["Image"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["Image"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "No file uploaded or invalid file.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        $query = "INSERT INTO students (Name, Email, Contact, Gender, DateOfBirth, state_id, city_id, Image) VALUES ('$Name', '$Email', '$Contact', '$Gender', '$DateOfBirth', '$State', '$City', '$target_file')";
        $result = mysqli_query($connection, $query);

        if ($result) {
            header("Location: index.php?message=Student added successfully");
            exit();
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }
}
?>
