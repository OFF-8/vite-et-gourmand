USE vite_et_gourmand;

INSERT INTO theme (nom) VALUES 
('Noël'),
('Pâques'),
('Anniversaire'),
('Evenement Professionnel');

INSERT INTO regime (nom) VALUES 
('Classique'),
('Végétarien'),
('Vegan');

INSERT INTO utilisateur (nom, prenom, email, gsm, adresse, code_postal, ville, mot_de_passe, role, actif) VALUES 
('Garcia', 'José', 'jose.garcia@example.com', '06 05 06 07 08', '456 Rue de la République', '33000', 'Bordeaux', '$2y$10$4YmnYC9CQh4o2JJk9vabIe2Qj7GzM8gbuVQmvX1YFrz4XMB4qaIFO', 'administrateur', 1),
('Martin', 'Julie', 'julie.martin@example.com', '06 09 10 11 12', '789 Rue de la Liberté', '33000', 'Bordeaux', '$2y$10$4YmnYC9CQh4o2JJk9vabIe2Qj7GzM8gbuVQmvX1YFrz4XMB4qaIFO', 'employe', 1),
('Dubois', 'Pierre', 'pierre.dubois@example.com', '06 13 14 15 16', '101 Rue de la Paix', '33000', 'Bordeaux', '$2y$10$4YmnYC9CQh4o2JJk9vabIe2Qj7GzM8gbuVQmvX1YFrz4XMB4qaIFO', 'utilisateur', 1),
('Durand', 'Marie', 'marie.durand@example.com', '06 17 18 19 20', '123 Rue de la Paix', '33100', 'Saint-Augustin', '$2y$10$4YmnYC9CQh4o2JJk9vabIe2Qj7GzM8gbuVQmvX1YFrz4XMB4qaIFO', 'utilisateur', 1);


INSERT INTO menu (
    titre,
    description,
    nombre_personnes_min,
    prix_minimum,
    conditions,
    precautions,
    stock,
    regime_id,
    theme_id
) VALUES (
    'Prestige de Noël',
    'Un repas de fête composé de foie gras mi-cuit accompagné de son chutney de figues, d''un suprême de volaille sauce aux morilles accompagné de pommes duchesse et de fagots de haricots verts, suivi d''une bûche pâtissière aux trois chocolats.',
    4,
    45.00,
    'Commande à réserver au minimum 7 jours avant Noël. Disponible du 20 au 25 décembre.',
    'Contient du gluten, des produits laitiers et des œufs. Présence possible de fruits à coque.',
    50,
    1,
    1),
    
    ('Festin de Pâques',
    'Menu festif composé d''asperges vertes accompagnées d''un œuf mollet et de sauce hollandaise, suivi d''un gigot d''agneau rôti au romarin et à l''ail avec pommes grenailles et légumes de saison, puis d''un nid de Pâques au chocolat et au praliné.',
    4,
    39.90,
    'Commande à réserver au minimum 5 jours à l''avance. Disponible durant le week-end de Pâques.',
    'Contient des œufs, du gluten, des produits laitiers et des fruits à coque. Peut contenir des traces d''allergènes.',
    40,
    1,
    2
);

INSERT INTO plat (nom, type) VALUES 
('Foie gras', 'entree'), -- 1
('Suprême de volaille', 'plat'), -- 2
('Bûche trois chocolats', 'dessert'), -- 3
('Asperges vertes', 'entree'), -- 4 
('Gigot d''agneau rôti', 'plat'), -- 5
('Nid de Pâques au chocolat', 'dessert'); -- 6

INSERT INTO menu_plat (menu_id, plat_id) VALUES 
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 4),
(2, 5),
(2, 6);


INSERT INTO allergene (nom) VALUES 
('Gluten'), -- 1    
('Lait'), -- 2
('Œufs'), -- 3
('Fruits à coque'), -- 4
('Arachides'), -- 5
('Soja'), -- 6
('Poissons'), -- 7  
('Crustacés'), -- 8
('Mollusques'), -- 9
('Céleri'), -- 10
('Moutarde'), -- 11
('Sésame'), -- 12
('Sulfites'), -- 13
('Lupin'); -- 14

INSERT INTO plat_allergene (plat_id, allergene_id) VALUES 

(1, 2),  -- Foie gras : lait 
(1, 13), -- Foie gras : sulfites 
(2, 1),  -- Suprême de volaille : gluten 
(2, 2),  -- Suprême de volaille : lait
(3, 1),  -- Bûche : gluten
(3, 2),  -- Bûche : lait
(3, 3),  -- Bûche : œufs
(3, 6),  -- Bûche : soja 
(4, 3),  -- Asperges : œufs 
(4, 2),  -- Asperges : lait 
(5, 2),  -- Gigot : lait 
(6, 1),  -- Nid de Pâques : gluten
(6, 2),  -- Nid de Pâques : lait
(6, 3),  -- Nid de Pâques : œufs
(6, 4);  -- Nid de Pâques : fruits à coque 

INSERT INTO image_menu (chemin, menu_id) VALUES 
('assets/images/foie_gras.jpg', 1),
('assets/images/supreme_volaille.jpg', 1),
('assets/images/buche_trois_chocolats.jpg', 1),
('assets/images/asperges_vertes.jpg', 1),
('assets/images/asperges_vertes.jpg', 2),
('assets/images/gigot_agneau_roti.jpg', 2),
('assets/images/nid_pques_chocolat.jpg', 2);

INSERT INTO horaire (jour, heure_ouverture, heure_fermeture) VALUES 
('Lundi', '09:00:00', '22:00:00'),
('Mardi', '09:00:00', '22:00:00'),
('Mercredi', '09:00:00', '22:00:00'),
('Jeudi', '09:00:00', '22:00:00'),
('Vendredi', '09:00:00', '22:00:00'),
('Samedi', '09:00:00', '22:00:00'),
('Dimanche', NULL, NULL);

INSERT INTO commande (utilisateur_id, menu_id, nom, prenom, email, gsm, adresse_prestation, ville, kilometres, date_prestation, heure, lieu, nombre_personnes, prix_menu, prix_livraison, remise, statut_actuel, materiel_prete, motif_annulation, mode_contact_annulation) VALUES 
(3, 1, 'Dubois', 'Pierre', 'pierre.dubois@example.com', '06 13 14 15 16', '101 Rue de la Paix', 'Bordeaux', 0, '2026-12-25', '12:00:00', 'Bordeaux', 4, 45.00, 0.00, 0.00, 'terminee', 0, NULL, NULL),
(4, 2, 'Durand', 'Marie', 'marie.durand@example.com', '06 17 18 19 20', '123 Rue de la Paix', 'Saint-Augustin', 20, '2026-12-25', '12:00:00', 'Saint-Augustin', 4, 39.90, 5.00, 0.00, 'en attente', 0, NULL, NULL);

INSERT INTO historique_statut (commande_id, statut_actuel, date_heure) VALUES
(1, 'terminee', '2026-10-15 19:30:00');

INSERT INTO avis (commande_id, note, commentaire, etat) VALUES
(1, 5, 'Excellente prestation, nous recommanderons.', 'valide');
