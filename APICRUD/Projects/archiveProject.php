<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

$conn = new mysqli("127.0.0.1", "agileProjects", "agileProjectsTFG", "agilProjects");
$result = "";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['username']) && isset($_POST['pname'])) {
    $username = $_POST['username'];
    $pname = $_POST['pname'];
    $sql = "UPDATE projects SET archived = TRUE WHERE username = '$username' and name = '$pname'";
    if (!$query = $conn->query($sql)) {
        $result = "ERROR";
    } else {
        $result = "SUCCESS";
    }
} else {
    $result = "ERROR";
}
echo $result;
$conn->close();
die();
?>
