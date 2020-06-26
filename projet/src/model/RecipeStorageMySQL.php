<?php
set_include_path("./src");
require_once('Recipe.php');
require_once('RecipeStorage.php');
require_once('lib/ObjectFileDB.php');


class RecipeStorageMySQL implements RecipeStorage{
	private $db;


	public function __construct() {
		//$dsn = mysql:host=mysql.info.unicaen.fr;port=3306;dbname=jml_3;charset=utf8
		$dsn = "mysql:host=".MYSQL_HOST.";port=".MYSQL_PORT.";dbname=".MYSQL_DB.";charset=utf8";
		$user = MYSQL_USER;
		$pass = MYSQL_PASSWORD;
		$this->db = new PDO($dsn, $user, $pass);
        //$this->db = new ObjectFileDB('/users/21203742/tmp/bd.php');

       // $this->bd = $bd->setAttribute($attribute, $value);
    }
		// Efface tous ce qui a dans la BD et rajoute deux recettes
  public function reinit(){
		$rq="DELETE FROM `recettes`";
		$stmt=$this->db->prepare($rq);
		$stmt->execute();

		$rq2="INSERT INTO `recettes` VALUES (1,'Raclette','Toto','5','1kg patates, Fromage à raclette', 'Faire bouillir les patates dans une casserole d\'eau, chauffer la machine à raclette, ajouter des ingrédients si besoin','https://www.papillesetpupilles.fr/wp-content/uploads/2016/12/Raclette-%C2%A9beats1-shutterstock.jpg','2020-01-30 12:56:17','français, repas',NULL);";
		$stmt2=$this->db->prepare($rq2);
		$stmt2->execute();

		$rq3="INSERT INTO `recettes` VALUES (2,'Gateau au Yaourt','Toto','15','1 pot de yaourt, 4 oeufs, 1 pot de farine, 1 pot de sucre, 1 pot d\'huile', 'Tout melanger, four 180 C','https://assets.afcdn.com/recipe/20130924/34684_w1024h768c1cx2592cy1728.jpg','2020-01-30 13:01:30', 'français, sucré, gateau',NULL);";
		$stmt3=$this->db->prepare($rq3);
		$stmt3->execute();

	}

  public function read($id){
		//return $this->db->fetch($id);
		$rq = "SELECT * FROM recettes WHERE id = :id";
		$stmt = $this->db->prepare($rq);
		/*** remplissage des paramètres ***/
		$data = array(":id" => $id);
		/*** exécution du statement ***/
		$stmt->execute($data);
		/*** récupération du résultat ***/
		$result = $stmt->fetch();

		$x = new Recipe($result[TITLE_REF],$result[AUTHOR_REF],$result[TIME_REF],$result[INGREDIENTS_REF],$result[PREPARATION_REF],$result[IMAGE_REF],$result[DATETIME_REF],$result[TAG_REF],$result[AVIS_REF]);
		//var_dump($x); /*** Pour voir ce qui se passe ***/

		return $x;
	}

	function readAll(){
		$rq = "SELECT * FROM recettes";
		$stmt = $this->db->prepare($rq);
		/*** remplissage des paramètres ***/
		$data = array();
		/*** exécution du statement ***/
		$stmt->execute($data);
		/*** récupération du résultat ***/
		$result = $stmt->fetchAll();
		//var_dump($result);
		$donnees;
		foreach($result as $value){
			$value['id'];
			$donnees[$value['id']] = new Recipe($value[TITLE_REF],$value[AUTHOR_REF],$value[TIME_REF],$value[INGREDIENTS_REF],$value[PREPARATION_REF],$value[IMAGE_REF],$value[DATETIME_REF],$value[TAG_REF],$value[AVIS_REF]);
			//var_dump($value[TAG_REF]);
			$tab = explode(",",$value[TAG_REF]);

			//supprime les espace en debut de chaine de caractère de chaque valeur dans le tableau
			$result = array_map('trim', $tab);
			//var_dump($result);
		}
		//var_dump($donnees);
		return $donnees;
	}

	//Affichage des recettes en fonction des tag
	function readTag(array $tag){
		$rq = "SELECT * FROM recettes";
		$stmt = $this->db->prepare($rq);
		$data = array();
		$stmt->execute($data);
		$result = $stmt->fetchAll();
		$donnees=array();
		//var_dump($tag);
		//Liste des tags demandé
		$tab;
		// if(key_exists(TAG_REF,$tag)){
		// 	if(count($tag[TAG_REF])>=2){
		// 		$tab=explode(",",$tag[TAG_REF]);
		// 		$tab = array_map('trim', $tab);
		// 	}
		// 	else{
		// 		$tab=$tag[TAG_REF];
		// 	}
		// }
		//$newTab = array_map('trim', $tab);
		//ar_dump($tab);

		foreach($result as $key=>$value){
			if(key_exists(TAG_REF,$value)){
				$tabValue = explode(",",$value[TAG_REF]);
				$resultValue = array_map('trim', $tabValue);
				//var_dump($resultValue);
				foreach ($resultValue as $keyValue => $tagValue) {
					// $tab=explode(",",$tag[TAG_REF]);
					// var_dump($tab);
					if(key_exists(TAG_REF,$tag)){
						if($tagValue === $tag[TAG_REF]){
									$value['id'];
									$donnees[$value['id']] = new Recipe($value[TITLE_REF],$value[AUTHOR_REF],$value[TIME_REF],$value[INGREDIENTS_REF],$value[PREPARATION_REF],$value[IMAGE_REF],$value[DATETIME_REF],$value[TAG_REF],$value[AVIS_REF]);
						}
					}
				}
			}
			// var_dump($result);
			// if(key_exists(TAG_REF, $result)){
			// 	$resultTag=$result[TAG_REF];
			// 	var_dump($resultTag);
				// $tab2 = explode(",",$value[TAG_REF]);
				// $resultat = array_map('trim', $tab2);
				// foreach($newTab as $newTab => $valeur){
				// 	foreach($resultat as $resultat){
				// 		var_dump($resultat);
				// 		if($valeur === $resultat){
				// 			$value['id'];
				// 			$donnees[$value['id']] = new Recipe($value[TITLE_REF],$value[AUTHOR_REF],$value[TIME_REF],$value[INGREDIENTS_REF],$value[PREPARATION_REF],$value[IMAGE_REF],$value[DATETIME_REF],$value[TAG_REF]);
				// 		}
				// 	}
				// }



		}
		//var_dump($donnees);
		return $donnees;
	}

	function readAllRecherche(){$recherche1 = $_GET['recette'];
		$tabingredient = "SELECT * FROM recettes WHERE ingredients CONTAINS :recette";
		$tabpreparation = "SELECT * FROM recettes WHERE preparation CONTAINS :recette";
		$tabtag ="SELECT * FROM recettes WHERE tag CONTAINS :recette";
		$rq = "SELECT * FROM recettes WHERE title LIKE :recette OR tag LIKE :recette OR ingredients LIKE :recette  OR preparation LIKE :recette ";
		$parametres = array("%$recherche1%");
		$smtp = $this->db->prepare($rq);
		$smtp->bindValue(':recette','%'.$recherche1.'%', PDO::PARAM_STR);
		$smtp->execute();
		$donnees=array();
		if ($smtp->rowCount() > 0) {
			$result = $smtp->fetchAll();
			$donnees;
			foreach( $result as $row ) {
				//echo $row["title"];
				$theid = $row["id"];
				$donnees[$row["id"]] = new Recipe($row[TITLE_REF],$row[AUTHOR_REF],$row[TIME_REF],$row[INGREDIENTS_REF],$row[PREPARATION_REF],$row[IMAGE_REF],$row[DATETIME_REF],$row[TAG_REF],$row[AVIS_REF]);
			}

		} else {
			//echo 'Rien trouvé';
		}
		return $donnees;
	}

	function create(Recipe $recette){

		$idee = "SELECT MAX(id) FROM recettes";
		$id2 = $this->db->prepare($idee);
		$id2->execute();
		$id3 = $id2->fetch()['MAX(id)'];
		$id3++;

		$rq = "INSERT INTO `recettes` SET id='".$id3."',title='".$recette->getTitle()."',author_name='".$recette->getAuthor()."',temps='".$recette->getTime()."',ingredients='".$recette->getIngredients()."', preparation='".$recette->getPreparation()."', image_url='".$recette->getImg()."',creation_date='".$recette->getDateTime()."',tag='".$recette->getTag()."',avis='NULL';";

		//$rq = "INSERT INTO `recettes` VALUES (".$id3.",".$recette->getTitle().",".$recette->getAuthor().",".$recette->getTime().",".$recette->getIngredients().",".$recette->getPreparation().",".$recette->getImg().",".$recette->getDateTime().",".$recette->getTag().");";

		//var_dump($recette->getTag());
		$stmt = $this->db->prepare($rq);
		$stmt->execute();
		//return "ok";

	}

	function modify(array $data){
		//$rq = "UPDATE recettes SET title= ?, author_name=?, temps=?,ingredients=?, preparation=?, image_url=?, creation_date=?, tag=?, avis=?, nbAvis=? WHERE id= :id ";
		$id=$data[ID_REF];
		//$stmt = $this->db->prepare($rq);

		foreach($data as $ref => $value){
			//var_dump($value);
			$rq="UPDATE recettes SET ".$ref."=".$value." WHERE id=".$id.";";
			$stmt = $this->db->prepare($rq);
			$stmt->execute();
		}
	}

	function delete($id){
		$rq="DELETE FROM `recettes` WHERE `id` =".$id;
		$stmt=$this->db->prepare($rq);
		$stmt->execute();
	}

  function existe($id){
		$rq = "SELECT MAX(id) FROM recettes";
		$stmt = $this->db->prepare($rq);
		$stmt->execute();
		$idMax = $stmt->fetch()['MAX(id)'];
		if($id<=$idMax){
			return true;
		}
		else{
			return false;
		}
  }

}
