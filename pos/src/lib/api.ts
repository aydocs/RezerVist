import apiClient from './api-client';
import { offlineManager } from './offline';

const api = apiClient;

// Response interceptor to handle offline scenarios
api.interceptors.response.use(
    response => response,
    async error => {
        const { config } = error;

        // If it's a network error and we're not already trying to sync offline
        if (!error.response && config && !config.headers['X-Offline-Sync']) {
            const isPostRequest = config.method === 'post' || config.method === 'put' || config.method === 'delete';

            if (isPostRequest) {
                const description = config.url?.includes('submit') ? 'Sipariş' :
                    config.url?.includes('payment') ? 'Ödeme' : 'İşlem';

                offlineManager.addToQueue(config.url || '', config.method?.toUpperCase(), config.data, description);

                // Return a fake "success" response so the UI doesn't crash
                return Promise.resolve({
                    data: {
                        success: true,
                        offline: true,
                        data: {},
                        message: 'İşlem çevrimdışı kaydedildi, internet gelince eşitlenecek.'
                    }
                });
            }
        }
        return Promise.reject(error);
    }
);

export default api;
