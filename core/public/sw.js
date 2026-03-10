self.addEventListener('push', function (event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    var data = {};
    if (event.data) {
        data = event.data.json();
    }

    console.log('Push received:', data);

    var title = data.title || 'RezerVist Bildirimi';
    var message = data.body || 'Yeni bir bildiriminiz var.';
    var icon = data.icon || '/icon.png';
    var url = data.action_url || '/';

    event.waitUntil(
        self.registration.showNotification(title, {
            body: message,
            icon: icon,
            badge: icon,
            data: {
                url: url
            }
        })
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.url)
    );
});

// Fetch handler removed to resolve "no-op fetch handler" warning.
// If PWA installability requires a fetch handler in the future, 
// implement a proper caching strategy here instead of an empty listener.
