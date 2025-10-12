/**
 * Application Configuration - JavaScript
 * Centralized settings for the entire application
 */

window.AppConfig = {
  /**
   * Redirect delay after form submission (in milliseconds)
   * This allows users to see notification messages before redirect
   */
  REDIRECT_DELAY: 1000, // 2 seconds

  /**
   * Notification auto-dismiss duration (in milliseconds)
   * Used by Notify.js - Change here to affect all notifications
   */
  NOTIFICATION_DURATION: {
    success: 5000, // 5 seconds
    error: 7000, // 7 seconds
    warning: 6000, // 6 seconds
    info: 5000, // 5 seconds
  },

  /**
   * Session configuration (matches server-side config)
   */
  SESSION: {
    CHECK_INTERVAL: 60000, // Check session every 60 seconds
    WARNING_TIME: 300, // 5 minutes warning before expiry
    ACTIVITY_UPDATE_INTERVAL: 60000, // Update activity every 60 seconds max
  },

  /**
   * AJAX request defaults
   */
  AJAX: {
    TIMEOUT: 30000, // 30 seconds timeout
    RETRY_ATTEMPTS: 3,
  },

  /**
   * File upload configuration
   */
  UPLOAD: {
    MAX_FILE_SIZE: 5242880, // 5MB in bytes
    ALLOWED_IMAGE_TYPES: [
      "image/jpeg",
      "image/jpg",
      "image/png",
      "image/gif",
      "image/webp",
    ],
    ALLOWED_DOCUMENT_TYPES: [
      "application/pdf",
      "application/msword",
      "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
    ],
  },

  /**
   * UI/UX settings
   */
  UI: {
    ANIMATION_DURATION: 300, // CSS animation duration
    DEBOUNCE_DELAY: 300, // Input debounce delay
    TOOLTIP_DELAY: 500, // Tooltip show delay
  },

  /**
   * Pagination
   */
  PAGINATION: {
    DEFAULT_PAGE_SIZE: 10,
    PAGE_SIZE_OPTIONS: [10, 25, 50, 100],
  },
};

/**
 * Helper function to get redirect delay
 * @returns {number} Delay in milliseconds
 */
window.getRedirectDelay = function () {
  return window.AppConfig.REDIRECT_DELAY;
};

/**
 * Helper function to perform delayed redirect
 * @param {string} url - Target URL
 * @param {number} delay - Optional custom delay (defaults to AppConfig.REDIRECT_DELAY)
 */
window.delayedRedirect = function (url, delay = null) {
  const redirectDelay =
    delay !== null ? delay : window.AppConfig.REDIRECT_DELAY;

  setTimeout(() => {
    window.location.href = url;
  }, redirectDelay);
};

/**
 * Helper function to perform delayed reload
 * @param {number} delay - Optional custom delay (defaults to AppConfig.REDIRECT_DELAY)
 */
window.delayedReload = function (delay = null) {
  const reloadDelay = delay !== null ? delay : window.AppConfig.REDIRECT_DELAY;

  setTimeout(() => {
    window.location.reload();
  }, reloadDelay);
};

// Log configuration on load (only in development)
if (window.APP_DEBUG || false) {
  console.log("AppConfig loaded:", window.AppConfig);
}
