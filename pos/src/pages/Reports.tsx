import { useState, useEffect } from 'react';
import {
    TrendingUp,
    CreditCard,
    Banknote,
    ShoppingBag,
    Loader2
} from 'lucide-react';
import { useNavigate } from 'react-router-dom';
import api from '../lib/api';
import DashboardLayout from '../components/DashboardLayout';
import classNames from 'classnames';

interface CategorySale {
    name: string;
    qty: number;
    total: number;
}

interface SummaryData {
    total_sales: number;
    order_count: number;
    cash_total: number;
    card_total: number;
    category_sales: CategorySale[];
    period: {
        start: string;
        end: string;
    };
}

export default function Reports() {
    const navigate = useNavigate();
    const [loading, setLoading] = useState(true);
    const [data, setData] = useState < SummaryData | null > (null);
    const [dateRange, setDateRange] = useState < 'today' | 'yesterday' | 'last7' | 'thisMonth' > ('today');

    const fetchSummary = async (range = dateRange) => {
        setLoading(true);
        try {
            let start_date = '';
            let end_date = '';

            const today = new Date();
            if (range === 'today') {
                start_date = today.toISOString().split('T')[0];
            } else if (range === 'yesterday') {
                const yesterday = new Date();
                yesterday.setDate(yesterday.getDate() - 1);
                start_date = yesterday.toISOString().split('T')[0];
                end_date = yesterday.toISOString().split('T')[0];
            } else if (range === 'last7') {
                const last7 = new Date();
                last7.setDate(last7.getDate() - 7);
                start_date = last7.toISOString().split('T')[0];
            } else if (range === 'thisMonth') {
                const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                start_date = firstDay.toISOString().split('T')[0];
            }

            const res = await api.get('/summary', { params: { start_date, end_date } });
            if (res.data.success) {
                setData(res.data.data);
            }
        } catch (err) {
            console.error('Rapor yükleme hatası:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchSummary();
    }, [dateRange]);

    const StatsCard = ({ title, value, icon: Icon, color, subValue, onClick }: any) => (
        <div
            onClick={onClick}
            className="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group cursor-pointer active:scale-[0.98]"
        >
            <div className="flex items-start justify-between mb-6">
                <div className={classNames("p-3 rounded-2xl group-hover:scale-110 transition-transform", color)}>
                    <Icon className="w-6 h-6 text-white" />
                </div>
            </div>
            <h3 className="text-gray-400 font-black text-[10px] uppercase tracking-widest mb-2">{title}</h3>
            <div className="text-3xl font-black text-gray-900 tracking-tight mb-1">
                {value}
            </div>
            {subValue && (
                <p className="text-xs font-bold text-gray-400 italic">{subValue}</p>
            )}
        </div>
    );

    return (
        <DashboardLayout>
            <div className="flex flex-col gap-10 pb-12 animate-in fade-in slide-in-from-bottom-4 duration-1000">
                {/* Header */}
                <div className="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                    <div>
                        <div className="flex items-center gap-3 mb-2">
                            <div className="p-2.5 bg-primary/10 rounded-xl text-primary">
                                <TrendingUp size={24} />
                            </div>
                            <h2 className="text-sm font-black text-primary uppercase tracking-[0.2em]">Veri Analitiği</h2>
                        </div>
                        <h1 className="text-5xl font-black text-gray-900 tracking-tighter">İşletme Raporları</h1>
                        <p className="text-gray-500 mt-2 font-medium">Satış trendlerini ve ödeme dağılımlarını anlık takip edin.</p>
                    </div>

                    <div className="flex items-center p-1.5 bg-white border border-gray-100 rounded-3xl shadow-sm">
                        {[
                            { id: 'today', label: 'Bugün' },
                            { id: 'yesterday', label: 'Dün' },
                            { id: 'last7', label: 'Son 7 Gün' },
                            { id: 'thisMonth', label: 'Bu Ay' }
                        ].map((range) => (
                            <button
                                key={range.id}
                                onClick={() => setDateRange(range.id as any)}
                                className={classNames(
                                    "px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all",
                                    dateRange === range.id ? "bg-primary text-white shadow-lg shadow-primary/20" : "text-gray-400 hover:text-gray-900"
                                )}
                            >
                                {range.label}
                            </button>
                        ))}
                    </div>
                </div>

                {/* KPI Cards & Charts Area */}
                {loading ? (
                    <div className="flex flex-col items-center justify-center py-40 bg-white/50 rounded-[3rem] border border-dashed border-gray-200">
                        <Loader2 size={48} className="text-primary animate-spin mb-4" />
                        <p className="text-gray-500 font-black uppercase tracking-widest text-xs">Raporlar Hazırlanıyor...</p>
                    </div>
                ) : (
                    <>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <StatsCard
                                title="TOPLAM SATIŞ"
                                value={`₺${(data?.total_sales || 0).toLocaleString('tr-TR')}`}
                                icon={TrendingUp}
                                color="bg-primary"
                                subValue={`${data?.order_count || 0} Tamamlanan Adisyon`}
                                onClick={() => navigate('/invoices')}
                            />
                            <StatsCard
                                title="NAKİT TAHSİLAT"
                                value={`₺${(data?.cash_total || 0).toLocaleString('tr-TR')}`}
                                icon={Banknote}
                                color="bg-emerald-500"
                                subValue={`Payda: %${data?.total_sales ? Math.round((data.cash_total / data.total_sales) * 100) : 0}`}
                            />
                            <StatsCard
                                title="KARTLI ÖDEME"
                                value={`₺${(data?.card_total || 0).toLocaleString('tr-TR')}`}
                                icon={CreditCard}
                                color="bg-blue-600"
                                subValue={`Payda: %${data?.total_sales ? Math.round((data.card_total / data.total_sales) * 100) : 0}`}
                            />
                            <StatsCard
                                title="ORTALAMA CİRO"
                                value={`₺${data?.order_count ? Math.round(data.total_sales / data.order_count).toLocaleString('tr-TR') : 0}`}
                                icon={ShoppingBag}
                                color="bg-amber-500"
                                subValue="İşlem Başına Verim"
                            />
                        </div>

                        {/* Charts Area */}
                        <div className="grid lg:grid-cols-3 gap-8">
                            {/* Payment Distribution (Donut Chart) */}
                            <div className="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm flex flex-col items-center">
                                <h4 className="text-sm font-black text-gray-900 uppercase tracking-widest mb-10 w-full">Ödeme Dağılımı</h4>
                                <div className="relative w-48 h-48 mb-10">
                                    <svg className="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                        <circle cx="18" cy="18" r="16" fill="transparent" stroke="#f1f5f9" strokeWidth="4" />
                                        {data?.total_sales ? (
                                            <>
                                                {/* Cash Segment */}
                                                <circle
                                                    cx="18" cy="18" r="16" fill="transparent"
                                                    stroke="#10b981" strokeWidth="4"
                                                    strokeDasharray={`${(data.cash_total / data.total_sales) * 100} 100`}
                                                    strokeLinecap="round"
                                                />
                                                {/* Card Segment - Offset by Cash */}
                                                <circle
                                                    cx="18" cy="18" r="16" fill="transparent"
                                                    stroke="#2563eb" strokeWidth="4"
                                                    strokeDasharray={`${(data.card_total / data.total_sales) * 100} 100`}
                                                    strokeDashoffset={`-${(data.cash_total / data.total_sales) * 100}`}
                                                    strokeLinecap="round"
                                                />
                                            </>
                                        ) : null}
                                    </svg>
                                    <div className="absolute inset-0 flex flex-col items-center justify-center">
                                        <span className="text-2xl font-black text-gray-900 tracking-tighter">
                                            ₺{data?.total_sales ? (data.total_sales >= 1000 ? `${Math.round(data.total_sales / 1000)}k` : Math.round(data.total_sales)) : 0}
                                        </span>
                                        <span className="text-[10px] font-black text-gray-400 uppercase">Ciro</span>
                                    </div>
                                </div>
                                <div className="w-full space-y-3">
                                    <div className="flex items-center justify-between">
                                        <div className="flex items-center gap-2">
                                            <div className="w-3 h-3 rounded-full bg-emerald-500" />
                                            <span className="text-[10px] font-black text-gray-500 uppercase">Nakit</span>
                                        </div>
                                        <span className="text-sm font-black text-gray-900">₺{(data?.cash_total || 0).toLocaleString()}</span>
                                    </div>
                                    <div className="flex items-center justify-between">
                                        <div className="flex items-center gap-2">
                                            <div className="w-3 h-3 rounded-full bg-blue-600" />
                                            <span className="text-[10px] font-black text-gray-500 uppercase">Kredi Kartı</span>
                                        </div>
                                        <span className="text-sm font-black text-gray-900">₺{(data?.card_total || 0).toLocaleString()}</span>
                                    </div>
                                </div>
                            </div>

                            {/* Sales by Product (Bar Chart) */}
                            <div className="lg:col-span-2 bg-white p-10 rounded-[3rem] border border-gray-100 shadow-sm">
                                <div className="flex justify-between items-center mb-10">
                                    <h4 className="text-sm font-black text-gray-900 uppercase tracking-widest leading-none">En Çok Satan Ürünler</h4>
                                    <span className="text-[10px] font-black text-primary uppercase tracking-widest italic leading-none">İlk 10 Sıralama</span>
                                </div>

                                <div className="space-y-6">
                                    {data?.category_sales && data.category_sales.length > 0 ? (
                                        data.category_sales.map((sale, idx) => {
                                            const maxTotal = data.category_sales[0].total;
                                            const percentage = (sale.total / maxTotal) * 100;
                                            return (
                                                <div key={idx} className="group">
                                                    <div className="flex justify-between items-end mb-2">
                                                        <div>
                                                            <span className="text-[9px] font-black text-primary uppercase tracking-widest mb-0.5 block italic">Rank #{idx + 1}</span>
                                                            <span className="text-sm font-black text-gray-800 uppercase tracking-tight">{sale.name}</span>
                                                        </div>
                                                        <div className="text-right">
                                                            <span className="text-xs font-black text-gray-900 leading-none">₺{sale.total.toLocaleString()}</span>
                                                            <p className="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{sale.qty} Adet</p>
                                                        </div>
                                                    </div>
                                                    <div className="h-1.5 w-full bg-gray-50 rounded-full overflow-hidden">
                                                        <div
                                                            className="h-full bg-gradient-to-r from-primary to-indigo-500 rounded-full transition-all duration-1000 group-hover:from-indigo-500 group-hover:to-primary"
                                                            style={{ width: `${percentage}%` }}
                                                        />
                                                    </div>
                                                </div>
                                            );
                                        })
                                    ) : (
                                        <div className="h-full flex flex-col items-center justify-center py-20 text-center">
                                            <p className="text-gray-400 font-bold italic">Bu dönemde henüz satış verisi yok.</p>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>

                        {/* Insights Banner */}
                        <div className="bg-slate-900 rounded-[3rem] p-12 text-white shadow-2xl relative overflow-hidden group">
                            <div className="absolute inset-0 bg-primary/10 blur-[80px] rounded-full -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition duration-1000"></div>
                            <div className="relative z-10 flex flex-col md:flex-row items-center justify-between gap-10">
                                <div className="max-w-xl">
                                    <h2 className="text-3xl font-black mb-4 tracking-tighter italic uppercase leading-none">Veri Güdümlü <br /> Yönetİm Paneli.</h2>
                                    <p className="text-slate-400 text-sm font-bold leading-relaxed italic uppercase tracking-widest opacity-80">Raporlarınızı PDF veya Excel olarak dışa aktarmak için Faturalar sekmesini kullanabilirsiniz.</p>
                                </div>
                                <button
                                    onClick={() => navigate('/invoices')}
                                    className="px-10 py-5 bg-white text-slate-900 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-primary hover:text-white transition-all active:scale-95 shadow-xl"
                                >
                                    Faturalara Gİt <ChevronRight className="inline ml-2" size={14} />
                                </button>
                            </div>
                        </div>
                    </>
                )}
            </div>
        </DashboardLayout>
    );
}

function ChevronRight({ className, size }: { className?: string, size?: number }) {
    return (
        <svg fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" viewBox="0 0 24 24" className={className} width={size} height={size}>
            <path d="M9 18l6-6-6-6"></path>
        </svg>
    );
}
