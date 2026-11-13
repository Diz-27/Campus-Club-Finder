<?php
declare(strict_types=1);

// this is how we connect the other files together (includes/database.php)
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/classes/Club.php';

// Writing the SELECT from the two tables, remember that order matters.
function getClubs(PDO $db, int $categoryId = 0): array{
    $sql = 'SELECT categories.category_id, categories.category_name, clubs.club_id, clubs.club_name, clubs.description, clubs.meeting_time, clubs.meeting_location, clubs.contact_email
            FROM clubs
            JOIN categories ON clubs.category_id = categories.category_id
            WHERE clubs.category_id = :filter_id
            ORDER BY clubs.club_name ASC;';

    // This is the PDO statement
    $statement = $db->prepare($sql);
    //This is the collection of everything because of fetchAll
    // we used this to bind the query and it doesn't return anything. It just runs it.
    // :filter_id is the named placeholder.
    $statement->execute([
        'filter_id' => $categoryId > 0 ? $categoryId : '*',
    ]);
    $clubSet = $statement->fetchAll(PDO::FETCH_ASSOC);
    //This is the array the function must return to
    $clubs = [];
    foreach($clubSet as $row){
        $clubs[] = Club::fromRow($row);
    }
    // returns to the $clubs array
    return $clubs;

    
    
}
// Turn each row into a Club with Club::fromRow()
$clubs = getClubs($db);

function getCategories(PDO $db): array{
    $sql = 'SELECT categories.category_id, categories.category_name
            FROM categories
            ORDER BY categories.category_name ASC';

    $statement = $db->query($sql);
    $categorySet = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $categorySet;
    
}

$categories = getCategories($db);

// Validate the category
$submittedCategory = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT);

$categoryId = null;

if ($submittedCategory !== false && $submittedCategory !== null){
    $categoryId = $submittedCategory;
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Finder</title>
</head>
<body>
    <h1>Clubs</h1>

    <!-- Form Area -->
    <form action="clubs.php" method="GET">

        <label for="category_id">Choose a Category:</label>
        <select name="category_id" id="category_id">
            // decided to put all categories outside the foreach. I'm sure this is how it was supposed to be.
            <option value="" <?= $categoryId === null ? 'selected': '' ?>>All categories</option>

            <!-- value sends the category's ID when the form is submitted.
                The comparison checks whether this option matches $categoryId. The integer cast makes the types match for the strict comparison.
                The conditional expression outputs selected for a match and an empty string otherwise.
                htmlspecialchars() escapes the category name for display. -->
            <?php foreach($categories as $category): ?>
               <option value="<?= (int) $category['category_id'] ?>"<?= (int) $category['category_id'] === $categoryId ? 'selected' : '' ?>><?= htmlspecialchars($category['category_name']) ?></option>
                <?php endforeach; ?>
        </select>

        <button type='submit'>Filter Clubs</button>
    </form>

    <table>
        <thead>
            <tr>
            <th>Club Name</th>
            <th>Category</th>
            <th>Meeting Time</th>
        </tr>
        </thead>
        <tbody>
            <?php if (count($clubs) > 0): ?>
            <!-- we are looping through every value -->
             <?php foreach ($clubs as $club): ?>
                <tr>
                    <!-- is this where i make each club name link to club.php?id... from Mileston 3 step 4? -->
                    <td><a href="club.php?id=<?= $club->club_id ?>"></a>
                    <?= htmlspecialchars($club->club_name) ?></td>

                    <td><?= htmlspecialchars($club->category_name) ?></td>
                    <td><?= htmlspecialchars($club->meeting_time) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        
    </table>
    
</body>
</html>