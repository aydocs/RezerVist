import React, { useState, useEffect } from 'react';
import DashboardLayout from '../components/DashboardLayout';
import { Search, Loader2, Star, CheckCircle2, XCircle } from 'lucide-react';
import api, { API_BASE_ROOT } from '../lib/api-client';

interface MenuItem {
    id: number;
    name: string;
    price: number;
    description: string;
    image: string | null;
    is_available: boolean;
}

interface MenuCategory {
    name: string;
    items: MenuItem[];
}

export default function Menu() {
    const [menu, setMenu] = useState < MenuCategory[] > ([]);
    const [loading, setLoading] = useState(true);
    const [searchTerm, setSearchTerm] = useState('');

    useEffect(() => {
        const fetchMenu = async () => {
            try {
                const res = await api.get('/menu');
                if (res.data.success) {
                    setMenu(res.data.data);
                }
            } catch (err) {
                console.error('Menü yüklenemedi:', err);
            } finally {
                setLoading(false);
            }
        };
        fetchMenu();
    }, []);

    const filteredMenu = menu.map(category => ({
        ...category,
        items: category.items.filter(item =>
            item.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            category.name.toLowerCase().includes(searchTerm.toLowerCase())
        )
    })).filter(category => category.items.length > 0);

    return (
        <DashboardLayout>
            <div className="flex flex-col gap-8">
                {/* Header Section */}
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 className="text-3xl font-bold text-gray-900 tracking-tight">Dijital Menü</h2>
                        <p className="text-gray-500 mt-1">İşletmenizin güncel ürün ve fiyat listesini görüntüleyin.</p>
                    </div>

                    <div className="flex gap-2 w-full max-w-lg">
                        <div className="relative flex-1">
                            <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 h-5 w-5" />
                            <input
                                type="text"
                                placeholder="Ürün veya kategori ara..."
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                                className="w-full pl-12 pr-6 py-3.5 bg-white border border-gray-200 rounded-2xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all font-medium text-gray-900 shadow-sm"
                            />
                        </div>
                        <button className="px-8 bg-gray-900 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-primary hover:shadow-lg hover:shadow-primary/30 transition-all active:scale-95 flex items-center gap-2">
                            <Search size={18} strokeWidth={3} />
                            ARA
                        </button>
                    </div>
                </div>

                {/* Menu Content */}
                {loading ? (
                    <div className="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border border-dashed border-gray-200">
                        <Loader2 className="h-10 w-10 text-primary animate-spin mb-4" />
                        <p className="text-gray-500 font-medium">Menü yükleniyor...</p>
                    </div>
                ) : filteredMenu.length > 0 ? (
                    <div className="flex flex-col gap-12">
                        {filteredMenu.map((category) => (
                            <div key={category.name} className="space-y-6">
                                <div className="flex items-center gap-4">
                                    <h3 className="text-2xl font-black text-gray-900 tracking-tight">{category.name}</h3>
                                    <div className="h-0.5 flex-1 bg-gradient-to-r from-gray-100 to-transparent"></div>
                                </div>

                                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                    {category.items.map((item) => (
                                        <div
                                            key={item.id}
                                            className="bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl hover:border-primary/30 transition-all group"
                                        >
                                            <div className="h-48 bg-gray-100 relative overflow-hidden">
                                                {/* Prioritize image_url if available (added via accessor), fallback to manual path construction if needed */}
                                                <React.Fragment>
                                                    <img
                                                        src={(item as any).image_url || (item.image?.startsWith('http') ? item.image : (item.image ? API_BASE_ROOT + `/storage/${item.image}` : undefined))}
                                                        alt={item.name}
                                                        className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                                        onError={(e) => {
                                                            (e.target as HTMLImageElement).src = 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&q=80&w=400&h=400';
                                                        }}
                                                    />
                                                </React.Fragment>
                                                <div className="absolute top-4 right-4 group-hover:scale-110 transition-transform">
                                                    {item.is_available ? (
                                                        <span className="bg-green-500 text-white px-2.5 py-1 rounded-full text-[10px] font-black uppercase flex items-center gap-1 shadow-lg">
                                                            <CheckCircle2 size={12} /> MEVCUT
                                                        </span>
                                                    ) : (
                                                        <span className="bg-red-500 text-white px-2.5 py-1 rounded-full text-[10px] font-black uppercase flex items-center gap-1 shadow-lg">
                                                            <XCircle size={12} /> TÜKENDİ
                                                        </span>
                                                    )}
                                                </div>
                                            </div>

                                            <div className="p-6">
                                                <h4 className="text-lg font-black text-gray-900 group-hover:text-primary transition-colors mb-2 truncate">
                                                    {item.name}
                                                </h4>
                                                <p className="text-xs text-gray-500 font-medium line-clamp-2 h-8 mb-4">
                                                    {item.description || 'Bu ürün için açıklama bulunmamaktadır.'}
                                                </p>
                                                <div className="flex items-center justify-between pt-4 border-t border-gray-50">
                                                    <span className="text-xl font-black text-primary">
                                                        {item.price.toLocaleString('tr-TR', { minimumFractionDigits: 2 })} ₺
                                                    </span>
                                                    <div className="flex gap-1">
                                                        {[1, 2, 3, 4, 5].map((_, i) => (
                                                            <Star key={i} size={12} className="fill-orange-400 text-orange-400" />
                                                        ))}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="flex flex-col items-center justify-center py-24 bg-white rounded-3xl border border-dashed border-gray-200">
                        <div className="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                            <Search size={40} className="text-gray-300" />
                        </div>
                        <h3 className="text-xl font-bold text-gray-900 mb-2">Ürün bulunamadı</h3>
                        <p className="text-gray-500 font-medium max-w-xs text-center">
                            "{searchTerm}" aramasına uygun herhangi bir ürün veya kategori bulunamadı.
                        </p>
                    </div>
                )}
            </div>
        </DashboardLayout>
    );
}
