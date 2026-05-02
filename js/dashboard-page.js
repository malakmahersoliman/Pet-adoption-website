/**
 * Dashboard CRUD → php/api/pet_*.php (session + CSRF).
 */
(function () {
  function qs(id) {
    return document.getElementById(id);
  }

  function escapeHtml(s) {
    var div = document.createElement('div');
    div.textContent = s;
    return div.innerHTML;
  }

  var csrfToken = '';
  var petsCache = [];

  function renderTable(pets) {
    var tbody = qs('petsTableBody');
    if (!tbody) return;
    petsCache = pets;
    tbody.innerHTML = pets
      .map(function (p) {
        var typeHuman =
          p.type === 'dogs' ? 'Dog' : p.type === 'cats' ? 'Cat' : 'Other';
        return (
          '<tr data-id="' +
          escapeHtml(String(p.id)) +
          '">' +
          '<td class="d-none d-md-table-cell"><img src="' +
          escapeHtml(p.image) +
          '" alt="" class="rounded table-dashboard-photo"></td>' +
          '<td><span class="fw-semibold">' +
          escapeHtml(p.name) +
          '</span><span class="d-md-none d-block small text-muted">' +
          escapeHtml(typeHuman) +
          '</span></td>' +
          '<td class="d-none d-md-table-cell">' +
          escapeHtml(typeHuman) +
          '</td>' +
          '<td class="d-none d-lg-table-cell text-muted small" title="' +
          escapeHtml(p.description) +
          '">' +
          '<span class="d-inline-block text-truncate" style="max-width: 240px">' +
          escapeHtml(p.description) +
          '</span></td>' +
          '<td class="text-end text-nowrap">' +
          '<button type="button" class="btn btn-sm btn-outline-primary me-1 mb-1 mb-md-0 btn-edit">Edit</button>' +
          '<button type="button" class="btn btn-sm btn-outline-danger btn-delete">Delete</button>' +
          '</td>' +
          '</tr>'
        );
      })
      .join('');
  }

  function loadPets() {
    return fetch('php/api/pets_list.php', { credentials: 'same-origin' })
      .then(function (r) {
        return r.json();
      })
      .then(function (data) {
        if (data.ok && data.pets) {
          renderTable(data.pets);
        }
      });
  }

  function openModal(pet) {
    var modalEl = qs('petModal');
    if (!modalEl) return;
    qs('petModalTitle').textContent = pet ? 'Edit pet' : 'Add pet';
    qs('petId').value = pet ? String(pet.id) : '';
    qs('petName').value = pet ? pet.name : '';
    qs('petType').value = pet ? pet.type : 'cats';
    qs('petDescription').value = pet ? pet.description : '';
    qs('petImage').value = pet ? pet.image : '';
    bootstrap.Modal.getOrCreateInstance(modalEl).show();
  }

  function saveForm() {
    var id = qs('petId').value;
    var payload = {
      csrf: csrfToken,
      name: qs('petName').value,
      type: qs('petType').value,
      description: qs('petDescription').value,
      image: qs('petImage').value,
    };
    var url = id ? 'php/api/pet_update.php' : 'php/api/pet_create.php';
    if (id) {
      payload.id = parseInt(id, 10);
    }
    fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    })
      .then(function (r) {
        return r.json();
      })
      .then(function (data) {
        if (!data.ok) {
          alert(data.error || 'Could not save');
          return;
        }
        var modalEl = qs('petModal');
        if (modalEl) {
          var mi = bootstrap.Modal.getInstance(modalEl);
          if (mi) mi.hide();
        }
        return loadPets();
      })
      .catch(function () {
        alert('Request failed');
      });
  }

  document.addEventListener('DOMContentLoaded', function () {
    fetch('php/session_info.php', { credentials: 'same-origin' })
      .then(function (r) {
        return r.json();
      })
      .then(function (sess) {
        if (!sess.loggedIn) {
          window.location.replace('login.html?next=' + encodeURIComponent('dashboard.html'));
          return null;
        }
        csrfToken = sess.csrf || '';
        return loadPets();
      })
      .catch(function () {
        window.location.replace('login.html?next=' + encodeURIComponent('dashboard.html'));
      });

    var addBtn = qs('btnAddPet');
    if (addBtn) addBtn.addEventListener('click', function () { openModal(null); });

    var saveBtn = qs('btnSavePet');
    if (saveBtn) saveBtn.addEventListener('click', saveForm);

    var tbody = qs('petsTableBody');
    if (tbody) {
      tbody.addEventListener('click', function (e) {
        var tr = e.target.closest('tr[data-id]');
        if (!tr) return;
        var id = tr.getAttribute('data-id');
        if (e.target.closest('.btn-delete')) {
          if (!confirm('Remove this pet from the listing?')) return;
          fetch('php/api/pet_delete.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: parseInt(id, 10), csrf: csrfToken }),
          })
            .then(function (r) {
              return r.json();
            })
            .then(function (data) {
              if (!data.ok) {
                alert(data.error || 'Delete failed');
                return;
              }
              loadPets();
            });
        } else if (e.target.closest('.btn-edit')) {
          var pet = petsCache.find(function (p) {
            return String(p.id) === id;
          });
          if (pet) openModal(pet);
        }
      });
    }
  });
})();
