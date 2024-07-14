<?php include 'header.php' ?>
<?php include 'dbcon.php' ?>

<!-- Toast Notification -->
<div aria-live="polite" aria-atomic="true" class="position-relative">
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <?php
                if (isset($_GET['message'])) {
                    $alertType = 'bg-success'; // Default to success
                    if (isset($_GET['alertType']) && $_GET['alertType'] == 'error') {
                        $alertType = 'bg-danger';
                    }
                    echo "<div class='alert $alertType'>" . $_GET['message'] . "</div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="box1">
    <h2>All Students</h2>

    <!-- Button trigger modal -->
    <div class="container mt-4 justify-content-center">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="material-icons align-middle mb-1">person_add</i> Add Student
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> Add Student</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="insert_data.php" method="post" enctype="multipart/form-data">
                        <div class="form-group mt-2 mb-4">
                            <label for="firstName">Name</label>
                            <input type="text" class="form-control" id="firstName" name="Name">
                        </div>
                        <div class="form-group mb-4">
                            <label>Gender</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="male" name="Gender" value="male"
                                    required>
                                <label class="form-check-label" for="male">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="female" name="Gender" value="female"
                                    required>
                                <label class="form-check-label" for="female">Female</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="other" name="Gender" value="other"
                                    required>
                                <label class="form-check-label" for="other">Other</label>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label for="dob">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="DateOfBirth">
                        </div>
                        <div class="form-group mb-4">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="Email">
                        </div>
                        <div class="form-group mb-2">
                            <label for="contact">Mobile</label>
                            <input type="number" class="form-control" id="contact" name="Contact">
                        </div>
                        <div class="form-group mb-2">
                            <label for="state">State</label>
                            <select class="form-control" id="state" name="State" onchange="fetchCities(this.value)">
                                <option value="">Select State</option>
                                <?php
                                $query = "SELECT * FROM states";
                                $result = mysqli_query($connection, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="city">City</label>
                            <select class="form-control" id="city" name="City">
                                <option value="">Select City</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="image">Profile Photo</label>
                            <input type="file" class="form-control" id="image" name="Image">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="material-icons align-middle">close</i> Close
                            </button>
                            <button type="submit" class="btn btn-success" name="add_students">
                                <i class="material-icons align-middle mb-1">person_add</i> ADD
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-hover table-border table-striped">
        <thead>
            <tr class="text-center ">
                <th>ID</th>
                <th>Profile Photo</th>
                <th>Name</th>
                <th>Gender</th>
                <th>DOB</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>State</th>
                <th>City</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT students.id, students.Name, students.Gender, students.DateOfBirth, students.Email, students.Contact, states.name AS state_name, cities.name AS city_name, students.Image 
                      FROM students
                      LEFT JOIN states ON students.state_id = states.id
                      LEFT JOIN cities ON students.city_id = cities.id";
            $result = mysqli_query($connection, $query);
            if (!$result) {
                die("Query failed");
            } else {
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr class="text-center">
                        <td class="vertical-center custome-sty"><?php echo $row['id']; ?></td>
                        <td class="vertical-center">
                            <?php if (!empty($row['Image'])): ?>
                                <img src="<?php echo $row['Image']; ?>" alt="Profile Photo"
                                    style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover; background-color: transparent;">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td class="vertical-center custome-sty"><?php echo $row['Name']; ?></td>
                        <td class="vertical-center custome-sty"><?php echo $row['Gender']; ?></td>
                        <td class="vertical-center custome-sty"><?php echo $row['DateOfBirth']; ?></td>
                        <td class="vertical-center custome-sty"><?php echo $row['Email']; ?></td>
                        <td class="vertical-center custome-sty"><?php echo $row['Contact']; ?></td>
                        <td class="vertical-center custome-sty"><?php echo $row['state_name']; ?></td>
                        <td class="vertical-center custome-sty"><?php echo $row['city_name']; ?></td>
                        <td class="vertical-center">
                            <a href="update_page_1.php?id=<?php echo $row['id']; ?>"
                                style="text-decoration: none; color: #4CAF50; font-size: 30px;">
                                <span class="material-icons" style="vertical-align: middle; font-size: inherit;">edit</span>
                            </a>
                            <a href="delete_page.php?id=<?php echo $row['id']; ?>" class="delete-link" data-bs-toggle="modal"
                                data-bs-target="#deleteModal" style="text-decoration: none; color: #f44336; font-size: 24px;">
                                <span class="material-icons" style="vertical-align: middle; font-size: 24px;">delete</span>
                            </a>


                        </td>
                    </tr>
                   <!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this student record?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-danger" id="deleteButton">Delete</a>
            </div>
        </div>
    </div>
</div>


                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function fetchCities(stateId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_cities.php?state_id=' + stateId, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                var cities = JSON.parse(xhr.responseText);
                var citySelect = document.getElementById('city');
                citySelect.innerHTML = '<option value="">Select City</option>';
                cities.forEach(function (city) {
                    var option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    citySelect.appendChild(option);
                });
            } else {
                console.log('Request failed. Returned status of ' + xhr.status);
            }
        };
        xhr.send();
    }

    // Trigger the toast
    document.addEventListener('DOMContentLoaded', function () {
        var toastElement = document.getElementById('toast');
        if (toastElement) {
            var toast = new bootstrap.Toast(toastElement);
            toast.show();
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var deleteLinks = document.querySelectorAll('.delete-link');
        deleteLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                var deleteUrl = this.getAttribute('href');
                document.getElementById('deleteButton').setAttribute('href', deleteUrl);
                var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                modal.show();
            });
        });

        // Reset modal backdrop on modal close
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('hidden.bs.modal', function () {
            document.querySelector('.modal-backdrop').remove();
        });
    });
</script>



<?php include ('footer.php') ?>