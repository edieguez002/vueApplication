<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

$conn = new mysqli("127.0.0.1", "agileProjects", "agileProjectsTFG", "agilProjects");
$result = "";

if (isset($_POST['username']) && isset($_POST['pName']) && isset($_POST['uoID'])) {
    $username = $_POST['username'];
    $pName = $_POST['pName'];
    $uoID = $_POST['uoID'];
    $sql = "UPDATE userObjectives SET inactive = 0 WHERE username = '$username' AND pName = '$pName' AND uoID = '$uoID'";
    if (!$query = $conn->query($sql)) {
        $result = "ERROR";
    } else {
        $result = "SUCCESS";
    }
}
echo $result;
$conn->close();
die();
?>
