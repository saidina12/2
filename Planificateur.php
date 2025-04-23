<?php
require_once 'Evenement.php';

class Planificateur {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO("mysql:host=localhost;dbname=easyplan;charset=utf8", "root", "");
    }

    public function ajouterEvenement($event) {
        $stmt = $this->pdo->prepare("INSERT INTO evenements (titre, description, date_event, statut) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$event->titre, $event->description, $event->date_event, $event->statut]);
    }

    public function modifierEvenement($event) {
        $stmt = $this->pdo->prepare("UPDATE evenements SET titre=?, description=?, date_event=?, statut=? WHERE id=?");
        return $stmt->execute([$event->titre, $event->description, $event->date_event, $event->statut, $event->id]);
    }

    public function supprimerEvenement($id) {
        $stmt = $this->pdo->prepare("DELETE FROM evenements WHERE id=?");
        return $stmt->execute([$id]);
    }

    public function getEvenements() {
        $stmt = $this->pdo->query("SELECT * FROM evenements ORDER BY date_event ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rechercherEvenements($mot_cle, $date) {
        $stmt = $this->pdo->prepare("SELECT * FROM evenements WHERE (titre LIKE ? OR description LIKE ?) AND DATE(date_event) = ?");
        $mot_cle = "%$mot_cle%";
        $stmt->execute([$mot_cle, $mot_cle, $date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEvenementsImminents() {
        $stmt = $this->pdo->query("SELECT * FROM evenements WHERE date_event BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 1 DAY)");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>