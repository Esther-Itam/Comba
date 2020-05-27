<?php
  
  // On enregistre notre autoload.
  /* function chargerClasse($classname) {
    require $classname.'.php';
  }

  spl_autoload_register('chargerClasse');*/
// On enregistre notre autoload.
  
  // On fait appel à la classe Personnage
  require 'class/Personnage.php';
  // On fait appel à la classe PersonnagesManager
  require 'class/PersonnagesManager.php';

  session_start(); // On appelle session_start() 

  if (isset($_GET['deconnexion'])) {
    session_destroy();
    header('Location: .');
    exit();
  }

  // On fait appel à la connexion à la bdd
  require 'config/init.php';

  // On fait appel à le code métier
  require 'combat.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <title>🥋Vs🥋 Fight ! </title>
    
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <p>Nombre de personnages créés : <?= $manager->count() ?></p>
<?php
  // On a un message à afficher ?
  if (isset($message)) {
    echo '<b>', $message, '</b>'; // Si oui, on l'affiche.
  }
  // Si on utilise un personnage (nouveau ou pas).
  if (isset($perso)) {
?>
    <p><a href="?deconnexion=1">Déconnexion</a></p>
    
    <fieldset>
      <legend>Mes informations</legend>
      <div class="card" style="width: 13rem;">
        <div class="card-body">
         <p class="card-text">
        Nom : <?= htmlspecialchars($perso->nom()) ?><br />
        Classe : <?= $perso->classe() ?><br />      
        Dégâts : <?= $perso->degats() ?><br />
        Level : <?= $perso->level() ?><br />
        Force : <?= $perso->strength() ?><br />

          </p>
        </div>
      </div>
    </fieldset>
    
    
   
      <legend>Qui frapper ?</legend>
      
     
        <?php
          $persos = $manager->getList($perso->nom());
          if (empty($persos)) {
            echo 'Personne à frapper !';
          } 
          else {


           foreach ($persos as $unPerso)
            {?>
            
                <div class="card" style="width: 13rem;">
                 <div class="card-body">
                  <p class="card-text" class="carte">
          <?php    echo '<a href="?frapper=', $unPerso->id(), '">',
                         htmlspecialchars($unPerso->nom()),
              '</a><br />Classe : ', $unPerso->classe(),
                            '<br />dégâts : ', $unPerso->degats(),
                            '<br />level : ',
                            htmlspecialchars($unPerso->level()),
                            '<br />force : ',
                            htmlspecialchars($unPerso->strength()), '<br />';?>
                  </p>
                </div>
               </div>
             
      <?php  }
          }
        ?>
      
 
<?php
}
// Sinon on affiche le formulaire de création de personnage
else {
?>
  <form action="" method="post">
    <p>
      Nom : <input type="text" name="nom" maxlength="50" />
      <input type="submit" value="Utiliser ce personnage" name="utiliser" /><br/>
      <label for="classe-select">Classe personnage:</label>
        <select name="classe" id="classe-select">
        <option valeur="Guerrier">Guerrier</option>
        <option valeur="Magicien">Magicien</option>
        <option valeur="Archer">Archer</option>
        </select>
      <input type="submit" value="Créer ce personnage" name="creer" />
      
    </p>
  </form>

<?php } ?>

  </body>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</html>
<?php
  // Si on a créé un personnage, on le stocke dans une variable session afin d'économiser une requête SQL.
  if (isset($perso)) {
    $_SESSION['perso'] = $perso;
  }