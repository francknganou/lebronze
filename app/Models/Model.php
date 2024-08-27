<?php

namespace App\Models;//e code appartient au namespace App\Models, ce qui aide à organiser les classes et à éviter les conflits de noms.

use PDO;//PDO est une extension PHP qui définit une interface pour accéder à une base de données.
use Database\DBConnection;// Cela importe la classe DBConnection depuis l'espace de noms Database. Cette classe est probablement utilisée pour gérer la connexion à la base de données.

abstract class Model {//C'est une classe abstraite, ce qui signifie qu'elle ne peut pas être instanciée directement. Elle sert de base pour d'autres classes.

    protected $db;//Cette propriété stocke la connexion à la base de données.
    protected $table;//Cette propriété contient le nom de la table associée à ce modèle.

    public function __construct(DBConnection $db)//C'est le constructeur de la classe. Il accepte une instance de DBConnection en paramètre.
    {
        $this->db = $db;//Cela initialise la propriété $db avec la connexion à la base de données
    }

    public function all():array//Cette méthode récupère tous les enregistrements de la table associée au modèle.
    {
        return $this->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");//Elle exécute une requête SQL pour récupérer toutes les lignes de la table, triées par la date de création (created_at) dans l'ordre décroissant.
    }

    public function findById(int $id) : Model // Cette méthode recherche un enregistrement spécifique par son id.
    {
        return $this->query("SELECT * FROM {$this->table} WHERE id = ?", $id, true); //Elle exécute une requête SQL pour sélectionner l'enregistrement ayant l'id spécifié.
    }

    public function query(string $sql, int $param = null, bool $single = null)//Cette méthode exécute une requête SQL. Elle peut accepter un paramètre ($param) et déterminer si elle doit retourner un seul enregistrement ($single).
    {
        $method = is_null($param) ? 'query' : 'prepare'; //query pour requete simple sinon prepare avec des parametres
        $fetch = is_null($single) ? 'fetchALL' : 'fetch'; //recupere tous les resultats sinon fetch pour un seul

        $stmt = $this->db->getPDO()->$method($sql);//a requête SQL est exécutée en utilisant la méthode appropriée (query ou prepare).
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($this), [$this->db]);//Cette ligne configure le mode de récupération pour qu'il retourne une instance de la classe actuelle.

        if ($method === 'query') { //Si la méthode utilisée est query, elle retourne les résultats directement.
            return $stmt->$fetch();
        } else {
            $stmt->execute([$param]);//Si la méthode utilisée est prepare, elle exécute la requête avec le paramètre fourni, puis retourne le résultat.
            return $stmt->fetch();
        }
}
}