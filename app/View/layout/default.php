<!DOCTYPE html>
<!-- banderolle + bien à la une + menu pour acceder aux autres pages?-->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImmoFind</title>
    <!--link rel="stylesheet" href="../css/banderolle.css"-->
    <style type="text/css">
        <?php echo $style_for_content; ?>
    </style>
</head>

<body>
    
    <title> <?php echo isset($title_layout) ? $title_layout : 'ImmoFind'; ?></title>
    <h1> <b>ImmoFind</b> </h1>
    <div id="boutonAcceuil">
        <a href="<?php echo Router::url("accueil/index");?>">Accueil</a> &nbsp;
        <a href="<?php echo Router::url("agence/agence");?>">Notre agence</a> &nbsp;
        <a href="<?php echo Router::url("bien/search");?>">Liste des biens</a> &nbsp;
        <a href="<?php if(!isset($_SESSION['User'])) echo Router::url("user/connexion"); else echo Router::url("user/logout");?>">
            <?php 
                if(!isset($_SESSION['User'])) echo 'Se connecter';
                else echo 'Se déconnecter'
            ?>
        </a> &nbsp;
        <a href="<?php if(isset($_SESSION['User'])) echo Router::url("panier/index/id:{$_SESSION['User']->id}"); else echo "#";?>">
            <?php if(isset($_SESSION['User'])) 
                echo 'Mon panier'
            ?>
        </a> &nbsp;
        <a href="<?php echo Router::url("contact/contact");?>">Nous contacter</a>
    </div>

    <?php echo $this->Session->flash();?>
    <?php echo  $content_for_layout; ?>
    
    <div id="aide">
        
    </div>

</body>

</html>