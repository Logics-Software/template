// Hando Admin Template - Main JavaScript File
// Author: Your Name
// Version: 1.0.0

// Global Hando object
window.Hando = {
  theme: "light",
  sidebarCollapsed: false,
};

// Cookie management functions
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

// Initialize sidebar state from cookie
function initSidebarState() {
  const isCollapsed = getCookie("sidebar_collapsed") === "true";
  const sidebar = document.querySelector(".sidebar");
  const mainContent = document.querySelector(".main-content");
  const topHeader = document.querySelector(".top-header");

  if (isCollapsed && sidebar && mainContent && topHeader) {
    sidebar.classList.add("collapsed");
    mainContent.classList.add("sidebar-collapsed");
    topHeader.classList.add("sidebar-collapsed");
  }
}

// Sidebar toggle functionality
function initSidebarToggle() {
  const sidebarToggle = document.getElementById("sidebarToggle");
  const sidebar = document.querySelector(".sidebar");
  const mainContent = document.querySelector(".main-content");
  const topHeader = document.querySelector(".top-header");

  if (sidebarToggle && sidebar && mainContent && topHeader) {
    sidebarToggle.addEventListener("click", function (e) {
      e.preventDefault();
      const isCollapsed = sidebar.classList.contains("collapsed");

      if (isCollapsed) {
        // Show sidebar
        sidebar.classList.remove("collapsed");
        mainContent.classList.remove("sidebar-collapsed");
        topHeader.classList.remove("sidebar-collapsed");
      } else {
        // Hide sidebar
        sidebar.classList.add("collapsed");
        mainContent.classList.add("sidebar-collapsed");
        topHeader.classList.add("sidebar-collapsed");
      }

      // For mobile, also toggle show class
      if (window.innerWidth <= 768) {
        sidebar.classList.toggle("show");
      }

      // Save sidebar state
      const isCollapsedState = sidebar.classList.contains("collapsed");
      setCookie("sidebar_collapsed", isCollapsedState ? "true" : "false");
    });

    // Handle window resize
    window.addEventListener("resize", function () {
      if (window.innerWidth > 768) {
        sidebar.classList.remove("show");
      }
    });
  }
}

// Theme management functions
function initTheme() {
  const savedTheme = getCookie("hando_theme") || "light";

  applyTheme(savedTheme);
  updateThemeIcon(savedTheme);
}

function applyTheme(theme) {
  document.documentElement.setAttribute("data-bs-theme", theme);
  document.body.setAttribute("data-bs-theme", theme);
  window.Hando.theme = theme;
  setCookie("hando_theme", theme);

  // Force re-render
  document.documentElement.style.colorScheme = theme;

  // Update theme icon
  updateThemeIcon(theme);
}

function updateThemeIcon(theme) {
  const themeIcon = document.getElementById("themeIcon");
  if (themeIcon) {
    if (theme === "dark") {
      themeIcon.className = "fa-solid fa-moon";
    } else {
      themeIcon.className = "fa-solid fa-sun";
    }
  }
}

function toggleTheme(theme) {
  applyTheme(theme);

  // Force style recalculation
  document.documentElement.offsetHeight;
}

// Password toggle functionality
function initPasswordToggle() {
  const passwordToggle = document.getElementById("passwordToggle");
  const passwordInput = document.getElementById("password");

  if (passwordToggle && passwordInput) {
    passwordToggle.addEventListener("click", function (e) {
      e.preventDefault();
      const type =
        passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);

      const icon = passwordToggle.querySelector("i");
      if (icon) {
        icon.className =
          type === "password" ? "fas fa-eye" : "fas fa-eye-slash";
      }
    });
  }
}

// Form submission with CSRF protection
function initFormSubmission() {
  const forms = document.querySelectorAll("form[method='post']");

  forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
      const csrfToken = document.querySelector('input[name="csrf_token"]');
      if (csrfToken) {
        // CSRF token is already in the form
        return true;
      }
    });
  });
}

// Fullscreen functionality
function initFullscreenToggle() {
  const fullscreenToggle = document.getElementById("fullscreenToggle");

  if (fullscreenToggle) {
    fullscreenToggle.addEventListener("click", function (e) {
      e.preventDefault();
      toggleFullscreen();
    });
  }
}

function toggleFullscreen() {
  if (!document.fullscreenElement) {
    // Enter fullscreen
    if (document.documentElement.requestFullscreen) {
      document.documentElement.requestFullscreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
      document.documentElement.webkitRequestFullscreen();
    } else if (document.documentElement.msRequestFullscreen) {
      document.documentElement.msRequestFullscreen();
    }
  } else {
    // Exit fullscreen
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) {
      document.msExitFullscreen();
    }
  }
}

// Update fullscreen icon based on state
function updateFullscreenIcon() {
  const fullscreenToggle = document.getElementById("fullscreenToggle");
  if (fullscreenToggle) {
    const icon = fullscreenToggle.querySelector("i");
    if (icon) {
      if (document.fullscreenElement) {
        icon.className = "fa-solid fa-compress";
      } else {
        icon.className = "fa-solid fa-expand";
      }
    }
  }
}

// Listen for fullscreen changes
document.addEventListener("fullscreenchange", updateFullscreenIcon);
document.addEventListener("webkitfullscreenchange", updateFullscreenIcon);
document.addEventListener("mozfullscreenchange", updateFullscreenIcon);
document.addEventListener("MSFullscreenChange", updateFullscreenIcon);

// Initialize all functionality when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  // Initialize sidebar state
  initSidebarState();

  // Initialize sidebar toggle
  initSidebarToggle();

  // Initialize theme
  initTheme();

  // Initialize fullscreen toggle
  initFullscreenToggle();

  // Initialize password toggle
  initPasswordToggle();

  // Initialize form submission
  initFormSubmission();

  // Theme toggle event listener
  const themeToggle = document.getElementById("themeToggle");

  if (themeToggle) {
    themeToggle.setAttribute("data-listener-added", "true");
    themeToggle.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();

      const currentTheme = window.Hando.theme || "light";
      const newTheme = currentTheme === "light" ? "dark" : "light";

      toggleTheme(newTheme);
    });
  }
});

// Export functions for global access
window.Hando.initSidebarToggle = initSidebarToggle;
window.Hando.initTheme = initTheme;
window.Hando.toggleTheme = toggleTheme;
window.Hando.updateThemeIcon = updateThemeIcon;

// Fallback initialization for theme toggle
// This ensures theme toggle works even if there are timing issues
setTimeout(function () {
  const themeToggle = document.getElementById("themeToggle");
  if (themeToggle && !themeToggle.hasAttribute("data-listener-added")) {
    themeToggle.setAttribute("data-listener-added", "true");
    themeToggle.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();

      const currentTheme = window.Hando.theme || "light";
      const newTheme = currentTheme === "light" ? "dark" : "light";

      toggleTheme(newTheme);
    });
  }
}, 1000);
