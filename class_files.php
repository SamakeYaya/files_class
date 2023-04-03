<?php

/**
 * Class our gerer les fichier
 */
class GestionnaireFichiers
{
    /**
     * Undocumented variable
     *
     * @var [type] string $dossier: le dossier a creer
     * @var [type] string $db: base de donnees
     */
    private $dossier;
    private $db;

    /**
     * Le constructeur de ma class GestionnaireFichiers
     *
     * @param [type] $dossier: le nom du dossier a creer
     * @param [type] $dsn : nom de server et base de donne
     * @param [type] $username: nom de l'utilisateur
     * @param [type] $password: Le mdp pour acceder a la base de donnees
     */
    public function __construct($dossier, $dsn, $username, $password)
    {
        $this->dossier = $dossier;
        $this->db = new PDO($dsn, $username, $password);
        $this->creerDossier();
    }
    /**
     * fonction permettant de creer le dossier
     *
     * @return void
     */
    private function creerDossier()
    {
        // Crée le dossier s'il n'existe pas déjà
        if (!file_exists($this->dossier)) {
            mkdir($this->dossier);
        }
    }
    /**
     * enregistrer un fichier
     *
     * @param [type] $nom_fichier
     * @param [type] $contenu_fichier
     * @param [type] $file_type
     * @return void
     */
    public function enregistrerFichier($nom_fichier, $contenu_fichier, $file_type)
    {
        // Liste des types de fichiers autorises
        $type_auth = [
            'pdf' => 'application/pdf',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg'
        ];
        // Génère un nom de fichier unique

        // on recupere l'extention du fichier telecgarge et une concatenation avec nom unique genere
        $extension = strtolower(pathinfo($nom_fichier, PATHINFO_EXTENSION));

        if (!array_key_exists($extension, $type_auth) || !in_array($file_type, $type_auth)) {

            //Message d'ereur dans ce bloc de code 
            $nom_unique = false;
        } else {
            $nom_unique = md5(uniqid()) . '.' . $extension;

            // Enregistre le fichier dans le dossier avec le nom unique
            $file_path = $this->dossier . '/' . $nom_unique;
            file_put_contents($file_path, $contenu_fichier);

            $this->enregistreDansBase($nom_unique);
            // Retourne le nom unique du fichier
        }

        return $nom_unique;
    }
    /**
     * enregistre un fichier dans la base de donnees
     *
     * @param [type] $unique
     * @return void
     */
    private function enregistreDansBase($unique)
    {
        $query = $this->db->prepare("INSERT INTO fichier (nom)VALUES (:nom)");

        // Liste des donnees a inserrer
        $data = [
            ':nom' => $unique
        ];
        $query->execute($data);
    }
    /**
     * Supprime un fichier
     *
     * @param [type] $nom_fichier
     * @return void
     */
    public function supprimerFichier($nom_fichier)
    {
        // Supprime le fichier du dossier
        $file_path = $this->dossier . '/' . $nom_fichier;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    public function fichierExiste($nom_fichier)
    {
        // Vérifie si le fichier existe dans le dossier
        $file_path = $this->dossier . '/' . $nom_fichier;
        return file_exists($file_path);
    }
}
