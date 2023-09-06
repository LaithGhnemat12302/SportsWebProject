<?php
session_start();
include ('db.php');
$pdo = db_connect();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Team</title>
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

    <h1>Create a New Team</h1>
    <p>
    <div class="edit-link">
        <a href="dashboard.php">Dashboard</a>
    </div>
    </p>
    <br>
    <form action="new.php" method="post">
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
                <td colspan="2"><input id="button" name="submit" type="submit"></td>
            </tr>
        </table>
    </form>

    <?php
    if (isset($_POST["submit"])) {
        try {
            $teamName = $_POST["teamName"];
            $email = $_SESSION['email'];
            $skill = $_POST["skill"];
            $game = $_POST["game"];

            if (empty($teamName) || empty($skill) || empty($game)) {
                die("<p style='text-align: center; color: red; font-weight: bold;'>Please enter a team name, skill level, and game day</p>");
            }
            
            if ($skill < 1 || $skill > 5) {
                die("<p style='text-align: center; color: red; font-weight: bold;'>Skill level must be between 1 and 5</p>");
            }
            
            $validGameDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            if (!in_array($game, $validGameDays)) {
                die("<p style='text-align: center; color: red; font-weight: bold;'>Invalid game day</p>");
            }
            

            // Check if the team name already exists
            $checkSql = "SELECT COUNT(*) FROM team WHERE team_name = :teamName";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->bindParam(':teamName', $teamName);
            $checkStmt->execute();
            $teamExists = $checkStmt->fetchColumn();

            if ($teamExists) {
                die("Team Name already taken");
            }

            $sql = "INSERT INTO team (team_name, skill_level, game_day, email) VALUES (:teamName, :skill, :game, :email)";
            $stmt = $pdo->prepare($sql);

            if (!$stmt) {
                die("SQL error: " . $pdo->errorInfo()[2]);
            }

            $stmt->bindParam(':teamName', $teamName);
            $stmt->bindParam(':skill', $skill);
            $stmt->bindParam(':game', $game);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {
                $_SESSION['email'] = $email;
                header("Location: dashboard.php");
            } else {
                die("Database error: " . $stmt->errorInfo()[2]);
            }
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    ?>


    <div class="containerFoot">
        <footer>
            <ul>
                <li>@2023 Laith Ghnemat 1200610</li>
                <li><a href="mailto:laithalmalky02@gmail.com">Contact Me</a></li>
                <li>059-2674798</li>
            </ul>
        </footer>
    </div>
</body>

</html>