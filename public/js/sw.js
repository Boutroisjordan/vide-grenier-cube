self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open("v1").then((cache) => {
      return cache.addAll([
        "/",
        "/index.html",
        "/css/style.css",
        "/js/custom.js",
        "/js/jquery-3.3.1.min.js",
        "/js/jquery-migrate-3.0.1.min.js",
      ]);
    })
  );

  console.log("Service Worker installed");
});

self.addEventListener("fetch", (event) => {
  event.respondWith(fetch(event.request));
});
