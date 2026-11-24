/*
 * Soft floating pastel shapes around the hero product (procedural — no model
 * files to download). Budget: a few thousand triangles, pixel ratio <= 1.5,
 * no antialias on phones, rendering paused when the hero is off screen or
 * the tab is hidden. Mouse parallax on desktop.
 */
import {
    WebGLRenderer, Scene, PerspectiveCamera, AmbientLight, DirectionalLight,
    MeshStandardMaterial, Mesh, Group, SphereGeometry, BoxGeometry, TorusGeometry,
    CylinderGeometry, ExtrudeGeometry, Shape, Clock,
} from 'three';

const PASTELS = [0xffd9de, 0xbdebdd, 0xffe7a3, 0xece7fb, 0xdbeafb, 0xf26b5b];

function cloud(mat) {
    const g = new Group();
    const s = new SphereGeometry(0.42, 16, 12);
    [[0, 0, 0, 1], [0.42, -0.05, 0, 0.8], [-0.42, -0.05, 0, 0.78], [0.18, 0.22, 0, 0.72]].forEach(([x, y, z, k]) => {
        const m = new Mesh(s, mat);
        m.position.set(x, y, z);
        m.scale.setScalar(k);
        g.add(m);
    });
    return g;
}

function star(mat) {
    const shape = new Shape();
    for (let i = 0; i < 10; i++) {
        const r = i % 2 ? 0.2 : 0.46;
        const a = (i / 10) * Math.PI * 2 - Math.PI / 2;
        i ? shape.lineTo(Math.cos(a) * r, Math.sin(a) * r) : shape.moveTo(Math.cos(a) * r, Math.sin(a) * r);
    }
    const geo = new ExtrudeGeometry(shape, { depth: 0.14, bevelEnabled: true, bevelThickness: 0.06, bevelSize: 0.05, bevelSegments: 2 });
    geo.center();
    return new Mesh(geo, mat);
}

function rattle(matA, matB) {
    const g = new Group();
    const head = new Mesh(new SphereGeometry(0.3, 18, 14), matA);
    head.position.y = 0.42;
    const ring = new Mesh(new TorusGeometry(0.3, 0.06, 10, 28), matB);
    ring.position.y = 0.42;
    ring.rotation.x = Math.PI / 2;
    const handle = new Mesh(new CylinderGeometry(0.07, 0.07, 0.6, 12), matB);
    const knob = new Mesh(new SphereGeometry(0.11, 12, 10), matA);
    knob.position.y = -0.32;
    g.add(head, ring, handle, knob);
    return g;
}

function block(mat) {
    return new Mesh(new BoxGeometry(0.55, 0.55, 0.55, 1, 1, 1), mat);
}

export function mount(host) {
    const mobile = window.innerWidth < 768;
    const renderer = new WebGLRenderer({ alpha: true, antialias: !mobile, powerPreference: 'low-power' });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 1.5));
    renderer.domElement.setAttribute('aria-hidden', 'true');
    renderer.domElement.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;pointer-events:none;opacity:0;transition:opacity .8s cubic-bezier(.2,.8,.2,1)';
    host.appendChild(renderer.domElement);

    const scene = new Scene();
    const camera = new PerspectiveCamera(40, 1, 0.1, 50);
    camera.position.set(0, 0, 9);
    scene.add(new AmbientLight(0xffffff, 1.6));
    const sun = new DirectionalLight(0xffffff, 1.4);
    sun.position.set(3, 5, 6);
    scene.add(sun);

    const mat = (c) => new MeshStandardMaterial({ color: c, roughness: 0.55, metalness: 0.05 });
    // Positions are fractions of the visible half-width/height (-1..1), so the
    // shapes always hug the edges of the canvas — around the product photo,
    // never over it — whatever the canvas size.
    const items = [
        [cloud(mat(0xffffff)), -0.82, 0.72, 0.8],
        [star(mat(PASTELS[2])), 0.8, 0.78, 0.8],
        [rattle(mat(PASTELS[0]), mat(PASTELS[3])), 0.84, -0.58, 0.9],
        [block(mat(PASTELS[1])), -0.82, -0.66, 0.7],
        [star(mat(PASTELS[0])), 0.1, 0.9, 0.45],
        [cloud(mat(PASTELS[4])), 0.4, -0.88, 0.5],
        [block(mat(PASTELS[3])), -0.45, 0.9, 0.38],
    ];
    const group = new Group();
    items.forEach(([obj, fx, fy, s], i) => {
        obj.userData = { fx, fy, s, baseY: 0, phase: i * 1.3, spin: 0.15 + (i % 3) * 0.08 };
        group.add(obj);
    });
    scene.add(group);

    function resize() {
        const w = host.clientWidth, h = host.clientHeight;
        if (!w || !h) return;
        renderer.setSize(w, h, false);
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
        const halfH = Math.tan((camera.fov * Math.PI) / 360) * camera.position.z;
        const halfW = halfH * camera.aspect;
        const unit = Math.min(halfW, halfH) / 3;
        group.children.forEach((o, i) => {
            const u = o.userData;
            o.position.set(u.fx * halfW * 0.88, u.fy * halfH * 0.85, -i * 0.2);
            u.baseY = o.position.y;
            o.scale.setScalar(u.s * unit * (mobile ? 0.85 : 1));
        });
    }
    resize();
    window.addEventListener('resize', resize, { passive: true });

    // Mouse parallax (desktop only).
    let tx = 0, ty = 0;
    if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
        host.closest('[data-hero]').addEventListener('pointermove', (e) => {
            const r = host.getBoundingClientRect();
            tx = ((e.clientX - r.left) / r.width - 0.5) * 0.5;
            ty = ((e.clientY - r.top) / r.height - 0.5) * 0.3;
        }, { passive: true });
    }

    const clock = new Clock();
    let visible = true, running = false, raf = 0;

    function frame() {
        const t = clock.getElapsedTime();
        group.children.forEach((o) => {
            o.position.y = o.userData.baseY + Math.sin(t * 0.9 + o.userData.phase) * 0.18;
            o.rotation.y += o.userData.spin * 0.016;
            o.rotation.z = Math.sin(t * 0.6 + o.userData.phase) * 0.15;
        });
        group.rotation.y += (tx - group.rotation.y) * 0.05;
        group.rotation.x += (ty - group.rotation.x) * 0.05;
        renderer.render(scene, camera);
        raf = requestAnimationFrame(frame);
    }
    function play() { if (!running && visible && !document.hidden) { running = true; clock.start(); raf = requestAnimationFrame(frame); } }
    function pause() { running = false; cancelAnimationFrame(raf); clock.stop(); }

    new IntersectionObserver(([e]) => { visible = e.isIntersecting; visible ? play() : pause(); }).observe(host);
    document.addEventListener('visibilitychange', () => (document.hidden ? pause() : play()));

    play();
    requestAnimationFrame(() => { renderer.domElement.style.opacity = '1'; });
}
