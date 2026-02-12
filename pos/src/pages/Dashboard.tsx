import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import DashboardLayout from '../components/DashboardLayout';
import { RefreshCw, Loader2, Filter as FilterIcon } from 'lucide-react';
import api from '../lib/api';
import CacheManager from '../lib/cache';

interface Table {
    id: number;
    name: string;
    category: string;
    capacity: number;
    status: 'empty' | 'occupied' | 'reserved';
    reservation?: {
        customer_name: string;
        start_time: string;
    };
    order?: {
        id: number;
        opened_at: string;
        total_amount: number;
        paid_amount: number;
        remaining_amount: number;
    } | null;
}

export default function Dashboard() {
    const navigate = useNavigate();
    const [tables, setTables] = useState < Table[] > (() => {
        return CacheManager.get('tables') || [];
    });
    const [loading, setLoading] = useState(false);
    const [filter, setFilter] = useState('Tümü');
    const [now, setNow] = useState(new Date());

    const syncOccupancy = async (currentTables: Table[]) => {
        if (currentTables.length === 0) return;

        const total = currentTables.length;
        const occupied = currentTables.filter(t => t.status === 'occupied').length;
        const rate = Math.round((occupied / total) * 100);

        try {
            await api.post('/update-occupancy', { occupancy_rate: rate });
        } catch (err) {
            console.warn('Doluluk senkronizasyon hatası:', err);
        }
    };

    const fetchTables = async (force = false) => {
        if (!force) {
            const cached = CacheManager.get('tables');
            if (cached) {
                setTables(cached);
                return;
            }
        }

        setLoading(true);
        try {
            const res = await api.get('/tables');
            if (res.data.success) {
                setTables(res.data.data);
                CacheManager.set('tables', res.data.data);
                syncOccupancy(res.data.data);
            }
        } catch (err) {
            console.error('Masa yükleme hatası:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchTables();
        const interval = setInterval(() => {
            setNow(new Date());
        }, 30000); // Update every 30 seconds for timers
        return () => clearInterval(interval);
    }, []);

    const calculateDuration = (openedAt: string) => {
        const opened = new Date(openedAt);
        const diffMs = Math.max(0, now.getTime() - opened.getTime()); // Prevent negative durations
        const diffMins = Math.floor(diffMs / 60000);

        if (diffMins < 60) {
            return `${diffMins} Dk`;
        }

        const hours = Math.floor(diffMins / 60);
        const mins = diffMins % 60;

        if (hours < 24) {
            return mins > 0 ? `${hours} Sa ${mins} Dk` : `${hours} Sa`;
        }

        const days = Math.floor(hours / 24);
        const remainingHours = hours % 24;

        if (days < 30) {
            return remainingHours > 0 ? `${days} Gün ${remainingHours} Sa` : `${days} Gün`;
        }

        const months = Math.floor(days / 30);
        const remainingDays = days % 30;

        return remainingDays > 0 ? `${months} Ay ${remainingDays} Gün` : `${months} Ay`;
    };

    const filteredTables = filter === 'Tümü'
        ? tables
        : tables.filter(t => t.category === filter);

    const categories = ['Tümü', ...Array.from(new Set(tables.map(t => t.category).filter(Boolean)))];

    return (
        <DashboardLayout>
            <div className="flex flex-col gap-8">
                {/* Header Section */}
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 className="text-3xl font-bold text-gray-900 tracking-tight">Masa Yönetimi</h2>
                        <p className="text-gray-500 mt-1">Sistemdeki tüm masaların canlı durumunu görün.</p>
                    </div>

                    <div className="flex items-center gap-3">
                        <button
                            onClick={() => fetchTables(true)}
                            disabled={loading}
                            title="Yenile"
                            className="flex items-center gap-2 px-4 py-3 bg-[#6200EE] hover:bg-purple-700 text-white rounded-xl shadow-lg shadow-purple-200 transition-all font-medium disabled:opacity-50"
                        >
                            {loading ? <Loader2 className="h-5 w-5 animate-spin" /> : <RefreshCw className="h-5 w-5" />}
                            <span className="hidden sm:inline">Yenile</span>
                        </button>
                    </div>
                </div>

                {/* Zones Section */}
                <div className="flex flex-col gap-4">
                    <div className="flex items-center justify-between">
                        <h3 className="text-sm font-black text-gray-400 uppercase tracking-widest ml-1">Hizmet Bölgeleri</h3>
                    </div>
                    <div className="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-hide">
                        {categories.map((tab) => (
                            <button
                                key={String(tab)}
                                onClick={() => setFilter(String(tab))}
                                className={`px-6 py-3 rounded-2xl text-sm font-bold whitespace-nowrap transition-all border-2 ${filter === tab
                                    ? 'bg-[#6200EE] text-white border-[#6200EE] shadow-xl shadow-purple-100'
                                    : 'bg-white text-gray-500 border-gray-100 hover:border-[#6200EE]/30 hover:text-[#6200EE]'
                                    }`}
                            >
                                {String(tab)}
                            </button>
                        ))}
                    </div>
                </div>

                {/* Tables Content */}
                {loading && tables.length === 0 ? (
                    <div className="flex flex-col items-center justify-center py-20 bg-white rounded-[2.5rem] border border-dashed border-gray-200">
                        <Loader2 className="h-10 w-10 text-primary animate-spin mb-4" />
                        <p className="text-gray-500 font-medium">Masalar yükleniyor...</p>
                    </div>
                ) : filteredTables.length > 0 ? (
                    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-4 lg:gap-6">
                        {filteredTables.map((table) => {
                            const isOccupied = table.status === 'occupied';
                            const isReserved = table.status === 'reserved';

                            return (
                                <div
                                    key={table.id}
                                    onClick={() => navigate(`/order/${table.id}`)}
                                    className={`relative border-2 rounded-[2rem] p-6 transition-all cursor-pointer group hover:shadow-2xl overflow-hidden
                                        ${isOccupied
                                            ? 'bg-red-50 border-red-100 hover:border-red-400'
                                            : isReserved
                                                ? 'bg-orange-50 border-orange-100 hover:border-orange-400'
                                                : 'bg-white border-gray-100 hover:border-primary/50'
                                        }`}
                                >
                                    {/* Duration Overlay for Occupied */}
                                    {isOccupied && table.order && (
                                        <div className="absolute top-0 right-0 bg-red-500 text-white px-3 py-1.5 text-[10px] font-black rounded-bl-[1.5rem] shadow-lg shadow-red-500/20">
                                            {calculateDuration(table.order.opened_at)}
                                        </div>
                                    )}

                                    <div className="flex justify-between items-start mb-6 relative z-10">
                                        <div className="flex flex-col">
                                            <span className={`text-2xl font-black tracking-tighter transition-colors ${isOccupied ? 'text-red-900' : 'text-gray-900 group-hover:text-primary'}`}>
                                                {table.name}
                                            </span>
                                            <span className="text-[10px] font-black text-gray-400 uppercase tracking-widest">{table.category}</span>
                                        </div>
                                        <div className={`h-4 w-4 rounded-full shadow-lg ${table.status === 'empty' ? 'bg-emerald-500 ring-4 ring-emerald-100' : table.status === 'occupied' ? 'bg-red-500 ring-4 ring-red-100' : 'bg-orange-500 ring-4 ring-orange-100'}`} />
                                    </div>

                                    <div className="space-y-4 relative z-10">
                                        {/* Status Badge */}
                                        <div className="flex justify-between items-center">
                                            <span className={`text-[10px] font-black uppercase tracking-widest ${isOccupied ? 'text-red-600/50' : 'text-gray-400'}`}>Durum</span>
                                            <span className={`text-[11px] font-black px-3 py-1 rounded-xl uppercase tracking-wider ${table.status === 'empty' ? 'text-emerald-600 bg-emerald-100/50' :
                                                table.status === 'occupied' ? 'text-red-600 bg-red-100/50' :
                                                    'text-orange-600 bg-orange-100/50'
                                                }`}>
                                                {table.status === 'empty' ? 'BOŞ' : table.status === 'occupied' ? 'DOLU' : 'REZERVE'}
                                            </span>
                                        </div>

                                        {/* Show total if occupied */}
                                        {isOccupied && table.order && (
                                            <div className="pt-4 border-t border-red-200/50">
                                                <div className="flex justify-between items-center mb-1">
                                                    <span className="text-[10px] font-black text-red-600/50 uppercase tracking-widest">Adisyon</span>
                                                    <span className="text-xl font-black text-red-900 tracking-tighter">
                                                        ₺{Number((table.order.total_amount || 0) - (table.order.paid_amount || 0)).toLocaleString('tr-TR', { minimumFractionDigits: 2 })}
                                                    </span>
                                                </div>
                                            </div>
                                        )}

                                        {!isOccupied && (
                                            <div className="pt-4 border-t border-gray-100">
                                                <div className="flex justify-between items-center">
                                                    <span className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Kapasite</span>
                                                    <span className="text-sm font-black text-gray-900">{table.capacity} KİŞİ</span>
                                                </div>
                                                {table.reservation && (
                                                    <div className="mt-3 flex flex-col gap-1 p-3 bg-orange-100/50 rounded-2xl border border-orange-200/50">
                                                        <span className="text-[10px] text-orange-600 font-black uppercase tracking-widest">Rezervasyon</span>
                                                        <span className="text-gray-900 font-extrabold text-xs truncate">{table.reservation.customer_name}</span>
                                                    </div>
                                                )}
                                            </div>
                                        )}
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                ) : (
                    <div className="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border border-dashed border-gray-200">
                        <FilterIcon className="h-10 w-10 text-gray-300 mb-4" />
                        <p className="text-gray-500 font-medium">Bu kategoride masa bulunamadı.</p>
                        <button
                            onClick={() => setFilter('Tümü')}
                            className="text-primary font-bold mt-2 hover:underline"
                        >
                            Tüm masaları göster
                        </button>
                    </div>
                )}
            </div>
        </DashboardLayout>
    );
}
