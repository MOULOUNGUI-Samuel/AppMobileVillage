// Nom du cache
const CACHE_NAME = 'yodi-events-cache-v1';

// Fichiers à mettre en cache lors de l'installation
const urlsToCache = [
  '/', // Page d'accueil/racine
  '/login', // Page de connexion
  '/manifest.json', // Le manifest
  '/assets/css/style.css', // Votre CSS principal
  '/assets/js/main.js', // Votre JS principal
  '/assets/img/logo1.png' // Votre logo
];

// 1. Installation du Service Worker
self.addEventListener('install', event => {
  console.log('Service Worker: Installation...');
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Service Worker: Mise en cache des fichiers principaux');
        return cache.addAll(urlsToCache);
      })
      .then(() => self.skipWaiting())
  );
});

// 2. Activation du Service Worker et nettoyage des anciens caches
self.addEventListener('activate', event => {
  console.log('Service Worker: Activation...');
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== CACHE_NAME) {
            console.log('Service Worker: Nettoyage de l\'ancien cache');
            return caches.delete(cache);
          }
        })
      );
    })
  );
});

// 3. Interception des requêtes (stratégie "Cache d'abord")
self.addEventListener('fetch', event => {
  console.log('Service Worker: Fetching', event.request.url);
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Si la ressource est dans le cache, on la retourne
        if (response) {
          return response;
        }
        // Sinon, on va la chercher sur le réseau
        return fetch(event.request);
      })
  );
});