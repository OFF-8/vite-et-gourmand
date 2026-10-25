-- Schéma Vite & Gourmand
-- Écarts avec l'annexe, à expliquer dans la doc technique :
-- mot de passe en VARCHAR(255), descriptions en TEXT, adresses en VARCHAR(255),
-- prix en DECIMAL, photo de plat en chemin, heures en TIME,
-- régime et thème uniquement par clés étrangères.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS reset_token;
DROP TABLE IF EXISTS avis;
DROP TABLE IF EXISTS historique_commande;
DROP TABLE IF EXISTS commande;
DROP TABLE IF EXISTS menu_image;
DROP TABLE IF EXISTS plat_allergene;
DROP TABLE IF EXISTS menu_plat;
DROP TABLE IF EXISTS plat;
DROP TABLE IF EXISTS allergene;
DROP TABLE IF EXISTS menu;
DROP TABLE IF EXISTS horaire;
DROP TABLE IF EXISTS utilisateur;
DROP TABLE IF EXISTS theme;
DROP TABLE IF EXISTS regime;
DROP TABLE IF EXISTS role;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE role (
    role_id INT NOT NULL AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL,
    PRIMARY KEY (role_id),
    UNIQUE KEY uq_role_libelle (libelle)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE regime (
    regime_id INT NOT NULL AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL,
    PRIMARY KEY (regime_id),
    UNIQUE KEY uq_regime_libelle (libelle)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE theme (
    theme_id INT NOT NULL AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL,
    PRIMARY KEY (theme_id),
    UNIQUE KEY uq_theme_libelle (libelle)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE utilisateur (
    utilisateur_id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(180) NOT NULL,
    password VARCHAR(255) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    telephone VARCHAR(50) NOT NULL,
    pays VARCHAR(50) NOT NULL,
    ville VARCHAR(50) NOT NULL,
    adresse_postale VARCHAR(255) NOT NULL,
    actif TINYINT(1) NOT NULL DEFAULT 1,
    role_id INT NOT NULL,
    PRIMARY KEY (utilisateur_id),
    UNIQUE KEY uq_utilisateur_email (email),
    CONSTRAINT fk_utilisateur_role FOREIGN KEY (role_id) REFERENCES role (role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE menu (
    menu_id INT NOT NULL AUTO_INCREMENT,
    titre VARCHAR(50) NOT NULL,
    nombre_personne_minimum INT NOT NULL,
    prix_par_personne DECIMAL(10,2) NOT NULL,
    description TEXT NOT NULL,
    quantite_restante INT NOT NULL,
    conditions TEXT NOT NULL,
    actif TINYINT(1) NOT NULL DEFAULT 1,
    regime_id INT NOT NULL,
    theme_id INT NOT NULL,
    PRIMARY KEY (menu_id),
    CONSTRAINT fk_menu_regime FOREIGN KEY (regime_id) REFERENCES regime (regime_id),
    CONSTRAINT fk_menu_theme FOREIGN KEY (theme_id) REFERENCES theme (theme_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE plat (
    plat_id INT NOT NULL AUTO_INCREMENT,
    titre_plat VARCHAR(50) NOT NULL,
    photo VARCHAR(255) NULL,
    type_plat ENUM('entree', 'plat', 'dessert') NOT NULL,
    PRIMARY KEY (plat_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE allergene (
    allergene_id INT NOT NULL AUTO_INCREMENT,
    libelle VARCHAR(50) NOT NULL,
    PRIMARY KEY (allergene_id),
    UNIQUE KEY uq_allergene_libelle (libelle)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE menu_plat (
    menu_id INT NOT NULL,
    plat_id INT NOT NULL,
    PRIMARY KEY (menu_id, plat_id),
    CONSTRAINT fk_menu_plat_menu FOREIGN KEY (menu_id) REFERENCES menu (menu_id) ON DELETE CASCADE,
    CONSTRAINT fk_menu_plat_plat FOREIGN KEY (plat_id) REFERENCES plat (plat_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE plat_allergene (
    plat_id INT NOT NULL,
    allergene_id INT NOT NULL,
    PRIMARY KEY (plat_id, allergene_id),
    CONSTRAINT fk_plat_allergene_plat FOREIGN KEY (plat_id) REFERENCES plat (plat_id) ON DELETE CASCADE,
    CONSTRAINT fk_plat_allergene_allergene FOREIGN KEY (allergene_id) REFERENCES allergene (allergene_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE menu_image (
    menu_image_id INT NOT NULL AUTO_INCREMENT,
    menu_id INT NOT NULL,
    chemin VARCHAR(255) NOT NULL,
    legende VARCHAR(255) NOT NULL,
    PRIMARY KEY (menu_image_id),
    CONSTRAINT fk_menu_image_menu FOREIGN KEY (menu_id) REFERENCES menu (menu_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE horaire (
    horaire_id INT NOT NULL AUTO_INCREMENT,
    jour VARCHAR(50) NOT NULL,
    heure_ouverture TIME NOT NULL,
    heure_fermeture TIME NOT NULL,
    PRIMARY KEY (horaire_id),
    UNIQUE KEY uq_horaire_jour (jour)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE commande (
    numero_commande VARCHAR(50) NOT NULL,
    date_commande DATE NOT NULL,
    date_prestation DATE NOT NULL,
    heure_livraison TIME NOT NULL,
    prix_menu DECIMAL(10,2) NOT NULL,
    nombre_personne INT NOT NULL,
    prix_livraison DECIMAL(10,2) NOT NULL,
    statut VARCHAR(50) NOT NULL,
    pret_materiel TINYINT(1) NOT NULL DEFAULT 0,
    restitution_materiel TINYINT(1) NOT NULL DEFAULT 0,
    ville VARCHAR(50) NOT NULL,
    adresse_prestation VARCHAR(255) NOT NULL,
    distance_km DECIMAL(8,2) NULL,
    annulation_motif TEXT NULL,
    annulation_contact VARCHAR(10) NULL,
    menu_id INT NOT NULL,
    utilisateur_id INT NOT NULL,
    PRIMARY KEY (numero_commande),
    CONSTRAINT fk_commande_menu FOREIGN KEY (menu_id) REFERENCES menu (menu_id),
    CONSTRAINT fk_commande_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (utilisateur_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE historique_commande (
    historique_id INT NOT NULL AUTO_INCREMENT,
    numero_commande VARCHAR(50) NOT NULL,
    statut VARCHAR(50) NOT NULL,
    horodate DATETIME NOT NULL,
    PRIMARY KEY (historique_id),
    CONSTRAINT fk_historique_commande FOREIGN KEY (numero_commande) REFERENCES commande (numero_commande)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE avis (
    avis_id INT NOT NULL AUTO_INCREMENT,
    note TINYINT UNSIGNED NOT NULL,
    description TEXT NOT NULL,
    statut VARCHAR(50) NOT NULL,
    utilisateur_id INT NOT NULL,
    numero_commande VARCHAR(50) NOT NULL,
    PRIMARY KEY (avis_id),
    UNIQUE KEY uq_avis_commande (numero_commande),
    CONSTRAINT fk_avis_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (utilisateur_id),
    CONSTRAINT fk_avis_commande FOREIGN KEY (numero_commande) REFERENCES commande (numero_commande)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reset_token (
    reset_token_id INT NOT NULL AUTO_INCREMENT,
    utilisateur_id INT NOT NULL,
    token_hash CHAR(64) NOT NULL,
    expire_at DATETIME NOT NULL,
    PRIMARY KEY (reset_token_id),
    CONSTRAINT fk_reset_token_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (utilisateur_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;