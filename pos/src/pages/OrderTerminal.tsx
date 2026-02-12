import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import {
    ChevronLeft,
    Plus,
    Minus,
    CreditCard,
    Loader2,
    List,
    CheckCircle2,
    ShoppingBag,
    Trash2,
    Printer,
    ArrowRightLeft,
    StickyNote,
    Merge,
    LayoutGrid
} from 'lucide-react';
import api from '../lib/api';
import { API_BASE_ROOT } from '../lib/api-client';
import PaymentModal from '../components/PaymentModal';
import CacheManager from '../lib/cache';
interface MenuItem {
    id: number;
    name: string;
    price: number;
    description?: string;
    image?: string;
    is_available: boolean;
    options?: any[];
    unit_type?: 'piece' | 'weight';
    background_color: string | null;
}

interface MenuCategory {
    name: string;
    items: MenuItem[];
}

interface OrderItem {
    id?: number;
    menu_id: number;
    name: string;
    image?: string; // Added image to interface
    quantity: number;
    unit_price: number;
    selected_options?: any[];
    weight_grams?: number;
    notes?: string;
    cartIndex?: number;
    uniqueId?: string;
    status?: string;
}

export default function OrderTerminal() {
    const { tableId } = useParams();
    const navigate = useNavigate();
    const [categories, setCategories] = useState < MenuCategory[] > (() => {
        try {
            const cached = CacheManager.get('menu');
            const data = cached || [];
            return data.filter((c: any) => c.name && c.name.trim() !== '' && c.items && c.items.length > 0);
        } catch (e) {
            return [];
        }
    });
    const [selectedCategory, setSelectedCategory] = useState < string > ('');
    const [cart, setCart] = useState < OrderItem[] > ([]);
    const [loading, setLoading] = useState(() => {
        const cached = CacheManager.get('menu');
        return !cached;
    });
    const [submitting, setSubmitting] = useState(false);
    const [showPayment, setShowPayment] = useState(false);
    const [orderId, setOrderId] = useState < number | null > (null);
    const [tableName, setTableName] = useState < string > ('');
    const [summaryView, setSummaryView] = useState < 'grouped' | 'list' > ('list');


    // Modals for Weight and Options
    const [activeSelection, setActiveSelection] = useState < MenuItem | null > (null);
    const [weightInput, setWeightInput] = useState < string > ('');
    const [showWeightModal, setShowWeightModal] = useState(false);
    const [showOptionsModal, setShowOptionsModal] = useState(false);
    const [selectedOptions, setSelectedOptions] = useState < any > ({});
    const [optionQty, setOptionQty] = useState(1);

    // Table Transfer/Merge States
    const [showTransferModal, setShowTransferModal] = useState(false);
    const [transferMode, setTransferMode] = useState < 'move' | 'merge' > ('move');
    const [targetTableId, setTargetTableId] = useState('');
    const [allTables, setAllTables] = useState < any[] > ([]);

    const [isDeleteMode, setIsDeleteMode] = useState(false);
    const [deleteSelection, setDeleteSelection] = useState < string[] > ([]); // Array of unique item IDs

    // Table Actions States
    const [tableNote, setTableNote] = useState < string > ('');
    const [showNoteModal, setShowNoteModal] = useState(false);

    const fetchTables = async () => {
        try {
            const res = await api.get('/tables');
            if (res.data.success) {
                setAllTables(res.data.data.flat().filter((t: any) => t.id !== (tableId === 'takeaway' ? null : Number(tableId))));
            }
        } catch (err) {
            console.error(err);
        }
    };

    // Fetch tables when modal opens
    useEffect(() => {
        if (showTransferModal) {
            fetchTables();
        }
    }, [showTransferModal]);



    useEffect(() => {
        if (categories.length > 0 && !selectedCategory) {
            setSelectedCategory(categories[0].name);
        }
    }, [categories, selectedCategory]);

    const handleTransferTable = async () => {
        if (!targetTableId) return;
        if (!confirm(`Bu masayı Masa ${allTables.find(t => t.id == targetTableId)?.name} ile birleştirmek/taşımak istediğinize emin misiniz?`)) return;

        setSubmitting(true);
        try {
            const res = await api.post('/tables/transfer', {
                from_table_id: tableId,
                to_table_id: targetTableId
            });

            if (res.data.success) {
                alert(res.data.message);
                CacheManager.invalidate('tables');
                navigate('/dashboard');
            } else {
                alert('Hata: ' + res.data.message);
            }
        } catch (err) {
            alert('İşlem başarısız.');
        } finally {
            setSubmitting(false);
            setShowTransferModal(false);
        }
    };

    const fetchData = async () => {
        const cachedMenu = CacheManager.get('menu');
        const hasCategories = categories.length > 0;

        // Use cached menu if available to skip network call
        const shouldFetchMenu = !hasCategories && !cachedMenu;

        if (!hasCategories && cachedMenu) {
            setCategories(cachedMenu);
        }

        if (shouldFetchMenu) setLoading(true);

        try {
            const promises: Promise<any>[] = [
                api.get(`/order/${tableId}`),
                api.get('/tables')
            ];

            if (shouldFetchMenu) {
                promises.push(api.get('/menu'));
            }

            const results = await Promise.all(promises);
            const orderRes = results[0];
            const tablesRes = results[1];
            const menuRes = shouldFetchMenu ? results[2] : null;

            if (tablesRes.data.success) {
                const flatTables = tablesRes.data.data.flat();
                const currentTable = flatTables.find((t: any) => String(t.id) === tableId);
                if (currentTable) {
                    setTableName(currentTable.name);
                }
            }

            if (menuRes && menuRes.data.success) {
                const menuData = menuRes.data.data.filter((c: any) =>
                    c.name &&
                    c.name.trim() !== '' &&
                    c.items &&
                    c.items.length > 0
                );
                setCategories(menuData);
                CacheManager.set('menu', menuData);
                if (menuData.length > 0 && !selectedCategory) {
                    setSelectedCategory(menuData[0].name);
                }
            } else if (cachedMenu && !selectedCategory && cachedMenu.length > 0) {
                // Ensure selected category is set if we used cache
                setSelectedCategory(cachedMenu[0].name);
            }

            if (orderRes.data.success && orderRes.data.data) {
                setOrderId(orderRes.data.data.id);
                const backendItems = orderRes.data.data.items
                    .filter((item: any) => !['completed', 'cancelled', 'deleted'].includes(item.status))
                    .map((item: any) => ({
                        id: item.id,
                        menu_id: item.menu_id,
                        name: item.name,
                        quantity: Number(item.quantity) || 1,
                        unit_price: Number(item.unit_price) || 0,
                        selected_options: item.selected_options || [],
                        weight_grams: item.weight_grams ? parseInt(item.weight_grams) : undefined,
                        notes: item.notes,
                        status: item.status
                    }));

                setCart(prev => {
                    // PRESERVE LOCAL ITEMS: Keep items that don't have an ID (newly added)
                    const localItems = prev.filter(item => !item.id);
                    // Merge: Backend items first, then local items waiting to be saved
                    return [...backendItems, ...localItems];
                });
            } else {
                setOrderId(null);
                setCart(prev => {
                    // PRESERVE LOCAL ITEMS even if no backend order exists
                    return prev.filter(item => !item.id);
                });
            }
        } catch (err) {
            console.error('Veri yükleme hatası:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchData();
    }, [tableId]);

    const addToCart = (product: MenuItem) => {
        if (product.unit_type === 'weight') {
            setActiveSelection(product);
            setWeightInput('');
            setShowWeightModal(true);
            return;
        }

        const options = typeof product.options === 'string' ? JSON.parse(product.options) : (product.options || []);

        if (options.length > 0) {
            setActiveSelection({ ...product, options });
            setOptionQty(1);
            setSelectedOptions({});
            setShowOptionsModal(true);
            return;
        }

        finalizeAddToCart(product, 1, product.price);
    };

    const finalizeAddToCart = (product: MenuItem, quantity: number, unitPrice: number, weightGrams?: number, options?: any[]) => {
        setCart(prev => {
            // Logic to merge items removed as per user request (each item should be separate x1)
            return [...prev, {
                menu_id: product.id,
                name: product.name,
                quantity: Number(quantity) || 1,
                unit_price: Number(unitPrice) || 0,
                selected_options: options,
                weight_grams: weightGrams ? Number(weightGrams) : undefined
            }];
        });

        setActiveSelection(null);
        setShowWeightModal(false);
        setShowOptionsModal(false);
    };

    const updateQuantity = (index: number, delta: number) => {
        setCart(prev => prev.map((item, i) => {
            if (i === index && !item.id) {
                return { ...item, quantity: Math.max(1, item.quantity + delta) };
            }
            return item;
        }));
    };


    const subtotal = cart.reduce((sum, item) => {
        if (['completed', 'cancelled', 'deleted'].includes(item.status || '')) return sum;
        return sum + (Number(item.unit_price || 0) * Number(item.quantity || 0));
    }, 0);

    const handleSubmitOrder = async (shouldNavigate = true) => {
        const newItems = cart.filter(item => !item.id).map(item => ({
            ...item,
            quantity: Number(item.quantity) || 1,
            unit_price: Number(item.unit_price) || 0,
            weight_grams: item.weight_grams ? Number(item.weight_grams) : undefined
        }));

        if (newItems.length === 0 && !orderId) return true;

        setSubmitting(true);
        try {
            const res = await api.post('/order/submit', {
                resource_id: tableId,
                items: newItems
            });
            if (res.data.success) {
                const order = res.data.data;
                setOrderId(order.id);
                CacheManager.invalidate('tables');
                setCart(order.items
                    .filter((item: any) => !['completed', 'cancelled'].includes(item.status))
                    .map((item: any) => ({
                        id: item.id,
                        menu_id: item.menu_id,
                        name: item.name,
                        quantity: Number(item.quantity) || 1,
                        unit_price: Number(item.unit_price) || 0,
                        selected_options: item.selected_options || [],
                        weight_grams: item.weight_grams ? parseInt(item.weight_grams) : undefined,
                        notes: item.notes,
                        status: item.status
                    })));
                if (shouldNavigate) navigate('/dashboard');
                return order.id; // Return the ID on success
            }
        } catch (err) {
            console.error('Sipariş gönderim hatası:', err);
            return false;
        } finally {
            setSubmitting(false);
        }
        return false;
    };

    const removeFromCart = async (index: number) => {
        const itemToRemove = cart[index];

        // If item has an ID, it means it's saved in DB. We must delete it from backend.
        if (itemToRemove.id && orderId) {
            try {
                // If it's a grouped view, we might need to delete multiple items?
                // The current removeFromCart (singular) logic is usually called for specific items or from grouped logic iterating.
                // But wait, the API expects ONE itemId. 
                // itemToRemove.id is the specific OrderItem ID.

                const res = await api.delete(`/order/${orderId}/items/${itemToRemove.id}`);

                // Check if order was automatically closed because it's empty
                if (res.data.order_deleted) {
                    // Order is deleted, clear state and nav to dashboard
                    setCart([]);
                    setOrderId(null);
                    CacheManager.invalidate('tables');
                    alert('Masa boşaldığı için kapatıldı.');
                    navigate('/dashboard');
                    return;
                }

                // Success - remove from UI
                setCart(prev => prev.filter((_, i) => i !== index));
            } catch (error: any) {
                console.error('Ürün silme hatası:', error);
                alert('Ürün silinirken hata oluştu: ' + (error.response?.data?.message || error.message));
                // Do not remove from UI if backend failed
            }
        } else {
            // Local only
            setCart(prev => prev.filter((_, i) => i !== index));
        }
    };

    const handleBulkDelete = async () => {
        if (deleteSelection.length === 0) {
            setIsDeleteMode(false);
            return;
        }
        if (!window.confirm(`${deleteSelection.length} ürünü silmek istediğinize emin misiniz?`)) return;


        setLoading(true);
        try {


            // We need to accurately identify which cart items are fully removed.
            // Given the complexity of exploded view vs grouped view vs DB items...
            // Let's trust the `deleteSelection` maps to cart indices.

            const selectionMap: Record<number, number> = {}; // idx -> count to delete
            deleteSelection.forEach(uid => {
                const idx = parseInt(uid.split('-')[0]);
                selectionMap[idx] = (selectionMap[idx] || 0) + 1;
            });

            // Sort high to low
            const indices = Object.keys(selectionMap).map(Number).sort((a, b) => b - a);

            const newCart = [...cart];

            for (const idx of indices) {
                const item = newCart[idx];
                const toRemove = selectionMap[idx];
                const currentQty = Number(item.quantity) || 1;

                if (item.id && orderId) {
                    // SAVED ITEM
                    if (toRemove >= currentQty) {
                        // Delete fully
                        await api.delete(`/order/${orderId}/items/${item.id}`);
                        newCart.splice(idx, 1);
                    } else {
                        // Partial delete of saved item
                        const newQty = currentQty - toRemove;
                        await api.patch(`/order/${orderId}/items/${item.id}`, {
                            quantity: newQty
                        });
                        newCart[idx] = { ...item, quantity: newQty };
                    }
                } else {
                    // UNSAVED ITEM
                    if (toRemove >= currentQty) {
                        newCart.splice(idx, 1);
                    } else {
                        newCart[idx] = { ...item, quantity: currentQty - toRemove };
                    }
                }
            }

            setCart(newCart);
            await handleSubmitOrder(false); // Sync any remaining changes

        } catch (err) {
            console.error(err);
        } finally {
            setLoading(false);
            setDeleteSelection([]);
            setIsDeleteMode(false);
            fetchData(); // Refresh to be sure
        }
    };

    // ...


    const handlePaymentClick = async () => {
        console.log('Ödeme Al tıklandı. Masa:', tableId, 'Mevcut Sipariş ID:', orderId);

        // For takeaway, if there are new items, submit first to get IDs
        if (tableId === 'takeaway') {
            const hasNewTakeawayItems = cart.some(item => !item.id);
            if (hasNewTakeawayItems) {
                console.log('Paket servis: yeni ürünler kaydediliyor...');
                const resultId = await handleSubmitOrder(false);
                if (resultId) setShowPayment(true);
            } else if (cart.length > 0) {
                setShowPayment(true);
            } else {
                console.warn('Sepet boş, modal açılmıyor.');
            }
            return;
        }

        const hasUnsavedItems = cart.some(item => !item.id);

        if (hasUnsavedItems) {
            console.log('Kaydedilmemiş ürünler var, kaydediliyor...');
            const resultId = await handleSubmitOrder(false);
            if (resultId) {
                console.log('Kayıt başarılı, ID:', resultId);
                setShowPayment(true);
            } else {
                console.error('Kayıt başarısız oldu!');
            }
        } else {
            if (orderId) {
                console.log('Kayıt gerekmiyor, modal açılıyor...');
                setShowPayment(true);
            } else if (cart.length > 0) {
                console.log('ID yok ama sepet dolu, zorunlu kayıt...');
                const resultId = await handleSubmitOrder(false);
                if (resultId) setShowPayment(true);
            }
        }
    };

    if (loading) {
        return (
            <div className="h-full w-full flex flex-col items-center justify-center bg-gray-50">
                <Loader2 className="h-12 w-12 text-primary animate-spin mb-4" />
                <p className="text-gray-500 font-bold uppercase tracking-widest text-sm">Menü Yükleniyor...</p>
            </div>
        );
    }

    const currentCategory = categories.find(c => c.name === selectedCategory);

    return (
        <div className="h-full w-full bg-gray-50 flex flex-col lg:flex-row overflow-hidden">
            <div className="flex-1 flex flex-col min-w-0 border-r border-gray-200">
                <header className="h-20 bg-white border-b border-gray-200 px-6 flex items-center justify-between shrink-0">
                    <div className="flex items-center gap-4 min-w-0">
                        <button
                            title="Geri Dön"
                            onClick={() => navigate('/dashboard')}
                            className="p-2.5 hover:bg-gray-100 rounded-xl transition-colors text-gray-500 shrink-0"
                        >
                            <ChevronLeft size={24} />
                        </button>
                        <div className="min-w-0">
                            <h1 className="text-2xl font-black text-gray-900 tracking-tight truncate">
                                {tableId === 'takeaway' ? 'Tezgah Satış' : (tableName || `Masa ${tableId}`)}
                            </h1>
                            <div className="flex items-center gap-3 text-[10px] text-gray-400 font-extrabold uppercase tracking-[0.15em] mt-0.5 whitespace-nowrap overflow-hidden">
                                {tableId === 'takeaway' ? (
                                    <span className="flex items-center gap-1.5 px-2 py-0.5 bg-orange-50 text-orange-500 rounded-md border border-orange-100">
                                        <ShoppingBag size={12} strokeWidth={3} /> HIZLI SATIŞ
                                    </span>
                                ) : (
                                    <span className="flex items-center gap-1.5 px-2 py-0.5 bg-indigo-50 text-indigo-500 rounded-md border border-indigo-100">
                                        OTURUM AÇIK
                                    </span>
                                )}
                                <span className="flex items-center gap-1.5 px-2 py-0.5 bg-emerald-50 text-emerald-500 rounded-md border border-emerald-100 hidden sm:flex">
                                    POS TERMİNAL
                                </span>
                            </div>
                        </div>
                    </div>

                    <div className="flex items-center gap-2">
                        {/* Table Actions Group */}
                        <div className="hidden md:flex items-center gap-1.5 bg-gray-50 p-1.5 rounded-2xl border border-gray-100 mr-2">
                            <button
                                onClick={() => { setTransferMode('move'); setShowTransferModal(true); }}
                                className="flex items-center gap-2 px-3 py-2 bg-white hover:bg-blue-600 hover:text-white text-blue-600 rounded-xl transition-all font-bold text-xs border border-blue-100 shadow-sm active:scale-95 group"
                                title="Masayı Başka Masaya Taşı"
                            >
                                <ArrowRightLeft size={16} className="group-hover:rotate-180 transition-transform duration-500" />
                                <span>TAŞI</span>
                            </button>

                            <button
                                onClick={() => { setTransferMode('merge'); setShowTransferModal(true); }}
                                className="flex items-center gap-2 px-3 py-2 bg-white hover:bg-indigo-600 hover:text-white text-indigo-600 rounded-xl transition-all font-bold text-xs border border-indigo-100 shadow-sm active:scale-95 group"
                                title="Masaları Birleştir"
                            >
                                <Merge size={16} />
                                <span>BİRLEŞTİR</span>
                            </button>

                            <button
                                onClick={() => setShowNoteModal(true)}
                                className={`flex items-center gap-2 px-3 py-2 rounded-xl transition-all font-bold text-xs border shadow-sm active:scale-95 ${tableNote ? 'bg-amber-500 text-white border-amber-500' : 'bg-white hover:bg-amber-50 text-amber-500 border-amber-100'}`}
                                title="Adisyon Notu"
                            >
                                <StickyNote size={16} />
                                <span>NOT</span>
                            </button>

                            <button
                                onClick={() => window.print()}
                                className="flex items-center gap-2 px-3 py-2 bg-white hover:bg-gray-900 hover:text-white text-gray-900 rounded-xl transition-all font-bold text-xs border border-gray-200 shadow-sm active:scale-95"
                                title="Hızlı Yazdır"
                            >
                                <Printer size={16} />
                                <span>YAZDIR</span>
                            </button>
                        </div>
                    </div>
                </header>

                <nav className="h-20 bg-white border-b border-gray-100 flex items-center px-6 gap-3 overflow-x-auto scrollbar-hide shrink-0">
                    {categories.map((cat) => (
                        <button
                            key={cat.name}
                            onClick={() => setSelectedCategory(cat.name)}
                            className={`px-7 py-3.5 rounded-[1.25rem] text-[12px] font-black uppercase tracking-[0.12em] transition-all duration-300 whitespace-nowrap border-2 ${selectedCategory === cat.name
                                ? 'bg-indigo-600 text-white border-indigo-600 shadow-xl shadow-indigo-600/30 scale-105 -translate-y-0.5'
                                : 'bg-gray-50 text-gray-500 border-gray-100/50 hover:bg-white hover:border-gray-200 hover:text-gray-900'
                                }`}
                        >
                            {cat.name}
                        </button>
                    ))}
                </nav>

                <div className="flex-1 overflow-y-auto p-4 scrollbar-thin scrollbar-thumb-gray-200 bg-gray-50/30">
                    <div className="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 2xl:grid-cols-6 gap-3">
                        {((!selectedCategory ? categories.flatMap(c => c.items) : currentCategory?.items) || []).map((product) => (
                            <button
                                key={product.id}
                                onClick={() => addToCart(product)}
                                className={`p-4 rounded-[2.5rem] border transition-all text-left flex flex-col group relative overflow-hidden h-full active:scale-95 duration-500 hover:-translate-y-2 hover:shadow-2xl ${!product.background_color ? 'bg-white border-gray-100 hover:border-indigo-200 shadow-xl shadow-gray-200/50' : 'shadow-lg border-transparent'}`}
                                style={product.background_color && product.background_color.startsWith('#') ? { backgroundColor: product.background_color + '25' } : {}}
                            >
                                <div className="aspect-square rounded-3xl mb-4 overflow-hidden relative border border-gray-100/50 bg-gray-50/50 shadow-inner group-hover:shadow-none transition-all duration-700">
                                    {product.image ? (
                                        <img
                                            src={product.image.startsWith('http') ? product.image : API_BASE_ROOT + `/storage/${product.image}`}
                                            alt=""
                                            className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000 ease-out"
                                        />
                                    ) : (
                                        <div className="w-full h-full flex items-center justify-center text-indigo-500/20 font-black text-5xl italic select-none">
                                            {product.name[0]}
                                        </div>
                                    )}
                                    {/* Quick Add Overlay */}
                                    <div className="absolute inset-0 bg-white/10 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <div className="bg-indigo-600 text-white p-3 rounded-2xl shadow-2xl transform translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                                            <Plus size={20} strokeWidth={4} />
                                        </div>
                                    </div>
                                </div>
                                <div className="space-y-1">
                                    <h3 className="font-black text-gray-900 text-[11px] leading-tight line-clamp-2 uppercase tracking-tight group-hover:text-indigo-600 transition-colors">{product.name}</h3>
                                    <p className="text-indigo-600 font-black text-sm tracking-tighter">
                                        {parseFloat(product.price.toString()).toLocaleString('tr-TR')} ₺
                                    </p>
                                </div>
                                {product.background_color && (
                                    <div className="absolute -top-6 -right-6 w-12 h-12 bg-white/20 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700"></div>
                                )}
                            </button>
                        ))}
                    </div>
                </div>
            </div>

            <div className="w-full lg:w-[350px] xl:w-[400px] h-[400px] lg:h-full bg-white border-t lg:border-t-0 lg:border-l border-gray-200 flex flex-col shadow-2xl relative z-20 shrink-0">
                <div className="p-6 border-b border-gray-200 shrink-0">
                    <div className="flex justify-between items-center mb-4">
                        <h2 className="text-xl font-extrabold text-gray-900 tracking-tight">Sipariş Özeti</h2>
                        <span className="px-3 py-1 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest rounded-full">
                            {cart.length} ÜRÜN
                        </span>
                    </div>
                    <div className="flex bg-gray-100 p-1 rounded-xl gap-1">
                        <button
                            onClick={() => { setSummaryView('grouped'); setDeleteSelection([]); }}
                            className={`flex-1 px-4 py-2 rounded-lg font-black text-[10px] uppercase tracking-widest flex items-center justify-center gap-2 transition-all ${summaryView === 'grouped' ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-400 hover:text-gray-600'}`}
                        >
                            <LayoutGrid size={14} />
                            Toplu
                        </button>
                        <button
                            onClick={() => { setSummaryView('list'); setDeleteSelection([]); }}
                            className={`flex-1 px-4 py-2 rounded-lg font-black text-[10px] uppercase tracking-widest flex items-center justify-center gap-2 transition-all ${summaryView === 'list' ? 'bg-black text-white shadow-lg' : 'text-gray-400 hover:text-gray-600'}`}
                        >
                            <List size={14} />
                            Liste
                        </button>
                        <button
                            onClick={() => { setIsDeleteMode(!isDeleteMode); setDeleteSelection([]); }}
                            className={`flex-1 px-4 py-2 rounded-lg font-black text-[10px] uppercase tracking-widest flex items-center justify-center gap-2 transition-all ${isDeleteMode ? 'bg-red-600 text-white shadow-lg' : 'bg-red-50 text-red-500 hover:bg-red-100'}`}
                        >
                            <Trash2 size={14} />
                            Ürün Sil
                        </button>
                    </div>
                </div>

                <div className="flex-1 overflow-y-auto p-4 space-y-3 scrollbar-thin scrollbar-thumb-gray-100 pb-20">
                    {(() => {
                        const displayItems: any[] = [];

                        if (summaryView === 'grouped') {
                            cart.forEach((item, originalIdx) => {
                                const existing = displayItems.find(g =>
                                    g.menu_id === item.menu_id &&
                                    g.notes === item.notes &&
                                    !!g.id === !!item.id &&
                                    JSON.stringify(g.selected_options) === JSON.stringify(item.selected_options) &&
                                    g.unit_price === item.unit_price
                                );
                                if (existing) {
                                    existing.quantity = Number(existing.quantity) + Number(item.quantity);
                                    existing.originalIndices.push(originalIdx);
                                } else {
                                    displayItems.push({
                                        ...item,
                                        originalIndices: [originalIdx],
                                        isGrouped: true
                                    });
                                }
                            });
                        } else {
                            // Explode quantities into separate X1 rows
                            cart.forEach((item, originalIdx) => {
                                const qty = Number(item.quantity || 1);
                                for (let i = 0; i < qty; i++) {
                                    displayItems.push({
                                        ...item,
                                        quantity: 1, // Force each row to be 1x
                                        originalIdx: originalIdx,
                                        explodedId: `${originalIdx}-${i}` // Unique for display key
                                    });
                                }
                            });
                        }

                        if (displayItems.length === 0) {
                            return (
                                <div className="flex flex-col items-center justify-center h-full text-gray-300 py-10 opacity-50">
                                    <Plus size={48} className="mb-4" />
                                    <p className="font-bold text-sm uppercase tracking-widest">SEPET ŞU AN BOŞ</p>
                                </div>
                            );
                        }

                        return displayItems.map((item, i) => {
                            const isSelectedForDelete = item.isGrouped
                                ? item.originalIndices.every((idx: number) => deleteSelection.includes(`${idx}-0`)) // Check first unit for simplicity in grouped
                                : deleteSelection.includes(item.explodedId);

                            return (
                                <div
                                    key={item.explodedId || i}
                                    onClick={() => {
                                        if (isDeleteMode) {
                                            if (item.isGrouped) {
                                                const groupIds = item.originalIndices.flatMap((idx: number) => {
                                                    const qty = Number(cart[idx].quantity || 1);
                                                    return Array.from({ length: qty }, (_, i) => `${idx}-${i}`);
                                                });
                                                const allIn = groupIds.every((id: string) => deleteSelection.includes(id));
                                                if (allIn) {
                                                    setDeleteSelection(prev => prev.filter(id => !groupIds.includes(id)));
                                                } else {
                                                    setDeleteSelection(prev => [...new Set([...prev, ...groupIds])]);
                                                }
                                            } else {
                                                const uid = item.explodedId || `${item.originalIdx}-0`;
                                                setDeleteSelection(prev =>
                                                    prev.includes(uid)
                                                        ? prev.filter(id => id !== uid)
                                                        : [...prev, uid]
                                                );
                                            }
                                        }
                                    }}
                                    className={`flex items-center gap-4 group p-3 rounded-2xl border transition-all ${isDeleteMode ? 'cursor-pointer' : ''} ${isSelectedForDelete ? 'border-red-500 bg-red-50/30' : item.id ? 'bg-blue-50/20 border-blue-100/50' : 'bg-white border-gray-100 hover:border-primary/20 hover:shadow-sm'}`}
                                >
                                    {isDeleteMode && (
                                        <div className={`w-5 h-5 rounded-full border-2 shrink-0 flex items-center justify-center transition-all ${isSelectedForDelete ? 'bg-red-500 border-red-500 text-white' : 'border-gray-300 bg-white'}`}>
                                            {isSelectedForDelete && <CheckCircle2 size={12} strokeWidth={4} />}
                                        </div>
                                    )}

                                    <div className="h-16 w-16 rounded-2xl bg-gray-50 flex items-center justify-center text-primary/40 shrink-0 relative overflow-hidden border border-gray-100 font-black text-lg select-none">
                                        {item.image ? (
                                            <img
                                                src={item.image.startsWith('http') ? item.image : API_BASE_ROOT + `/storage/${item.image}`}
                                                alt=""
                                                className="w-full h-full object-cover"
                                            />
                                        ) : (
                                            <div className="flex flex-col items-center">
                                                <span className="leading-none">{item.name[0]}</span>
                                                <div className="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent"></div>
                                            </div>
                                        )}
                                    </div>

                                    <div className="flex-1 min-w-0">
                                        <h4 className="text-[13px] font-black text-gray-900 truncate leading-tight mb-1">{item.name}</h4>

                                        <div className="flex flex-wrap gap-1 mt-1">
                                            {item.selected_options && item.selected_options.map((opt: any, oidx: number) => (
                                                <span key={oidx} className="text-[9px] bg-indigo-50 text-indigo-600 px-1.5 py-0.5 rounded-md font-bold">
                                                    {opt.name}
                                                </span>
                                            ))}
                                            {item.weight_grams && (
                                                <span className="text-[9px] bg-amber-50 text-amber-600 px-1.5 py-0.5 rounded-md font-bold">
                                                    {item.weight_grams} g
                                                </span>
                                            )}
                                        </div>

                                        <p className="text-indigo-600 font-black text-xs mt-1.5 italic tracking-tight">{(Number(item.unit_price) * Number(item.quantity)).toLocaleString('tr-TR')} ₺</p>
                                    </div>

                                    <div className="flex flex-col items-end gap-2 shrink-0">
                                        <div className="flex items-center gap-2">
                                            {!isDeleteMode && (
                                                <button
                                                    title="Sil"
                                                    onClick={() => {
                                                        if (item.isGrouped) {
                                                            // Remove all items in this group
                                                            const indices = [...item.originalIndices].sort((a, b) => b - a);
                                                            indices.forEach(idx => removeFromCart(idx));
                                                        } else {
                                                            removeFromCart(item.originalIdx);
                                                        }
                                                    }}
                                                    className="w-8 h-8 rounded-xl bg-red-50 text-red-500 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:bg-red-500 hover:text-white"
                                                >
                                                    <Trash2 size={14} />
                                                </button>
                                            )}

                                            {!item.id ? (
                                                <div className="flex items-center gap-1.5 bg-gray-900 rounded-xl p-1 shadow-md" onClick={(e) => e.stopPropagation()}>
                                                    <button
                                                        title="Azalt"
                                                        onClick={(e) => { e.stopPropagation(); updateQuantity(item.isGrouped ? item.originalIndices[0] : item.originalIdx, -1); }}
                                                        className="w-7 h-7 flex items-center justify-center hover:bg-gray-800 rounded-lg transition-colors text-white"
                                                    >
                                                        <Minus size={12} strokeWidth={4} />
                                                    </button>
                                                    <span className="text-xs font-black w-6 text-center text-white">{item.quantity}</span>
                                                    <button
                                                        title="Artır"
                                                        onClick={(e) => { e.stopPropagation(); updateQuantity(item.isGrouped ? item.originalIndices[0] : item.originalIdx, 1); }}
                                                        className="w-7 h-7 flex items-center justify-center hover:bg-gray-800 rounded-lg transition-colors text-white"
                                                    >
                                                        <Plus size={12} strokeWidth={4} />
                                                    </button>
                                                </div>
                                            ) : (
                                                <div className="flex flex-col items-end">
                                                    <span className="px-2 py-1 bg-gray-100 text-gray-400 text-[10px] font-black rounded-lg border border-gray-200">
                                                        X{item.quantity}
                                                    </span>
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            );
                        });
                    })()}
                </div>

                <div className="p-6 bg-gray-50/50 border-t border-gray-200 space-y-6 shrink-0">
                    <div className="space-y-4">
                        <div className="flex justify-between items-center text-gray-900 font-black text-2xl pt-2 border-t border-gray-200 tracking-tighter">
                            <span>TOPLAM</span>
                            <span className="text-primary">{subtotal.toLocaleString('tr-TR', { minimumFractionDigits: 2 })} ₺</span>
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            {isDeleteMode ? (
                                <>
                                    <button
                                        onClick={() => { setIsDeleteMode(false); setDeleteSelection([]); }}
                                        className="col-span-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition-all flex items-center justify-center gap-2 active:scale-95"
                                    >
                                        İptal
                                    </button>
                                    <button
                                        onClick={handleBulkDelete}
                                        disabled={deleteSelection.length === 0}
                                        className={`col-span-1 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-2 ${deleteSelection.length === 0 ? 'bg-red-50 text-red-200 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700 text-white shadow-xl shadow-red-600/20 active:scale-95'}`}
                                    >
                                        <Trash2 size={16} />
                                        ({deleteSelection.length}) Sil
                                    </button>
                                </>
                            ) : (
                                <>
                                    <button
                                        onClick={() => tableId !== 'takeaway' ? handleSubmitOrder(true) : alert('Yazdırılıyor...')}
                                        disabled={submitting || (tableId !== 'takeaway' && cart.filter(item => !item.id).length === 0)}
                                        className={`py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all shadow-lg flex flex-col items-center justify-center gap-1 ${(submitting || (tableId !== 'takeaway' && cart.filter(item => !item.id).length === 0))
                                            ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                            : 'bg-black text-white hover:bg-gray-800 active:scale-95'
                                            }`}
                                    >
                                        {tableId !== 'takeaway' ? (
                                            <>
                                                {submitting ? <Loader2 className="h-4 w-4 animate-spin" /> : <Plus size={14} strokeWidth={4} />}
                                                Sipariş Gönder
                                            </>
                                        ) : (
                                            <>
                                                <Printer size={14} strokeWidth={3} />
                                                YAZDIR
                                            </>
                                        )}
                                    </button>

                                    <button
                                        onClick={handlePaymentClick}
                                        className="py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all shadow-xl shadow-blue-600/20 active:scale-95 flex flex-col items-center justify-center gap-1"
                                    >
                                        <CreditCard size={14} strokeWidth={3} />
                                        Ödeme Al
                                    </button>
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </div>

            {/* Weight Entry Modal */}
            {
                showWeightModal && activeSelection && (
                    <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                        <div className="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                            <div className="p-8 text-center border-b border-gray-100">
                                <h3 className="text-2xl font-black text-gray-900 mb-2">{activeSelection.name}</h3>
                                <p className="text-gray-500 font-bold uppercase tracking-widest text-xs">Gramaj Girin (Gram)</p>
                                <div className="mt-6 flex items-baseline justify-center gap-2">
                                    <span className="text-5xl font-black text-primary tracking-tighter">{weightInput || '0'}</span>
                                    <span className="text-xl font-bold text-gray-400">g</span>
                                </div>
                                <p className="mt-2 text-indigo-600 font-black">
                                    {((activeSelection.price / 1000) * (weightInput === '' ? 1000 : parseInt(weightInput))).toLocaleString('tr-TR')} ₺
                                </p>
                            </div>
                            <div className="p-6 grid grid-cols-3 gap-3 bg-gray-50">
                                {[1, 2, 3, 4, 5, 6, 7, 8, 9, 'C', 0, 'OK'].map((key) => (
                                    <button
                                        key={key.toString()}
                                        title={key.toString()}
                                        onClick={() => {
                                            if (key === 'C') setWeightInput('');
                                            else if (key === 'OK') {
                                                const g = weightInput === '' ? 1000 : parseInt(weightInput);
                                                if (g > 0) {
                                                    finalizeAddToCart(activeSelection, g / 1000, activeSelection.price, g);
                                                    setWeightInput('');
                                                }
                                            } else {
                                                if (weightInput.length < 5) setWeightInput(prev => prev + key);
                                            }
                                        }}
                                        className={`h-16 rounded-2xl flex items-center justify-center text-xl font-black transition-all active:scale-95 ${key === 'OK'
                                            ? 'bg-indigo-600 text-white col-span-1 shadow-xl shadow-indigo-600/30'
                                            : key === 'C'
                                                ? 'bg-red-500 text-white shadow-xl shadow-red-500/20'
                                                : 'bg-white text-gray-900 border border-gray-100 shadow-sm'
                                            }`}
                                    >
                                        {key}
                                    </button>
                                ))}
                            </div>
                            <button
                                onClick={() => setShowWeightModal(false)}
                                className="w-full py-4 bg-white text-gray-400 font-bold uppercase tracking-widest text-[10px] hover:text-gray-600 transition-colors"
                            >
                                İptal
                            </button>
                        </div>
                    </div>
                )
            }

            {/* Options Selection Modal */}
            {
                showOptionsModal && activeSelection && (
                    <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                        <div className="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
                            <div className="p-8 border-b border-gray-100 flex justify-between items-center">
                                <div>
                                    <h3 className="text-2xl font-black text-gray-900">{activeSelection.name}</h3>
                                    <p className="text-gray-500 font-bold uppercase tracking-widest text-xs mt-1">Seçenekleri Belirleyin</p>
                                </div>
                                <div className="text-right">
                                    <span className="text-2xl font-black text-primary">
                                        {(activeSelection.price + Object.values(selectedOptions).reduce((sum: number, opt: any) => sum + (opt.price_extra || 0), 0)).toLocaleString('tr-TR')} ₺
                                    </span>
                                </div>
                            </div>

                            <div className="p-8 space-y-8 max-h-[60vh] overflow-y-auto">
                                {(activeSelection.options || []).map((group: any, gIdx: number) => (
                                    <div key={gIdx} className="space-y-4">
                                        <h4 className="font-black text-gray-900 uppercase tracking-widest text-[11px] flex items-center gap-2">
                                            <div className="w-1.5 h-1.5 rounded-full bg-primary"></div>
                                            {group.name}
                                        </h4>
                                        <div className="grid grid-cols-2 gap-3">
                                            {(group.values || []).map((val: any, vIdx: number) => {
                                                const isSelected = selectedOptions[group.name]?.name === val.name;
                                                return (
                                                    <button
                                                        key={vIdx}
                                                        onClick={() => setSelectedOptions((prev: any) => ({ ...prev, [group.name]: val }))}
                                                        className={`p-4 rounded-2xl border-2 text-left transition-all relative group ${isSelected
                                                            ? 'border-primary bg-primary/5 shadow-md'
                                                            : 'border-gray-100 hover:border-gray-200 bg-white'
                                                            }`}
                                                    >
                                                        <div className="font-bold text-gray-900 text-sm">{val.name}</div>
                                                        {val.price_extra > 0 && (
                                                            <div className="text-xs font-black text-primary mt-1">+{val.price_extra} ₺</div>
                                                        )}
                                                        {isSelected && (
                                                            <div className="absolute top-2 right-2 bg-primary text-white rounded-full p-0.5 animate-in zoom-in">
                                                                <CheckCircle2 size={12} />
                                                            </div>
                                                        )}
                                                    </button>
                                                );
                                            })}
                                        </div>
                                    </div>
                                ))}
                            </div>

                            <div className="p-6 bg-gray-50 flex items-center gap-4">
                                <div className="flex items-center gap-4 bg-white p-2 rounded-2xl border border-gray-200 shadow-sm">
                                    <button
                                        title="Azalt"
                                        onClick={() => setOptionQty(q => Math.max(1, q - 1))}
                                        className="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-900 rounded-xl hover:bg-gray-100 transition-all font-black border border-gray-100"
                                    >
                                        <Minus size={18} strokeWidth={3} />
                                    </button>
                                    <span className="w-8 text-center font-black text-lg text-gray-900">{optionQty}</span>
                                    <button
                                        title="Artır"
                                        onClick={() => setOptionQty(q => q + 1)}
                                        className="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-900 rounded-xl hover:bg-gray-100 transition-all font-black border border-gray-100"
                                    >
                                        <Plus size={18} strokeWidth={3} />
                                    </button>
                                </div>
                                <button
                                    onClick={() => {
                                        const extra = Object.values(selectedOptions).reduce((sum: number, opt: any) => sum + (opt.price_extra || 0), 0);
                                        finalizeAddToCart(activeSelection, optionQty, activeSelection.price + extra, undefined, Object.values(selectedOptions));
                                        setOptionQty(1);
                                    }}
                                    className="flex-1 py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl shadow-indigo-600/40 hover:scale-[1.02] active:scale-95 transition-all"
                                >
                                    Sepete Ekle
                                </button>
                            </div>
                        </div>
                    </div>
                )
            }

            {
                showPayment && (
                    <PaymentModal
                        totalAmount={subtotal}
                        onClose={() => {
                            setShowPayment(false);
                            fetchData(); // Refresh on close too in case they made any partial payments
                        }}
                        onComplete={() => {
                            fetchData();
                            setShowPayment(false);
                            // Only navigate if order is now empty (fully paid or closed)
                            // But usually, it's better to let user decide or navigate if preferred.
                            // However, navigate('/dashboard') was the previous behavior.
                            // Let's check if cart is empty after refresh.
                        }}
                        orderId={orderId}
                        tableId={tableId!}
                        items={cart}
                    />
                )
            }

            {/* Table Transfer Modal */}
            {
                showTransferModal && (
                    <div className="fixed inset-0 bg-black/80 backdrop-blur-sm z-[200] flex items-center justify-center p-4">
                        <div className="bg-gray-900 border border-gray-800 rounded-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">
                            <div className="p-4 border-b border-gray-800 flex justify-between items-center bg-gray-900/50">
                                <h2 className="text-xl font-bold text-white flex items-center gap-2">
                                    <LayoutGrid className="w-6 h-6 text-blue-400" />
                                    Masa İşlemleri
                                </h2>
                                <button
                                    onClick={() => setShowTransferModal(false)}
                                    className="p-2 hover:bg-gray-800 rounded-lg text-gray-400 hover:text-white transition-colors"
                                    title="Kapat"
                                >
                                    <ChevronLeft className="w-6 h-6" />
                                </button>
                            </div>

                            <div className="p-6 overflow-y-auto custom-scrollbar">
                                <p className="text-gray-400 mb-4">
                                    {transferMode === 'move'
                                        ? 'Bu masayı taşımak için boş bir masa seçin.'
                                        : 'Bu masayı birleştirmek için hedef masayı seçin.'}
                                </p>

                                {(() => {
                                    // Filter out current table
                                    const availableTables = allTables.filter(t => t.id !== tableId);

                                    // Filter based on mode
                                    // MOVE: Only empty tables
                                    // MERGE: All tables (usually occupied, but empty is valid for move-like merge)
                                    // User Request: "Masa taşımada dolu olan masaları göstermesin"
                                    const filteredTables = transferMode === 'move'
                                        ? availableTables.filter(t => t.status !== 'occupied')
                                        : availableTables;

                                    if (filteredTables.length === 0) {
                                        return (
                                            <div className="text-center py-8 bg-gray-800/30 rounded-xl border border-dashed border-gray-700">
                                                <p className="text-gray-400 font-medium">
                                                    {transferMode === 'move'
                                                        ? 'Taşınacak boş masa bulunamadı.'
                                                        : 'Birleştirilecek masa bulunamadı.'}
                                                </p>
                                            </div>
                                        );
                                    }

                                    return (
                                        <div className="grid grid-cols-3 gap-3">
                                            {filteredTables.map((table) => (
                                                <button
                                                    key={table.id}
                                                    onClick={() => setTargetTableId(String(table.id))}
                                                    className={`p-4 rounded-xl border flex flex-col items-center justify-center gap-2 transition-all ${targetTableId === String(table.id)
                                                        ? 'bg-blue-600 border-blue-500 text-white shadow-lg shadow-blue-500/20'
                                                        : 'bg-gray-800/50 border-gray-700 text-gray-300 hover:bg-gray-800 hover:border-gray-600'
                                                        }`}
                                                >
                                                    <span className="text-lg font-bold">{table.name}</span>
                                                    {table.status === 'occupied' ? (
                                                        <span className="text-xs px-2 py-0.5 bg-orange-500/20 text-orange-400 rounded-full border border-orange-500/20">Dolu</span>
                                                    ) : (
                                                        <span className="text-xs px-2 py-0.5 bg-green-500/20 text-green-400 rounded-full border border-green-500/20">Boş</span>
                                                    )}
                                                </button>
                                            ))}
                                        </div>
                                    );
                                })()}
                            </div>

                            <div className="p-4 border-t border-gray-800 bg-gray-900/50 flex gap-3">
                                <button
                                    onClick={() => setShowTransferModal(false)}
                                    className="flex-1 py-3 px-4 bg-gray-800 hover:bg-gray-700 text-white rounded-xl font-medium transition-colors"
                                >
                                    İptal
                                </button>
                                <button
                                    onClick={handleTransferTable}
                                    disabled={!targetTableId || submitting}
                                    className="flex-1 py-3 px-4 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                                >
                                    {submitting ? (
                                        <Loader2 className="w-5 h-5 animate-spin" />
                                    ) : (
                                        <>
                                            {allTables.find(t => t.id == targetTableId)?.status === 'occupied'
                                                ? 'Masaları Birleştir'
                                                : 'Masayı Taşı'
                                            }
                                        </>
                                    )}
                                </button>
                            </div>
                        </div>
                    </div>
                )
            }

            {/* Table Note Modal */}
            {showNoteModal && (
                <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-[250] flex items-center justify-center p-4">
                    <div className="bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl animate-in zoom-in duration-300">
                        <div className="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h2 className="text-2xl font-black text-gray-900 flex items-center gap-3">
                                <StickyNote className="w-8 h-8 text-amber-500" />
                                ADİSYON NOTU
                            </h2>
                            <button onClick={() => setShowNoteModal(false)} className="p-3 hover:bg-white rounded-full transition-colors text-gray-400" title="Kapat">
                                <ChevronLeft className="w-6 h-6 rotate-90" />
                            </button>
                        </div>
                        <div className="p-8">
                            <textarea
                                value={tableNote}
                                onChange={(e) => setTableNote(e.target.value)}
                                placeholder="Masa için bir not yazın..."
                                className="w-full h-40 p-6 bg-gray-50 border-2 border-gray-100 rounded-[2rem] text-lg font-bold text-gray-900 placeholder:text-gray-300 focus:outline-none focus:border-amber-500/50 focus:bg-white transition-all resize-none"
                                autoFocus
                            />
                        </div>
                        <div className="p-8 bg-gray-50/50 flex gap-4">
                            <button
                                onClick={() => { setTableNote(''); setShowNoteModal(false); }}
                                className="flex-1 py-5 bg-white text-gray-500 rounded-[1.5rem] font-black uppercase text-xs tracking-widest border border-gray-200 hover:bg-gray-100 transition-all"
                            >
                                Temizle
                            </button>
                            <button
                                onClick={() => setShowNoteModal(false)}
                                className="flex-1 py-5 bg-amber-500 text-white rounded-[1.5rem] font-black uppercase text-xs tracking-widest shadow-xl shadow-amber-500/30 hover:scale-105 active:scale-95 transition-all"
                            >
                                Kaydet
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div >
    );
}
