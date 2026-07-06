<?php
declare(strict_types=1);
require_once __DIR__ . '/root.php';

// Sanitize and validate input parameters
$idInput = $_GET['git'] ?? '';
$id = (int)removeNonNumeric($idInput);
$searchTerm = sanitizeInput($_GET['aranan'] ?? '');

if ($id <= 0) {
    echo '<div class="container"><div style="margin-top:20px;" class="alert alert-danger">Invalid post ID!</div></div>';
    exit;
}

// Prepare and execute query to fetch post details
$query = $db->prepare("SELECT * FROM posts WHERE id = :id");
$query->bindValue(":id", $id, PDO::PARAM_INT);
$query->execute();
$post = $query->fetch(PDO::FETCH_OBJ);

if ($post) {
    $title = (string)$post->title;
    $content = (string)$post->content;
} else {
    echo '<div class="container"><div style="margin-top:20px;" class="alert alert-danger">Post not found!</div></div>';
    exit;
}

// Extract truncated descriptions for SEO
$seoDescription = mb_substr(strip_tags($content), 0, 155);
if (mb_strlen(strip_tags($content)) > 155) {
    $seoDescription .= '...';
}
$seoTitle = mb_substr(strip_tags($title), 0, 100);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="<?php echo htmlspecialchars($seoTitle, ENT_QUOTES, 'UTF-8'); ?>, <?php echo htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="description" content="<?php echo htmlspecialchars($seoDescription, ENT_QUOTES, 'UTF-8'); ?>">
    
    <!-- OpenGraph -->
    <meta property='og:title' content="<?php echo htmlspecialchars($seoTitle, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property='og:type' content="article">
    <meta property='og:url' content="http://localhost/new/detail.php?git=<?php echo htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property='og:description' content="<?php echo htmlspecialchars($seoDescription, ENT_QUOTES, 'UTF-8'); ?>">
    
    <title><?php echo htmlspecialchars($seoTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="https://unpkg.com/papercss@1.8.2/dist/paper.min.css">
</head>
<body>
    <noscript>
        <div class="container">
            <div style="margin-top:20px;" class="alert alert-warning">JavaScript is disabled in your browser. Please enable it and try again!</div>
        </div>
    </noscript>
    <div class="container">
        <div style="margin-top: 20px;">
            <?php if ($searchTerm !== '') { ?>
                <span>Search Term: <b><?php echo htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8'); ?></b></span><br>
                <span>The search term has been highlighted.</span><br>
                <a href="./detail.php?git=<?php echo htmlspecialchars((string)$id, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="button" class="btn-small" value="Remove Highlight" />
                </a>
            <?php } ?>
        </div>
        <h2><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h2>
        <span><?php echo highlightSearchTerms($content, $searchTerm); ?></span>
        
        <div style="margin-top: 40px; margin-bottom: 40px;">
            <h3>Suggestions Based on Your Search</h3>
            <div class="row">
                <?php
                $suggestions = $db->prepare("SELECT * FROM posts WHERE id != :id AND content LIKE :searchTerm ORDER BY id DESC LIMIT 5");
                $searchTermWithWildcards = "%" . $searchTerm . "%";
                $suggestions->bindValue(":id", $id, PDO::PARAM_INT);
                $suggestions->bindValue(":searchTerm", $searchTermWithWildcards, PDO::PARAM_STR);
                $suggestions->execute();
                $suggestionItems = $suggestions->fetchAll(PDO::FETCH_OBJ);

                if (!empty($suggestionItems)) {
                    foreach ($suggestionItems as $item) { ?>
                        <div class="card" style="width: 20rem; margin: 10px;">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars((string)$item->title, ENT_QUOTES, 'UTF-8'); ?></h5>
                                <p>
                                    <?php 
                                    $snippet = mb_substr(strip_tags((string)$item->content), 0, 150);
                                    echo htmlspecialchars($snippet, ENT_QUOTES, 'UTF-8') . "... ";
                                    ?>
                                    <a href="detail.php?git=<?php echo htmlspecialchars((string)$item->id, ENT_QUOTES, 'UTF-8'); ?>&aranan=<?php echo htmlspecialchars(urlencode($searchTerm), ENT_QUOTES, 'UTF-8'); ?>">
                                        Read More...
                                    </a>
                                </p>
                            </div>
                        </div>
                    <?php }
                } else {
                    echo "<p>No matching items found!</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
