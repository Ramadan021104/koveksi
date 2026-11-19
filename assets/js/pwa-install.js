/*
 * PWA Install Handler - Indra Konveksi
 * - Chrome/Edge/Android/Desktop: memanfaatkan event beforeinstallprompt
 * - iOS Safari: tidak support beforeinstallprompt, jadi tampilkan modal petunjuk manual
 * - Kalau aplikasi sudah terinstall (display-mode: standalone), tombol disembunyikan
 */
(function () {
  var deferredPrompt = null;
  var installBtn = document.getElementById('pwaInstallBtn');
  var iosModalEl = document.getElementById('iosInstallModal');

  function isStandalone() {
    return window.matchMedia('(display-mode: standalone)').matches ||
      window.navigator.standalone === true;
  }

  function isIOS() {
    return /iphone|ipad|ipod/i.test(window.navigator.userAgent);
  }

  function showButton() {
    if (installBtn && !isStandalone()) {
      installBtn.classList.add('show');
    }
  }

  function hideButton() {
    if (installBtn) {
      installBtn.classList.remove('show');
    }
  }

  if (isStandalone()) {
    hideButton();
    return;
  }

  // Android / Desktop Chrome & Edge
  window.addEventListener('beforeinstallprompt', function (e) {
    e.preventDefault();
    deferredPrompt = e;
    showButton();
  });

  window.addEventListener('appinstalled', function () {
    hideButton();
    deferredPrompt = null;
  });

  // iOS Safari: tidak ada beforeinstallprompt, tampilkan tombol + modal manual
  if (isIOS() && !isStandalone()) {
    showButton();
  }

  if (installBtn) {
    installBtn.addEventListener('click', function () {
      if (deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.finally(function () {
          deferredPrompt = null;
          hideButton();
        });
      } else if (isIOS()) {
        if (iosModalEl && window.bootstrap) {
          var modal = new window.bootstrap.Modal(iosModalEl);
          modal.show();
        } else if (iosModalEl) {
          iosModalEl.style.display = 'block';
        }
      }
    });
  }
})();
