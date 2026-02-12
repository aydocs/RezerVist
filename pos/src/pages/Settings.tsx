import { useState, useEffect } from 'react';
import DashboardLayout from '../components/DashboardLayout';
import { Shield, Printer, Save, CheckCircle2, User, Loader2, Grid, Settings as SettingsIcon, Plus, X, Trash2, RefreshCcw } from 'lucide-react';
import classNames from 'classnames';
import api from '../lib/api';
import MenuEditor from '../components/MenuEditor';

function StaffManager() {
    const [staff, setStaff] = useState < any[] > ([]);
    const [loading, setLoading] = useState(true);
    const [updating, setUpdating] = useState < number | null > (null);
    const [isAdding, setIsAdding] = useState(false);
    const [newStaff, setNewStaff] = useState({ name: '', position: 'Garson', pin_code: '', permissions: ['take_orders', 'view_tables'] });

    const fetchStaff = async () => {
        setLoading(true);
        try {
            const res = await api.get('/staff');
            if (res.data.success) setStaff(res.data.data);
        } catch (err) {
            console.error('Staff fetch error', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchStaff();
    }, []);

    const handleUpdatePermissions = async (id: number, currentPerms: string[], permission: string) => {
        let newPerms: string[];
        if (currentPerms.includes(permission)) {
            newPerms = currentPerms.filter(p => p !== permission);
        } else {
            newPerms = [...currentPerms, permission];
        }

        setUpdating(id);
        try {
            const res = await api.post(`/staff/${id}/update-permissions`, { permissions: newPerms });
            if (res.data.success) {
                fetchStaff();
            }
        } catch (err) {
            console.error('Permission update error', err);
        } finally {
            setUpdating(null);
        }
    };

    const handleUpdatePin = async (id: number, newPin: string) => {
        if (newPin.length !== 4) return;
        setUpdating(id);
        try {
            const res = await api.post(`/staff/${id}/update-pin`, { pin: newPin });
            if (res.data.success) {
                fetchStaff();
            }
        } catch (err) {
            console.error('PIN update error', err);
        } finally {
            setUpdating(null);
        }
    };

    const handleAddStaff = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoading(true);
        try {
            const res = await api.post('/staff', newStaff);
            if (res.data.success) {
                setIsAdding(false);
                setNewStaff({ name: '', position: 'Garson', pin_code: '', permissions: ['take_orders', 'view_tables'] });
                fetchStaff();
            }
        } catch (err: any) {
            alert(err.response?.data?.message || 'Personel eklenemedi.');
        } finally {
            setLoading(false);
        }
    };

    const handleDeleteStaff = async (id: number) => {
        if (!confirm('Bu personeli silmek istediğinize emin misiniz?')) return;
        setUpdating(id);
        try {
            const res = await api.delete(`/staff/${id}`);
            if (res.data.success) fetchStaff();
        } catch (err) {
            console.error('Delete error', err);
        } finally {
            setUpdating(null);
        }
    };

    if (loading) return <div className="flex justify-center p-8"><Loader2 className="animate-spin text-primary" /></div>;

    return (
        <div className="space-y-4">
            <div className="flex justify-between items-center mb-6">
                <p className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Kayıtlı Personeller</p>
                <button
                    onClick={() => setIsAdding(true)}
                    className="px-4 py-2 bg-primary text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:shadow-lg transition-all"
                >
                    + Yeni Personel Ekle
                </button>
            </div>

            {isAdding && (
                <div className="p-6 bg-white rounded-3xl border-2 border-primary/20 shadow-xl space-y-4 animate-in slide-in-from-top-2">
                    <div className="flex justify-between items-center mb-2">
                        <h4 className="font-black text-gray-900 uppercase tracking-tight">Yeni Personel Kaydı</h4>
                        <button onClick={() => setIsAdding(false)} className="text-gray-400 hover:text-red-500"><X size={20} /></button>
                    </div>
                    <form onSubmit={handleAddStaff} className="grid grid-cols-2 gap-4">
                        <div className="space-y-1">
                            <label className="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-1">AD SOYAD</label>
                            <input
                                required
                                value={newStaff.name}
                                onChange={e => setNewStaff({ ...newStaff, name: e.target.value })}
                                className="w-full px-4 py-3 bg-gray-50 rounded-xl font-bold border-none focus:ring-2 focus:ring-primary/20 outline-none"
                                placeholder="..."
                            />
                        </div>
                        <div className="space-y-1">
                            <label className="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-1">POZİSYON</label>
                            <select
                                value={newStaff.position}
                                onChange={e => setNewStaff({ ...newStaff, position: e.target.value })}
                                className="w-full px-4 py-3 bg-gray-50 rounded-xl font-bold border-none focus:ring-2 focus:ring-primary/20 outline-none"
                            >
                                <option value="Garson">Garson</option>
                                <option value="Kasiyer">Kasiyer</option>
                                <option value="Aşçı">Aşçı</option>
                                <option value="Müdür">Müdür</option>
                            </select>
                        </div>
                        <div className="space-y-1 col-span-2">
                            <label className="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-1">GİRİŞ ŞİFRESİ (4 RAKAM)</label>
                            <input
                                required
                                maxLength={4}
                                pattern="\d{4}"
                                value={newStaff.pin_code}
                                onChange={e => setNewStaff({ ...newStaff, pin_code: e.target.value })}
                                className="w-full px-4 py-3 bg-gray-50 rounded-xl font-black tracking-[1em] text-center border-none focus:ring-2 focus:ring-primary/20 outline-none"
                                placeholder="----"
                            />
                        </div>
                        <button type="submit" className="col-span-2 py-4 bg-primary text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-primary/20 hover:scale-[1.02] transition-all">
                            PERSONELİ KAYDET
                        </button>
                    </form>
                </div>
            )}
            {staff.map((s) => (
                <div key={s.id} className="p-6 bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all space-y-4">
                    <div className="flex items-center justify-between">
                        <div className="flex items-center gap-4">
                            <div className="w-14 h-14 bg-primary/5 rounded-2xl flex items-center justify-center">
                                <User className="text-primary w-6 h-6" />
                            </div>
                            <div>
                                <p className="font-black text-gray-900 text-lg tracking-tight">{s.name}</p>
                                <p className="text-xs text-gray-500 font-bold uppercase tracking-widest">{s.position}</p>
                            </div>
                        </div>
                        <div className="flex items-center gap-3">
                            <button
                                onClick={() => handleDeleteStaff(s.id)}
                                className="p-2.5 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all"
                                title="Personeli Sil"
                            >
                                <Trash2 size={18} />
                            </button>
                            <div className="relative">
                                <input
                                    title={`${s.name} için PIN kodu`}
                                    type="text"
                                    maxLength={4}
                                    defaultValue={s.pin_code || ''}
                                    placeholder="----"
                                    onBlur={(e) => handleUpdatePin(s.id, e.target.value)}
                                    className="w-24 px-4 py-2.5 bg-gray-50 border border-transparent rounded-xl text-center font-black tracking-widest focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 outline-none transition-all"
                                />
                                {updating === s.id && (
                                    <div className="absolute inset-0 bg-white/80 rounded-xl flex items-center justify-center">
                                        <Loader2 className="w-4 h-4 animate-spin text-primary" />
                                    </div>
                                )}
                            </div>
                            <span className="text-[10px] font-black text-gray-400 uppercase tracking-tighter">PIN (4 HANE)</span>
                        </div>
                    </div>

                    <div className="pt-4 border-t border-gray-50 flex flex-wrap gap-2">
                        {[
                            { id: 'take_orders', label: 'Sipariş Al' },
                            { id: 'process_payments', label: 'Ödeme Al' },
                            { id: 'manage_menu', label: 'Menü Yönetimi' },
                            { id: 'view_reports', label: 'Raporlar' },
                            { id: 'manage_settings', label: 'Ayarlar' }
                        ].map(perm => {
                            const hasPerm = (s.permissions || []).includes(perm.id);
                            return (
                                <button
                                    key={perm.id}
                                    onClick={() => handleUpdatePermissions(s.id, s.permissions || [], perm.id)}
                                    className={classNames(
                                        "px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border-2 flex items-center gap-2",
                                        hasPerm
                                            ? "bg-primary/10 text-primary border-primary shadow-sm"
                                            : "bg-gray-50 text-gray-400 border-transparent hover:bg-gray-100"
                                    )}
                                >
                                    {hasPerm && <CheckCircle2 size={12} />}
                                    {perm.label}
                                </button>
                            );
                        })}
                    </div>
                </div>
            ))}
            {staff.length === 0 && !isAdding && (
                <p className="text-center text-gray-400 font-medium py-4">Sistemde kayıtlı personel bulunamadı.</p>
            )}
        </div>
    );
}

function ResourceManager() {
    const [tables, setTables] = useState < any[] > ([]);
    const [loading, setLoading] = useState(true);
    const [isAdding, setIsAdding] = useState(false);
    const [newTable, setNewTable] = useState({ name: '', capacity: 4, category: 'SALON' });
    const [updating, setUpdating] = useState < number | null > (null);

    useEffect(() => {
        fetchTables();
    }, []);

    const fetchTables = async () => {
        try {
            const res = await api.get('/tables');
            if (res.data.success) setTables(res.data.data);
        } catch (err) {
            console.error('Tables fetch error:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleAddTable = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoading(true);
        try {
            const res = await api.post('/tables', newTable);
            if (res.data.success) {
                setIsAdding(false);
                setNewTable({ name: '', capacity: 4, category: 'SALON' });
                fetchTables();
            }
        } catch (err: any) {
            alert(err.response?.data?.message || 'Masa eklenemedi.');
        } finally {
            setLoading(false);
        }
    };

    const handleDeleteTable = async (id: number) => {
        if (!confirm('Bu masayı silmek istediğinize emin misiniz?')) return;
        setUpdating(id);
        try {
            await api.delete(`/tables/${id}`);
            fetchTables();
        } catch (err: any) {
            alert(err.response?.data?.message || 'Masa silinemedi.');
        } finally {
            setUpdating(null);
        }
    };

    const categories = Array.from(new Set(tables.map(t => t.category)));

    if (loading && tables.length === 0) return <div className="p-12 text-center font-black text-gray-400 animate-pulse">YÜKLENİYOR...</div>;

    return (
        <div className="space-y-8 animate-in fade-in duration-500">
            <div className="flex justify-between items-center">
                <div>
                    <h3 className="text-xl font-black text-gray-900 tracking-tight uppercase">MASA YÖNETİMİ</h3>
                    <p className="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Katlar ve Masalar</p>
                </div>
                <button
                    onClick={() => setIsAdding(true)}
                    className="flex items-center gap-2 px-5 py-3 bg-primary text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:shadow-lg hover:shadow-primary/30 transition-all active:scale-95"
                >
                    <Plus size={14} strokeWidth={3} />
                    YENİ MASA EKLE
                </button>
            </div>

            {isAdding && (
                <div className="p-6 bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl animate-in slide-in-from-top-4 duration-300">
                    <form onSubmit={handleAddTable} className="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div className="space-y-2">
                            <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Masa Adı</label>
                            <input
                                title="Masa adı"
                                type="text"
                                required
                                value={newTable.name}
                                onChange={e => setNewTable({ ...newTable, name: e.target.value })}
                                className="w-full px-4 py-3 bg-white border-2 border-transparent focus:border-primary rounded-xl font-bold text-gray-900 outline-none transition-all"
                                placeholder="Örn: MASA 10"
                            />
                        </div>
                        <div className="space-y-2">
                            <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kapasite</label>
                            <input
                                title="Masa kapasitesi"
                                type="number"
                                required
                                min="1"
                                value={newTable.capacity}
                                onChange={e => setNewTable({ ...newTable, capacity: parseInt(e.target.value) })}
                                className="w-full px-4 py-3 bg-white border-2 border-transparent focus:border-primary rounded-xl font-bold text-gray-900 outline-none transition-all"
                            />
                        </div>
                        <div className="space-y-2">
                            <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kategori / Kat</label>
                            <input
                                title="Masa kategorisi"
                                type="text"
                                required
                                value={newTable.category}
                                onChange={e => setNewTable({ ...newTable, category: e.target.value.toUpperCase() })}
                                className="w-full px-4 py-3 bg-white border-2 border-transparent focus:border-primary rounded-xl font-bold text-gray-900 outline-none transition-all"
                                placeholder="Örn: SALON"
                            />
                        </div>
                        <div className="flex gap-2">
                            <button
                                type="submit"
                                className="flex-1 py-3 bg-primary text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:shadow-lg transition-all"
                            >
                                KAYDET
                            </button>
                            <button
                                type="button"
                                onClick={() => setIsAdding(false)}
                                className="p-3 bg-white text-gray-400 hover:text-red-500 rounded-xl transition-all"
                            >
                                <X size={18} />
                            </button>
                        </div>
                    </form>
                </div>
            )}

            <div className="space-y-12">
                {categories.length === 0 && !isAdding && (
                    <div className="text-center py-12 border-2 border-dashed border-gray-100 rounded-[2rem]">
                        <p className="text-gray-400 font-bold uppercase tracking-widest text-xs">Henüz masa veya kategori eklenmemiş.</p>
                    </div>
                )}

                {categories.map(cat => (
                    <div key={cat} className="space-y-6">
                        <div className="flex items-center gap-3 border-b border-gray-100 pb-4">
                            <div className="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                                <Grid size={16} />
                            </div>
                            <h4 className="text-sm font-black text-gray-900 uppercase tracking-wider">{cat}</h4>
                            <span className="text-[10px] font-black text-gray-400 bg-gray-50 px-2 py-1 rounded-lg uppercase">
                                {tables.filter(t => t.category === cat).length} MASA
                            </span>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            {tables.filter(t => t.category === cat).map(table => (
                                <div key={table.id} className="p-5 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-all group flex items-center justify-between">
                                    <div className="flex items-center gap-4">
                                        <div className="w-12 h-12 bg-gray-50 rounded-xl flex flex-col items-center justify-center border border-gray-100 group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all">
                                            <span className="text-[10px] font-black leading-none">{table.capacity}</span>
                                            <span className="text-[8px] font-bold uppercase mt-0.5">KİŞİ</span>
                                        </div>
                                        <div>
                                            <h5 className="font-black text-gray-900 uppercase tracking-tight text-sm">{table.name}</h5>
                                            <p className="text-[9px] text-gray-400 font-bold uppercase tracking-widest">{table.type === 'table' ? 'Masa' : 'Alan'}</p>
                                        </div>
                                    </div>

                                    <button
                                        onClick={() => handleDeleteTable(table.id)}
                                        disabled={updating === table.id}
                                        className="p-2.5 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all disabled:opacity-50"
                                    >
                                        {updating === table.id ? <Loader2 className="w-4 h-4 animate-spin" /> : <Trash2 size={16} />}
                                    </button>
                                </div>
                            ))}
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}

export default function Settings() {
    const [saving, setSaving] = useState(false);
    const [saved, setSaved] = useState(false);
    const [activeTab, setActiveTab] = useState < 'general' | 'menu' | 'tables' > ('general');
    const [masterPinLoading, setMasterPinLoading] = useState(false);
    const [masterPinSuccess, setMasterPinSuccess] = useState(false);

    const [config, setConfig] = useState({
        printerName: 'Mutfak-Termal-01',
        autoPrint: true,
        notificationSound: true,
        kioskMode: false,
        theme: 'light'
    });

    const handleSave = () => {
        setSaving(true);
        setTimeout(() => {
            setSaving(false);
            setSaved(true);
            setTimeout(() => setSaved(false), 3000);
        }, 1500);
    };

    return (
        <DashboardLayout>
            <div className="flex flex-col gap-8">
                {/* Header Section */}
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 className="text-3xl font-bold text-gray-900 tracking-tight">Sistem Ayarları</h2>
                        <p className="text-gray-500 mt-1">POS terminali ve yazdırma seçeneklerini yapılandırın.</p>
                    </div>

                    <button
                        onClick={handleSave}
                        disabled={saving}
                        className="flex items-center gap-2 px-6 py-3 bg-primary hover:bg-purple-700 text-white rounded-2xl shadow-lg shadow-primary/30 transition-all font-bold disabled:opacity-50"
                        title={saving ? 'Kaydediliyor...' : saved ? 'Kaydedildi' : 'Değişiklikleri Kaydet'}
                    >
                        {saving ? (
                            <div className="h-5 w-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                        ) : saved ? (
                            <CheckCircle2 className="h-5 w-5" />
                        ) : (
                            <Save className="h-5 w-5" />
                        )}
                        <span>{saving ? 'Kaydediliyor...' : saved ? 'Kaydedildi' : 'Değişiklikleri Kaydet'}</span>
                    </button>
                </div>

                <div className="flex-1 flex overflow-hidden min-h-[600px] border border-gray-100 rounded-[2.5rem] bg-white shadow-sm">
                    {/* Sidebar Nav */}
                    <div className="w-64 bg-white border-r border-gray-100 flex flex-col p-6 gap-2 shrink-0">
                        <button
                            onClick={() => setActiveTab('general')}
                            className={classNames(
                                "flex items-center gap-3 px-5 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all",
                                activeTab === 'general' ? "bg-primary text-white shadow-lg shadow-primary/20" : "text-gray-400 hover:bg-gray-50 hover:text-gray-600"
                            )}
                        >
                            <SettingsIcon size={18} strokeWidth={3} />
                            Genel Ayarlar
                        </button>
                        <button
                            onClick={() => setActiveTab('menu')}
                            className={classNames(
                                "flex items-center gap-3 px-5 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all",
                                activeTab === 'menu' ? "bg-primary text-white shadow-lg shadow-primary/20" : "text-gray-400 hover:bg-gray-50 hover:text-gray-600"
                            )}
                        >
                            <Grid size={18} strokeWidth={3} />
                            Menü Yönetimi
                        </button>
                        <button
                            onClick={() => setActiveTab('tables')}
                            className={classNames(
                                "flex items-center gap-3 px-5 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all",
                                activeTab === 'tables' ? "bg-primary text-white shadow-lg shadow-primary/20" : "text-gray-400 hover:bg-gray-50 hover:text-gray-600"
                            )}
                        >
                            <Grid size={18} strokeWidth={3} />
                            Masa Yönetimi
                        </button>
                    </div>

                    {/* Content Area */}
                    <div className="flex-1 overflow-y-auto bg-gray-50/30">
                        {activeTab === 'general' ? (
                            <div className="p-12 space-y-8 max-w-4xl mx-auto">
                                <section className="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
                                    <div className="flex items-center gap-3 mb-8">
                                        <div className="w-10 h-10 bg-purple-50 text-primary rounded-xl flex items-center justify-center">
                                            <Shield size={20} />
                                        </div>
                                        <h3 className="text-xl font-black text-gray-900">Sistem Lisansı</h3>
                                    </div>

                                    <div className="p-6 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-between">
                                        <div className="flex items-center gap-4 overflow-hidden">
                                            <div className="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm shrink-0">
                                                <Grid className="text-primary w-6 h-6" />
                                            </div>
                                            <div className="overflow-hidden">
                                                <p className="font-black text-gray-900 text-sm tracking-tight uppercase">AKTİF LİSANS ANAHTARI</p>
                                                <code className="text-[11px] text-gray-400 font-mono tracking-widest truncate block mt-1">
                                                    {localStorage.getItem('pos_token') || 'Bağlantı Yok'}
                                                </code>
                                            </div>
                                        </div>
                                        <div className="flex items-center gap-2 px-3 py-1.5 bg-emerald-500/10 text-emerald-600 rounded-full border border-emerald-500/20 text-[10px] font-black uppercase tracking-widest shrink-0">
                                            <CheckCircle2 size={12} />
                                            BAĞLI
                                        </div>
                                    </div>
                                    <p className="mt-4 text-[10px] text-gray-400 font-bold italic">
                                        * Bu terminal şu anda {localStorage.getItem('business_name')} hesabına bağlıdır.
                                    </p>
                                </section>

                                <section className="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
                                    <div className="flex items-center gap-3 mb-8">
                                        <div className="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                                            <Printer size={20} />
                                        </div>
                                        <h3 className="text-xl font-black text-gray-900">Yazdırma Ayarları</h3>
                                    </div>

                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div className="space-y-2">
                                            <label className="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">VARSAYILAN YAZICI</label>
                                            <select
                                                title="Varsayılan Yazıcı Seç"
                                                value={config.printerName}
                                                onChange={(e) => setConfig({ ...config, printerName: e.target.value })}
                                                className="w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl transition-all font-bold text-gray-900 outline-none"
                                            >
                                                <option value="Mutfak-Termal-01">Mutfak-Termal-01</option>
                                                <option value="Kasa-Lazer-02">Kasa-Lazer-02</option>
                                                <option value="Bar-Termal-01">Bar-Termal-01</option>
                                            </select>
                                        </div>
                                        <div className="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                                            <div>
                                                <p className="font-bold text-gray-900">Otomatik Yazdır</p>
                                                <p className="text-xs text-gray-500 font-medium">Sipariş sonrası fiş çıkar</p>
                                            </div>
                                            <button
                                                title={config.autoPrint ? "Otomatik Yazdırmayı Devre Dışı Bırak" : "Otomatik Yazdırmayı Etkinleştir"}
                                                onClick={() => setConfig({ ...config, autoPrint: !config.autoPrint })}
                                                className={classNames(
                                                    "w-14 h-8 rounded-full transition-all relative p-1",
                                                    config.autoPrint ? "bg-primary" : "bg-gray-300"
                                                )}
                                            >
                                                <div className={classNames(
                                                    "w-6 h-6 bg-white rounded-full shadow-sm transition-all",
                                                    config.autoPrint ? "translate-x-6" : "translate-x-0"
                                                )} />
                                            </button>
                                        </div>
                                    </div>
                                </section>

                                <section className="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
                                    <div className="flex items-center gap-3 mb-8">
                                        <div className="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center">
                                            <RefreshCcw size={20} />
                                        </div>
                                        <h3 className="text-xl font-black text-gray-900">Veri Yönetimi</h3>
                                    </div>

                                    <div className="p-6 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-between">
                                        <div>
                                            <p className="font-black text-gray-900 text-sm">Menü ve Verileri Yenile</p>
                                            <p className="text-xs text-gray-500 font-medium mt-1 max-w-md">
                                                Yeni eklenen ürünler görünmüyorsa veya senkronizasyon sorunu yaşıyorsanız bu işlemi kullanın.
                                            </p>
                                        </div>
                                        <button
                                            onClick={() => {
                                                if (confirm('Tüm yerel veriler silinecek ve menü yeniden yüklenecektir. Onaylıyor musunuz?')) {
                                                    import('../lib/cache').then(m => {
                                                        m.default.clear();
                                                        window.location.reload();
                                                    });
                                                }
                                            }}
                                            className="px-6 py-3 bg-white hover:bg-red-50 text-red-600 border border-gray-200 hover:border-red-200 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-sm hover:shadow-md transition-all active:scale-95 flex items-center gap-2"
                                        >
                                            <RefreshCcw size={14} />
                                            VERİLERİ YENİLE
                                        </button>
                                    </div>
                                </section>

                                <section className="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
                                    <div className="flex items-center gap-3 mb-8">
                                        <div className="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center">
                                            <Shield size={20} />
                                        </div>
                                        <h3 className="text-xl font-black text-gray-900">Terminal Güvenliği</h3>
                                    </div>

                                    <div className="space-y-6">
                                        <div className="p-4 bg-amber-50 rounded-2xl border border-amber-100">
                                            <p className="text-xs text-amber-800 font-bold leading-relaxed">
                                                <span className="block mb-1 font-black text-[10px] uppercase">DİKKAT!</span>
                                                Master PIN (8 hane) ile terminalin tüm ayarlarına ve kasa işlemlerine tam yetki verilir. Personel PIN kodları (4 hane) sadece sipariş işlemleri içindir.
                                            </p>
                                        </div>

                                        <div className="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                                            <div>
                                                <p className="font-bold text-gray-900 text-sm">İşletme Master PIN</p>
                                                <p className="text-[10px] text-gray-500 font-bold font-medium">Tam yetki için (8 Hane)</p>
                                            </div>
                                            <div className="relative">
                                                <input
                                                    type="password"
                                                    disabled={masterPinLoading}
                                                    maxLength={8}
                                                    placeholder="••••••••"
                                                    onBlur={async (e) => {
                                                        const pin = e.target.value;
                                                        if (pin.length === 8) {
                                                            setMasterPinLoading(true);
                                                            try {
                                                                await api.post('/update-master-pin', { pin });
                                                                setMasterPinSuccess(true);
                                                                setTimeout(() => setMasterPinSuccess(false), 3000);
                                                            } catch (err) {
                                                                alert('Hata: PIN 8 hane olmalıdır.');
                                                            } finally {
                                                                setMasterPinLoading(false);
                                                            }
                                                        }
                                                    }}
                                                    className="w-32 px-4 py-2 bg-white border border-gray-200 rounded-xl text-center font-black tracking-[0.2em] focus:border-primary outline-none transition-all placeholder:tracking-normal disabled:bg-gray-50"
                                                />
                                                {masterPinLoading && (
                                                    <div className="absolute inset-0 bg-white/50 rounded-xl flex items-center justify-center">
                                                        <Loader2 className="w-4 h-4 animate-spin text-primary" />
                                                    </div>
                                                )}
                                                {masterPinSuccess && (
                                                    <div className="absolute -right-10 top-1/2 -translate-y-1/2 text-green-500 animate-in fade-in zoom-in">
                                                        <CheckCircle2 size={24} />
                                                    </div>
                                                )}
                                            </div>
                                        </div>

                                        <div className="pt-4 border-t border-gray-100">
                                            <p className="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Personel PIN Kodları</p>
                                            <StaffManager />
                                        </div>
                                    </div>
                                </section>
                            </div>
                        ) : activeTab === 'menu' ? (
                            <MenuEditor />
                        ) : (
                            <div className="p-12 max-w-6xl mx-auto">
                                <ResourceManager />
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </DashboardLayout>
    );
}
