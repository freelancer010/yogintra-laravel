/*
  Production-friendly service worker
  - Precaches core assets
  - Runtime caching for same-origin assets (images, css, js) using stale-while-revalidate
  - Network-first for navigations with offline fallback
  - Versioned cache name to force updates
*/

const CACHE_VERSION = 'v2';
const PRECACHE = `yogintra-precache-${CACHE_VERSION}`;
const RUNTIME = `yogintra-runtime-${CACHE_VERSION}`;

const OFFLINE_URL = '/offline';

const PRECACHE_URLS = [
  '/',
  OFFLINE_URL,
  '/manifest.json',
  '/assets/css/custom.css',
  '/assets/js/custom.js',
];

// A helper to limit cache size (optional)
async function trimCache(cacheName, maxItems) {
  const cache = await caches.open(cacheName);
  const keys = await cache.keys();
  while (keys.length > maxItems) {
    await cache.delete(keys[0]);
    keys.shift();
  }
}

self.addEventListener('install', (event) => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(PRECACHE)
      .then((cache) => cache.addAll(PRECACHE_URLS))
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => {
      return Promise.all(
        keys.map((key) => {
          if (key !== PRECACHE && key !== RUNTIME) {
            return caches.delete(key);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Helper: stale-while-revalidate strategy
async function staleWhileRevalidate(event) {
  const cache = await caches.open(RUNTIME);
  const cachedResponse = await cache.match(event.request);
  const networkFetch = fetch(event.request)
    .then((response) => {
      // only cache valid responses
      if (response && response.status === 200 && response.type !== 'opaque') {
        cache.put(event.request, response.clone());
      }
      return response;
    })
    .catch(() => null);
  return cachedResponse || networkFetch;
}

self.addEventListener('fetch', (event) => {
  const request = event.request;

  // Only handle GET requests
  if (request.method !== 'GET') return;

  const url = new URL(request.url);

  // Network-first for navigations (so users get latest HTML), fallback to offline page
  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request).then((response) => {
        // Good response -> update cache and return
        return response;
      }).catch(async () => {
        const cache = await caches.open(PRECACHE);
        const cached = await cache.match(OFFLINE_URL);
        return cached || Response.error();
      })
    );
    return;
  }

  // For same-origin static assets (css/js/images), use stale-while-revalidate
  if (url.origin === location.origin) {
    if (request.destination === 'style' || request.destination === 'script' || request.destination === 'image' || request.destination === 'font') {
      event.respondWith(staleWhileRevalidate(event));
      return;
    }
  }

  // Default: try cache, then network
  event.respondWith(
    caches.match(request).then((cached) => {
      return cached || fetch(request).then((response) => {
        return response;
      }).catch(() => {
        // If it's an image request, optionally return a small inline placeholder
        return cached;
      });
    })
  );
});

