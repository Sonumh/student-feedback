// ============================================
// KJU Feedback — Form Logic
// Saves to localStorage
// Validation: silent (blocks submit, no messages, no * labels)
// ============================================

document.addEventListener('DOMContentLoaded', () => {

  const form           = document.getElementById('feedbackForm');
  const starBtns       = document.querySelectorAll('.star-btn');
  const ratingInput    = document.getElementById('rating');
  const ratingLabel    = document.getElementById('ratingLabel');
  const submitBtn      = document.getElementById('submitBtn');
  const successOverlay = document.getElementById('successOverlay');
  const submitAnother  = document.getElementById('submitAnotherBtn');
  const successName    = document.getElementById('successName');
  const successEvent   = document.getElementById('successEvent');
  const successRating  = document.getElementById('successRating');

  const ratingWords = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
  const starSyms    = ['', '★☆☆☆☆', '★★☆☆☆', '★★★☆☆', '★★★★☆', '★★★★★'];

  // ── Star Rating ───────────────────────────────
  starBtns.forEach(btn => {
    btn.addEventListener('mouseenter', () => paintStars(+btn.dataset.value, 'hover'));
    btn.addEventListener('mouseleave', () => paintStars(+ratingInput.value, 'active'));
    btn.addEventListener('click', () => {
      ratingInput.value = btn.dataset.value;
      paintStars(+btn.dataset.value, 'active');
      clearShake('rating');
    });
  });

  function paintStars(val, cls) {
    starBtns.forEach(b => {
      b.classList.remove('active', 'hovered');
      if (+b.dataset.value <= val) b.classList.add(cls === 'hover' ? 'hovered' : 'active');
    });
    if (val > 0) {
      ratingLabel.innerHTML = `<span class="mono">${ratingWords[val]} (${val}/5)</span>`;
      ratingLabel.classList.add('has-value');
    } else {
      ratingLabel.innerHTML = `<span class="mono">Select a rating</span>`;
      ratingLabel.classList.remove('has-value');
    }
  }

  // ── Silent shake feedback (no text, just visual) ──
  function shakeField(field) {
    const fg = document.getElementById('fg-' + field);
    if (!fg) return;
    const input = fg.querySelector('input, select, .stars');
    fg.classList.remove('shake');
    void fg.offsetWidth; // reflow to restart animation
    fg.classList.add('shake');
    if (input) input.classList.add('invalid');
    setTimeout(() => fg.classList.remove('shake'), 500);
  }

  function clearShake(field) {
    const fg = document.getElementById('fg-' + field);
    if (!fg) return;
    const input = fg.querySelector('input, select');
    fg.classList.remove('shake');
    if (input) input.classList.remove('invalid');
  }

  document.getElementById('student_name').addEventListener('input', () => clearShake('name'));
  document.getElementById('student_id').addEventListener('input',   () => clearShake('sid'));
  document.getElementById('event_id').addEventListener('change',    () => clearShake('event'));

  function validate() {
    let valid = true;
    const missing = [];

    if (!document.getElementById('event_id').value)               { missing.push('event');  valid = false; }
    if (!document.getElementById('student_name').value.trim())    { missing.push('name');   valid = false; }
    if (!document.getElementById('student_id').value.trim())      { missing.push('sid');    valid = false; }
    if (!ratingInput.value)                                        { missing.push('rating'); valid = false; }

    if (!valid) {
      // Stagger shakes for each missing field
      missing.forEach((f, i) => setTimeout(() => shakeField(f), i * 80));
    }
    return valid;
  }

  // ── Save to localStorage ──────────────────────
  function saveFeedback(entry) {
    const existing = JSON.parse(localStorage.getItem('gw_feedback') || '[]');
    existing.unshift(entry);
    localStorage.setItem('gw_feedback', JSON.stringify(existing));
  }

  // ── Submit ────────────────────────────────────
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    if (!validate()) return;

    const name    = document.getElementById('student_name').value.trim();
    const sid     = document.getElementById('student_id').value.trim();
    const eventEl = document.getElementById('event_id');
    const event   = eventEl.options[eventEl.selectedIndex].text;
    const rating  = +ratingInput.value;

    submitBtn.classList.add('loading');

    setTimeout(() => {
      submitBtn.classList.remove('loading');

      saveFeedback({
        id:        Date.now(),
        name,
        studentId: sid,
        event,
        rating,
        timestamp: new Date().toISOString()
      });

      successName.textContent   = name;
      successEvent.textContent  = event;
      successRating.textContent = `RATING: ${starSyms[rating]}  ${ratingWords[rating].toUpperCase()} (${rating}/5)`;
      successOverlay.style.display = 'flex';
      successOverlay.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 850);
  });

  // ── Submit Another ────────────────────────────
  submitAnother.addEventListener('click', () => {
    successOverlay.style.display = 'none';
    form.reset();
    ratingInput.value = '';
    paintStars(0, 'active');
    ['event','name','sid','rating'].forEach(clearShake);
  });

});
