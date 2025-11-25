// UI Utilities for Student Attendance System

// Loading Spinner
const showSpinner = (targetElement) => {
    const spinner = document.createElement('div');
    spinner.className = 'spinner-container';
    spinner.innerHTML = `
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    `;
    targetElement.appendChild(spinner);
};

const hideSpinner = (targetElement) => {
    const spinner = targetElement.querySelector('.spinner-container');
    if (spinner) spinner.remove();
};

// Toast Notifications
const showToast = (message, type = 'success') => {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove toast after it's hidden
    toast.addEventListener('hidden.bs.toast', () => toast.remove());
};

const createToastContainer = () => {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    document.body.appendChild(container);
    return container;
};

// Confirmation Dialogs
const confirmAction = (message, onConfirm) => {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.setAttribute('tabindex', '-1');
    modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><i class="fas fa-exclamation-triangle text-warning me-2"></i>${message}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmBtn">Confirm</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const modalInstance = new bootstrap.Modal(modal);

    // Ensure aria-hidden is removed when modal is shown
    modal.addEventListener('shown.bs.modal', () => {
        modal.removeAttribute('aria-hidden');
    });

    modalInstance.show();
    
    modal.querySelector('#confirmBtn').addEventListener('click', () => {
        onConfirm();
        modalInstance.hide();
    });
    
    modal.addEventListener('hidden.bs.modal', () => {
        modal.remove();
    });
};

// AJAX Request Wrapper with Loading Spinner
const ajaxRequest = async (url, options = {}) => {
    const targetElement = options.targetElement || document.body;
    showSpinner(targetElement);
    
    try {
        const response = await fetch(url, options);
        if (!response.ok) throw new Error('Network response was not ok');
        const data = await response.json();
        return data;
    } catch (error) {
        showToast(error.message, 'danger');
        throw error;
    } finally {
        hideSpinner(targetElement);
    }
};

// Initialize Tooltips
const initTooltips = () => {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
};

// Progress Indicator
class ProgressIndicator {
    constructor(steps, currentStep = 1) {
        this.totalSteps = steps;
        this.currentStep = currentStep;
        this.element = this.createProgressElement();
    }
    
    createProgressElement() {
        const container = document.createElement('div');
        container.className = 'progress-indicator mb-4';
        
        const progressBar = document.createElement('div');
        progressBar.className = 'progress';
        progressBar.style.height = '2px';
        
        const progress = document.createElement('div');
        progress.className = 'progress-bar';
        progress.style.width = `${(this.currentStep - 1) / (this.totalSteps - 1) * 100}%`;
        
        progressBar.appendChild(progress);
        container.appendChild(progressBar);
        
        const stepsContainer = document.createElement('div');
        stepsContainer.className = 'd-flex justify-content-between mt-1';
        
        for (let i = 1; i <= this.totalSteps; i++) {
            const step = document.createElement('div');
            step.className = `progress-step ${i <= this.currentStep ? 'active' : ''}`;
            step.innerHTML = `
                <div class="step-indicator">
                    <i class="fas fa-circle"></i>
                </div>
                <div class="step-label">Step ${i}</div>
            `;
            stepsContainer.appendChild(step);
        }
        
        container.appendChild(stepsContainer);
        return container;
    }
    
    updateProgress(step) {
        this.currentStep = step;
        const progress = this.element.querySelector('.progress-bar');
        progress.style.width = `${(step - 1) / (this.totalSteps - 1) * 100}%`;
        
        const steps = this.element.querySelectorAll('.progress-step');
        steps.forEach((s, index) => {
            if (index + 1 <= step) {
                s.classList.add('active');
            } else {
                s.classList.remove('active');
            }
        });
    }
    
    attachTo(container) {
        container.appendChild(this.element);
    }
}

// Breadcrumb Navigation
const updateBreadcrumb = (items) => {
    const nav = document.createElement('nav');
    nav.setAttribute('aria-label', 'breadcrumb');
    
    const ol = document.createElement('ol');
    ol.className = 'breadcrumb';
    
    items.forEach((item, index) => {
        const li = document.createElement('li');
        li.className = `breadcrumb-item${index === items.length - 1 ? ' active' : ''}`;
        if (index === items.length - 1) {
            li.setAttribute('aria-current', 'page');
            li.textContent = item.text;
        } else {
            const a = document.createElement('a');
            a.href = item.url;
            a.textContent = item.text;
            li.appendChild(a);
        }
        ol.appendChild(li);
    });
    
    nav.appendChild(ol);
    return nav;
};