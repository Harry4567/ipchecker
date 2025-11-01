<?php
// Définit le type de contenu de la réponse comme JSON
header('Content-Type: application/json');

// Vérifie si une IP a été soumise via la méthode POST
if (!isset($_POST['ip']) || empty($_POST['ip'])) {
    echo json_encode(['error' => 'Veuillez fournir une adresse IP.']);
    exit;
}

// Récupère l'IP fournie par l'utilisateur et la nettoie
$ip_address = filter_var($_POST['ip'], FILTER_VALIDATE_IP);

if (!$ip_address) {
    echo json_encode(['error' => 'Adresse IP invalide.']);
    exit;
}

// URL de l'API de géolocalisation. Nous utilisons ip-api.com
// Attention : l'utilisation gratuite est limitée à 45 requêtes par minute.
$url = "http://ip-api.com/json/" . $ip_address . "?fields=status,message,country,countryCode,regionName,city,lat,lon,isp,org,as,query";

// Effectue la requête HTTP vers l'API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Décode la réponse JSON de l'API
$data = json_decode($response, true);

// Vérifie si l'API a retourné un statut de succès
if ($data && $data['status'] === 'success') {
    // Renvoie les données de localisation au format JSON
    echo json_encode([
        'success' => true,
        'location' => [
            'IP' => $data['query'],
            'Pays' => $data['country'] . " (" . $data['countryCode'] . ")",
            'Région/État' => $data['regionName'],
            'Ville' => $data['city'],
            'Latitude' => $data['lat'],
            'Longitude' => $data['lon'],
            'Fournisseur' => $data['isp'],
            'Organisation' => $data['org']
        ]
    ]);
} else {
    // Gère les erreurs de l'API (ex: IP réservée, rate limit, etc.)
    $message = $data['message'] ?? 'Erreur inconnue lors de la recherche.';
    echo json_encode(['error' => $message]);
}
?>