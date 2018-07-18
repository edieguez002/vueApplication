<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

$conn = new mysqli("127.0.0.1", "agileProjects", "agileProjectsTFG", "agilProjects");
$row = array();
$result = "";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $sql = "SELECT * FROM projects WHERE username = '$username'";
    if (!$query = $conn->query($sql)) {
        $result = "ERROR";
    }else {
        while ($row = $query->fetch_assoc()) {
            echo $row['name'] . "?" . $row['description'] . "?" . $row['archived'] . "/";
        }
    }
}

$conn->close();
die();
?>
