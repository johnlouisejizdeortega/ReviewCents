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

window.Alpine = Alpine;

Alpine.start();
