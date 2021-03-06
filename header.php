<?php
$config = parse_ini_file(__DIR__ ."/class/config.ini");

$base  = $config['base']; ?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Projet de galerie web IUT A - Bourg en Bresse">
        <base href="<?php echo $base; ?>"/>
        <title><?php echo $titre; ?></title>

        <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/design.css" rel="stylesheet">
        <script src="js/functions_backoffice.js"></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lte IE 8]>
          <script src="<?php echo $base; ?>js/html5shiv.js"></script>
          <script src="<?php echo $base; ?>js/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript">
            $(document).ready(function() {
                $("#connect").submit(function(event) {
                    event.preventDefault();
                    var data = "username=" + $("#usernameConnect").val() + "&password=" + $("#passwordConnect").val();
                    $.ajax({
                        type: "POST",
                        url: "ajax/users/connect.php",
                        data: data,
                        success: function(result) {
                            if(result === "connect")
                            {
                                $("#connection").html("<p class='navbar-text'>Vous êtes connecté en tant que "+ $("#usernameConnect").val() + " .</p>");
                                location.reload();
                            }
                            else
                                alert("Connexion impossible, veuillez vérifier vos informations");
                        },
                        error: function(xhr, type, thrownError) {

                            alert("Une erreur inattendue s'est produite, merci de réesayer.");

                        }

                    });
                });
                
                $("#disconnect").click(function(event) {
                    event.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: "ajax/users/disconnect.php",
                        success: function(result) {
                            $("#connection").html("<p class='navbar-text'>Vous êtes maintenant déconnecté.</p>");
                            
                        },
                        error: function(xhr, type, thrownError) {

                            alert("Une erreur inattendue s'est produite, merci de réesayer.");

                        }

                    });
                });
            });
        </script>
    </head>

    <body>

        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Afficher la navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Administration de la galerie</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="category/">Catégories/images</a></li>
                        <li><a href="utilisateurs/">Utilisateurs</a></li>
                    </ul>
                    <div id="connection" class="navbar-right">
                        <?php
                        if(isset($_SESSION["connected"]))
                        {
                            echo "<p class='navbar-text'>Vous êtes connecté en tant que ".$_SESSION['name_user']. 
                                    " . <a href='utilisateurs/disconnect.php' id='disconnect'>Deconnexion</a></p>";
                            ?>
                        
                        <?php
                        }
                        else {
                            ?>
                        <form class="navbar-form navbar-right" role="form" id="connect">
                            <div class="form-group">
                                <input type="text" name="usernameConnect" id="usernameConnect" class="form-control" placeholder="Nom d'utilisateur">
                            </div>
                            <div class="form-group">

                                <input type="password" name="passwordConnect" id="passwordConnect" class="form-control" placeholder="Mot de passe">
                            </div>
                            <button type="submit" class="btn btn-default" id="buttonConnect" data-loading-text="Connexion en cours ...">Connexion</button>>
                        </form>
                        <?php
                        }
                        ?>
                    </div>
                </div><!--/.navbar-collapse -->
            </div>
        </div>