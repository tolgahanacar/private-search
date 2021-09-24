<?php
require_once './connect.php';
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
                <label for="Ara">Ara</label><br>
                <input type="text" name="ara" id="ara" placeholder="Aranacak kelime...">
            </div>
            <div>
                <input type="submit" value="Gonder" name="gonder">
            </div>
        </form>
    </div>
</body>

</html>

<?php
if (isset($_GET['gonder'])) {
    $ara = $_GET['ara'];

    $sorgu = $db->prepare("SELECT * FROM posts WHERE title LIKE '%$ara%' LIMIT 10");
    $sorgu->execute();
    $sorgu2 = $sorgu->fetchAll(PDO::FETCH_OBJ);
    $sorgusay = $sorgu->rowCount();
    if ($sorgusay > 0 && !empty($ara)) {
        foreach ($sorgu2 as $item) { ?>
            <div class="container">
                <h2><a href="icerik.php?git=<?php echo $item->id; ?>&aranan=<?php echo $ara; ?>"> <?php echo $item->title; ?> </a></h2>
                <span><?php echo $item->content; ?></span>
            </div>
<?php }
    }elseif(empty($ara) || $ara===""){
        echo "<div class=container>";
        echo '<div class="row flex-spaces">
        <input class="alert-state" id="alert-1" type="checkbox">
        <div class="alert alert-secondary dismissible">
          Lütfen en az 1 harf giriniz.
          <label class="btn-close" for="alert-1">X</label>
        </div>';
        echo "</div>";
    }
    
    else{
        echo '<div class=container>';
        echo '<div class="alert alert-danger">Aramanıza uygun sonuç bulunamadı!</div>';
        echo '</div>';
    }
}

?>