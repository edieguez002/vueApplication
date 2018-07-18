<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

$conn = new mysqli("127.0.0.1", "agileProjects", "agileProjectsTFG", "agilprojects");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['username'])){
    $name = $_POST['name'];
    $username = $_POST['username'];
    $description = $_POST['description'];
    $result = "";
    $sql = "INSERT INTO projects(name, username, description, archived) VALUES ('$name', '$username', '$description', 0)";
    if(!$query = $conn->query($sql)) {
        $result = "ERROR";
    } else {
        $result = "SUCCESS";
    }
}
echo $result;
$conn->close();
die();
?>
