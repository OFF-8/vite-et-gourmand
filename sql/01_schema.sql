CREATE DATABASE IF NOT EXISTS vite_et_gourmand CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE vite_et_gourmand;

CREATE TABLE theme (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

CREATE TABLE regime (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

CREATE TABLE utilisateur (
   id INT AUTO_INCREMENT PRIMARY KEY,
   nom VARCHAR(100) NOT NULL,
   prenom VARCHAR(100) NOT NULL,
   email VARCHAR(180) NOT NULL UNIQUE,
   gsm VARCHAR(20) NOT NULL,
   adresse VARCHAR(255) NOT NULL,
   code_postal VARCHAR(10) NOT NULL,
   ville VARCHAR(100) NOT NULL,
   mot_de_passe VARCHAR(255) NOT NULL,
   role VARCHAR(20) NOT NULL,
   actif TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    nombre_personnes_min INT NOT NULL,
    prix_minimum DECIMAL(10, 2) NOT NULL,
    conditions TEXT NOT NULL,
    precautions TEXT,
    stock INT NOT NULL DEFAULT 0,
    regime_id INT NOT NULL,
    theme_id INT NOT NULL,
    FOREIGN KEY (regime_id) REFERENCES regime(id),
    FOREIGN KEY (theme_id) REFERENCES theme(id)
);


CREATE TABLE plat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    type VARCHAR(20) NOT NULL
);

CREATE TABLE menu_plat (
    menu_id INT NOT NULL,
    plat_id INT NOT NULL,
    PRIMARY KEY (menu_id, plat_id),
    FOREIGN KEY (menu_id) REFERENCES menu(id),
    FOREIGN KEY (plat_id) REFERENCES plat(id)
);

CREATE TABLE allergene (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL
);

CREATE TABLE plat_allergene (
    plat_id INT NOT NULL,
    allergene_id INT NOT NULL,
    FOREIGN KEY (plat_id) REFERENCES plat(id),
    FOREIGN KEY (allergene_id) REFERENCES allergene(id),
    PRIMARY KEY (plat_id, allergene_id)
);

CREATE TABLE image_menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chemin VARCHAR(255),
    menu_id INT NOT NULL,
    FOREIGN KEY (menu_id) REFERENCES menu(id)
);

CREATE TABLE horaire (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jour VARCHAR(20) NOT NULL,
    heure_ouverture TIME,
    heure_fermeture TIME 
);

CREATE TABLE commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    menu_id INT NOT NULL,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(180),
    gsm VARCHAR(20),
    adresse_prestation VARCHAR(255),
    ville VARCHAR(100),
    kilometres DECIMAL(6, 2),
    date_prestation DATE,
    heure TIME,
    lieu VARCHAR(150),
    nombre_personnes INT,
    prix_menu DECIMAL(10, 2),
    prix_livraison DECIMAL(10, 2),
    remise DECIMAL(10, 2),
    statut_actuel VARCHAR(50),
    materiel_prete TINYINT(1) DEFAULT 0,
    motif_annulation TEXT,
    mode_contact_annulation VARCHAR(20),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id),
    FOREIGN KEY (menu_id) REFERENCES menu(id)
);

CREATE TABLE historique_statut (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    statut_actuel VARCHAR(50),
    date_heure DATETIME,
    FOREIGN KEY (commande_id) REFERENCES commande(id)
);

CREATE TABLE avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL UNIQUE,
    note TINYINT,
    commentaire TEXT,
    etat VARCHAR(20),
    FOREIGN KEY (commande_id) REFERENCES commande(id)
);

CREATE TABLE message_contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(150),
    description TEXT,
    email VARCHAR(180),
    date DATETIME
);

