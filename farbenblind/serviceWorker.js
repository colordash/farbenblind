const Farbenblindspiel = "dev-Farbenspiel-Farbenblind-v1"
const assets = [
    "./Farbenblind.js",
    "./Farbenblind.css",
    "./backimage.webp",
]

self.addEventListener("install", installEvent =>{
  installEvent.waitUntil(
    caches.open(Farbenblindspiel).then(cache => {
      cache.addAll(assets)
    })
  )
})

self.addEventListener("fetch", fetchEvent => {
    fetchEvent.respondWith(
      caches.match(fetchEvent.request).then(res => {
        return res || fetch(fetchEvent.request)
      })
    )
  })