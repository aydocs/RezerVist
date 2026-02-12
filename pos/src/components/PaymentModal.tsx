import { useState } from 'react';
import { X, CreditCard, Banknote, CheckCircle2, Printer, Users, ShoppingBag, Trash2, Settings2, Percent, Ticket } from 'lucide-react';
import classNames from 'classnames';
import { useNavigate } from 'react-router-dom';
import api from '../lib/api';
import CacheManager from '../lib/cache';

interface PaymentModalProps {
    totalAmount: number;
    onClose: () => void;
    onComplete?: () => void;
    orderId: number | null; // Optional for takeaway
    tableId: string; // Required to identify counter sales
    items: any[];
}

type PaymentMethodId = 'cash' | 'credit_card' | 'sodexo' | 'ticket';

interface PaymentMethodDef {
    id: PaymentMethodId;
    label: string;
    icon: any;
    color: string;
    bgColor: string;
    shadowColor: string;
}

const PAYMENT_METHODS: PaymentMethodDef[] = [
    { id: 'credit_card', label: 'KREDİ KARTI', icon: CreditCard, color: 'text-indigo-600', bgColor: 'bg-indigo-50', shadowColor: 'shadow-indigo-500/20' },
    { id: 'cash', label: 'NAKİT PARA', icon: Banknote, color: 'text-emerald-500', bgColor: 'bg-emerald-50', shadowColor: 'shadow-emerald-500/20' },
    { id: 'sodexo', label: 'SODEXO', icon: Ticket, color: 'text-orange-500', bgColor: 'bg-orange-50', shadowColor: 'shadow-orange-500/20' },
    { id: 'ticket', label: 'TICKET', icon: Ticket, color: 'text-red-500', bgColor: 'bg-red-50', shadowColor: 'shadow-red-500/20' },
];

export default function PaymentModal({ totalAmount, onClose, onComplete, orderId: initialOrderId, tableId, items = [] }: PaymentModalProps) {
    const navigate = useNavigate();
    const [orderId, setOrderId] = useState < number | null > (initialOrderId);
    const [selectedMethod, setSelectedMethod] = useState < PaymentMethodId | null > (null);
    const [showAdvanced, setShowAdvanced] = useState(false);
    const [splitMode, setSplitMode] = useState < 'full' | 'equal' | 'item' > ('full');
    const [numPersons, setNumPersons] = useState(2);
    const [selectedItemIds, setSelectedItemIds] = useState < number[] > ([]);
    const [isProcessing, setIsProcessing] = useState(false);
    const [isSuccess, setIsSuccess] = useState(false);
    const [error, setError] = useState('');

    // Discount State
    const [discountType, setDiscountType] = useState < 'percent' | 'fixed' > ('percent');
    const [discountValue, setDiscountValue] = useState(0);

    // SPLAT LOGIC: Split items with quantity > 1 into individual rows
    // This needs to be calculated before baseAmount to correctly reference indices
    const splittedItems: any[] = [];
    items.forEach((item, originalIdx) => {
        const qty = Number(item.quantity || 1);
        for (let i = 0; i < qty; i++) {
            splittedItems.push({
                ...item,
                quantity: 1, // Force each row to be 1x
                originalIdx,
                dbId: item.id, // Actual database ID from OrderItem
                uniqueId: `item-${originalIdx}-${i}-${item.id || item.menu_id}`
            });
        }
    });

    const baseAmount = splitMode === 'full'
        ? totalAmount
        : splitMode === 'equal'
            ? totalAmount / (numPersons || 1)
            : splittedItems
                .filter((_, idx) => selectedItemIds.includes(idx))
                .reduce((s, it) => s + Number(it.unit_price || 0), 0);

    const discountAmount = discountType === 'percent'
        ? (baseAmount * discountValue) / 100
        : discountValue;

    const currentTotal = Math.max(0, baseAmount - discountAmount);

    const handlePayment = async () => {
        if (!selectedMethod) return;
        setIsProcessing(true);
        setError('');
        try {
            let targetOrderId = orderId;

            // 1. If takeaway and no orderId, create the order first
            if (tableId === 'takeaway' && !targetOrderId) {
                const res = await api.post('/order/submit', {
                    resource_id: 'takeaway',
                    items: items
                });
                if (res.data.success) {
                    targetOrderId = res.data.data.id;
                    setOrderId(targetOrderId);
                } else {
                    throw new Error('Sipariş oluşturulamadı.');
                }
            }

            // 2. Map selected indices back to actual database IDs
            const selectedDbIds = splitMode === 'item'
                ? splittedItems
                    .filter((_, idx) => selectedItemIds.includes(idx))
                    .map(it => it.dbId)
                    .filter(id => !!id)
                : [];

            // 3. Process payment with the order ID (new or existing)
            const payRes = await api.post(`/order/${targetOrderId}/pay`, {
                amount: currentTotal,
                method: selectedMethod,
                is_partial: splitMode !== 'full',
                item_ids: selectedDbIds,
                discount: discountAmount
            });

            if (payRes.data.success) {
                CacheManager.invalidate('tables');
                setIsSuccess(true);
            }
        } catch (err: any) {
            console.error('Ödeme hatası:', err);
            setError(err.response?.data?.message || err.message || 'Ödeme işlemi başarısız oldu.');
        } finally {
            setIsProcessing(false);
        }
    };

    if (!orderId && tableId !== 'takeaway') {
        return (
            <div className="fixed inset-0 z-[60] flex items-center justify-center bg-gray-950/60 backdrop-blur-sm">
                <div className="bg-white p-12 rounded-[2.5rem] shadow-2xl flex flex-col items-center gap-6">
                    <div className="w-12 h-12 border-4 border-indigo-600/20 border-t-indigo-600 rounded-full animate-spin" />
                    <p className="text-gray-900 font-black tracking-widest text-xs uppercase">Sipariş Bilgisi Alınıyor...</p>
                </div>
            </div>
        );
    }

    if (isSuccess) {
        return (
            <div className="fixed inset-0 z-[60] flex items-center justify-center bg-gray-950/90 backdrop-blur-2xl animate-in fade-in duration-500">
                <div className="text-center p-12 max-w-sm w-full animate-in zoom-in duration-300">
                    <div className="h-24 w-24 bg-green-500 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-[0_20px_40px_rgba(34,197,94,0.3)] rotate-12">
                        <CheckCircle2 className="h-12 w-12 text-white -rotate-12" />
                    </div>
                    <h2 className="text-3xl font-black text-white mb-2 tracking-tight">ÖDEME ALINDI</h2>
                    <p className="text-green-500 font-bold uppercase tracking-widest text-[10px] mb-12">İşlem Başarıyla Tamamlandı</p>

                    <div className="space-y-4">
                        <button onClick={() => alert('Yazdırılıyor...')} className="w-full py-5 bg-white/5 hover:bg-white/10 text-white rounded-2xl font-black uppercase tracking-widest transition-all border border-white/10 flex items-center justify-center gap-3 active:scale-95">
                            <Printer size={20} /> FİŞ YAZDIR
                        </button>
                        <button onClick={() => { if (onComplete) onComplete(); else navigate('/dashboard'); onClose(); }} className="w-full py-6 bg-white text-black rounded-2xl font-black uppercase tracking-[0.2em] shadow-xl hover:bg-gray-100 transition-all active:scale-95">
                            DEVAM ET
                        </button>
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/60 backdrop-blur-sm animate-in fade-in duration-300 p-4">
            <div className="bg-white rounded-[3rem] w-full max-w-5xl min-h-[600px] flex flex-col overflow-hidden shadow-2xl relative">

                {/* Header / Summary */}
                <div className="p-12 pb-8 flex justify-between items-start border-b border-gray-50 bg-gray-50/50">
                    <div className="flex gap-12">
                        <div>
                            <h3 className="text-gray-400 font-black text-[10px] uppercase tracking-[0.25em] mb-2">ÖDENECEK TOPLAM</h3>
                            <div className="text-5xl font-black text-gray-900 tracking-tighter flex items-baseline gap-2">
                                {Number(currentTotal || 0).toLocaleString('tr-TR', { minimumFractionDigits: 2 })}
                                <span className="text-2xl text-indigo-600 font-black">₺</span>
                            </div>
                        </div>
                        {discountAmount > 0 && (
                            <div className="animate-in slide-in-from-left-4">
                                <h3 className="text-gray-400 font-black text-[10px] uppercase tracking-[0.25em] mb-2">İNDİRİM</h3>
                                <div className="text-5xl font-black text-emerald-500 tracking-tighter flex items-baseline gap-2">
                                    -{discountAmount.toLocaleString('tr-TR', { minimumFractionDigits: 2 })}
                                    <span className="text-2xl font-black">₺</span>
                                </div>
                            </div>
                        )}
                    </div>
                    <button title="Kapat" onClick={onClose} className="p-4 bg-white hover:bg-red-50 hover:text-red-500 rounded-2xl transition-all border border-gray-100 active:scale-90 shadow-sm">
                        <X className="h-6 w-6" />
                    </button>
                </div>

                {/* Main Content */}
                <div className="flex-1 p-12 flex flex-col">
                    {!showAdvanced ? (
                        <div className="flex-1 flex flex-col">
                            <h4 className="text-center text-gray-400 font-black text-[10px] uppercase tracking-[0.2em] mb-12">BİR ÖDEME YÖNTEMİ SEÇİN</h4>

                            <div className="grid grid-cols-2 gap-8 flex-1 mb-12">
                                {PAYMENT_METHODS.slice(0, 2).map((method) => (
                                    <button
                                        key={method.id}
                                        onClick={() => setSelectedMethod(method.id)}
                                        className={classNames(
                                            "flex flex-col items-center justify-center gap-8 p-12 rounded-[2.5rem] border-4 transition-all duration-300 group relative overflow-hidden",
                                            selectedMethod === method.id
                                                ? `border-indigo-600 ${method.bgColor}/50 shadow-2xl ${method.shadowColor} scale-[1.02]`
                                                : "border-gray-50 bg-gray-50/30 text-gray-400 hover:border-gray-100 hover:bg-gray-50"
                                        )}
                                    >
                                        <method.icon size={64} className={classNames("transition-all duration-500", selectedMethod === method.id ? "text-indigo-600 scale-110" : "text-gray-200 group-hover:text-gray-300")} />
                                        <span className={classNames("font-black tracking-[0.2em] text-sm", selectedMethod === method.id ? "text-gray-900" : "text-gray-400")}>{method.label}</span>
                                    </button>
                                ))}
                            </div>

                            <div className="flex flex-col gap-4">
                                <button
                                    onClick={handlePayment}
                                    disabled={!selectedMethod || isProcessing}
                                    className={classNames(
                                        "w-full py-8 text-2xl font-black text-white rounded-[2rem] transition-all duration-500 flex items-center justify-center gap-4 active:scale-95 shadow-2xl",
                                        selectedMethod ? "bg-indigo-600 shadow-indigo-500/40" : "bg-gray-200 cursor-not-allowed"
                                    )}
                                >
                                    {isProcessing ? (
                                        <div className="w-8 h-8 border-[5px] border-white/20 border-t-white rounded-full animate-spin" />
                                    ) : (
                                        <>
                                            <CheckCircle2 size={32} />
                                            <span className="tracking-[0.2em]">ÖDEMEYİ TAMAMLA</span>
                                        </>
                                    )}
                                </button>

                                <button
                                    onClick={() => {
                                        setShowAdvanced(true);
                                        if (splitMode === 'full') setSplitMode('equal');
                                    }}
                                    className="py-4 text-gray-400 font-bold text-[10px] uppercase tracking-widest hover:text-indigo-600 transition-all flex items-center justify-center gap-2"
                                >
                                    <Settings2 size={14} /> DETAYLAR / İNDİRİM / BÖL
                                </button>
                            </div>
                        </div>
                    ) : (
                        <div className="flex-1 flex flex-col animate-in slide-in-from-right-12 duration-500">
                            <div className="flex justify-between items-center mb-8">
                                <h4 className="text-gray-900 font-black text-sm uppercase tracking-widest">GELİŞMİŞ AYARLAR</h4>
                                <button onClick={() => setShowAdvanced(false)} className="text-indigo-600 font-black text-[10px] uppercase tracking-widest bg-indigo-50 px-6 py-3 rounded-xl hover:bg-indigo-100 transition-all">← GERİ DÖN</button>
                            </div>

                            <div className="grid grid-cols-2 gap-12 flex-1">
                                <div className="space-y-12">
                                    {/* Split Section */}
                                    <div className="grid gap-4">
                                        <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-2"><Users size={14} /> HESAP BÖLME</label>
                                        <div className="flex flex-col gap-3">
                                            {/* Top Full Width Button */}
                                            <button
                                                onClick={() => setSplitMode('full')}
                                                className={classNames(
                                                    "w-full flex items-center justify-center gap-3 p-5 rounded-2xl border-2 transition-all",
                                                    splitMode === 'full' ? "border-indigo-600 bg-indigo-50 text-indigo-600 shadow-lg shadow-indigo-600/10 scale-[1.02]" : "border-gray-50 text-gray-400 hover:border-gray-100"
                                                )}
                                            >
                                                <CheckCircle2 size={20} />
                                                <span className="font-black text-xs tracking-[0.2em]">TAMAMINI ÖDE</span>
                                            </button>

                                            {/* Bottom Two Split Buttons */}
                                            <div className="grid grid-cols-2 gap-3">
                                                {[
                                                    { id: 'equal' as const, label: 'KİŞİYE BÖL', icon: Users },
                                                    { id: 'item' as const, label: 'ÜRÜN SEÇ', icon: Trash2 },
                                                ].map((mode) => (
                                                    <button
                                                        key={mode.id}
                                                        onClick={() => setSplitMode(mode.id)}
                                                        className={classNames(
                                                            "flex items-center justify-center gap-3 p-5 rounded-2xl border-2 transition-all text-center",
                                                            splitMode === mode.id ? "border-indigo-600 bg-indigo-50 text-indigo-600 shadow-lg shadow-indigo-600/10 scale-105" : "border-gray-50 text-gray-400 hover:border-gray-100"
                                                        )}
                                                    >
                                                        <mode.icon size={18} />
                                                        <span className="font-black text-[10px] tracking-widest leading-tight">{mode.label}</span>
                                                    </button>
                                                ))}
                                            </div>
                                        </div>
                                    </div>

                                    {/* Discount Section */}
                                    <div className="grid gap-4">
                                        <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-2"><Percent size={14} /> İNDİRİM UYGULA</label>
                                        <div className="flex flex-col gap-4">
                                            <div className="flex bg-gray-50 p-1.5 rounded-2xl">
                                                <button onClick={() => { setDiscountType('percent'); setDiscountValue(0); }} className={classNames("flex-1 py-3 text-[10px] font-black rounded-xl transition-all", discountType === 'percent' ? "bg-white text-indigo-600 shadow-sm" : "text-gray-400")}>YÜZDE (%)</button>
                                                <button onClick={() => { setDiscountType('fixed'); setDiscountValue(0); }} className={classNames("flex-1 py-3 text-[10px] font-black rounded-xl transition-all", discountType === 'fixed' ? "bg-white text-indigo-600 shadow-sm" : "text-gray-400")}>TUTAR (₺)</button>
                                            </div>
                                            <div className="grid grid-cols-4 gap-2">
                                                {[5, 10, 20, 50].map(val => (
                                                    <button
                                                        key={val}
                                                        onClick={() => setDiscountValue(val)}
                                                        className={classNames(
                                                            "py-4 rounded-xl border-2 font-black text-xs transition-all",
                                                            discountValue === val && discountType === 'percent' ? "border-emerald-500 bg-emerald-50 text-emerald-600" : "border-gray-50 text-gray-400 hover:border-gray-100"
                                                        )}
                                                    >
                                                        {discountType === 'percent' ? `%${val}` : `${val}₺`}
                                                    </button>
                                                ))}
                                            </div>

                                            {/* Manual Discount Input */}
                                            <div className="relative group/input">
                                                <input
                                                    type="number"
                                                    inputMode={discountType === 'percent' ? "numeric" : "decimal"}
                                                    value={discountValue || ''}
                                                    onChange={(e) => setDiscountValue(Number(e.target.value))}
                                                    placeholder={discountType === 'percent' ? "Özel Yüzde (%)" : "Özel Tutar (₺)"}
                                                    className="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl py-5 px-6 font-black text-sm text-gray-900 placeholder:text-gray-300 focus:bg-white focus:border-indigo-600 outline-none transition-all pr-12 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                />
                                                <div className="absolute right-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within/input:text-indigo-600 transition-colors">
                                                    {discountType === 'percent' ? <Percent size={18} /> : <span className="font-black text-lg">₺</span>}
                                                </div>
                                            </div>

                                            <button
                                                onClick={() => setDiscountValue(0)}
                                                className="w-full py-4 bg-red-50 text-red-500 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all border border-red-100 flex items-center justify-center gap-2 active:scale-95"
                                            >
                                                İNDİRİMİ SIFIRLA
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div className="flex flex-col gap-8">
                                    {splitMode === 'item' ? (
                                        <div className="space-y-4 animate-in fade-in flex-1">
                                            <div className="flex justify-between items-center mb-6">
                                                <h3 className="text-sm font-black text-gray-400 uppercase tracking-widest flex items-center gap-2"><ShoppingBag size={16} /> ÜRÜN LİSTESİ</h3>
                                                <span className="text-[10px] font-bold text-gray-400">HER ÜRÜNÜ TEK TEK SEÇEBİLİRSİNİZ</span>
                                            </div>
                                            <div className="space-y-2 max-h-[350px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-100">
                                                {splittedItems.map((item, idx) => {
                                                    const isSelected = selectedItemIds.includes(idx); // Use splitted index as tracking ID
                                                    return (
                                                        <button
                                                            key={item.uniqueId}
                                                            onClick={() => {
                                                                setSelectedItemIds(prev =>
                                                                    prev.includes(idx) ? prev.filter(id => id !== idx) : [...prev, idx]
                                                                );
                                                            }}
                                                            className={classNames(
                                                                "w-full flex items-center justify-between p-4 rounded-2xl border-2 transition-all",
                                                                isSelected ? "border-indigo-600 bg-indigo-50 shadow-md" : "border-gray-50 bg-white"
                                                            )}
                                                        >
                                                            <div className="flex items-center gap-3">
                                                                <div className={classNames("w-6 h-6 rounded-full flex items-center justify-center border-2 transition-all", isSelected ? "bg-indigo-600 border-indigo-600 text-white" : "border-gray-100")}>
                                                                    {isSelected && <CheckCircle2 size={14} />}
                                                                </div>
                                                                <div className="text-left">
                                                                    <div className="text-xs font-black text-gray-900">1x {item.name}</div>
                                                                    {item.selected_options && item.selected_options.length > 0 && (
                                                                        <div className="text-[9px] text-indigo-600 font-bold uppercase tracking-tighter">
                                                                            {item.selected_options.map((o: any) => o.name).join(', ')}
                                                                        </div>
                                                                    )}
                                                                </div>
                                                            </div>
                                                            <div className="text-xs font-black text-gray-900">{(Number(item.unit_price) || 0).toLocaleString('tr-TR', { minimumFractionDigits: 2 })} ₺</div>
                                                        </button>
                                                    );
                                                })}
                                            </div>
                                        </div>
                                    ) : (
                                        <div className="flex-1 flex items-center justify-center text-center p-8 border-2 border-dashed border-gray-100 rounded-[2.5rem] mb-4">
                                            {splitMode === 'equal' && (
                                                <div className="space-y-4">
                                                    <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest">Kişi Sayısı</label>
                                                    <div className="flex items-center gap-6 justify-center">
                                                        <button onClick={() => setNumPersons(Math.max(2, numPersons - 1))} className="w-14 h-14 bg-white rounded-xl border border-gray-100 flex items-center justify-center text-xl font-black text-gray-400 hover:border-indigo-600 hover:text-indigo-600 transition-all shadow-sm active:scale-95">-</button>
                                                        <input
                                                            type="number"
                                                            inputMode="numeric"
                                                            value={numPersons}
                                                            onChange={(e) => setNumPersons(Math.max(1, Number(e.target.value)))}
                                                            className="text-4xl font-black text-gray-900 w-20 text-center bg-transparent border-none focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                                        />
                                                        <button onClick={() => setNumPersons(numPersons + 1)} className="w-14 h-14 bg-white rounded-xl border border-gray-100 flex items-center justify-center text-xl font-black text-gray-400 hover:border-indigo-600 hover:text-indigo-600 transition-all shadow-sm active:scale-95">+</button>
                                                    </div>
                                                </div>
                                            )}
                                        </div>
                                    )}

                                    {/* Advanced View Payment Methods & Confirm */}
                                    <div className="space-y-6 pt-6 border-t border-gray-50">
                                        <h4 className="text-[10px] font-black text-gray-400 uppercase tracking-widest">ÖDEME YÖNTEMİ</h4>
                                        <div className="grid grid-cols-4 gap-3">
                                            {PAYMENT_METHODS.map((method) => (
                                                <button
                                                    key={method.id}
                                                    onClick={() => setSelectedMethod(method.id)}
                                                    className={classNames(
                                                        "py-6 rounded-2xl border-2 transition-all flex flex-col items-center justify-center gap-2",
                                                        selectedMethod === method.id ? `border-indigo-600 ${method.bgColor} ${method.color}` : "border-gray-50 text-gray-300 hover:border-gray-100"
                                                    )}
                                                >
                                                    <method.icon size={20} />
                                                    <span className="font-black text-[8px] tracking-tighter uppercase">{method.label.split(' ')[0]}</span>
                                                </button>
                                            ))}
                                        </div>

                                        <button
                                            onClick={handlePayment}
                                            disabled={!selectedMethod || isProcessing}
                                            className={classNames(
                                                "w-full py-6 text-xl font-black text-white rounded-2xl transition-all flex items-center justify-center gap-3 active:scale-95 shadow-xl",
                                                selectedMethod ? "bg-indigo-600 shadow-indigo-500/20" : "bg-gray-200 cursor-not-allowed"
                                            )}
                                        >
                                            {isProcessing ? <div className="w-6 h-6 border-4 border-white/20 border-t-white rounded-full animate-spin" /> : <>ÖDEMEYİ TAMAMLA</>}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}
                </div>

                {error && (
                    <div className="px-12 pb-12">
                        <div className="p-6 bg-red-50 text-red-600 rounded-3xl text-sm font-bold border border-red-100 flex items-center gap-3 animate-pulse">
                            <span className="h-2 w-2 rounded-full bg-red-500"></span>
                            {error}
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}
