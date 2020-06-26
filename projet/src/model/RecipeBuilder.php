<?php

set_include_path("./src");
require_once("model/Recipe.php");


class RecipeBuilder{
  private $data;
  private $error;

  function __construct($data){
    $this->data=$data;
    $this->error=array();
  }

  public function getData($ref){
    return $this->data[$ref];
  }

  public function setData($ref,$str){
    $this->data[$ref]=$str;
  }

  public function getError(){
    $chaine="";
    foreach ($this->error as $key => $value) {
      $chaine.=$value."<br>";
    }
    return $chaine;
  }

  // public function createRecipe(){
  //   $date=date('l j F Y \à H:i:s');
  //   $recipe = new Recipe($this->data[TITLE_REF], $_SESSION['login'], $this->data[TIME_REF], $this->data[INGREDIENTS_REF],$this->data[PREPARATION_REF],$this->data[IMAGE_REF],$date,$this->data[TAG_REF]);
  //   return $recipe;
  // }

  public function isValid(){
    $this->error=array();
    if(!key_exists(TITLE_REF,$this->data) || $this->data[TITLE_REF]===""){
      $this->error[TITLE_REF]="Entrez le titre de votre recette";
    }
    else if (mb_strlen($this->data[TITLE_REF], 'UTF-8') >= 50){
			$this->error[TITLE_REF] = "Le titre doit faire moins de 50 caractères";
    }
    if(!key_exists(TIME_REF,$this->data) || $this->data[TIME_REF]===""){
      $this->error[TIME_REF]="Entrez le temps de préparation de votre recette";
    }
    else if (mb_strlen($this->data[TIME_REF], 'UTF-8') >=11 ){
			$this->error[TIME_REF] = "Ecrivez le temps de préparation en minutes";
    }
    if(!key_exists(INGREDIENTS_REF,$this->data) || $this->data[INGREDIENTS_REF]===""){
      $this->error[INGREDIENTS_REF]="Entrez les ingrédients de votre recette";
    }
    else if (mb_strlen($this->data[INGREDIENTS_REF], 'UTF-8') < 10 ){
			$this->error[INGREDIENTS_REF] = "Votre recette doit contenir au moins 2 ingrédients";
    }
    if(!key_exists(PREPARATION_REF,$this->data) || $this->data[PREPARATION_REF]===""){
      $this->error[PREPARATION_REF]="Entrez les instructions pour la préparation de votre recette";
    }
    else if (mb_strlen($this->data[PREPARATION_REF], 'UTF-8') < 10 ){
			$this->error[PREPARATION_REF] = "Il doit y avoir au moins 1 étape de préparation à votre recette";
    }
    if (count($this->error) == 0){
      return true;
    }
    else{
      return false;
    }
  }


}

?>
