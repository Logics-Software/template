/**
 * Logis PHP APP Template - Main JavaScript
 */

// Global variables
window.Hando = {
  csrfToken: window.csrfToken || "",
  appUrl: window.appUrl || "",
  theme: getCookie("hando_theme") || "light",
};

// Utility functions
function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(";").shift();
  return null;
}

function setCookie(name, value, days = 365) {
  const expires = new Date(Date.now() + days * 864e5).toUTCString();
  document.cookie = `${name}=${value}; expires=${expires}; path=/`;
}

// Theme management
function initTheme() {
  const savedTheme = getCookie("hando_theme") || "light";
  applyTheme(savedTheme);

  // Initialize theme icon
  const themeIcon = document.getElementById("themeIcon");
  if (themeIcon) {
    if (savedTheme === "dark") {
      themeIcon.className = "fa-solid fa-moon";
    } else {
      themeIcon.className = "fa-solid fa-sun";
    }
  }
}

function applyTheme(theme) {
  document.documentElement.setAttribute("data-bs-theme", theme);
  window.Hando.theme = theme;
  setCookie("hando_theme", theme);
}

function toggleTheme(theme) {
  applyTheme(theme);

  // Update theme toggle icon
  const themeIcon = document.getElementById("themeIcon");
  if (themeIcon) {
    if (theme === "dark") {
      themeIcon.className = "fa-solid fa-moon";
    } else {
      themeIcon.className = "fa-solid fa-sun";
    }
  }
}

// AJAX helper
function ajaxRequest(url, options = {}) {
  const defaultOptions = {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-Token": window.Hando.csrfToken,
    },
  };

  const mergedOptions = { ...defaultOptions, ...options };

  return fetch(url, mergedOptions).then((response) => {
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return response.json();
  });
}

// Form validation
function validateForm(form) {
  const requiredFields = form.querySelectorAll("[required]");
  let isValid = true;

  requiredFields.forEach((field) => {
    if (!field.value.trim()) {
      field.classList.add("is-invalid");
      isValid = false;
    } else {
      field.classList.remove("is-invalid");
    }
  });

  return isValid;
}

// Show loading state
function showLoading(element, text = "Loading...") {
  const originalContent = element.innerHTML;
  element.innerHTML = `<i class="fa-solid fa-hourglass-half me-1"></i>${text}`;
  element.disabled = true;

  return function hideLoading() {
    element.innerHTML = originalContent;
    element.disabled = false;
  };
}

// Show alert
function showAlert(message, type = "info", duration = 5000) {
  const alertDiv = document.createElement("div");
  alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
  alertDiv.style.cssText =
    "top: 20px; right: 20px; z-index: 9999; min-width: 300px;";
  alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

  document.body.appendChild(alertDiv);

  if (duration > 0) {
    setTimeout(() => {
      if (alertDiv.parentNode) {
        alertDiv.remove();
      }
    }, duration);
  }
}

// Password toggle
function initPasswordToggle() {
  document.querySelectorAll('[data-toggle="password"]').forEach((toggle) => {
    toggle.addEventListener("click", function () {
      const target = document.querySelector(this.dataset.target);
      const icon = this.querySelector("i");

      if (target.type === "password") {
        target.type = "text";
        icon.className = "fa-solid fa-eye-slash";
      } else {
        target.type = "password";
        icon.className = "fa-solid fa-eye";
      }
    });
  });
}

// Data table enhancements
function initDataTable() {
  // Add search functionality
  const searchInput = document.querySelector("#search");
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      const searchTerm = this.value.toLowerCase();
      const tableRows = document.querySelectorAll("#usersTable tbody tr");

      tableRows.forEach((row) => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? "" : "none";
      });
    });
  }

  // Add sort functionality
  document.querySelectorAll("th[data-sort]").forEach((header) => {
    header.style.cursor = "pointer";
    header.addEventListener("click", function () {
      const column = this.dataset.sort;
      const table = this.closest("table");
      const tbody = table.querySelector("tbody");
      const rows = Array.from(tbody.querySelectorAll("tr"));

      const isAsc = this.classList.contains("sort-asc");

      // Remove sort classes from all headers
      table.querySelectorAll("th").forEach((th) => {
        th.classList.remove("sort-asc", "sort-desc");
      });

      // Add sort class to current header
      this.classList.add(isAsc ? "sort-desc" : "sort-asc");

      // Sort rows
      rows.sort((a, b) => {
        const aVal = a
          .querySelector(`td:nth-child(${this.cellIndex + 1})`)
          .textContent.trim();
        const bVal = b
          .querySelector(`td:nth-child(${this.cellIndex + 1})`)
          .textContent.trim();

        if (isAsc) {
          return bVal.localeCompare(aVal);
        } else {
          return aVal.localeCompare(bVal);
        }
      });

      // Re-append sorted rows
      rows.forEach((row) => tbody.appendChild(row));
    });
  });
}

// Chart initialization
function initCharts() {
  // Sales chart
  const salesCtx = document.getElementById("salesChart");
  if (salesCtx) {
    new Chart(salesCtx, {
      type: "line",
      data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
        datasets: [
          {
            label: "Sales",
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: "rgba(78, 115, 223, 0.1)",
            borderColor: "rgba(78, 115, 223, 1)",
            borderWidth: 2,
            fill: true,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  }

  // Pipeline chart
  const pipelineCtx = document.getElementById("pipelineChart");
  if (pipelineCtx) {
    new Chart(pipelineCtx, {
      type: "doughnut",
      data: {
        labels: ["Won", "Discovery", "Undiscovery"],
        datasets: [
          {
            data: [12.48, 5.23, 15.58],
            backgroundColor: ["#4e73df", "#1cc88a", "#36b9cc"],
            hoverBackgroundColor: ["#2e59d9", "#17a673", "#2c9faf"],
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
      },
    });
  }
}

// Form submission with AJAX
function initAjaxForms() {
  document.querySelectorAll("form[data-ajax]").forEach((form) => {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(this);
      const submitBtn = this.querySelector('button[type="submit"]');
      const hideLoading = showLoading(submitBtn, "Processing...");

      fetch(this.action, {
        method: this.method,
        body: formData,
        headers: {
          "X-CSRF-Token": window.Hando.csrfToken,
        },
      })
        .then((response) => response.json())
        .then((data) => {
          hideLoading();

          if (data.success) {
            showAlert(
              data.message || "Operation completed successfully",
              "success"
            );

            if (data.redirect) {
              setTimeout(() => {
                window.location.href = data.redirect;
              }, 1000);
            }
          } else {
            showAlert(data.error || "An error occurred", "danger");
          }
        })
        .catch((error) => {
          hideLoading();
          showAlert("An error occurred while processing the request", "danger");
          console.error("Error:", error);
        });
    });
  });
}

// Auto-save functionality
function initAutoSave() {
  const autoSaveForms = document.querySelectorAll("form[data-autosave]");

  autoSaveForms.forEach((form) => {
    const inputs = form.querySelectorAll("input, textarea, select");
    let saveTimeout;

    inputs.forEach((input) => {
      input.addEventListener("input", function () {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(() => {
          saveFormData(form);
        }, 2000);
      });
    });
  });
}

function saveFormData(form) {
  const formData = new FormData(form);
  const saveIndicator = form.querySelector(".save-indicator");

  if (saveIndicator) {
    saveIndicator.textContent = "Saving...";
    saveIndicator.className = "save-indicator text-warning";
  }

  fetch(form.action, {
    method: "POST",
    body: formData,
    headers: {
      "X-CSRF-Token": window.Hando.csrfToken,
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (saveIndicator) {
        if (data.success) {
          saveIndicator.textContent = "Saved";
          saveIndicator.className = "save-indicator text-success";
        } else {
          saveIndicator.textContent = "Save failed";
          saveIndicator.className = "save-indicator text-danger";
        }
      }
    })
    .catch((error) => {
      if (saveIndicator) {
        saveIndicator.textContent = "Save failed";
        saveIndicator.className = "save-indicator text-danger";
      }
    });
}

// Sidebar toggle functionality
function initSidebarToggle() {
  const sidebarToggle = document.getElementById("sidebarToggle");
  const sidebar = document.querySelector(".sidebar");
  const mainContent = document.querySelector(".main-content");
  const topHeader = document.querySelector(".top-header");

  if (sidebarToggle && sidebar && mainContent && topHeader) {
    sidebarToggle.addEventListener("click", function () {
      // Toggle sidebar
      sidebar.classList.toggle("collapsed");

      // Toggle main content margin
      mainContent.classList.toggle("sidebar-collapsed");

      // Toggle header position
      topHeader.classList.toggle("sidebar-collapsed");

      // For mobile, also toggle show class
      if (window.innerWidth <= 768) {
        sidebar.classList.toggle("show");
      }

      // Save sidebar state
      const isCollapsed = sidebar.classList.contains("collapsed");
      setCookie("sidebar_collapsed", isCollapsed ? "true" : "false");
    });

    // Restore sidebar state
    const isCollapsed = getCookie("sidebar_collapsed") === "true";
    if (isCollapsed) {
      sidebar.classList.add("collapsed");
      mainContent.classList.add("sidebar-collapsed");
      topHeader.classList.add("sidebar-collapsed");
    }

    // Handle window resize
    window.addEventListener("resize", function () {
      if (window.innerWidth > 768) {
        sidebar.classList.remove("show");
      }
    });
  }
}

// Fullscreen toggle functionality
function initFullscreenToggle() {
  const fullscreenToggle = document.getElementById("fullscreenToggle");

  if (fullscreenToggle) {
    fullscreenToggle.addEventListener("click", function () {
      if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch((err) => {
          console.log(`Error attempting to enable fullscreen: ${err.message}`);
        });
      } else {
        document.exitFullscreen();
      }
    });

    // Update icon based on fullscreen state
    document.addEventListener("fullscreenchange", function () {
      const icon = fullscreenToggle.querySelector("i");
      if (document.fullscreenElement) {
        icon.className = "fa-solid fa-compress";
        fullscreenToggle.setAttribute("title", "Exit Full Screen");
      } else {
        icon.className = "fa-solid fa-expand";
        fullscreenToggle.setAttribute("title", "Full Screen");
      }
    });
  }
}

// F12 Lock Screen Detection
function initF12LockScreen() {
  // Configuration
  const config = {
    enabled: true, // Set to false to disable F12 lock screen
    warningDelay: 1000, // Delay before locking screen (ms)
    detectionMethods: {
      f12Key: true,
      windowSize: true,
      console: true,
      rightClick: true,
      shortcuts: true,
    },
  };

  // Check if feature is enabled
  if (!config.enabled) return;

  // Only activate if user is logged in (check for session data or specific elements)
  const isLoggedIn =
    document.querySelector(".user-info") ||
    document.querySelector(".sidebar") ||
    window.location.pathname !== "/login";

  if (!isLoggedIn) return;

  // Track if F12 was pressed
  let f12Pressed = false;
  let lockScreenTriggered = false;

  // Listen for F12 key press
  if (config.detectionMethods.f12Key) {
    document.addEventListener("keydown", function (event) {
      if (event.key === "F12") {
        event.preventDefault();
        f12Pressed = true;

        // Redirect to lock screen after short delay
        setTimeout(() => {
          if (!lockScreenTriggered) {
            window.location.href = window.Hando.appUrl + "/lock-screen";
          }
        }, config.warningDelay);
      }
    });
  }

  // Listen for developer tools detection (multiple methods)
  let devtools = {
    open: false,
    orientation: null,
  };

  const threshold = 160;

  // Method 1: Window size detection
  if (config.detectionMethods.windowSize) {
    setInterval(() => {
      if (
        window.outerHeight - window.innerHeight > threshold ||
        window.outerWidth - window.innerWidth > threshold
      ) {
        if (!devtools.open) {
          devtools.open = true;
          showAlert(
            "Developer tools detected! Redirecting to lock screen...",
            "warning",
            2000
          );
          setTimeout(() => {
            window.location.href = window.Hando.appUrl + "/lock-screen";
          }, config.warningDelay);
        }
      } else {
        devtools.open = false;
      }
    }, 500);
  }

  // Method 2: Console detection
  if (config.detectionMethods.console) {
    let devtoolsConsole = false;
    setInterval(() => {
      const start = performance.now();
      debugger;
      const end = performance.now();
      if (end - start > 100) {
        if (!devtoolsConsole) {
          devtoolsConsole = true;

          setTimeout(() => {
            window.location.href = window.Hando.appUrl + "/lock-screen";
          }, config.warningDelay);
        }
      } else {
        devtoolsConsole = false;
      }
    }, 1000);
  }

  // Method 3: Right-click context menu detection
  if (config.detectionMethods.rightClick) {
    document.addEventListener("contextmenu", function (event) {
      event.preventDefault();
      showAlert("Right-click disabled for security", "warning", 2000);
    });
  }

  // Method 4: Keyboard shortcuts detection
  if (config.detectionMethods.shortcuts) {
    document.addEventListener("keydown", function (event) {
      // Detect common developer tools shortcuts
      const shortcuts = [
        { key: "F12", ctrl: false, shift: false, alt: false },
        { key: "I", ctrl: true, shift: false, alt: false },
        { key: "J", ctrl: true, shift: false, alt: false },
        { key: "C", ctrl: true, shift: false, alt: false },
        { key: "U", ctrl: true, shift: false, alt: false },
        { key: "K", ctrl: true, shift: false, alt: false },
      ];

      shortcuts.forEach((shortcut) => {
        if (
          event.key === shortcut.key &&
          event.ctrlKey === shortcut.ctrl &&
          event.shiftKey === shortcut.shift &&
          event.altKey === shortcut.alt
        ) {
          event.preventDefault();
          setTimeout(() => {
            window.location.href = window.Hando.appUrl + "/lock-screen";
          }, config.warningDelay);
        }
      });
    });
  }

  // Function to redirect to lock screen
  function redirectToLockScreen() {
    if (lockScreenTriggered) return;
    lockScreenTriggered = true;
    window.location.href = window.Hando.appUrl + "/lock-screen";
  }
}

// Initialize everything when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  // Initialize theme
  initTheme();

  // Initialize sidebar toggle
  initSidebarToggle();

  // Initialize fullscreen toggle
  initFullscreenToggle();

  // Initialize password toggles
  initPasswordToggle();

  // Initialize data table enhancements
  initDataTable();

  // Initialize charts
  initCharts();

  // Initialize AJAX forms
  initAjaxForms();

  // Initialize auto-save
  initAutoSave();

  // Initialize F12 lock screen detection
  initF12LockScreen();

  // Theme toggle event listener
  const themeToggle = document.getElementById("themeToggle");
  if (themeToggle) {
    themeToggle.addEventListener("click", function (e) {
      e.preventDefault();
      const currentTheme = window.Hando.theme;
      const newTheme = currentTheme === "light" ? "dark" : "light";
      toggleTheme(newTheme);
    });
  }

  // Auto-hide alerts
  document.querySelectorAll(".alert[data-auto-hide]").forEach((alert) => {
    const duration = parseInt(alert.dataset.autoHide) || 5000;
    setTimeout(() => {
      if (alert.parentNode) {
        alert.remove();
      }
    }, duration);
  });

  // Tooltips removed

  // Initialize popovers
  const popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]')
  );
  popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });
});

// Export functions for global use
window.Hando = {
  ...window.Hando,
  ajaxRequest,
  showAlert,
  showLoading,
  validateForm,
  toggleTheme,
  applyTheme,
};
