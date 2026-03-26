<?php
require_once 'config.php';

// Fetch active events
$events = [];
try {
    $db = getDB();
    $stmt = $db->query("SELECT id, event_name, event_date FROM events WHERE is_active = 1 ORDER BY event_date DESC");
    $events = $stmt->fetchAll();
} catch (Exception $e) {
    // handled gracefully in frontend
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Feedback — Gateway Electronics</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

  <!-- Noise texture overlay -->
  <div class="noise"></div>

  <!-- Scanline effect -->
  <div class="scanlines"></div>

  <!-- Header -->
  <header class="site-header">
    <div class="header-inner">
      <div class="logo-block">
        <div class="logo-icon">
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
            <rect x="2" y="2" width="32" height="32" rx="4" stroke="#e63946" stroke-width="2"/>
            <path d="M8 18h4l3-7 4 14 3-7h6" stroke="#e63946" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <div class="logo-text">
          <span class="logo-main">GATEWAY</span>
          <span class="logo-sub">ELECTRONICS</span>
        </div>
      </div>
      <div class="header-tag">
        <span class="mono">STUDENT_FEEDBACK_PORTAL</span>
      </div>
    </div>
  </header>

  <!-- Hero -->
  <section class="hero">
    <div class="hero-bg-text">FEEDBACK</div>
    <div class="hero-content">
      <div class="hero-label mono">// ACADEMIC EVENT REVIEW</div>
      <h1 class="hero-title">Share Your<br><span class="accent">Experience</span></h1>
      <p class="hero-desc">Help us improve every event. Your feedback shapes the future of Gateway Electronics.</p>
    </div>
    <div class="hero-line"></div>
  </section>

  <!-- Form Section -->
  <section class="form-section">
    <div class="form-container">

      <div class="form-header">
        <div class="form-header-num mono">01</div>
        <h2>Submit Feedback</h2>
        <div class="form-header-line"></div>
      </div>

      <!-- Success / Error messages -->
      <div id="msg-success" class="msg msg-success" style="display:none;">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="9" stroke="#4ade80" stroke-width="1.5"/><path d="M6 10l3 3 5-5" stroke="#4ade80" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <span>Feedback submitted successfully! Thank you.</span>
      </div>
      <div id="msg-error" class="msg msg-error" style="display:none;">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="9" stroke="#e63946" stroke-width="1.5"/><path d="M10 6v5M10 13v1" stroke="#e63946" stroke-width="1.5" stroke-linecap="round"/></svg>
        <span id="msg-error-text">Something went wrong. Please try again.</span>
      </div>

      <form id="feedbackForm" novalidate>

        <!-- Event Selection -->
        <div class="field-group">
          <label class="field-label" for="event_id">
            <span class="field-num mono">01</span>
            Select Event
            <span class="required">*</span>
          </label>
          <div class="select-wrap">
            <select id="event_id" name="event_id" required>
              <option value="">— Choose an event —</option>
              <?php foreach ($events as $ev): ?>
                <option value="<?= htmlspecialchars($ev['id']) ?>">
                  <?= htmlspecialchars($ev['event_name']) ?>
                  <?php if ($ev['event_date']): ?>
                    (<?= date('d M Y', strtotime($ev['event_date'])) ?>)
                  <?php endif; ?>
                </option>
              <?php endforeach; ?>
              <?php if (empty($events)): ?>
                <option value="" disabled>No active events available</option>
              <?php endif; ?>
            </select>
            <div class="select-arrow">
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M3 5l4 4 4-4" stroke="#e63946" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
          </div>
        </div>

        <!-- Student Name -->
        <div class="field-group">
          <label class="field-label" for="student_name">
            <span class="field-num mono">02</span>
            Student Name
            <span class="required">*</span>
          </label>
          <input type="text" id="student_name" name="student_name" placeholder="Enter your full name" required autocomplete="off"/>
        </div>

        <!-- Student ID -->
        <div class="field-group">
          <label class="field-label" for="student_id">
            <span class="field-num mono">03</span>
            Student ID / Roll Number
            <span class="required">*</span>
          </label>
          <input type="text" id="student_id" name="student_id" placeholder="e.g. GE2024001" required autocomplete="off"/>
        </div>

        <!-- Rating -->
        <div class="field-group">
          <label class="field-label">
            <span class="field-num mono">04</span>
            Event Rating
            <span class="required">*</span>
          </label>
          <div class="rating-block">
            <div class="stars" id="starRating">
              <?php for ($i = 1; $i <= 5; $i++): ?>
              <button type="button" class="star-btn" data-value="<?= $i ?>" aria-label="Rate <?= $i ?> star<?= $i > 1 ? 's' : '' ?>">
                <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
                  <path d="M18 4l3.6 7.3 8 1.2-5.8 5.6 1.4 8-7.2-3.8-7.2 3.8 1.4-8-5.8-5.6 8-1.2z" stroke="#e63946" stroke-width="1.5" stroke-linejoin="round"/>
                </svg>
              </button>
              <?php endfor; ?>
            </div>
            <div class="rating-label" id="ratingLabel"><span class="mono">Select a rating</span></div>
          </div>
          <input type="hidden" id="rating" name="rating" value="" required/>
        </div>

        <!-- Submit -->
        <div class="form-footer">
          <button type="submit" class="submit-btn" id="submitBtn">
            <span class="btn-text">Submit Feedback</span>
            <span class="btn-icon">
              <svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M3 9h12M10 4l5 5-5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <div class="btn-loader" id="btnLoader" style="display:none;">
              <div class="spinner"></div>
            </div>
          </button>
          <p class="form-note mono">// All fields marked * are required</p>
        </div>

      </form>
    </div>

    <!-- Side decoration -->
    <div class="form-side-deco">
      <div class="deco-line"></div>
      <div class="deco-label mono">GW_ELEC_2025</div>
      <div class="deco-circuit">
        <svg width="120" height="200" viewBox="0 0 120 200" fill="none" opacity="0.15">
          <circle cx="60" cy="40" r="8" stroke="#e63946" stroke-width="1.5"/>
          <line x1="60" y1="48" x2="60" y2="80" stroke="#e63946" stroke-width="1"/>
          <line x1="60" y1="80" x2="20" y2="80" stroke="#e63946" stroke-width="1"/>
          <line x1="60" y1="80" x2="100" y2="80" stroke="#e63946" stroke-width="1"/>
          <circle cx="20" cy="80" r="4" stroke="#e63946" stroke-width="1"/>
          <circle cx="100" cy="80" r="4" stroke="#e63946" stroke-width="1"/>
          <line x1="20" y1="84" x2="20" y2="130" stroke="#e63946" stroke-width="1"/>
          <line x1="100" y1="84" x2="100" y2="130" stroke="#e63946" stroke-width="1"/>
          <rect x="10" y="130" width="20" height="10" rx="2" stroke="#e63946" stroke-width="1"/>
          <rect x="90" y="130" width="20" height="10" rx="2" stroke="#e63946" stroke-width="1"/>
          <line x1="20" y1="140" x2="20" y2="170" stroke="#e63946" stroke-width="1"/>
          <line x1="100" y1="140" x2="100" y2="170" stroke="#e63946" stroke-width="1"/>
          <line x1="20" y1="170" x2="100" y2="170" stroke="#e63946" stroke-width="1"/>
          <circle cx="60" cy="170" r="6" stroke="#e63946" stroke-width="1.5"/>
        </svg>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="footer-inner">
      <span class="mono">© 2025 GATEWAY ELECTRONICS</span>
      <span class="footer-sep">|</span>
      <span class="mono">STUDENT FEEDBACK PORTAL v1.0</span>
      <span class="footer-sep">|</span>
      <a href="admin/login.php" class="footer-admin-link mono">ADMIN ACCESS</a>
    </div>
  </footer>

  <script src="assets/js/form.js"></script>
</body>
</html>
