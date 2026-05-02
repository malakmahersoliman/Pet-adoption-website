(function () {
  document.addEventListener('DOMContentLoaded', function () {
    var params = new URLSearchParams(window.location.search);

    if (params.get('submitted') === '1') {
      var okBox = document.getElementById('adoptSuccess');
      var mainForm = document.getElementById('adoptionForm');
      if (okBox) {
        okBox.classList.remove('d-none');
        okBox.innerHTML =
          '<strong>Thank you.</strong> Your request was saved in the database. We will follow up at the email you provided.';
      }
      if (mainForm) mainForm.style.display = 'none';
    }

    var err = params.get('error');
    if (err) {
      var warn = document.createElement('div');
      warn.className = 'alert alert-warning border-0 shadow-sm mb-4';
      warn.setAttribute('role', 'alert');
      var msg =
        err === 'invalid_pet'
          ? 'That pet is no longer available. Choose another from Browse pets.'
          : 'Something went wrong. Check all fields and try again.';
      warn.textContent = msg;
      var container = document.querySelector('.container.px-3');
      if (container && container.firstChild) {
        container.insertBefore(warn, container.firstChild);
      }
    }

    var petId = params.get('pet');
    var petHint = document.getElementById('adoptPetHint');
    var hiddenPet = document.getElementById('adoptPetId');

    if (hiddenPet && petId) {
      hiddenPet.value = petId;
    }

    if (!petHint || !petId) return;

    fetch('php/api/pets_list.php', { credentials: 'same-origin' })
      .then(function (r) {
        return r.json();
      })
      .then(function (data) {
        if (!data.ok || !data.pets) return;
        var idNum = parseInt(petId, 10);
        var pet = data.pets.find(function (p) {
          return p.id === idNum;
        });
        if (pet) {
          petHint.textContent = 'You are applying to adopt ' + pet.name + '.';
          petHint.classList.remove('d-none');
        }
      })
      .catch(function () {});
  });
})();
