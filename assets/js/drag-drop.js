/**
 * Drag and Drop Utility Functions
 * Reusable JavaScript functions for drag and drop functionality
 */

class DragDropManager {
  constructor() {
    this.draggedElement = null;
    this.draggedIndex = 0;
    this.init();
  }

  init() {
    // Initialize drag and drop for all draggable elements
    this.initDraggableRows();
    this.initDraggableCards();
    this.initDraggableListItems();
    this.initDraggableGridItems();
  }

  // Initialize draggable table rows
  initDraggableRows() {
    const rows = document.querySelectorAll(".draggable-row");
    rows.forEach((row, index) => {
      row.draggable = true;
      row.addEventListener("dragstart", this.handleDragStart.bind(this));
      row.addEventListener("dragend", this.handleDragEnd.bind(this));
      row.addEventListener("dragover", this.handleDragOver.bind(this));
      row.addEventListener("dragenter", this.handleDragEnter.bind(this));
      row.addEventListener("dragleave", this.handleDragLeave.bind(this));
      row.addEventListener("drop", this.handleDrop.bind(this));
    });
  }

  // Initialize draggable cards
  initDraggableCards() {
    const cards = document.querySelectorAll(".draggable-card");
    cards.forEach((card, index) => {
      card.draggable = true;
      card.addEventListener("dragstart", this.handleDragStart.bind(this));
      card.addEventListener("dragend", this.handleDragEnd.bind(this));
      card.addEventListener("dragover", this.handleDragOver.bind(this));
      card.addEventListener("dragenter", this.handleDragEnter.bind(this));
      card.addEventListener("dragleave", this.handleDragLeave.bind(this));
      card.addEventListener("drop", this.handleDrop.bind(this));
    });
  }

  // Initialize draggable list items
  initDraggableListItems() {
    const items = document.querySelectorAll(".draggable-list-item");
    items.forEach((item, index) => {
      item.draggable = true;
      item.addEventListener("dragstart", this.handleDragStart.bind(this));
      item.addEventListener("dragend", this.handleDragEnd.bind(this));
      item.addEventListener("dragover", this.handleDragOver.bind(this));
      item.addEventListener("dragenter", this.handleDragEnter.bind(this));
      item.addEventListener("dragleave", this.handleDragLeave.bind(this));
      item.addEventListener("drop", this.handleDrop.bind(this));
    });
  }

  // Initialize draggable grid items
  initDraggableGridItems() {
    const items = document.querySelectorAll(".draggable-grid-item");
    items.forEach((item, index) => {
      item.draggable = true;
      item.addEventListener("dragstart", this.handleDragStart.bind(this));
      item.addEventListener("dragend", this.handleDragEnd.bind(this));
      item.addEventListener("dragover", this.handleDragOver.bind(this));
      item.addEventListener("dragenter", this.handleDragEnter.bind(this));
      item.addEventListener("dragleave", this.handleDragLeave.bind(this));
      item.addEventListener("drop", this.handleDrop.bind(this));
    });
  }

  handleDragStart(e) {
    // Ensure we have valid elements
    if (!e.target || !e.target.parentNode) {
      return;
    }

    this.draggedElement = e.target;
    this.draggedIndex = Array.from(e.target.parentNode.children).indexOf(
      e.target
    );

    // Only proceed if we have a valid index
    if (this.draggedIndex === -1) {
      return;
    }

    e.target.classList.add("dragging");
    e.dataTransfer.effectAllowed = "move";
    e.dataTransfer.setData("text/html", e.target.outerHTML);
  }

  handleDragEnd(e) {
    e.target.classList.remove("dragging");
    // Remove drag-over class from all elements
    document.querySelectorAll(".drag-over").forEach((el) => {
      el.classList.remove("drag-over");
    });
    this.draggedElement = null;
  }

  handleDragOver(e) {
    if (e.preventDefault) {
      e.preventDefault();
    }
    e.dataTransfer.dropEffect = "move";
    return false;
  }

  handleDragEnter(e) {
    if (this.draggedElement && e.target) {
      // Find the actual row element (in case we're entering a child element)
      let targetRow = e.target;
      while (targetRow && !targetRow.classList.contains("draggable-row")) {
        targetRow = targetRow.parentNode;
      }

      if (targetRow && targetRow !== this.draggedElement) {
        targetRow.classList.add("drag-over");
      }
    }
  }

  handleDragLeave(e) {
    // Find the actual row element (in case we're leaving a child element)
    let targetRow = e.target;
    while (targetRow && !targetRow.classList.contains("draggable-row")) {
      targetRow = targetRow.parentNode;
    }

    // Only remove if we're actually leaving the element
    if (targetRow && !targetRow.contains(e.relatedTarget)) {
      targetRow.classList.remove("drag-over");
    }
  }

  handleDrop(e) {
    if (e.stopPropagation) {
      e.stopPropagation();
    }

    if (this.draggedElement && e.target) {
      // Find the actual row element (in case we're dropping on a child element)
      let targetRow = e.target;
      while (targetRow && !targetRow.classList.contains("draggable-row")) {
        targetRow = targetRow.parentNode;
      }

      // Ensure we have valid row elements and they're different
      if (
        this.draggedElement !== targetRow &&
        targetRow &&
        this.draggedElement.classList.contains("draggable-row")
      ) {
        // Ensure both elements have the same parent
        if (this.draggedElement.parentNode === targetRow.parentNode) {
          const dropIndex = Array.from(targetRow.parentNode.children).indexOf(
            targetRow
          );

          // Only move if the drop position is valid
          if (dropIndex !== -1 && this.draggedIndex !== dropIndex) {
            // Move DOM element safely
            if (this.draggedIndex < dropIndex) {
              // Moving down: insert before the next sibling of drop target
              const nextSibling = targetRow.nextSibling;
              targetRow.parentNode.insertBefore(
                this.draggedElement,
                nextSibling
              );
            } else {
              // Moving up: insert before the drop target
              targetRow.parentNode.insertBefore(this.draggedElement, targetRow);
            }

            // Trigger custom event for sort order update
            this.triggerSortOrderUpdate();
          }
        } else {
          // Elements have different parents
        }
      }
    }

    // Remove drag-over class from all elements
    document.querySelectorAll(".drag-over").forEach((el) => {
      el.classList.remove("drag-over");
    });

    return false;
  }

  triggerSortOrderUpdate() {
    const event = new CustomEvent("sortOrderChanged", {
      detail: {
        draggedElement: this.draggedElement,
        newOrder: this.getNewOrder(),
      },
    });
    document.dispatchEvent(event);
  }

  getNewOrder() {
    const elements = document.querySelectorAll(
      ".draggable-row, .draggable-card, .draggable-list-item, .draggable-grid-item"
    );
    return Array.from(elements).map((el, index) => ({
      id: parseInt(el.dataset.id) || index,
      sort_order: index + 1,
      element: el,
    }));
  }
}

/**
 * Toast Notification Utility - Updated to use unified Notify system
 */
class ToastManager {
  constructor() {
    // Use unified Notify system instead of custom implementation
    this.notify = window.Notify;
  }

  show(message, type = "info", duration = 3000) {
    // Use unified Notify system
    return this.notify.show(type, message, duration);
  }

  success(message, duration = 3000) {
    return this.notify.success(message, duration);
  }

  error(message, duration = 5000) {
    return this.notify.error(message, duration);
  }

  warning(message, duration = 4000) {
    return this.notify.warning(message, duration);
  }

  info(message, duration = 3000) {
    return this.notify.info(message, duration);
  }
}

/**
 * Sort Order Update Utility
 */
class SortOrderManager {
  constructor(options = {}) {
    this.updateUrl = options.updateUrl || null;
    this.csrfToken = options.csrfToken || null;
    this.onSuccess = options.onSuccess || null;
    this.onError = options.onError || null;
    this.toastManager = new ToastManager();
    this.init();
  }

  init() {
    document.addEventListener(
      "sortOrderChanged",
      this.handleSortOrderChanged.bind(this)
    );
  }

  getCSRFToken() {
    // Try multiple sources for CSRF token
    // 1. From meta tag
    let token = document
      .querySelector('meta[name="csrf-token"]')
      ?.getAttribute("content");

    // 2. From hidden input with name _token
    if (!token) {
      token = document.querySelector('input[name="_token"]')?.value;
    }

    // 3. From global variable
    if (!token && window.csrfToken) {
      token = window.csrfToken;
    }

    // 4. Use provided token as fallback
    if (!token) {
      token = this.csrfToken;
    }

    return token;
  }

  handleSortOrderChanged(e) {
    if (this.updateUrl) {
      this.updateSortOrder(e.detail.newOrder);
    }
  }

  updateSortOrder(orders) {
    const elements = orders.map((order) => order.element);

    // Show loading state
    elements.forEach((el) => {
      el.classList.add("updating");
    });

    // Get fresh CSRF token
    const csrfToken = this.getCSRFToken();

    fetch(this.updateUrl, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
        "X-CSRF-Token": csrfToken,
      },
      body: JSON.stringify({
        orders: orders,
        _token: csrfToken,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        // Remove loading state
        elements.forEach((el) => {
          el.classList.remove("updating");
        });

        if (data.success) {
          if (this.onSuccess) {
            this.onSuccess(data);
          } else {
            this.toastManager.success(
              data.message || "Urutan berhasil diperbarui!"
            );
          }
        } else {
          if (this.onError) {
            this.onError(data);
          } else {
            this.toastManager.error(data.error || "Gagal memperbarui urutan");
            // Reload page to reset order
            setTimeout(() => {
              location.reload();
            }, 2000);
          }
        }
      })
      .catch((error) => {
        // Remove loading state
        elements.forEach((el) => {
          el.classList.remove("updating");
        });

        if (this.onError) {
          this.onError({ error: "Terjadi kesalahan saat memperbarui urutan" });
        } else {
          this.toastManager.error("Terjadi kesalahan saat memperbarui urutan");
          // Reload page to reset order
          setTimeout(() => {
            location.reload();
          }, 2000);
        }
      });
  }
}

/**
 * Initialize drag and drop functionality
 * @param {Object} options - Configuration options
 */
function initDragDrop(options = {}) {
  // Initialize drag drop manager
  const dragDropManager = new DragDropManager();

  // Initialize sort order manager if URL is provided
  if (options.sortOrderUrl) {
    new SortOrderManager({
      updateUrl: options.sortOrderUrl,
      csrfToken: options.csrfToken,
      onSuccess: options.onSuccess,
      onError: options.onError,
    });
  }

  return {
    dragDropManager,
    toastManager: new ToastManager(),
  };
}

/**
 * Legacy function for backward compatibility
 */
function showSuccessMessage(message) {
  const toastManager = new ToastManager();
  toastManager.success(message);
}

function showErrorMessage(message) {
  const toastManager = new ToastManager();
  toastManager.error(message);
}

function showWarningMessage(message) {
  const toastManager = new ToastManager();
  toastManager.warning(message);
}

function showInfoMessage(message) {
  const toastManager = new ToastManager();
  toastManager.info(message);
}

// Auto-initialize when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
  // Initialize drag and drop for elements with data-drag-drop attribute
  const dragDropElements = document.querySelectorAll("[data-drag-drop]");
  if (dragDropElements.length > 0) {
    initDragDrop();
  }
});

// Export for module usage
if (typeof module !== "undefined" && module.exports) {
  module.exports = {
    DragDropManager,
    ToastManager,
    SortOrderManager,
    initDragDrop,
    showSuccessMessage,
    showErrorMessage,
    showWarningMessage,
    showInfoMessage,
  };
}
