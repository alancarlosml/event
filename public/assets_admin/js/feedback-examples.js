/**
 * Exemplos práticos de uso do Sistema de Feedback Visual
 * Este arquivo demonstra como usar as funcionalidades implementadas
 */

// Exemplo 1: Validação de formulário com feedback visual
function validateFormExample() {
    const form = document.getElementById('example-form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Mostra loading no botão
    setButtonLoading(submitBtn, 'Validando...');
    
    // Simula validação
    setTimeout(() => {
        const email = form.querySelector('#email').value;
        
        if (email.includes('@')) {
            showToast('Formulário válido!', 'success');
            resetButton(submitBtn);
        } else {
            showToast('Email inválido!', 'error');
            resetButton(submitBtn);
        }
    }, 2000);
}

// Exemplo 2: Upload de arquivo com progresso
function uploadFileExample() {
    const overlay = showProgressOverlay('Fazendo upload do arquivo...', 0);
    let progress = 0;
    
    const interval = setInterval(() => {
        progress += 10;
        updateProgress(overlay, progress, `${progress}% concluído`);
        
        if (progress >= 100) {
            clearInterval(interval);
            hideProgressOverlay(overlay);
            showToast('Upload concluído com sucesso!', 'success');
        }
    }, 500);
}

// Exemplo 3: Operação em lote com feedback
function batchOperationExample() {
    const items = ['Item 1', 'Item 2', 'Item 3', 'Item 4', 'Item 5'];
    const overlay = showProgressOverlay('Processando itens...', 0);
    
    items.forEach((item, index) => {
        setTimeout(() => {
            const progress = ((index + 1) / items.length) * 100;
            updateProgress(overlay, progress, `Processando ${item}...`);
            
            if (index === items.length - 1) {
                setTimeout(() => {
                    hideProgressOverlay(overlay);
                    showToast(`${items.length} itens processados com sucesso!`, 'success');
                }, 500);
            }
        }, index * 1000);
    });
}

// Exemplo 4: Validação de campo em tempo real
function realTimeValidationExample() {
    const input = document.getElementById('username');
    
    input.addEventListener('input', function() {
        const value = this.value;
        
        if (value.length < 3) {
            this.classList.remove('input-success', 'input-error');
            return;
        }
        
        // Simula verificação AJAX
        setTimeout(() => {
            if (value.length >= 6) {
                this.classList.remove('input-error');
                this.classList.add('input-success');
                showToast('Nome de usuário disponível!', 'success', null, 2000);
            } else {
                this.classList.remove('input-success');
                this.classList.add('input-error');
                showToast('Nome de usuário muito curto', 'warning', null, 2000);
            }
        }, 1000);
    });
}

// Exemplo 5: Confirmação antes de ação destrutiva
function deleteWithConfirmationExample() {
    if (confirm('Tem certeza que deseja excluir este item?')) {
        const button = document.getElementById('delete-btn');
        setButtonLoading(button, 'Excluindo...');
        
        // Simula operação de exclusão
        setTimeout(() => {
            resetButton(button);
            showToast('Item excluído com sucesso!', 'success');
            
            // Remove elemento da interface
            const element = document.getElementById('item-to-delete');
            if (element) {
                element.style.opacity = '0.5';
                setTimeout(() => {
                    element.remove();
                }, 300);
            }
        }, 2000);
    }
}

// Exemplo 6: Carregamento de dados com feedback
function loadDataExample() {
    const container = document.getElementById('data-container');
    asyncFeedback.addSectionLoading(container);
    
    // Simula carregamento de dados
    setTimeout(() => {
        asyncFeedback.removeSectionLoading(container);
        container.innerHTML = '<p>Dados carregados com sucesso!</p>';
        showToast('Dados atualizados!', 'info');
    }, 3000);
}

// Exemplo 7: Múltiplas notificações
function multipleNotificationsExample() {
    showToast('Iniciando processo...', 'info', null, 2000);
    
    setTimeout(() => {
        showToast('Processando dados...', 'info', null, 2000);
    }, 1000);
    
    setTimeout(() => {
        showToast('Processo concluído!', 'success');
    }, 3000);
}

// Exemplo 8: Validação de formulário completo
function completeFormValidationExample() {
    const form = document.getElementById('complete-form');
    const fields = form.querySelectorAll('input[required]');
    let isValid = true;
    
    fields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('input-error');
            isValid = false;
        } else {
            field.classList.remove('input-error');
            field.classList.add('input-success');
        }
    });
    
    if (isValid) {
        const submitBtn = form.querySelector('button[type="submit"]');
        setButtonLoading(submitBtn, 'Enviando...');
        
        setTimeout(() => {
            resetButton(submitBtn);
            showToast('Formulário enviado com sucesso!', 'success');
        }, 2000);
    } else {
        showToast('Por favor, preencha todos os campos obrigatórios', 'error');
    }
}

// Exemplo 9: Operação com retry
function operationWithRetryExample() {
    let attempts = 0;
    const maxAttempts = 3;
    
    function attemptOperation() {
        attempts++;
        showToast(`Tentativa ${attempts} de ${maxAttempts}...`, 'info', null, 2000);
        
        // Simula operação que pode falhar
        const success = Math.random() > 0.5;
        
        if (success) {
            showToast('Operação realizada com sucesso!', 'success');
        } else if (attempts < maxAttempts) {
            setTimeout(() => {
                attemptOperation();
            }, 2000);
        } else {
            showToast('Operação falhou após todas as tentativas', 'error');
        }
    }
    
    attemptOperation();
}

// Exemplo 10: Feedback para DataTable
function dataTableFeedbackExample() {
    const table = $('#example-table').DataTable();
    
    // Configura feedback personalizado
    table.on('processing.dt', (e, settings, processing) => {
        if (processing) {
            showToast('Carregando dados da tabela...', 'info');
        }
    });
    
    table.on('draw.dt', () => {
        showToast('Tabela atualizada!', 'success', null, 1500);
    });
}

// Função para inicializar exemplos
function initFeedbackExamples() {
    console.log('Sistema de Feedback Visual carregado!');
    console.log('Funções disponíveis:');
    console.log('- validateFormExample()');
    console.log('- uploadFileExample()');
    console.log('- batchOperationExample()');
    console.log('- realTimeValidationExample()');
    console.log('- deleteWithConfirmationExample()');
    console.log('- loadDataExample()');
    console.log('- multipleNotificationsExample()');
    console.log('- completeFormValidationExample()');
    console.log('- operationWithRetryExample()');
    console.log('- dataTableFeedbackExample()');
}

// Inicializa quando o documento estiver pronto
$(document).ready(() => {
    initFeedbackExamples();
}); 