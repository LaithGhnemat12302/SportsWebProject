<?php
session_start();
include ('db.php');
$pdo = db_connect();
$email = $_SESSION['email'];

if (isset($_GET['team_name'])) {
    $teamName = $_GET['team_name'];
    echo $teamName;

    // Retrieve the team information from the database
    $sql = "SELECT * FROM team WHERE team_name = :teamName";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':teamName', $teamName);
    $stmt->execute();
    $team = $stmt->fetch(PDO::FETCH_ASSOC);

    // Pre-fill the form fields with the existing team data
    $teamName = $team['team_name'];
    $skill = $team['skill_level'];
    $game = $team['game_day'];
}

// Handle form submission
if (isset($_POST["submit"])) {
    try {
        $teamName = $_POST["teamName"];
        $skill = $_POST["skill"];
        $game = $_POST["game"];

        // Update the team information in the database
        $sql = "UPDATE team SET team_name = :teamName, skill_level = :skill, game_day = :game WHERE team_name = :teamName";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':teamName', $teamName);
        $stmt->bindParam(':skill', $skill);
        $stmt->bindParam(':game', $game);
        //$stmt->bindParam(':teamId', $teamId);
        $stmt->execute();

        // Redirect to the dashboard
        header("Location: dashboard.php");
        exit();
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Team</title>
    <link rel="stylesheet" href="styleMain.css">
</head>

<body>
    <div class="container">
        <header>
            <ul>
                <li><a href="https://www.bbc.com/sport/football">
                        <img src="./images/pic1.jpg" alt="This is a football icon" width="50" height="50">
                    </a></li>
                <li>Football Overload</li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="./profile.html">My Profile</a></li>
                <li><a href="about.html">About Us</a></li>
                <li>
                    <div class="logout-link">
                        <a href="./index.php">Log Out</a>
                    </div>
                </li>
            </ul>
        </header>

        <div id="mySidenav" class="sidenav">
            <nav>
                <ul>
                    <li><a href="new.php">Create a New Team</a></li>
                    <li><a href="edit.php">Edit a Team</a></li>
                </ul>
            </nav>
        </div>
    </div>
    <h1>Edit Team</h1>
    <p>
    <div class="edit-link">
        <a href="dashboard.php">Dashboard</a>
    </div>
    </p>
    <br>
    <form action="edit.php" method="post">
        <table>

            <tr>
                <td>Team Name:</td>
                <td><input type="text" name="teamName" id="teamName"></td>
            </tr>

            <tr>
                <td>Skill Level (1-5):</td>
                <td><input type="text" name="skill" id="skill"></td>
            </tr>
            <tr>
                <td>Game Day:</td>
                <td><input type="text" name="game" id="game"></td>
            </tr>
            <tr>
                <td colspan="2"><input id="button" name="submit" type="submit" value="Submit"></td>
            </tr>
        </table>
    </form>
</body>

<div class="containerFoot">
    <footer>
        <ul>
            <li>@2023 Laith Ghnemat 1200610</li>
            <li><a href="mailto:laithalmalky02@gmail.com">Contact Me</a></li>
            <li>059-2674798</li>
        </ul>
    </footer>
</div>

</html>