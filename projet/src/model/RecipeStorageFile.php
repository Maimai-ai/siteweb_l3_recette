<?php
set_include_path("./src");
require_once("model/BookStorage.php");
require_once("lib/ObjectFileDB.php");

class RecipeStorageFile implements BookStorage{
  private $db;

  public function __construct($file){
    $this->db=new ObjectFileDB($file);
  }

  public function reinit(){
    $this->db->deleteAll();
    $this->db->insert(new Recipe("Raclette", "Tatiana", "10","poivrons, champignon, jambon, fromage, pomme de terre", "faite cuire les pommes de terres à l'eau chaude, prenez la machine à raclette, coupez le poivrons","Pas d'image", "2020-02-03 14:30:20", "salé"));
    $this->db->insert(new Recipe("Quiche", "Tatiana", "15", "pate feuilleté, 20 g lardons, 10 g fromage, 20 ml lait, 1 oeuf","Prechauffez le four à 180g, Mettez la pate feuileté sur un moule, déposer les lardons dessus, melanger le lait et les oeufs, ajouter dans l'appareil le fromage", "Pas d'image", "2020-02-10 14:25:30", "salé"));
    $this->db->insert(new Recipe("Mousse au chocolat", "Tatiana", "10", "5 oeufs, 500g de chocolat noir", "Séparez le blanc des jaunes, faire battre en neige le blanc des oeufs, faire fondre le chocolat noir, l'incorporer délicatement dans le blanc en neige, laissez au froid 1h avant la dégustation", "Pas d'image", "2020-02-10 14:27:03", "sucré"));
    $this->db->insert(new Recipe("Salade de fruit", "Tatiana", "5", "banane, pomme, fraise, raisin, orange", "Coupez les fruits, les mettre dans un saladier, laissez refroidir 20 min dans le réfrégirateur, degustez", "Pas d'image", "2020-02-10 14:29:50", "sucré"));
  }

  public function read($id){
    if($this->db->exists($id)){
      return $this->db->fetch($id);
    }
    return null;
  }

  public function readAll(){
    return $this->db->fetchAll();
  }

  public function create(Book $book){
    return $this->db->insert($book);
  }

  public function delete($id){
    return $this->db->delete($id);
  }

  public function existe($id){
    return $this->db->exists($id);
  }
}
?>
