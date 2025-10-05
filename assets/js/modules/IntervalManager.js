/**
 * Interval Manager - Handles all intervals with proper cleanup
 */
const IntervalManager = {
  intervals: new Map(),

  /**
   * Set an interval with automatic cleanup support
   */
  setInterval(callback, delay, key = null) {
    // Generate key if not provided
    if (!key) {
      key = `interval_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
    }

    // Clear existing interval if key exists
    this.clearInterval(key);

    // Set new interval
    const intervalId = setInterval(callback, delay);
    this.intervals.set(key, intervalId);

    return key;
  },

  /**
   * Clear a specific interval
   */
  clearInterval(key) {
    if (this.intervals.has(key)) {
      clearInterval(this.intervals.get(key));
      this.intervals.delete(key);
      return true;
    }
    return false;
  },

  /**
   * Clear all intervals
   */
  clearAll() {
    for (const [key, intervalId] of this.intervals) {
      clearInterval(intervalId);
    }
    this.intervals.clear();
  },

  /**
   * Get list of active intervals
   */
  getActiveIntervals() {
    return Array.from(this.intervals.keys());
  },

  /**
   * Check if an interval exists
   */
  hasInterval(key) {
    return this.intervals.has(key);
  },
};

/**
 * Debounce utility for limiting function calls
 */
function debounce(func, wait, immediate = false) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      timeout = null;
      if (!immediate) func(...args);
    };
    const callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func(...args);
  };
}

/**
 * Throttle utility for limiting function calls
 */
function throttle(func, limit) {
  let inThrottle;
  return function () {
    const args = arguments;
    const context = this;
    if (!inThrottle) {
      func.apply(context, args);
      inThrottle = true;
      setTimeout(() => (inThrottle = false), limit);
    }
  };
}

// Cleanup on page unload
window.addEventListener("beforeunload", () => {
  IntervalManager.clearAll();
});

// Export for global use
window.IntervalManager = IntervalManager;
window.debounce = debounce;
window.throttle = throttle;
