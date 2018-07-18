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

if (isset($_POST['oldUsername']) && isset($_POST['oldEmail']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['username']) && isset($_POST['email'])) {
    $oldUsername = $_POST['oldUsername'];
    $oldEmail = $_POST['oldEmail'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    //When username and email are the same as the old, only change the other info -->
    if ($oldUsername == $username && $oldEmail == $email) {
        $sql = "UPDATE users SET firstName = '$firstname', lastName = '$lastname' WHERE userName = '$username' AND email = '$email'";
        if (!$query = $conn->query($sql)) {
            $result = "ERROR";
        } else {
            $result = $username . "¿" . $email . "¿" . $firstname . "¿" . $lastname;
        }
    //When the user changed the username or email verify there isn't other user with the same username and email -->
    } else if ($oldUsername != $username || $oldEmail != $email) {
        $sql = "SELECT * FROM users WHERE userName = '$username' AND email = '$email'";
        $count = 0;
        if ($query = $conn->query($sql)) {
            while ($row = $query->fetch_assoc()) {
                $count = $count + 1;
            }
        } else {
            $result = "ERROR";
        }
        //Get the  user and update all the data -->
        if ($count == 0) {
            $sql = "UPDATE users SET userName = '$username', email = '$email', firstName = '$firstname', lastName = '$lastname' WHERE userName = '$oldUsername' AND email = '$oldEmail'";
            if (!$query = $conn->query($sql)) {
                $result = "ERROR";
            } else {
                $result = $username . "¿" . $email . "¿" . $firstname . "¿" . $lastname;
            }
        } else {
            $result = "EMAILUSERNAMEACTIVE";
        }
    }
    if ($result != "ERROR" || $result != "EMAILUSERNAMEACTIVE") {
        //Change password -->
        if (isset($_POST['password']) && $_POST['password'] != "") {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = '$password' WHERE userName = '$oldUsername' AND email = '$oldEmail'";
            if (!$query = $conn->query($sql)) {
                $result = "ERROR";
            }
        }
    }
}

echo $result;
$conn->close();
die();
?>
