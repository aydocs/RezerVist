import { useEffect, useState, useMemo } from 'react';
import { Clock, CheckCircle, ChefHat, Bell, Flame, AlertOctagon } from 'lucide-react';
import api, { getImageUrl } from '../lib/api-client';
import { playNotificationSound } from '../lib/sound';
import classNames from 'classnames';

interface OrderItem {
    id: number;
    name: string;
    quantity: number;
    notes: string | null;
    status: 'pending' | 'preparing' | 'ready' | 'completed';
    created_at: string;
    menu?: {
        category: string;
        image: string | null;
    };
}

interface Order {
    id: number;
    resource_id: number;
    resource: { name: string; id: number };
    items: OrderItem[];
    created_at: string;
}

export default function KitchenDisplay() {
    const [orders, setOrders] = useState < Order[] > ([]);
    const [loading, setLoading] = useState(true);
    const [activeCategory, setActiveCategory] = useState < string > ('TÜMÜ');

    // Extract unique categories from active orders
    const categories = useMemo(() => {
        const cats = new Set < string > (['TÜMÜ']);
        orders.forEach(order => {
            order.items.forEach(item => {
                if (item.menu?.category) {
                    cats.add(item.menu.category);
                }
            });
        });
        return Array.from(cats);
    }, [orders]);

    const fetchOrders = async () => {
        try {
            const res = await api.get('/kds/orders');
            if (res.data.success) {
                const newOrders = res.data.data;

                // Check if there are new orders to play sound
                // Simple check: different length or different last order ID
                const prevLastId = orders.length > 0 ? Math.max(...orders.map(o => o.id)) : 0;
                const newLastId = newOrders.length > 0 ? Math.max(...newOrders.map((o: Order) => o.id)) : 0;

                if (newLastId > prevLastId) {
                    playNotificationSound();
                }

                setOrders(newOrders);
            }
        } catch (err) {
            console.error('KDS Fetch error:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchOrders();
        const interval = setInterval(fetchOrders, 5000); // Refresh every 5 seconds
        return () => clearInterval(interval);
    }, []);

    const updateStatus = async (itemId: number, newStatus: string) => {
        try {
            await api.post(`/kds/items/${itemId}/status`, { status: newStatus });
            fetchOrders();
        } catch (err) {
            console.error('Status update error:', err);
        }
    };

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'pending': return 'bg-red-500 text-white';
            case 'preparing': return 'bg-yellow-500 text-white';
            case 'ready': return 'bg-green-500 text-white';
            default: return 'bg-gray-300 text-gray-700';
        }
    };

    const getElapsedTime = (createdAt: string) => {
        const now = new Date();
        const created = new Date(createdAt);
        const diffMs = now.getTime() - created.getTime();
        const diffMins = Math.floor(diffMs / 60000);
        return diffMins;
    };

    if (loading) return <div className="h-screen flex items-center justify-center"><p className="text-2xl font-black text-gray-400 animate-pulse">MUTFAK EKRANI YÜKLENİYOR...</p></div>;

    return (
        <div className="h-screen bg-gray-900 overflow-hidden flex flex-col">
            {/* Header */}
            {/* Header */}
            <header className="bg-gray-800 border-b-4 border-primary p-4 flex justify-between items-center shadow-xl z-10">
                <div className="flex items-center gap-4">
                    <ChefHat className="h-10 w-10 text-primary" strokeWidth={2.5} />
                    <div>
                        <h1 className="text-3xl font-black text-white uppercase tracking-tight">Mutfak Ekranı</h1>
                        <div className="flex gap-4 mt-1">
                            <p className="text-xs text-gray-400 font-bold">
                                Bekleyen: <span className="text-yellow-500">{orders.reduce((sum, o) => sum + o.items.filter(i => i.status === 'pending').length, 0)}</span>
                            </p>
                            <p className="text-xs text-gray-400 font-bold">
                                Hazırlanan: <span className="text-blue-500">{orders.reduce((sum, o) => sum + o.items.filter(i => i.status === 'preparing').length, 0)}</span>
                            </p>
                        </div>
                    </div>
                </div>

                {/* Category Filters */}
                <div className="flex gap-2 overflow-x-auto max-w-2xl px-4 py-2">
                    {categories.map(cat => (
                        <button
                            key={cat}
                            onClick={() => setActiveCategory(cat)}
                            className={classNames(
                                "px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all whitespace-nowrap",
                                activeCategory === cat
                                    ? "bg-primary text-white shadow-lg scale-105"
                                    : "bg-gray-700 text-gray-300 hover:bg-gray-600"
                            )}
                        >
                            {cat}
                        </button>
                    ))}
                </div>

                <div className="text-right flex flex-col items-end">
                    <div className="flex items-center gap-4">
                        <button
                            onClick={async () => {
                                const pin = prompt('Çıkış günü için Yönetici PIN kodunu girin:');
                                if (pin) {
                                    try {
                                        // Verify master pin (simple endpoint call or check local assumption)
                                        // For now, we will just redirect if pin is entered, assuming staff lock handles security, 
                                        // but user asked for admin exit menu.
                                        // Let's implement robust check.
                                        const res = await api.post('/verify-master-pin', { pin });
                                        if (res.data.success) {
                                            window.location.href = '#/dashboard';
                                        } else {
                                            alert('Hatalı PIN!');
                                        }
                                    } catch (e) {
                                        alert('Doğrulama hatası!');
                                    }
                                }
                            }}
                            className="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 transition-colors"
                        >
                            <span className="hidden sm:inline">Yönetici Çıkışı</span>
                        </button>
                        <p className="text-4xl font-black text-primary font-mono">{new Date().toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' })}</p>
                    </div>
                    <div className="flex items-center justify-end gap-2 mt-1">
                        <div className="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                        <p className="text-[10px] text-gray-400 uppercase tracking-widest">Canlı</p>
                    </div>
                </div>
            </header>

            {/* Orders Grid */}
            <div className="flex-1 overflow-y-auto p-6">
                {orders.length === 0 ? (
                    <div className="h-full flex flex-col items-center justify-center text-gray-600">
                        <Bell size={96} className="mb-6 opacity-20" />
                        <p className="text-3xl font-black uppercase tracking-wide">Bekleyen Sipariş Yok</p>
                        <p className="text-lg text-gray-500 mt-2">Yeni siparişler otomatik olarak burada görünecek</p>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        {orders.map(order => {
                            const elapsed = getElapsedTime(order.created_at);
                            const isOverdue = elapsed > 15;

                            return (
                                <div
                                    key={order.id}
                                    className={`bg-white border-4 rounded-3xl shadow-2xl overflow-hidden transition-all hover:scale-105 ${isOverdue ? 'border-red-600 animate-pulse' : 'border-gray-200'}`}
                                >
                                    {/* Order Header */}
                                    <div className={`p-6 ${isOverdue ? 'bg-red-600' : 'bg-gradient-to-r from-primary to-purple-700'}`}>
                                        <div className="flex justify-between items-start">
                                            <div>
                                                <h3 className="text-3xl font-black text-white uppercase tracking-tight">{order.resource.name}</h3>
                                                <p className="text-sm text-white/80 font-bold mt-1">Sipariş #{order.id}</p>
                                            </div>
                                            <div className="text-right">
                                                <div className="flex items-center gap-2 text-white">
                                                    <Clock size={20} />
                                                    <span className="text-2xl font-black">{elapsed}'</span>
                                                </div>
                                                {isOverdue && <p className="text-xs bg-white text-red-600 px-2 py-1 rounded-full font-black mt-1">GECİKME!</p>}
                                            </div>
                                        </div>
                                    </div>

                                    {/* Items List */}
                                    {/* Items List */}
                                    <div className="p-4 space-y-3 max-h-[calc(100vh-350px)] overflow-y-auto custom-scrollbar">
                                        {order.items
                                            .filter(item => activeCategory === 'TÜMÜ' || item.menu?.category === activeCategory)
                                            .map(item => (
                                                <div key={item.id} className={classNames(
                                                    "border-2 rounded-xl p-3 transition-all",
                                                    item.status === 'completed' ? 'border-green-200 bg-green-50 opacity-60' : 'border-gray-100 hover:border-primary',
                                                    item.status === 'preparing' ? 'border-yellow-400 bg-yellow-50 shadow-md ring-2 ring-yellow-200' : ''
                                                )}>
                                                    <div className="flex justify-between items-start mb-2">
                                                        <div className="flex-1">
                                                            <div className="flex items-center gap-2">
                                                                <span className="text-2xl font-black text-primary">{item.quantity}x</span>
                                                                <h4 className="text-lg font-bold text-gray-900 leading-tight">{item.name}</h4>
                                                            </div>
                                                            {item.menu && (item.menu.image || (item.menu as any).image_url) && (
                                                                <img
                                                                    src={getImageUrl(item.menu.image, (item.menu as any).image_url)}
                                                                    alt={item.name}
                                                                    className="w-12 h-12 object-cover rounded-lg my-1 shadow-sm"
                                                                    onError={(e) => {
                                                                        (e.target as HTMLImageElement).style.display = 'none';
                                                                    }}
                                                                />
                                                            )}
                                                            {item.menu?.category && (
                                                                <span className="text-[10px] text-gray-400 uppercase font-bold tracking-wider">{item.menu.category}</span>
                                                            )}
                                                            {item.notes && (
                                                                <div className="mt-1 flex items-start gap-1 text-red-600 bg-red-50 p-2 rounded-lg">
                                                                    <AlertOctagon size={14} className="mt-0.5 shrink-0" />
                                                                    <p className="text-xs font-bold leading-tight uppercase">{item.notes}</p>
                                                                </div>
                                                            )}
                                                        </div>
                                                    </div>

                                                    {/* Status Badge */}
                                                    <div className="mt-2 text-center">
                                                        <span className={`inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest ${getStatusColor(item.status)}`}>
                                                            {item.status === 'pending' && '⏳ Bekliyor'}
                                                            {item.status === 'preparing' && '👨‍🍳 Hazırlanıyor'}
                                                            {item.status === 'ready' && '✅ Hazır'}
                                                            {item.status === 'completed' && '🎉 Teslim Edildi'}
                                                        </span>
                                                    </div>

                                                    {/* Status Actions */}
                                                    <div className="grid grid-cols-2 gap-2 mt-2">
                                                        {item.status === 'pending' && (
                                                            <button
                                                                onClick={() => updateStatus(item.id, 'preparing')}
                                                                className="col-span-2 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-xs font-black uppercase tracking-wider shadow-sm flex items-center justify-center gap-2"
                                                            >
                                                                <Flame size={14} /> Hazırla
                                                            </button>
                                                        )}

                                                        {item.status === 'preparing' && (
                                                            <button
                                                                onClick={() => updateStatus(item.id, 'ready')}
                                                                className="col-span-2 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-xs font-black uppercase tracking-wider shadow-sm flex items-center justify-center gap-2"
                                                            >
                                                                <CheckCircle size={14} /> Hazır
                                                            </button>
                                                        )}

                                                        {item.status === 'ready' && (
                                                            <button
                                                                onClick={() => updateStatus(item.id, 'completed')}
                                                                className="col-span-2 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs font-black uppercase tracking-wider shadow-sm flex items-center justify-center gap-2"
                                                            >
                                                                <ChefHat size={14} /> Teslim Et
                                                            </button>
                                                        )}
                                                    </div>
                                                </div>
                                            ))}
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                )}
            </div>
        </div>
    );
}
