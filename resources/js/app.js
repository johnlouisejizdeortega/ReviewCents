import './echo';

import '@fontsource-variable/inter';
import '@fontsource-variable/jetbrains-mono';

import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';

Alpine.plugin(intersect);

/**
 * Terminal typewriter for the hero background.
 * Types code line-by-line with a blinking caret, then loops through snippets.
 * Falls back to the full static text when the user prefers reduced motion.
 */
Alpine.data('terminal', (snippets = []) => ({
    lines: [],
    current: '',
    done: false,
    reduced: window.matchMedia('(prefers-reduced-motion: reduce)').matches,

    init() {
        if (this.reduced || !snippets.length) {
            // Static fallback: show the first snippet in full, no animation.
            const first = snippets[0] || [];
            this.lines = [...first];
            this.done = true;
            return;
        }
        this.run();
    },

    async run() {
        // eslint-disable-next-line no-constant-condition
        while (true) {
            for (const snippet of snippets) {
                this.lines = [];
                this.current = '';
                for (const line of snippet) {
                    await this.type(line);
                    this.lines.push(line);
                    this.current = '';
                    await this.pause(160);
                }
                await this.pause(1600);
            }
        }
    },

    type(line) {
        return new Promise((resolve) => {
            let i = 0;
            const tick = () => {
                this.current = line.slice(0, i);
                i += 1;
                if (i <= line.length) {
                    setTimeout(tick, 22 + Math.floor((i % 3) * 8));
                } else {
                    resolve();
                }
            };
            tick();
        });
    },

    pause(ms) {
        return new Promise((resolve) => setTimeout(resolve, ms));
    },
}));

Alpine.data('themeToggle', () => ({
    dark: document.documentElement.classList.contains('dark'),
    toggle() {
        this.dark = !this.dark;
        document.documentElement.classList.toggle('dark', this.dark);
        try {
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        } catch (e) {}
    },
}));

// Slim top progress bar shown during full-page navigation.
Alpine.data('navProgress', () => ({
    active: false,
    width: 0,
    timer: null,
    init() {
        document.addEventListener('click', (e) => {
            const a = e.target.closest('a');
            if (!a) return;
            const href = a.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
            if (a.target === '_blank' || a.hasAttribute('download')) return;
            if (a.origin && a.origin !== location.origin) return;
            this.start();
        });
        document.addEventListener('submit', (e) => {
            if (!e.defaultPrevented) this.start();
        });
        window.addEventListener('pagehide', () => this.finish());
    },
    start() {
        this.active = true;
        this.width = 8;
        clearInterval(this.timer);
        this.timer = setInterval(() => {
            this.width = Math.min(this.width + Math.random() * 12, 90);
        }, 200);
    },
    finish() {
        clearInterval(this.timer);
        this.width = 100;
        setTimeout(() => { this.active = false; this.width = 0; }, 300);
    },
}));

window.Alpine = Alpine;

Alpine.start();
