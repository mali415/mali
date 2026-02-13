<?php
require_once __DIR__ . '/../../app/config/db.php';
require_once __DIR__ . '/../../app/models/Machine.php';

$pdo = get_pdo();
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$machine = $id ? Machine::find($pdo, $id) : null;
if ($id && !$machine) {
    http_response_code(404);
    echo 'Makine bulunamadı.';
    exit;
}

require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1><?php echo $machine ? 'Makine Güncelle' : 'Makine Ekle'; ?></h1>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <form id="machineForm">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Kod *</label>
                <input type="text" class="form-control" name="code" required value="<?php echo $machine ? escape($machine['code']) : ''; ?>" />
              </div>
              <div class="form-group col-md-4">
                <label>Ad *</label>
                <input type="text" class="form-control" name="name" required value="<?php echo $machine ? escape($machine['name']) : ''; ?>" />
              </div>
              <div class="form-group col-md-4">
                <label>Durum *</label>
                <select class="form-control" name="status" required>
                  <?php
                    $status = $machine['status'] ?? '';
                  ?>
                  <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Aktif</option>
                  <option value="passive" <?php echo $status === 'passive' ? 'selected' : ''; ?>>Pasif</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Lokasyon</label>
                <input type="text" class="form-control" name="location" value="<?php echo $machine ? escape($machine['location'] ?? '') : ''; ?>" />
              </div>
              <div class="form-group col-md-4">
                <label>Tip</label>
                <input type="text" class="form-control" name="type" value="<?php echo $machine ? escape($machine['type'] ?? '') : ''; ?>" />
              </div>
              <div class="form-group col-md-4">
                <label>Kritiklik</label>
                <select class="form-control" name="criticality">
                  <?php $criticality = $machine['criticality'] ?? ''; ?>
                  <option value="">Seçiniz</option>
                  <option value="low" <?php echo $criticality === 'low' ? 'selected' : ''; ?>>Low</option>
                  <option value="med" <?php echo $criticality === 'med' ? 'selected' : ''; ?>>Med</option>
                  <option value="high" <?php echo $criticality === 'high' ? 'selected' : ''; ?>>High</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Üretici</label>
                <input type="text" class="form-control" name="manufacturer" value="<?php echo $machine ? escape($machine['manufacturer'] ?? '') : ''; ?>" />
              </div>
              <div class="form-group col-md-4">
                <label>Model</label>
                <input type="text" class="form-control" name="model" value="<?php echo $machine ? escape($machine['model'] ?? '') : ''; ?>" />
              </div>
              <div class="form-group col-md-4">
                <label>Seri No</label>
                <input type="text" class="form-control" name="serial_no" value="<?php echo $machine ? escape($machine['serial_no'] ?? '') : ''; ?>" />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Güç (kW)</label>
                <input type="number" step="0.01" class="form-control" name="power_kw" value="<?php echo $machine ? escape((string)($machine['power_kw'] ?? '')) : ''; ?>" />
              </div>
              <div class="form-group col-md-8">
                <label>Notlar</label>
                <input type="text" class="form-control" name="notes" value="<?php echo $machine ? escape($machine['notes'] ?? '') : ''; ?>" />
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Kaydet</button>
            <a href="/machines" class="btn btn-secondary">Vazgeç</a>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  const form = document.getElementById('machineForm');
  const machineId = <?php echo $machine ? (int)$machine['id'] : 'null'; ?>;

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const formData = new FormData(form);
    const payload = Object.fromEntries(formData.entries());
    const method = machineId ? 'PUT' : 'POST';
    const url = machineId ? `/api/machines/${machineId}` : '/api/machines';

    const response = await fetch(url, {
      method,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });

    const result = await response.json();
    if (result.ok) {
      window.location.href = '/machines';
      return;
    }
    alert(result.error?.message || 'Kayıt başarısız.');
  });
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
