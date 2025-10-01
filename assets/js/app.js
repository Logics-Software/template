// Logics Admin Template - Main JavaScript File
// Author: Your Name
// Version: 1.0.0

// Global Logics object
window.Logics = {
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
  const savedTheme = getCookie("logics_theme") || "light";

  applyTheme(savedTheme);
  updateThemeIcon(savedTheme);
}

function applyTheme(theme) {
  document.documentElement.setAttribute("data-bs-theme", theme);
  document.body.setAttribute("data-bs-theme", theme);
  window.Logics.theme = theme;
  setCookie("logics_theme", theme);

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

// Initialize sidebar dropdown with switching system
function initSidebarDropdown() {
  // Handle dropdown toggle clicks (manual open/close)
  document.addEventListener("click", function (e) {
    const toggle = e.target.closest(".sidebar .dropdown-toggle");
    if (toggle) {
      e.preventDefault();
      e.stopPropagation();

      const targetId = toggle.getAttribute("data-bs-target");
      const collapse = document.querySelector(targetId);

      if (collapse) {
        const isCurrentlyOpen = collapse.classList.contains("show");

        if (isCurrentlyOpen) {
          // User wants to close - allow it
          collapse.classList.remove("show");
          collapse.style.display = "none";
          toggle.setAttribute("aria-expanded", "false");
          collapse.removeAttribute("data-keep-open");
          // Clear sessionStorage when manually closed
          sessionStorage.removeItem("sidebar_dropdown_open");
        } else {
          // User wants to open - allow it
          collapse.classList.add("show");
          collapse.style.display = "block";
          toggle.setAttribute("aria-expanded", "true");
          collapse.setAttribute("data-keep-open", "true");
        }
      }
      return false;
    }
  });

  // Handle submenu clicks - keep dropdown open
  document.addEventListener("click", function (e) {
    const submenuLink = e.target.closest(".sidebar .collapse .nav-link");
    if (submenuLink) {
      e.preventDefault();
      e.stopPropagation();

      const parentCollapse = submenuLink.closest(".collapse");
      if (parentCollapse) {
        // Ensure dropdown stays open when submenu is clicked
        parentCollapse.classList.add("show");
        parentCollapse.style.display = "block";
        parentCollapse.setAttribute("data-keep-open", "true");
        const toggle = document.querySelector(
          `[data-bs-target="#${parentCollapse.id}"]`
        );
        if (toggle) {
          toggle.setAttribute("aria-expanded", "true");
        }

        // Store state in sessionStorage before navigation
        sessionStorage.setItem("sidebar_dropdown_open", parentCollapse.id);

        // Navigate to the link
        const href = submenuLink.getAttribute("href");
        if (href && href !== "#") {
          setTimeout(() => {
            window.location.href = href;
          }, 100);
        }
      }
      return false;
    }
  });

  // Prevent Bootstrap from auto-closing dropdowns
  const sidebarDropdowns = document.querySelectorAll(".sidebar .collapse");
  sidebarDropdowns.forEach((dropdown) => {
    // Check if dropdown should be open based on active submenu
    const hasActiveSubmenu = dropdown.querySelector(".nav-link.active");
    const wasOpenBeforeNavigation =
      sessionStorage.getItem("sidebar_dropdown_open") === dropdown.id;

    if (hasActiveSubmenu || wasOpenBeforeNavigation) {
      dropdown.classList.add("show");
      dropdown.style.display = "block";
      dropdown.setAttribute("data-keep-open", "true");
      const toggle = document.querySelector(
        `[data-bs-target="#${dropdown.id}"]`
      );
      if (toggle) {
        toggle.setAttribute("aria-expanded", "true");
      }
    }

    // Prevent auto-collapse
    dropdown.addEventListener("hide.bs.collapse", function (e) {
      const shouldKeepOpen = this.getAttribute("data-keep-open") === "true";
      if (shouldKeepOpen) {
        e.preventDefault();
        e.stopPropagation();
        return false;
      }
    });
  });
}

// Initialize all functionality when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  // Initialize sidebar state
  initSidebarState();

  // Initialize sidebar toggle
  initSidebarToggle();

  // Initialize sidebar dropdown
  initSidebarDropdown();

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

      const currentTheme = window.Logics.theme || "light";
      const newTheme = currentTheme === "light" ? "dark" : "light";

      toggleTheme(newTheme);
    });
  }
});

// Export functions for global access
window.Logics.initSidebarToggle = initSidebarToggle;
window.Logics.initTheme = initTheme;
window.Logics.toggleTheme = toggleTheme;
window.Logics.updateThemeIcon = updateThemeIcon;

// Fallback initialization for theme toggle
// This ensures theme toggle works even if there are timing issues
setTimeout(function () {
  const themeToggle = document.getElementById("themeToggle");
  if (themeToggle && !themeToggle.hasAttribute("data-listener-added")) {
    themeToggle.setAttribute("data-listener-added", "true");
    themeToggle.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();

      const currentTheme = window.Logics.theme || "light";
      const newTheme = currentTheme === "light" ? "dark" : "light";

      toggleTheme(newTheme);
    });
  }
}, 1000);

// Message/Chat functionality
function initMessageSystem() {
  // Update unread count badge
  function updateUnreadCount() {
    fetch(`${window.appUrl}/api/messages/unread-count`)
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          const badge = document.getElementById("unread-count-badge");
          if (badge) {
            if (data.unread_count > 0) {
              badge.textContent = data.unread_count;
              badge.style.display = "inline";
            } else {
              badge.style.display = "none";
            }
          }
        }
      })
      .catch((error) => {
        console.error("Error fetching unread count:", error);
      });
  }

  // Update unread count on page load
  updateUnreadCount();

  // Update unread count every 30 seconds
  setInterval(updateUnreadCount, 30000);

  // Auto-refresh message list if on messages page
  if (
    window.location.pathname.includes("/messages") &&
    !window.location.pathname.includes("/create")
  ) {
    setInterval(() => {
      // Only refresh if user is not actively interacting with the page
      if (
        document.visibilityState === "visible" &&
        !document.querySelector(":focus")
      ) {
        location.reload();
      }
    }, 60000); // Refresh every minute
  }

  // Mark message as read when viewing
  const messageId = new URLSearchParams(window.location.search).get("id");
  if (messageId && window.location.pathname.includes("/messages/")) {
    fetch(`${window.appUrl}/api/messages/mark-read`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify({
        message_id: messageId,
      }),
    });
  }

  // Auto-save draft functionality
  const messageForm = document.getElementById("messageForm");
  if (messageForm) {
    const subjectInput = messageForm.querySelector('input[name="subject"]');
    const contentTextarea = messageForm.querySelector(
      'textarea[name="content"]'
    );
    const recipientsSelect = messageForm.querySelector(
      'select[name="recipients[]"]'
    );

    function saveDraft() {
      // Get Quill content if editor exists
      let content = contentTextarea.value;
      if (window.quill) {
        content = window.quill.root.innerHTML;
      }

      const draft = {
        subject: subjectInput.value,
        content: content,
        recipients: Array.from(recipientsSelect.selectedOptions).map(
          (option) => option.value
        ),
        timestamp: Date.now(),
      };
      localStorage.setItem("message_draft", JSON.stringify(draft));
    }

    function loadDraft() {
      // Check if user just sent a message (from URL parameter)
      const urlParams = new URLSearchParams(window.location.search);
      const justSent = urlParams.get("sent");

      if (justSent === "true") {
        // Clear any existing draft if user just sent a message
        localStorage.removeItem("message_draft");
        return;
      }

      const draft = localStorage.getItem("message_draft");
      if (draft) {
        try {
          const draftData = JSON.parse(draft);
          // Only load draft if it's less than 24 hours old
          if (Date.now() - draftData.timestamp < 24 * 60 * 60 * 1000) {
            if (draftData.subject) subjectInput.value = draftData.subject;
            if (draftData.content) {
              contentTextarea.value = draftData.content;
              // Load content into Quill editor if it exists
              if (window.quill) {
                window.quill.root.innerHTML = draftData.content;
              }
            }
            if (draftData.recipients && draftData.recipients.length > 0) {
              Array.from(recipientsSelect.options).forEach((option) => {
                option.selected = draftData.recipients.includes(option.value);
              });
            }
          }
        } catch (e) {
          localStorage.removeItem("message_draft");
        }
      }
    }

    // Load draft on page load
    loadDraft();

    // Save draft on input change
    [subjectInput, contentTextarea].forEach((input) => {
      if (input) {
        input.addEventListener("input", saveDraft);
      }
    });

    if (recipientsSelect) {
      recipientsSelect.addEventListener("change", saveDraft);
    }

    // Clear draft on successful send
    messageForm.addEventListener("submit", function () {
      setTimeout(() => {
        localStorage.removeItem("message_draft");
      }, 1000);
    });
  }

  // Message search functionality
  const searchForm = document.querySelector('form[action*="/messages/search"]');
  if (searchForm) {
    const searchInput = searchForm.querySelector('input[name="q"]');
    if (searchInput) {
      // Add search suggestions (basic implementation)
      searchInput.addEventListener("input", function () {
        const query = this.value;
        if (query.length > 2) {
          // Could implement search suggestions here
        }
      });
    }
  }

  // Print message functionality
  window.printMessage = function () {
    window.print();
  };

  // Delete message with confirmation
  // NOTE: This function is now implemented in each message view file with Bootstrap modal
  // Commented out to prevent conflict with modal implementation
  /*
  window.deleteMessage = function (messageId) {
    if (confirm("Apakah Anda yakin ingin menghapus pesan ini?")) {
      fetch(`${window.appUrl}/messages/${messageId}`, {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            location.reload();
          } else {
            alert("Gagal menghapus pesan: " + data.message);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Terjadi kesalahan saat menghapus pesan");
        });
    }
  };
  */
}

// Initialize message system when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  initMessageSystem();
  initHeaderMessageDropdown();
});

// Header Message Dropdown functionality
function initHeaderMessageDropdown() {
  const messageToggle = document.getElementById("messageToggle");
  const messageBadge = document.getElementById("messageBadge");
  const messageList = document.getElementById("messageList");

  if (!messageToggle || !messageBadge || !messageList) {
    return;
  }

  // Load unread count and recent messages
  function loadMessageData() {
    // Load unread count
    fetch(`${window.appUrl}/api/messages/unread-count`)
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        if (data.success && data.unread_count > 0) {
          messageBadge.textContent = data.unread_count;
          messageBadge.style.display = "inline";
        } else {
          messageBadge.style.display = "none";
        }
      })
      .catch((error) => {
        console.error("Error loading unread count:", error);
      });

    // Load recent messages
    fetch(`${window.appUrl}/api/messages/recent`)
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        if (data.success && data.messages && data.messages.length > 0) {
          let html = "";
          data.messages.forEach((message) => {
            const timeAgo = getTimeAgo(message.created_at);
            const senderInitial = message.sender_name.charAt(0).toUpperCase();
            const gradientColors = [
              "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
              "linear-gradient(135deg, #28a745 0%, #20c997 100%)",
              "linear-gradient(135deg, #fd7e14 0%, #e83e8c 100%)",
              "linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%)",
              "linear-gradient(135deg, #dc3545 0%, #fd7e14 100%)",
            ];
            const randomGradient =
              gradientColors[Math.floor(Math.random() * gradientColors.length)];

            // Use actual sender picture if available, otherwise fallback to avatar
            let pictureUrl = `${window.appUrl}/assets/images/users/avatar.svg`; // Default fallback

            if (message.sender_picture) {
              // Check if the picture path already includes the full path
              if (message.sender_picture.startsWith("assets/images/users/")) {
                pictureUrl = `${window.appUrl}/${message.sender_picture}`;
              } else {
                pictureUrl = `${window.appUrl}/assets/images/users/${message.sender_picture}`;
              }
            }

            html += `
              <div class="message-item" data-message-id="${
                message.id
              }" data-message-url="${message.url}">
                <div class="d-flex">
                  <div class="message-avatar">
                    <img src="${pictureUrl}" alt="User" class="rounded-circle" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="avatar-fallback avatar-md" style="display:none; background:${randomGradient};">${senderInitial}</div>
                  </div>
                  <div class="message-content">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                      <h6 class="mb-0">${escapeHtml(message.sender_name)}</h6>
                      <small class="text-muted">${timeAgo}</small>
                    </div>
                    <p class="mb-0 text-muted">${escapeHtml(
                      message.subject
                    )}</p>
                  </div>
                </div>
              </div>
            `;
          });
          messageList.innerHTML = html;

          // Add click handlers to message items
          messageList.querySelectorAll(".message-item").forEach((item) => {
            item.addEventListener("click", function () {
              const messageUrl = this.getAttribute("data-message-url");
              if (messageUrl) {
                window.location.href = messageUrl;
              }
            });
          });
        } else {
          messageList.innerHTML = `
            <div class="text-center p-4">
              <i class="fa-regular fa-envelope fa-2x text-muted mb-3"></i>
              <p class="text-muted mb-0">Tidak ada pesan</p>
            </div>
          `;
        }
      })
      .catch((error) => {
        console.error("Error loading recent messages:", error);
        messageList.innerHTML = `
          <div class="text-center p-4">
            <i class="fa-solid fa-exclamation-triangle fa-2x text-warning mb-3"></i>
            <p class="text-muted mb-0">Gagal memuat pesan</p>
          </div>
        `;
      });
  }

  // Helper function to escape HTML
  function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
  }

  // Helper function to get time ago
  function getTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);

    if (diffInSeconds < 60) {
      return "Baru saja";
    } else if (diffInSeconds < 3600) {
      const minutes = Math.floor(diffInSeconds / 60);
      return `${minutes} menit yang lalu`;
    } else if (diffInSeconds < 86400) {
      const hours = Math.floor(diffInSeconds / 3600);
      return `${hours} jam yang lalu`;
    } else {
      const days = Math.floor(diffInSeconds / 86400);
      return `${days} hari yang lalu`;
    }
  }

  // Load data on page load
  loadMessageData();

  // Refresh data when dropdown is opened
  messageToggle.addEventListener("click", function () {
    setTimeout(loadMessageData, 100);
  });

  // Mark all as read button handler
  const markAllAsReadBtn = document.getElementById("markAllAsReadBtn");
  if (markAllAsReadBtn) {
    markAllAsReadBtn.addEventListener("click", function () {
      // Show loading state
      const originalText = this.textContent;
      this.textContent = "Memproses...";
      this.disabled = true;

      fetch(`${window.appUrl}/api/messages/mark-all-read`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Reload message data to reflect changes
            loadMessageData();
            // Show success message briefly
            this.textContent = "Berhasil!";
            setTimeout(() => {
              this.textContent = originalText;
              this.disabled = false;
            }, 2000);
          } else {
            alert("Gagal menandai pesan sebagai sudah dibaca");
            this.textContent = originalText;
            this.disabled = false;
          }
        })
        .catch((error) => {
          console.error("Error marking all as read:", error);
          alert("Terjadi kesalahan saat menandai pesan");
          this.textContent = originalText;
          this.disabled = false;
        });
    });
  }

  // Refresh data every 30 seconds
  setInterval(loadMessageData, 30000);
}
