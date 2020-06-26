<?php
set_include_path("./src/model");
require_once("RecipeStorage.php");
require_once("Recipe.php");

class RecipeStorageStub implements RecipeStorage{
  public $recipeTab;

  public function __construct(){
    $this->recipeTab=array(
      'raclette' => new Recipe("Raclette", "Tatiana", "10","poivrons, champignon, jambon, fromage, pomme de terre", "faite cuire les pommes de terres à l'eau chaude, prenez la machine à raclette, coupez le poivrons","Pas d'image", "2020-02-03 14:30:20", "salé"),
      'quiche' => new Recipe("Quiche", "Tatiana", "15", "pate feuilleté, 20 g lardons, 10 g fromage, 20 ml lait, 1 oeuf","Prechauffez le four à 180g, Mettez la pate feuileté sur un moule, déposer les lardons dessus, melanger le lait et les oeufs, ajouter dans l'appareil le fromage", "Pas d'image", "2020-02-10 14:25:30", "salé"),
      'mousse' => new Recipe("Mousse au chocolat", "Tatiana", "10", "5 oeufs, 500g de chocolat noir", "Séparez le blanc des jaunes, faire battre en neige le blanc des oeufs, faire fondre le chocolat noir, l'incorporer délicatement dans le blanc en neige, laissez au froid 1h avant la dégustation", "Pas d'image", "2020-02-10 14:27:03", "sucré"),
      'fruit' => new Recipe("Salade de fruit", "Tatiana", "5", "banane, pomme, fraise, raisin, orange", "Coupez les fruits, les mettre dans un saladier, laissez refroidir 20 min dans le réfrégirateur, degustez", "Pas d'image", "2020-02-10 14:29:50", "sucré"),
    );
  }

  public function read($id){
    if(key_exists($id, $this->recipeTab)){
      return $this->recipeTab[$id];
    }
    return null;
  }

  public function readAll(){
    return $this->recipeTab;
  }

  public function create(Recipe $recipe){
    return $this->recipeTab=$recipe;
  }
}
?>
