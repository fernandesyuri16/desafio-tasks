import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8001/api',
    headers: {
        'Content-Type': 'application/json'
    }
});

// Interceptor para adicionar o token nas requisições
api.interceptors.request.use(config => {
    const token = localStorage.getItem('authToken');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, error => {
    return Promise.reject(error);
});

api.interceptors.response.use(response => {
    return response;
}, error => {
    if (error.response && error.response.status === 401) {
        localStorage.removeItem('authToken');
        window.location.href = '/';
    }
    return Promise.reject(error);
});

export default api;
