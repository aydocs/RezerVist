import api from './api-client';

interface OfflineRequest {
    id: string;
    url: string;
    method: 'GET' | 'POST' | 'PUT' | 'DELETE';
    data: any;
    timestamp: number;
    description: string;
    retryCount: number;
}

class OfflineManager {
    private queue: OfflineRequest[] = [];
    private storageKey = 'pos_offline_queue';
    private isSyncing = false;
    private readonly MAX_QUEUE_AGE = 3 * 24 * 60 * 60 * 1000; // 3 days

    constructor() {
        this.loadQueue();

        // Auto-sync on reconnection
        window.addEventListener('online', () => {
            console.log('🟢 Back online - syncing queue');
            this.sync();
        });

        window.addEventListener('offline', () => {
            console.log('🔴 Offline mode activated');
        });

        // Periodic cleanup (every hour)
        setInterval(() => this.cleanupOldRequests(), 60 * 60 * 1000);

        // Periodic sync attempt (every 30 seconds)
        setInterval(() => {
            if (navigator.onLine && !this.isSyncing) {
                this.sync();
            }
        }, 30000);
    }

    private loadQueue() {
        const stored = localStorage.getItem(this.storageKey);
        if (stored) {
            try {
                this.queue = JSON.parse(stored);
                console.log(`📦 Loaded ${this.queue.length} offline requests`);
            } catch (e) {
                this.queue = [];
            }
        }
    }

    private saveQueue() {
        localStorage.setItem(this.storageKey, JSON.stringify(this.queue));
    }

    private cleanupOldRequests() {
        const before = this.queue.length;
        const now = Date.now();
        this.queue = this.queue.filter(req => (now - req.timestamp) < this.MAX_QUEUE_AGE);

        if (before !== this.queue.length) {
            console.log(`🧹 Cleaned up ${before - this.queue.length} old requests`);
            this.saveQueue();
        }
    }

    public addToQueue(url: string, method: any, data: any, description: string) {
        const request: OfflineRequest = {
            id: crypto.randomUUID(),
            url,
            method,
            data,
            timestamp: Date.now(),
            description,
            retryCount: 0
        };
        this.queue.push(request);
        this.saveQueue();
        console.log(`➕ Queued: ${description} (${this.queue.length} total)`);

        // Notify UI
        window.dispatchEvent(new CustomEvent('offline-queue-updated', { detail: this.queue.length }));
    }

    public async sync() {
        if (this.isSyncing || this.queue.length === 0 || !navigator.onLine) return;

        this.isSyncing = true;
        try {
            console.log(`🔄 Syncing ${this.queue.length} offline requests...`);

            const remainingQueue: OfflineRequest[] = [];

            for (const req of this.queue) {
                try {
                    await api({
                        url: req.url,
                        method: req.method,
                        data: req.data,
                        headers: { 'X-Offline-Sync': 'true' }
                    });
                    console.log(`✅ Synced: ${req.description}`);
                } catch (err) {
                    console.error(`❌ Failed to sync: ${req.description}`, err);

                    req.retryCount++;

                    // Keep in queue if retry count < 5
                    if (req.retryCount < 5) {
                        remainingQueue.push(req);
                    } else {
                        console.warn(`⛔ Dropping request after 5 failures: ${req.description}`);
                    }
                }
            }

            this.queue = remainingQueue;
            this.saveQueue();
        } finally {
            this.isSyncing = false;
        }

        if (this.queue.length === 0) {
            console.log('✨ All requests synced!');
        } else {
            console.log(`⚠️ ${this.queue.length} requests still pending`);
        }

        window.dispatchEvent(new CustomEvent('offline-queue-updated', { detail: this.queue.length }));
    }

    public getQueueLength() {
        return this.queue.length;
    }

    public getQueueStatus() {
        return {
            isOnline: navigator.onLine,
            queueLength: this.queue.length,
            oldestRequest: this.queue[0]?.timestamp
        };
    }
}

export const offlineManager = new OfflineManager();
