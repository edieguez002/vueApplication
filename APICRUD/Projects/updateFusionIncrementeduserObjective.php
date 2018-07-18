<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

$conn = new mysqli("127.0.0.1", "agileProjects", "agileProjectsTFG", "agilProjects");
$activities = array(0 => "Pool of ideas", 1 => "A1", 2=> "A2", 3 => "A3", 4 => "Done");
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
        $sql1 = "SELECT * FROM userObjectives WHERE username = '$username' AND pName = '$pName' AND uoID = '$uoID'";
        if (!$query1 = $conn->query($sql1)) {
            $result = "ERROR";
        } else {
            while ($row = $query1->fetch_assoc()) {
                $activity = $row['state'];
                $predecessors = preg_split('/,/', $row['uoAntecesor']);
                for ($i=0; $i < sizeOf($predecessors); $i++) {
                    $row1 = [];
                    $sql2 = "SELECT * FROM userObjectives WHERE username = '$username' AND pName = '$pName' AND uoID = '$predecessors[$i]'";
                    if ($query2 = $conn->query($sql2)) {
                        while ($row1 = $query2->fetch_assoc()) {
                            if (array_search($activity, $activities) > array_search($row1['state'], $activities)) {
                                $sql3 = "UPDATE userObjectives SET state = '$activity' WHERE username = '$username' AND pName = '$pName' AND uoID = '$predecessors[$i]'";
                                if (!$query3 = $conn->query($sql3)) {
                                    $result = "ERROR";
                                }else {
                                    $result = "SUCCESS";
                                }
                            }
                            $result = "SUCCESS";
                        }
                    }
                }
            }
        }
    }

    if ($result == "SUCCESS") {
        $sql4 = "SELECT * FROM userObjectives WHERE username = '$username' AND pName = '$pName'";
        if (!$query4 = $conn->query($sql4)) {
            echo "ERROR";
        } else {
            $row2 = [];
            while ($row2 = $query4->fetch_assoc()) {
                echo $row2['uoID'] . "¿" . $row2['pName'] . "¿" . $row2['uoName'] . "¿" . $row2['uoType'] . "¿" . $row2['uoAntecesor'] . "¿" . $row2['uoSucesor'] . "¿" . $row2['description'] . "¿" . $row2['state'] . "¿" . $row2['reject'] . "¿" . $row2['inactive'] . "¿" . $row['time'] . "/";
            }
        }
    } else {
        echo "ERROR";
    }
}

$conn->close();
die();
?>
