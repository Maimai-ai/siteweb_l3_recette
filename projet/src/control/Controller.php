<?php
set_include_path("./src");
require_once("view/View.php");
require_once("model/Recipe.php");
require_once("model/RecipeStorage.php");
require_once("model/authentification/AuthentificationManager.php");

class Controller{
  private $view;
  private $recipeStorage;
  private $auth;

  public function __construct(View $view, RecipeStorage $recipeStorage, AuthentificationManager $auth){
    $this->view=$view;
    $this->recipeStorage=$recipeStorage;
    $this->auth=$auth;
  }

  public function showInformation($id,array $data){
    $read=$this->recipeStorage->read($id);
    if($read==null){
      $this->view->makeUnknowRecipePage();
    }
    else{
      //var_dump($data);
      if(key_exists(AVIS_REF, $data)){
        //Calcul de la note de la recette
        if($data[AVIS_REF]!=='null'){
          $read->getAvis[]=$data[AVIS_REF];

          $tab2="".$read->getAvis[0];
          foreach ($read->getAvis as $key => $value) {
              if($value!==$read->getAvis[0]){
                $tab2.=",".$value;
              }
          }
          $arrayModify=array();
          $arrayModify[ID_REF]=$_GET['id'];
          $arrayModify[AVIS_REF]=$tab2;

          $this->recipeStorage->modify($arrayModify);
        }
      }
      $this->view->makeRecipePage($read);
    }
  }

  public function showList(array $data){
    //var_dump($data);
    if(key_exists(TAG_REF,$data)){ // Affichage des recettes ayant le tag voulu

      if($data[TAG_REF]==="--Recherche tag--"){
        $tab=$this->recipeStorage->readAll();
      }
      else{
        $tab=$this->recipeStorage->readTag($data);
      }
    }
    else{
      $tab=$this->recipeStorage->readAll();
    }
    $this->view->makeListPage($tab);
  }

  public function showListRecettesTrouvees(){
    $tab2=$this->recipeStorage->readAllRecherche();
    $this->view->makeListTrouveePage($tab2);
  }

  public function saveNewRecipe(array $data){
    $recipeBuilder=new RecipeBuilder($data);
    if($recipeBuilder->isValid()==false){
      $this->view->makeRecipeCreationPage($recipeBuilder);
    }
    else{
      $date=date('Y-m-d H:i:s');
      $newRecipe=new Recipe($recipeBuilder->getData(TITLE_REF),$_SESSION['login'],$recipeBuilder->getData(TIME_REF),$recipeBuilder->getData(INGREDIENTS_REF),$recipeBuilder->getData(PREPARATION_REF),$recipeBuilder->getData(IMAGE_REF),$date,$recipeBuilder->getData(TAG_REF),NULL,NULL);
      $this->recipeStorage->create($newRecipe);
      //var_dump($newRecipe);
      $this->view->makeRecipePage($newRecipe);
    }
  }

  public function authentification(array $data){
    $error="";
    if(key_exists('login',$data) && key_exists('mdp',$data) && !key_exists('user', $_SESSION)){
      $this->auth->connectUser($data['login'], $data['mdp']);
      if(!$this->auth->connectUser($data['login'], $data['mdp'])){
        $error="Erreur votre mot de passe ou votre login est faux !";
      }
    }
    if(key_exists('deco', $data)){
      $this->auth->disconnectUser();
      header('location: '.$_SERVER['PHP_SELF']);
    }
    $this->view->makeLoginFormPage($error);
  }

// Inscription si un utilisateur a déjà le meme login alors on retourne sur la page d'Inscription
// sinon il enregistre dans la base de donnée l'utilisateur

  public function registration(array $data){
    $info="";
    if(key_exists('login',$data) && key_exists('mdp',$data) && key_exists('name',$data)){
      if($this->auth->userExist($data['login'])==true){
        $info="Ce nom d'utilisateur existe déjà !";
        $this->view->makeLoginRegisterPage($info);
      }
      else{
        $hash = password_hash($data['mdp'], PASSWORD_BCRYPT);
        $this->auth->register($data['name'],$data['login'],$hash);
        $this->authentification($data);
        // $this->view->makeHomePage();
        $this->auth->connectUser($data['login'],$data['mdp']);
        var_dump("AJOUT");
        $this->view->makeLoginFormPage("Bienvenue sur le site : ". $data['name']);
      }
    }
    else{
      $info="Vous avez oublié de remplir certaines information !";
      $this->view->makeLoginRegisterPage($info);
      var_dump("PROBLEME AU FIN");
    }
  }

  public function askRecipeDeletion($id){
    if($this->recipeStorage->existe($id) && $this->auth->isUserConnected()){
      $this->view->makeRecipeDelectionPage($id);
    }
    else{
      $this->view->makeUnknowRecipePage();
    }
  }

  public function deleteRecipe($id){
    $this->recipeStorage->delete($id);
    $tab=$this->recipeStorage->readAll();
    $this->view->makeListPage($tab);
  }

}
?>
