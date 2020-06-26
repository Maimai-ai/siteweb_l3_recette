<?php


require_once("model/authentification/Account.php");

class AuthentificationManager{
  private $db;

  public function __construct(){
    //$this->account=$account;
    $dsn = "mysql:host=".MYSQL_HOST.";port=".MYSQL_PORT.";dbname=".MYSQL_DB.";charset=utf8";
		$user = MYSQL_USER;
		$pass = MYSQL_PASSWORD;
    $this->db = new PDO($dsn, $user, $pass);
  }

  function connectUser($login, $password){
    $rq="SELECT * FROM comptes";
    $stmt=$this->db->query($rq);
    $tab=$stmt->fetchAll(PDO::FETCH_BOTH);
    foreach($tab as $ligne){
      if($ligne['login']===$login && password_verify($password,$ligne['mdp'])){
        $_SESSION['user'] = $ligne['name'];
        $_SESSION['login']= $ligne['login'];
        $_SESSION['statut'] =$ligne['statut'];
        return true;
      }
    }
    return false;
  }

  public function isUserConnected(){
    if(key_exists('login',$_SESSION)){
			return true;
		}
		return false;
	}

  public function userExist($login){
    $rq="SELECT * FROM comptes";
    $stmt=$this->db->query($rq);
    $tab=$stmt->fetchAll(PDO::FETCH_BOTH);
    foreach($tab as $ligne){
      if($ligne['login'] === $login){
        return true;
      }
    }
    return false;
  }

  public function register($nom,$login,$password){
    $newAccount=new Account($nom,$login,$password,'user');

    $ajout="INSERT INTO `comptes` SET name='".$nom."', login='".$login."', mdp='".$password."', statut='user';";
    $stmt = $this->db->prepare($ajout);
		$stmt->execute();
  }

  public function isAdminConnected(){
    if(key_exists('statut',$_SESSION)){
      if($_SESSION['statut']==='admin'){
        return true;
      }
    }
    else{
      return false;
    }
  }

  public function getUserName(){
    if(key_exists('user',$_SESSION)){
      return $_SESSION['user'];
    }
    else{
      throw new Exception("Not Connected");
    }
  }

  public function disconnectUser(){
    return session_unset();
  }

  public function getError(){
    $chaine="<span class='error'>Erreur votre mot de passe ou votre login est faux !</span>";
    return $chaine;
  }
}
?>
