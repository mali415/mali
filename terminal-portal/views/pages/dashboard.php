<?php
require_once __DIR__ . '/../../app/config/db.php';
require_once __DIR__ . '/../../app/models/Machine.php';

$pdo = get_pdo();
$counts = Machine::counts($pdo);
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <h1>Dashboard</h1>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-4">
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?php echo (int)$counts['total']; ?></h3>
              <p>Toplam Makine Sayısı</p>
            </div>
            <div class="icon">
              <i class="fas fa-industry"></i>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="small-box bg-success">
            <div class="inner">
              <h3><?php echo (int)$counts['active']; ?></h3>
              <p>Aktif Makine Sayısı</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="small-box bg-secondary">
            <div class="inner">
              <h3><?php echo (int)$counts['passive']; ?></h3>
              <p>Pasif Makine Sayısı</p>
            </div>
            <div class="icon">
              <i class="fas fa-pause-circle"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
