/**
 * Navigation UI from PHP session (php/session_info.php).
 */
(function (global) {
  function setDisplay(el, show) {
    if (!el) return;
    el.style.display = show ? '' : 'none';
  }

  function init() {
    var guest = document.getElementById('navWhenGuest');
    var authed = document.getElementById('navWhenAuthed');
    var dash = document.getElementById('navDashboard');
    var logoutWrap = document.getElementById('navLogoutWrap');
    var nameEl = document.getElementById('navUserName');

    fetch('php/session_info.php', { credentials: 'same-origin' })
      .then(function (r) {
        return r.json();
      })
      .then(function (data) {
        if (data.loggedIn) {
          setDisplay(guest, false);
          setDisplay(authed, true);
          setDisplay(dash, true);
          setDisplay(logoutWrap, true);
          if (nameEl) {
            nameEl.textContent = data.name || data.email || 'Member';
          }
        } else {
          setDisplay(guest, true);
          setDisplay(authed, false);
          setDisplay(dash, false);
          setDisplay(logoutWrap, false);
        }
      })
      .catch(function () {
        setDisplay(guest, true);
        setDisplay(authed, false);
        setDisplay(dash, false);
        setDisplay(logoutWrap, false);
      });

    var logoutBtn = document.getElementById('navLogoutBtn');
    if (logoutBtn && !logoutBtn.dataset.bound) {
      logoutBtn.dataset.bound = '1';
      logoutBtn.addEventListener('click', function (e) {
        e.preventDefault();
        global.location.href = 'php/logout.php';
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  global.StrayAuthUi = { init: init };
})(typeof window !== 'undefined' ? window : this);
