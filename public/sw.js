const STATIC_ASSETS = ["/"];
const CACHE_NAME = "static_cache";

async function preCache() {
    const cache = await caches.open(CACHE_NAME);
    return cache.addAll(STATIC_ASSETS);
}
self.addEventListener("install", (event) => {
    console.log("install");
    event.waitUntil(preCache());
});

async function cleanupCache() {
    const keys = await caches.keys(CACHE_NAME);
    const keysToDelete = keys.map((key) => {
        if (key !== CACHE_NAME) {
            return caches.delete(key);
        }
    });
    return Promise.all(keysToDelete);
}

self.addEventListener("active", (event) => {
    console.log("active");
    event.waitUntil(cleanupCache());
});

async function fetchAssets(event) {
    try {
        const response = await fetch(event.request);
        return response;
    } catch (err) {
        const cache = await caches.open(CACHE_NAME);
        return cache.match(event.request);
    }
}
self.addEventListener("fetch", (event) => {
    console.log("fetch");
    event.respondWith(fetchAssets(event));
});
