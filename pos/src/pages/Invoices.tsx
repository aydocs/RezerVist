import { useState, useEffect } from 'react';
import DashboardLayout from '../components/DashboardLayout';
import {
    FileText,
    Search,
    ChevronDown,
    CreditCard,
    Banknote,
    Calendar,
    ArrowUpRight,
    Loader2,
    Printer,
    Download,
    Eye,
    Smartphone
} from 'lucide-react';
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
    payment_method?: 'cash' | 'credit_card' | 'iyzico_app' | string;
    total_amount: number;
    paid_amount: number;
    opened_at: string;
    closed_at?: string;
    created_at: string;
    items: OrderItem[];
}

export default function Invoices() {
    const [orders, setOrders] = useState < Order[] > ([]);
    const [loading, setLoading] = useState(true);
    const [searchQuery, setSearchQuery] = useState('');
    const [statusFilter, setStatusFilter] = useState < 'all' | 'completed' | 'active' > ('all');
    const [methodFilter, setMethodFilter] = useState < 'all' | 'cash' | 'credit_card' | 'iyzico_app' > ('all');
    const [dateRange, setDateRange] = useState < 'today' | 'yesterday' | 'last7' | 'thisMonth' > ('today');
    const [expandedId, setExpandedId] = useState < number | null > (null);

    const fetchOrders = async (range = dateRange) => {
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

            const res = await api.get('/orders', { params: { start_date, end_date } });
            if (res.data.success) {
                setOrders(res.data.data);
            }
        } catch (err) {
            console.error('Faturalar yüklenemedi:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchOrders();
    }, [dateRange]);

    const filteredOrders = orders.filter(order => {
        const matchesSearch = String(order.id).includes(searchQuery) ||
            order.resource?.name?.toLowerCase().includes(searchQuery.toLowerCase());
        const matchesStatus = statusFilter === 'all' || order.status === statusFilter;
        const matchesMethod = methodFilter === 'all' || order.payment_method === methodFilter;

        return matchesSearch && matchesStatus && matchesMethod;
    });

    // KPI Calculations
    const totalRevenue = filteredOrders.reduce((sum, o) => sum + Number(o.total_amount), 0);

    const cashTotal = filteredOrders.filter(o => {
        const method = (o.payment_method || '').toLowerCase();
        return method === 'cash' || method === 'nakit';
    }).reduce((sum, o) => sum + Number(o.total_amount), 0);

    const cardTotal = filteredOrders.filter(o => {
        const method = (o.payment_method || '').toLowerCase();
        return method === 'credit_card' || method === 'card' || method === 'creditcard' || method === 'kredi_karti';
    }).reduce((sum, o) => sum + Number(o.total_amount), 0);

    const appTotal = filteredOrders.filter(o => {
        const method = (o.payment_method || '').toLowerCase();
        return method === 'iyzico_app';
    }).reduce((sum, o) => sum + Number(o.total_amount), 0);



    const handleExport = () => {
        if (filteredOrders.length === 0) return alert('Dışa aktarılacak veri yok.');

        const headers = ['Fatura ID', 'Tarih', 'Masa/Alan', 'Ürün Sayısı', 'Toplam Tutar', 'Ödeme Durumu', 'Ödeme Yöntemi'];
        const csvContent = [
            "\uFEFF" + headers.join(';'), // Add BOM for Excel Turkish characters
            ...filteredOrders.map(o => [
                `#${o.id}`,
                new Date(o.created_at).toLocaleString('tr-TR'),
                o.resource?.name || 'Paket/Diğer',
                o.items.length,
                o.total_amount.toFixed(2),
                o.payment_status === 'paid' ? 'Tahsil Edildi' : 'Bekliyor',
                o.payment_method === 'cash' ? 'Nakit' : o.payment_method === 'credit_card' ? 'Kredi Kartı' : '---'
            ].join(';'))
        ].join('\n');

        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `RezerVist_Finansal_Rapor_${dateRange}_${new Date().toISOString().split('T')[0]}.csv`;
        link.click();
    };

    const handleDailySummary = () => {
        // Simple Z-Report Logic
        const today = new Date().toLocaleDateString('tr-TR');
        const todayOrders = orders.filter(o => new Date(o.created_at).toLocaleDateString('tr-TR') === today);

        const total = todayOrders.reduce((sum, o) => sum + Number(o.total_amount), 0);
        const count = todayOrders.length;
        const cash = todayOrders.filter(o => o.payment_method === 'cash').reduce((sum, o) => sum + Number(o.total_amount), 0);
        const card = todayOrders.filter(o => o.payment_method === 'credit_card').reduce((sum, o) => sum + Number(o.total_amount), 0);

        alert(
            `GÜNLÜK İCMAL (${today})\n\n` +
            `Toplam Ciro: ₺${total.toLocaleString('tr-TR')}\n` +
            `İşlem Adedi: ${count}\n` +
            `----------------\n` +
            `Nakit: ₺${cash.toLocaleString('tr-TR')}\n` +
            `Kredi Kartı: ₺${card.toLocaleString('tr-TR')}`
        );
    };

    return (
        <DashboardLayout>
            <div className="flex flex-col gap-8 pb-12 animate-in fade-in slide-in-from-bottom-4 duration-700">
                {/* Header Section */}
                <div className="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                    <div>
                        <div className="flex items-center gap-3 mb-2">
                            <div className="p-2.5 bg-primary/10 rounded-xl text-primary">
                                <FileText size={24} />
                            </div>
                            <h2 className="text-sm font-black text-primary uppercase tracking-[0.2em]">Finansal Takip</h2>
                        </div>
                        <h1 className="text-4xl font-black text-gray-900 tracking-tight">Faturalar & Tahsilatlar</h1>
                        <p className="text-gray-500 mt-2 font-medium">İşletmenizin tüm finansal kayıtlarını detaylı olarak inceleyin.</p>
                    </div>

                    <div className="flex items-center gap-3">
                        <button
                            onClick={handleExport}
                            className="flex items-center gap-2 px-5 py-3 bg-white border border-gray-100 text-gray-700 rounded-2xl font-bold shadow-sm hover:shadow-md transition-all active:scale-95"
                        >
                            <Download size={18} />
                            Dışa Aktar
                        </button>
                        <button
                            onClick={handleDailySummary}
                            className="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-primary/30 hover:shadow-primary/40 transition-all active:scale-95"
                        >
                            <Printer size={18} />
                            Günlük İcmal
                        </button>
                    </div>
                </div>

                {/* KPI Summary Cards */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <KPICard
                        title="Toplam Ciro"
                        value={totalRevenue}
                        icon={<ArrowUpRight size={20} />}
                        subtitle={`${filteredOrders.length} İşlem`}
                        trend="+12%"
                        color="primary"
                    />
                    <KPICard
                        title="Nakit Tahsilat"
                        value={cashTotal}
                        icon={<Banknote size={20} />}
                        subtitle={`${filteredOrders.filter(o => o.payment_method === 'cash').length} Adisyon`}
                        color="emerald"
                    />
                    <KPICard
                        title="Kartlı Ödeme"
                        value={cardTotal}
                        icon={<CreditCard size={20} />}
                        subtitle={`${filteredOrders.filter(o => o.payment_method === 'credit_card').length} Adisyon`}
                        color="blue"
                    />
                    <KPICard
                        title="QR App Ödemesi"
                        value={appTotal}
                        icon={<Smartphone size={20} />}
                        subtitle={`${filteredOrders.filter(o => o.payment_method === 'iyzico_app').length} Adisyon`}
                        color="violet"
                    />
                </div>

                {/* Filters & Search */}
                <div className="bg-white/60 backdrop-blur-xl border border-white p-8 rounded-[3rem] shadow-xl shadow-gray-200/40">
                    <div className="flex flex-col gap-6">
                        <div className="flex flex-col md:flex-row items-center gap-4">
                            <div className="relative flex-1 group">
                                <Search className="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors" size={20} />
                                <input
                                    type="text"
                                    placeholder="Fatura No veya Masa adı ile ara..."
                                    value={searchQuery}
                                    onChange={(e) => setSearchQuery(e.target.value)}
                                    className="w-full pl-14 pr-6 py-4.5 bg-gray-50 border-none rounded-[1.5rem] focus:ring-4 focus:ring-primary/10 transition-all font-bold text-gray-700"
                                />
                            </div>

                            <div className="flex items-center gap-2 p-1.5 bg-gray-100/50 rounded-[1.8rem]">
                                {[
                                    { id: 'today', label: 'Bugün' },
                                    { id: 'yesterday', label: 'Dün' },
                                    { id: 'last7', label: '7 Gün' },
                                    { id: 'thisMonth', label: 'Bu Ay' }
                                ].map((range) => (
                                    <button
                                        key={range.id}
                                        onClick={() => setDateRange(range.id as any)}
                                        className={classNames(
                                            "px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all",
                                            dateRange === range.id ? "bg-white text-primary shadow-md" : "text-gray-400 hover:text-gray-900"
                                        )}
                                    >
                                        {range.label}
                                    </button>
                                ))}
                            </div>
                        </div>

                        <div className="h-px bg-gray-100 w-full" />

                        <div className="flex flex-wrap items-center gap-4 justify-between">
                            <div className="flex items-center gap-4">
                                <FilterGroup
                                    label="Ödeme Durumu"
                                    current={statusFilter}
                                    options={[
                                        { id: 'all', label: 'Hepsi' },
                                        { id: 'completed', label: 'Ödenmiş' },
                                        { id: 'active', label: 'Bekleyen' }
                                    ]}
                                    onChange={setStatusFilter as any}
                                />
                                <FilterGroup
                                    label="Yöntem"
                                    current={methodFilter}
                                    options={[
                                        { id: 'all', label: 'Hepsi' },
                                        { id: 'cash', label: 'Nakit' },
                                        { id: 'credit_card', label: 'Kart' },
                                        { id: 'iyzico_app', label: 'QR App' }
                                    ]}
                                    onChange={setMethodFilter as any}
                                />
                            </div>
                            <div className="text-[10px] font-black text-gray-300 uppercase tracking-[0.2em] italic">
                                {filteredOrders.length} Kayıt Listeleniyor
                            </div>
                        </div>
                    </div>
                </div>

                {/* Invoices List */}
                <div className="space-y-4">
                    {loading ? (
                        <div className="flex flex-col items-center justify-center py-32 bg-white/50 rounded-[3rem] border border-dashed border-gray-200">
                            <Loader2 size={48} className="text-primary animate-spin mb-4" />
                            <p className="text-gray-500 font-black uppercase tracking-widest text-xs">Veriler Hazırlanıyor...</p>
                        </div>
                    ) : filteredOrders.length > 0 ? (
                        filteredOrders.map((order) => (
                            <InvoiceRow
                                key={order.id}
                                order={order}
                                isExpanded={expandedId === order.id}
                                onToggle={() => setExpandedId(expandedId === order.id ? null : order.id)}
                            />
                        ))
                    ) : (
                        <div className="flex flex-col items-center justify-center py-32 bg-white/50 rounded-[3rem] border border-dashed border-gray-200">
                            <div className="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                                <Search size={40} className="text-gray-300" />
                            </div>
                            <h3 className="text-xl font-bold text-gray-900 mb-2">Kayıt Bulunamadı</h3>
                            <p className="text-gray-500 font-medium">Arama kriterlerinize uygun herhangi bir fatura bulunamadı.</p>
                        </div>
                    )}
                </div>
            </div>
        </DashboardLayout>
    );
}

function KPICard({ title, value, icon, subtitle, trend, color }: any) {
    const colors: any = {
        primary: 'bg-primary text-white shadow-primary/20',
        emerald: 'bg-emerald-500 text-white shadow-emerald-500/20',
        blue: 'bg-blue-600 text-white shadow-blue-600/20',
        amber: 'bg-amber-500 text-white shadow-amber-500/20',
    };

    return (
        <div className="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 group">
            <div className="flex items-start justify-between mb-4">
                <div className={`p-3 rounded-2xl ${colors[color]} group-hover:scale-110 transition-transform duration-500`}>
                    {icon}
                </div>
                {trend && (
                    <span className="flex items-center gap-1 text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full uppercase tracking-widest">
                        {trend}
                    </span>
                )}
            </div>
            <p className="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{title}</p>
            <h3 className="text-2xl font-black text-gray-900 tracking-tighter mb-1">
                {typeof value === 'number' && !title.includes('ADEDİ')
                    ? `₺${value.toLocaleString('tr-TR', { minimumFractionDigits: 2 })}`
                    : value}
            </h3>
            <p className="text-xs font-bold text-gray-400">{subtitle}</p>
        </div>
    );
}

function FilterGroup({ label: _label, current, options, onChange }: { label: string, current: string, options: any[], onChange: (id: any) => void }) {
    return (
        <div className="flex items-center p-1.5 bg-gray-50 rounded-2xl gap-1 shrink-0">
            {options.map((opt) => (
                <button
                    key={opt.id}
                    onClick={() => onChange(opt.id)}
                    className={classNames(
                        "px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all",
                        current === opt.id
                            ? "bg-white text-primary shadow-sm"
                            : "text-gray-400 hover:text-gray-600"
                    )}
                >
                    {opt.label}
                </button>
            ))}
        </div>
    );
}

function InvoiceRow({ order, isExpanded, onToggle }: { order: Order, isExpanded: boolean, onToggle: () => void }) {
    return (
        <div className={classNames(
            "bg-white border rounded-[2rem] transition-all duration-500 overflow-hidden",
            isExpanded ? "border-primary shadow-2xl shadow-primary/5 ring-4 ring-primary/5" : "border-gray-100 shadow-sm hover:border-gray-300"
        )}>
            <div
                onClick={onToggle}
                className="p-6 flex flex-wrap items-center justify-between gap-6 cursor-pointer"
            >
                <div className="flex items-center gap-4">
                    <div className={classNames(
                        "w-14 h-14 rounded-2xl flex items-center justify-center transition-all duration-500",
                        order.payment_status === 'paid' ? "bg-emerald-50 text-emerald-600" : "bg-primary/5 text-primary",
                        isExpanded && "scale-110"
                    )}>
                        <FileText size={28} />
                    </div>
                    <div>
                        <div className="flex items-center gap-3 mb-1">
                            <h3 className="text-lg font-black text-gray-900 tracking-tight">#{order.id.toString().padStart(5, '0')}</h3>
                            <span className={classNames(
                                "px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-[0.1em]",
                                order.payment_status === 'paid' ? "bg-emerald-100 text-emerald-700" : "bg-amber-100 text-amber-700"
                            )}>
                                {order.payment_status === 'paid' ? 'Tahsil Edildi' : 'Ödeme Bekliyor'}
                            </span>
                        </div>
                        <div className="flex items-center gap-3 text-sm text-gray-400 font-bold">
                            <span className="flex items-center gap-1.5"><Calendar size={14} /> {new Date(order.created_at).toLocaleDateString('tr-TR')}</span>
                            <span className="w-1 h-1 rounded-full bg-gray-300" />
                            <span>Masa: {order.resource?.name || 'Paket'}</span>
                        </div>
                    </div>
                </div>

                <div className="flex items-center gap-10">
                    <div className="text-right">
                        <p className="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Ödeme Yöntemi</p>
                        <div className="flex items-center justify-end gap-2 text-gray-700 font-black">
                            {(() => {
                                const method = (order.payment_method || '').toLowerCase();
                                if (method === 'cash' || method === 'nakit') {
                                    return <><Banknote size={16} className="text-emerald-500" /><span className="text-sm uppercase tracking-tighter">Nakit</span></>;
                                } else if (method === 'credit_card' || method === 'card' || method === 'creditcard' || method === 'kredi_karti') {
                                    return <><CreditCard size={16} className="text-blue-500" /><span className="text-sm uppercase tracking-tighter">Kredi Kartı</span></>;
                                } else if (method === 'iyzico_app') {
                                    return <><Smartphone size={16} className="text-violet-500" /><span className="text-sm uppercase tracking-tighter">QR App</span></>;
                                } else {
                                    return <span className="text-sm text-gray-400">---</span>;
                                }
                            })()}
                        </div>
                    </div>

                    <div className="text-right">
                        <p className="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Toplam Tutar</p>
                        <p className="text-2xl font-black text-gray-900 tracking-tighter">
                            ₺{parseFloat(String(order.total_amount)).toLocaleString('tr-TR', { minimumFractionDigits: 2 })}
                        </p>
                    </div>

                    <div className={classNames(
                        "p-3 rounded-xl transition-all duration-500",
                        isExpanded ? "bg-primary text-white rotate-180" : "bg-gray-50 text-gray-400 group-hover:bg-primary/10"
                    )}>
                        <ChevronDown size={20} />
                    </div>
                </div>
            </div>

            {/* Expanded Content */}
            <div className={classNames(
                "transition-all duration-500 ease-in-out",
                isExpanded ? "max-h-[1000px] border-t border-gray-50 pb-8" : "max-h-0"
            )}>
                <div className="p-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        {/* Items Table */}
                        <div>
                            <h4 className="text-sm font-black text-gray-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <span className="w-1.5 h-1.5 rounded-full bg-primary" />
                                Adisyon Detayı
                            </h4>
                            <div className="space-y-3">
                                {order.items.map((item) => (
                                    <div key={item.id} className="flex items-center justify-between p-4 bg-gray-50/50 rounded-2xl border border-gray-100 hover:bg-gray-50 transition-colors">
                                        <div className="flex items-center gap-4">
                                            <div className="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center font-black text-primary text-sm">
                                                {item.quantity}x
                                            </div>
                                            <div>
                                                <p className="text-sm font-bold text-gray-800">{item.name}</p>
                                                <p className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Adet: ₺{parseFloat(String(item.unit_price)).toLocaleString('tr-TR')}</p>
                                            </div>
                                        </div>
                                        <p className="font-black text-gray-900">₺{parseFloat(String(item.total_price)).toLocaleString('tr-TR', { minimumFractionDigits: 2 })}</p>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Order Summary & Actions */}
                        <div className="flex flex-col">
                            <h4 className="text-sm font-black text-gray-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <span className="w-1.5 h-1.5 rounded-full bg-primary" />
                                Tahsilat Özeti
                            </h4>
                            <div className="bg-gray-900 rounded-[2rem] p-8 text-white shadow-2xl relative overflow-hidden flex-1 flex flex-col justify-between">
                                {/* Decor */}
                                <div className="absolute top-0 right-0 w-32 h-32 bg-primary/20 blur-3xl rounded-full" />

                                <div className="space-y-4 relative z-10">
                                    <div className="flex justify-between items-center text-gray-400">
                                        <span className="text-xs font-bold uppercase">Ara Toplam</span>
                                        <span className="font-bold">₺{parseFloat(String(order.total_amount)).toLocaleString('tr-TR')}</span>
                                    </div>
                                    <div className="flex justify-between items-center text-gray-400">
                                        <span className="text-xs font-bold uppercase">KDV (%10)</span>
                                        <span className="font-bold">Dahil</span>
                                    </div>
                                    <div className="pt-4 border-t border-white/10 flex justify-between items-end">
                                        <div>
                                            <p className="text-[10px] font-black text-primary uppercase tracking-widest">NET TAHSİLAT</p>
                                            <h5 className="text-4xl font-black tracking-tighter">₺{parseFloat(String(order.total_amount)).toLocaleString('tr-TR', { minimumFractionDigits: 2 })}</h5>
                                        </div>
                                        <div className="flex flex-col items-end gap-1">
                                            <span className="text-[10px] font-black text-emerald-400 uppercase tracking-widest">İŞLEM ONAYLANDI</span>
                                            <p className="text-[10px] text-gray-500 font-bold">{new Date().toLocaleTimeString('tr-TR')}</p>
                                        </div>
                                    </div>
                                </div>

                                <div className="grid grid-cols-2 gap-3 mt-10 relative z-10">
                                    <button className="flex items-center justify-center gap-2 py-4 bg-white/10 hover:bg-white/20 rounded-2xl transition-all font-black text-[10px] uppercase tracking-widest border border-white/5 group">
                                        <Printer size={16} className="group-hover:scale-110 transition-transform" />
                                        Fiş Yazdır
                                    </button>
                                    <button className="flex items-center justify-center gap-2 py-4 bg-primary rounded-2xl transition-all font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 group">
                                        <Eye size={16} className="group-hover:scale-110 transition-transform" />
                                        Digital Kopya
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

