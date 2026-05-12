CREATE DATABASE IF NOT EXISTS gestionsante;
USE gestionsante;
-- ============================================================
-- Script SQL - Application Régime Alimentaire (MODIFIÉ)
-- ============================================================

-- ------------------------------------------------------------
-- 1. USER ROLE
-- ------------------------------------------------------------
CREATE TABLE user_role (
    id      INT PRIMARY KEY AUTO_INCREMENT,
    role    ENUM('admin', 'utilisateur') NOT NULL DEFAULT 'utilisateur'
);

-- ------------------------------------------------------------
-- 2. UTILISATEUR (MODIFIÉ: ajout gold_depuis)
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
    gold_depuis     DATETIME NULL,                -- NEW: date d'activation Gold
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
-- 5. REGIME
-- ------------------------------------------------------------
CREATE TABLE regime (
    id           INT PRIMARY KEY AUTO_INCREMENT,
    nom          VARCHAR(150) NOT NULL,
    pct_viande   DECIMAL(5,2) NOT NULL,           -- pourcentage viande
    pct_poisson  DECIMAL(5,2) NOT NULL,           -- pourcentage poisson
    pct_volaille DECIMAL(5,2) NOT NULL           -- pourcentage volaille
);

-- ------------------------------------------------------------
-- 6. REGIME DETAIL
-- ------------------------------------------------------------
CREATE TABLE regime_detail (
    id              INT PRIMARY KEY AUTO_INCREMENT,
    id_regime       INT NOT NULL,                   
    duree           INT NOT NULL,                 -- en jours
    prix            DECIMAL(10,2) NOT NULL,       -- prix regime/jours (en EUR)
    variation_poids DECIMAL(5,2) NOT NULL,        -- positif = prise, négatif = perte
    FOREIGN KEY (id_regime) REFERENCES regime(id)
);

-- ------------------------------------------------------------
-- 7. SPORT
-- ------------------------------------------------------------
CREATE TABLE sport (
    id  INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(150) NOT NULL,
    apport_poids INT NOT NULL DEFAULT 0           -- +1 = prise de poids, -1 = perte de poids
);

-- ------------------------------------------------------------
-- 8. OBJECTIF
-- ------------------------------------------------------------
CREATE TABLE objectif (
    id                  INT PRIMARY KEY AUTO_INCREMENT,
    id_utilisateur      INT NOT NULL,
    id_type_objectif    INT NOT NULL,
    poids_initial       DECIMAL(5,2),
    objectif_poids      DECIMAL(5,2),  
    regime_id           INT NOT NULL,
    sport_id            INT, 
    IMC_initial         DECIMAL(5,2),
    duree_objectif      INT NOT NULL,
    prix_total          DECIMAL(10,2) NOT NULL,
    date_debut          DATE,
    FOREIGN KEY (regime_id)   REFERENCES regime(id),
    FOREIGN KEY (sport_id)    REFERENCES sport(id),
    FOREIGN KEY (id_utilisateur)   REFERENCES utilisateur(id),
    FOREIGN KEY (id_type_objectif) REFERENCES type_objectif(id)
);

-- ------------------------------------------------------------
-- 9. CODE ARGENT
-- ------------------------------------------------------------
CREATE TABLE code_argent (
    id              INT PRIMARY KEY AUTO_INCREMENT,
    code            VARCHAR(50) NOT NULL UNIQUE,
    valeur          DECIMAL(10,2) NOT NULL,
    est_valide      TINYINT(1) NOT NULL DEFAULT 1,
    id_utilisateur  INT,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id)
);

-- ============================================================
-- NEW TABLES - GOLD FUNCTIONALITY
-- ============================================================

-- ------------------------------------------------------------
-- 10. GOLD CONFIG (configurable par admin)
-- ------------------------------------------------------------
CREATE TABLE gold_config (
    id              INT PRIMARY KEY AUTO_INCREMENT,
    prix            DECIMAL(10,2) NOT NULL DEFAULT 29.99,  -- en EUR
    remise_pct      INT NOT NULL DEFAULT 15,               -- pourcentage remise (15%)
    actif           TINYINT(1) NOT NULL DEFAULT 1,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert valeur par défaut
INSERT INTO gold_config (prix, remise_pct, actif) VALUES (29.99, 15, 1);

-- ------------------------------------------------------------
-- 11. PAYMENTS (historique paiements Gold)
-- ------------------------------------------------------------
CREATE TABLE payments (
    id              INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id         INT NOT NULL,
    product         VARCHAR(50) NOT NULL,         -- ex: 'gold'
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES utilisateur(id) ON DELETE CASCADE,
    INDEX (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- DONNEES MINIMALES
-- ============================================================

-- Rôles
INSERT INTO user_role (role) VALUES ('admin'), ('utilisateur');
 
-- Mots de passe hashés avec password_hash() PHP (password : pass1234)
-- Admin (mdp : admin1234)
INSERT INTO utilisateur (id_role, nom, email, mot_de_passe, date_naissance, genre, poids_actuel, taille, est_gold, gold_depuis, solde_monnaie) VALUES
(1, 'Admin Système', 'admin@app.com', '$2y$10$8QvYv3mCNODsouIDZ5JNaOkuBNsFHDFTFmJzme9sMICqvFzm5BXZS', '1990-01-01', 'homme', 75.00, 1.75, 0, NULL, 0.00);
 
-- 5 utilisateurs (mdp : pass1234)
INSERT INTO utilisateur (id_role, nom, email, mot_de_passe, date_naissance, genre, poids_actuel, taille, est_gold, gold_depuis, solde_monnaie) VALUES
(2, 'Alice Dupont',  'alice@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1995-03-12', 'femme', 72.00, 1.65, 0, NULL, 20.00),
(2, 'Bob Martin',    'bob@mail.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1988-07-22', 'homme', 95.00, 1.80, 1, '2026-05-01 10:30:00', 50.00),
(2, 'Clara Rabe',    'clara@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2000-11-05', 'femme', 50.00, 1.60, 0, NULL, 10.00),
(2, 'David Rakoto',  'david@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1992-06-18', 'homme', 85.00, 1.78, 0, NULL, 0.00),
(2, 'Eva Rasolofo',  'eva@mail.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1998-09-30', 'femme', 60.00, 1.70, 0, NULL, 30.00);
 
-- 5 régimes
INSERT INTO regime (nom, pct_viande, pct_poisson, pct_volaille) VALUES
('Régime Méditerranéen',  20.00, 50.00, 30.00),
('Régime Masika be',      20.00, 50.00, 30.00),
('Régime Hyperprotéiné',  50.00, 20.00, 30.00),
('Régime avoine',         50.00, 20.00, 30.00),
('Régime Équilibré',      30.00, 30.00, 40.00),
('Régime gras',           30.00, 30.00, 40.00),
('Régime Minceur',        15.00, 45.00, 40.00),
('Régime végétarien',     15.00, 45.00, 40.00),
('Régime Prise de masse', 40.00, 20.00, 40.00),
('Régime de viande MG',   40.00, 20.00, 40.00);
 
INSERT INTO regime_detail (id_regime, duree, prix, variation_poids) VALUES
(1, 14, 9.99, -5.00),      
(2, 20, 6, -6.00),      
(3, 9, 14, 3.00),      
(4, 13, 3, 7.00),      
(5, 5, 3, -1.50),      
(6, 4, 6, -5.00),      
(7, 26, 2, -4.00),      
(7, 27, 12, -8.50),      
(8, 26, 5.5, 4.00),       
(9, 25, 16, 8.00);       

-- 5 sports
INSERT INTO sport (nom, apport_poids) VALUES
('Course à pied', -1),
('Natation', -1),
('Vélo', -1),
('Musculation', 1),
('basketball', -1),
('Yoga', 0);
 
-- 15 codes argent
INSERT INTO code_argent (code, valeur, est_valide) VALUES
('933772902550071',  50.00, 1),
('458375423622141', 10.00, 1),
('870855474463671', 15.00, 1),
('986976544918694',  50.00, 1),
('724856515746817', 20.00, 1),
('398993629620321', 10.00, 1),
('658883308381427', 250.00, 1),
('533216367186595',  5.00, 1),
('973053118021121', 100.00, 0),
('830064473687696', 15.00, 1),
('159557106512045',  50.00, 1),
('882953932288321', 20.00, 1),
('934612380418957', 100.00, 1),
('642285810966344',  50.00, 1),
('320554871504056', 50.00, 1);

-- Historique paiements (exemple)
INSERT INTO payments (user_id, product, created_at) VALUES
(3, 'gold', '2026-05-01 10:30:00');
