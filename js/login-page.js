(function () {
  document.addEventListener('DOMContentLoaded', function () {
    var params = new URLSearchParams(window.location.search);
    var next = params.get('next');
    var nextInput = document.querySelector('input[name="next"]');
    if (nextInput && next && next.indexOf('http') !== 0 && next.indexOf('//') !== 0) {
      nextInput.value = next;
    }

    var err = params.get('error');
    if (err) {
      var box = document.getElementById('loginError');
      if (box) {
        box.classList.remove('d-none');
        box.textContent =
          err === 'credentials'
            ? 'Invalid email or password.'
            : 'Please check your email and password format.';
      }
    }
  });
})();
