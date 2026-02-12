import { NavLink, useNavigate, useLocation } from 'react-router-dom';
import { LayoutGrid, UtensilsCrossed, Receipt, Settings, LogOut, TrendingUp, FileText, ShoppingBag } from 'lucide-react';
import classNames from 'classnames';
import api from '../lib/api';
import SecureStorage from '../lib/SecureStorage';

export default function Sidebar() {
    const navigate = useNavigate();
    const location = useLocation();

    const handleLogout = async () => {
        // Prompt for admin PIN (Master PIN - 8 digits)
        const adminPin = prompt('Lisans anahtarını sıfırlamak için Yönetici PIN\'ini girin (8 hane):');

        if (!adminPin) {
            return; // User cancelled
        }

        if (adminPin.length !== 8) {
            alert('Yönetici PIN 8 hane olmalıdır!');
            return;
        }

        try {
            // Verify admin PIN with backend
            const res = await api.post('/verify-master-pin', { pin: adminPin });

            if (!res.data.success) {
                alert('Geçersiz Yönetici PIN!');
                return;
            }

            // If PIN is correct, proceed with logout
            if (window.confirm('Çıkış yapmak istediğinize emin misiniz?')) {
                // Call deactivation API to clear license lock
                try {
                    await api.post('/deactivate');
                } catch (deactivationErr) {
                    console.error('Deactivation failed:', deactivationErr);
                }

                SecureStorage.clearToken();
                localStorage.removeItem('pos_staff_session');
                navigate('/login');
            }
        } catch (err: any) {
            alert(err.response?.data?.message || 'PIN doğrulama hatası!');
        }
    };

    const staffSession = JSON.parse(localStorage.getItem('pos_staff_session') || '{}');
    const permissions = staffSession.permissions || [];
    const isAdmin = permissions.includes('all');

    const hasPermission = (perm: string) => isAdmin || permissions.includes(perm);

    const businessName = SecureStorage.getBusinessName() || 'İşletme';

    return (
        <aside className="h-full w-16 md:w-20 lg:w-64 bg-white border-r border-gray-200 flex flex-col shadow-sm relative z-50 transition-all duration-300 overflow-hidden shrink-0">
            {/* Logo Section */}
            <div className="p-4 md:p-6 flex items-center justify-center lg:justify-start gap-3">
                <div className="w-10 h-10 bg-gradient-to-br from-[#6200EE] to-purple-700 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-purple-200 shrink-0">
                    R
                </div>
                <div className="hidden lg:block min-w-0">
                    <h1 className="text-xl font-bold text-gray-900 tracking-tight truncate">RezerVistA</h1>
                    <p className="text-xs text-gray-500 font-medium tracking-wide truncate">{businessName}</p>
                </div>
            </div>

            {/* Navigation */}
            <nav className="flex-1 px-4 py-6 space-y-2">
                <NavItem to="/dashboard" icon={<LayoutGrid size={20} />} label="Masalar" active={location.pathname === '/dashboard'} />
                <NavItem to="/order/takeaway" icon={<ShoppingBag size={20} className="text-emerald-500" />} label="Tezgah Satış" active={location.pathname === '/order/takeaway'} />
                <NavItem to="/orders" icon={<Receipt size={20} />} label="Siparişler" active={location.pathname === '/orders'} />
                <NavItem to="/invoices" icon={<FileText size={20} />} label="Faturalar" active={location.pathname === '/invoices'} />

                {hasPermission('view_reports') && (
                    <NavItem to="/reports" icon={<TrendingUp size={20} />} label="Raporlar" active={location.pathname === '/reports'} />
                )}

                {hasPermission('manage_menu') && (
                    <NavItem to="/menu" icon={<UtensilsCrossed size={20} />} label="Menü" active={location.pathname === '/menu'} />
                )}

                {/* (hasPermission('view_kds') || isAdmin) && (
                    <NavItem to="/kds" icon={<ChefHat size={20} />} label="Mutfak" active={location.pathname === '/kds'} />
                ) */}

                {hasPermission('manage_settings') && (
                    <NavItem to="/settings" icon={<Settings size={20} />} label="Ayarlar" active={location.pathname === '/settings'} />
                )}
            </nav>

            {/* Footer / Logout */}
            <div className="p-4 border-t border-gray-100">
                <button
                    onClick={handleLogout}
                    className="flex items-center gap-3 px-4 py-3 w-full rounded-xl text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors group font-medium"
                >
                    <LogOut className="h-5 w-5 shrink-0 group-hover:-translate-x-1 transition-transform" />
                    <span className="hidden lg:block">Çıkış Yap</span>
                </button>
            </div>
        </aside>
    );
}

function NavItem({ to, icon, label, active }: { to: string; icon: React.ReactNode; label: string; active: boolean }) {
    return (
        <NavLink
            to={to}
            className={classNames(
                "flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group font-bold",
                active
                    ? "bg-primary/10 text-primary shadow-sm"
                    : "text-gray-400 hover:bg-gray-50 hover:text-gray-600"
            )}
            title={label}
        >
            <div className={classNames(
                "transition-transform duration-200 group-hover:scale-110 flex items-center justify-center",
                active ? "text-[#6200EE]" : "text-gray-300"
            )}>
                {icon}
            </div>
            <span className="tracking-tight hidden lg:block">{label}</span>
        </NavLink>
    );
}
