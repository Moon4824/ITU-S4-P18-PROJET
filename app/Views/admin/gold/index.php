<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<?php $config = $config ?? ['prix' => 29.99, 'remise_pct' => 15, 'actif' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]; ?>

<div class="page-header">
    <div>
        <h2>Configuration Gold 💎</h2>
        <div class="breadcrumb">Gérez les paramètres de l'option Gold</div>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Carte principale de configuration -->
    <article class="dashboard-card dashboard-card-wide">
        <h3>Paramètres Gold</h3>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                ✓ <?= session()->getFlashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                ✗ <?= session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form id="goldConfigForm" method="POST" action="<?= base_url('admin/gold/update') ?>" style="max-width: 600px;">
            <?= csrf_field(); ?>

            <!-- Prix Gold -->
            <div style="margin-bottom: 25px;">
                <label for="prix" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                    Prix Gold (€)
                </label>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="background: #f0f0f0; padding: 10px 12px; border-radius: 4px 0 0 4px; border: 1px solid #ddd; font-weight: 600;">€</span>
                    <input 
                        type="number" 
                        id="prix" 
                        name="prix" 
                        value="<?= $config['prix'] ?? '29.99'; ?>"
                        step="0.01"
                        min="0"
                        required
                        style="flex: 1; padding: 10px 12px; border: 1px solid #ddd; border-radius: 0 4px 4px 0; font-size: 16px;"
                    />
                </div>
                <small style="display: block; margin-top: 6px; color: #666;">Prix unique à payer pour accès à vie</small>
            </div>

            <!-- Remise -->
            <div style="margin-bottom: 25px;">
                <label for="remise_pct" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                    Remise (%)
                </label>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <input 
                        type="number" 
                        id="remise_pct" 
                        name="remise_pct" 
                        value="<?= $config['remise_pct'] ?? '15'; ?>"
                        min="0"
                        max="100"
                        required
                        style="flex: 1; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px 0 0 4px; font-size: 16px;"
                    />
                    <span style="background: #f0f0f0; padding: 10px 12px; border-radius: 0 4px 4px 0; border: 1px solid #ddd;">%</span>
                </div>
                <small style="display: block; margin-top: 6px; color: #666;">Appliqué à tous les régimes pour utilisateurs Gold</small>
            </div>

            <!-- Aperçu -->
            <div style="background: #e7f3ff; border: 1px solid #b3d9ff; padding: 16px; border-radius: 4px; margin-bottom: 25px;">
                <h6 style="margin: 0 0 12px 0; font-weight: 600; color: #0066cc;">📊 Aperçu</h6>
                <p style="margin: 8px 0; font-size: 14px;">
                    <strong>Prix à payer:</strong> <span id="prixDisplay" style="color: #0066cc; font-weight: 600;"><?= number_format($config['prix'] ?? 29.99, 2, '.', ''); ?></span> €
                </p>
                <p style="margin: 8px 0; font-size: 14px;">
                    <strong>Remise appliquée:</strong> <span id="remiseDisplay" style="color: #0066cc; font-weight: 600;"><?= (int)($config['remise_pct'] ?? 15); ?></span> % sur tous les régimes
                </p>
            </div>

            <!-- Boutons -->
            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <a href="<?= base_url('admin'); ?>" style="padding: 10px 24px; background: #f0f0f0; color: #333; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; cursor: pointer; font-weight: 500;">
                    Annuler
                </a>
                <button type="submit" style="padding: 10px 24px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 500; font-size: 14px;">
                    💾 Enregistrer
                </button>
            </div>
        </form>
    </article>

    <!-- Informations -->
    <article class="dashboard-card">
        <h3>Informations</h3>
        <div style="background: #f9f9f9; padding: 16px; border-radius: 4px;">
            <p style="margin: 10px 0; font-size: 14px;">
                <strong>Dernière modification:</strong><br>
                <?= date('d/m/Y à H:i', strtotime($config['updated_at'] ?? 'now')); ?>
            </p>
            <p style="margin: 10px 0; font-size: 14px;">
                <strong>Créé le:</strong><br>
                <?= date('d/m/Y à H:i', strtotime($config['created_at'] ?? 'now')); ?>
            </p>
            <p style="margin: 10px 0; font-size: 14px;">
                <strong>Statut:</strong><br>
                <span style="display: inline-block; padding: 4px 12px; border-radius: 12px; background: <?= $config['actif'] ? '#d4edda' : '#f8d7da'; ?>; color: <?= $config['actif'] ? '#155724' : '#721c24'; ?>; font-weight: 600; font-size: 12px;">
                    <?= $config['actif'] ? '✓ Actif' : '✗ Inactif'; ?>
                </span>
            </p>
        </div>
    </article>

    <!-- Guide d'utilisation -->
    <article class="dashboard-card">
        <h3>Guide d'utilisation</h3>
        <ul style="list-style: none; padding: 0;">
            <li style="margin: 10px 0; padding: 10px; background: #f0f8ff; border-left: 3px solid #0066cc;">
                💰 <strong>Prix Gold:</strong> Le montant que les utilisateurs vont payer une seule fois
            </li>
            <li style="margin: 10px 0; padding: 10px; background: #f0f8ff; border-left: 3px solid #0066cc;">
                📉 <strong>Remise:</strong> Réduction en % appliquée à tous les régimes
            </li>
            <li style="margin: 10px 0; padding: 10px; background: #f0f8ff; border-left: 3px solid #0066cc;">
                🔄 <strong>Exemple:</strong> Régime à 10€ → Gold obtient 10€ × 0.85 = 8.50€
            </li>
            <li style="margin: 10px 0; padding: 10px; background: #f0f8ff; border-left: 3px solid #0066cc;">
                ♾️ <strong>Accès:</strong> Illimité une fois Gold activé
            </li>
        </ul>
    </article>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const prixInput = document.getElementById('prix');
    const remiseInput = document.getElementById('remise_pct');
    const prixDisplay = document.getElementById('prixDisplay');
    const remiseDisplay = document.getElementById('remiseDisplay');

    // Mettre à jour l'aperçu en temps réel
    prixInput.addEventListener('input', function() {
        prixDisplay.textContent = parseFloat(this.value || 0).toFixed(2);
    });

    remiseInput.addEventListener('input', function() {
        remiseDisplay.textContent = this.value || '0';
    });

    // Gestion du formulaire AJAX
    const form = document.getElementById('goldConfigForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('<?= base_url('admin/gold/update'); ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Afficher notification de succès
                const alertDiv = document.createElement('div');
                alertDiv.style.cssText = 'background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;';
                alertDiv.innerHTML = `✓ ${data.message}`;
                form.parentElement.insertBefore(alertDiv, form);

                // Recharger après 2 secondes
                setTimeout(() => window.location.reload(), 2000);
            } else {
                alert('Erreur: ' + (data.error || 'Une erreur s\'est produite'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur s\'est produite');
        });
    });
});
</script>

<?= $this->endSection() ?>
