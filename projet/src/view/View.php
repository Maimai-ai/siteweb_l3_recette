<?php

set_include_path("./src");
require_once("Router.php");
require_once("model/Recipe.php");
require_once("model/authentification/AuthentificationManager.php");

class View{
  private $title;
  private $content;
  private $router;
  private $auth;
  private $recherche;

  function __construct(Router $router, AuthentificationManager $auth) {
    $this->title="";
    /*$this->recherche = $recherche = '<form action ='+$this->router->getRecherchePageUrl()+'method="get">
			  <input type="search" name="recette">
			  <input type="submit" name="envoi" value="recherche">
		  </form>';*/
		  /*$this->recherche = '<form action ="" method="post">
			  <input type="search" name="recette">
			  <input type="submit" name="envoi" value="recherche">
		  </form>';*/
	$this->recherche = '<form action ="" method="get">
			  <input type="search" name="recette">
			  <input type="submit" name="envoi" value="recherche">
		  </form>';
    $this->content="";
    $this->router=$router;
    $this->auth=$auth;

    $this->menu="<a href='".$this->router->getHomeURL()."'> ACCUEIL </a>";
    $this->menu.="<a href='".$this->router->getHomeURL()."?liste'> LISTE DES RECETTES </a>";
    if($this->auth->isUserConnected()){
      $this->menu.="<a href='".$this->router->getRecipeCreationURL()."'> AJOUT DE RECETTE </a>";
    }
    if(key_exists('user',$_SESSION)){
      $this->connexion="<a href='".$this->router->getAccountURL()."'>  DECONNEXION </a>";
    }
    else{
      $this->connexion="<a href='".$this->router->getAccountURL()."'>  CONNEXION </a> <br> <a href='".$this->router->getAccountRegisterURL()."'>INSCRIPTION</a>";
    }
  }

  function render(){
    include("squelette.php");
  }

  public function makeHomePage() {
		$this->title = "Accueil";
		$this->content = "Bonjour ceci est l'accueil de notre site";
	}

  function subArraysToString($ar, $sep = ',') {
    $str = '';
    foreach ($ar as $val) {
        $str .= implode($sep, $val);
        $str .= $sep; // add separator between sub-arrays
    }
    $str = rtrim($str, $sep); // remove last separator
    return $str;
}

  //Affichage d'une page de recette
  function makeRecipePage(Recipe $recipe){
    /*$tab=explode(',',$recipe->getAvis());
    var_dump($tab);
    $tab[]='5';
    var_dump($tab);
    $tab2="".$tab[0];
    foreach ($tab as $key => $value) {
        if($value!==$tab[0]){
          $tab2.=",".$value;
        }
    }*/

    $this->title=$recipe->getTitle();
    if(empty($recipe->getImg())){
      $this->content="<img src='unknow.jpg' alt='pas d'image' width='400' />";
    }
    else{
      $this->content="<img src='".$recipe->getImg()."' alt='".$recipe->getTitle()."' width='400' />";
    }
    $this->content.="<div id=description>";
    if($this->auth->isUserConnected()){
      $this->content.="<form action=".$this->router->getRecipeURL($_GET['id'])." method='POST'><select name='".AVIS_REF."' size='1'><option>null <option>0 <option>1 <option>2 <option>3 <option>4 <option>5 </select><input type='submit' value='Envoyer'/></form>";
    }
    if(is_null($recipe->getAvis())){
      $this->content.="<br><span class='avis'>Note: Pas d'avis</span>";
    }
    else{
      $this->content.="<br><span class='avis'>Note:</span>".$recipe->getNote()."/5";
    }
    $this->content.= "<br><span class='titre'>Titre : </span>".$recipe->getTitle();
    $this->content.= "<br><span class='time'>Temps de préparation : </span>".$recipe->getTime()." minutes";
    //Listing des ingrédients
    $this->content.= "<br><span class='ing'>Ingredients : <br>";
    $tabIngr = explode(",",$recipe->getIngredients());
    foreach ($tabIngr as $key => $value) {
       $this->content.="- ".$value."<br>";
    }
    $this->content.="</span>";
    //Listing des préparations
    $this->content.= "<br><span class='prep'>Preparation :</span> <br>";
    $tabPrep = explode(",",$recipe->getPreparation());
    foreach ($tabPrep as $key => $value) {
      $nb = $key+1;
      $this->content.=$nb.". ".$value."<br>";
    }
    $this->content.="</span>";

    $this->content.="<br><span class='author'> Ajouté par : </span>".$recipe->getAuthor()." le ".$recipe->getDateTime();
    $this->content.="<br><span class='tag'> TAG: ".$recipe->getTag()."</span>";
    // foreach ($recipe->getTag() as $key => $value) {
    //   $this->content.="<span class='tag'>".$value."</span>";
    // }
    $this->content.="</div>";
    // AFFICHAGE DU BOUTONS DE SUPPRESSION
    if($this->auth->isAdminConnected()){
      if(key_exists('id',$_GET)){
        $id = $_GET['id'];
        $this->content.="<form action=".$this->router->getRecipeAskDeletionURL($id)." method='POST'>";
        $this->content.="<input type='submit' value='Supprimer'/></form>";
      }
    }
  }

  //Affichage d'une page qui indique une erreur
  function makeUnknowRecipePage(){
    $this->title="Erreur";
    $this->content="La page demandée est inconnu";
  }

  //Affichage d'une liste de recette
  function makeListPage($tableau){
    $this->title="Liste de Recette";
    $menuDeroulant="<form action=".$this->router->getListURL()." method='POST'><select name='".TAG_REF."' size='1'><option>--Recherche tag--<option>plat <option>dessert <option>français <option>anglais <option>italien <option>espagnol <option>chinois <option>japonais <option>vegan <option>végétarien <option>soupe </select><input type='submit' value='Envoyer'/></form>";

    $this->content.="<p><label>Recherche des recettes</label> : $menuDeroulant </p>";
    $this->content.="<ul class='recette' >";
    foreach($tableau as $tab => $key){
      $this->content .="<li><a href='".$this->router->getRecipeURL($tab)."'>".$key->getTitle()."<br><img src='".$key->getImg()."' alt='".$key->getTitle()."' /></a></li>";
    }
    $this->content .="</ul>";
  }

  public function makeDebugPage($variable) {
  	$this->title = 'Erreur';
  	$this->content = '<pre>'.htmlspecialchars(var_export($variable, true)).'</pre>';
  }

  public function makeRecipeCreationPage(RecipeBuilder $recipeBuilder){
    $this->content="<form action=".$this->router->getRecipeSaveURL()." method='POST'>";
    $this->content.="<p><label>Titre </label>  : <input type='text' name='".TITLE_REF."' value='".self::htmlesc($recipeBuilder->getData(TITLE_REF))."' /> </p>";
    $this->content.="<p><label>Temps de la recette</label> : <input type='number' name='".TIME_REF."' value='".self::htmlesc($recipeBuilder->getData(TIME_REF))."'/></p>";
    $this->content.="<p><label>Ingredients</label> : <textarea name='".INGREDIENTS_REF."' placeholder='Ajoutez quelques ingrédients...'>".self::htmlesc($recipeBuilder->getData(INGREDIENTS_REF))."</textarea> </p>";
    $this->content.="<p><label>Préparation</label> : <textarea name='".PREPARATION_REF."' placeholder='Comment préparez...'>".self::htmlesc($recipeBuilder->getData(PREPARATION_REF))."</textarea> </p>";
    $this->content.="<p><label>Entrez l'URL de votre image</label> : <input type='url' name='".IMAGE_REF."'  id='url' placeholder='https://example.com' pattern='https://.*' size='30'> </p>";

    //LISTE déroulante pour ajout de tag
    // $this->content.="<p><label>Tag</label> : <input type='text' name='".TAG_REF."' value='".self::htmlesc($recipeBuilder->getData(TAG_REF))."' /></p>";

    $menuDeroulant="<select name='".TAG_REF."' size='1'><option>plat <option>dessert <option>français <option>anglais <option>italien <option>espagnol <option>chinois <option>japonais <option>vegan <option>végétarien <option>soupe </select>";

    $this->content.="<p><label>Tag</label> : $menuDeroulant </p>";
    $this->content.="<input type='submit' value='Envoyer'/></form>";
//value='".self::htmlesc($recipeBuilder->getData(IMAGE_REF))."'
    //Permet d'afficher les erreurs
    if ($recipeBuilder->getError() !== ""){
      $this->content.= ' <span class="error">'.$recipeBuilder->getError().'</span>';
    }
  }

  public function makeRecipeDelectionPage($id){
    $this->content.="<form action=".$this->router->getRecipeDeletionURL($id)." method='POST'>";
    $this->content.="<input type='submit' name='confirme' value='Confirmez !'> <input type='submit' value='Annuler'></form>";
  }

  public function makeLoginFormPage($error){
    if(key_exists('user',$_SESSION)){
      $this->title="Deconnexion";
      $this->content="<p> Vous vous êtes bien connecté : ".$this->auth->getUserName()."</p> <br>";
      $this->content.="<form action=".$this->router->getAccountURL()." method='POST'>";
      $this->content.="<input type='submit' name='deco' value='Deconnexion'/></form>";
    }
    else{
      $this->title="Connexion";
      $this->content="<form action=".$this->router->getAccountURL()." method='POST'>";
      $this->content.="<p><label>Login</label> : <input type='text' name='login' /> ";
      $this->content.="<label>Mot de passe</label> : <input type='password' name='mdp' /> ";
      $this->content.="</p><input type='submit' value='Connexion'/></form> <br>";
    }
    $this->content.= ' <span class="error">'.$error.'</span>';
  }

  public function makeLoginRegisterPage($info){
    $this->title="Inscription";
    $this->content= '<span class="error">'.$info.'</span>';
    $this->content.="<form action=".$this->router->getAccountRegisterSaveURL()." method='POST'>";
    $this->content.="<p><label>Prenom</label> : <input type='text' name='name' /> ";
    $this->content.="<label>Login</label> : <input type='text' name='login' /> ";
    $this->content.="<label>Mot de passe</label> : <input type='password' name='mdp' /> ";
    $this->content.="</p><input type='submit' value='Inscription'/></form>";
}

    /* Une fonction pour échapper les caractères spéciaux de HTML,
  * car celle de PHP nécessite trop d'options. */
  public static function htmlesc($str) {
    return htmlspecialchars($str,
      /* on échappe guillemets _et_ apostrophes : */
      ENT_QUOTES
      /* les séquences UTF-8 invalides sont
      * remplacées par le caractère �
      * au lieu de renvoyer la chaîne vide…) */
      | ENT_SUBSTITUTE
      /* on utilise les entités HTML5 (en particulier &apos;) */
      | ENT_HTML5,
      'UTF-8');
  }

  /*
public function makeRecherchePage($info){
    $this->title="Recherche";
}*/
 //Affichage d'une liste de recette trouvées
 function makeListTrouveePage($tableau){
   $this->title="Liste de Recette(s) trouvées";
   $this->content="<ul class='recette' >";
   foreach($tableau as $tab => $key){
     $this->content .="<li><a href='".$this->router->getRecipeURL($tab)."'>".$key->getTitle()."</a></li>";
   }

   $this->content .="</ul>";
 }


}

?>
