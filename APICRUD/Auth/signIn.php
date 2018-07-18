<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

$conn = new mysqli("127.0.0.1", "agileProjects", "agileProjectsTFG", "agilprojects");
$row = array();
$count = 0;

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['username']) && $_POST['username'] != "" && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $result = "";
    $sql = "SELECT * FROM users WHERE userName = '$username'";
    if (!$query = $conn->query($sql)) {
        $count = 0;
    } else {
        while ($row = $query->fetch_assoc()) {
            if ($username == $row['userName'] && password_verify($password, $row['password'])) {
                $count = $count + 1;
                $firstname = $row['firstName'];
                $lastname = $row['lastName'];
                $email = $row['email'];
            }
        }
    }
} elseif (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    if (!$query = $conn->query($sql)) {
        $count = 0;
    } else {
        while ($row = $query->fetch_assoc()) {
            if ($email == $row['email'] && $password == $_POST['password']) {
                $count = $count + 1;
                $username = $row['userName'];
                $firstname = $row['firstName'];
                $lastname = $row['lastName'];
            }
        }
    }
}

if ($count == 0) {
    $result = "EMPTY";
} elseif ($count == 1) {
    $result = $username . " " . $firstname . " " . $lastname . " " . $email;
} else {
    $result = "ERROR";
}
echo $result;
$conn->close();
die();
?>
