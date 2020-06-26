<?php
class Recipe{
  private $title;
  private $time;
  private $ingredients;
  private $preparation;
  private $img;
  private $datetime;
  private $author;
  private $tag;
  private $avis;

  public function __construct($title,$author,$time,$ingredients,$preparation,$img,$datetime,$tag,$avis){

    $this->title=$title;
    $this->author=$author;
    $this->time=$time;
    $this->ingredients=$ingredients;
    $this->preparation=$preparation;
    $this->img=$img;
    $this->datetime=$datetime;
    $this->tag=$tag;
    //$this->avis=$avis;
    $this->avis= explode(',',$avis);
    //$this->avis= array_map('trim', $this->avis);
  }

  public function getTitle(){
    return $this->title;
  }
  public function getAuthor(){
    return $this->author;
  }

  public function getTime(){
    return $this->time;
  }

  public function getIngredients(){ //PARSER
    //$tab = explode(",",$this->ingredients);
    return $this->ingredients;
  }

  public function getPreparation(){
    //$tab = explode(",",$this->preparation);
    return $this->preparation;
  }

  public function getImg(){
    return $this->img;
  }

  public function getDateTime(){
    return $this->datetime;
  }

  public function getTag(){
    //$tab = explode(",",$this->tag);
    return $this->tag;
  }

  public function getAvis(){
    //$tab=explode(",",$this->avis);
    return $this->avis;
  }

  public function getNote(){
    $compt=0;
    if(count($this->getAvis())==1){
      return $this->getAvis()[0];
    }
    else{
      foreach ($this->getAvis() as $key => $value) {
        $compt=$compt+$value;
      }
      $result=$compt/(count($this->getAvis()));
      return $result;
    }
  }

  public function setTitle($titre){
    $this->title=$titre;
  }

  public function setAuthor($author){
    $this->author=$author;
  }

  public function setTime($time){
    $this->time=$time;
  }

  public function setIngredients($ingredients){
    $this->ingredients=$ingredents;
  }

  public function setPreparation($preparation){
    $this->preparation=$preparation;
  }

  public function setImg($img){
    $this->img=$img;
  }

  public function setDateTime($datetime){
    $this->dateTime=$datetime;
  }

  public function setTag($tag){
    $this->tag=$tag;
  }

  public function setAvis($avis){
    array_push($this->avis,$avis);
  }
}
?>
