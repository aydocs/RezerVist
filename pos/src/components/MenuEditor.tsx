import React, { useState, useEffect } from 'react';
import { Plus, Edit2, Trash2, Save, X, Grid } from 'lucide-react';
import api from '../lib/api';
import { getImageUrl } from '../lib/api-client';

interface Product {
    id: number;
    name: string;
    price: number;
    description: string;
    image: string | null;
    is_available: boolean;
    unit_type: 'piece' | 'weight';
    options: any[];
    background_color: string | null;
    stock_enabled: boolean;
    stock_quantity: number | null;
    low_stock_alert: number | null;
}

interface Category {
    name: string;
    items: Product[];
}

export default function MenuEditor() {
    const [categories, setCategories] = useState < Category[] > ([]);
    const [loading, setLoading] = useState(true);
    const [editingProduct, setEditingProduct] = useState < Product | null > (null);
    const [isAddingProduct, setIsAddingProduct] = useState(false);
    const [selectedCatForAdd, setSelectedCatForAdd] = useState < string > ('');
    const [isAddingCategory, setIsAddingCategory] = useState(false);
    const [newCategoryName, setNewCategoryName] = useState('');


    const [imageFile, setImageFile] = useState < File | null > (null);
    const [imagePreview, setImagePreview] = useState < string | null > (null);

    useEffect(() => {
        fetchMenu();
    }, []);

    const fetchMenu = async () => {
        try {
            const res = await api.get('/menu');
            if (res.data.success) {
                setCategories(res.data.data);
            }
        } catch (err) {
            console.error('Menu fetch error:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleEditClick = (product: Product) => {
        // Safe parsing of options to prevent crash
        let parsedOptions: any[] = [];

        if (Array.isArray(product.options)) {
            parsedOptions = product.options;
        } else if (typeof product.options === 'string') {
            try {
                parsedOptions = JSON.parse(product.options);
            } catch (e) {
                console.error('Failed to parse options:', e);
                parsedOptions = [];
            }
        }

        // Validate structure: ensure each group has a 'values' array
        const safeOptions = Array.isArray(parsedOptions)
            ? parsedOptions.map((opt: any) => ({
                name: opt?.name || '',
                values: Array.isArray(opt?.values) ? opt.values : []
            }))
            : [];

        setEditingProduct({
            ...product,
            options: safeOptions
        });

        setImageFile(null);

        // Use global API base URL if available, else fallback
        const baseUrl = localStorage.getItem('api_base_url') || 'http://localhost:8000';
        const imgUrl = getImageUrl(product.image, (product as any).image_url) || null;
        setImagePreview(imgUrl);
        setIsAddingProduct(true);
        // Find category logic if needed, but usually we just keep the current one or let user change
        const parentCat = categories.find(c => c.items.some(i => i.id === product.id));
        if (parentCat) setSelectedCatForAdd(parentCat.name);
    };

    const handleAddNewClick = () => {
        setEditingProduct({
            id: 0,
            name: '',
            price: 0,
            description: '',
            unit_type: 'piece',
            options: [],
            image: null,
            is_available: true,
            background_color: null,
            stock_enabled: false,
            stock_quantity: null,
            low_stock_alert: null
        });
        setImageFile(null);
        setImagePreview(null);
        setIsAddingProduct(true);
        setSelectedCatForAdd(categories.length > 0 ? categories[0].name : '');
    };

    const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            setImageFile(file);
            setImagePreview(URL.createObjectURL(file));
        }
    };

    const handleSaveProduct = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!editingProduct) return;

        setLoading(true);
        try {
            const formData = new FormData();
            formData.append('name', editingProduct.name);
            formData.append('price', editingProduct.price.toString());
            formData.append('description', editingProduct.description || '');
            formData.append('category', selectedCatForAdd || '');
            formData.append('unit_type', editingProduct.unit_type || 'piece');
            formData.append('options', JSON.stringify(editingProduct.options || []));
            formData.append('stock_enabled', editingProduct.stock_enabled ? '1' : '0');

            // Only append numerical values if they exist and are valid numbers
            if (editingProduct.stock_quantity !== null && !isNaN(Number(editingProduct.stock_quantity))) {
                formData.append('stock_quantity', editingProduct.stock_quantity.toString());
            } else {
                formData.append('stock_quantity', '0');
            }

            if (editingProduct.low_stock_alert !== null && !isNaN(Number(editingProduct.low_stock_alert))) {
                formData.append('low_stock_alert', editingProduct.low_stock_alert.toString());
            }

            if (editingProduct.background_color) {
                formData.append('background_color', editingProduct.background_color);
            }

            if (imageFile) {
                formData.append('image', imageFile);
            }

            // Force _method=PUT depending on implementation or just use POST
            // Using standard POST for endpoint
            let res;
            if (editingProduct.id === 0) {
                res = await api.post('/menu/items', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
            } else {
                res = await api.post(`/menu/items/${editingProduct.id}`, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
            }

            if (res.data.success) {
                setIsAddingProduct(false);
                setEditingProduct(null);
                setImageFile(null);
                setImagePreview(null);
                // Also reset temporary category selection
                setSelectedCatForAdd('');
                fetchMenu();
            }
        } catch (err: any) {
            console.error('Save error details:', err);
            const msg = err.response?.data?.message || err.message || 'Ürün kaydedilirken hata oluştu.';
            alert(`HATA: ${msg}`);
        } finally {
            setLoading(false);
        }
    };

    const handleDeleteProduct = async (id: number) => {
        if (!confirm('Bu ürünü silmek istediğinize emin misiniz?')) return;
        try {
            await api.delete(`/menu/items/${id}`);
            fetchMenu();
        } catch (err) {
            alert('Silme işlemi başarısız.');
        }
    };

    const handleAddCategory = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!newCategoryName.trim()) return;

        const exists = categories.find(c => c.name.toLowerCase() === newCategoryName.toLowerCase());
        if (exists) {
            alert('Bu kategori zaten mevcut.');
            return;
        }

        setCategories([...categories, { name: newCategoryName.toUpperCase(), items: [] }]);
        setNewCategoryName('');
        setIsAddingCategory(false);
    };

    const handleDeleteCategory = async (catName: string) => {
        const cat = categories.find(c => c.name === catName);
        if (cat && cat.items.length > 0) {
            alert('İçinde ürün olan kategori silinemez. Önce ürünleri silin veya taşıyın.');
            return;
        }

        if (!confirm(`"${catName}" kategorisini silmek istediğinize emin misiniz?`)) return;
        setCategories(categories.filter(c => c.name !== catName));
    };

    if (loading) return <div className="flex items-center justify-center h-full"><p className="font-black text-gray-400 animate-pulse">YÜKLENİYOR...</p></div>;

    return (
        <div className="h-full flex flex-col bg-gray-50/50">
            <header className="px-8 py-6 bg-white border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 className="text-2xl font-black text-gray-900 tracking-tight">Menü Yönetimi</h2>
                    <p className="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Kategoriler ve Ürünler</p>
                </div>
                <div className="flex gap-3">
                    <button
                        onClick={() => setIsAddingCategory(true)}
                        className="flex items-center gap-2 px-6 py-3 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-100 transition-all active:scale-95"
                    >
                        <Grid size={16} strokeWidth={3} />
                        Kategori Ekle
                    </button>
                    <button
                        onClick={handleAddNewClick}
                        className="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-600/30 transition-all active:scale-95"
                    >
                        <Plus size={16} strokeWidth={3} />
                        Yeni Ürün Ekle
                    </button>
                </div>
            </header>

            <div className="flex-1 overflow-y-auto p-8 space-y-12">
                {categories.map((cat) => (
                    <section key={cat.name} className="space-y-6">
                        <div className="flex items-center justify-between">
                            <div className="flex items-center gap-3">
                                <div className="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                                    <Grid size={20} />
                                </div>
                                <h3 className="text-lg font-black text-gray-900 uppercase tracking-tight">{cat.name}</h3>
                            </div>
                            <div className="flex gap-2">
                                <button
                                    onClick={() => handleDeleteCategory(cat.name)}
                                    className="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                ><Trash2 size={16} /></button>
                            </div>
                        </div>

                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            {cat.items.map((product) => (
                                <div
                                    key={product.id}
                                    className="p-5 rounded-[2rem] border border-gray-100 shadow-xl shadow-gray-200/40 transition-all hover:-translate-y-1 hover:shadow-2xl bg-white relative overflow-hidden group"
                                    style={product.background_color ? { backgroundColor: product.background_color + '15' } : {}}
                                >
                                    {product.background_color && (
                                        <div
                                            className="absolute top-0 right-0 w-20 h-20 bg-current opacity-5 rounded-bl-[4rem]"
                                            style={{ color: product.background_color }}
                                        />
                                    )}
                                    <div className="flex gap-5">
                                        <div className="w-20 h-20 bg-gray-50 rounded-3xl overflow-hidden shrink-0 border border-gray-100 italic flex items-center justify-center text-indigo-500/20 font-black text-2xl shadow-inner group-hover:scale-105 transition-transform duration-500 relative">
                                            <React.Fragment>
                                                <img
                                                    src={getImageUrl(product.image, (product as any).image_url)}
                                                    className="w-full h-full object-cover"
                                                    referrerPolicy="no-referrer"
                                                    onError={(e) => {
                                                        e.currentTarget.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(product.name)}&background=f3f4f6&color=8b5cf6&size=400`;
                                                    }}
                                                />
                                            </React.Fragment>
                                            <span className="uppercase">{product.name[0]}</span>
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <h4 className="font-extrabold text-gray-900 truncate uppercase tracking-tight text-sm mb-1">{product.name}</h4>
                                            <p className="text-indigo-600 font-black text-xs tracking-tighter mb-3">{product.price.toLocaleString('tr-TR')} ₺</p>
                                            <div className="flex gap-2">
                                                <button
                                                    onClick={() => handleEditClick(product)}
                                                    className="px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all"
                                                >
                                                    DÜZENLE
                                                </button>
                                                <button
                                                    onClick={() => handleDeleteProduct(product.id)}
                                                    className="px-3 py-1.5 bg-red-50 text-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all"
                                                >
                                                    SİL
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </section>
                ))}
            </div>

            {/* Edit/Add Modal */}
            {isAddingProduct && (
                <div className="fixed inset-0 z-[200] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-6">
                    <div className="bg-white w-full max-w-4xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300 max-h-[90vh] overflow-y-auto">
                        <header className="px-8 py-6 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                            <div>
                                <h3 className="text-xl font-black text-gray-900 uppercase tracking-tight">
                                    {editingProduct?.id === 0 ? 'Yeni Ürün' : 'Ürünü Düzenle'}
                                </h3>
                                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Ürün Detayları</p>
                            </div>
                            <button onClick={() => setIsAddingProduct(false)} className="p-3 bg-gray-50 text-gray-400 hover:text-gray-900 rounded-2xl transition-all"><X size={20} /></button>
                        </header>

                        <form onSubmit={handleSaveProduct} className="p-8 space-y-6">
                            {/* Image Upload */}
                            <div className="flex items-center gap-4">
                                <div className="w-24 h-24 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden relative group cursor-pointer hover:border-indigo-600/50 transition-all">
                                    {imagePreview ? (
                                        <img src={imagePreview} className="w-full h-full object-cover" />
                                    ) : (
                                        <div className="text-center p-2">
                                            <Grid className="w-6 h-6 text-gray-300 mx-auto mb-1" />
                                            <span className="text-[9px] font-bold text-gray-400 uppercase">Görsel</span>
                                        </div>
                                    )}
                                    <input
                                        type="file"
                                        accept="image/*"
                                        onChange={handleImageChange}
                                        className="absolute inset-0 opacity-0 cursor-pointer"
                                    />
                                    <div className="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <Edit2 className="text-white w-6 h-6" />
                                    </div>
                                </div>
                                <div className="flex-1">
                                    <p className="font-bold text-gray-900 text-sm">Ürün Görseli</p>
                                    <p className="text-[10px] text-gray-400 mt-1">Tıklayarak yeni bir görsel yükleyebilirsiniz.</p>
                                </div>
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2 col-span-2">
                                    <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Ürün Adı</label>
                                    <input
                                        type="text"
                                        required
                                        value={editingProduct?.name || ''}
                                        onChange={(e) => setEditingProduct(prev => prev ? { ...prev, name: e.target.value } : null)}
                                        className="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-900 focus:ring-2 focus:ring-indigo-600/20 outline-none transition-all"
                                        placeholder="Ürün adını giriniz..."
                                    />
                                </div>
                                <div className="space-y-2">
                                    <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Fiyat (₺)</label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        required
                                        value={editingProduct?.price || ''}
                                        onChange={(e) => setEditingProduct(prev => prev ? { ...prev, price: parseFloat(e.target.value) || 0 } : null)}
                                        className="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-900 focus:ring-2 focus:ring-indigo-600/20 outline-none transition-all"
                                        placeholder="0.00"
                                    />
                                </div>
                                <div className="space-y-2">
                                    <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kategori</label>
                                    <select
                                        value={selectedCatForAdd}
                                        onChange={(e) => setSelectedCatForAdd(e.target.value)}
                                        className="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-900 focus:ring-2 focus:ring-indigo-600/20 outline-none transition-all"
                                    >
                                        <option value="">Seçiniz</option>
                                        {categories.map(c => <option key={c.name} value={c.name}>{c.name}</option>)}
                                    </select>
                                </div>
                            </div>

                            <div className="space-y-2">
                                <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Açıklama</label>
                                <textarea
                                    value={editingProduct?.description || ''}
                                    onChange={(e) => setEditingProduct(prev => prev ? { ...prev, description: e.target.value } : null)}
                                    className="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-900 focus:ring-2 focus:ring-indigo-600/20 outline-none transition-all resize-none h-20"
                                    placeholder="Ürün açıklaması (opsiyonel)..."
                                />
                            </div>

                            <div className="space-y-4">
                                <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Ürün Kart Rengi</label>
                                <div className="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl">
                                    <input
                                        type="color"
                                        value={editingProduct?.background_color || '#ffffff'}
                                        onChange={(e) => setEditingProduct(prev => prev ? { ...prev, background_color: e.target.value } : null)}
                                        className="w-12 h-12 rounded-xl border-none cursor-pointer bg-transparent"
                                    />
                                    <div className="flex-1">
                                        <p className="text-[10px] font-black text-gray-500 uppercase tracking-tight">ÖZEL RENK SEÇİNİ</p>
                                        <p className="text-[9px] text-gray-400 mt-0.5">Terminalde bu rengin açık tonu kullanılacaktır.</p>
                                    </div>
                                    {editingProduct?.background_color && (
                                        <button
                                            type="button"
                                            onClick={() => setEditingProduct(prev => prev ? { ...prev, background_color: null } : null)}
                                            className="text-[9px] font-black text-red-500 uppercase tracking-widest p-2 hover:bg-white rounded-lg transition-all"
                                        >
                                            TEMİZLE
                                        </button>
                                    )}
                                </div>
                            </div>

                            <div className="space-y-4">
                                <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Satış Birimi</label>
                                <div className="flex gap-2 p-1 bg-gray-100 rounded-2xl">
                                    <button
                                        type="button"
                                        onClick={() => setEditingProduct(prev => prev ? { ...prev, unit_type: 'piece' } : null)}
                                        className={`flex-1 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all ${editingProduct?.unit_type !== 'weight' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-400'}`}
                                    >
                                        Adet / Porsiyon
                                    </button>
                                    <button
                                        type="button"
                                        onClick={() => setEditingProduct(prev => prev ? { ...prev, unit_type: 'weight' } : null)}
                                        className={`flex-1 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all ${editingProduct?.unit_type === 'weight' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-400'}`}
                                    >
                                        Ağırlık (Gramaj)
                                    </button>
                                </div>
                            </div>

                            <div className="space-y-4">
                                <div className="flex justify-between items-center px-1">
                                    <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ürün Seçenekleri</label>
                                    <button
                                        type="button"
                                        onClick={() => setEditingProduct(prev => prev ? { ...prev, options: [...(prev.options || []), { name: '', values: [{ name: '', price_extra: 0 }] }] } : null)}
                                        className="text-[10px] font-black text-indigo-600 uppercase tracking-widest"
                                    >
                                        + Grup Ekle
                                    </button>
                                </div>
                                <div className="space-y-4 max-h-[300px] overflow-y-auto pr-2 scrollbar-thin">
                                    {(editingProduct?.options || []).map((group, gIdx) => (
                                        <div key={gIdx} className="p-4 bg-gray-50 rounded-2xl space-y-3 relative border border-gray-100">
                                            <button
                                                type="button"
                                                onClick={() => setEditingProduct(prev => {
                                                    if (!prev) return null;
                                                    const newOpts = [...prev.options];
                                                    newOpts.splice(gIdx, 1);
                                                    return { ...prev, options: newOpts };
                                                })}
                                                className="absolute top-2 right-2 text-red-500 hover:bg-red-50 p-1 rounded-lg"
                                            >
                                                <X size={14} />
                                            </button>
                                            <input
                                                type="text"
                                                placeholder="Grup Adı (Örn: Çay Seçimi)"
                                                value={group.name}
                                                onChange={(e) => setEditingProduct(prev => {
                                                    if (!prev) return null;
                                                    const newOpts = [...prev.options];
                                                    newOpts[gIdx].name = e.target.value;
                                                    return { ...prev, options: newOpts };
                                                })}
                                                className="w-full bg-white border-none rounded-xl text-xs font-black p-3 focus:ring-1 focus:ring-indigo-600/20"
                                            />
                                            <div className="space-y-2">
                                                {(group.values || []).map((val: any, vIdx: number) => (
                                                    <div key={vIdx} className="flex gap-2">
                                                        <input
                                                            type="text"
                                                            placeholder="Değer (Örn: Demli)"
                                                            value={val.name}
                                                            onChange={(e) => setEditingProduct(prev => {
                                                                if (!prev) return null;
                                                                const newOpts = [...prev.options];
                                                                newOpts[gIdx].values[vIdx].name = e.target.value;
                                                                return { ...prev, options: newOpts };
                                                            })}
                                                            className="flex-3 bg-white border-none rounded-xl text-[10px] font-bold p-2 focus:ring-1 focus:ring-indigo-600/20"
                                                        />
                                                        <input
                                                            type="number"
                                                            placeholder="+ Fiyat"
                                                            value={val.price_extra || ''}
                                                            onChange={(e) => setEditingProduct(prev => {
                                                                if (!prev) return null;
                                                                const newOpts = [...prev.options];
                                                                newOpts[gIdx].values[vIdx].price_extra = parseFloat(e.target.value) || 0;
                                                                return { ...prev, options: newOpts };
                                                            })}
                                                            className="flex-1 bg-white border-none rounded-xl text-[10px] font-black p-2 focus:ring-1 focus:ring-indigo-600/20 text-indigo-600"
                                                        />
                                                        <button
                                                            type="button"
                                                            onClick={() => setEditingProduct(prev => {
                                                                if (!prev) return null;
                                                                const newOpts = prev.options.map((group, groupIdx) => {
                                                                    if (groupIdx !== gIdx) return group;
                                                                    return {
                                                                        ...group,
                                                                        values: group.values.filter((_: any, valueIdx: number) => valueIdx !== vIdx)
                                                                    };
                                                                });
                                                                return { ...prev, options: newOpts };
                                                            })}
                                                            className="p-2 text-gray-300 hover:text-red-500"
                                                        >
                                                            <Trash2 size={12} />
                                                        </button>
                                                    </div>
                                                ))}
                                                <button
                                                    type="button"
                                                    onClick={() => setEditingProduct(prev => {
                                                        if (!prev) return null;
                                                        const newOpts = prev.options.map((group, groupIdx) => {
                                                            if (groupIdx !== gIdx) return group;
                                                            return {
                                                                ...group,
                                                                values: [...(group.values || []), { name: '', price_extra: 0 }]
                                                            };
                                                        });
                                                        return { ...prev, options: newOpts };
                                                    })}
                                                    className="text-[9px] font-black text-indigo-500 uppercase tracking-widest ml-1"
                                                >
                                                    + SEÇENEK EKLE
                                                </button>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>

                            <div className="space-y-4 p-5 bg-amber-50/50 border-2 border-amber-100 rounded-2xl">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <label className="text-[10px] font-black text-amber-700 uppercase tracking-widest">Stok Takibi</label>
                                        <p className="text-[9px] text-amber-600 mt-0.5">Otomatik stok azaltma aktif edilsin mi?</p>
                                    </div>
                                    <button
                                        type="button"
                                        onClick={() => setEditingProduct(prev => prev ? { ...prev, stock_enabled: !prev.stock_enabled } : null)}
                                        className={`relative w-14 h-7 rounded-full transition-all ${editingProduct?.stock_enabled ? 'bg-amber-500' : 'bg-gray-200'}`}
                                    >
                                        <div className={`absolute top-0.5 w-6 h-6 bg-white rounded-full shadow-md transition-all ${editingProduct?.stock_enabled ? 'left-7' : 'left-0.5'}`} />
                                    </button>
                                </div>
                                {editingProduct?.stock_enabled && (
                                    <div className="grid grid-cols-2 gap-3 animate-in slide-in-from-top-2 duration-300">
                                        <div className="space-y-2">
                                            <label className="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-1">Mevcut Stok</label>
                                            <input
                                                type="number"
                                                min="0"
                                                value={editingProduct?.stock_quantity || ''}
                                                onChange={(e) => setEditingProduct(prev => prev ? { ...prev, stock_quantity: parseInt(e.target.value) || 0 } : null)}
                                                className="w-full px-4 py-3 bg-white border-none rounded-xl font-black text-amber-700 focus:ring-2 focus:ring-amber-400/20 outline-none transition-all"
                                                placeholder="0"
                                            />
                                        </div>
                                        <div className="space-y-2">
                                            <label className="text-[9px] font-black text-amber-600 uppercase tracking-widest ml-1">Düşük Stok Uyarı</label>
                                            <input
                                                type="number"
                                                min="0"
                                                value={editingProduct?.low_stock_alert || ''}
                                                onChange={(e) => setEditingProduct(prev => prev ? { ...prev, low_stock_alert: parseInt(e.target.value) || 0 } : null)}
                                                className="w-full px-4 py-3 bg-white border-none rounded-xl font-black text-amber-700 focus:ring-2 focus:ring-amber-400/20 outline-none transition-all"
                                                placeholder="5"
                                            />
                                        </div>
                                    </div>
                                )}
                            </div>

                            <button
                                type="submit"
                                className="w-full py-5 bg-indigo-600 text-white rounded-[1.5rem] font-black text-sm uppercase tracking-widest hover:shadow-xl hover:shadow-indigo-600/30 transition-all active:scale-95 flex items-center justify-center gap-3 mt-4"
                            >
                                <Save size={18} strokeWidth={3} />
                                Kaydet
                            </button>
                        </form>
                    </div>
                </div>
            )}

            {/* Add Category Modal */}
            {isAddingCategory && (
                <div className="fixed inset-0 z-[200] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-6">
                    <div className="bg-white w-full max-w-sm rounded-[2.5rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
                        <header className="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                            <div>
                                <h3 className="text-xl font-black text-gray-900 uppercase tracking-tight">Yeni Kategori</h3>
                                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Kategori Yönetimi</p>
                            </div>
                            <button onClick={() => setIsAddingCategory(false)} className="p-3 bg-gray-50 text-gray-400 hover:text-gray-900 rounded-2xl transition-all"><X size={20} /></button>
                        </header>

                        <form onSubmit={handleAddCategory} className="p-8 space-y-6">
                            <div className="space-y-2">
                                <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kategori Adı</label>
                                <input
                                    type="text"
                                    required
                                    autoFocus
                                    value={newCategoryName}
                                    onChange={(e) => setNewCategoryName(e.target.value)}
                                    className="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                                    placeholder="Örn: SOĞUK İÇECEKLER"
                                />
                            </div>

                            <button
                                type="submit"
                                className="w-full py-5 bg-primary text-white rounded-[1.5rem] font-black text-sm uppercase tracking-widest hover:shadow-xl hover:shadow-primary/30 transition-all active:scale-95 flex items-center justify-center gap-3 mt-4"
                            >
                                <Plus size={18} strokeWidth={3} />
                                KATEGORİ EKLE
                            </button>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
}
