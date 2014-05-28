<?php
include "sql_req.php";
error_reporting (E_ALL);

$bdd = bdd();

if (isset($_POST['login']) && isset($_POST['mdp']) && preg_match('#[^a-zA-Z0-9_]#', $_POST['login']) + preg_match('#[^a-zA-Z0-9_]#', $_POST['mdp']) == 0)
  {
    $login = htmlentities($_POST['login']);
    $mdp = htmlentities($_POST['mdp']);

    $sqllogin = $bdd->prepare('SELECT login FROM admin WHERE login = \''.$login.'\';');
    $sqllogin->execute(array('.$login.' => $_POST['login']));
    $lres = $sqllogin->fetch();

    $sqlmdp = $bdd->prepare('SELECT mdp FROM admin WHERE mdp = \''.md5($mdp).'\';');
    $sqlmdp->execute(array('.$mdp.' => $_POST['mdp']));
    $mdpres = $sqlmdp->fetch();

    if ($lres && $mdpres)
      {
        session_start();    
        $_SESSION['login'] = $login;

        $lid = 'SELECT id FROM admin WHERE login = \''.$_SESSION['login'].'\' AND mdp = \''.$_SESSION['mdp'].'\'';
        
        $idres = $bdd->query($lid);
        $iddon = $idres->fetch();
        
        $_SESSION['id']=$iddon['id'];

        header ('Location: ../index.php');
      }
    else 
    {   
      header ('Location: ../index.php');
    }
  }