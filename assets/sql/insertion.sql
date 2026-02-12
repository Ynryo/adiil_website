-- Ajout des roles
INSERT INTO ROLE (nom_role, p_log_role, p_boutique_role, p_reunion_role, p_utilisateur_role, p_grade_role, p_roles_role, p_actualite_role, p_evenements_role, p_comptabilite_role, p_achats_role, p_moderation_role) VALUES
('referent', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
('president', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
('comptable', 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0),
('superette', 0, 1, 0, 0, 0, 0, 0, 0, 0, 1, 0),
('animateur', 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0),
('infos', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0);

-- Insertion des membres
INSERT INTO MEMBRE (nom_membre, prenom_membre, email_membre, password_membre, xp_membre, discord_token_membre, pp_membre, tp_membre) VALUES
('RUFFAULT--RAVENEL', 'Gemino', 'gemino.ruffault@example.com', 'password1', 50, NULL, 'http://files.bdeinfo.fr/fes2fse1f21se.jpg', '11A'),
('HANNIER', 'Axelle', 'axelle.hannier@example.com', 'password2', 18, NULL, '2.jpg', '12C'),
('DAUVERGNE', 'Julien', 'julien.dauvergne@example.com', 'password3', 0, 'g4rd64g6rd4g8f4e64h5bv231h5th44g5ht6h87yj8ty6', 'http://files.bdeinfo.fr/gprdgrd5.jpg','31A'),
('DELAYE', 'Baptiste', 'baptiste.delahay@example.com', 'password4', 0, NULL, 'http://files.bdeinfo.fr/h5th42fth.jpg', '32D'),
('VIEILLARD', 'Nathalie', 'nathalie.vieillard@example.com', 'password5', 11, NULL, 'http://files.bdeinfo.fr/jygjgy56yjg.jpg', NULL),
('HAVARD', 'Barnabe', 'barnabe.havard@example.com', 'password6', 0, 'kiuilui4l8iul654hg2g', 'http://files.bdeinfo.fr/fesifo45ht45h.jpg', '11A'),
('FEVRIER', 'Theo', 'theo.fevrier@example.com', 'password7', 0, NULL, 'http://files.bdeinfo.fr/gr68grg.jpg', NULL),
('GOUIN', 'Tom', 'tom.gouin@example.com', 'password8', 12, NULL, 'http://files.bdeinfo.fr/fesf4556fe.jpg', NULL),
('CONGNARD', 'Evann', 'evann.congnard@example.com', 'password9', 0, NULL, 'http://files.bdeinfo.fr/2f1e2sfs.jpg', '31A'),
('LE COZ', 'Erwan', 'erwan.lecoz@example.com', 'password10', 0, NULL, 'http://files.bdeinfo.fr/fesf45ef6s4fes6.jpg', '31B');

-- Definition des roles
INSERT INTO ASSIGNATION (id_membre, id_role) VALUES
(5, 1),  -- Nathalie Vieillard devient referent
(1, 2),  -- Gemino RUFFAULT--RAVENEL devient president
(6, 6),  -- Barnabe HAVARD a le role "infos"
(3, 6),  -- Julien DAUVERGNE a le role "infos"
(6, 3);  -- Barnabe HAVARD a egalement le role "comptable"

-- Ajout des grades
INSERT INTO GRADE (reduction_grade, image_grade, prix_grade, description_grade, nom_grade) VALUES
(0, 'http://files.bdeinfo.fr/grade_fer.jpg', 5, 'Un grade de base en fer.', 'Fer'),
(0, 'http://files.bdeinfo.fr/grade_or.jpg', 10, 'Un grade supérieur en or.', 'Or'),
(10, 'http://files.bdeinfo.fr/grade_diamand.jpg', 13, 'Le grade ultime en diamant.', 'Diamant');

-- Insertion des adhésions
INSERT INTO ADHESION (date_adhesion, prix_adhesion, paiement_adhesion, id_membre, id_grade) VALUES
('2024-05-02 11:44:18', 13, 'TPE', 7, 3),	 -- Theo Fevrier achete le grade Diamant
('2024-05-02 11:44:18', 10, 'PayPal', 8, 2),  -- Tom Gouin achete le grade Diamant
('2019-11-18 10:34:09', 8, 'TPE', 2, 2),	 -- Axelle HANNIER achete le grade or, elle paye uniquement 8e car il coutait moins cher a l'epoque
('2024-05-02 11:44:18', 5, 'Especes', 10, 1); -- Erwan le Coz achete le grade Fer

-- Ajout des articles de la boutique
INSERT INTO ARTICLE (xp_article, nom_article, stock_article, image_article, reduction_article, prix_article) VALUES
(1, 'Canette de Coca', 50, 'http://files.bdeinfo.fr/coca.jpg', 0, 1.50),
(1, 'Coca Cherry', 30, 'http://files.bdeinfo.fr/coca_cherry.jpg', 0, 1.75),
(20, 'Lipton Ice Tea', 40, 'http://files.bdeinfo.fr/lipton.jpg', 0, 1.50),
(1, 'Formule Cafe', 20, 'http://files.bdeinfo.fr/_cafe.jpg', 0, 2.00),
(1, 'Bueno White', 25, 'http://files.bdeinfo.fr/_bueno_white.jpg', 1, 1.20),
(1, 'Bueno', 35, 'http://files.bdeinfo.fr/bueno.jpg', 1, 1.20),
(10, 'Snack Chips', 60, 'http://files.bdeinfo.fr/chips.jpg', 0, 1.00),
(1, 'Barre de Chocolat', 0, 'http://files.bdeinfo.fr/chocolat.jpg', 0, 1.50),
(1, 'Jus d Orange', 55, 'http://files.bdeinfo.fr/jus_orange.jpg', 0, 1.30),
(30, 'Volvic', 70, 'http://files.bdeinfo.fr/volvic.jpg', 0, 0.80);

-- Ajout des commandes
INSERT INTO COMMANDE (statut_commande, prix_commande, paiement_commande, date_commande, qte_commande, id_membre, id_article) VALUES
(1, 3.00, 'Carte de credit', NOW(), 2, 1, 1),  -- Gemino achete 2 canettes de Coca
(0, 3.50, 'Especes', NOW(), 1, 2, 2),          -- Axelle achete 1 Coca Cherry
(0, 2.00, 'TPE', NOW(), 1, 3, 3),              -- Julien achete 1 Lipton Ice Tea
(0, 4.00, 'PayPal', NOW(), 2, 4, 4),           -- Barnabe achete 2 Formule Cafe
(0, 1.20, 'Carte de credit', NOW(), 5, 2, 5),  -- Axelle achete 5 Bueno White
(0, 1.20, 'Especes', NOW(), 10, 6, 6),         -- Theo achete 10 Bueno
(0, 1.00, 'TPE', NOW(), 3, 7, 7),              -- Tom achete 3 Snack Chips
(0, 4.50, 'Carte de credit', NOW(), 2, 8, 8),  -- Erwan achete 2 Barres de Chocolat
(1, 2.60, 'PayPal', NOW(), 2, 9, 9),           -- Evann achete 2 Jus d Orange
(1, 1.60, 'Especes', NOW(), 4, 10, 10);       -- Baptiste achete 4 Volvic

-- Ajout des evenements
INSERT INTO EVENEMENT (nom_evenement, xp_evenement, places_evenement, prix_evenement, reductions_evenement, lieu_evenement, date_evenement) VALUES
('LAN Minecraft', 10, 100, 20, 1, 'Amphi 1', '2026-09-15 10:00:00'),
('Competition CSGO', 10, 200, 15, 1, 'Amphi 1', '2026-10-20 14:00:00'),
('Raclette', 10, 30, 25, 0, 'TD2', '2026-11-05 18:30:00'),
('Loup Garou', 10, 150, 10, 1, 'TD2', '2026-12-10 19:00:00'),
('LAN Mario Kart', 10, 1, 5, 1, 'Amphi 3', '2027-01-15 15:00:00'),
('Barbecue de l''ADIIL', 10, 300, 10, 1, 'Parking du Batiment Dep. Informatique', '2027-05-01 12:00:00'),
('Raclette 2', 10, 75, 12, 0, 'TD2', '2027-05-20 18:30:00'),
('Course de Caddie Carrefour', 10, 100, 10, 1, 'Carrefour', '2027-06-10 10:00:00'),
('Soiree Bar', 10, 200, 15, 1, 'Bar l''After Work', '2027-06-20 20:00:00'),
('Barbecue de Depart', 10, 50, 30, 0, 'Centre de Conferences', '2027-07-01 12:00:00'),
('Evenement passé pour les tests', 10, 50, 30, 0, 'Quelque part', '2023-07-01 12:00:00');

-- Ajout des inscriptions
INSERT INTO INSCRIPTION (id_membre, id_evenement, date_inscription, paiement_inscription, prix_inscription) VALUES
(1, 3, '2026-10-15 11:06:05', 'TPE', 25),
(4, 1, '2026-09-08 12:14:18', 'Paypal', 20),
(4, 6, '2027-04-26 09:04:05', 'Espece', 10),
(8, 5, '2027-01-13 17:26:32', 'Espece', 4.50),
(5, 10, '2027-06-15 14:31:56', 'TPE', 30),
(6, 8, '2026-05-12 8:56:01', 'Paypal', 10),
(7, 10, '2027-04-02 13:04:02', 'Carte de credit', 30),
(8, 1, '2026-09-13 16:16:45', 'TPE', 18),
(10, 9, '2026-06-19 10:08:00', 'TPE', 15),
(9, 7, '2027-05-05 13:02:18','Carte de credit', 12);

-- Ajout des actualites
INSERT INTO ACTUALITE (image_actualite, titre_actualite, contenu_actualite, date_actualite, id_membre) VALUES
('http://files.bdeinfo.fr/photoIntegration2024.jpg', 'Un soiree d''integration haute en couleur', 'Hier soir se tenait la soiree d''integration de notre chere BDE. Elle fut remarquable, nous en gardons un tres bon souvenir ! Merci a tous d''etre venu nombreux !', '2024-09-12 19:30:00', 1),
('http://files.bdeinfo.fr/photoNouvelleSalleEtude.jpg', 'Inauguration de la nouvelle salle d etude', 'La nouvelle salle d etude equipee de postes informatiques dernier cri est desormais ouverte a tous les etudiants. Venez la decouvrir !', '2024-09-18 10:15:00', 1),
('http://files.bdeinfo.fr/photoNouvelEquipementSport.jpg', 'Nouveau matariel sportif au gymnase', 'Le gymnase de l IUT a ete equipe de nouveaux appareils de musculation. Une bonne nouvelle pour les amateurs de sport.', '2024-09-25 14:45:00', 1),
('http://files.bdeinfo.fr/photoPartenariatEntreprise.jpg', 'Partenariat avec une entreprise locale', 'Un nouveau partenariat a ete signe entre l IUT et TechMayenne entreprise specialisee dans le developpement d applications web. De belles opportunites de stages en perspective.', '2024-10-01 09:00:00', 1),
('http://files.bdeinfo.fr/photoRenovationBibliotheque.jpg', 'Renovation de la bibliotheque', 'La bibliotheque de l IUT a subi une renovation complete et propose desormais plus d espace et de nouveaux ouvrages en informatique.', '2024-10-05 11:20:00', 1),
('http://files.bdeinfo.fr/photoResultatsConcoursTech.jpg', 'Resultats du concours de technologie', 'Les resultats du concours de technologie viennent de tomber ! Felicitations a tous les participants, et en particulier aux vainqueurs du departement informatique Theo et Alban.', '2024-10-10 16:00:00', 1),
('http://files.bdeinfo.fr/photoSemaineIntegration.jpg', 'Retour sur la semaine d integration', 'La semaine d integration s est achevee avec succes. Merci a tous ceux qui ont contribue a rendre ces moments inoubliables pour les nouveaux etudiants.', '2024-10-15 18:50:00', 1),
('http://files.bdeinfo.fr/photoNouveauSiteWeb.jpg', 'Lancement du nouveau site web du BDE', 'Le BDE est fier de vous annoncer le lancement de son nouveau site web, entierement repense pour faciliter l acces aux informations et evenements.', '2024-10-20 13:30:00', 1),
('http://files.bdeinfo.fr/photoCollecteVetements.jpg', 'Collecte de vetements reussie', 'La collecte de vetements organisee par le BDE a permis de rassembler plus de 200 kg de vetements qui seront distribues a des associations locales.', '2024-10-22 10:45:00', 1),
('http://files.bdeinfo.fr/photoConferenceInnovations.jpg', 'Conference sur les innovations technologiques', 'Une conference sur les innovations technologiques recentes s est tenue a l IUT. Des intervenants de renom ont partage leurs experiences avec les etudiants.', '2024-10-22 17:15:00', 1);

-- Ajout de la comptabilite
INSERT INTO COMPTABILITE (date_comptabilite, nom_comptabilite, url_comptabilite, id_membre) VALUES
('2024-03-05', 'Compta fev2024', 'http://files.bdeinfo.fr/comptaFev2024.xls', 6),
('2023-12-10', 'Compta nov2023', 'http://files.bdeinfo.fr/comptaNov2023.xls', 6),
('2024-01-07', 'Compta dec2023', 'http://files.bdeinfo.fr/comptaDec2023.xls', 6),
('2024-02-05', 'Compta janv2024', 'http://files.bdeinfo.fr/comptaJanv2024.xls', 6),
('2024-04-10', 'Compta mars2024', 'http://files.bdeinfo.fr/comptaMars2024.xls', 6),
('2024-05-07', 'Compta avril2024', 'http://files.bdeinfo.fr/comptaAvril2024.xls', 6),
('2024-06-10', 'Compta mai2024', 'http://files.bdeinfo.fr/comptaMai2024.xls', 6),
('2024-07-05', 'Compta juin2024', 'http://files.bdeinfo.fr/comptaJuin2024.xls', 6),
('2024-08-12', 'Compta juillet2024', 'http://files.bdeinfo.fr/comptaJuillet2024.xls', 6),
('2024-09-09', 'Compta aout2024', 'http://files.bdeinfo.fr/comptaAout2024.xls', 6);

-- Ajout des reunions
INSERT INTO REUNION (date_reunion, fichier_reunion, id_membre) VALUES
('2024-09-08', 'http://files.bdeinfo.fr/CR433.odt', 3),
('2024-09-15', 'http://files.bdeinfo.fr/CR434.odt', 5),
('2024-09-22', 'http://files.bdeinfo.fr/CR435.odt', 6),
('2024-09-29', 'http://files.bdeinfo.fr/CR436.odt', 3),
('2024-10-06', 'http://files.bdeinfo.fr/CR437.odt', 5),
('2024-10-13', 'http://files.bdeinfo.fr/CR438.odt', 6),
('2024-10-20', 'http://files.bdeinfo.fr/CR439.odt', 3),
('2024-10-27', 'http://files.bdeinfo.fr/CR440.odt', 5),
('2024-11-03', 'http://files.bdeinfo.fr/CR441.odt', 6),
('2024-11-10', 'http://files.bdeinfo.fr/CR442.odt', 3);

-- Ajout des medias
INSERT INTO MEDIA (url_media, date_media, id_membre, id_evenement) VALUES
('http://files.bdeinfo.fr/hjrehr.mp4', '2024-10-21', 3, 2),
('http://files.bdeinfo.fr/fhir.jpeg', '2024-10-21', 5, 2),
('http://files.bdeinfo.fr/uyjhghg.mp4', '2024-11-05', 6, 3),
('http://files.bdeinfo.fr/rtuhght.jpeg', '2024-09-17', 3, 1),
('http://files.bdeinfo.fr/ytraztru.mp4', '2024-12-13', 8, 4),
('http://files.bdeinfo.fr/rtghyy.jpeg', '2024-10-11', 6, 4),
('http://files.bdeinfo.fr/ythghtr.mp4', '2025-01-15', 3, 5),
('http://files.bdeinfo.fr/tuhyy.jpeg', '2024-09-18', 10, 1),
('http://files.bdeinfo.fr/reyhy.mp4', '2025-05-02', 6, 6),
('http://files.bdeinfo.fr/yryert.jpeg', '2024-05-02', 9, 6);