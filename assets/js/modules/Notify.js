/**
 * Unified Notification System - Simple & Effective
 * Replaces complex AlertManager with 5 simple methods
 */
class Notify {
  constructor() {
    this.container = null;
    this.init();
  }

  init() {
    this.createContainer();
    this.bindEvents();
  }

  createContainer() {
    if (!document.getElementById("notify-container")) {
      this.container = document.createElement("div");
      this.container.id = "notify-container";
      this.container.className = "notify-container";
      this.container.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        max-width: 400px;
        pointer-events: none;
      `;
      document.body.appendChild(this.container);
    } else {
      this.container = document.getElementById("notify-container");
    }
  }

  bindEvents() {
    // Auto-dismiss after duration
    document.addEventListener("click", (e) => {
      if (e.target.classList.contains("notify__close")) {
        this.dismiss(e.target.closest(".notify"));
      }
    });

    // Auto-dismiss for PHP-rendered flash messages
    this.initFlashMessageAutoDismiss();
  }

  /**
   * Initialize auto-dismiss for PHP-rendered flash messages
   */
  initFlashMessageAutoDismiss() {
    // Wait for DOM to be ready
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", () => {
        this.setupFlashMessageAutoDismiss();
      });
    } else {
      this.setupFlashMessageAutoDismiss();
    }
  }

  /**
   * Setup auto-dismiss for flash messages
   */
  setupFlashMessageAutoDismiss() {
    const flashMessages = document.querySelectorAll(".notify-flash");

    flashMessages.forEach((notify) => {
      // Move flash message to fixed container
      this.container.appendChild(notify);

      // Trigger smooth transition
      setTimeout(() => {
        notify.style.opacity = "1";
        notify.style.transform = "translateX(0)";
      }, 50);

      // Setup auto-dismiss
      const duration = parseInt(notify.getAttribute("data-auto-dismiss"));

      if (duration > 0) {
        setTimeout(() => {
          this.dismiss(notify);
        }, duration);
      }
    });
  }

  /**
   * Show notification
   */
  show(type, message, duration = 5000) {
    const notify = this.createNotify(type, message);
    this.container.appendChild(notify);

    // Auto-dismiss
    if (duration > 0) {
      setTimeout(() => {
        this.dismiss(notify);
      }, duration);
    }

    return notify;
  }

  /**
   * Create notification element
   */
  createNotify(type, message) {
    const notify = document.createElement("div");
    notify.className = `notify notify--${type}`;

    const icons = {
      success: "fas fa-check-circle",
      error: "fas fa-exclamation-triangle",
      warning: "fas fa-exclamation-triangle",
      info: "fas fa-info-circle",
    };

    notify.innerHTML = `
      <div class="notify__content">
        <i class="notify__icon ${icons[type] || icons.info}"></i>
        <span class="notify__message">${this.escapeHtml(message)}</span>
      </div>
      <button type="button" class="notify__close" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    `;

    return notify;
  }

  /**
   * Quick methods
   */
  success(message, duration = 5000) {
    return this.show("success", message, duration);
  }

  error(message, duration = 7000) {
    return this.show("error", message, duration);
  }

  warning(message, duration = 6000) {
    return this.show("warning", message, duration);
  }

  info(message, duration = 5000) {
    return this.show("info", message, duration);
  }

  /**
   * Confirmation dialog
   */
  confirm(message) {
    return new Promise((resolve) => {
      const overlay = document.createElement("div");
      overlay.className = "notify-confirm-overlay";
      overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
      `;

      const box = document.createElement("div");
      box.className = "notify-confirm-box";
      box.style.cssText = `
        background: white;
        border-radius: 8px;
        padding: 24px;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      `;

      box.innerHTML = `
        <div class="notify-confirm-header" style="margin-bottom: 16px;">
          <i class="fas fa-question-circle" style="color: #f59e0b; margin-right: 8px;"></i>
          <strong>Konfirmasi</strong>
        </div>
        <div class="notify-confirm-message" style="margin-bottom: 24px; color: #374151;">
          ${this.escapeHtml(message)}
        </div>
        <div class="notify-confirm-actions" style="display: flex; gap: 12px; justify-content: flex-end;">
          <button class="btn btn-secondary" data-action="cancel" style="padding: 8px 16px; border: 1px solid #d1d5db; background: #f9fafb; border-radius: 4px; cursor: pointer;">
            Batal
          </button>
          <button class="btn btn-primary" data-action="confirm" style="padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Ya, Lanjutkan
          </button>
        </div>
      `;

      overlay.appendChild(box);
      document.body.appendChild(overlay);

      // Handle clicks
      box.addEventListener("click", (e) => {
        if (e.target.dataset.action === "confirm") {
          document.body.removeChild(overlay);
          resolve(true);
        } else if (e.target.dataset.action === "cancel") {
          document.body.removeChild(overlay);
          resolve(false);
        }
      });

      overlay.addEventListener("click", (e) => {
        if (e.target === overlay) {
          document.body.removeChild(overlay);
          resolve(false);
        }
      });
    });
  }

  /**
   * Dismiss notification
   */
  dismiss(notify) {
    if (notify && notify.parentNode) {
      notify.classList.add("notify--dismissing");
      setTimeout(() => {
        if (notify.parentNode) {
          notify.parentNode.removeChild(notify);
        }
      }, 300);
    }
  }

  /**
   * Clear all notifications
   */
  clear() {
    const notifications = this.container.querySelectorAll(".notify");
    notifications.forEach((notify) => this.dismiss(notify));
  }

  /**
   * Escape HTML
   */
  escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
  }
}

// Create global instance
window.Notify = new Notify();

// Legacy support
window.showToast = function (type, message, options = {}) {
  return window.Notify.show(type, message, options.duration || 3000);
};

// Override native functions
window.alert = function (message) {
  return window.Notify.error(message);
};

window.confirm = function (message) {
  return window.Notify.confirm(message);
};

// Export for module systems
if (typeof module !== "undefined" && module.exports) {
  module.exports = Notify;
}
