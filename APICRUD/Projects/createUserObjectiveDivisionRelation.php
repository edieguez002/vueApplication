<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

$conn = new mysqli("127.0.0.1", "agileProjects", "agileProjectsTFG", "agilProjects");
$row = array();
$result = "";
$state = "";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['username']) && isset($_POST['pName']) && isset($_POST['uoID']) && isset($_POST['uoName']) && isset($_POST['uoType']) && isset($_POST['description']) && isset($_POST['uoAntecesor'])) {
    $username = $_POST['username'];
    $pName = $_POST['pName'];
    $uoID = $_POST['uoID'];
    $uoName = $_POST['uoName'];
    $uoType = $_POST['uoType'];
    $description = $_POST['description'];
    $uoAntecesor = $_POST['uoAntecesor'];
    $sql = "SELECT * FROM userObjectives WHERE username = '$username' AND pName = '$pName' AND uoID = '$uoAntecesor'";
    if (!$query = $conn->query($sql)) {
        $result = "ERROR";
    } else {
        while ($row = $query->fetch_assoc()) {
            $state = $row['state'];
            $sucessors = $row['uoSucesor'];
            if ($sucessors == "") {
                $sucessors = $uoID;
            } else {
                $sucessors = $sucessors . "," . $uoID;
            }
            $sql1 = "UPDATE userObjectives SET uoSucesor = '$sucessors' WHERE username = '$username' AND pName = '$pName' AND uoID = '$uoAntecesor'";
            if (!$query1 = $conn->query($sql1)) {
                $result = "ERROR";
            } else {
                $result = "SUCCESS";
            }
        }
    }
    if ($result == "SUCCESS") {
        $sql2 = "INSERT INTO userobjectives (username, pName, uoID, uoName, uoType, description, state, uoAntecesor, uoSucesor, reject, inactive, time) VALUES ('$username', '$pName', '$uoID', '$uoName', '$uoType', '$description', '$state', '$uoAntecesor', '', 0, 0, 0.0)";
        if (!$query2 = $conn->query($sql2)) {
            echo "ERROR";
        } else {
            $sql3 = "SELECT * FROM userObjectives WHERE username = '$username' AND pName = '$pName'";
            if (!$query3 = $conn->query($sql3)) {
                echo "ERROR";
            } else {
                $row = [];
                while ($row = $query3->fetch_assoc()) {
                    echo $row['uoID'] . "¿" . $row['pName'] . "¿" . $row['uoName'] . "¿" . $row['uoType'] . "¿" . $row['uoAntecesor'] . "¿" . $row['uoSucesor'] . "¿" . $row['description'] . "¿" . $row['state'] . "¿" . $row['reject'] . "¿" . $row['inactive'] . "¿" . $row['time'] . "/";
                }
            }
        }
    } else {
        echo "ERROR";
    }
}

$conn->close();
die();
?>
