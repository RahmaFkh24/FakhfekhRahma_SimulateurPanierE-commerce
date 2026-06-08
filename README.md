# FakhfekhRahma_SimulateurPanierE-commerce
Application web interactive permettant d’ajouter, supprimer et gérer des produits dans un panier en temps réel. Le projet met en œuvre les bases du développement front-end avec HTML, CSS et JavaScript pur, en manipulant le DOM et en utilisant le stockage local (localStorage) pour sauvegarder les données.
Ce projet inclut désormais une partie backend PHP : le fichier `order.php` reçoit les commandes envoyées depuis la page, les enregistre dans `orders.json` et renvoie un statut JSON.

Pour démarrer le backend localement :
1. Ouvrir un terminal dans ce dossier.
2. Lancer `start-backend.bat` ou exécuter `php -S localhost:8000`.
3. Ouvrir `http://localhost:8000/index.html` dans le navigateur.

> Important : la page doit être servie par PHP, pas ouverte en `file://`.
