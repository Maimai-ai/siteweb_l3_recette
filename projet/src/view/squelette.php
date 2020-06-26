<!DOCTYPE html>
<html>
  <head>
    <link rel = "stylesheet" href="skin/style.css"/>
    <title>Recipe Repository</title>
  </head>
  <body>
    <header>
      <h1> Recipe Repository </h1>
      <nav id="connexion">
        <?php echo $this->connexion; ?>
      </nav>
      <nav id="menu">
        <?php echo $this->menu; ?>
      </nav>
    </header>
    <!--<img src='src/view/img/mlo.jpg' alt='test' />-->
    <div class="article">
      <h2><?php echo $this->title;?></h2>
      <div class="barre de recherche">
        <?php echo $this->recherche; ?>
      </div>
      <p><?php echo $this->content;?></p>
    </div>
    <hr/>
    <footer>
      <p>Projet annuel</p>
      <p>Licence informatique</p>
      <p>Troisième année</p>
      <p>Année de création : 2020</p>
    </footer>
  </body>
</html>
