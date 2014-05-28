<?php
include "sql_req.php";
include "block.php";




function _search(){
    if (isset($_POST["search"]) || isset($_GET["s"])){
        (isset($_POST["search"]) ? $search = $_POST["search"] : $search = $_GET["s"]);
        $bdd = bdd();
        $count = sql_select($bdd, "count(*)", "article", "WHERE titre = '".$search."'");
        $post = sql_select($bdd, "*", "article", "WHERE titre = '".$search."'");
        if ($count[0][0]){
            for ($i = 0; $i != $count[0][0]; $i++){
                echo '  <div class="post'.$post[$i][0].'"><hr>
                        <h1><a href="#">'.$post[$i][2].'</a></h1>
                        <p><span class="glyphicon glyphicon-time"></span> Posted on '.$post[$i][1].' by <a href="index.php?p=contact">Start Bootstrap</a></p><hr>
                        <p>'.$post[$i][3].'</p><hr>
                        <a class="btn btn-primary" href="index.php?'.(isset($_POST["search"]) ? "p=s&s=".$search."&c=".$post[$i][0] : (isset($_GET["s"]) ? "p=s&s=".$search."&c=".$post[$i][0] : "")).'">Comm <span class="glyphicon glyphicon-chevron-right"></span></a>';
                        if (isset($_GET["c"]) && $_GET["c"] == $post[$i][0])
                                _block_com($post[$i][0]);   
                echo    '</div><hr></br></br>';
            }
        }else echo " not found";
   }
}



function _look_post($i, $c = -1){
    $bdd = bdd();
    $post = sql_select($bdd, "*", "article", "WHERE id = '".$i."'");
    $bname = sql_select($bdd, "*", "admin");
    if ($post[0][6] == -1){
        echo '  <div class="well">
                <div class="post'.$i.'">
                <div class="well"><legend><h1><a href="#">'.$post[0][2].'</a></h1></legend>
                <p><span class="glyphicon glyphicon-time"></span> Posted on '.$post[0][1].' by <a href="index.php?p=c">'.$bname[0][1].'</a></p>';
                if(isset($_SESSION["login"])){
                    echo "|<a href='index.php?dp=". $i ."'> Delete </a>||<a href='index.php?p=ep&id=". $i ."'> Edit </a>|";
                }
                echo '</div><div class="well"><p>'.$post[0][3].'</p></div><hr>';
                    _block_com($i);

            echo '</div></div>';
    }else return false;
}

function _login(){
   readfile("html/page_login.html");
}

function _add_post(){
    if (isset($_SESSION["login"])){
       readfile("html/page_addpost.html");
    }
}

function _edit_post(){
    $bdd = bdd();
    $post =  sql_select($bdd, "*", "article", "WHERE id = " . $_GET["id"]);
    if (isset($_SESSION["login"])){
        echo '<div class="well">
                <legend>Edit Post</legend>
                    <form method="POST" action="index.php?p=ep&id='.$_GET["id"].'&v">
                        <label class="col-md-4 control-label" for="form_name">Titre :</label>
                        <input  name="title" type="text" placeholder="(Obligatoire)" class="form-control input-md" required="" value="'.$post[0][2].'"></br>
                        <label class="col-md-4 control-label" for="form_message">Votre message :</label>
                        <textarea class="form-control" id="form_message" name="posttext">'.$post[0][3].'</textarea></br>
                        <button id="form_button" name="form_button" class="btn btn-primary">Valid</button>         
                    </form>
           </div>';
    }
}

function _select_page(){
    $bdd = bdd();

    if (isset($_SESSION["login"]) && isset($_GET["dp"])) sql_update($bdd, "article", "hide = 1", "id = ". $_GET["dp"]);
    if (isset($_SESSION["login"]) && isset($_GET["pv"])) sql_update($bdd, "comment", "valid = 1", "id = ". $_GET["pv"]);
    if (isset($_SESSION["login"]) && isset($_GET["ph"])) sql_update($bdd, "comment", "hide = 1", "id = ". $_GET["ph"]);

    if (isset($_GET["p"]) && $_GET["p"] == "s"){
        (isset($_GET["p"]) ? (isset($_POST["search"]) ? _search($_POST["search"]) : _search($_GET["s"])) : "");
    }else if (isset($_GET["p"]) && $_GET["p"] == "c"){
        _block_contact();
    }else if (isset($_GET["p"]) && $_GET["p"] == "l"){
         _login();
    }else if (isset($_GET["p"]) && $_GET["p"] == "p"){
        _profil();
    }else if (isset($_GET["p"]) && $_GET["p"] == "ap"){
        if (isset($_POST["title"]) && isset($_POST["posttext"]) && isset($_SESSION["login"]))
            sql_insert_post($bdd);
        _add_post();
    }else if (isset($_GET["p"]) && $_GET["p"] == "d"){
        $_SESSION = array();
        session_destroy();
        header("location: index.php");
    }else if (isset($_GET["p"]) && $_GET["p"] == "ep" && isset($_GET["id"])){
        if (isset($_GET["v"])) {
            if (isset($_POST["title"]) && isset($_POST["posttext"]) && isset($_SESSION["login"])){
                sql_update($bdd, "article", "titre = '". $_POST["title"] ."'", "id = ". $_GET["id"]);
                sql_update($bdd, "article", "contenu = '".$_POST["posttext"]."'", "id = ". $_GET["id"]);
            }
            header("location: index.php");
        }else _edit_post();
    }else if (isset($_GET["p"]) && $_GET["p"] == "lp" && isset($_GET["id"])){
        _look_post($_GET["id"]);
    }else{

        $total = _block_article(-1);
        $post =  sql_select($bdd, "*", "article", "WHERE hide = -1");
        

        if (isset($_GET["a"]) && $_GET["a"] != "" && $_GET["a"] >= 2){
            $i = $total - ($_GET["a"] * 3) +3;
            $lastp = ($_GET["a"] * 3) + 1;
        }else if (isset($_GET["a"]) && $_GET["a"] < 2){
            header("Location: index.php");
        }else{
            $i = 0;
            $lastp = 3;
        }

        for ($i,$u = $i-1,  $c = 0; $i != $lastp && $i < $total + 2; $i++, $total--, $c++, $u--){
            $article = (isset($_GET["a"]) ? $total - ($_GET["a"] * 3) + 2 : $total - 1);
            _block_article((isset($_GET["a"]) ? $post[$u][0]: $post[$total - 1][0]), $u);
        }
        _block_pager((isset($_GET["a"]) && $_GET["a"] != null ? $_GET["a"] : 1));
    }
}

function _profil(){
    //$bdd = bdd();
    //$bname = sql_select($bdd, "*", "admin");

    echo '   <div class="well">';
    //readfile("php/view.php");
    echo    '</div>';
}
