<?php
require_once './root.php';

// Sanitize and validate input parameters
$id = Security(DeleteNumbers($_GET['git']));
$searchTerm = Security($_GET['aranan']);

// Prepare and execute query to fetch post details
$query = $db->prepare("SELECT * FROM posts WHERE id = :id");
$query->bindParam(":id", $id, PDO::PARAM_INT);
$query->execute();
$post = $query->fetch(PDO::FETCH_OBJ);

if ($post) {
    $title = $post->title;
    $content = $post->content;
} else {
    echo "Post not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="<?php echo htmlspecialchars(substr($title, 0, 100)); ?>, <?php echo htmlspecialchars($searchTerm); ?>">
    <meta name="description" content="<?php echo htmlspecialchars($content); ?>">
    <!-- OpenGraph -->
    <meta property='og:title' content="<?php echo htmlspecialchars(substr($title, 0, 100)); ?>">
    <meta property='og:type' content="article">
    <meta property='og:url' content="http://localhost/new/icerik.php?git=<?php echo htmlspecialchars($id); ?>">
    <meta property='og:description' content="<?php echo htmlspecialchars($content); ?>">
    <title><?php echo htmlspecialchars(substr($title, 0, 100)); ?></title>
    <link rel="stylesheet" href="https://unpkg.com/papercss@1.8.2/dist/paper.min.css">
</head>

<body>
    <noscript>
        JavaScript is disabled in your browser. Please enable it and try again!
    </noscript>
    <div class="container">
        <div>
            <?php if (!empty($searchTerm)) { ?>
                <span>Search Term: <b><?php echo htmlspecialchars($searchTerm); ?></b></span><br>
                <span>The search term has been highlighted.</span><br>
                <a href="./icerik.php?git=<?php echo htmlspecialchars($id); ?>"><input type="button" class="btn-small" value="Remove Highlight" /></a>
            <?php } ?>
        </div>
        <h2><?php echo htmlspecialchars($title); ?></h2>
        <span><?php echo highlightSearchTerms($content, $searchTerm); ?></span>
        <div>
            <h3>Suggestions Based on Your Search</h3>
            <?php
            $suggestions = $db->prepare("SELECT * FROM posts WHERE id != :id AND content LIKE :searchTerm ORDER BY id DESC LIMIT 5");
            $searchTermWithWildcards = "%$searchTerm%";
            $suggestions->bindParam(":id", $id, PDO::PARAM_INT);
            $suggestions->bindParam(":searchTerm", $searchTermWithWildcards, PDO::PARAM_STR);
            $suggestions->execute();
            $suggestionsCount = $suggestions->rowCount();
            $suggestionItems = $suggestions->fetchAll(PDO::FETCH_OBJ);

            if ($suggestionsCount > 0) {
                foreach ($suggestionItems as $item) { ?>
                    <div class="card" style="width: 20rem;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($item->title); ?></h5>
                            <p><?php echo htmlspecialchars(substr($item->content, 0, 150)) . " <a href='icerik.php?git=" . htmlspecialchars($item->id) . "&aranan=" . urlencode($searchTerm) . "'>Read More...</a>"; ?></p>
                        </div>
                    </div>
                <?php }
            } else {
                echo "No matching items found!";
            }
            ?>
        </div>
    </div>
</body>

</html>
