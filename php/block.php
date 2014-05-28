<?php

function _block_pub(){
   readfile('html/block_pub.html');
}


function _block_search(){
   readfile("html/block_search.html");
}

function _block_pager($p){
    $bdd = bdd();
    $total = _block_article(-1);
    $next = $p + 1;
    $prev = $p - 1;
    
    echo '  <ul class="pager">';
                if ($p > 1) echo '<li class="previous"><a href="index.php?a='. $prev .'">&larr; Newer</a></li>';
                if (($p * 3) < $total) echo '<li class="next"><a href="index.php?a='. $next .'">Older &rarr;</a></li>';
    echo '  </ul></br></br></br>';
}

function _block_contact(){
    echo '
        <div class="well">
            <fieldset>
                <!-- Debut du Formulaire-->
                        <legend>Contact</legend>
                        <!-- Name input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="form_name">Votre nom :</label>
                            <div class="col-md-5">
                                <input id="form_name" name="form_name" type="text" placeholder="(Obligatoire)" class="form-control input-md" required="">
                            </div>
                        </div>
                        <!-- Mail input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="form_email">Votre e-mail :</label>  
                            <div class="col-md-5">
                                <input id="form_email" name="form_email" type="text" placeholder="(Obligatoire)" class="form-control input-md" required="">
                            </div>
                        </div>
                        <!-- Textarea -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="form_message">Votre message :</label>
                            <div class="col-md-4">                     
                                <textarea class="form-control" id="form_message" name="form_message"></textarea>
                            </div>
                        </div>
                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="form_button"></label>
                            <div class="col-md-4">
                                <button id="form_button" name="form_button" class="btn btn-primary">envoyer de votre message</button>
                            </div>
                        </div>
                    </fieldset>
        </div>';
}


function _block_navbar(){
    $bdd = bdd();
    $bname = sql_select($bdd, "*", "admin");
    echo '<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php"><span class="glyphicon glyphicon-home"></span> ZDT-Server</a>
                </div>
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="index.php?p=c" class="btn btn-inverse"><span class="glyphicon glyphicon-envelope"></span> Contact</a></li>
                        <li><a href="index.php?p=p" class="btn btn-inverse"><span class="glyphicon glyphicon-user"></span> Servers</a></li>';
                        if (isset($_SESSION["login"]))
                             echo   '<li><a href="index.php?p=d" class="btn btn-inverse"><span class="glyphicon glyphicon-off"></span> Deconnexion</a></li>';
                        else echo   '<li><a href="index.php?p=l" class="btn btn-inverse"><span class="glyphicon glyphicon-off"></span> Connexion</a></li> ';               
                echo    '</ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>';
}

function _block_article($i = 1, $c = null){
    $bdd = bdd();
    if ($i != -1){
        $post = sql_select($bdd, "*", "article", "WHERE id = '".$i."'");
        $bname = sql_select($bdd, "*", "admin");
    	if ($post[0][6] == -1){
            echo '  <div class="well">
                <div class="post'.$i.'">
                <div class="well"><legend><h2><a href="index.php?p=lp&id='.$i.'&c=-1">'.$post[0][2].'</a></h2></legend>
                    <p><span class="glyphicon glyphicon-time"></span> Posted on '.$post[0][1].' by <a href="index.php?p=c">'.$bname[0][1].'</a></p>';
                    if(isset($_SESSION["login"])){
                        echo "|<a href='index.php?dp=". $i ."'> Delete </a>||<a href='index.php?p=ep&id=". $i ."'> Edit </a>|";
                    }
                    echo '</div><div class="well"><p>'.$post[0][3].'</p></div><hr>
                    <a class="btn btn-primary" href="index.php?'.(isset($_GET["a"]) ? "a=".$_GET["a"]."&c=".$c : "c=".$c).'">Comm <span class="glyphicon glyphicon-chevron-right"></span></a>';
                    if (isset($_GET["c"]) && $_GET["c"] == $c)
                        _block_com($i);

                echo '</div></div>';
        }else return false;
    }else{
        $total = sql_select($bdd, "count(*)", "article", "WHERE hide = -1"); 
        return $total[0][0];
    }
}

function _block_topcat(){
    echo '<div class="well">
                    <h4>Popular Blog Categories</h4>
                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="list-unstyled">
                                <li><a href="#dinosaurs">Dinosaurs</a>
                                </li>
                                <li><a href="#spaceships">Spaceships</a>
                                </li>
                                <li><a href="#fried-foods">Fried Foods</a>
                                </li>
                                <li><a href="#wild-animals">Wild Animals</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <ul class="list-unstyled">
                                <li><a href="#alien-abductions">Alien Abductions</a>
                                </li>
                                <li><a href="#business-casual">Business Casual</a>
                                </li>
                                <li><a href="#robots">Robots</a>
                                </li>
                                <li><a href="#fireworks">Fireworks</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>';
}

function _block_admin(){
        $bdd = bdd();
        $count = sql_select($bdd, "count(*)", "comment", "WHERE hide = -1 and valid = 0"); 
        echo '<div class="well">
            <div class="sidebar-nav">
                <div  style="width: 200px; padding: 8px 0;">
                    <li class="nav-header"><span class"glyphicon glyphicon-th-list"></span> Tableau de bord</li>        
                    <li><span class="glyphicon glyphicon-edit"></span><a href="index.php?p=ap"> Nouvel Article</a></li>
                    <li><span class="glyphicon glyphicon-comment"></span><a href="#"> Commentaires ('.$count[0][0].')</a></li>
                </div>
            </div>
        </div>';
}

function _block_footer(){
   readfile("html/block_footer.html");
}

function _block_com($i){
    $bdd = bdd();
    $total = sql_select($bdd, "count(*)", "comment", "WHERE id_article = '".$i."' and hide = -1");
    if (isset($_POST["text"])){
        sql_insert_com($bdd, $i);
        header("Location: ".$_SERVER['REQUEST_URI']);
    }

    echo ' 

        <!-- mcpe article com -->
        <center><ins class="adsbygoogle"
             style="display:inline-block;width:468px;height:60px"
             data-ad-client="ca-pub-3034481264150902"
             data-ad-slot="1536047462"></ins>
        <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        </script></center>

    </br></br><div class="well"><center>';
            if ($total[0][0] != 0){
                $com = sql_select($bdd, "*", "comment", "WHERE id_article = '".$i."' and hide = -1");
                for ($u = 0; $u != $total[0][0]; $u++)
                    if (($com[$u][6] == 1 || isset($_SESSION["login"])) ){//&& $com[$u][5] != 0){
                        echo '<li> Comment by '.htmlentities($com[$u][2]).'</br>';
                        if (isset($_SESSION["login"]) && $com[$u][6] == 0) echo '<a href="'.$_SERVER['REQUEST_URI'].'&pv='.$com[$u][0].'">|Valid|</a>';
                        if (isset($_SESSION["login"])) echo '<a href="'.$_SERVER['REQUEST_URI'].'&ph='.$com[$u][0].'">|Delete|</a>';
                        echo '</br><textarea style="background-color: transparent;resize: none" rows="6" cols="70" disabled>'.htmlentities($com[$u][3]).'</textarea></li><hr>';
                    } 
            }

        echo ' 
                    </center><h4>Leave a Comment:</h4>
                    <form method="POST" action="'.$_SERVER['REQUEST_URI'].'"> <!-- .$_SERVER["REQUEST_URI"].-->
                        <div class="form-group">
                        <label>Pseudo:</label> <input name="pseudo" rows="50" id="pseudo" required />
                            <textarea name="text" class="form-control" rows="3" requireds></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button></br>
                         
                         <!-- mcpe article com -->
                        <center><ins class="adsbygoogle"
                             style="display:inline-block;width:468px;height:60px"
                             data-ad-client="ca-pub-3034481264150902"
                             data-ad-slot="1536047462"></ins>
                        <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                        </script></center>
                    </form>
                </div>';
}
