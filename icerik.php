<?php
require_once './connect.php';
require_once './functions.php';
$git = Security(DeleteNumbers($_GET['git']));
$aranan = Security($_GET['aranan']);


$sorgu = $db->prepare("SELECT * FROM posts WHERE id = :id");
$sorgu->bindParam(":id", $git, PDO::PARAM_INT);
$sorgu->execute();
$sorgu2 = $sorgu->fetchAll(PDO::FETCH_OBJ);
foreach ($sorgu2 as $item) {
    $title = $item->title;
    $content = $item->content;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="<?php echo substr($title, 0, 100); ?>, <?php echo $aranan; ?>">
    <meta name="description" content="<?php echo $content; ?>">
    <!-- OpenGraph-->
    <meta name='og:title' content="<?php echo substr($title, 0, 100); ?>">
    <meta name='og:type' content="news">
    <meta name='og:url' content="http://localhost/new/icerik.php?git=<?php echo $git; ?>">
    <meta name='og:description' content="<?php echo $content; ?>">
    <title><?php echo substr($title, 0, 100); ?></title>
    <link rel="stylesheet" href="https://unpkg.com/papercss@1.8.2/dist/paper.min.css">
</head>

<body>
    <noscript>
        Tarayıcınızda javascript kullanmaya izin verilmiyor. Lütfen kontrol edip tekrar deneyiniz!
    </noscript>
    <div class="container">
        <div>
            <?php if (!empty($aranan) || $aranan === "") { ?>
                <span>Aranan Kelime: <?php echo "<b>" . $aranan . "</b>"; ?></span><br>
                <span>Aranan sözcük vurgulanmıştır.</span><br>
                <a href="./icerik.php?git=<?php echo $git; ?>"><input type="button" class="btn-small" value="Vurgulamayı Kaldır" /></a>
            <?php } ?>
        </div>
        <h2><?php echo $title; ?></h2>
        <span><?php echo Renklendir($content, $aranan); ?></span>
        <div>
            <h3>Aramanıza Öneriler</h3>
            <?php
            $oneria = $db->prepare("SELECT * FROM posts WHERE id!=:id AND content LIKE '%$aranan%' ORDER BY id DESC LIMIT 5");
            $oneria->bindParam(":id", $git, PDO::PARAM_INT);
            $oneria->execute();
            $oneriasay = $oneria->rowCount();
            $onerib = $oneria->fetchAll(PDO::FETCH_OBJ);
            if ($oneriasay > 0 && !empty($aranan)) {
                foreach ($onerib as $item2) { ?>
                    <div class="card" style="width: 20rem;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $item2->title; ?></h5>
                            <p><?php echo substr($item2->content, 0, 150,) . " " . "<a href='icerik.php?git=$item2->id'>Devamını Oku...</a>"; ?></p>
                        </div>
                    </div>
            <?php }
            } else {
                echo "Aramanıza uygun öğe bulunamadı!";
            }
            ?>
        </div>
    </div>
</body>

</html>