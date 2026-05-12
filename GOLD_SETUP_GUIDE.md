# Guide d'intégration - Fonctionnalité Gold

## 📋 Résumé de la mise en place

### Fichiers créés/modifiés:

1. **BDD Modifications**
   - `Diet/bdd_modified.sql` - Schéma SQL complet avec nouvelles tables/colonnes
   
2. **Migration CodeIgniter**
   - `app/Database/Migrations/2026-05-11-100000_AddGoldFeature.php`
   
3. **Modèles**
   - `app/Models/GoldConfigModel.php` - Gestion config + calcul remise
   
4. **Contrôleurs**
   - `app/Controllers/Admin/GoldController.php` - CRUD configuration Gold
   
5. **Vues**
   - `app/Views/admin/gold/index.php` - Interface admin pour gérer Gold
   
6. **Routes**
   - Mise à jour de `app/Config/Routes.php` avec routes Gold

---

## 🚀 Étapes de déploiement

### 1. Appliquer la migration

```bash
cd /home/iantra/Documents/My_Docs/S4/SI/ITU-S4-P18-PROJET
php spark migrate
```

Cela va:
- Ajouter colonne `gold_depuis` à table `utilisateur`
- Créer table `gold_config` (avec valeur par défaut: 29.99€, 15% remise)
- Créer table `payments` (historique paiements)

### 2. Vérifier la base de données

```sql
-- Vérifier colonnes utilisateur
DESCRIBE utilisateur;

-- Vérifier config Gold
SELECT * FROM gold_config;

-- Vérifier table payments
DESCRIBE payments;
```

### 3. Accéder à l'interface admin

- URL: `http://localhost:8080/admin/gold`
- Authentification: admin uniquement (filtre `admin`)
- Permission: Lire et modifier la configuration Gold

---

## ⚙️ Structure des données

### Table `gold_config`
```sql
id          INT (PK)
prix        DECIMAL(10,2) - Prix Gold en EUR (défaut: 29.99)
remise_pct  INT           - % remise appliqué (défaut: 15)
actif       TINYINT(1)    - 1=actif, 0=inactif
created_at  DATETIME
updated_at  DATETIME
```

### Table `payments`
```sql
id        INT UNSIGNED (PK)
user_id   INT (FK)         - Référence utilisateur
product   VARCHAR(50)      - Type: 'gold'
created_at DATETIME        - Date paiement
```

### Colonne `utilisateur.gold_depuis`
```sql
gold_depuis DATETIME NULL  - Date d'activation Gold (NULL = non-Gold)
```

---

## 🔧 Utilisation du modèle

### Dans vos contrôleurs

```php
use App\Models\GoldConfigModel;

$goldModel = new GoldConfigModel();

// Obtenir config active
$config = $goldModel->getActiveConfig();
// Résultat: ['id' => 1, 'prix' => 29.99, 'remise_pct' => 15, ...]

// Appliquer remise à un prix
$basePrice = 10.00;  // EUR
$isUserGold = true;
$finalPrice = $goldModel->applyDiscount($basePrice, $isUserGold);
// Résultat: 8.50 (10.00 * 0.85)

// Mettre à jour config
$goldModel->updateConfig(35.00, 20);  // Nouveau prix 35€, 20% remise
```

---

## 📱 Interface Admin

### Champs éditables:
1. **Prix Gold (€)** - Montant unique que l'utilisateur paye
2. **Remise (%)** - Pourcentage appliqué à tous les régimes

### Affichage:
- Aperçu du prix actuel et remise
- Dernière modification (date/heure)
- Statut (Actif/Inactif)

### Sécurité:
- Authentification admin requise
- Validation côté serveur
- CSRF token (protection POST)
- Réponse JSON avec gestion erreurs

---

## 🎯 Logique métier implémentée

1. **Activation Gold**
   - Utilisateur paie prix fixe = paiement enregistré
   - `utilisateur.est_gold = 1`, `gold_depuis = NOW()`
   - Enregistrement dans table `payments`

2. **Application remise**
   - Calcul: `prix_final = prix_base * (1 - remise_pct/100)`
   - Arrondi à 2 décimales (EUR)
   - Appliqué à TOUS les régimes pour utilisateurs Gold

3. **Idempotence**
   - Un utilisateur ne peut pas payer Gold 2 fois
   - Historique conservé dans `payments` pour audit

---

## ✅ Checklist intégration

- [ ] Migration appliquée (`php spark migrate`)
- [ ] Vérifier tables créées en BDD
- [ ] Accéder à `/admin/gold` (admin connecté)
- [ ] Tester modification prix Gold
- [ ] Tester modification remise %
- [ ] Vérifier application remise dans régimes (prochaine étape)

---

## 📌 Prochaines étapes

1. **Intégrer remise dans affichage régimes**
   - Afficher prix original vs prix Gold
   - Utiliser `GoldConfigModel::applyDiscount()`

2. **Paiement (Stripe/PayPal)**
   - Créer endpoint initiation paiement
   - Gérer webhook de confirmation
   - Incrémenter `utilisateur.est_gold` après webhook

3. **Tests**
   - Unitaire: calcul remise
   - Intégration: flow paiement → activation Gold

---

## 🐛 Dépannage

### Migration ne s'applique pas?
```bash
# Vérifier statut
php spark migrate:status

# Rollback si besoin
php spark migrate:rollback
```

### GoldConfigModel non trouvé?
- Vérifier chemin: `app/Models/GoldConfigModel.php`
- Vérifier namespace: `namespace App\Models;`

### Routes admin non accessible?
- Vérifier filtrer auth admin: `$routes->group('admin', ['filter' => 'admin'])`
- Vérifier session user a `role = 'admin'`

---

**Dernière modification**: 11 mai 2026
