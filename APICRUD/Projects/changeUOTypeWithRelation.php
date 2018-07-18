<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

$conn = new mysqli("127.0.0.1", "agileProjects", "agileProjectsTFG", "agilProjects");
$row = array();
$splitted = array();
$result = "";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_POST['username']) && isset($_POST['pName']) && isset($_POST['uoID']) && isset($_POST['uoName']) && isset($_POST['state']) && isset($_POST['description']) && isset($_POST['type']) && isset($_POST['oldUOPredecessor']) && isset($_POST['newUOPredecessor']) && isset($_POST['time'])) {
    $username = $_POST['username'];
    $pName = $_POST['pName'];
    $uoID = $_POST['uoID'];
    $uoName = $_POST['uoName'];
    $state = $_POST['state'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $oldUOPredecessor = $_POST['oldUOPredecessor'];
    $newUOPredecessor = $_POST['newUOPredecessor'];
    $time = $_POST['time'];
    $splitted = preg_split('/,/', $oldUOPredecessor);
    if ($splitted[0] != "") {
        for ($i=0; $i < sizeOf($splitted); $i++) {
            $row = [];
            $newSucessors = "";
            $sql = "SELECT * FROM userObjectives WHERE username = '$username' AND pName = '$pName' AND uoID = '$splitted[$i]'";
            if (!$query = $conn->query($sql)) {
                $result = "ERROR";
            } else {
                while ($row = $query->fetch_assoc()) {
                    $sucessors = preg_split('/,/', $row['uoSucesor']);
                    for ($j=0; $j < sizeOf($sucessors); $j++) {
                        if ($uoID != $sucessors[$j]) {
                            if ($j == 0) {
                                $newSucessors = $sucessors[$j];
                            } elseif ($newSucessors != "" && $j < sizeOf($sucessors)-1) {
                                $newSucessors = $newSucessors . "," . $sucessors[$j];
                            } else {
                                if ($newSucessors == "") {
                                    $newSucessors = $sucessors[$j];
                                } else {
                                    $newSucessors = $newSucessors . "," . $sucessors[$j];
                                }
                            }
                        }
                    }
                    $sql1 = "UPDATE userObjectives SET uoSucesor = '$newSucessors' WHERE username = '$username' AND pName = '$pName' AND uoID = '$splitted[$i]'";
                    if (!$query1 = $conn->query($sql1)) {
                        $result = "ERROR";
                    } else {
                        $result = "SUCCESS";
                    }
                }
            }
        }
    }else {
        $result = "SUCCESS";
    }

    if ($result == "SUCCESS") {
        $sql2 = "UPDATE userObjectives SET uoAntecesor = '$newUOPredecessor', uoType = '$type', uoName = '$uoName', state = '$state', description = '$description', time = '$time' WHERE username = '$username' AND pName = '$pName' AND uoID = '$uoID'";
        if (!$query2 = $conn->query($sql2)) {
            $result = "ERROR";
        } else {
            $splitted = preg_split('/,/', $newUOPredecessor);
            for ($i=0; $i < sizeOf($splitted); $i++) {
                $row = [];
                $newSucessors = "";
                $sql3 = "SELECT * FROM userObjectives WHERE username = '$username' AND pName = '$pName' AND uoID = '$splitted[$i]'";
                if (!$query3 = $conn->query($sql3)) {
                    $result = "ERROR";
                } else {
                    while ($row = $query3->fetch_assoc()) {
                        $sucessors = $row['uoSucesor'];
                        if ($sucessors == "") {
                            $sucessors = $uoID;
                        } else {
                            $sucessors = $sucessors . "," . $uoID;
                        }
                        $sql4 = "UPDATE userObjectives SET uoSucesor = '$sucessors' WHERE username = '$username' AND pName = '$pName' AND uoID = '$splitted[$i]'";
                        if (!$query4 = $conn->query($sql4)) {
                            $result = "ERROR";
                        } else {
                            $result = "SUCCESS";
                        }
                    }

                }
            }
        }
    }

    if ($result == "SUCCESS") {
        $sql5 = "SELECT * FROM userObjectives WHERE username = '$username' AND pName = '$pName'";
        if (!$query5 = $conn->query($sql5)) {
            echo "ERROR";
        } else {
            $row = [];
            while ($row = $query5->fetch_assoc()) {
                echo $row['uoID'] . "¿" . $row['pName'] . "¿" . $row['uoName'] . "¿" . $row['uoType'] . "¿" . $row['uoAntecesor'] . "¿" . $row['uoSucesor'] . "¿" . $row['description'] . "¿" . $row['state'] . "¿" . $row['reject'] . "¿" . $row['inactive'] . "¿" . $row['time'] . "/";
            }
        }
    } else {
        echo "ERROR";
    }
}

$conn->close();
die();
?>
