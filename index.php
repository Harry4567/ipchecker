<?php

// Fonction pour récupérer l'adresse IP de l'utilisateur de manière robuste
function get_ip_address() {
    // Vérifie si l'IP est transmise par un proxy ou un CDN (ex: Cloudflare)
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Cas où l'IP est transmise par d'autres types de proxies
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        // Cas standard : adresse IP du client
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    // Si plusieurs IP sont présentes (dans HTTP_X_FORWARDED_FOR), prend la première
    if (strpos($ip, ',') !== false) {
        $ip = trim(explode(',', $ip)[0]);
    }

    return $ip;
}

$user_ip = get_ip_address();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Adresse IP</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333; 
            display: flex; 
            flex-direction: column;
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0;
            padding: 20px;
            padding-top: 100px;
            position: relative;
            overflow-x: hidden;
        }

        .navbar {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2),
                        0 0 0 1px rgba(255, 255, 255, 0.2) inset;
            z-index: 1000;
            display: flex;
            gap: 20px;
            align-items: center;
            animation: slideDown 0.6s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        .navbar a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            font-size: 1em;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .navbar a::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%);
            transform: translate(-50%, -50%);
            transition: width 0.4s, height 0.4s;
        }

        .navbar a:hover::before {
            width: 200px;
            height: 200px;
        }

        .navbar a:hover {
            color: #764ba2;
            transform: translateY(-2px);
        }

        .navbar a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .navbar a span {
            position: relative;
            z-index: 1;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 12px 20px;
                gap: 10px;
                top: 10px;
            }

            .navbar a {
                font-size: 0.9em;
                padding: 8px 15px;
            }

            body {
                padding-top: 90px;
            }
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
            pointer-events: none;
        }

        .container { 
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 50px 40px; 
            border-radius: 24px; 
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 
                        0 0 0 1px rgba(255, 255, 255, 0.2) inset;
            text-align: center;
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s ease-out;
            max-width: 600px;
            width: 100%;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 30px; 
            font-size: 2.5em;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        p {
            color: #666;
            font-size: 1.1em;
            margin-bottom: 25px;
        }

        .ip-box { 
            margin-top: 25px; 
            padding: 30px 50px; 
            min-width: 280px; 
            border: 3px solid transparent;
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(135deg, #667eea 0%, #764ba2 100%) border-box;
            border-radius: 16px; 
            font-size: 2em;
            font-weight: 700; 
            color: #667eea; 
            cursor: pointer; 
            transition: all 0.3s ease;
            position: relative;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        }

        .ip-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-radius: 16px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .ip-box:hover::before {
            opacity: 1;
        }

        .ip-box:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .ip-box:active {
            transform: translateY(-2px) scale(1.01);
        }

        #ip-value {
            position: relative;
            z-index: 1;
            display: inline-block;
            letter-spacing: 2px;
        }

        .confirmation-message {
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 1em;
            font-weight: 600;
            opacity: 0;
            transition: all 0.3s ease;
            pointer-events: none;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
            white-space: nowrap;
        }

        .confirmation-message.show {
            opacity: 1;
            transform: translateX(-50%) translateY(-5px);
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }

            h1 {
                font-size: 2em;
            }

            .ip-box {
                padding: 25px 30px;
                font-size: 1.6em;
                min-width: 240px;
            }

            p {
                font-size: 1em;
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
    </style>
    <link rel="icon" href="./ipchecker.png" type="image/x-icon">
</head>
<body>
    <nav class="navbar">
        <a href="./" class="active"><span>Mon IP</span></a>
        <a href="lookup"><span>Recherche IP</span></a>
    </nav>
    
    <div class="container">
        <h1>Mon Adresse IP Publique</h1>
        <p>Cliquez sur l'adresse IP pour la copier :</p>
        
        <div class="ip-box" id="ip-to-copy">
            <span id="ip-value">
                <?php echo htmlspecialchars($user_ip); ?>
            </span>
            <span class="confirmation-message" id="copy-confirm">✓ Copié !</span>
        </div>
        
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ipBox = document.getElementById('ip-to-copy');
            const ipValueElement = document.getElementById('ip-value');
            const confirmMessage = document.getElementById('copy-confirm');

            // Vérifie si les éléments existent et si l'API de presse-papiers est supportée
            if (ipBox && ipValueElement && navigator.clipboard) {
                
                ipBox.addEventListener('click', () => {
                    // Récupère UNIQUEMENT le texte de l'élément contenant l'IP
                    const ipAddress = ipValueElement.textContent.trim();
                    
                    // Utilisation de l'API moderne pour copier (asynchrone)
                    navigator.clipboard.writeText(ipAddress).then(() => {
                        
                        // Affichage du message de confirmation
                        confirmMessage.classList.add('show');
                        
                        // Masquer le message après 1.5 seconde
                        setTimeout(() => {
                            confirmMessage.classList.remove('show');
                        }, 1500);
                        
                    }).catch(err => {
                        console.error('Erreur lors de la copie : ', err);
                        alert("Impossible de copier automatiquement. Veuillez copier manuellement : " + ipAddress);
                    });
                });
            } else if (ipBox) {
                // Fallback si l'API n'est pas supportée
                 ipBox.addEventListener('click', () => {
                    alert("Votre navigateur ne supporte pas la copie automatique. Copiez manuellement.");
                });
            }
        });
    </script>
</body>
</html>