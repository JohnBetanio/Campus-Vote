import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Laravel CSRF: rely on XSRF-TOKEN cookie (auto-updates on session regen)
window.axios.defaults.withCredentials = true;
window.axios.defaults.xsrfCookieName = 'XSRF-TOKEN';
window.axios.defaults.xsrfHeaderName = 'X-XSRF-TOKEN';

// Also set meta token when present (useful for first-page load)
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
