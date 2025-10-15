# Architecture 
````
RestoCampus
├── app/
│   ├── controllers/       # Contrôleurs (logique métier)
│   ├── models/            # Modèles (accès aux données)
│   ├── views/             # Vues (interface utilisateur)
│   └── core/              # Routeur, base Controller et Model
├── public/                # Point d’entrée (index.php, assets)
│   ├── css/
│   ├── js/
│   └── index.php
├── config/                # Configuration (BDD, constantes)
├── routes/                # Fichier de routes (optionnel)
├── .htaccess              # Redirection vers public/index.php
└── README.md`
````
