import { useState } from 'react';
import axios from 'axios';
import api from '../lib/api-client';
import { useNavigate } from 'react-router-dom';
import { Loader2, AlertCircle, ChefHat, Settings, Mail, Lock } from 'lucide-react';
import SecureStorage from '../lib/SecureStorage';
import { generateDeviceFingerprint } from '../lib/deviceFingerprint';

export default function Login() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const [showSetup, setShowSetup] = useState(false);
    const [baseUrl, setBaseUrl] = useState(localStorage.getItem('api_base_url') || 'http://localhost:8000/api');
    const navigate = useNavigate();

    const saveSetup = () => {
        localStorage.setItem('api_base_url', baseUrl);
        setShowSetup(false);
        window.location.reload();
    };

    const handleLogin = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoading(true);
        setError('');

        try {
            // 1. Authenticate User
            const loginUrl = `${baseUrl.replace(/\/pos$/, '')}/auth/login`; // Ensure we hit /api/auth/login
            console.log('🔐 Attempting login to:', loginUrl);

            const loginRes = await axios.post(loginUrl, {
                email,
                password
            });

            const token = loginRes.data.access_token;
            if (!token) throw new Error('Token alınamadı.');

            console.log('✅ Login successful, token received.');
            SecureStorage.setToken(token);

            // 2. Initialize POS (Device Registration & Checks)
            const deviceFingerprint = generateDeviceFingerprint();
            console.log('📱 Initializing POS with fingerprint:', deviceFingerprint);

            const initRes = await api.get('/init', {
                headers: {
                    'X-Device-Fingerprint': deviceFingerprint
                }
            });

            if (initRes.data.success) {
                console.log('🏢 Business initialized:', initRes.data.data.name);
                SecureStorage.setBusinessName(initRes.data.data.name);

                // 3. Redirect
                const targetPath = initRes.data.data.has_master_pin === false ? '/setup-pin' : '/dashboard';
                console.log('🔄 Redirecting to', targetPath);

                navigate(targetPath);

                // Force reload to ensure all states are fresh
                setTimeout(() => {
                    window.location.reload();
                }, 150);
            }

        } catch (err: any) {
            console.error('Login error:', err);
            let errorMsg = 'Giriş yapılamadı.';

            if (err.response) {
                // Determine if it's auth error or POS init error
                if (err.config?.url?.includes('/auth/login')) {
                    if (err.response.status === 401) {
                        errorMsg = 'E-posta veya şifre hatalı.';
                    } else {
                        errorMsg = err.response.data.message || 'Giriş işlemi başarısız.';
                    }
                } else {
                    // POS Init errors
                    const errorCode = err.response.data?.error_code;
                    if (errorCode === 'DEVICE_MISMATCH') {
                        errorMsg = '❌ Bu lisans/hesap başka bir cihazda kullanılıyor!\n\nHer hesap sadece 1 cihazda çalışır.';
                    } else if (errorCode === 'SUBSCRIPTION_EXPIRED') {
                        errorMsg = '❌ Üyeliğiniz sona ermiş.';
                    } else {
                        errorMsg = err.response.data.message || 'POS başlatılamadı.';
                    }
                }
            } else if (err.request) {
                errorMsg = 'Sunucuya ulaşılamıyor. İnternet bağlantınızı kontrol edin.';
            }

            setError(errorMsg);
            // If init failed but login succeeded, we might want to clear token
            if (!err.config?.url?.includes('/auth/login')) {
                SecureStorage.clearToken();
            }
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-screen flex bg-white font-sans overflow-hidden">
            {/* Left Side - Image & Stats (Matching Backend Hero) */}
            <div className="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary via-purple-700 to-indigo-900 relative overflow-hidden">
                {/* Background Image with Overlay */}
                <div className="absolute inset-0 z-0">
                    <img
                        src="/login-bg.png"
                        alt="Restaurant"
                        className="w-full h-full object-cover opacity-30 mix-blend-overlay"
                    />
                </div>

                {/* Background Pattern */}
                <div className="absolute inset-0 opacity-10 z-1 login-pattern">
                </div>

                {/* Content Overlay */}
                <div className="relative z-10 flex flex-col justify-center items-center text-center px-12 w-full text-white">
                    <div className="mb-10">
                        <div className="w-24 h-24 bg-white/20 backdrop-blur-lg rounded-3xl flex items-center justify-center mb-6 mx-auto shadow-2xl border border-white/20">
                            <ChefHat className="w-12 h-12 text-white" />
                        </div>
                    </div>

                    <h1 className="text-5xl font-black mb-6 leading-tight tracking-tighter">
                        İşletmeniz İçin<br />Akıllı POS Sistemi
                    </h1>

                    <p className="text-xl text-white/80 mb-12 max-w-md leading-relaxed font-medium">
                        Bu sistemle siparişlerinizi yönetin, ödemelerinizi hızlandırın ve raporlayın.
                    </p>
                </div>
            </div>

            {/* Right Side - Login Form */}
            <div className="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white lg:bg-gray-50/50">
                <div className="max-w-md w-full px-4">
                    <div className="mb-12">
                        <h2 className="text-4xl font-extrabold text-gray-900 mb-3 tracking-tight">Giriş Yap</h2>
                        <p className="text-gray-500 font-medium text-lg">Hesabınızla giriş yapın.</p>
                    </div>

                    <form onSubmit={handleLogin} className="space-y-6">
                        {error && (
                            <div className="flex items-center gap-3 bg-red-50 border border-red-100 p-4 rounded-2xl text-red-600 text-sm font-bold animate-in fade-in duration-300 whitespace-pre-line">
                                <AlertCircle className="h-4 w-4 shrink-0" />
                                {error}
                            </div>
                        )}

                        <div className="space-y-4">
                            <div className="space-y-2">
                                <label className="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1">E-POSTA ADRESİ</label>
                                <div className="relative group">
                                    <div className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-primary transition-colors">
                                        <Mail className="h-5 w-5" />
                                    </div>
                                    <input
                                        type="email"
                                        required
                                        value={email}
                                        onChange={(e) => setEmail(e.target.value)}
                                        className="w-full pl-12 pr-4 py-4 border-2 border-gray-100 rounded-2xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all font-medium text-lg text-gray-900 placeholder-gray-300 bg-white"
                                        placeholder="ornek@alanadiniz.com"
                                    />
                                </div>
                            </div>

                            <div className="space-y-2">
                                <label className="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1">ŞİFRE</label>
                                <div className="relative group">
                                    <div className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-primary transition-colors">
                                        <Lock className="h-5 w-5" />
                                    </div>
                                    <input
                                        type="password"
                                        required
                                        value={password}
                                        onChange={(e) => setPassword(e.target.value)}
                                        className="w-full pl-12 pr-4 py-4 border-2 border-gray-100 rounded-2xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all font-medium text-lg text-gray-900 placeholder-gray-300 bg-white"
                                        placeholder="••••••••"
                                    />
                                </div>
                            </div>
                        </div>

                        <div className="flex flex-col gap-4 pt-4">
                            <button
                                type="submit"
                                disabled={loading}
                                className="w-full py-5 px-6 bg-gray-900 hover:bg-primary text-white font-black rounded-2xl shadow-xl shadow-gray-200 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-3 text-lg uppercase tracking-wider group"
                            >
                                {loading ? (
                                    <>
                                        <Loader2 className="animate-spin h-6 w-6" />
                                        <span>GİRİŞ YAPILIYOR...</span>
                                    </>
                                ) : (
                                    <>
                                        <span>GİRİŞ YAP</span>
                                        <div className="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center group-hover:bg-white/20 transition-colors">
                                            <AlertCircle className="rotate-90 h-4 w-4" />
                                        </div>
                                    </>
                                )}
                            </button>

                            <button
                                type="button"
                                onClick={() => setShowSetup(true)}
                                className="w-full py-4 px-6 border-2 border-gray-100 hover:border-primary/20 hover:bg-primary/5 text-gray-400 hover:text-primary font-black rounded-2xl transition-all flex items-center justify-center gap-3 text-xs uppercase tracking-[0.2em]"
                            >
                                <Settings className="h-4 w-4" />
                                <span>Bağlantı Ayarları</span>
                            </button>
                        </div>
                    </form>

                    {showSetup && (
                        <div className="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-gray-900/60 backdrop-blur-sm animate-in fade-in">
                            <div className="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden border border-white/20">
                                <div className="p-10">
                                    <div className="flex items-center gap-4 mb-8">
                                        <div className="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                                            <Settings className="w-6 h-6" />
                                        </div>
                                        <div>
                                            <h3 className="text-2xl font-black text-gray-900 tracking-tight uppercase">SİSTEM KURULUMU</h3>
                                            <p className="text-xs text-gray-500 font-bold uppercase tracking-widest">Global Bağlantı Ayarları</p>
                                        </div>
                                    </div>

                                    <div className="space-y-6">
                                        <div className="space-y-2">
                                            <label className="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">API SUNUCU ADRESİ</label>
                                            <input
                                                type="text"
                                                value={baseUrl}
                                                onChange={(e) => setBaseUrl(e.target.value)}
                                                className="w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-primary focus:bg-white rounded-2xl transition-all font-bold text-gray-900 outline-none"
                                                placeholder="http://localhost:8000/api"
                                            />
                                            <p className="text-[9px] text-gray-400 font-bold leading-relaxed px-1">
                                                * Normalde <b>http://localhost:8000/api</b> olmalıdır. Sonunda /pos olmamalıdır.
                                            </p>
                                        </div>

                                        <div className="flex gap-4 pt-4">
                                            <button
                                                onClick={() => setShowSetup(false)}
                                                className="flex-1 py-4 px-6 border-2 border-gray-100 text-gray-400 font-black rounded-2xl hover:bg-gray-50 transition-all text-xs uppercase tracking-widest"
                                            >
                                                VAZGEÇ
                                            </button>
                                            <button
                                                onClick={saveSetup}
                                                className="flex-1 py-4 px-6 bg-primary text-white font-black rounded-2xl shadow-lg shadow-primary/20 hover:scale-[1.02] transition-all text-xs uppercase tracking-widest"
                                            >
                                                AYARLARI KAYDET
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}

                    <p className="mt-12 text-center text-[10px] font-black text-gray-300 uppercase tracking-[0.2em]">
                        &copy; 2026 TEKNOLOJİ A.Ş.
                    </p>
                </div>
            </div>
        </div>
    );
}
