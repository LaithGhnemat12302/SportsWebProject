<?php
// details.php
session_start();
include ('db.php');
$pdo = db_connect();

if (isset($_GET['team_name'])) {
    $teamName = $_GET['team_name'];
    $query = "SELECT skill_level FROM team WHERE team_name = :teamName";
    $statement = $pdo->prepare($query);
    $statement->bindValue(':teamName', $teamName);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);



    $q = "SELECT game_day FROM team WHERE team_name = :teamName";
    $stat = $pdo->prepare($q);
    $stat->bindValue(':teamName', $teamName);
    $stat->execute();
    $resultGame = $stat->fetch(PDO::FETCH_ASSOC);


    $sql = "SELECT player_name FROM player WHERE team_name = :team_name;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":team_name", $teamName);
    $stmt->execute();
    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Team name not provided.";
}

if (isset($_POST["add"])) {
    $teamName = $_GET['team_name'];

    $sqll = "SELECT COUNT(*) FROM player WHERE team_name = :team_name";
    $stmtt = $pdo->prepare($sqll);
    $stmtt->bindValue(":team_name", $teamName);
    $stmtt->execute();
    $count = $stmtt->fetchColumn();
    if ($count < 9) {
        $playerName = $_POST["playerName"];
        // Check if the player name is not empty
        if (!empty($playerName)) {
            $insertQuery = "INSERT INTO player (player_name, team_name) VALUES (:playerName, :teamName)";
            $insertStmt = $pdo->prepare($insertQuery);
            $insertStmt->bindValue(':playerName', $playerName);
            $insertStmt->bindValue(':teamName', $teamName);
            $insertStmt->execute();
        } else {
            echo '<span style="color: red; font-weight: bold;">Please enter a player name.</span>';
        }
    } else {
        echo '<div style="text-align: center;"><span style="color: red; font-weight: bold;">The team is full, you cannot add more players.</span></div>';
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details</title>
    <link rel="stylesheet" href="detail.css">
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

    <form action="<?php $_SERVER["PHP_SELF"] ?>" method="post">
        <h2><?php
            echo $teamName; ?>
        </h2>

        <a href="./dashboard.php">Dashboard</a>
        <br><br>

        <p>
            <?php
            echo "Team Name: " . $teamName . "<br>";
            if ($result) {
                $skill = $result['skill_level'];
                echo "Skill Level: " . $skill . "<br>";
            }
            if ($resultGame) {
                $game = $resultGame['game_day'];
                echo "Game Day: " . $game . "<br>";
            }
            ?>
        </p>

        <h3>Players:</h3>

        <p>
            <?php
            foreach ($players as $player) {
                echo $player['player_name'] . "<br>"; // Print each player name
            }
            ?>
        </p>

        <h3>Add Player:</h3>
        <table>
            <tr>
                <td>Player Name:</td>
                <td><input type="text" name="playerName" id="playerName"></td>
            </tr>
            <tr>
                <td colspan="2"><input id="button" name="add" type="submit" value="Add"></td>
            </tr>
        </table>
    </form>

    <p>
        <?php
        echo "<a href='edit.php?team_name={$_GET['team_name']}'>edit</a>";
        ?>
        <br>
        <?php
        echo "<a href='delete.php?team_name=$teamName'>delete</a>";
        ?>
    </p>

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