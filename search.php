<?php
declare(strict_types=1);
require_once __DIR__ . '/root.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Posts</title>
    <link rel="stylesheet" href="https://unpkg.com/papercss@1.8.2/dist/paper.min.css">
</head>
<body>
    <div class="container">
        <form action="" method="GET">
            <div>
                <label for="ara">Search</label><br>
                <input type="text" name="ara" id="ara" placeholder="Search keyword..." value="<?php echo htmlspecialchars($_GET['ara'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div>
                <input type="submit" value="Search" name="gonder">
            </div>
        </form>
    </div>
</body>
</html>
<?php
if (isset($_GET['gonder'])) {
    $searchQuery = trim($_GET['ara'] ?? '');

    if ($searchQuery !== '') {
        $stmt = $db->prepare("SELECT * FROM posts WHERE title LIKE :searchTerm LIMIT 10");
        $stmt->execute(['searchTerm' => "%{$searchQuery}%"]);
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);

        if (!empty($results)) {
            foreach ($results as $item) { ?>
                <div class="container">
                    <h2>
                        <a href="detail.php?git=<?php echo htmlspecialchars((string)$item->id, ENT_QUOTES, 'UTF-8'); ?>&aranan=<?php echo htmlspecialchars(urlencode($searchQuery), ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars((string)$item->title, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </h2>
                    <span><?php echo htmlspecialchars(substr((string)$item->content, 0, 200), ENT_QUOTES, 'UTF-8'); ?>...</span>
                </div>
            <?php }
        } else {
            echo '<div class="container">';
            echo '<div class="alert alert-danger">No results found for your search!</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="container">';
        echo '<div class="row flex-spaces">';
        echo '<input class="alert-state" id="alert-1" type="checkbox">';
        echo '<div class="alert alert-secondary dismissible">';
        echo 'Please enter at least one character.';
        echo '<label class="btn-close" for="alert-1">X</label>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
?>
