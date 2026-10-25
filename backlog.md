# Backlog — Vite & Gourmand

Branche de travail : `feature/PROJ-XX` créée depuis `develop`, fusionnée dans `develop` après vérification, puis `develop` fusionnée dans `main` en fin de sprint.

Comptes du jeu d’essai :

- jose@vite-et-gourmand.local / JoseAdmin1! — administrateur
- julie@vite-et-gourmand.local / JulieEmploye1! — employée
- client@vite-et-gourmand.local / ClientVite1! — utilisateur

## Sprint 1 — Socle et vitrine

### PROJ-01 — Initialiser le dépôt et l’environnement local
- **Type** : setup
- **Priorité** : P0
- **Épic** : EPIC-1
- **Description** : Le projet part d’un dossier vide. Il faut un dépôt Git public, les branches demandées par l’examen, et une stack locale qui démarre avec une seule commande.
- **Critères d’acceptation** :
  - Étant donné une machine avec Docker Desktop, quand on copie `.env.example` vers `.env` et qu’on lance `docker compose up --build`, alors `http://localhost:8080` affiche « Vite & Gourmand ».
  - Étant donné l’accueil en `APP_ENV=local`, quand la page se charge, alors elle affiche une ligne de succès pour `SELECT 1` sur MySQL et une ligne de succès pour le `ping` MongoDB.
  - Étant donné Mailpit sur `http://localhost:8025`, quand l’accueil envoie le mail de test, alors ce mail est visible, expédié par `SMTP_FROM`.
  - Le dépôt distant contient `main` et `develop`. `.env` et `vendor/` sont absents de Git.
- **Notes techniques** : Services Docker `app` (php:8.3-apache, `pdo_mysql`, extension `mongodb`), `mysql`, `mongo`, `mailpit` (ports 8025 et 1025). DocumentRoot sur `public/`. Fichiers : `Dockerfile`, `docker-compose.yml`, `.env.example`, `.gitignore`, `composer.json` (`phpmailer/phpmailer`, `mongodb/mongodb`), `includes/bootstrap.php`, `includes/header.php`, `includes/footer.php`, `src/Database.php`, `src/MongoStore.php`, `src/Mailer.php`, `public/index.php`, `README.md`. Dans les conteneurs, l’hôte MySQL est `mysql` et l’hôte SMTP est `mailpit`. Réessayer la connexion MySQL 10 fois. Le README décrit ces étapes pour une machine vierge.
- **Dépendances** : aucune

### PROJ-02 — Créer le schéma relationnel
- **Type** : tech
- **Priorité** : P0
- **Épic** : EPIC-1
- **Description** : L’annexe 1 donne le modèle de données. Le sujet exige en plus des informations qu’elle ne contient pas : identité, conditions des menus, type de plat, galerie, suivi, annulation et avis lié à une commande.
- **Critères d’acceptation** :
  - Étant donné une base vide, quand on exécute `sql/schema.sql`, alors toutes les tables ci-dessous existent avec leurs clés étrangères.
  - Un utilisateur a exactement un rôle. Une commande référence un utilisateur et un menu. Un menu a un thème et un régime. La table de liaison menu-plat autorise un plat dans plusieurs menus. La table de liaison plat-allergène autorise plusieurs allergènes par plat.
  - `menu.regime` et `menu.theme` n’existent pas en texte libre : seules les clés étrangères vers `regime` et `theme` existent.
  - Rejouer le fichier sur une base déjà créée échoue de façon explicite, ou le fichier commence par supprimer les tables dans un ordre qui respecte les clés.
- **Notes techniques** : Tables de l’annexe : `role`, `utilisateur`, `menu`, `plat`, `allergene`, `regime`, `theme`, `horaire`, `commande`, `avis`, plus liaisons `menu_plat`, `plat_allergene`. Ajouts : `utilisateur.nom`, `utilisateur.actif`, `menu.conditions` (TEXT), `menu.actif`, `plat.type_plat` (`entree`, `plat`, `dessert`), `menu_image(chemin, legende)`, `avis.commande_id` unique, `avis.note` entier, `historique_commande(statut, horodate)`, `commande.ville`, `commande.adresse_prestation`, `commande.distance_km`, `commande.annulation_motif`, `commande.annulation_contact`, `reset_token(token_hash, expire_at)`. Mot de passe `VARCHAR(255)`, descriptions en `TEXT`, adresses en `VARCHAR(255)`, photo de plat en chemin et non en BLOB, prix en `DECIMAL(10,2)`. Ces écarts seront justifiés dans PROJ-37.
- **Dépendances** : PROJ-01

### PROJ-03 — Charger le jeu de données initial
- **Type** : tech
- **Priorité** : P0
- **Épic** : EPIC-1
- **Description** : L’application doit démarrer avec des comptes, des menus et des horaires réalistes pour que chaque parcours de l’examen soit démontrable sans saisie manuelle.
- **Critères d’acceptation** :
  - Étant donné le schéma créé, quand on exécute `sql/seed.sql`, alors il existe un administrateur José, une employée Julie et un client, avec les mots de passe du haut de ce fichier, hashés.
  - Il existe les rôles `utilisateur`, `employe`, `administrateur`, les thèmes Noël, Pâques, classique et évènement, les régimes végétarien, vegan et classique, au moins quatre allergènes, et sept horaires du lundi au dimanche.
  - Au moins quatre menus ont un stock, des conditions, une image, un thème, un régime, et des plats d’entrée, de plat et de dessert. Un même plat est lié à deux menus.
  - Exécuter deux fois le seed aboutit au même nombre de lignes, sans doublon.
- **Notes techniques** : `password_hash` généré une fois et collé dans le SQL, pas de mot de passe en clair dans la table. Images de démonstration dans `public/uploads/menus/`, chemins stockés dans `menu_image`. Aucune donnée MongoDB dans ce ticket.
- **Dépendances** : PROJ-02

### PROJ-04 — Produire la charte et les maquettes
- **Type** : doc
- **Priorité** : P1
- **Épic** : EPIC-1
- **Description** : L’examen demande une charte et six maquettes. Les écrans suivants reprennent cette palette pour rester cohérents avec le dossier jury.
- **Critères d’acceptation** :
  - `docs/maquettes/` contient trois images desktop et trois images mobile : accueil, liste des menus, fiche menu.
  - Un fichier `docs/charte.md` indique les couleurs fond `#F7F3EB`, texte `#1F1A17`, bordeaux `#8C2F39`, or `#C4A574`, le texte en Source Sans 3 et les titres en Fraunces.
  - Chaque maquette montre le bandeau, le contenu propre à l’écran et le pied de page.
- **Notes techniques** : Outil libre au choix (Figma, Penpot, dessins exportés). Les PDF finaux sont assemblés au PROJ-37. Ce ticket ne bloque pas PROJ-05 : la palette ci-dessus suffit pour coder.
- **Dépendances** : aucune

### PROJ-05 — Poser le bandeau, le pied de page et les pages légales
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-2
- **Description** : Toutes les pages partagent la navigation et le pied de page. Les horaires et les textes légaux sont exigés avant même la commande.
- **Critères d’acceptation** :
  - Le bandeau de chaque page contient des liens vers l’accueil, tous les menus, la connexion et le contact.
  - Étant donné les sept lignes de `horaire`, quand une page s’affiche, alors le pied de page montre le jour, l’heure d’ouverture et l’heure de fermeture pour le lundi jusqu’au dimanche.
  - `mentions-legales.php` indique l’identité Vite & Gourmand, Bordeaux, et les données collectées (identité, contact, commande).
  - `cgv.php` indique la livraison à 0 € dans Bordeaux, 5 € + 0,59 € par kilomètre en dehors, et 600 € si le matériel prêté n’est pas rendu sous 10 jours ouvrés.
- **Notes techniques** : `includes/header.php`, `includes/footer.php`, `public/mentions-legales.php`, `public/cgv.php`. Lire les horaires en PDO préparé. Liens contact et menus encore autorisés à pointer vers l’accueil tant que leurs tickets ne sont pas faits.
- **Dépendances** : PROJ-01, PROJ-03

### PROJ-06 — Présenter l’entreprise sur l’accueil
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-2
- **Description** : Julie et José veulent une vitrine : qui ils sont, depuis combien de temps, et les avis déjà validés.
- **Critères d’acceptation** :
  - L’accueil contient un seul `h1`, la présentation de Vite & Gourmand (Julie, José, 25 ans, Bordeaux, prestations pour les évènements) et un paragraphe sur le travail de l’équipe.
  - Étant donné un avis de statut `valide`, quand l’accueil s’affiche, alors sa note et son commentaire sont visibles.
  - Étant donné un avis `en_attente` ou `refuse`, quand l’accueil s’affiche, alors cet avis est absent.
  - Étant donné aucun avis valide, quand l’accueil s’affiche, alors la zone des avis indique qu’il n’y en a pas encore.
- **Notes techniques** : `public/index.php`. Retirer le bloc de diagnostic local de l’affichage public et le garder derrière un paramètre `?diagnostics=1` réservé à `APP_ENV=local`, pour que l’accueil livré au jury soit la vitrine.
- **Dépendances** : PROJ-05

### PROJ-07 — Afficher le catalogue des menus
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-2
- **Description** : Le visiteur, connecté ou non, voit tous les menus encore commandables, avec l’essentiel pour comparer.
- **Critères d’acceptation** :
  - Étant donné un menu `actif = 1` et `quantite_restante > 0`, quand on ouvre la liste, alors elle montre son titre, sa description, son minimum de personnes et son prix.
  - Le prix affiché est égal à `prix_par_personne × nombre_personne_minimum`, arrondi au centime.
  - Un menu inactif ou en stock 0 n’apparaît pas.
  - Chaque carte a un lien vers la fiche de ce menu.
- **Notes techniques** : `public/menus.php`. Requête avec jointures `theme` et `regime` déjà prévues pour PROJ-08. Échapper toutes les sorties HTML.
- **Dépendances** : PROJ-03, PROJ-05

### PROJ-08 — Filtrer les menus sans recharger la page
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-2
- **Description** : Le visiteur réduit la liste selon son budget, le thème, le régime et le nombre d’invités. Le sujet impose une mise à jour dynamique.
- **Critères d’acceptation** :
  - Le formulaire propose un prix maximum, une fourchette min-max, un thème, un régime et un nombre de personnes.
  - Étant donné un nombre de personnes saisi, quand le filtre s’applique, alors seuls les menus dont `nombre_personne_minimum` est inférieur ou égal à cette valeur restent affichés.
  - Les filtres de prix comparent le prix affiché du catalogue, pas `prix_par_personne` seul.
  - Changer un filtre met la liste à jour sans rechargement du document. Combiner trois filtres applique les trois. Vider les filtres réaffiche tous les menus commandables.
- **Notes techniques** : `public/api/menus.php` renvoie du JSON. `public/js/menus.js` appelle cette URL avec `fetch`. Requêtes préparées, y compris pour les filtres optionnels. Thèmes et régimes chargés depuis leurs tables.
- **Dépendances** : PROJ-07

### PROJ-09 — Afficher la fiche d’un menu
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-2
- **Description** : Avant de commander, le client doit voir le menu complet, en particulier les conditions, pour ne pas pouvoir dire qu’il ne les a pas vues.
- **Critères d’acceptation** :
  - La fiche affiche galerie, description, thème, régime, stock, minimum, prix catalogue, conditions, et les plats groupés en entrée, plat et dessert avec leurs allergènes.
  - Un plat lié à deux menus apparaît sur les deux fiches.
  - Les conditions sont dans un encadré placé avant le bouton Commander.
  - Le bouton Commander d’un visiteur non connecté ouvre la connexion, puis ramène vers la commande de ce menu. Le bouton d’un utilisateur connecté ouvre directement cette commande.
- **Notes techniques** : `public/menu.php?id=`. Mémoriser l’identifiant du menu en session avant la redirection vers la connexion. Images avec attribut `alt` égal à la légende. Ce ticket crée le lien ; le formulaire de commande lui-même est PROJ-14.
- **Dépendances** : PROJ-07

## Sprint 2 — Comptes et commande

### PROJ-10 — Permettre l’inscription
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-3
- **Description** : Un visiteur devient client pour pouvoir commander. Son rôle est fixé à l’inscription, et il reçoit un accueil par mail.
- **Critères d’acceptation** :
  - Le formulaire exige nom, prénom, téléphone, e-mail, adresse postale et mot de passe.
  - Étant donné un mot de passe de moins de 10 caractères, ou sans majuscule, ou sans minuscule, ou sans chiffre, ou sans caractère spécial, quand on valide, alors le compte n’est pas créé et le champ en erreur est indiqué.
  - Étant donné un e-mail déjà utilisé, quand on valide, alors la création est refusée.
  - Étant donné des données valides, quand on valide, alors le rôle est `utilisateur`, `actif = 1`, le mot de passe est hashé, et Mailpit contient un mail de bienvenue sans le mot de passe.
- **Notes techniques** : `public/inscription.php`. `password_hash`. Jeton CSRF. Requêtes préparées. Contrainte unique sur `utilisateur.email`.
- **Dépendances** : PROJ-02, PROJ-05

### PROJ-11 — Permettre la connexion et la déconnexion
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-3
- **Description** : L’accès aux espaces privés se fait par l’e-mail et le mot de passe. Un compte désactivé reste fermé.
- **Critères d’acceptation** :
  - Étant donné un e-mail et un mot de passe valides d’un compte actif, quand on valide, alors la session contient l’identifiant et le rôle, et l’utilisateur arrive sur son espace.
  - Étant donné un mauvais mot de passe, quand on valide, alors un message unique indique que l’identifiant est refusé, sans préciser si l’e-mail existe.
  - Étant donné `actif = 0`, quand on valide le bon mot de passe, alors la connexion est refusée.
  - La déconnexion vide la session et ramène à l’accueil.
- **Notes techniques** : `public/connexion.php`, `public/deconnexion.php`. `password_verify` et `session_regenerate_id(true)` après succès. Le bandeau affiche « Mon compte » à la place de « Connexion » lorsque la session existe.
- **Dépendances** : PROJ-10

### PROJ-12 — Réinitialiser le mot de passe oublié
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-3
- **Description** : Un client qui a perdu son mot de passe le remplace grâce à un lien reçu par mail, sans que l’ancien mot de passe ne circule.
- **Critères d’acceptation** :
  - Étant donné un e-mail inconnu, quand on demande un lien, alors l’écran affiche le même message que pour un e-mail connu, et aucun mail n’est envoyé.
  - Étant donné un e-mail connu, quand on demande un lien, alors Mailpit reçoit un lien à usage unique valable 30 minutes.
  - Étant donné un lien valide et un mot de passe conforme à PROJ-10, quand on valide, alors l’ancien mot de passe ne fonctionne plus et le lien ne fonctionne plus.
  - Étant donné un lien expiré ou déjà utilisé, quand on l’ouvre, alors la réinitialisation est refusée.
- **Notes techniques** : `public/mot-de-passe-oublie.php`, `public/reinitialiser.php`. Stocker le hash du jeton, jamais le jeton en clair. Supprimer les jetons précédents de cet utilisateur à chaque nouvelle demande.
- **Dépendances** : PROJ-11

### PROJ-13 — Restreindre les pages selon le rôle
- **Type** : tech
- **Priorité** : P0
- **Épic** : EPIC-3
- **Description** : Visiteur, utilisateur, employé et administrateur ne voient pas les mêmes actions. Le contrôle se fait sur chaque page, pas seulement en cachant un lien.
- **Critères d’acceptation** :
  - Étant donné un visiteur, quand il ouvre une URL d’espace connecté, alors il est renvoyé vers la connexion.
  - Étant donné un utilisateur, quand il ouvre une URL `/employe/` ou `/admin/`, alors la réponse est un refus 403.
  - Étant donné un employé, quand il ouvre une URL `/admin/`, alors la réponse est un refus 403.
  - Étant donné un administrateur, quand il ouvre une URL `/employe/`, alors la page s’affiche.
- **Notes techniques** : Une fonction `requireRole` dans `includes/auth.php`, appelée en haut de chaque page privée. Les dossiers `public/employe/` et `public/admin/` arrivent avec les tickets suivants ; ce ticket pose la fonction et un test manuel sur une page provisoire de chaque rôle.
- **Dépendances** : PROJ-11

### PROJ-14 — Préparer le formulaire de commande
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-4
- **Description** : La commande reprend le menu choisi sur la fiche et les coordonnées du compte, puis demande où et quand a lieu la prestation.
- **Critères d’acceptation** :
  - Étant donné un utilisateur arrivé depuis la fiche, quand la page s’ouvre, alors nom, prénom, e-mail et téléphone viennent du compte, et le menu est déjà sélectionné.
  - Étant donné un utilisateur qui ouvre la commande sans menu choisi, quand la page s’ouvre, alors il choisit un menu parmi les menus encore en stock.
  - Les champs date, heure, lieu, adresse et ville sont obligatoires.
  - Étant donné la ville Bordeaux, quelle que soit la casse, quand le formulaire change, alors le champ distance est masqué. Pour toute autre ville, le champ distance est obligatoire.
- **Notes techniques** : `public/commande.php`. Calcul du prix affiché en direct en JavaScript, recalculé côté serveur au PROJ-15. Ne pas enregistrer la commande dans ce ticket.
- **Dépendances** : PROJ-09, PROJ-13

### PROJ-15 — Enregistrer la commande
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-4
- **Description** : Julie applique un prix selon le nombre de personnes et selon la ville. La commande confirme le stock et part par mail.
- **Critères d’acceptation** :
  - Étant donné un nombre de personnes inférieur au minimum du menu, quand on valide, alors la commande est refusée.
  - Le récapitulatif affiche deux lignes avant validation : `prix_menu = prix_par_personne × nombre de personnes`, puis −10 % sur ce montant si le nombre est supérieur d’au moins 5 au minimum ; `prix_livraison = 0` à Bordeaux, sinon `5 + 0,59 × distance`, arrondi au centime.
  - Étant donné un stock à 0, quand on valide, alors la commande est refusée. Étant donné un stock positif, quand on valide, alors le statut est `en_attente`, le stock baisse de 1, une ligne d’historique est créée, et Mailpit reçoit le récapitulatif.
  - Revalider le formulaire une seconde fois ne crée pas une deuxième commande.
- **Notes techniques** : Transaction PDO : insertion `commande`, mise à jour `quantite_restante`, insertion `historique_commande`. Numéro `VG-AAAA-0001`. Jeton CSRF à usage unique. Le total stocké est la somme des deux lignes. Le contrôle du stock se fait dans la transaction avec relecture de la ligne.
- **Dépendances** : PROJ-14

### PROJ-16 — Consulter ses commandes
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-4
- **Description** : Le client retrouve le détail de ce qu’il a commandé, et seulement ses propres commandes.
- **Critères d’acceptation** :
  - Étant donné un utilisateur connecté, quand il ouvre son espace, alors chaque commande montre le numéro, le menu, la date de prestation, l’heure, le lieu, le nombre de personnes, les deux prix, le total et le statut.
  - Étant donné deux clients, quand le premier ouvre son espace, alors les commandes du second sont absentes.
  - Étant donné l’identifiant d’une commande d’un autre client dans l’URL, quand on l’ouvre, alors la réponse est un refus 403.
- **Notes techniques** : `public/mon-compte.php`. Filtrer par `utilisateur_id` de la session dans chaque requête, y compris la requête de détail.
- **Dépendances** : PROJ-15

### PROJ-17 — Modifier ou annuler une commande en attente
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-4
- **Description** : Tant que l’équipe n’a pas accepté, le client corrige la prestation ou renonce. Le menu choisi reste celui d’origine.
- **Critères d’acceptation** :
  - Étant donné une commande `en_attente`, quand le client modifie la date, l’heure, le lieu, l’adresse, la ville ou le nombre de personnes, alors les prix sont recalculés avec les règles de PROJ-15 et l’historique n’ajoute pas de statut.
  - Le formulaire de modification ne contient pas de champ pour changer le menu.
  - Étant donné une annulation, quand elle est confirmée, alors le statut devient `annulee`, le stock du menu augmente de 1, et une ligne d’historique est écrite.
  - Étant donné un statut autre que `en_attente`, quand le client appelle l’URL de modification, alors l’action est refusée.
- **Notes techniques** : Même transaction que PROJ-15 pour l’annulation et le stock. Vérifier le propriétaire et le statut côté serveur.
- **Dépendances** : PROJ-16

### PROJ-18 — Modifier son profil
- **Type** : feature
- **Priorité** : P1
- **Épic** : EPIC-4
- **Description** : Le client corrige ses coordonnées. Ces coordonnées préremplissent les prochaines commandes.
- **Critères d’acceptation** :
  - Le formulaire modifie nom, prénom, téléphone et adresse postale.
  - L’e-mail modifié et déjà pris par un autre compte est refusé.
  - Après enregistrement, une nouvelle commande reprend les nouvelles valeurs.
- **Notes techniques** : Formulaire sur `public/mon-compte.php`. Ne pas permettre de changer son rôle ni `actif`.
- **Dépendances** : PROJ-11

### PROJ-19 — Exporter ou supprimer son compte
- **Type** : feature
- **Priorité** : P1
- **Épic** : EPIC-4
- **Description** : Le sujet demande le respect du RGPD. Le client récupère les données liées à son compte et peut le fermer.
- **Critères d’acceptation** :
  - Étant donné un utilisateur connecté, quand il demande l’export, alors un fichier JSON téléchargé contient son profil, ses commandes et ses avis, et aucune donnée d’un autre compte.
  - Étant donné une suppression confirmée par le mot de passe, quand elle aboutit, alors `actif = 0`, la connexion devient impossible, et le nom affiché dans les commandes passées reste consultable par l’employé.
  - Les commandes déjà enregistrées restent en base.
- **Notes techniques** : `public/mon-compte.php`. Désactivation plutôt que suppression physique, pour conserver les commandes. Session détruite après suppression.
- **Dépendances** : PROJ-18

## Sprint 3 — Équipe, avis et suivi

### PROJ-20 — Gérer les plats et les allergènes
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-5
- **Description** : L’équipe compose les menus à partir de plats réutilisables. Chaque plat déclare ses allergènes.
- **Critères d’acceptation** :
  - Un employé ou un administrateur crée, modifie et supprime un plat avec son titre, son type (`entree`, `plat`, `dessert`) et son image.
  - Il associe ou retire plusieurs allergènes. Il crée un allergène qui manque dans la liste.
  - Étant donné un plat lié à un menu, quand on le supprime, alors la suppression est refusée et le message indique de le retirer d’abord des menus.
  - Un utilisateur reçoit un 403 sur ces URL.
- **Notes techniques** : `public/employe/plats.php`. Upload limité à JPEG, PNG et WebP, 2 Mo, nom de fichier généré, fichier servi depuis `public/uploads/plats/`. Vérifier le type MIME réel.
- **Dépendances** : PROJ-13, PROJ-03

### PROJ-21 — Gérer les menus
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-5
- **Description** : Julie met à jour l’offre : contenu, prix, stock, conditions et photos. Un menu déjà commandé doit rester lisible dans les commandes passées.
- **Critères d’acceptation** :
  - Le formulaire enregistre titre, description, conditions, minimum, prix par personne, stock, thème, régime, plats existants et galerie.
  - Étant donné un menu sans commande, quand on le supprime, alors il disparaît de la base et du catalogue.
  - Étant donné un menu déjà commandé, quand on le retire, alors `actif = 0`, il disparaît du catalogue, et la commande passée affiche encore son titre.
  - Après création d’un menu en stock, il apparaît dans le catalogue public sans modification du code.
- **Notes techniques** : `public/employe/menus.php`. Table `menu_image` : ajout et suppression de lignes, fichiers dans `public/uploads/menus/`. Au moins un plat obligatoire. Réutiliser les contrôles d’upload de PROJ-20.
- **Dépendances** : PROJ-20

### PROJ-22 — Modifier les horaires
- **Type** : feature
- **Priorité** : P1
- **Épic** : EPIC-5
- **Description** : Les horaires du pied de page changent depuis l’espace employé, pour les sept jours.
- **Critères d’acceptation** :
  - La page affiche les sept jours avec heure d’ouverture et heure de fermeture.
  - Étant donné un horaire modifié et enregistré, quand on recharge une page publique, alors le pied de page montre la nouvelle heure.
  - Une heure de fermeture antérieure à l’ouverture est refusée.
- **Notes techniques** : `public/employe/horaires.php`. Mettre à jour les sept lignes existantes plutôt que d’en insérer de nouvelles.
- **Dépendances** : PROJ-05, PROJ-13

### PROJ-23 — Retrouver les commandes côté employé
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-5
- **Description** : L’équipe doit isoler les commandes à traiter, par état ou par client.
- **Critères d’acceptation** :
  - La liste montre numéro, client, menu, date de prestation, statut et total.
  - Étant donné un statut choisi, quand on filtre, alors seules les commandes de ce statut restent.
  - Étant donné le nom ou l’e-mail d’un client, quand on filtre, alors seules ses commandes restent.
  - Les deux filtres se combinent. L’administrateur voit la même liste.
- **Notes techniques** : `public/employe/commandes.php`. Requêtes préparées. Le filtre client cherche sur nom, prénom et e-mail.
- **Dépendances** : PROJ-15, PROJ-13

### PROJ-24 — Faire avancer le statut d’une commande
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-5
- **Description** : La commande passe par la cuisine puis la livraison. S’il y a du matériel prêté, une étape supplémentaire prévient le client.
- **Critères d’acceptation** :
  - Les seules transitions acceptées sont : `en_attente` → `acceptee` → `en_preparation` → `en_cours_de_livraison` → `livre` → `terminee`.
  - Étant donné la case « prêt de matériel » cochée au passage à `livre`, quand on valide, alors la transition suivante autorisée est `en_attente_retour_materiel`, puis `terminee` avec `restitution_materiel = 1`.
  - Étant donné une transition qui saute une étape, quand on valide, alors elle est refusée.
  - Chaque transition ajoute une ligne d’historique avec le statut et l’horodatage. Le passage à `terminee` envoie un mail d’invitation à déposer un avis. Le passage à `en_attente_retour_materiel` envoie un mail qui rappelle les 10 jours ouvrés et les 600 €.
- **Notes techniques** : Colonne `pret_materiel`. Contrôler la transition dans une fonction unique, appelée par le formulaire. Lien du mail d’avis vers la commande du client, même si le formulaire d’avis arrive au PROJ-27.
- **Dépendances** : PROJ-23

### PROJ-25 — Modifier ou annuler une commande après contact client
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-5
- **Description** : L’employé peut corriger ou annuler une commande seulement après avoir joint le client. Le dossier garde la preuve de ce contact.
- **Critères d’acceptation** :
  - Étant donné une annulation sans mode de contact ou sans motif, quand on valide, alors elle est refusée.
  - Étant donné le mode `gsm` ou `mail` et un motif, quand on annule, alors le statut devient `annulee`, les deux informations sont enregistrées, le stock augmente de 1 et l’historique est mis à jour.
  - La modification d’une commande par l’employé exige les mêmes deux champs, recalcule les prix, et ne change pas le menu.
  - Un utilisateur reçoit un 403 sur cette action.
- **Notes techniques** : Réutiliser la transaction de stock de PROJ-17. Le mode de contact est une valeur parmi `gsm` et `mail`, pas un texte libre.
- **Dépendances** : PROJ-24

### PROJ-26 — Afficher le suivi au client
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-4
- **Description** : Dès que la commande est acceptée, le client voit chaque étape avec sa date et son heure, sans pouvoir la modifier.
- **Critères d’acceptation** :
  - Étant donné une commande `acceptee` ou au-delà, quand le client ouvre son détail, alors toutes les lignes d’historique s’affichent avec le statut, la date et l’heure.
  - Les boutons modifier et annuler sont absents dès que le statut n’est plus `en_attente`.
  - Étant donné une commande encore `en_attente`, quand le client l’ouvre, alors les actions de PROJ-17 restent présentes et l’historique de création est visible.
- **Notes techniques** : Compléter `public/mon-compte.php`. Lire `historique_commande` trié par horodatage croissant.
- **Dépendances** : PROJ-16, PROJ-24

### PROJ-27 — Déposer un avis sur une commande terminée
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-6
- **Description** : Après la prestation, le client note le menu. L’avis n’est pas public tant que l’équipe ne l’a pas relu.
- **Critères d’acceptation** :
  - Étant donné une commande `terminee` sans avis, quand le client envoie une note de 1 à 5 et un commentaire, alors l’avis est créé avec le statut `en_attente`.
  - Une note hors de 1 à 5, une commande qui n’est pas `terminee`, ou une deuxième soumission sur la même commande sont refusées.
  - L’avis `en_attente` n’apparaît pas sur l’accueil.
- **Notes techniques** : Formulaire dans le détail de `public/mon-compte.php`. Contrainte unique déjà prévue sur `avis.commande_id`. Vérifier que la commande appartient à la session.
- **Dépendances** : PROJ-26

### PROJ-28 — Modérer les avis
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-6
- **Description** : Julie choisit les avis qui représentent l’entreprise sur l’accueil. Elle peut aussi les refuser.
- **Critères d’acceptation** :
  - La page employé liste les avis `en_attente` avec la note, le commentaire, le menu et le client.
  - Étant donné une validation, quand l’accueil est rechargé, alors l’avis est visible.
  - Étant donné un refus, quand l’accueil est rechargé, alors l’avis est absent.
  - Un avis déjà `valide` peut passer à `refuse`, et disparaît alors de l’accueil.
- **Notes techniques** : `public/employe/avis.php`. Mettre à jour `avis.statut` parmi `valide` et `refuse`. L’accueil de PROJ-06 lit déjà ce statut : ne pas dupliquer la requête dans une deuxième règle.
- **Dépendances** : PROJ-27, PROJ-06

### PROJ-29 — Envoyer un message depuis la page contact
- **Type** : feature
- **Priorité** : P1
- **Épic** : EPIC-6
- **Description** : Un visiteur écrit à l’entreprise sans créer de compte. Le message arrive sur la boîte définie dans la configuration.
- **Critères d’acceptation** :
  - Le formulaire exige un titre, un message et un e-mail valide.
  - Étant donné des données valides, quand on envoie, alors Mailpit reçoit un mail à `CONTACT_EMAIL`, dont le sujet est le titre saisi et dont l’adresse de réponse est l’e-mail saisi.
  - Un champ vide ou un e-mail invalide n’envoie rien et indique le champ concerné.
- **Notes techniques** : `public/contact.php`. Réutiliser `src/Mailer.php`. Ajouter `Reply-To`. Jeton CSRF.
- **Dépendances** : PROJ-05

## Sprint 4 — Administration, mise en ligne, dossier

### PROJ-30 — Créer et désactiver un compte employé
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-6
- **Description** : José ouvre un accès à un nouvel employé et le retire quand la personne quitte l’équipe. Le mot de passe ne voyage pas par mail.
- **Critères d’acceptation** :
  - Étant donné un administrateur, quand il saisit un e-mail et un mot de passe conforme à PROJ-10, alors un compte de rôle `employe` est créé et Mailpit reçoit un mail sans le mot de passe.
  - Étant donné cet employé, quand il se connecte avec le mot de passe choisi par José, alors il accède à l’espace employé.
  - Étant donné un employé passé à `actif = 0`, quand il tente de se connecter, alors la connexion est refusée.
  - Aucune page, y compris en POST direct, ne crée un compte de rôle `administrateur`.
- **Notes techniques** : `public/admin/employes.php`. Le rôle est écrit en dur à `employe` dans la requête d’insertion. José reste le seul administrateur, créé par PROJ-03.
- **Dépendances** : PROJ-13

### PROJ-31 — Recopier les commandes dans MongoDB
- **Type** : tech
- **Priorité** : P0
- **Épic** : EPIC-6
- **Description** : Le sujet impose une base non relationnelle pour comparer les menus. Chaque commande confirmée, modifiée ou annulée y laisse un document à jour.
- **Critères d’acceptation** :
  - Étant donné une commande enregistrée, quand la transaction MySQL aboutit, alors MongoDB contient un document avec `numeroCommande`, `menuId`, `titreMenu`, `montant`, `dateCommande` et `statut`.
  - `montant` est égal à `prix_menu + prix_livraison`.
  - Étant donné une modification de prix ou une annulation, quand elle aboutit, alors le même document est mis à jour, sans doublon sur `numeroCommande`.
  - Étant donné un échec MongoDB après un succès MySQL, quand la page répond, alors elle signale l’erreur de synchronisation et un script `php bin/sync-mongo.php` peut réécrire les documents depuis MySQL.
- **Notes techniques** : Collection `commandes`. Appeler l’écriture depuis PROJ-15, PROJ-17 et PROJ-25. Index unique sur `numeroCommande`. Le script de rattrapage relit MySQL et fait un upsert.
- **Dépendances** : PROJ-15, PROJ-17, PROJ-25

### PROJ-32 — Comparer le nombre de commandes par menu
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-6
- **Description** : José voit quels menus sont les plus commandés. Les chiffres viennent de MongoDB.
- **Critères d’acceptation** :
  - Le graphique montre un barre par menu, avec le nombre de documents dont le statut n’est pas `annulee`.
  - Étant donné une nouvelle commande, quand José recharge la page, alors la barre de ce menu augmente de 1.
  - Étant donné l’annulation de cette commande, quand José recharge la page, alors la barre diminue de 1.
  - Couper MySQL et garder MongoDB laisse le graphique affichable. La page n’exécute pas de SELECT MySQL pour ces comptages.
- **Notes techniques** : `public/admin/statistiques.php`. Agrégation MongoDB `$group` sur `menuId`. Chart.js chargé en local dans `public/vendor/chart.js`, pour que le jury voie le graphique sans dépendance CDN. Réservé à l’administrateur.
- **Dépendances** : PROJ-30, PROJ-31

### PROJ-33 — Calculer le chiffre d’affaires par menu
- **Type** : feature
- **Priorité** : P0
- **Épic** : EPIC-6
- **Description** : José filtre le montant encaissé sur une période et, s’il le souhaite, sur un seul menu.
- **Critères d’acceptation** :
  - Sans filtre, le total affiché est la somme des `montant` des documents dont le statut n’est pas `annulee`.
  - Étant donné un menu et une date de début et de fin, quand on filtre, alors la somme ne compte que les documents de ce menu dont `dateCommande` est dans l’intervalle, bornes incluses.
  - Une date de fin antérieure à la date de début affiche un message et ne lance pas le calcul.
  - Les montants affichés correspondent, au centime, à la somme MySQL des mêmes commandes.
- **Notes techniques** : Même page que PROJ-32, deuxième bloc. Filtre MongoDB sur `menuId` et `dateCommande`. La comparaison MySQL sert de contrôle manuel, elle n’alimente pas l’écran.
- **Dépendances** : PROJ-32

### PROJ-34 — Rendre les parcours principaux accessibles
- **Type** : tech
- **Priorité** : P0
- **Épic** : EPIC-7
- **Description** : Le sujet exige une application conforme au RGAA. La vérification porte sur les parcours qu’un visiteur et un client font vraiment.
- **Critères d’acceptation** :
  - Accueil, liste, fiche et commande : un seul `h1`, chaque champ de formulaire a un `label` lié, le focus clavier est visible, et l’ordre de tabulation suit l’ordre visuel.
  - Une erreur de formulaire est écrite à côté du champ et le champ est indiqué en `aria-invalid`.
  - Chaque image de galerie a un texte alternatif égal à sa légende.
  - Le texte `#1F1A17` sur le fond `#F7F3EB` et le texte blanc sur `#8C2F39` sont les seuls couples texte/fond des contenus courants.
- **Notes techniques** : Vérifier au clavier, sans souris, les quatre pages. Corriger le HTML des tickets précédents plutôt que d’ajouter une surcouche. Noter dans `docs/accessibilite.md` les quatre parcours et le résultat.
- **Dépendances** : PROJ-09, PROJ-15, PROJ-28, PROJ-33

### PROJ-35 — Vérifier les contrôles de sécurité
- **Type** : tech
- **Priorité** : P0
- **Épic** : EPIC-7
- **Description** : La documentation d’examen doit pouvoir citer des contrôles concrets, déjà en place dans les tickets précédents et revérifiés ici.
- **Critères d’acceptation** :
  - Toutes les écritures SQL passent par des requêtes préparées. Une recherche dans le code ne trouve pas de valeur de formulaire concaténée dans une requête.
  - Chaque formulaire POST vérifie le jeton CSRF. Chaque page privée appelle le contrôle de rôle.
  - Un fichier uploadé qui n’est pas JPEG, PNG ou WebP, ou qui dépasse 2 Mo, est refusé et n’est pas écrit dans `public/uploads/`.
  - Les mots de passe stockés commencent par le préfixe de `password_hash`. Aucun mail de l’application ne contient un mot de passe.
- **Notes techniques** : Écrire le résultat de cette relecture dans `docs/securite.md` : hash, PDO, CSRF, session, rôles, uploads, RGPD. Ce document alimente PROJ-37.
- **Dépendances** : PROJ-21, PROJ-30

### PROJ-36 — Déployer l’application
- **Type** : setup
- **Priorité** : P0
- **Épic** : EPIC-7
- **Description** : Le sujet prévoit une pénalité si l’application n’est pas en ligne au moment de la remise. Les mails de production doivent arriver dans une vraie boîte.
- **Critères d’acceptation** :
  - L’URL publique sert l’accueil en HTTPS.
  - Un visiteur crée un compte sur cette URL, commande un menu de démonstration, et reçoit le mail de confirmation sur sa propre adresse.
  - Les statistiques de José s’affichent sur l’URL publique après cette commande.
  - Le README sépare les étapes locales des variables d’environnement de production, sans y écrire les mots de passe réels.
- **Notes techniques** : Hébergeur PHP + MySQL au choix parmi ceux cités par l’énoncé, MongoDB Atlas pour la base non relationnelle, SMTP de l’hébergeur ou d’un service de mail. `APP_ENV=production` masque les diagnostics. Dossier `public/uploads/` inscriptible. Rejouer `schema.sql` puis `seed.sql` sur la base de production.
- **Dépendances** : PROJ-35

### PROJ-37 — Assembler le dossier jury
- **Type** : doc
- **Priorité** : P0
- **Épic** : EPIC-7
- **Description** : L’examen demande le code, l’application en ligne, le suivi de projet et un dossier qui justifie les choix. Une personne extérieure doit pouvoir rejouer les parcours avec le seul manuel.
- **Critères d’acceptation** :
  - Le dépôt public contient le code, `README.md`, `sql/schema.sql` et `sql/seed.sql`. Les branches `main` et `develop` existent, et chaque ticket livré a une branche `feature/PROJ-XX` fusionnée.
  - Le manuel PDF donne l’URL publique et les identifiants de José, Julie et du client, puis les étapes des parcours visiteur, client, employé et administrateur.
  - La charte PDF contient la palette, les deux polices, trois maquettes desktop et trois maquettes mobile.
  - La documentation technique contient le choix de stack, l’installation, le modèle réel avec les écarts de PROJ-02, un diagramme de cas d’utilisation, un diagramme de séquence de la commande, et les étapes de déploiement. Une note d’une page explique le découpage en tickets et les branches.
  - Le rendu indique aussi le lien du tableau Trello ou Notion qui reprend ces tickets.
- **Notes techniques** : `docs/manuel.pdf`, `docs/charte.pdf`, `docs/technique.md`, `docs/gestion-projet.md`. Diagrammes en Mermaid exportés en image. Vérifier le manuel en suivant ses étapes sur l’URL publique, sans regarder le code.
- **Dépendances** : PROJ-04, PROJ-36

## Hors MVP

### PROJ-38 — Calculer la distance automatiquement
- **Type** : feature
- **Priorité** : P3
- **Épic** : hors backlog
- **Description** : La distance est saisie à la main. Une API de géocodage éviterait l’erreur de saisie, sans être demandée par le sujet.
- **Critères d’acceptation** :
  - Étant donné une adresse hors Bordeaux, quand le formulaire perd le focus, alors la distance en kilomètres se remplit sans saisie.
  - Étant donné une adresse introuvable, quand la recherche échoue, alors le champ reste saisissable.
- **Notes techniques** : À ne pas commencer avant PROJ-37. Choisir une API sans clé exposée au navigateur.
- **Dépendances** : PROJ-14

### PROJ-39 — Payer la commande en ligne
- **Type** : feature
- **Priorité** : P3
- **Épic** : hors backlog
- **Description** : Le sujet s’arrête à l’enregistrement de la commande. Le paiement en ligne est une évolution commerciale.
- **Critères d’acceptation** :
  - Étant donné un paiement confirmé par le prestataire, quand le retour arrive, alors la commande passe de `en_attente` à `acceptee`.
  - Étant donné un paiement refusé, quand le retour arrive, alors la commande reste `en_attente` et le stock n’est pas rendu.
- **Notes techniques** : Hors sujet d’examen. Ne pas l’ajouter au dossier jury.
- **Dépendances** : PROJ-15

### PROJ-40 — Exporter les commandes en CSV
- **Type** : feature
- **Priorité** : P3
- **Épic** : hors backlog
- **Description** : Julie pourrait sortir les commandes d’une période pour sa comptabilité. Le sujet ne le demande pas.
- **Critères d’acceptation** :
  - Étant donné un employé et une période, quand il exporte, alors le fichier CSV contient les commandes de cette période et aucune autre.
- **Notes techniques** : Une route dans `public/employe/commandes.php`. À traiter seulement après la remise.
- **Dépendances** : PROJ-23