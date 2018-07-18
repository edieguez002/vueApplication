<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

$conn = new mysqli("127.0.0.1", "agileProjects", "agileProjectsTFG", "agilprojects");
$row = array();
$count = 0;
$result = "";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['username']) && isset($_POST['oldName']) && isset($_POST['oldDescription']) && isset($_POST['newName']) && isset($_POST['newDescription'])) {
    $username = $_POST['username'];
    $oldName = $_POST['oldName'];
    $oldDescription = $_POST['oldDescription'];
    $newName = $_POST['newName'];
    $newDescription = $_POST['newDescription'];
    // Verify that the user hasn't got a project with the new name -->
    if ($oldName != $newName && $oldDescription != $newDescription) {
        $sql = "SELECT * FROM projects WHERE username = '$username' AND name = '$newName'";
        if ($query = $conn->query($sql)) {
            while($row = $queery->fetch_assoc()) {
                $count = $count + 1;
            }
        }

        if ($count == 0) {
            $sql = "UPDATE projects SET name = '$newName', description = '$newDescription' WHERE username = '$username' AND name = '$oldName'";
            if (!$query = $conn->query($sql)) {
                $result = "ERROR";
            } else {
                $result = $newName . "¿" . $newDescription;
            }
        } else {
            $result = "PROJECTACTIVE";
        }
    } elseif ($oldName != $newName && $oldDescription == $newDescription) {
        $sql = "SELECT * FROM projects WHERE username = '$username' AND name = '$newName'";
        if ($query = $conn->query($sql)) {
            while($row = $query->fetch_assoc()) {
                $count = $count + 1;
            }
        }

        if ($count == 0) {
            $sql = "UPDATE projects SET name = '$newName' WHERE username = '$username' AND name = '$oldName'";
            if (!$query = $conn->query($sql)) {
                $result = "ERROR";
            } else {
                $result = $newName . "¿" . $newDescription;
            }
        } else {
            $result = "PROJECTACTIVE";
        }
    } elseif ($oldName == $newName && $oldDescription != $newDescription) {
        $sql = "UPDATE projects SET description = '$newDescription' WHERE username = '$username' AND name = '$oldName'";
        if (!$query = $conn->query($sql)) {
            $result = "ERROR";
        } else {
            $result = $newName . "¿" . $newDescription;
        }
    }
}
echo $result;
$conn->close();
die();
?>
