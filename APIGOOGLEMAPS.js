// Importați modulul Google Maps
const google = require('google-maps');

// Obțineți cheia API
const apiKey = '5333221315213156';

// Creați o instanță a Google Maps
const map = new google.maps.Map(document.getElementById('map'), {
  center: {lat: 45.751, lng: 21.258},
  zoom: 12,
});

// Adăugați un marker pe hartă
const marker = new google.maps.Marker({
  position: {lat: 45.751, lng: 21.258},
});

// Adăugați markerul pe hartă
map.addMarker(marker);