import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Only wire up real-time chat when Reverb is actually configured.
// Without this guard, a missing VITE_REVERB_APP_KEY (e.g. in production where
// Reverb isn't enabled) makes Pusher throw at load time, which would break
// ALL page JavaScript (Alpine, animations, menus).
const reverbKey = import.meta.env.VITE_REVERB_APP_KEY;

if (reverbKey) {
    window.Pusher = Pusher;

    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: reverbKey,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
        wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
    });
}
