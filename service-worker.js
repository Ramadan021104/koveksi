/*
 * Service Worker - Indra Konveksi PWA
 * Strategi:
 *  - Aset statis (css/js/gambar/icon) -> cache-first (cepat & bisa offline)
 *  - Halaman (navigasi/PHP)          -> network-first, fallback ke cache/offline.html
 * Cache otomatis "self-heal": versi lama dibersihkan saat SW baru aktif.
 */

const CACHE_VERSION = 'ik-cache-v3';
const OFFLINE_URL = '/offline.html';

const PRECACHE_ASSETS = [
  '/manifest.json',
  '/assets/css/style.css',
  '/assets/js/script.js',
  '/assets/img/icon-192.png',
  '/assets/img/icon-512.png',
  '/assets/img/logo.jpg',
  OFFLINE_URL
];

self.addEventListener('install', (event) => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_VERSION).then((cache) => {
      return Promise.all(
        PRECACHE_ASSETS.map((url) =>
          cache.add(url).catch(() => {
            // Lewati aset yang gagal diambil saat install (mis. belum ada di server)
          })
        )
      );
    })
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(
        keys
          .filter((key) => key !== CACHE_VERSION)
          .map((key) => caches.delete(key))
      )
    ).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  const req = event.request;

  if (req.method !== 'GET') return;

  const url = new URL(req.url);
  if (url.origin !== self.location.origin) return;

  // Navigasi halaman (index.php, katalog.php, dll) -> network-first
  if (req.mode === 'navigate') {
    event.respondWith(
      fetch(req)
        .then((res) => {
          const resClone = res.clone();
          caches.open(CACHE_VERSION).then((cache) => cache.put(req, resClone));
          return res;
        })
        .catch(() =>
          caches.match(req).then((cached) => cached || caches.match(OFFLINE_URL))
        )
    );
    return;
  }

  // Aset statis -> cache-first, lalu update cache di background
  const isStaticAsset = /\.(css|js|png|jpg|jpeg|webp|svg|gif|ico|woff2?)$/i.test(url.pathname);
  if (isStaticAsset) {
    event.respondWith(
      caches.match(req).then((cached) => {
        const fetchPromise = fetch(req)
          .then((res) => {
            const resClone = res.clone();
            caches.open(CACHE_VERSION).then((cache) => cache.put(req, resClone));
            return res;
          })
          .catch(() => cached);
        return cached || fetchPromise;
      })
    );
    return;
  }

  // Request lain (PHP data dinamis, dsb) -> biarkan lewat jaringan seperti biasa
});
