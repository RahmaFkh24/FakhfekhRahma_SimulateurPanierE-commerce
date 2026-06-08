<?php
/**
 * API Backend pour la gestion des commandes
 * 
 * Ce fichier reçoit les données du panier depuis le frontend (index.html)
 * et les enregistre dans un fichier JSON (orders.json).
 */

// Définir le type de réponse en JSON avec encodage UTF-8
header('Content-Type: application/json; charset=utf-8');

// ========== GESTION DES REQUÊTES GET ==========
// Les requêtes GET retournent un message d'information (pas d'erreur)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(['success' => true, 'message' => 'Backend API pour les commandes. Utilise POST pour envoyer une commande.']);
    exit;
}

// ========== VALIDATION DE LA MÉTHODE ==========
// Seules les requêtes POST sont autorisées
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

// ========== RÉCUPÉRATION DES DONNÉES ==========
// Lire le corps de la requête JSON envoyée par le frontend
$rawBody = file_get_contents('php://input');
$data = json_decode($rawBody, true);

// ========== VALIDATION DES DONNÉES ==========
// Vérifier que les données sont valides et que le panier existe
if (!is_array($data) || !isset($data['cart']) || !is_array($data['cart'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Données de commande invalides.']);
    exit;
}

// ========== EXTRACTION DES DONNÉES ==========
// Récupérer le panier, le total et le code promo
$cart = $data['cart'];
$total = isset($data['total']) ? floatval($data['total']) : 0.0;
$promo = isset($data['promo']) ? trim($data['promo']) : null;

// ========== CRÉATION DE L'OBJET COMMANDE ==========
// Construire un objet contenant les informations de la commande
$order = [
    'date' => date('Y-m-d H:i:s'),
    'items' => $cart,
    'total' => $total,
    'promo' => $promo,
];

// ========== CHARGEMENT DES COMMANDES EXISTANTES ==========
// Chemin du fichier JSON qui stocke toutes les commandes
$ordersFile = __DIR__ . '/orders.json';
$orders = [];

// Lire les commandes précédentes s'il existe un fichier
if (file_exists($ordersFile)) {
    $stored = file_get_contents($ordersFile);
    $decoded = json_decode($stored, true);
    if (is_array($decoded)) {
        $orders = $decoded;
    }
}

// ========== AJOUT DE LA NOUVELLE COMMANDE ==========
// Ajouter la nouvelle commande à la liste
$orders[] = $order;

// ========== SAUVEGARDE DES COMMANDES ==========
// Écrire toutes les commandes dans le fichier JSON
// JSON_PRETTY_PRINT : Format lisible
// JSON_UNESCAPED_UNICODE : Préserve les caractères accentués
if (file_put_contents($ordersFile, json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Impossible d\'enregistrer la commande.']);
    exit;
}

// ========== RÉPONSE DE SUCCÈS ==========
// Retourner une réponse JSON confirmant l'enregistrement
echo json_encode(['success' => true, 'message' => 'Commande enregistrée avec succès.', 'order' => $order]);
