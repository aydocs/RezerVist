import { useState, useEffect, type ReactNode } from 'react';
import Sidebar from './Sidebar';
import { WifiOff, RefreshCcw } from 'lucide-react';
import { offlineManager } from '../lib/offline';

interface DashboardLayoutProps {
    children: ReactNode;
}

export default function DashboardLayout({ children }: DashboardLayoutProps) {
    const [isOffline, setIsOffline] = useState(!navigator.onLine);
    const [queueLength, setQueueLength] = useState(offlineManager.getQueueLength());

    useEffect(() => {
        const handleOnline = () => setIsOffline(false);
        const handleOffline = () => setIsOffline(true);
        const handleQueue = (e: any) => setQueueLength(e.detail);

        window.addEventListener('online', handleOnline);
        window.addEventListener('offline', handleOffline);
        window.addEventListener('offline-queue-updated', handleQueue);

        return () => {
            window.removeEventListener('online', handleOnline);
            window.removeEventListener('offline', handleOffline);
            window.removeEventListener('offline-queue-updated', handleQueue);
        };
    }, []);

    return (
        <div className="h-full bg-gray-50 text-gray-900 flex">
            {/* Background Decor */}
            <div className="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
                <div className="absolute top-[-10%] left-[20%] w-[40%] h-[40%] rounded-full bg-primary/5 blur-[150px]" />
                <div className="absolute bottom-[-10%] right-[20%] w-[40%] h-[40%] rounded-full bg-blue-500/5 blur-[150px]" />
            </div>

            <Sidebar />

            <main className="flex-1 relative z-10 p-4 lg:p-8 overflow-y-auto h-full min-w-0">
                {(() => {
                    const menuCache = sessionStorage.getItem('pos_menu_cache');
                    const hasMenu = menuCache ? JSON.parse(menuCache).length > 0 : true; // Default to true to allow initial load
                    const staffSession = JSON.parse(localStorage.getItem('pos_staff_session') || '{}');
                    const isAdmin = staffSession.permissions?.includes('all');

                    // If no menu and is admin, show setup prompt unless on settings page
                    if (!hasMenu && isAdmin && !window.location.hash.includes('settings') && !window.location.hash.includes('menu')) {
                        return (
                            <div className="h-full flex flex-col items-center justify-center text-center p-8">
                                <div className="w-24 h-24 bg-primary/10 rounded-3xl flex items-center justify-center mb-6 animate-pulse">
                                    <RefreshCcw size={48} className="text-primary" />
                                </div>
                                <h2 className="text-2xl font-black text-gray-900 mb-2">Kurulum Gerekli</h2>
                                <p className="text-gray-500 max-w-md mb-8 font-medium">
                                    POS sistemini kullanmaya başlamadan önce lütfen menünüzü ve kategorilerinizi oluşturun.
                                </p>
                                <div className="flex gap-4">
                                    <button
                                        onClick={() => window.location.hash = '#/menu'}
                                        className="px-8 py-4 bg-primary text-white rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition-all"
                                    >
                                        Menüyü Düzenle
                                    </button>
                                    <button
                                        onClick={() => window.location.hash = '#/settings'}
                                        className="px-8 py-4 bg-white text-gray-900 border border-gray-100 rounded-2xl font-black uppercase text-xs tracking-widest shadow-sm hover:bg-gray-50 transition-all"
                                    >
                                        Genel Ayarlar
                                    </button>
                                </div>
                            </div>
                        );
                    }

                    return children;
                })()}
            </main>

            {/* Offline Status Float */}
            {(isOffline || queueLength > 0) && (
                <div className="fixed bottom-6 right-6 z-50 animate-bounce-subtle">
                    <div className={`flex items-center gap-3 px-5 py-3 rounded-2xl shadow-2xl border backdrop-blur-md transition-all ${isOffline
                        ? 'bg-red-500/90 text-white border-red-400'
                        : 'bg-purple-600/90 text-white border-purple-400'
                        }`}>
                        {isOffline ? (
                            <WifiOff size={20} className="animate-pulse" />
                        ) : (
                            <RefreshCcw size={20} className="animate-spin" />
                        )}
                        <div className="flex flex-col">
                            <span className="text-[10px] font-black uppercase tracking-widest opacity-80">
                                {isOffline ? 'Bağlantı Yok' : 'Eşitleniyor'}
                            </span>
                            <span className="text-xs font-bold">
                                {isOffline
                                    ? 'İşlemleriniz kaydediliyor'
                                    : `${queueLength} İşlem Aktarılıyor...`}
                            </span>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
