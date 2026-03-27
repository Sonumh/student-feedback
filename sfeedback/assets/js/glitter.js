// ============================================
// KJU Feedback — Glitter Trail + Cursor + BG Particles
// Fixed: cursor visible on desktop, glitter on mouse + touch,
//        canvas never blocks form interaction
// ============================================

(function () {

  const canvas = document.getElementById('glitterCanvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  let W = canvas.width  = window.innerWidth;
  let H = canvas.height = window.innerHeight;

  canvas.style.pointerEvents = 'none';

  window.addEventListener('resize', () => {
    W = canvas.width  = window.innerWidth;
    H = canvas.height = window.innerHeight;
  });

  const isTouchDevice = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);

  if (!isTouchDevice) {
    document.addEventListener('mousemove', e => {
      spawnGlitter(e.clientX, e.clientY, 6);
    });
  }

  // ── Touch glitter — on document so form still works ──
  if (isTouchDevice) {
    document.addEventListener('touchmove', function (e) {
      for (let i = 0; i < e.changedTouches.length; i++) {
        spawnGlitter(e.changedTouches[i].clientX, e.changedTouches[i].clientY, 8);
      }
    }, { passive: true });

    document.addEventListener('touchstart', function (e) {
      for (let i = 0; i < e.changedTouches.length; i++) {
        spawnGlitter(e.changedTouches[i].clientX, e.changedTouches[i].clientY, 12);
      }
    }, { passive: true });
  }

  // ── Glitter particles ─────────────────────────
  const particles = [];

  const COLORS = [
    'rgba(230,57,70,',
    'rgba(255,80,95,',
    'rgba(200,20,35,',
    'rgba(255,160,165,',
    'rgba(255,200,200,',
    'rgba(255,255,255,',
    'rgba(255,100,50,',
  ];

  function spawnGlitter(x, y, count) {
    for (let i = 0; i < count; i++) {
      const angle   = Math.random() * Math.PI * 2;
      const speed   = 0.5 + Math.random() * 2.2;
      const size    = 0.6 + Math.random() * 3.2;
      const isWhite = Math.random() > 0.85;
      particles.push({
        x:        x + (Math.random() - 0.5) * 14,
        y:        y + (Math.random() - 0.5) * 14,
        vx:       Math.cos(angle) * speed,
        vy:       Math.sin(angle) * speed - 0.8,
        size,
        life:     70,
        maxLife:  70,
        color:    COLORS[Math.floor(Math.random() * COLORS.length)],
        shape:    Math.random() > 0.45 ? 'diamond' : (Math.random() > 0.5 ? 'circle' : 'spark'),
        rotation: Math.random() * Math.PI,
        rotSpeed: (Math.random() - 0.5) * 0.18,
        glow:     isWhite ? 12 : 7,
      });
    }
  }

  function drawParticle(p) {
    const t     = p.life / p.maxLife;
    const alpha = t < 0.25 ? (t / 0.25) * 0.9 : t * 0.9;
    ctx.save();
    ctx.translate(p.x, p.y);
    ctx.rotate(p.rotation);
    ctx.fillStyle   = p.color + alpha + ')';
    ctx.shadowColor = p.color + Math.min(alpha * 0.7, 0.5) + ')';
    ctx.shadowBlur  = p.glow;

    if (p.shape === 'circle') {
      ctx.beginPath();
      ctx.arc(0, 0, p.size, 0, Math.PI * 2);
      ctx.fill();
    } else if (p.shape === 'diamond') {
      const s = p.size;
      ctx.beginPath();
      ctx.moveTo(0, -s * 1.8);
      ctx.lineTo(s * 0.65, 0);
      ctx.lineTo(0, s * 1.8);
      ctx.lineTo(-s * 0.65, 0);
      ctx.closePath();
      ctx.fill();
    } else {
      const len = p.size * 3;
      ctx.strokeStyle = p.color + alpha + ')';
      ctx.lineWidth   = p.size * 0.5;
      ctx.lineCap     = 'round';
      ctx.beginPath();
      ctx.moveTo(0, -len);
      ctx.lineTo(0,  len);
      ctx.stroke();
    }
    ctx.restore();
  }

  (function loop() {
    ctx.clearRect(0, 0, W, H);
    for (let i = particles.length - 1; i >= 0; i--) {
      const p = particles[i];
      p.x  += p.vx;
      p.y  += p.vy;
      p.vy += 0.055;
      p.vx *= 0.985;
      p.rotation += p.rotSpeed;
      p.life--;
      if (p.life <= 0) { particles.splice(i, 1); continue; }
      drawParticle(p);
    }
    requestAnimationFrame(loop);
  })();

  // ── Floating Background Particles ─────────────
  const bgContainer = document.getElementById('bgParticles');
  if (bgContainer) {
    for (let i = 0; i < 22; i++) {
      const el     = document.createElement('div');
      el.className = 'bg-particle';
      const size   = 1.5 + Math.random() * 3.5;
      const left   = Math.random() * 100;
      const dur    = 10 + Math.random() * 16;
      const delay  = Math.random() * 14;
      const drift  = (Math.random() - 0.5) * 90;
      const op     = 0.04 + Math.random() * 0.07;
      el.style.cssText = `width:${size}px;height:${size}px;left:${left}%;--dur:${dur}s;--delay:${delay}s;--drift:${drift}px;--op:${op};`;
      bgContainer.appendChild(el);
    }
  }

})();
