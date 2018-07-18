<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

$conn = new mysqli("127.0.0.1", "agileProjects", "agileProjectsTFG", "agilProjects");
$row = array();
$uos = array();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $sql = "SELECT * FROM userObjectives WHERE username = '$username'";
    if (!$query = $conn->query($sql)) {
        echo "ERROR";
    } else {
        while ($row = $query->fetch_assoc()) {
            echo $row['uoID'] . "¿" . $row['pName'] . "¿" . $row['uoName'] . "¿" . $row['uoType'] . "¿" . $row['uoAntecesor'] . "¿" . $row['uoSucesor'] . "¿" . $row['description'] . "¿" . $row['state'] . "¿" . $row['reject'] . "¿" . $row['inactive'] . "¿" . $row['time'] . "/";
        }
    }
} else {
    echo "ERROR";

}

$conn->close();
die();
?>
