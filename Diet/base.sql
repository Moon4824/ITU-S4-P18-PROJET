CREATE DATABASE IF NOT EXISTS gestionsante;
USE gestionsante;
-- ============================================================
-- Script SQL - Application Régime Alimentaire
-- ============================================================

-- ------------------------------------------------------------
-- 1. USER ROLE
-- ------------------------------------------------------------
CREATE TABLE user_role (
    id      INT PRIMARY KEY AUTO_INCREMENT,
    role    ENUM('admin', 'utilisateur') NOT NULL DEFAULT 'utilisateur'
);

-- ------------------------------------------------------------
-- 2. UTILISATEUR
-- ------------------------------------------------------------
CREATE TABLE utilisateur (
    id              INT PRIMARY KEY AUTO_INCREMENT,
    id_role         INT NOT NULL UNIQUE,
    nom             VARCHAR(100) NOT NULL,
    email           VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe    VARCHAR(255) NOT NULL,
    date_naissance  DATE NOT NULL,
    genre           ENUM('homme', 'femme') NOT NULL,
    poids_actuel    DECIMAL(5,2) NOT NULL,
    taille          DECIMAL(4,2) NOT NULL,        -- en mètres ex: 1.75
    est_gold        TINYINT(1) NOT NULL DEFAULT 0,
    solde_monnaie   DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    FOREIGN KEY (id_role) REFERENCES user_role(id)
);

-- ------------------------------------------------------------
-- 3. INTERPRETATION IMC
-- ------------------------------------------------------------
CREATE TABLE interpretation_imc (
    id      INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(100) NOT NULL,
    min     DECIMAL(5,2),                         -- NULL = pas de borne inférieure
    max     DECIMAL(5,2)                          -- NULL = pas de borne supérieure
);

INSERT INTO interpretation_imc (libelle, min, max) VALUES
('Sous-poids', NULL,  18.49),
('Normal',     18.50, 24.99),
('Surpoids',   25.00, 29.99),
('Obésité',    30.00, NULL );

-- ------------------------------------------------------------
-- 4. TYPE OBJECTIF
-- ------------------------------------------------------------
CREATE TABLE type_objectif (
    id      INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(100) NOT NULL
);

INSERT INTO type_objectif (libelle) VALUES
('Augmenter poids'),
('Réduire poids'),
('Atteindre IMC idéal');

-- ------------------------------------------------------------
-- 5. OBJECTIF
-- ------------------------------------------------------------
CREATE TABLE objectif (
    id                  INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur      INT NOT NULL,
    id_type_objectif    INT NOT NULL,
    valeur_poids        DECIMAL(5,2),             -- NULL si type = IMC idéal
    FOREIGN KEY (id_utilisateur)   REFERENCES utilisateur(id),
    FOREIGN KEY (id_type_objectif) REFERENCES type_objectif(id)
);

-- ------------------------------------------------------------
-- 6. REGIME
-- ------------------------------------------------------------
CREATE TABLE regime (
    id           INT PRIMARY KEY AUTO_INCREMENT,
    nom          VARCHAR(150) NOT NULL,
    pct_viande   DECIMAL(5,2) NOT NULL,           -- pourcentage viande
    pct_poisson  DECIMAL(5,2) NOT NULL,           -- pourcentage poisson
    pct_volaille DECIMAL(5,2) NOT NULL           -- pourcentage volaille
);

-- ------------------------------------------------------------
-- 7. REGIME DETAIL
-- ------------------------------------------------------------
CREATE TABLE regime_detail (
    id              INT PRIMARY KEY AUTO_INCREMENT,
    id_regime       INT NOT NULL,
    duree           INT NOT NULL,                 -- en jours
    prix            DECIMAL(10,2) NOT NULL,
    variation_poids DECIMAL(5,2) NOT NULL,        -- positif = prise, négatif = perte
    FOREIGN KEY (id_regime) REFERENCES regime(id)
);

-- ------------------------------------------------------------
-- 8. SPORT
-- ------------------------------------------------------------
CREATE TABLE sport (
    id  INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(150) NOT NULL
);

-- ------------------------------------------------------------
-- 9. CODE ARGENT
-- ------------------------------------------------------------
CREATE TABLE code_argent (
    id              INT PRIMARY KEY AUTO_INCREMENT,
    code            VARCHAR(50) NOT NULL UNIQUE,
    valeur          DECIMAL(10,2) NOT NULL,
    est_valide      TINYINT(1) NOT NULL DEFAULT 1,
    id_utilisateur  INT,                          -- NULL si pas encore utilisé
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id)
);

-- ------------------------------------------------------------
-- 10. SUGGESTION PROGRAMME
-- ------------------------------------------------------------
CREATE TABLE suggestion_programme (
    id              INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur  INT NOT NULL,
    id_objectif     INT NOT NULL,
    date_debut      DATE NOT NULL,
    duree_programme INT NOT NULL,                 -- en jours
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id),
    FOREIGN KEY (id_objectif)    REFERENCES objectif(id)
);

-- ------------------------------------------------------------
-- 11. SUGGESTION PROGRAMME DETAIL
-- ------------------------------------------------------------
CREATE TABLE suggestion_programme_detail (
    id              INT PRIMARY KEY AUTO_INCREMENT,
    id_suggestion   INT NOT NULL,
    id_regime       INT NOT NULL,
    id_sport        INT NOT NULL,
    FOREIGN KEY (id_suggestion) REFERENCES suggestion_programme(id),
    FOREIGN KEY (id_regime)     REFERENCES regime(id),
    FOREIGN KEY (id_sport)      REFERENCES sport(id)
);

-- ============================================================
-- DONNEES MINIMALES
-- ============================================================
 
-- Rôles
INSERT INTO user_role (role) VALUES ('admin'), ('utilisateur'), ('utilisateur'), ('utilisateur'), ('utilisateur'), ('utilisateur');
 
-- Mots de passe hashés avec password_hash() PHP (password : pass1234)
-- Le hashage est fait côté PHP dans le code, ces valeurs sont des hash bcrypt précalculés
-- Admin (mdp : admin1234)
INSERT INTO utilisateur (id_role, nom, email, mot_de_passe, date_naissance, genre, poids_actuel, taille, est_gold, solde_monnaie) VALUES
(1, 'Admin Système', 'admin@app.com', '$2y$10$0MbgG5ykCS1deZaFdNBH8O9lS8CYKAICA6j2LbqTySNqEl1Kshhg.', '1990-01-01', 'homme', 75.00, 1.75, 0, 0.00);
 
-- 5 utilisateurs (mdp : pass1234)
INSERT INTO utilisateur (id_role, nom, email, mot_de_passe, date_naissance, genre, poids_actuel, taille, est_gold, solde_monnaie) VALUES
(2, 'Alice Dupont',  'alice@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1995-03-12', 'femme', 72.00, 1.65, 0, 20.00),
(3, 'Bob Martin',    'bob@mail.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1988-07-22', 'homme', 95.00, 1.80, 1, 50.00),
(4, 'Clara Rabe',    'clara@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2000-11-05', 'femme', 50.00, 1.60, 0, 10.00),
(5, 'David Rakoto',  'david@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1992-06-18', 'homme', 85.00, 1.78, 0, 0.00),
(6, 'Eva Rasolofo',  'eva@mail.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1998-09-30', 'femme', 60.00, 1.70, 0, 30.00);
 
-- 5 régimes
INSERT INTO regime (nom, pct_viande, pct_poisson, pct_volaille) VALUES
('Régime Méditerranéen',  20.00, 50.00, 30.00),
('Régime Hyperprotéiné',  50.00, 20.00, 30.00),
('Régime Équilibré',      30.00, 30.00, 40.00),
('Régime Minceur',        15.00, 45.00, 40.00),
('Régime Prise de masse', 40.00, 20.00, 40.00);
 
-- Détails prix/durée par régime
INSERT INTO regime_detail (id_regime, duree, prix, variation_poids) VALUES
(1, 7,  15.00, -0.50), (1, 14, 25.00, -1.00), (1, 30, 45.00, -2.50),
(2, 7,  18.00,  0.80), (2, 14, 32.00,  1.50),  (2, 30, 55.00,  3.00),
(3, 7,  12.00, -0.30), (3, 14, 20.00, -0.80), (3, 30, 38.00, -1.50),
(4, 7,  14.00, -0.80), (4, 14, 24.00, -1.80), (4, 30, 42.00, -3.50),
(5, 7,  20.00,  1.00), (5, 14, 35.00,  2.00),  (5, 30, 60.00,  4.50);
 
-- 5 sports
INSERT INTO sport (nom) VALUES
('Course à pied'),
('Natation'),
('Vélo'),
('Musculation'),
('Yoga');
 
-- 15 codes argent (15 chiffres, comme carte de recharge)
INSERT INTO code_argent (code, valeur, est_valide) VALUES
('933772902550071',  5.00, 1),
('458375423622141', 10.00, 1),
('870855474463671', 15.00, 1),
('986976544918694',  5.00, 1),
('724856515746817', 20.00, 1),
('398993629620321', 10.00, 1),
('658883308381427', 25.00, 1),
('533216367186595',  5.00, 1),
('973053118021121', 10.00, 1),
('830064473687696', 15.00, 1),
('159557106512045',  5.00, 1),
('882953932288321', 20.00, 1),
('934612380418957', 10.00, 1),
('642285810966344',  5.00, 1),
('320554871504056', 50.00, 1);
 
