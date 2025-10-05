/* Bootstrap 5.3.0 - Custom Build JS */
/* Modal functionality */
class Modal {
    constructor(element) {
        this._element = element;
        this._isShown = false;
        this._backdrop = null;
    }
    
    show() {
        if (this._isShown) return;
        this._isShown = true;
        this._element.style.display = "block";
        this._element.classList.add("show");
        document.body.classList.add("modal-open");
        this._createBackdrop();
    }
    
    hide() {
        if (!this._isShown) return;
        this._isShown = false;
        this._element.classList.remove("show");
        document.body.classList.remove("modal-open");
        this._removeBackdrop();
    }
    
    _createBackdrop() {
        this._backdrop = document.createElement("div");
        this._backdrop.className = "modal-backdrop fade show";
        document.body.appendChild(this._backdrop);
    }
    
    _removeBackdrop() {
        if (this._backdrop) {
            this._backdrop.remove();
            this._backdrop = null;
        }
    }
}

/* Dropdown functionality */
class Dropdown {
    constructor(element) {
        this._element = element;
        this._isShown = false;
    }
    
    show() {
        if (this._isShown) return;
        this._isShown = true;
        this._element.classList.add("show");
    }
    
    hide() {
        if (!this._isShown) return;
        this._isShown = false;
        this._element.classList.remove("show");
    }
    
    toggle() {
        if (this._isShown) {
            this.hide();
        } else {
            this.show();
        }
    }
}

/* Initialize Bootstrap components */
document.addEventListener("DOMContentLoaded", function() {
    // Modal initialization
    const modalElements = document.querySelectorAll(".modal");
    modalElements.forEach(element => {
        const modal = new Modal(element);
        const triggers = document.querySelectorAll(`[data-bs-toggle="modal"][data-bs-target="#${element.id}"]`);
        triggers.forEach(trigger => {
            trigger.addEventListener("click", () => modal.show());
        });
        
        const closeButtons = element.querySelectorAll("[data-bs-dismiss=\"modal\"]");
        closeButtons.forEach(button => {
            button.addEventListener("click", () => modal.hide());
        });
    });
    
    // Dropdown initialization
    const dropdownElements = document.querySelectorAll(".dropdown");
    dropdownElements.forEach(element => {
        const dropdown = new Dropdown(element.querySelector(".dropdown-menu"));
        const toggle = element.querySelector("[data-bs-toggle=\"dropdown\"]");
        if (toggle) {
            toggle.addEventListener("click", (e) => {
                e.preventDefault();
                dropdown.toggle();
            });
        }
    });
});

/* Bootstrap 5.3.0 - Custom Build JS Complete */