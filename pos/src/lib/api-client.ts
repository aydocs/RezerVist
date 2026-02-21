import axios from 'axios';
import SecureStorage from './SecureStorage';

// Production API URL
export const API_BASE_ROOT = 'https://rezervist.com';
const API_BASE_URL = `${API_BASE_ROOT}/api/pos`;

const apiClient = axios.create({
    baseURL: API_BASE_URL,
    timeout: 30000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

// Request interceptor to add token from SecureStorage
apiClient.interceptors.request.use(
    (config) => {
        const token = SecureStorage.getToken();
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Add response interceptor for global error handling
apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        // Only redirect to login if it's a 401 and NOT a PIN validation request
        if (error.response?.status === 401 && !error.config?.url?.includes('validate-pin')) {
            console.error('Yetkisiz erişim - Oturum kapatılıyor...');
            SecureStorage.clearToken();
            localStorage.removeItem('pos_staff_session');
            window.location.hash = '#/login';
        }
        return Promise.reject(error);
    }
);



export default apiClient;
