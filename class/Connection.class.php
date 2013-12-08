<?php

/**
 * La classe connectUser permet de gérer les utilisateurs dans la base de données
 */
class Connection {

    // Variables qui correspondent aux infos transmises par le formulaire
    private $user;
    private $psswd;
    private $time;
    // Variable de connexion à la db
    private $db;
    // Variables provenant de la db
    private $user_db;
    private $password_db;
    private $access_level_db;
    private $user_id_db;
    public $isConnected = false;

    public function __construct() {
        // Début de la connexion à la BDD avec PDO
        require_once(__DIR__ . '/config.php');
        $this->db = connectPdo();
    }

    /*
     * La fonction checkBdd permet d'aller vérifier en base de données si le nom d'utilisateur transmis existe bel et bien.
     * Cette fonction récupère aussi toute la ligne présente en base de données (id,user,password,last_login,level_access)
     * @return Rien si tout s'est bien passé OU une exception en cas de problème
     */

    public function connect($user, $psswd) {
        $this->user = $user;
        $this->psswd = $psswd;
        try {
            $sql = "SELECT * FROM user WHERE user = '" . $this->user . "'";
            $result = $this->db->query($sql);
            $result->setFetchMode(PDO::FETCH_ASSOC);
            $row = $result->fetch();
            $this->user_db = $row['user'];
            $this->password_db = $row['password'];
            $this->access_level_db = $row['level_access'];
            $this->user_id_db = $row['iduser'];
        } catch (PDOException $e) {
            print $e->getMessage() . "</br>";
        }
        if ($this->checkPassword()) {
            $this->userConnected();
            $this->isConnected = true;
        } else {
            echo "La connexion &agrave; &eacute;chou&eacute;e veuillez v&eacute;rifier vos informations de connexion";
        }
    }

    // La fonction checkPassword() vérifie que le mot de passe entré est bien correct.
    private function checkPassword() {
        if ($this->password_db === crypt($this->psswd, "js")) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // Vérifie que l'utilisateur est bien connecté
    private function userConnected() {
        $_SESSION['name_user'] = $this->user_db;
        $_SESSION['connected'] = $this->access_level_db;
        $_SESSION['user_id'] = $this->user_id_db;
        if($this->access_level_db == 0)
            $_SESSION['admin'] = true;
        else
            $_SESSION['admin'] = false;
        echo "Vous &eacirc;tes connect&eacute; en tant que " . $this->user_db;
        $time = time();
        $sql = "UPDATE user SET time = '" . $time . "' WHERE iduser= '" . $this->user_id_db . "'";
        $this->db->exec($sql);
    }

    public function addUser($username, $password, $level_access, $email) {
        $requete = $this->db->prepare('INSERT INTO user
(username, password, level_access, email) 
VALUES (:username, :password, :level_access, :email )');

        // Chiffrement du mot de passe avec crypt
        if (!$requete->execute(array('username' => strtolower($username),
                    'password' => crypt($password, "js"),
                    'level_access' => $level_access,
                    'email' => $email))) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function modifyUser($username, $id, $email, $password = null) {
        try {
            // Si le mot de passe a été changé
            if ($password != null)
                $requete = $this->db->prepare('UPDATE user SET
username = :username, email = :email, password = :password WHERE iduser = :id');
            if (!$requete->execute(array('username' => $username, 'password' => crypt($password, "js"),
                        'email' => $email,
                        'id' => $id))) {
                $message = $requete->errorInfo();
                print_r($message);
                throw new PDOException();
            } else {
                $requete = $this->db->prepare('UPDATE user SET
username = :username email = :email, WHERE iduser = :id');
                if (!$requete->execute(array('username' => $username,
                            'email' => $email,
                            'id' => $id))) {
                    $message = $requete->errorInfo();
                    print_r($message);
                    throw new PDOException();
                }
            }
        } catch (PDOException $e) {
            $message = $e->getMessage();
            echo "Une erreur est survenue durant la modification merci de réesayer";
        }
    }

    public function deleteUser($id) {
        $requete = $this->db->prepare('DELETE FROM user
WHERE iduser = :id');

        if (!$requete->execute(array('id' => $id))) {
            return FALSE;
        } else {
            return true;
        }
    }

    public function getListUsers() {
        require_once(__DIR__ . '/config.php');
        $this->db = connectPdo();
        $sql = "SELECT * FROM user";
        $result = $this->db->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $result->fetchAll();
        return $rows;
    }

    public function getUser($id) {
        require_once(__DIR__ . '/config.php');
        $this->db = connectPdo();

        $sql = "SELECT * FROM user WHERE iduser= :iduser";

        $requete = $this->db->prepare($sql);
        $result = $requete->execute(array('iduser' => $id));
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $row = $result->fetch();
        return $row;
    }
    /**
     * Vérifie la disponibilité du nom d'utilisateur
     * @param string $username
     */
    public function checkAvailibity($username) {
        $sql = "SELECT count(*)as nb FROM user WHERE username = :name";

        $requete = $this->db->prepare($sql);
        
        $requete->execute(array('name' => $username));
        $row = $requete->fetch();
        if($row['nb'] == 0)
            return true;
        return false;
    }

}
