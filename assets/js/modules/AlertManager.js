/**
 * Alert Manager - Centralized alert and notification system
 * Handles all types of alerts, toasts, and messages
 */
class AlertManager {
  constructor() {
    this.container = null;
    this.toastContainer = null;
    this.init();
  }

  init() {
    this.createContainers();
    this.bindEvents();
  }

  createContainers() {
    // Create main alert container
    if (!document.getElementById("alert-container")) {
      this.container = document.createElement("div");
      this.container.id = "alert-container";
      this.container.className = "alert-container";
      this.container.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        max-width: 400px;
      `;
      document.body.appendChild(this.container);
    }

    // Create toast container
    if (!document.getElementById("toast-container")) {
      this.toastContainer = document.createElement("div");
      this.toastContainer.id = "toast-container";
      this.toastContainer.className = "toast-container";
      this.toastContainer.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        max-width: 400px;
      `;
      document.body.appendChild(this.toastContainer);
    }
  }

  bindEvents() {
    // Auto-dismiss alerts after 5 seconds
    document.addEventListener("click", (e) => {
      if (e.target.classList.contains("alert__close")) {
        this.dismissAlert(e.target.closest(".alert"));
      }
    });
  }

  /**
   * Show alert with BEM methodology
   */
  showAlert(type, message, options = {}) {
    const alert = this.createAlert(type, message, options);

    if (options.container === "toast") {
      this.toastContainer.appendChild(alert);
    } else {
      this.container.appendChild(alert);
    }

    // Auto-dismiss after delay
    if (options.autoDismiss !== false) {
      setTimeout(() => {
        this.dismissAlert(alert);
      }, options.duration || 5000);
    }

    return alert;
  }

  /**
   * Create alert element with BEM structure
   */
  createAlert(type, message, options = {}) {
    const alert = document.createElement("div");
    alert.className = `alert alert--${type} alert--dismissible alert--fade show`;

    const icons = {
      success: "fas fa-check-circle",
      danger: "fas fa-exclamation-triangle",
      warning: "fas fa-exclamation-triangle",
      info: "fas fa-info-circle",
    };

    alert.innerHTML = `
      <i class="alert__icon ${icons[type] || icons.info}"></i>
      <span class="alert__content">${this.escapeHtml(message)}</span>
      <button type="button" class="alert__close" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    `;

    return alert;
  }

  /**
   * Show toast notification
   */
  showToast(type, message, options = {}) {
    return this.showAlert(type, message, {
      ...options,
      container: "toast",
      duration: options.duration || 3000,
    });
  }

  /**
   * Show success message
   */
  success(message, options = {}) {
    return this.showAlert("success", message, options);
  }

  /**
   * Show error message
   */
  error(message, options = {}) {
    return this.showAlert("danger", message, options);
  }

  /**
   * Show warning message
   */
  warning(message, options = {}) {
    return this.showAlert("warning", message, options);
  }

  /**
   * Show info message
   */
  info(message, options = {}) {
    return this.showAlert("info", message, options);
  }

  /**
   * Dismiss specific alert
   */
  dismissAlert(alert) {
    if (alert && alert.parentNode) {
      alert.classList.remove("show");
      setTimeout(() => {
        if (alert.parentNode) {
          alert.parentNode.removeChild(alert);
        }
      }, 150);
    }
  }

  /**
   * Clear all alerts
   */
  clearAll() {
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach((alert) => this.dismissAlert(alert));
  }

  /**
   * Show message alert in dropdown
   */
  showMessageAlert(type, message, container) {
    const alert = document.createElement("div");
    alert.className = `message-alert message-alert--${type}`;
    alert.textContent = message;

    if (container) {
      container.appendChild(alert);

      // Auto-remove after 3 seconds
      setTimeout(() => {
        if (alert.parentNode) {
          alert.parentNode.removeChild(alert);
        }
      }, 3000);
    }

    return alert;
  }

  /**
   * Show confirmation dialog
   */
  confirm(message, options = {}) {
    return new Promise((resolve) => {
      const confirmDialog = document.createElement("div");
      confirmDialog.className = "alert-confirm-overlay";
      confirmDialog.style.cssText = `
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

      const confirmBox = document.createElement("div");
      confirmBox.className = "alert-confirm-box";
      confirmBox.style.cssText = `
        background: white;
        border-radius: 8px;
        padding: 24px;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      `;

      confirmBox.innerHTML = `
        <div class="alert-confirm-header" style="margin-bottom: 16px;">
          <i class="fas fa-question-circle" style="color: #f59e0b; margin-right: 8px;"></i>
          <strong>Konfirmasi</strong>
        </div>
        <div class="alert-confirm-message" style="margin-bottom: 24px; color: #374151;">
          ${this.escapeHtml(message)}
        </div>
        <div class="alert-confirm-actions" style="display: flex; gap: 12px; justify-content: flex-end;">
          <button class="btn btn-secondary" data-action="cancel" style="padding: 8px 16px; border: 1px solid #d1d5db; background: #f9fafb; border-radius: 4px; cursor: pointer;">
            Batal
          </button>
          <button class="btn btn-primary" data-action="confirm" style="padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer;">
            Ya, Lanjutkan
          </button>
        </div>
      `;

      confirmDialog.appendChild(confirmBox);
      document.body.appendChild(confirmDialog);

      // Handle button clicks
      confirmBox.addEventListener("click", (e) => {
        if (e.target.dataset.action === "confirm") {
          document.body.removeChild(confirmDialog);
          resolve(true);
        } else if (e.target.dataset.action === "cancel") {
          document.body.removeChild(confirmDialog);
          resolve(false);
        }
      });

      // Handle overlay click
      confirmDialog.addEventListener("click", (e) => {
        if (e.target === confirmDialog) {
          document.body.removeChild(confirmDialog);
          resolve(false);
        }
      });
    });
  }

  /**
   * Show delete confirmation
   */
  confirmDelete(itemName = "item", options = {}) {
    return this.confirm(
      `Apakah Anda yakin ingin menghapus ${itemName}? Tindakan ini tidak dapat dibatalkan.`,
      options
    );
  }

  /**
   * Show form submission confirmation
   */
  confirmSubmit(formName = "form", options = {}) {
    return this.confirm(
      `Apakah Anda yakin ingin mengirim ${formName}?`,
      options
    );
  }

  /**
   * Handle API response messages
   */
  handleApiResponse(response, options = {}) {
    if (response.success) {
      this.success(
        response.message || "Operation completed successfully",
        options
      );
    } else {
      this.error(
        response.error || response.message || "An error occurred",
        options
      );
    }
  }

  /**
   * Escape HTML to prevent XSS
   */
  escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
  }
}

// Create global instance
window.AlertManager = new AlertManager();

// Legacy support for existing showToast function
window.showToast = function (type, message, options = {}) {
  return window.AlertManager.showToast(type, message, options);
};

// Global alert replacement functions
window.alert = function (message) {
  return window.AlertManager.error(message);
};

window.confirm = function (message) {
  return window.AlertManager.confirm(message);
};

// Global confirmation functions
window.confirmDelete = function (itemName) {
  return window.AlertManager.confirmDelete(itemName);
};

window.confirmSubmit = function (formName) {
  return window.AlertManager.confirmSubmit(formName);
};

// Export for module systems
if (typeof module !== "undefined" && module.exports) {
  module.exports = AlertManager;
}
