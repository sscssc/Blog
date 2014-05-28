<?php 

function bdd(){
    $login = "root";
    $serveur = "localhost";
    $psw = "";
    $bdd = "";
    try{
        $bdd = new PDO('mysql:host='.$serveur.';dbname='.$bdd.'', $login, $psw);
    }catch (Exception $e){
        die('Erreur : ' .$e->getMessage());
    }
    return ($bdd);
}

function sql_insert_com($bdd, $id){
    $request = $bdd->prepare("INSERT INTO comment VALUES(NULL, '".$id."', '".$_POST["pseudo"]."', '".$_POST["text"]."', 'none', '-1', '0')");
    $request->execute();
}

function sql_insert_post($bdd){
    $info = getdate();
    $request = $bdd->prepare("INSERT INTO article VALUES(NULL, '".$info['mday']."/".$info['mon']."/".$info['year']." - ".$info['hours'].":".$info['minutes']."', '".$_POST["title"]."', '".$_POST["posttext"]."', 'none', 'none', '-1')");
    $request->execute();
    header("location: index.php");
}

function sql_update($bdd, $colomn, $table_set, $precision){
    $request = $bdd->prepare("UPDATE ".$colomn." SET ".$table_set." WHERE ".$precision);
    $request->execute();
}

function sql_select($bdd, $column, $table, $precision = ""){
    $request = "SELECT ".$column." FROM ".$table."  ".$precision;
    $responce = $bdd->query($request);

    for ($i = 0; $result = $responce->fetch() ; $i++) {
        if (isset($result[5])){
            for ($u = 0; $result[$u]; $u++)
                $tab[$i][$u] = $result[$u];
        }else
            $tab[$i] = $result;
    }
    return $tab;
}
