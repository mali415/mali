<?php
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
?>

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h1>Machines</h1>
      <a href="/machines/add" class="btn btn-primary">Yeni Makine</a>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <table class="table table-bordered table-striped" id="machinesTable">
            <thead>
              <tr>
                <th>Kod</th>
                <th>Ad</th>
                <th>Lokasyon</th>
                <th>Tip</th>
                <th>Durum</th>
                <th>İşlemler</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  const tableBody = document.querySelector('#machinesTable tbody');

  const fetchMachines = async () => {
    const response = await fetch('/api/machines');
    const payload = await response.json();
    tableBody.innerHTML = '';
    if (!payload.ok) {
      return;
    }
    payload.data.forEach((machine) => {
      const row = document.createElement('tr');

      const codeCell = document.createElement('td');
      codeCell.textContent = machine.code;
      row.appendChild(codeCell);

      const nameCell = document.createElement('td');
      nameCell.textContent = machine.name;
      row.appendChild(nameCell);

      const locationCell = document.createElement('td');
      locationCell.textContent = machine.location || '-';
      row.appendChild(locationCell);

      const typeCell = document.createElement('td');
      typeCell.textContent = machine.type || '-';
      row.appendChild(typeCell);

      const statusCell = document.createElement('td');
      statusCell.textContent = machine.status;
      row.appendChild(statusCell);

      const actionsCell = document.createElement('td');
      const editLink = document.createElement('a');
      editLink.href = `/machines/edit?id=${machine.id}`;
      editLink.className = 'btn btn-sm btn-info';
      editLink.textContent = 'Düzenle';

      const deleteButton = document.createElement('button');
      deleteButton.className = 'btn btn-sm btn-danger ml-2';
      deleteButton.textContent = 'Sil';
      deleteButton.addEventListener('click', () => handleDelete(machine.id));

      actionsCell.appendChild(editLink);
      actionsCell.appendChild(deleteButton);
      row.appendChild(actionsCell);

      tableBody.appendChild(row);
    });
  };

  const handleDelete = async (id) => {
    if (!confirm('Silmek istediğinize emin misiniz?')) {
      return;
    }
    const response = await fetch(`/api/machines/${id}`, { method: 'DELETE' });
    const payload = await response.json();
    if (payload.ok) {
      fetchMachines();
    } else {
      alert(payload.error?.message || 'Silme işlemi başarısız.');
    }
  };

  fetchMachines();
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
