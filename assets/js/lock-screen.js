/**
 * Lock Screen Auto-Lock Functionality
 * Automatically locks the screen after user inactivity
 */

class LockScreenManager {
  constructor() {
    this.idleTime = 0;
    this.idleInterval = null;
    this.lockTimeout = 15 * 60 * 1000; // 15 minutes in milliseconds
    this.warningTime = 14 * 60 * 1000; // 14 minutes (1 minute warning)
    this.warningShown = false;

    this.init();
  }

  init() {
    // Only run on pages that are not lock screen or login
    if (this.isLockScreen() || this.isLoginPage()) {
      return;
    }

    this.startIdleTimer();
    this.bindEvents();
    this.checkRememberMe();
  }

  isLockScreen() {
    return window.location.pathname.includes("/lock-screen");
  }

  isLoginPage() {
    return window.location.pathname.includes("/login");
  }

  startIdleTimer() {
    this.idleInterval = setInterval(() => {
      this.idleTime += 1000; // Increment by 1 second

      // Show warning 1 minute before auto-lock
      if (this.idleTime >= this.warningTime && !this.warningShown) {
        this.showWarning();
      }

      // Auto-lock after timeout
      if (this.idleTime >= this.lockTimeout) {
        this.lockScreen();
      }
    }, 1000);
  }

  bindEvents() {
    // Reset idle time on user activity
    const events = [
      "mousedown",
      "mousemove",
      "keypress",
      "scroll",
      "touchstart",
      "click",
      "keydown",
    ];

    events.forEach((event) => {
      document.addEventListener(
        event,
        () => {
          this.resetIdleTime();
        },
        true
      );
    });

    // Handle visibility change (tab switching)
    document.addEventListener("visibilitychange", () => {
      if (document.hidden) {
        // User switched tabs or minimized window
        // You might want to pause the timer or continue counting
      } else {
        // User came back to the tab
        this.resetIdleTime();
      }
    });

    // Handle beforeunload (user is leaving the page)
    window.addEventListener("beforeunload", () => {
      this.cleanup();
    });
  }

  resetIdleTime() {
    this.idleTime = 0;
    this.warningShown = false;
    this.hideWarning();
  }

  showWarning() {
    this.warningShown = true;

    // Create warning modal
    const warningModal = document.createElement("div");
    warningModal.id = "lock-warning-modal";
    warningModal.className = "lock-warning-overlay";
    warningModal.innerHTML = `
            <div class="lock-warning-modal">
                <div class="lock-warning-content">
                    <div class="lock-warning-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>Session Timeout Warning</h3>
                    <p>Your session will be locked in <span id="countdown">60</span> seconds due to inactivity.</p>
                    <div class="lock-warning-actions">
                        <button type="button" class="btn btn-primary" onclick="lockScreenManager.stayActive()">
                            <i class="fas fa-hand-paper"></i> Stay Active
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="lockScreenManager.lockScreen()">
                            <i class="fas fa-lock"></i> Lock Now
                        </button>
                    </div>
                </div>
            </div>
        `;

    // Add styles
    const style = document.createElement("style");
    style.textContent = `
            .lock-warning-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.8);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                animation: fadeIn 0.3s ease;
            }
            
            .lock-warning-modal {
                background: white;
                border-radius: 1rem;
                padding: 2rem;
                max-width: 400px;
                width: 90%;
                text-align: center;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
                animation: slideIn 0.3s ease;
            }
            
            .lock-warning-icon {
                font-size: 3rem;
                color: #f59e0b;
                margin-bottom: 1rem;
            }
            
            .lock-warning-content h3 {
                color: #2d3748;
                margin-bottom: 1rem;
                font-size: 1.25rem;
                font-weight: 600;
            }
            
            .lock-warning-content p {
                color: #718096;
                margin-bottom: 1.5rem;
                line-height: 1.5;
            }
            
            #countdown {
                font-weight: 700;
                color: #f59e0b;
                font-size: 1.1em;
            }
            
            .lock-warning-actions {
                display: flex;
                gap: 1rem;
                justify-content: center;
            }
            
            .lock-warning-actions .btn {
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                font-weight: 600;
                transition: all 0.2s ease;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            @keyframes slideIn {
                from { transform: translateY(-20px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
        `;

    document.head.appendChild(style);
    document.body.appendChild(warningModal);

    // Start countdown
    let countdown = 60;
    const countdownElement = document.getElementById("countdown");

    const countdownInterval = setInterval(() => {
      countdown--;
      if (countdownElement) {
        countdownElement.textContent = countdown;
      }

      if (countdown <= 0) {
        clearInterval(countdownInterval);
        this.lockScreen();
      }
    }, 1000);
  }

  hideWarning() {
    const warningModal = document.getElementById("lock-warning-modal");
    if (warningModal) {
      warningModal.remove();
    }
  }

  stayActive() {
    this.resetIdleTime();
    this.hideWarning();
  }

  lockScreen() {
    this.cleanup();
    window.location.href = window.appUrl + "/lock-screen";
  }

  checkRememberMe() {
    // Check if user has "remember me" cookie
    const rememberToken = this.getCookie("remember_token");
    if (rememberToken) {
      // Extend the timeout for remembered users
      this.lockTimeout = 60 * 60 * 1000; // 1 hour
      this.warningTime = 59 * 60 * 1000; // 59 minutes
    }
  }

  getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(";").shift();
    return null;
  }

  cleanup() {
    if (this.idleInterval) {
      clearInterval(this.idleInterval);
      this.idleInterval = null;
    }
    this.hideWarning();
  }
}

// Initialize lock screen manager when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  window.lockScreenManager = new LockScreenManager();
});

// Export for use in other scripts
if (typeof module !== "undefined" && module.exports) {
  module.exports = LockScreenManager;
}
