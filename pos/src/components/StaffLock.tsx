import React, { useState, useEffect } from 'react';
import { Lock, Delete, ChevronRight, User, Loader2 } from 'lucide-react';
import api from '../lib/api';

interface StaffLockProps {
    children: React.ReactNode;
}

export default function StaffLock({ children }: StaffLockProps) {
    const [isLocked, setIsLocked] = useState(true);
    const [pin, setPin] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const [staffInfo, setStaffInfo] = useState < { name: string; position: string } | null > (null);
    const [loginMode, setLoginMode] = useState < 'staff' | 'master' > ('staff');

    useEffect(() => {
        const savedStaff = localStorage.getItem('pos_staff_session');
        if (savedStaff) {
            setStaffInfo(JSON.parse(savedStaff));
            setIsLocked(false);
        }
    }, []);

    const handlePinSubmit = async (finalPin: string) => {
        setLoading(true);
        setError('');
        try {
            const res = await api.post('/validate-pin', { pin: finalPin });
            if (res.data.success) {
                const staffData = res.data.data;
                setStaffInfo(staffData);
                localStorage.setItem('pos_staff_session', JSON.stringify(staffData));
                setIsLocked(false);
                setPin('');
            }
        } catch (err: any) {
            setError(err.response?.data?.message || 'Geçersiz PIN kodu.');
            setPin('');
        } finally {
            setLoading(false);
        }
    };

    const handleNumberClick = (num: string) => {
        const maxLength = loginMode === 'staff' ? 4 : 8;
        if (pin.length < maxLength) {
            const newPin = pin + num;
            setPin(newPin);
        }
    };

    const handleDelete = () => {
        setPin(pin.slice(0, -1));
    };

    const handleLogoutStaff = () => {
        localStorage.removeItem('pos_staff_session');
        setIsLocked(true);
        setStaffInfo(null);
    };



    if (isLocked) {
        const slots = loginMode === 'staff' ? 4 : 8;

        return (
            <div className="fixed inset-0 z-[100] bg-slate-900 flex items-center justify-center p-6 overflow-hidden">
                <div className="max-w-md w-full animate-in fade-in zoom-in duration-300">
                    <div className="text-center mb-8">
                        <div className={`w-24 h-24 rounded-3xl flex items-center justify-center mx-auto mb-6 border shadow-2xl backdrop-blur-xl transition-all duration-500 ${loginMode === 'staff' ? 'bg-indigo-500/20 border-indigo-500/20' : 'bg-purple-500/20 border-purple-500/20'}`}>
                            <Lock className={`w-10 h-10 animate-pulse transition-colors ${loginMode === 'staff' ? 'text-indigo-400' : 'text-purple-400'}`} />
                        </div>
                        <h1 className="text-4xl font-black text-white mb-2 tracking-tight">
                            {loginMode === 'staff' ? 'Personel Girişi' : 'Yönetici Girişi'}
                        </h1>
                        <p className="text-slate-400 font-medium">Lütfen {slots} haneli PIN kodunuzu girin</p>
                    </div>

                    <div className="flex bg-white/5 p-1 rounded-2xl mb-10 border border-white/10">
                        <button
                            onClick={() => { setLoginMode('staff'); setPin(''); setError(''); }}
                            className={`flex-1 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all ${loginMode === 'staff' ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-400 hover:text-white'}`}
                        >
                            Personel
                        </button>
                        <button
                            onClick={() => { setLoginMode('master'); setPin(''); setError(''); }}
                            className={`flex-1 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all ${loginMode === 'master' ? 'bg-purple-500 text-white shadow-lg shadow-purple-500/20' : 'text-slate-400 hover:text-white'}`}
                        >
                            Yönetici
                        </button>
                    </div>

                    <div className="flex justify-center gap-2 mb-10">
                        {Array.from({ length: slots }).map((_, i) => (
                            <div
                                key={i}
                                className={`w-12 h-16 rounded-xl border-2 transition-all duration-200 flex items-center justify-center text-2xl font-black ${pin.length > i
                                    ? loginMode === 'staff' ? 'bg-indigo-500 border-indigo-500 text-white shadow-lg shadow-indigo-500/30 scale-105' : 'bg-purple-500 border-purple-500 text-white shadow-lg shadow-purple-500/30 scale-105'
                                    : 'bg-white/5 border-white/10 text-transparent'
                                    }`}
                            >
                                {pin[i] ? '•' : ''}
                            </div>
                        ))}
                    </div>

                    {error && (
                        <div className="bg-red-500/20 border border-red-500/50 text-red-200 px-4 py-3 rounded-xl text-center mb-8 font-bold animate-shake">
                            {error}
                        </div>
                    )}

                    <div className="grid grid-cols-3 gap-4 mb-8">
                        {['1', '2', '3', '4', '5', '6', '7', '8', '9'].map((num) => (
                            <button
                                key={num}
                                onClick={() => handleNumberClick(num)}
                                className="h-16 bg-white/5 hover:bg-white/10 border border-white/10 text-white text-3xl font-black rounded-2xl transition-all active:scale-95 flex items-center justify-center"
                            >
                                {num}
                            </button>
                        ))}
                        <button
                            onClick={handleDelete}
                            title="Sil"
                            className="h-16 bg-white/5 hover:bg-red-500/20 border border-white/10 text-white rounded-2xl transition-all active:scale-95 flex items-center justify-center"
                        >
                            <Delete className="w-8 h-8" />
                        </button>
                        <button
                            onClick={() => handleNumberClick('0')}
                            className="h-16 bg-white/5 hover:bg-white/10 border border-white/10 text-white text-3xl font-black rounded-2xl transition-all active:scale-95 flex items-center justify-center"
                        >
                            0
                        </button>
                        <button
                            onClick={() => handlePinSubmit(pin)}
                            disabled={pin.length < slots || loading}
                            className={`h-16 text-white rounded-2xl transition-all active:scale-95 flex items-center justify-center shadow-lg ${loginMode === 'staff' ? 'bg-indigo-600 hover:bg-indigo-500 shadow-indigo-600/20' : 'bg-purple-600 hover:bg-purple-500 shadow-purple-600/20'} disabled:bg-slate-700 disabled:shadow-none`}
                        >
                            {loading ? <Loader2 className="w-6 h-6 animate-spin" /> : <ChevronRight className="w-8 h-8" />}
                        </button>
                    </div>


                </div>
            </div>
        );
    }

    return (
        <div className="relative h-full flex flex-col">
            <div className="bg-white border-b border-gray-100 px-6 py-3 flex justify-between items-center shrink-0">
                <div className="flex items-center gap-3">
                    <div className="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                        <User className="w-5 h-5 text-indigo-600" />
                    </div>
                    <div>
                        <p className="text-xs font-bold text-gray-400 uppercase tracking-widest">{staffInfo?.position}</p>
                        <p className="font-bold text-gray-900">{staffInfo?.name}</p>
                    </div>
                </div>
                <button
                    onClick={handleLogoutStaff}
                    className="px-4 py-2 bg-gray-50 hover:bg-red-50 text-gray-500 hover:text-red-600 rounded-xl transition-all font-bold text-sm border border-gray-100"
                >
                    Kilitle
                </button>
            </div>
            <div className="flex-1 overflow-hidden">
                {children}
            </div>
        </div>
    );
}
