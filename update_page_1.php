<?php include 'header.php'; ?>
<?php include 'dbcon.php'; ?>

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch student data with state and city names
    $query = "SELECT students.*, states.name AS state_name, cities.name AS city_name 
              FROM students
              LEFT JOIN states ON students.state_id = states.id
              LEFT JOIN cities ON students.city_id = cities.id
              WHERE students.id = '$id'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    } else {
        $row = mysqli_fetch_assoc($result);

        // Fetch cities for the selected state, if available
        $selected_state_id = $row['state_id'];
        $query_cities = "SELECT * FROM cities WHERE state_id = '$selected_state_id'";
        $result_cities = mysqli_query($connection, $query_cities);
    }
}

// Fetch all states for dropdown
$query_states = "SELECT * FROM states";
$result_states = mysqli_query($connection, $query_states);
?>

<?php
if (isset($_POST['update_students'])) {
    $id = $_POST['id'];
    $Name = $_POST['Name'];
    $Email = $_POST['Email'];
    $Contact = $_POST['Contact'];
    $Gender = $_POST['Gender'];
    $DateOfBirth = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['DateOfBirth']))); 
    $state_id = $_POST['State'];
    $city_id = $_POST['City'];
    $imagePath = $row['Image']; // Default to existing image

    // Handle file upload if a new image is uploaded
    if (!empty($_FILES["Image"]["tmp_name"]) && is_uploaded_file($_FILES["Image"]["tmp_name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["Image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["Image"]["tmp_name"]);
        if ($check !== false && $_FILES["Image"]["size"] <= 3 * 1024 * 1024 && in_array($imageFileType, array("jpg", "jpeg", "png", "gif"))) {
            if (move_uploaded_file($_FILES["Image"]["tmp_name"], $target_file)) {
                $imagePath = $target_file;
            }
        }
    }

    $query = "UPDATE students SET Name = '$Name', Email = '$Email', Contact = '$Contact', Gender = '$Gender', DateOfBirth = '$DateOfBirth', state_id = '$state_id', city_id = '$city_id', Image = '$imagePath' WHERE id = '$id'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    } else {
        header('location: index.php?message=Student updated successfully&alertType=success');
        exit();
    }
}
?>

<script>
function fetchCities(stateId) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_cities.php?state_id=' + stateId, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            var cities = JSON.parse(xhr.responseText);
            var citySelect = document.getElementById('city');
            citySelect.innerHTML = '<option value="">Select City</option>';
            cities.forEach(function(city) {
                var option = document.createElement('option');
                option.value = city.id;
                option.textContent = city.name;
                if (city.id == <?php echo $row['city_id']; ?>) {
                    option.selected = true;
                }
                citySelect.appendChild(option);
            });
        } else {
            console.log('Request failed. Returned status of ' + xhr.status);
        }
    };
    xhr.send();
}
</script>

<div class="container">
    <h2>Update Student</h2>
    <form action="update_page_1.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <div class="form-group mt-2 mb-4">
            <label for="firstName">Name</label>
            <input type="text" class="form-control" id="firstName" name="Name" value="<?php echo $row['Name']; ?>">
        </div>
        <div class="form-group mb-2">
    <label for="gender">Gender</label>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" id="male" name="Gender" value="male" <?php if ($row['Gender'] == 'male') echo 'checked'; ?>>
        <label class="form-check-label" for="male">Male</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" id="female" name="Gender" value="female" <?php if ($row['Gender'] == 'female') echo 'checked'; ?>>
        <label class="form-check-label" for="female">Female</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" id="other" name="Gender" value="other" <?php if ($row['Gender'] == 'other') echo 'checked'; ?>>
        <label class="form-check-label" for="other">Other</label>
    </div>
</div>

        <div class="form-group mb-2">
            <label for="dob">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="DateOfBirth" value="<?php echo $row['DateOfBirth']; ?>">
        </div>
        <div class="form-group mb-4">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="Email" value="<?php echo $row['Email']; ?>">
        </div>
        <div class="form-group mb-2">
            <label for="contact">Mobile</label>
            <input type="number" class="form-control" id="contact" name="Contact" value="<?php echo $row['Contact']; ?>">
        </div>
        <div class="form-group mb-2">
            <label for="state">State</label>
            <select class="form-control" id="state" name="State" onchange="fetchCities(this.value)">
                <option value="">Select State</option>
                <?php while ($state = mysqli_fetch_assoc($result_states)) : ?>
                    <option value="<?php echo $state['id']; ?>" <?php if ($state['id'] == $row['state_id']) echo 'selected'; ?>><?php echo $state['name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group mb-2">
            <label for="city">City</label>
            <select class="form-control" id="city" name="City">
                <option value="">Select City</option>
                <?php while ($city = mysqli_fetch_assoc($result_cities)) : ?>
                    <option value="<?php echo $city['id']; ?>" <?php if ($city['id'] == $row['city_id']) echo 'selected'; ?>><?php echo $city['name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group mb-2">
            <label for="image">Profile Photo</label>
            <input type="file" class="form-control" id="image" name="Image">
            <img src="<?php echo $row['Image']; ?>" alt="Profile Photo" style="max-width: 100px; max-height: 100px;">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-success" name="update_students" value="Update">
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
