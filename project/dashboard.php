<?php
session_start();
include "db.php";
$pdo = db_connect();
$sql = "SELECT * FROM team";
$statement = $pdo->prepare($sql);
$statement->execute();
$row = $statement->fetchALL();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard</title>
    <link rel="stylesheet" href="dashStyle.css">
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
                        <a href="./index.php">log out</a>
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
    <br>
    <h3>Welcome, <?php
                    $username = $_SESSION['username'];
                    echo $username;
                    ?></h3>

    <br>
    <table>
        <tr>
            <th>Team Name</th>
            <th>Skill Level (1-5)</th>
            <th>Players</th>
            <th>Game Day</th>
        </tr>
        <?php
        foreach ($row as $team) {
            $teamName = $team['team_name'];
            $sql = "SELECT COUNT(*) FROM player WHERE team_name = :team_name";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":team_name", $teamName);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            echo "<tr>";
            echo "<td><a href='details.php?team_name={$team['team_name']}'>{$team['team_name']}</a></td>";
            echo "<td>" . $team['skill_level'] . "</td>";
            echo "<td>" . $count . "/9</td>";
            echo "<td>" . $team['game_day'] . "</td>";
        }
        ?>
    </table>
    <br><br>
    <form action="dashboard.php" method="post">
        <td colspan="2"><input id="button" name="create" type="submit" value="Create New Team"></td>
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
<?php

if (isset($_POST["create"])) {
    $email = $_SESSION['email'];
    header("Location: new.php");
}

?>