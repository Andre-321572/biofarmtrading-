// public/js/admin-notifications.js

class AdminNotifications {
    constructor() {
        this.lastOrderId = null;
        this.audio = null;
        this.init();
    }

    init() {
        console.log('Admin Notifications Loaded');
        // Start polling as a robust fallback
        this.pollForOrders();
    }

    enableAudio() {
        // Must be called via user interaction
        this.audio = new Audio('/sounds/notification.mp3');
        this.audio.play().then(() => {
            console.log('Audio notifications enabled');
        }).catch(e => console.log('Initial audio check:', e));

        if (Notification.permission !== 'granted') {
            Notification.requestPermission();
        }
    }

    async pollForOrders() {
        try {
            const response = await fetch('/admin/api/latest-order');
            if (!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();

            if (data && data.id) {
                if (this.lastOrderId !== null && data.id > this.lastOrderId) {
                    this.notify(data);
                }
                this.lastOrderId = data.id;
            }
        } catch (error) {
            console.error('Error polling for orders:', error);
        }

        setTimeout(() => this.pollForOrders(), 10000); // Polling reduced to 10s for better stability
    }

    notify(order) {
        // Play sound if allowed
        if (this.audio) {
            this.audio.play().catch(e => console.log('Audio play failed:', e));
        }

        // Show browser notification
        if (Notification.permission === 'granted') {
            try {
                const notification = new Notification('Nouvelle Commande !', {
                    body: `#${order.id} - ${order.customer_name} (${order.total_amount} FCFA)`,
                    icon: '/images/logo.jpg',
                });

                notification.onclick = () => {
                    window.focus();
                    window.location.href = `/admin/orders/${order.id}`;
                };
            } catch (e) {
                console.error('Notification creation failed:', e);
            }
        }
    }
}

// Global instance
window.adminNotifications = new AdminNotifications();

