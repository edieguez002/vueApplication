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

if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $result = "";
    $sqlusername = "SELECT * FROM users WHERE userName= '$username'";
    $sqlemail = "SELECT * FROM users WHERE email= '$email'";
    if ($query = $conn->query($sqlusername)) {
        while($row = $query->fetch_assoc()) {
            if ($username == $row['userName']) {
                $result = "USERNAMEACTIVE";
            }
        }
    }
    if ($result == "" && $query = $conn->query($sqlemail)) {
        while($row = $query->fetch_assoc()) {
            if ($email == $row['email']) {
                $result = "EMAILACTIVE";
            }
        }
    }
    if ($result == "") {
        $row = array();
        $sql = "INSERT INTO users(firstName, lastName, userName, email, password) VALUES ('$firstname', '$lastname', '$username', '$email', '$password')";
        if (!$query = $conn->query($sql)) {
            $result = "ERROR";
        }else {

            $result = $username . " " . $firstname . " " . $lastname . " " . $email;
        }
    }

}
echo $result;
$conn->close();
die();
?>
