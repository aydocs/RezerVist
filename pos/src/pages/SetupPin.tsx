import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { ShieldCheck, Loader2, AlertCircle } from 'lucide-react';
import api from '../lib/api';

export default function SetupPin() {
    const [pin, setPin] = useState('');
    const [confirmPin, setConfirmPin] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const navigate = useNavigate();

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setError('');

        if (pin.length !== 8) {
            setError('PIN kodu tam olarak 8 haneli olmalıdır.');
            return;
        }

        if (pin !== confirmPin) {
            setError('PIN kodları birbiriyle eşleşmiyor.');
            return;
        }

        if (!/^\d+$/.test(pin)) {
            setError('PIN kodu sadece rakamlardan oluşmalıdır.');
            return;
        }

        setLoading(true);
        try {
            const res = await api.post('/update-master-pin', { pin });
            if (res.data.success) {
                navigate('/dashboard');
            } else {
                setError(res.data.message || 'Üzgünüz, şifre güncellenemedi.');
            }
        } catch (err: any) {
            console.error('Setup PIN error:', err);
            setError('Sunucu hatası: Lütfen bağlantınızı kontrol edin.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-screen bg-[#0F111A] flex items-center justify-center p-4">
            <div className="max-w-md w-full">
                <div className="text-center mb-10">
                    <div className="inline-flex items-center justify-center w-20 h-20 bg-indigo-500/10 rounded-3xl mb-6 border border-indigo-500/20">
                        <ShieldCheck className="text-indigo-500 w-10 h-10" />
                    </div>
                    <h1 className="text-3xl font-black text-white tracking-tight mb-3">Güvenlik Kurulumu</h1>
                    <p className="text-gray-400 text-sm">Sistemi kullanmaya başlamadan önce lütfen 8 haneli yönetici (Master PIN) şifrenizi oluşturun.</p>
                </div>

                <div className="bg-[#161926] border border-white/5 rounded-[2.5rem] p-8 shadow-2xl">
                    <form onSubmit={handleSubmit} className="space-y-6">
                        {error && (
                            <div className="bg-rose-500/10 border border-rose-500/20 rounded-2xl p-4 flex items-center gap-3 text-rose-500 text-sm animate-shake">
                                <AlertCircle size={18} />
                                <span className="font-bold">{error}</span>
                            </div>
                        )}

                        <div className="space-y-2">
                            <label className="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] px-1">YENİ MASTER PIN</label>
                            <input
                                type="password"
                                maxLength={8}
                                value={pin}
                                onChange={(e) => setPin(e.target.value.replace(/\D/g, ''))}
                                placeholder="8 Haneli Sayı"
                                className="w-full bg-[#0F111A] border-2 border-white/5 rounded-2xl px-6 py-5 text-2xl text-center text-white font-mono tracking-[0.5em] focus:border-indigo-500 outline-none transition-all placeholder:text-gray-800 placeholder:tracking-normal placeholder:text-sm"
                                required
                            />
                        </div>

                        <div className="space-y-2">
                            <label className="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] px-1">PIN TEKRAR</label>
                            <input
                                type="password"
                                maxLength={8}
                                value={confirmPin}
                                onChange={(e) => setConfirmPin(e.target.value.replace(/\D/g, ''))}
                                placeholder="8 Haneli Sayı"
                                className="w-full bg-[#0F111A] border-2 border-white/5 rounded-2xl px-6 py-5 text-2xl text-center text-white font-mono tracking-[0.5em] focus:border-indigo-500 outline-none transition-all placeholder:text-gray-800 placeholder:tracking-normal placeholder:text-sm"
                                required
                            />
                        </div>

                        <button
                            type="submit"
                            disabled={loading || pin.length !== 8}
                            className="w-full bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 disabled:hover:bg-indigo-600 text-white font-black py-5 rounded-2xl transition-all shadow-xl shadow-indigo-600/20 mt-4 flex items-center justify-center gap-3 text-sm uppercase tracking-widest"
                        >
                            {loading ? <Loader2 className="animate-spin" size={20} /> : 'ŞİFREYİ OLUŞTUR VE BAŞLA'}
                        </button>
                    </form>

                    <div className="mt-8 p-4 bg-indigo-500/5 border border-indigo-500/10 rounded-2xl">
                        <p className="text-[10px] text-indigo-400 font-medium leading-relaxed">
                            <strong className="text-white uppercase tracking-tighter">Not:</strong> Bu şifre ile iptal, iade ve rapor ekranlarına erişim sağlanacaktır. Lütfen güvenli bir şifre seçin ve unutmayın.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
}
