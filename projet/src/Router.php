<?php
set_include_path("./src");
require_once("view/View.php");
require_once("control/Controller.php");
require_once("model/RecipeStorage.php");
require_once("model/RecipeStorageMySQL.php");
require_once("model/RecipeBuilder.php");
require_once("model/authentification/AuthentificationManager.php");

class Router{

  public function __construct(){
  }

  public function main(){
    $recipeStorage = new RecipeStorageMySQL();

    $authManager= new AuthentificationManager();
    //$recipeStorage->reinit();
      $vue = new View($this,$authManager);
    $controleur = new Controller($vue,$recipeStorage,$authManager);

    if(key_exists('id',$_GET)){
      $id = $_GET['id'];
      $controleur->showInformation($id,$_POST);
    }
    else if(key_exists('liste',$_GET)){
      // $array=array();
      $controleur->showList($_POST);
    }
    else //if((key_exists('rechercher',$_GET)){
		if(key_exists('envoi',$_GET) AND $_GET["envoi"] == "recherche"){
		  $recette = htmlspecialchars($_GET["recette"]);
		  $recette = strtolower($recette);
		  $controleur->showListRecettesTrouvees();
	//	}
    }
    else if(key_exists('connexion',$_GET)){
      $controleur->authentification($_POST);
    }
    else if(key_exists('inscription',$_GET)){
      if($authManager->isUserConnected()==false){
        $vue->makeLoginRegisterPage("");
      }
      else{
        $variable="Vous êtes déjà connecté !";
        $vue->makeDebugPage($variable);
      }
    }
    else if(key_exists('registerSave',$_GET)){
      $controleur->registration($_POST);
    }
    else if(key_exists('action',$_GET)){
      if($_GET['action']==="nouveau"){
        $array=array(TITLE_REF => "", AUTHOR_REF=>"",TIME_REF=>"",INGREDIENTS_REF=>"", PREPARATION_REF=>"",TAG_REF=>"");
        $recipeBuilder=new RecipeBuilder($array);
        $vue->makeRecipeCreationPage($recipeBuilder);
      }
      else if($_GET['action']==="sauverNouveau"){
        $controleur->saveNewRecipe($_POST);
      }
    }
    else if(key_exists('askSuppression',$_GET)){
      $idSuppression=$_GET['askSuppression'];
      $controleur->askRecipeDeletion($idSuppression);
    }
    else if(key_exists('suppression',$_GET)){
      $idSupp=$_GET['suppression'];
      if(key_exists('confirme', $_POST)){
        $controleur->deleteRecipe($idSupp);
      }
    }
    else{
      $vue->makeHomePage();
    }
    $vue->render();
  }

  public function getHomeURL(){
    return $_SERVER['PHP_SELF'];
  }

  public function getListURL(){
    return $_SERVER['PHP_SELF']."?liste";
  }

  public function getRecipeURL($id){
    return $_SERVER['PHP_SELF']."?id=$id";
  }

  public function getRecipeCreationURL(){
    return $_SERVER['PHP_SELF']."?action=nouveau";
  }

  public function getRecipeSaveURL(){
    return $_SERVER['PHP_SELF']."?action=sauverNouveau";
  }

  public function getAccountURL(){
    return $_SERVER['PHP_SELF']."?connexion";
  }

  public function getAccountRegisterURL(){
    return $_SERVER['PHP_SELF']."?inscription";
  }

  public function getAccountRegisterSaveURL(){
    return $_SERVER['PHP_SELF']."?registerSave";
  }

  public function getRecipeAskDeletionURL($id){
    return $_SERVER['PHP_SELF']."?askSuppression=$id";
  }

  public function getRecipeDeletionURL($id){
    return $_SERVER['PHP_SELF']."?suppression=$id";
  }
  //peut etre non utile ?
  public function getRecherchePageUrl(){
    return $_SERVER['PHP_SELF']."?rechercher";
  }
}

?>
