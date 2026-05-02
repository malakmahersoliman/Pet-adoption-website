/**
 * Browse pets from php/api/pets_list.php (MySQL).
 */
(function () {
  var petsCache = [];

  function escapeHtml(s) {
    var div = document.createElement('div');
    div.textContent = s;
    return div.innerHTML;
  }

  function typeLabel(t) {
    if (t === 'dogs') return 'Dog';
    if (t === 'cats') return 'Cat';
    return 'Other';
  }

  function render(filterType, pets) {
    var grid = document.getElementById('petGrid');
    if (!grid) return;

    var list =
      filterType === 'all'
        ? pets
        : pets.filter(function (p) {
            return p.type === filterType;
          });

    if (!list.length) {
      grid.innerHTML =
        '<div class="col-12 text-center py-5"><p class="text-muted mb-0">No pets match this filter.</p></div>';
      return;
    }

    grid.innerHTML = list
      .map(function (p) {
        return (
          '<div class="col-sm-6 col-lg-4">' +
          '<div class="card h-100 shadow-sm border-0 pet-card-dynamic">' +
          '<img class="card-img-top" src="' +
          escapeHtml(p.image) +
          '" alt="' +
          escapeHtml(p.name) +
          '">' +
          '<div class="card-body d-flex flex-column">' +
          '<span class="badge bg-secondary align-self-start mb-2">' +
          escapeHtml(typeLabel(p.type)) +
          '</span>' +
          '<h3 class="h5 card-title">' +
          escapeHtml(p.name) +
          '</h3>' +
          '<p class="card-text small flex-grow-1">' +
          escapeHtml(p.description) +
          '</p>' +
          '<a class="btn btn-primary mt-auto w-100" href="adopt.html?pet=' +
          encodeURIComponent(p.id) +
          '">Adopt</a>' +
          '</div></div></div>'
        );
      })
      .join('');
  }

  document.addEventListener('DOMContentLoaded', function () {
    var grid = document.getElementById('petGrid');
    if (!grid) return;

    var current = 'all';

    fetch('php/api/pets_list.php', { credentials: 'same-origin' })
      .then(function (r) {
        return r.json();
      })
      .then(function (data) {
        if (!data.ok || !data.pets) {
          grid.innerHTML =
            '<div class="col-12"><div class="alert alert-warning">Could not load pets. Run the site through PHP with MySQL and import <code>sql/stray_station.sql</code>.</div></div>';
          return;
        }
        petsCache = data.pets;
        render(current, petsCache);

        function syncFilterButtons() {
          document.querySelectorAll('[data-pet-filter]').forEach(function (b) {
            var on = (b.getAttribute('data-pet-filter') || 'all') === current;
            b.className = on
              ? 'btn btn-sm btn-primary rounded-pill px-3'
              : 'btn btn-sm btn-outline-primary rounded-pill px-3';
          });
        }

        document.querySelectorAll('[data-pet-filter]').forEach(function (btn) {
          btn.addEventListener('click', function () {
            current = btn.getAttribute('data-pet-filter') || 'all';
            syncFilterButtons();
            render(current === 'all' ? 'all' : current, petsCache);
          });
        });

        syncFilterButtons();
      })
      .catch(function () {
        grid.innerHTML =
          '<div class="col-12"><div class="alert alert-danger">Network error loading pets. Use Apache/nginx with PHP.</div></div>';
      });
  });
})();
