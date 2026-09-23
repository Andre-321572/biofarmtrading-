var staticCacheName = "pwa-v1";
var filesToCache = [
    '/offline',
    '/images/logo.jpg',
    '/images/biofarm_logo.jpg',
    '/images/admin_sidebar_logo.jpg',
];

// Cache on install
self.addEventListener("install", event => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName).then(cache => {
            return Promise.allSettled(
                filesToCache.map(url => 
                    cache.add(url).catch(err => console.warn('PWA: Skipped uncacheable asset ' + url, err))
                )
            );
        })
    );
});

// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match('offline');
            })
    )
});
