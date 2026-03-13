/**
 * Web Push Subscription Logic
 */

export function initWebPush() {
    console.log('Push: Initializing...');
    
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        console.warn('Push notifications are not supported by this browser.');
        return;
    }

    // Expose entry points immediately to avoid "Sistem hazırlanıyor" errors
    window.subscribePush = () => {
        console.log('Push: Subscribe requested');
        navigator.serviceWorker.ready.then(registration => {
            subscribeUser(registration);
        }).catch(err => {
            console.error('Push: SW ready failed', err);
            showToast('Sistem hazır değil, lütfen sayfayı yenileyin.', 'error');
        });
    };

    window.unsubscribePush = () => {
        console.log('Push: Unsubscribe requested');
        navigator.serviceWorker.ready.then(registration => {
            unsubscribeUser(registration);
        });
    };

    registerServiceWorker();
}

function registerServiceWorker() {
    navigator.serviceWorker.register('/sw.js')
        .then(registration => {
            console.log('Push: ServiceWorker registration successful with scope: ', registration.scope);
            checkSubscription(registration);
        })
        .catch(err => {
            console.error('Push: ServiceWorker registration failed: ', err);
            showToast('Service Worker kaydı başarısız.', 'error');
        });
}

function checkSubscription(registration) {
    registration.pushManager.getSubscription()
        .then(subscription => {
            const isSubscribed = !!subscription;
            console.log('Push: Initial subscription status:', isSubscribed);
            
            window.isPushEnabled = isSubscribed;
            // Dispatch event for Alpine.js to pick up
            window.dispatchEvent(new CustomEvent('push-status-changed', { detail: isSubscribed }));

            if (subscription) {
                console.log('Push: Updating existing subscription on server...');
                updateSubscription(subscription);
            }
        })
        .catch(err => console.error('Push: Failed to get subscription', err));
}

function subscribeUser(registration) {
    const publicKey = window.VAPID_PUBLIC_KEY;
    if (!publicKey) {
        console.error('Push: VAPID public key not found.');
        showToast('Yapılandırma hatası: VAPID anahtarı bulunamadı.', 'error');
        return;
    }

    console.log('Push: Subscribing user...');
    const applicationServerKey = urlBase64ToUint8Array(publicKey);

    registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: applicationServerKey
    })
        .then(subscription => {
            console.log('Push: User is subscribed:', subscription);
            updateSubscription(subscription);
            window.isPushEnabled = true;
            window.dispatchEvent(new CustomEvent('push-status-changed', { detail: true }));
            showToast('Bildirimlere başarıyla abone oldunuz!', 'success');
        })
        .catch(err => {
            console.error('Push: Failed to subscribe the user: ', err);
            if (Notification.permission === 'denied') {
                showToast('Bildirim izinleri reddedilmiş. Lütfen tarayıcı ayarlarından izin verin.', 'warning');
            } else {
                showToast('Bildirim aboneliği başarısız oldu.', 'error');
            }
        });
}

function unsubscribeUser(registration) {
    registration.pushManager.getSubscription()
        .then(subscription => {
            if (subscription) {
                subscription.unsubscribe().then(successful => {
                    if (successful) {
                        console.log('Push: User is unsubscribed.');
                        removeSubscriptionFromServer(subscription);
                        window.isPushEnabled = false;
                        window.dispatchEvent(new CustomEvent('push-status-changed', { detail: false }));
                        showToast('Bildirimler devre dışı bırakıldı.', 'info');
                    }
                }).catch(err => {
                    console.error('Push: Unsubscribe failed', err);
                    showToast('Abonelik iptali başarısız oldu.', 'error');
                });
            }
        });
}

function updateSubscription(subscription) {
    fetch('/api/push/subscribe', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(subscription)
    })
        .then(res => {
            if (!res.ok) throw new Error('Server responded with ' + res.status);
            return res.json();
        })
        .then(data => console.log('Push: Subscription updated on server', data))
        .catch(err => console.error('Push: Failed to update subscription on server', err));
}

function removeSubscriptionFromServer(subscription) {
    fetch('/api/push/unsubscribe', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(subscription)
    })
        .then(res => console.log('Push: Subscription removed from server'))
        .catch(err => console.error('Push: Failed to remove subscription from server', err));
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
