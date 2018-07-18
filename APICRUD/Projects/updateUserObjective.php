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

if (isset($_POST['username']) && isset($_POST['pName']) && isset($_POST['uoID']) && isset($_POST['state']) && isset($_POST['uoName']) && isset($_POST['description']) && isset($_POST['time'])) {
    $username = $_POST['username'];
    $pName = $_POST['pName'];
    $uoID = $_POST['uoID'];
    $state = $_POST['state'];
    $uoName = $_POST['uoName'];
    $description = $_POST['description'];
    $time = $_POST['time'];
    $sql = "UPDATE userObjectives SET state = '$state', uoName = '$uoName', description = '$description', time = '$time' WHERE username = '$username' AND pName = '$pName' AND uoID = '$uoID'";
    if (!$query = $conn->query($sql)) {
        $result = "ERROR";
    } else {
        $result = "SUCCESS";
    }
}

if ($result == "SUCCESS") {
    $sql1 = "SELECT * FROM userObjectives WHERE username = '$username' AND pName = '$pName'";
    if (!$query1 = $conn->query($sql1)) {
        echo "ERROR";
    } else {
        $row = [];
        while ($row = $query1->fetch_assoc()) {
            echo $row['uoID'] . "¿" . $row['pName'] . "¿" . $row['uoName'] . "¿" . $row['uoType'] . "¿" . $row['uoAntecesor'] . "¿" . $row['uoSucesor'] . "¿" . $row['description'] . "¿" . $row['state'] . "¿" . $row['reject'] . "¿" . $row['inactive'] . "¿" . $row['time'] . "/";
        }
    }
} else {
    echo "ERROR";
}

$conn->close();
die();
?>
