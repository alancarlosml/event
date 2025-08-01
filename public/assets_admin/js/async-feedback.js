/**
 * Sistema de Feedback Visual para Ações Assíncronas
 * Gerencia notificações toast, estados de loading e feedback visual
 */

class AsyncFeedback {
    constructor() {
        this.toastContainer = null;
        this.init();
    }

    init() {
        this.createToastContainer();
        this.setupGlobalAjaxHandlers();
        this.setupFormHandlers();
    }

    /**
     * Cria o container para notificações toast
     */
    createToastContainer() {
        if (!document.getElementById('toast-container')) {
            this.toastContainer = document.createElement('div');
            this.toastContainer.id = 'toast-container';
            this.toastContainer.className = 'toast-container';
            document.body.appendChild(this.toastContainer);
        } else {
            this.toastContainer = document.getElementById('toast-container');
        }
    }

    /**
     * Configura handlers globais para AJAX
     */
    setupGlobalAjaxHandlers() {
        // Intercepta todas as requisições AJAX do jQuery
        $(document).ajaxSend((event, xhr, settings) => {
            this.showRequestLoading(settings);
        });

        $(document).ajaxComplete((event, xhr, settings) => {
            this.hideRequestLoading(settings);
        });

        $(document).ajaxError((event, xhr, settings, error) => {
            this.handleAjaxError(xhr, settings, error);
        });

        $(document).ajaxSuccess((event, xhr, settings) => {
            this.handleAjaxSuccess(xhr, settings);
        });
    }

    /**
     * Configura handlers para formulários
     */
    setupFormHandlers() {
        $(document).on('submit', 'form', (e) => {
            this.handleFormSubmit(e);
        });

        // Intercepta cliques em botões de submit
        $(document).on('click', 'button[type="submit"], input[type="submit"]', (e) => {
            this.handleButtonClick(e);
        });
    }

    /**
     * Mostra notificação toast
     */
    showToast(message, type = 'info', title = null, duration = 5000) {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        const iconMap = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };

        const titleText = title || this.getDefaultTitle(type);
        
        toast.innerHTML = `
            <div class="toast-header">
                <h6 class="toast-title">
                    <span style="margin-right: 8px;">${iconMap[type]}</span>
                    ${titleText}
                </h6>
                <button type="button" class="toast-close" onclick="this.parentElement.parentElement.remove()">
                    ×
                </button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;

        this.toastContainer.appendChild(toast);

        // Auto-remove após duração especificada
        if (duration > 0) {
            setTimeout(() => {
                this.removeToast(toast);
            }, duration);
        }

        return toast;
    }

    /**
     * Remove notificação toast
     */
    removeToast(toast) {
        toast.classList.add('fade-out');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }

    /**
     * Obtém título padrão baseado no tipo
     */
    getDefaultTitle(type) {
        const titles = {
            success: 'Sucesso',
            error: 'Erro',
            warning: 'Atenção',
            info: 'Informação'
        };
        return titles[type] || 'Notificação';
    }

    /**
     * Mostra loading para requisição AJAX
     */
    showRequestLoading(settings) {
        const target = this.getTargetElement(settings);
        if (target) {
            target.classList.add('input-loading');
        }
    }

    /**
     * Esconde loading para requisição AJAX
     */
    hideRequestLoading(settings) {
        const target = this.getTargetElement(settings);
        if (target) {
            target.classList.remove('input-loading');
        }
    }

    /**
     * Obtém elemento alvo baseado na configuração AJAX
     */
    getTargetElement(settings) {
        if (settings.context) {
            return settings.context;
        }
        
        // Tenta encontrar elemento baseado na URL
        const url = settings.url;
        if (url.includes('check_slug')) {
            return document.getElementById('slug');
        } else if (url.includes('create_slug')) {
            return document.getElementById('slug');
        } else if (url.includes('get_areas_by_category')) {
            return document.getElementById('area_id');
        } else if (url.includes('get_city')) {
            return document.getElementById('city');
        }
        
        return null;
    }

    /**
     * Manipula erros AJAX
     */
    handleAjaxError(xhr, settings, error) {
        let message = 'Ocorreu um erro inesperado.';
        
        if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
        } else if (xhr.status === 422) {
            message = 'Dados inválidos. Verifique as informações fornecidas.';
        } else if (xhr.status === 404) {
            message = 'Recurso não encontrado.';
        } else if (xhr.status === 500) {
            message = 'Erro interno do servidor.';
        }

        this.showToast(message, 'error');
    }

    /**
     * Manipula sucessos AJAX
     */
    handleAjaxSuccess(xhr, settings) {
        // Para validações de slug, mostra feedback visual
        if (settings.url.includes('check_slug') || settings.url.includes('create_slug')) {
            const response = xhr.responseJSON;
            const slugInput = document.getElementById('slug');
            
            if (slugInput) {
                slugInput.classList.remove('input-loading');
                
                if (response.slug_exists == '1') {
                    slugInput.classList.remove('input-success');
                    slugInput.classList.add('input-error');
                    this.showToast('Este slug já está em uso. Escolha outro.', 'warning');
                } else {
                    slugInput.classList.remove('input-error');
                    slugInput.classList.add('input-success');
                }
            }
        }
    }

    /**
     * Manipula envio de formulários
     */
    handleFormSubmit(e) {
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
        
        if (submitBtn) {
            this.setButtonLoading(submitBtn, 'Enviando...');
        }
        
        form.classList.add('form-submitting');
    }

    /**
     * Manipula cliques em botões
     */
    handleButtonClick(e) {
        const button = e.target;
        const action = button.getAttribute('data-action');
        
        if (action === 'delete' || button.classList.contains('btn-delete')) {
            if (!confirm('Tem certeza que deseja excluir este item?')) {
                e.preventDefault();
                return;
            }
            this.setButtonLoading(button, 'Excluindo...');
        } else if (action === 'save' || button.classList.contains('btn-save')) {
            this.setButtonLoading(button, 'Salvando...');
        }
    }

    /**
     * Define estado de loading para botão
     */
    setButtonLoading(button, loadingText = 'Processando...') {
        const originalText = button.innerHTML;
        const originalDisabled = button.disabled;
        
        button.disabled = true;
        button.classList.add('btn-loading');
        button.innerHTML = `
            <span class="spinner-border" role="status" aria-hidden="true"></span>
            <span class="btn-loading-text">${loadingText}</span>
            <span class="btn-text" style="display: none;">${originalText}</span>
        `;
        
        // Restaura após um tempo (fallback)
        setTimeout(() => {
            this.resetButton(button, originalText, originalDisabled);
        }, 30000);
        
        // Armazena dados para restaurar depois
        button.setAttribute('data-original-text', originalText);
        button.setAttribute('data-original-disabled', originalDisabled);
    }

    /**
     * Reseta estado do botão
     */
    resetButton(button, originalText = null, originalDisabled = false) {
        if (!originalText) {
            originalText = button.getAttribute('data-original-text') || button.innerHTML;
        }
        if (originalDisabled === false) {
            originalDisabled = button.getAttribute('data-original-disabled') === 'true';
        }
        
        button.disabled = originalDisabled;
        button.classList.remove('btn-loading');
        button.innerHTML = originalText;
    }

    /**
     * Mostra overlay de progresso para operações longas
     */
    showProgressOverlay(title = 'Processando...', progress = 0) {
        const overlay = document.createElement('div');
        overlay.className = 'progress-overlay';
        overlay.innerHTML = `
            <div class="progress-modal">
                <h5>${title}</h5>
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: ${progress}%" 
                         aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="progress-text">${progress}% concluído</div>
            </div>
        `;
        
        document.body.appendChild(overlay);
        return overlay;
    }

    /**
     * Atualiza progresso do overlay
     */
    updateProgress(overlay, progress, text = null) {
        const progressBar = overlay.querySelector('.progress-bar');
        const progressText = overlay.querySelector('.progress-text');
        
        if (progressBar) {
            progressBar.style.width = `${progress}%`;
            progressBar.setAttribute('aria-valuenow', progress);
        }
        
        if (progressText) {
            progressText.textContent = text || `${progress}% concluído`;
        }
    }

    /**
     * Remove overlay de progresso
     */
    hideProgressOverlay(overlay) {
        if (overlay && overlay.parentNode) {
            overlay.parentNode.removeChild(overlay);
        }
    }

    /**
     * Adiciona loading a uma seção específica
     */
    addSectionLoading(element) {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }
        
        if (element) {
            element.classList.add('section-loading');
        }
    }

    /**
     * Remove loading de uma seção específica
     */
    removeSectionLoading(element) {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }
        
        if (element) {
            element.classList.remove('section-loading');
        }
    }

    /**
     * Configura DataTable com feedback visual
     */
    setupDataTableFeedback(table) {
        if (table && table.DataTable) {
            const dt = table.DataTable();
            
            // Adiciona loading personalizado
            dt.on('processing.dt', (e, settings, processing) => {
                if (processing) {
                    this.addSectionLoading(table);
                } else {
                    this.removeSectionLoading(table);
                }
            });
        }
    }

    /**
     * Configura autocomplete com feedback visual
     */
    setupAutocompleteFeedback(element) {
        if (element && element.autocomplete) {
            element.autocomplete('option', 'beforeSend', () => {
                element.classList.add('ui-autocomplete-loading');
            });
            
            element.autocomplete('option', 'complete', () => {
                element.classList.remove('ui-autocomplete-loading');
            });
        }
    }
}

// Inicializa o sistema de feedback
const asyncFeedback = new AsyncFeedback();

// Funções globais para uso direto
window.showToast = (message, type, title, duration) => {
    return asyncFeedback.showToast(message, type, title, duration);
};

window.setButtonLoading = (button, text) => {
    return asyncFeedback.setButtonLoading(button, text);
};

window.resetButton = (button) => {
    return asyncFeedback.resetButton(button);
};

window.showProgressOverlay = (title, progress) => {
    return asyncFeedback.showProgressOverlay(title, progress);
};

window.updateProgress = (overlay, progress, text) => {
    return asyncFeedback.updateProgress(overlay, progress, text);
};

window.hideProgressOverlay = (overlay) => {
    return asyncFeedback.hideProgressOverlay(overlay);
};

// Configuração automática para DataTables existentes
$(document).ready(() => {
    $('.dataTable').each((index, table) => {
        asyncFeedback.setupDataTableFeedback($(table));
    });
    
    // Configuração para autocomplete
    $('input[data-autocomplete]').each((index, input) => {
        asyncFeedback.setupAutocompleteFeedback($(input));
    });
}); 