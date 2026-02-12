import { useState, useEffect } from 'react';
import DashboardLayout from '../components/DashboardLayout';
import { Receipt, Loader2, ChevronRight } from 'lucide-react';
import api from '../lib/api';
import classNames from 'classnames';

interface OrderItem {
    id: number;
    name: string;
    quantity: number;
    unit_price: number;
    total_price: number;
}

interface Order {
    id: number;
    resource_id: number;
    resource?: {
        name: string;
    };
    status: 'active' | 'completed' | 'cancelled';
    payment_status: 'unpaid' | 'partial' | 'paid';
    total_amount: number;
    paid_amount: number;
    opened_at: string;
    closed_at?: string;
    created_at: string;
    items: OrderItem[];
}

export default function Orders() {
    const [orders, setOrders] = useState < Order[] > ([]);
    const [loading, setLoading] = useState(true);
    const [filter, setFilter] = useState < 'active' | 'completed' | 'all' > ('active');

    const fetchOrders = async () => {
        setLoading(true);
        try {
            const res = await api.get('/orders');
            if (res.data.success) {
                setOrders(res.data.data);
            }
        } catch (err) {
            console.error('Siparişler yüklenemedi:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchOrders();
    }, []);

    const filteredOrders = orders.filter(order => {
        if (filter === 'all') return true;
        return order.status === filter;
    });

    return (
        <DashboardLayout>
            <div className="flex flex-col gap-8">
                {/* Header Section */}
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 className="text-3xl font-bold text-gray-900 tracking-tight">Sipariş Geçmişi</h2>
                        <p className="text-gray-500 mt-1">İşletmenizdeki tüm aktif ve tamamlanmış siparişleri yönetin.</p>
                    </div>

                    <div className="flex items-center gap-2 bg-white p-1 rounded-2xl border border-gray-100 shadow-sm overflow-x-auto scrollbar-hide">
                        <button
                            onClick={() => setFilter('active')}
                            className={classNames(
                                "px-6 py-2.5 rounded-xl text-sm font-bold transition-all border-2",
                                filter === 'active'
                                    ? "bg-[#6200EE] text-white border-[#6200EE] shadow-lg shadow-purple-200"
                                    : "bg-white text-gray-500 border-gray-100 hover:border-[#6200EE]/30 hover:text-[#6200EE]"
                            )}
                        >
                            Aktif
                        </button>
                        <button
                            onClick={() => setFilter('completed')}
                            className={classNames(
                                "px-6 py-2.5 rounded-xl text-sm font-bold transition-all border-2",
                                filter === 'completed'
                                    ? "bg-[#6200EE] text-white border-[#6200EE] shadow-lg shadow-purple-200"
                                    : "bg-white text-gray-500 border-gray-100 hover:border-[#6200EE]/30 hover:text-[#6200EE]"
                            )}
                        >
                            Tamamlanan
                        </button>
                        <button
                            onClick={() => setFilter('all')}
                            className={classNames(
                                "px-6 py-2.5 rounded-xl text-sm font-bold transition-all border-2",
                                filter === 'all'
                                    ? "bg-[#6200EE] text-white border-[#6200EE] shadow-lg shadow-purple-200"
                                    : "bg-white text-gray-500 border-gray-100 hover:border-[#6200EE]/30 hover:text-[#6200EE]"
                            )}
                        >
                            Tümü
                        </button>
                    </div>
                </div>

                {/* Orders Content */}
                {loading ? (
                    <div className="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border border-dashed border-gray-200">
                        <Loader2 className="h-10 w-10 text-primary animate-spin mb-4" />
                        <p className="text-gray-500 font-medium">Siparişler yükleniyor...</p>
                    </div>
                ) : filteredOrders.length > 0 ? (
                    <div className="grid grid-cols-1 gap-4">
                        {filteredOrders.map((order) => (
                            <div
                                key={order.id}
                                className="bg-white border border-gray-200 rounded-2xl p-6 hover:border-primary/50 transition-all group shadow-sm hover:shadow-md"
                            >
                                <div className="flex flex-wrap items-center justify-between gap-4">
                                    <div className="flex items-center gap-4">
                                        <div className={classNames(
                                            "w-12 h-12 rounded-xl flex items-center justify-center shadow-sm",
                                            order.status === 'active' ? "bg-orange-50 text-orange-600" : "bg-green-50 text-green-600"
                                        )}>
                                            <Receipt size={24} />
                                        </div>
                                        <div>
                                            <div className="flex items-center gap-2">
                                                <h3 className="text-lg font-black text-gray-900">Sipariş #{order.id}</h3>
                                                <span className={classNames(
                                                    "px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider",
                                                    order.status === 'active' ? "bg-orange-100 text-orange-700" : "bg-green-100 text-green-700"
                                                )}>
                                                    {order.status === 'active' ? 'AKTİF' : 'TAMAMLANDI'}
                                                </span>
                                            </div>
                                            <p className="text-sm text-gray-500 font-medium capitalize">
                                                Masa: {order.resource?.name || 'Bilinmiyor'} • {new Date(order.created_at).toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' })}
                                            </p>
                                        </div>
                                    </div>

                                    <div className="flex items-center gap-8">
                                        <div className="text-right">
                                            <p className="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Toplam Tutar</p>
                                            <p className="text-xl font-black text-primary">{parseFloat(String(order.total_amount)).toLocaleString('tr-TR', { minimumFractionDigits: 2 })} ₺</p>
                                        </div>
                                        <div className="text-right hidden sm:block">
                                            <p className="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Ödeme Durumu</p>
                                            <span className={classNames(
                                                "px-3 py-1 rounded-full text-xs font-bold",
                                                order.payment_status === 'paid' ? "bg-green-100 text-green-700" : "bg-red-100 text-red-700"
                                            )}>
                                                {order.payment_status === 'paid' ? 'ÖDENDİ' : 'ÖDEME BEKLİYOR'}
                                            </span>
                                        </div>
                                        <button
                                            title="Detayları Gör"
                                            className="p-3 bg-gray-50 rounded-xl text-gray-400 group-hover:bg-primary group-hover:text-white transition-all shadow-sm"
                                        >
                                            <ChevronRight size={20} strokeWidth={3} />
                                        </button>
                                    </div>
                                </div>

                                {/* Items Preview */}
                                <div className="mt-6 pt-6 border-t border-gray-50 flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                                    {order.items.map((item, idx) => (
                                        <div key={idx} className="px-3 py-1.5 bg-gray-50 rounded-lg border border-gray-100 flex items-center gap-2 shrink-0">
                                            <span className="text-xs font-black text-primary">{item.quantity}x</span>
                                            <span className="text-xs font-bold text-gray-600 truncate max-w-[120px]">{item.name}</span>
                                        </div>
                                    ))}
                                    {order.items.length === 0 && (
                                        <span className="text-xs text-gray-400 font-medium italic">Ürün bulunmuyor</span>
                                    )}
                                </div>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="flex flex-col items-center justify-center py-24 bg-white rounded-3xl border border-dashed border-gray-200">
                        <div className="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                            <Receipt size={40} className="text-gray-300" />
                        </div>
                        <h3 className="text-xl font-bold text-gray-900 mb-2">Henüz sipariş yok</h3>
                        <p className="text-gray-500 font-medium max-w-xs text-center">
                            Seçilen filtreye uygun herhangi bir sipariş bulunamadı.
                        </p>
                    </div>
                )}
            </div>
        </DashboardLayout>
    );
}
