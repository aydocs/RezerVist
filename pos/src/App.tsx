import { HashRouter, Routes, Route, Navigate } from 'react-router-dom';
import { useEffect } from 'react';
import Login from './pages/Login.tsx';
import Dashboard from './pages/Dashboard.tsx';
import OrderTerminal from './pages/OrderTerminal.tsx';
import Orders from './pages/Orders.tsx';
import Menu from './pages/Menu.tsx';
import Settings from './pages/Settings.tsx';
import Reports from './pages/Reports.tsx';
// import KitchenDisplay from './pages/KitchenDisplay.tsx';
import Invoices from './pages/Invoices.tsx';
import SetupPin from './pages/SetupPin.tsx';
import StaffLock from './components/StaffLock';
import api from './lib/api';
import SecureStorage from './lib/SecureStorage';
import UpdateNotifier from './components/UpdateNotifier';
import CacheManager from './lib/cache';

function App() {
  // Global Menu Pre-fetch for maximum speed
  useEffect(() => {
    const prefetchMenu = async () => {
      const token = SecureStorage.getToken();
      if (!token) return; // Don't prefetch if not logged in

      // Check cache first
      if (CacheManager.get('menu')) return;

      try {
        const res = await api.get('/menu');
        if (res.data.success) {
          CacheManager.set('menu', res.data.data);
        }
      } catch (err) {
        console.warn('Menu prefetch failed', err);
      }
    };
    prefetchMenu();
  }, []);

  const token = SecureStorage.getToken();

  return (
    <HashRouter>
      <Routes>
        <Route
          path="/login"
          element={token ? <Navigate to="/dashboard" replace /> : <Login />}
        />

        {/* Protected POS Routes */}
        <Route path="/*" element={
          !token ? <Navigate to="/login" replace /> : (
            <Routes>
              <Route path="/setup-pin" element={<SetupPin />} />
              <Route path="*" element={
                <StaffLock>
                  <Routes>
                    <Route path="/dashboard" element={<Dashboard />} />
                    <Route path="/orders" element={<Orders />} />
                    <Route path="/invoices" element={<Invoices />} />
                    <Route path="/menu" element={<Menu />} />
                    {/* <Route path="/kds" element={<KitchenDisplay />} /> */}
                    <Route path="/settings" element={<Settings />} />
                    <Route path="/reports" element={<Reports />} />
                    <Route path="/order/:tableId" element={<OrderTerminal />} />
                    <Route path="*" element={<Navigate to="/dashboard" replace />} />
                  </Routes>
                </StaffLock>
              } />
            </Routes>
          )
        } />
      </Routes>
      <UpdateNotifier />
    </HashRouter>
  )
}

export default App
