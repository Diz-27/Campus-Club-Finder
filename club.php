<?php

//require_once makes sure there isn't a collision with multiple people using the same database.
require __DIR__ . '/includes/database.php';
require_once __DIR__ . '/classes/Club.php';

// $sql = "SELECT clubs.club_name, clubs.description, clubs.meeting_time, clubs.meeting_location, clubs.contact_email
//         FROM clubs, categories
//         JOIN clubs.category_id = categories.category_id
//         ";

// $statement = $db->query($sql);
// $row = $statement->fetch(PDO::FETCH_ASSOC);

// $club = Club::fromRow($row);
function getClub(PDO $db, int $id): ?Club{
    $sql = 'SELECT categories.category_id, categories.category_name, clubs.club_id, clubs.club_name, clubs.description, clubs.meeting_time, clubs.meeting_location, clubs.contact_email
            FROM clubs
            JOIN categories ON clubs.category_id = categories.category_id
            WHERE clubs.club_id = :filter_id';

    $statement = $db->prepare($sql);
    // Remember that :filter_id is a named placeholder.
    $statement->execute([
        ':filter_id'=> $id,
    ]);

    
    $row = $statement->fetch(PDO::FETCH_ASSOC);

    // this is an early return and exits early if there's no club present.
    if($row == null){
        return null;
    }
     //This is the array the function must return to
    $club= Club::fromRow($row);
    
    // returns to the $clubs array
    return $club;
}
// if the $_GET is in the URL parameter, then we will us it. Otherwise it will be null.
$club = isset($_GET['id']) ? getClub($db, $_GET['id']) : null;

$errors = [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club</title>
    <link rel="stylesheet" href="styles.css">

  
</head>
<body>
      <h1><a href="clubs.php">All Clubs</a></h1>

    <!-- Validating Clubs -->
    <?php if (!$club == null): ?>
    <p><?= htmlspecialchars($club->club_name) ?></p>
    <p><?= htmlspecialchars($club->category_name) ?></p>
    <p><?= htmlspecialchars($club->description) ?></p>
    <p><?= htmlspecialchars($club->meeting_time) ?></p>
    <p><?= htmlspecialchars($club->meeting_location) ?></p>
    <p><?= htmlspecialchars($club->contact_email) ?></p>

         <p>No clubs were found in this category</p>

    <?php endif; ?>
</body>
</html>