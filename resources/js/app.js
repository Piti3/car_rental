import Alpine from 'alpinejs';
import axios from 'axios';

// Konfiguracja Axios (dla API)
axios.defaults.baseURL = 'http://localhost:8080/api';
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Content-Type'] = 'application/json';

// Dodaj token JWT do requestów (jeśli jest)
const token = localStorage.getItem('auth_token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

// Udostępnij Alpine i Axios globalnie
window.Alpine = Alpine;
window.axios = axios;

// Uruchom Alpine
Alpine.start();
