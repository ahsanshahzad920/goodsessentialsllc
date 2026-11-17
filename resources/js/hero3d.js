/*
 * Hero 3D loader (tiny, always shipped on the homepage).
 *
 * The Three.js scene is decoration only, so it is loaded LAST and only when
 * the device can afford it:
 *   - after window "load" (never competes with the LCP image)
 *   - not with prefers-reduced-motion or Save-Data
 *   - viewport >= 768px, or a phone reporting >= 4 GB memory
 * Otherwise the static hero (already on the page) is all the shopper sees.
 */
function capable() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return false;
    const conn = navigator.connection || {};
    if (conn.saveData) return false;
    if (/(^|-)2g$/.test(conn.effectiveType || '')) return false;
    return window.innerWidth >= 768 || (navigator.deviceMemory || 0) >= 4;
}

function boot() {
    const host = document.querySelector('[data-hero-3d]');
    if (!host || !capable()) return;
    // A WebGL context must be available; otherwise keep the static hero.
    try {
        const probe = document.createElement('canvas');
        if (!(probe.getContext('webgl2') || probe.getContext('webgl'))) return;
    } catch (e) {
        return;
    }
    const start = () => import('./hero3d-scene.js').then((m) => m.mount(host)).catch(() => {});
    'requestIdleCallback' in window ? requestIdleCallback(start, { timeout: 2500 }) : setTimeout(start, 1200);
}

if (document.readyState === 'complete') boot();
else window.addEventListener('load', boot, { once: true });
