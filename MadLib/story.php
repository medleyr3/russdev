<?php
session_start();

$name = $_POST['name'] ?? '';
$noun1 = $_POST['noun1'] ?? '';
$verb = $_POST['verb'] ?? '';
$adjective =  $_POST['adjective'] ?? '';
$noun2 = $_POST['noun2'] ?? '';
/*
$_SESSION['words'] = [
    'name' => $name,
    'noun1' => $noun1,
    'verb' => $verb,
    'adjective' => $adjective,
    'noun2' => $noun2,
];
*/
// connect to internal database
$pdo = new PDO('sqlite:' . __DIR__ . '/data/stories.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// if there are no stories submitted insert time of submission into next position in story table.
if (!isset($_SESSION['story']))
{
    $sql = "INSERT INTO story (timecreated) VALUES(?)";
    $statement = $pdo->prepare($sql);
    $statement->execute([time()]); //gets current time stamp
    $_SESSION['story'] = $pdo->lastInsertId();
}


//remove all words
$del = "DELETE FROM story_words WHERE story_id = ?";
$deleteStatement = $pdo->prepare($del);
$deleteStatement->execute([$_SESSION['story']]);

//insert submitted story into database
$insertQuery = "INSERT INTO story_words (story_id, label, word) VALUES (?, ?, ?)";
$insertStatement = $pdo->prepare($insertQuery);
$insertStatement->execute([$_SESSION['story'], 'name', $name]);
$insertStatement->execute([$_SESSION['story'], 'noun1', $noun1]);
$insertStatement->execute([$_SESSION['story'], 'verb', $verb]);
$insertStatement->execute([$_SESSION['story'], 'adjective', $adjective]);
$insertStatement->execute([$_SESSION['story'], 'noun2', $noun2]);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"
    <title>MadLibs</title>
</head>

<body>
<h1>Here's Your Story</h1>
<p>Yesterday, <?php echo htmlentities($name);?> decided to buy a <?php echo htmlentities($adjective);?>
    <?php echo $noun1;?>. After using it to <?php echo $verb;?> with the <?php echo $noun2;?>
    they decided to give the <?php echo $noun1;?> to their friend.
</p>
<p><a href="index.php">Edit the story</a></p>
</body>
</html>