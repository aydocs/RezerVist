import { useState, useEffect } from 'react';
import { Download, RefreshCcw, X, CheckCircle2, ChevronRight, Loader2 } from 'lucide-react';
import api from '../lib/api';

interface UpdateStatus {
    type: 'checking' | 'available' | 'not-available' | 'downloading' | 'downloaded' | 'error' | 'idle';
    version?: string;
    percent?: number;
    speed?: number;
    message?: string;
}

export default function UpdateNotifier() {
    const [status, setStatus] = useState < UpdateStatus > ({ type: 'idle' });
    const [isVisible, setIsVisible] = useState(false);

    // Check backend API version on mount
    useEffect(() => {
        const checkApiVersion = async () => {
            try {
                // Get current app version from build define
                // @ts-ignore
                const currentVersion = typeof __APP_VERSION__ !== 'undefined' ? __APP_VERSION__ : '1.0.0';

                // Use the configured axios instance which handles base URL
                const res = await api.get('/version');

                if (res.data.success) {
                    const { latest_version, message } = res.data.data;

                    // Semantic verification helper
                    const compareVersions = (v1: string, v2: string) => {
                        const parts1 = v1.split('.').map(Number);
                        const parts2 = v2.split('.').map(Number);
                        for (let i = 0; i < Math.max(parts1.length, parts2.length); i++) {
                            const p1 = parts1[i] || 0;
                            const p2 = parts2[i] || 0;
                            if (p1 > p2) return 1;
                            if (p1 < p2) return -1;
                        }
                        return 0;
                    };

                    // Only notify if SERVER version is GREATER than LOCAL version
                    if (compareVersions(latest_version, currentVersion) > 0) {
                        console.log(`Update available: ${latest_version} (Current: ${currentVersion})`);
                        setStatus({
                            type: 'available',
                            version: latest_version,
                            message: message || 'Yeni versiyon indirilebilir.',
                        });
                        setIsVisible(true);
                    }
                }
            } catch (e) {
                console.error("Version check failed", e);
            }
        };

        // Run check immediately
        checkApiVersion();

        // Optional: Poll every 30 mins
        const interval = setInterval(checkApiVersion, 30 * 60 * 1000);
        return () => clearInterval(interval);
    }, []);

    // ... (rest of the file)
    // Wait, I should implement it fully.

    useEffect(() => {
        // Electron Auto-Updater Listeners
        // @ts-ignore
        if (window.ipcRenderer) {
            const handler = (_event: any, data: UpdateStatus) => {
                console.log('Update status received:', data);
                setStatus(data);
                if (data.type === 'available' || data.type === 'downloading' || data.type === 'downloaded' || data.type === 'error') {
                    setIsVisible(true);
                }
                if (data.type === 'not-available') {
                    setTimeout(() => setIsVisible(false), 3000);
                }
            };
            // @ts-ignore
            window.ipcRenderer.on('update:status', handler);
            return () => {
                // @ts-ignore
                window.ipcRenderer.off('update:status', handler);
            };
        } else {
            console.warn('IPC renderer not found, falling back to API check logic (simulation)');
            // Fallback logic could go here
        }
    }, []);

    const handleInstall = () => {
        // @ts-ignore
        if (window.ipcRenderer) {
            (window as any).ipcRenderer.send('update:install');
        }
    };

    if (!isVisible || status.type === 'idle') return null;

    return (
        <div className="fixed bottom-6 right-6 z-[9999] animate-in slide-in-from-right-10 duration-500">
            <div className="w-96 bg-white/80 backdrop-blur-2xl border border-white/50 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.15)] overflow-hidden">
                <div className="p-6">
                    <div className="flex justify-between items-start mb-4">
                        <div className={`p-3 rounded-2xl ${status.type === 'error' ? 'bg-red-100 text-red-600' :
                            status.type === 'downloaded' ? 'bg-green-100 text-green-600' :
                                'bg-primary/10 text-primary'
                            }`}>
                            {status.type === 'downloading' ? <Download className="animate-bounce" /> :
                                status.type === 'checking' ? <Loader2 className="animate-spin" /> :
                                    status.type === 'downloaded' ? <CheckCircle2 /> :
                                        status.type === 'error' ? <X /> :
                                            <RefreshCcw />}
                        </div>
                        <button
                            onClick={() => setIsVisible(false)}
                            className="p-2 hover:bg-gray-100 rounded-xl transition-colors"
                        >
                            <X size={18} className="text-gray-400" />
                        </button>
                    </div>

                    <div className="space-y-1">
                        <h4 className="text-lg font-black text-gray-900 tracking-tight">
                            {status.type === 'checking' && 'Güncellemeler Denetleniyor...'}
                            {status.type === 'available' && 'Yeni Sürüm Mevcut!'}
                            {status.type === 'downloading' && 'Güncelleme İndiriliyor...'}
                            {status.type === 'downloaded' && 'Güncelleme Hazır!'}
                            {status.type === 'error' && 'Güncelleme Hatası'}
                            {status.type === 'not-available' && 'Sistem Güncel'}
                        </h4>
                        <p className="text-sm font-medium text-gray-500">
                            {status.type === 'available' && `Sürüm ${status.version} indiriliyor.`}
                            {status.type === 'downloading' && `Hız: ${(status.speed ? status.speed / 1024 / 1024 : 0).toFixed(2)} MB/s`}
                            {status.type === 'downloaded' && 'Yüklemeyi tamamlamak için uygulamayı yeniden başlatın.'}
                            {status.type === 'error' && status.message}
                            {status.type === 'not-available' && 'En son sürümü kullanıyorsunuz.'}
                            {status.type === 'checking' && 'Lütfen bekleyin...'}
                        </p>
                    </div>

                    {status.type === 'downloading' && (
                        <div className="mt-6">
                            <div className="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                                <div
                                    className="h-full bg-primary transition-all duration-300"
                                    style={{ width: `${status.percent}%` }}
                                />
                            </div>
                            <div className="flex justify-between mt-2">
                                <span className="text-[10px] font-black text-primary uppercase leading-none">%{Math.round(status.percent || 0)}</span>
                                <span className="text-[10px] font-black text-gray-300 uppercase leading-none tracking-widest italic">PREMIUM UPDATE</span>
                            </div>
                        </div>
                    )}

                    {status.type === 'downloaded' && (
                        <button
                            onClick={handleInstall}
                            className="mt-6 w-full py-4 bg-primary text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-primary/30 hover:shadow-xl hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 group"
                        >
                            <RefreshCcw size={16} className="group-hover:rotate-180 transition-transform duration-700" />
                            Şimdi Güncelle ve Yeniden Başlat
                        </button>
                    )}

                    {status.type === 'available' && (
                        <div className="mt-6 flex items-center gap-2 text-primary font-black text-[10px] uppercase tracking-widest animate-pulse">
                            <span className="w-2 h-2 rounded-full bg-primary" />
                            İndirme işlemi arka planda başladı
                        </div>
                    )}
                </div>

                <div className="bg-gray-50/50 px-6 py-4 flex items-center justify-between border-t border-white/50">
                    <span className="text-[10px] font-black text-gray-400 uppercase tracking-widest">RezerVist Auto-Sync v2</span>
                    <ChevronRight size={14} className="text-gray-300" />
                </div>
            </div>
        </div>
    );
}
