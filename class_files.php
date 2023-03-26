<?php
class GestionnaireFichiers
{
    private $dossier;
    private $db;

    public function __construct($dossier, $dsn, $username, $password)
    {
        $this->dossier = $dossier;
        $this->db = new PDO($dsn, $username, $password);
        $this->creerDossier();
    }

    private function creerDossier()
    {
        // Crée le dossier s'il n'existe pas déjà
        if (!file_exists($this->dossier)) {
            mkdir($this->dossier);
        }
    }

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

    private function enregistreDansBase($unique)
    {
        $query = $this->db->prepare("INSERT INTO fichier (nom)VALUES (:nom)");

        // Liste des donnees a inserrer
        $data = [
            ':nom' => $unique
        ];
        $query->execute($data);
    }

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
