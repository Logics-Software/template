/**
 * CSRF Helper - UNIVERSAL SOLUTION
 * Mengatasi masalah CSRF token secara PERMANEN
 *
 * JANGAN PERNAH generate token baru di view!
 * SELALU gunakan window.csrfToken yang sudah di-set di layout!
 */

class CsrfHelper {
  constructor() {
    // Pastikan token tersedia - SILENT CHECK (no console spam)
    if (!window.csrfToken) {
      // Token akan di-set dari layout, tidak perlu panic
      window.csrfToken = "";
    }
  }

  /**
   * Get CSRF token
   * @returns {string} CSRF token
   */
  getToken() {
    return window.csrfToken || "";
  }

  /**
   * Get CSRF headers for fetch requests
   * @returns {Object} Headers object dengan CSRF token
   */
  getHeaders() {
    return {
      "Content-Type": "application/json",
      "X-Requested-With": "XMLHttpRequest",
      "X-CSRF-Token": this.getToken(),
    };
  }

  /**
   * Create fetch options dengan CSRF token
   * @param {string} method - HTTP method (POST, PUT, DELETE, etc)
   * @param {Object} data - Data to send
   * @returns {Object} Fetch options
   */
  createFetchOptions(method, data = null) {
    const options = {
      method: method.toUpperCase(),
      headers: this.getHeaders(),
      credentials: "same-origin",
    };

    if (data) {
      options.body = JSON.stringify({
        ...data,
        _token: this.getToken(), // Always include token in body too
      });
    }

    return options;
  }

  /**
   * Wrapper untuk fetch POST dengan CSRF
   * @param {string} url - URL endpoint
   * @param {Object} data - Data to send
   * @returns {Promise} Fetch promise
   */
  post(url, data) {
    return fetch(url, this.createFetchOptions("POST", data));
  }

  /**
   * Wrapper untuk fetch PUT dengan CSRF
   * @param {string} url - URL endpoint
   * @param {Object} data - Data to send
   * @returns {Promise} Fetch promise
   */
  put(url, data) {
    return fetch(url, this.createFetchOptions("PUT", data));
  }

  /**
   * Wrapper untuk fetch DELETE dengan CSRF
   * @param {string} url - URL endpoint
   * @param {Object} data - Data to send (optional)
   * @returns {Promise} Fetch promise
   */
  delete(url, data = null) {
    return fetch(url, this.createFetchOptions("DELETE", data));
  }

  /**
   * Add CSRF token to FormData
   * @param {FormData} formData - FormData object
   * @returns {FormData} FormData dengan CSRF token
   */
  addToFormData(formData) {
    formData.append("_token", this.getToken());
    return formData;
  }

  /**
   * Create hidden input untuk form
   * @returns {HTMLInputElement} Hidden input element
   */
  createHiddenInput() {
    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "_token";
    input.value = this.getToken();
    return input;
  }
}

// Create global instance
window.csrf = new CsrfHelper();

// Export untuk compatibility
if (typeof module !== "undefined" && module.exports) {
  module.exports = CsrfHelper;
}
