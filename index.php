<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="styleMain.css">
</head>

<body>
    <h1>Welcome!</h1>

    <form action="index.php" method="post">
        <table>
            <tr>
                <th colspan="2">Register</th>
            </tr>
            <tr>
                <td>User Name:</td>
                <td><input type="text" name="username" id="username"></td>
            </tr>

            <tr>
                <td>Email:</td>
                <td><input type="email" name="email" id="email"></td>
            </tr>

            <tr>
                <td>Password:</td>
                <td><input type="password" name="password" id="password"></td>
            </tr>

            <tr>
                <td>Confirm PW:</td>
                <td><input type="password" name="confirm" id="connfirm"></td>
            </tr>
            <tr>
                <td colspan="2"><input id="button" name="Register" type="submit" value="Register"></td>
            </tr>
        </table>

        <br>
        <table>
            <tr>
                <th colspan="2">Log In</th>
            </tr>

            <tr>
                <td>Email:</td>
                <td><input type="email" name="emailSignUp" id="emailSignUp"></td>
            </tr>

            <tr>
                <td>Password:</td>
                <td><input type="password" name="passwordSignUp" id="passwordSignUp"></td>
            </tr>
            <tr>
                <td colspan="2"><input id="button" name="LogIn" type="submit" value="Log In"></td>
            </tr>
        </table>
    </form>

</body>

</html>

<?php
session_start();
include ('db.php');
$pdo=db_connect();

if(isset($_POST["Register"])){
    try {
    
    if (strlen($_POST["password"]) < 8) {
        die("Password must be at least 8 characters");
    }
    
    if ($_POST["password"] !== $_POST["confirm"]) {
        echo "The Password Does Not Match!";
    }
    
    $username=$_POST["username"];
    $email=$_POST["email"];
    $password=$_POST["password"];
    $confirm=$_POST["confirm"];
    
    
    $sql = "INSERT INTO user_registration (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $pdo->prepare($sql);
    
        if (!$stmt) {
            die("SQL error: " . $pdo->errorInfo()[2]);
        }
       
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':username', $username);
    
        if ($stmt->execute()) {
            $_SESSION['email'] = $email;
            header("Location: dashboard.php");
        } else {
            $errorCode = $stmt->errorInfo()[1];
            if ($errorCode === 1062) {
                die("Email already taken");
            } else {
                die("Database error: " . $stmt->errorInfo()[2]);
            }
        }
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
else if(isset($_POST["LogIn"])){
    $emailSignUp=$_POST["emailSignUp"];
    $passwordSignUp=$_POST["passwordSignUp"];

    $usernameSignUp="SELECT username FROM user_registration where email=:emailSignUp";


$sql = "SELECT * FROM user_registration WHERE email = :emailSignUp AND password = :passwordSignUp";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':emailSignUp', $emailSignUp);
$stmt->bindParam(':passwordSignUp', $passwordSignUp);
$stmt->execute();


$username=$pdo->prepare($usernameSignUp);
$username->bindParam(':emailSignUp',$emailSignUp);
$username->execute();
$usernameExists = $username->fetch(PDO::FETCH_ASSOC);


if ($stmt->rowCount() > 0) {
    $_SESSION['username'] = $usernameExists['username'];
    $_SESSION['emailSignUp'] = $emailSignUp;
    header("Location: dashboard.php");
} else {

    echo "Invalid email or password.";
}
    
}

?>