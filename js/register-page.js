(function () {
  document.addEventListener('DOMContentLoaded', function () {
    var params = new URLSearchParams(window.location.search);
    var err = params.get('error');
    if (err) {
      var box = document.getElementById('registerError');
      if (box) {
        box.classList.remove('d-none');
        if (err === 'duplicate_email') {
          box.textContent = 'An account with this email already exists.';
        } else if (err === 'server') {
          box.textContent = 'Could not register. Try again later.';
        } else {
          box.textContent = 'Please correct the highlighted fields and try again.';
        }
      }
    }

    var form = document.getElementById('registerForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
      var p1 = document.getElementById('registerPassword').value;
      var p2 = document.getElementById('registerPasswordConfirm').value;
      if (p1 !== p2) {
        e.preventDefault();
        alert('Passwords do not match.');
      }
    });
  });
})();
