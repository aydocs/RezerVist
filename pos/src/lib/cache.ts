/**
 * POS Data Cache Manager
 * Handles local caching with Time-To-Live (TTL) functionality.
 */

const CACHE_PREFIX = 'pos_cache_';
const DEFAULT_TTL = 30 * 60 * 1000; // 30 minutes in milliseconds

interface CacheEntry {
    data: any;
    timestamp: number;
    ttl: number;
}

export const CacheManager = {
    /**
     * Set data in cache
     */
    set(key: string, data: any, ttl: number = DEFAULT_TTL) {
        const entry: CacheEntry = {
            data,
            timestamp: Date.now(),
            ttl
        };
        localStorage.setItem(CACHE_PREFIX + key, JSON.stringify(entry));
        // Also update sessionStorage for immediate session-wide availability
        sessionStorage.setItem(CACHE_PREFIX + key, JSON.stringify(entry));
    },

    /**
     * Get data from cache if not expired
     */
    get(key: string) {
        // Try session storage first (faster, same tab)
        let raw = sessionStorage.getItem(CACHE_PREFIX + key);
        if (!raw) {
            // Fallback to local storage (cross-tab/persistent)
            raw = localStorage.getItem(CACHE_PREFIX + key);
        }

        if (!raw) return null;

        try {
            const entry: CacheEntry = JSON.parse(raw);
            const now = Date.now();

            if (now - entry.timestamp > entry.ttl) {
                this.invalidate(key);
                return null;
            }

            return entry.data;
        } catch (e) {
            this.invalidate(key);
            return null;
        }
    },

    /**
     * Remove specific cache entry
     */
    invalidate(key: string) {
        localStorage.removeItem(CACHE_PREFIX + key);
        sessionStorage.removeItem(CACHE_PREFIX + key);
    },

    /**
     * Invalidate all POS caches
     */
    clear() {
        Object.keys(localStorage).forEach(key => {
            if (key.startsWith(CACHE_PREFIX)) {
                localStorage.removeItem(key);
            }
        });
        Object.keys(sessionStorage).forEach(key => {
            if (key.startsWith(CACHE_PREFIX)) {
                sessionStorage.removeItem(key);
            }
        });
    }
};

export default CacheManager;
