/**
 * Web Push Subscription Logic
 */

export function initWebPush() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        console.warn('Push notifications are not supported by this browser.');
        return;
    }

    registerServiceWorker();
}

function registerServiceWorker() {
    navigator.serviceWorker.register('/sw.js')
        .then(registration => {
            console.log('ServiceWorker registration successful with scope: ', registration.scope);
            checkSubscription(registration);
        })
        .catch(err => console.error('ServiceWorker registration failed: ', err));
}

function checkSubscription(registration) {
    registration.pushManager.getSubscription()
        .then(subscription => {
            window.isPushEnabled = !!subscription;
            // Dispatch event for Alpine.js to pick up
            window.dispatchEvent(new CustomEvent('push-status-changed', { detail: !!subscription }));

            if (subscription) {
                updateSubscription(subscription);
            }
            // Expose globally
            window.subscribePush = () => subscribeUser(registration);
            window.unsubscribePush = () => unsubscribeUser(registration);
        });
}

function subscribeUser(registration) {
    const publicKey = window.VAPID_PUBLIC_KEY;
    if (!publicKey) {
        console.error('VAPID public key not found.');
        return;
    }

    const applicationServerKey = urlBase64ToUint8Array(publicKey);

    registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: applicationServerKey
    })
        .then(subscription => {
            console.log('User is subscribed:', subscription);
            updateSubscription(subscription);
            window.isPushEnabled = true;
            window.dispatchEvent(new CustomEvent('push-status-changed', { detail: true }));
            showToast('Bildirimlere başarıyla abone oldunuz!', 'success');
        })
        .catch(err => {
            console.error('Failed to subscribe the user: ', err);
            showToast('Bildirim aboneliği başarısız oldu.', 'error');
        });
}

function unsubscribeUser(registration) {
    registration.pushManager.getSubscription()
        .then(subscription => {
            if (subscription) {
                subscription.unsubscribe().then(successful => {
                    if (successful) {
                        console.log('User is unsubscribed.');
                        removeSubscriptionFromServer(subscription);
                        window.isPushEnabled = false;
                        window.dispatchEvent(new CustomEvent('push-status-changed', { detail: false }));
                        showToast('Bildirimler devre dışı bırakıldı.', 'info');
                    }
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
        .then(res => res.json())
        .then(data => console.log('Subscription updated on server', data))
        .catch(err => console.error('Failed to update subscription on server', err));
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
        .catch(err => console.error('Failed to remove subscription from server', err));
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
