<?php
require_once './root.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    $ara = trim($_GET['ara']);

    if (!empty($ara)) {
        $stmt = $db->prepare("SELECT * FROM posts WHERE title LIKE :searchTerm LIMIT 10");
        $stmt->execute(['searchTerm' => "%$ara%"]);
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);

        if (count($results) > 0) {
            foreach ($results as $item) { ?>
                <div class="container">
                    <h2><a href="icerik.php?git=<?php echo htmlspecialchars($item->id, ENT_QUOTES, 'UTF-8'); ?>&aranan=<?php echo htmlspecialchars($ara, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?></a></h2>
                    <span><?php echo htmlspecialchars($item->content, ENT_QUOTES, 'UTF-8'); ?></span>
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
