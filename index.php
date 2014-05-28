<?php
    include "php/pages.php";
    session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>

    <title>ZDT-Server Pocket MineCraft</title>

    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/blog-home.css" rel="stylesheet">
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/adb_dtect.js"></script>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

</head>
<div class="afs_ads">&nbsp;</div> <!-- adb dtect -->
<?php 
    if (!isset($_SESSION["login"])) 
        echo "<script>adb_dtect();</script>"; 
?>
<body background="img/bg.jpg">
    <?php _block_navbar(); ?>
    <div class="container">
        <div class="row">
            <div class="well" onClick="window.location.href='index.php';">
                <img src="img/banner.jpg" style="max-width:100%; max-height:100%">
            </div>
            <div class="col-lg-8"> <!-- block gauche -->
                <?php _select_page(); ?>
            </div>
            <div class="col-lg-4"> <!-- block droite -->
                <?php   
                        if (isset($_SESSION["login"]))  _block_admin();
                        if (isset($_GET["p"]) && $_GET["p"] == "p") true; 
                        else _block_server();
                        //_block_search(); 
                        _block_pub();
                
                ?>
            </div>
        </div>
        <?php _block_footer(); ?>
    </div>
</body>
</html>
