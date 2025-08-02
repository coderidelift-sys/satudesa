const CACHE_NAME = "pwa-cache-v3"; // Ganti versi setiap ada update
const urlsToCache = [
  "/",
  "index.html",
  "styles.css",
  "app.js",
  "assets/icons/icon-192x192.png",
  "assets/icons/icon-512x512.png"
];

// Install event: Cache the specified resources
self.addEventListener("install", event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log("Opened cache");
        return cache.addAll(urlsToCache);
      })
      .catch(error => {
        console.error("Failed to cache resources:", error);
      })
  );
  self.skipWaiting(); // Paksa service worker baru langsung aktif
});

// Fetch event: Network First untuk selalu mendapatkan update terbaru
self.addEventListener("fetch", event => {
  event.respondWith(
    fetch(event.request)
      .then(response => {
        // Simpan response baru ke cache
        return caches.open(CACHE_NAME).then(cache => {
          cache.put(event.request, response.clone());
          return response;
        });
      })
      .catch(() => caches.match(event.request)) // Jika gagal, ambil dari cache
  );
});

// Activate event: Hapus cache lama dan ambil kontrol langsung
self.addEventListener("activate", event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            console.log("Deleting old cache:", cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim(); // Memaksa kontrol langsung ke service worker baru
});
