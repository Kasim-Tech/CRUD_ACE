<?php
include('dbcon.php');

if (isset($_GET['state_id'])) {
    $state_id = $_GET['state_id'];
    $query = "SELECT * FROM cities WHERE state_id = '$state_id'";
    $result = mysqli_query($connection, $query);
    
    $cities = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $cities[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($cities);
}
?>
