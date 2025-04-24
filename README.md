# Réseau Social

## Objectif du Projet
Créer une plateforme de réseau social sécurisée et intuitive, favorisant la collaboration, la communication en temps réel et l'accès à des fonctionnalités communautaires.

## Fonctionnalités Clés

### Fonctionnalités de Base
- **Authentification et Gestion des Utilisateurs** : Inscription et connexion sécurisées avec rôles (Utilisateur, Administrateur, Modérateur).
- **Gestion des profils** : photo, bio, et informations personnelles.
- **Amis et Réseaux (Optionnel)** : Ajouter, accepter ou refuser des demandes d’amitié. Notifications pour les nouvelles interactions.
- **Publications et Réactions** : Création de publications (texte, image, vidéo). Possibilité de commenter et de réagir aux publications.
- **Groupes et Communautés** : Créer ou rejoindre des groupes basés sur des intérêts. Partage de ressources et discussions dans des groupes dédiés.
- **Chat en Temps Réel (Optionnel)** : Messagerie privée et discussions de groupe. Notifications en temps réel pour messages et annonces.

### Fonctionnalités Avancées
- **Recherche** : Barre de recherche pour trouver des utilisateurs, publications, groupes. Suggestions basées sur les préférences et activités.
- **Notifications** : Recevoir des notifications pour les nouvelles interactions sur les publications, les demandes d’amitié, et les messages privés , avertissements ou alertes envoyées aux utilisateurs en cas de modération (contenu inapproprié, suspension, etc.)
- **Administration et Modération** : Tableau de bord pour les administrateurs (gestion des utilisateurs, contenu et permissions). Modération des publications et commentaires.

## Technologies Utilisées

### Frontend
- **HTML5** : Structure des pages web.
- **TailwindCSS** : Design moderne et responsive.
- **JavaScript** : Dynamisme et interactivité (AJAX, animations).

### Backend
- **PHP (Laravel)** : Gestion des fonctionnalités serveur (authentification, API, logique métier).
- **Laravel websocket** : Chat en temps réel et notifications.

### Base de Données
- **MySQL** : Stockage des utilisateurs, publications, messages.

### Autres Outils et API
- **PHPMailer** : Envoi de mails (notifications, récupération de mot de passe).

## Cas d'Utilisation (User Stories)
1. **Authentification et Gestion des Comptes**  
   En tant qu’utilisateur, je veux pouvoir créer un compte sécurisé et me connecter pour accéder à mon espace personnel.

2. **Gestion des Amis et Réseaux**  
   En tant qu’utilisateur, je veux envoyer des demandes d’amitié et recevoir des notifications pour rester informé.

3. **Publications et Interactions**  
   En tant qu’utilisateur, je veux publier des contenus (texte, image ou vidéo) et interagir avec les autres.

4. **Groupes**  
   En tant qu’utilisateur, je veux créer des groupes pour collaborer avec mes pairs et participer à des discussions.

5. **Chat et Communication en Temps Réel (optionnel)**  
   En tant qu’utilisateur, je veux envoyer des messages privés et participer à des discussions de groupe.

6. **Recherche**  
   En tant qu’utilisateur, je veux rechercher des utilisateurs, des publications par mots-clés pour trouver facilement les informations.

7. **Administration et Modération**  
   En tant qu’administrateur, je veux gérer les comptes utilisateurs et modérer les publications pour garantir un environnement sécurisé.

## Points Forts du Projet
- **Expérience Utilisateur Optimisée** : Interface intuitive et personnalisable.
- **Collaboration et Communication** : Groupes et messagerie instantanée.
- **Sécurité** : Authentification robuste, protection CSRF, et chiffrement des données sensibles.

---


