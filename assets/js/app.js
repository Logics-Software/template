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

  if (sidebar && mainContent && topHeader) {
    if (isCollapsed) {
      sidebar.classList.add("collapsed");
      mainContent.classList.add("sidebar-collapsed");
      topHeader.classList.add("sidebar-collapsed");
    }

    // Remove temporary init class after state is properly set
    document.documentElement.classList.remove("sidebar-collapsed-init");
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

// Initialize notification dropdown
function initNotificationDropdown() {
  const notificationToggle = document.getElementById("notificationToggle");
  const notificationDropdown = notificationToggle?.nextElementSibling;

  if (notificationToggle && notificationDropdown) {
    // Add smooth animation classes
    notificationDropdown.style.transition =
      "all 0.3s cubic-bezier(0.4, 0, 0.2, 1)";

    // Toggle dropdown on click
    notificationToggle.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();

      // Close other dropdowns first
      document
        .querySelectorAll(
          ".dropdown-menu.show, .notification-menu.show, .message-menu.show"
        )
        .forEach((menu) => {
          if (menu !== notificationDropdown) {
            menu.classList.remove("show");
          }
        });

      // Toggle current dropdown with smooth animation
      const isOpen = notificationDropdown.classList.contains("show");

      if (isOpen) {
        // Close dropdown
        notificationDropdown.classList.remove("show");
        notificationToggle.setAttribute("aria-expanded", "false");
      } else {
        // Open dropdown
        notificationDropdown.classList.add("show");
        notificationToggle.setAttribute("aria-expanded", "true");

        // Add entrance animation to notification items
        setTimeout(() => {
          const notificationItems =
            notificationDropdown.querySelectorAll(".notification-item");
          notificationItems.forEach((item, index) => {
            item.style.opacity = "0";
            item.style.transform = "translateY(-10px)";
            setTimeout(() => {
              item.style.transition = "all 0.3s ease";
              item.style.opacity = "1";
              item.style.transform = "translateY(0)";
            }, index * 50);
          });
        }, 50);
      }
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", function (e) {
      if (
        !notificationToggle.contains(e.target) &&
        !notificationDropdown.contains(e.target)
      ) {
        notificationDropdown.classList.remove("show");
        notificationToggle.setAttribute("aria-expanded", "false");
      }
    });

    // Handle clear all notifications
    const clearAllBtn = notificationDropdown.querySelector(
      ".dropdown-header .btn"
    );
    if (clearAllBtn) {
      clearAllBtn.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();

        // Add loading state to button
        const originalText = clearAllBtn.innerHTML;
        clearAllBtn.innerHTML =
          '<i class="fas fa-spinner fa-spin me-1"></i>Clearing...';
        clearAllBtn.disabled = true;

        // Clear all notification items with animation
        const notificationItems =
          notificationDropdown.querySelectorAll(".notification-item");
        notificationItems.forEach((item, index) => {
          setTimeout(() => {
            item.style.transition = "all 0.3s ease";
            item.style.opacity = "0";
            item.style.transform = "translateX(100%) scale(0.8)";
            setTimeout(() => {
              item.remove();
            }, 300);
          }, index * 100);
        });

        // Update badge count with animation
        const badge = notificationToggle.querySelector(".badge");
        if (badge) {
          badge.style.transition = "all 0.3s ease";
          badge.style.transform = "scale(1.2)";
          setTimeout(() => {
            badge.textContent = "0";
            badge.style.transform = "scale(1)";
            setTimeout(() => {
              badge.style.display = "none";
            }, 300);
          }, 200);
        }

        // Reset button and show empty state
        setTimeout(() => {
          clearAllBtn.innerHTML = originalText;
          clearAllBtn.disabled = false;

          const notificationList =
            notificationDropdown.querySelector(".notification-list");
          if (notificationList && notificationList.children.length === 0) {
            notificationList.innerHTML = `
              <div class="notification-empty">
                <i class="fas fa-bell-slash"></i>
                <h6>No notifications</h6>
                <p>All caught up! You're all set.</p>
              </div>
            `;
          }
        }, notificationItems.length * 100 + 500);
      });
    }

    // Handle individual notification item clicks
    notificationDropdown.addEventListener("click", function (e) {
      const notificationItem = e.target.closest(".notification-item");
      if (notificationItem) {
        // Add read state styling
        notificationItem.style.opacity = "0.7";
        notificationItem.style.transform = "translateX(-4px)";

        // Remove after animation
        setTimeout(() => {
          notificationItem.remove();

          // Update badge count
          const badge = notificationToggle.querySelector(".badge");
          if (badge) {
            const currentCount = parseInt(badge.textContent) || 0;
            const newCount = Math.max(0, currentCount - 1);
            badge.textContent = newCount;

            if (newCount === 0) {
              badge.style.display = "none";
            }
          }
        }, 300);
      }
    });

    // Add keyboard navigation support
    notificationToggle.addEventListener("keydown", function (e) {
      if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        notificationToggle.click();
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

  // Initialize notification dropdown
  initNotificationDropdown();

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
window.Logics.initNotificationDropdown = initNotificationDropdown;

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
  // Check if user is logged in before making API calls
  const isLoggedIn =
    document.querySelector(".user-dropdown") ||
    document.querySelector(".sidebar") ||
    document.querySelector(".main-content");

  if (!isLoggedIn) {
    return; // Exit if user is not logged in
  }

  // Update unread count badge
  function updateUnreadCount() {
    fetch(`${window.appUrl}/api/messages/unread-count`)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Unauthorized");
        }
        return response.json();
      })
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
        // Silently handle unauthorized errors
        if (error.message !== "Unauthorized") {
          // Error handled silently
        }
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

    // For messages form, we might have a different structure
    const selectedRecipientsInput = messageForm.querySelector(
      'input[name="recipients[]"]'
    );

    function saveDraft() {
      // Get Quill content if editor exists
      let content = contentTextarea.value;
      if (window.quill) {
        content = window.quill.root.innerHTML;
      }

      // Get recipients based on form structure
      let recipients = [];
      if (recipientsSelect) {
        // Traditional select dropdown
        recipients = Array.from(recipientsSelect.selectedOptions).map(
          (option) => option.value
        );
      } else if (selectedRecipientsInput) {
        // Hidden input with comma-separated values
        const recipientsValue = selectedRecipientsInput.value;
        recipients = recipientsValue
          ? recipientsValue.split(",").filter((id) => id.trim())
          : [];
      }

      const draft = {
        subject: subjectInput.value,
        content: content,
        recipients: recipients,
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
              if (recipientsSelect) {
                // Traditional select dropdown
                Array.from(recipientsSelect.options).forEach((option) => {
                  option.selected = draftData.recipients.includes(option.value);
                });
              } else if (selectedRecipientsInput) {
                // Hidden input with comma-separated values
                selectedRecipientsInput.value = draftData.recipients.join(",");
              }
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

    if (selectedRecipientsInput) {
      selectedRecipientsInput.addEventListener("change", saveDraft);
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
}

// Initialize message system when DOM is loaded (only if user is logged in)
document.addEventListener("DOMContentLoaded", function () {
  // Check if user is logged in by looking for user session elements
  const isLoggedIn =
    document.querySelector(".user-dropdown") ||
    document.querySelector(".sidebar") ||
    document.querySelector(".main-content");

  if (isLoggedIn) {
    initMessageSystem();
    initHeaderMessageDropdown();
  }
});

// Header Message Dropdown functionality
function initHeaderMessageDropdown() {
  // Check if user is logged in before making API calls
  const isLoggedIn =
    document.querySelector(".user-dropdown") ||
    document.querySelector(".sidebar") ||
    document.querySelector(".main-content");

  if (!isLoggedIn) {
    return; // Exit if user is not logged in
  }

  const messageToggle = document.getElementById("messageToggle");
  const messageBadge = document.getElementById("messageBadge");
  const messageList = document.getElementById("messageList");
  const messageDropdown = messageToggle?.nextElementSibling;

  if (!messageToggle || !messageBadge || !messageList || !messageDropdown) {
    return;
  }

  // Load unread count and recent messages
  function loadMessageData() {
    // Load unread count
    fetch(`${window.appUrl}/api/messages/unread-count`)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Unauthorized");
        }
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
        // Silently handle unauthorized errors
        if (error.message !== "Unauthorized") {
          // Error handled silently
        }
      });

    // Load recent messages
    fetch(`${window.appUrl}/api/messages/recent`)
      .then((response) => {
        if (!response.ok) {
          throw new Error("Unauthorized");
        }
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
        // Error handled silently
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

  // Toggle dropdown on click
  messageToggle.addEventListener("click", function (e) {
    e.preventDefault();
    e.stopPropagation();

    // Close other dropdowns first
    document
      .querySelectorAll(
        ".dropdown-menu.show, .notification-menu.show, .message-menu.show"
      )
      .forEach((menu) => {
        if (menu !== messageDropdown) {
          menu.classList.remove("show");
        }
      });

    // Toggle current dropdown
    messageDropdown.classList.toggle("show");

    // Refresh data when dropdown is opened
    if (messageDropdown.classList.contains("show")) {
      setTimeout(loadMessageData, 100);
    }
  });

  // Close dropdown when clicking outside
  document.addEventListener("click", function (e) {
    if (
      !messageToggle.contains(e.target) &&
      !messageDropdown.contains(e.target)
    ) {
      messageDropdown.classList.remove("show");
    }
  });

  // Mark all as read link handler
  const markAllAsReadBtn = document.getElementById("markAllAsReadBtn");
  if (markAllAsReadBtn) {
    markAllAsReadBtn.addEventListener("click", function (e) {
      e.preventDefault(); // Prevent default link navigation

      // Show loading state
      const originalText = this.textContent;
      this.textContent = "Memproses...";
      this.style.pointerEvents = "none"; // Disable clicks
      this.classList.add("disabled");

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
              this.style.pointerEvents = "auto";
              this.classList.remove("disabled");
            }, 2000);
          } else {
            window.Notify.error("Gagal menandai pesan sebagai sudah dibaca");
            this.textContent = originalText;
            this.style.pointerEvents = "auto";
            this.classList.remove("disabled");
          }
        })
        .catch((error) => {
          // Error handled silently
          window.Notify.error("Terjadi kesalahan saat menandai pesan");
          this.textContent = originalText;
          this.style.pointerEvents = "auto";
          this.classList.remove("disabled");
        });
    });
  }

  // Clear all notifications link handler
  const clearAllNotificationsBtn = document.getElementById(
    "clearAllNotificationsBtn"
  );
  if (clearAllNotificationsBtn) {
    clearAllNotificationsBtn.addEventListener("click", function (e) {
      e.preventDefault(); // Prevent default link navigation

      // Show loading state
      const originalText = this.textContent;
      this.textContent = "Clearing...";
      this.style.pointerEvents = "none"; // Disable clicks
      this.classList.add("disabled");

      // Simulate clearing notifications (replace with actual API call when available)
      setTimeout(() => {
        window.Notify.success("All notifications cleared");
        this.textContent = originalText;
        this.style.pointerEvents = "auto";
        this.classList.remove("disabled");

        // Clear notification badge
        const badge = document.getElementById("notificationBadge");
        if (badge) {
          badge.textContent = "0";
          badge.style.display = "none";
        }
      }, 1000);
    });
  }

  // Refresh data every 30 seconds
  setInterval(loadMessageData, 30000);
}

// Keyboard shortcuts
document.addEventListener("keydown", function (event) {
  // Ctrl + F12 shortcut for lock-screen
  if (event.ctrlKey && event.key === "F12") {
    event.preventDefault(); // Prevent browser default behavior

    // Check if user is logged in
    const isLoggedIn =
      document.querySelector(".user-dropdown") ||
      document.querySelector(".sidebar") ||
      document.querySelector(".main-content");

    if (isLoggedIn) {
      // Redirect to lock-screen
      window.location.href = window.location.origin + "/lock-screen";
    } else {
      // If not logged in, show alert
      Notify.warning(
        "Anda harus login terlebih dahulu untuk mengakses lock screen"
      );
    }
  }
});

// ============================================
// Bootstrap Tooltips Initialization
// ============================================

/**
 * Initialize all Bootstrap tooltips on page load
 */
function initTooltips() {
  // Find all elements with tooltip attribute
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );

  // Initialize each tooltip with custom options
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl, {
      delay: {
        show: 500, // Delay 500ms sebelum muncul
        hide: 100, // Cepat hilang saat hover out
      },
      placement: "top", // Default position (akan auto-adjust jika tidak cukup ruang)
      trigger: "hover focus", // Show on hover and focus (accessibility)
      html: false, // Security: disable HTML parsing
      animation: true, // Enable CSS animation
      boundary: "viewport", // Keep tooltip dalam viewport
      fallbackPlacements: ["top", "bottom", "left", "right"], // Auto adjust position
      popperConfig: {
        modifiers: [
          {
            name: "offset",
            options: {
              offset: [0, 8], // Offset dari element (horizontal, vertical)
            },
          },
          {
            name: "preventOverflow",
            options: {
              boundary: "viewport",
              padding: 8,
            },
          },
        ],
      },
    });
  });

  return tooltipList;
}

/**
 * Refresh tooltips - untuk dynamic content
 * Call this after AJAX load atau DOM manipulation
 */
window.refreshTooltips = function () {
  // Dispose all existing tooltips
  document
    .querySelectorAll('[data-bs-toggle="tooltip"]')
    .forEach(function (el) {
      const tooltipInstance = bootstrap.Tooltip.getInstance(el);
      if (tooltipInstance) {
        tooltipInstance.dispose();
      }
    });

  // Re-initialize
  initTooltips();
};

/**
 * Auto cleanup tooltips when elements are removed from DOM
 */
function setupTooltipAutoCleanup() {
  const observer = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
      mutation.removedNodes.forEach(function (node) {
        if (node.nodeType === 1) {
          // Check if the node itself has a tooltip
          const tooltipInstance = bootstrap.Tooltip.getInstance(node);
          if (tooltipInstance) {
            tooltipInstance.dispose();
          }

          // Check if any children have tooltips
          const childrenWithTooltips = node.querySelectorAll
            ? node.querySelectorAll('[data-bs-toggle="tooltip"]')
            : [];
          childrenWithTooltips.forEach(function (child) {
            const childTooltip = bootstrap.Tooltip.getInstance(child);
            if (childTooltip) {
              childTooltip.dispose();
            }
          });
        }
      });
    });
  });

  observer.observe(document.body, {
    childList: true,
    subtree: true,
  });
}

/**
 * Module Access Validation
 * Validates user access to modules before navigation
 */
function initModuleAccessValidation() {
  const moduleLinks = document.querySelectorAll(".validate-module-access");

  moduleLinks.forEach((link) => {
    link.addEventListener("click", async function (e) {
      e.preventDefault();

      const moduleLink = this.getAttribute("data-module-link");
      const targetUrl = this.getAttribute("href");

      // If no module link data, just navigate normally
      if (!moduleLink || moduleLink === "#") {
        window.location.href = targetUrl;
        return;
      }

      // Show loading indicator
      const originalText = this.innerHTML;
      this.innerHTML =
        '<i class="fas fa-spinner fa-spin"></i> ' +
          this.querySelector(".nav-text")?.textContent || "";
      this.style.pointerEvents = "none";

      try {
        // Call validation API
        const apiUrl = window.appUrl + "/api/validate-module-access";

        const response = await fetch(apiUrl, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-Token": window.csrfToken, // CSRF protection
          },
          credentials: "same-origin", // Important: Include cookies/session
          body: JSON.stringify({
            link: moduleLink,
          }),
        });

        // Check if response is OK
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        // Parse JSON response
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
          const text = await response.text();
          console.error("Non-JSON response:", text);
          throw new Error("Server returned non-JSON response");
        }

        const data = await response.json();

        // Restore link
        this.innerHTML = originalText;
        this.style.pointerEvents = "auto";

        if (data.success && data.allowed) {
          // Access granted - navigate to page
          window.location.href = targetUrl;
        } else {
          // Access denied - show modal
          showAccessDeniedModal(data.module?.caption || "modul ini");
        }
      } catch (error) {
        // Restore link on error
        this.innerHTML = originalText;
        this.style.pointerEvents = "auto";

        console.error("Module access validation error:", error);
        window.Notify.error("Terjadi kesalahan saat validasi akses modul");
      }
    });
  });
}

/**
 * Show access denied modal
 */
function showAccessDeniedModal(moduleName) {
  // Check if modal already exists
  let modal = document.getElementById("accessDeniedModal");

  if (!modal) {
    // Create modal
    const modalHtml = `
      <div class="modal fade" id="accessDeniedModal" tabindex="-1" aria-labelledby="accessDeniedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title" id="accessDeniedModalLabel">
                <i class="fas fa-exclamation-triangle me-2"></i>Akses Ditolak
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
              <i class="fas fa-ban fa-3x text-danger mb-3"></i>
              <p class="fs-5 mb-0" id="accessDeniedMessage">Anda tidak berhak menjalankan modul tersebut</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="accessDeniedOkBtn">
                <i class="fas fa-check me-2"></i>OK
              </button>
            </div>
          </div>
        </div>
      </div>
    `;

    document.body.insertAdjacentHTML("beforeend", modalHtml);
    modal = document.getElementById("accessDeniedModal");

    // Add event listener for OK button to redirect to dashboard
    document
      .getElementById("accessDeniedOkBtn")
      .addEventListener("click", function () {
        window.location.href = window.appUrl + "/dashboard";
      });

    // Also redirect when modal is closed by backdrop or X button
    modal.addEventListener("hidden.bs.modal", function () {
      window.location.href = window.appUrl + "/dashboard";
    });
  }

  // Update message
  document.getElementById("accessDeniedMessage").textContent =
    "Anda tidak berhak menjalankan modul tersebut";

  // Show modal
  const bsModal = new bootstrap.Modal(modal);
  bsModal.show();
}

// Initialize tooltips on page load
document.addEventListener("DOMContentLoaded", function () {
  initTooltips();
  setupTooltipAutoCleanup();
  initModuleAccessValidation();
});
