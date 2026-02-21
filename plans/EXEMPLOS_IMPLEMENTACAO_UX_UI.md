# Exemplos Práticos de Implementação - Melhorias UX/UI

Este documento contém exemplos de código e implementações específicas para as melhorias sugeridas.

---

## 🎯 1. Hotsite Conference - Cards de Lotes Modernos

### Antes (Tabela)
```html
<table class="table table-hover">
    <tr>
        <td>Lote 1</td>
        <td>R$ 100,00</td>
        <td><input type="number" /></td>
    </tr>
</table>
```

### Depois (Cards Modernos)
```html
<div class="lotes-container">
    @foreach ($event->lotesAtivosHoje() as $lote)
        <div class="lote-card {{ $lote->isPopular ? 'popular' : '' }} {{ $lote->isSoldOut ? 'sold-out' : '' }}">
            <div class="lote-badge">
                @if($lote->isPopular)
                    <span class="badge badge-popular">⭐ Popular</span>
                @endif
                @if($lote->remaining <= 10 && $lote->remaining > 0)
                    <span class="badge badge-warning">⚠️ Últimas {{ $lote->remaining }} vagas</span>
                @endif
                @if($lote->isSoldOut)
                    <span class="badge badge-danger">Esgotado</span>
                @endif
            </div>
            
            <div class="lote-header">
                <h3 class="lote-name">{{ $lote->name }}</h3>
                @if($lote->description)
                    <p class="lote-description">{{ $lote->description }}</p>
                @endif
            </div>
            
            <div class="lote-pricing">
                <div class="price-main">
                    <span class="currency">R$</span>
                    <span class="amount">{{ number_format($lote->value, 2, ',', '.') }}</span>
                </div>
                @if($lote->type == 0)
                    <small class="tax-info">+ taxa de R$ {{ number_format($lote->value * 0.1, 2, ',', '.') }}</small>
                @endif
            </div>
            
            <div class="lote-progress">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ ($lote->sold / $lote->limit_max) * 100 }}%"></div>
                </div>
                <small class="progress-text">{{ $lote->sold }} de {{ $lote->limit_max }} vendidos</small>
            </div>
            
            <div class="lote-quantity">
                <label>Quantidade:</label>
                <div class="quantity-selector">
                    <button class="qty-btn minus" onclick="decreaseQty('{{ $lote->hash }}')">-</button>
                    <input type="number" 
                           class="qty-input" 
                           id="qty-{{ $lote->hash }}"
                           value="0" 
                           min="0" 
                           max="{{ $lote->limit_max }}"
                           data-lote-hash="{{ $lote->hash }}"
                           data-min="{{ $lote->limit_min }}"
                           data-max="{{ $lote->limit_max }}"
                           data-value="{{ $lote->value }}">
                    <button class="qty-btn plus" onclick="increaseQty('{{ $lote->hash }}')">+</button>
                </div>
                <div class="quick-select">
                    <button class="quick-btn" onclick="setQuickQty('{{ $lote->hash }}', 1)">1</button>
                    <button class="quick-btn" onclick="setQuickQty('{{ $lote->hash }}', 2)">2</button>
                    <button class="quick-btn" onclick="setQuickQty('{{ $lote->hash }}', 3)">3</button>
                    <button class="quick-btn" onclick="setQuickQty('{{ $lote->hash }}', 4)">4+</button>
                </div>
            </div>
            
            <div class="lote-footer">
                <small class="lote-deadline">
                    @if ($event->max_event_dates() >= \Carbon\Carbon::now())
                        Disponível até
                    @else
                        Finalizado em
                    @endif
                    {{ \Carbon\Carbon::parse($lote->datetime_end)->format('d/m/y \à\s H:i') }}
                </small>
            </div>
        </div>
    @endforeach
</div>
```

### CSS Correspondente
```css
.lotes-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.lote-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    border: 2px solid transparent;
}

.lote-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    border-color: var(--primary-color);
}

.lote-card.popular {
    border-color: #ffd700;
    background: linear-gradient(135deg, #fff 0%, #fffef0 100%);
}

.lote-card.sold-out {
    opacity: 0.6;
    pointer-events: none;
}

.lote-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
}

.badge-popular {
    background: linear-gradient(135deg, #ffd700, #ffed4e);
    color: #333;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.lote-pricing {
    margin: 1rem 0;
}

.price-main {
    display: flex;
    align-items: baseline;
    gap: 0.25rem;
}

.currency {
    font-size: 1rem;
    color: #666;
}

.amount {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
}

.lote-progress {
    margin: 1rem 0;
}

.progress-bar {
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    transition: width 0.3s ease;
}

.quantity-selector {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 1rem 0;
}

.qty-btn {
    width: 40px;
    height: 40px;
    border: 2px solid #ddd;
    background: white;
    border-radius: 8px;
    font-size: 1.25rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.qty-btn:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.qty-input {
    width: 80px;
    height: 40px;
    text-align: center;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
}

.quick-select {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.quick-btn {
    flex: 1;
    padding: 0.5rem;
    border: 1px solid #ddd;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.quick-btn:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}
```

---

## 📊 2. Dashboard - Gráficos de Vendas

### HTML
```html
<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-content">
            <h3>{{ count($ingressos_confirmados) }}</h3>
            <p>Ingressos Confirmados</p>
            <small class="stat-change positive">+12% vs mês anterior</small>
        </div>
    </div>
    
    <div class="chart-container">
        <canvas id="salesChart"></canvas>
    </div>
</div>
```

### JavaScript (Chart.js)
```javascript
// resources/js/dashboard-charts.js
import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart');
    if (!ctx) return;
    
    const salesData = @json($salesData); // Dados do backend
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: salesData.labels,
            datasets: [{
                label: 'Vendas Confirmadas',
                data: salesData.confirmed,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Vendas Pendentes',
                data: salesData.pending,
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    }
                }
            }
        }
    });
});
```

---

## 🎨 3. Wizard de Criação de Eventos

### HTML
```html
<div class="wizard-container">
    <div class="wizard-progress">
        <div class="progress-bar" style="width: {{ $progress }}%"></div>
    </div>
    
    <div class="wizard-steps">
        <div class="step {{ $currentStep >= 1 ? 'active' : '' }} {{ $currentStep > 1 ? 'completed' : '' }}">
            <div class="step-number">1</div>
            <div class="step-label">Informações</div>
        </div>
        <div class="step {{ $currentStep >= 2 ? 'active' : '' }} {{ $currentStep > 2 ? 'completed' : '' }}">
            <div class="step-number">2</div>
            <div class="step-label">Inscrições</div>
        </div>
        <div class="step {{ $currentStep >= 3 ? 'active' : '' }} {{ $currentStep > 3 ? 'completed' : '' }}">
            <div class="step-number">3</div>
            <div class="step-label">Cupons</div>
        </div>
        <div class="step {{ $currentStep >= 4 ? 'active' : '' }}">
            <div class="step-number">4</div>
            <div class="step-label">Publicar</div>
        </div>
    </div>
    
    <div class="wizard-content">
        <!-- Conteúdo do step atual -->
    </div>
    
    <div class="wizard-actions">
        @if($currentStep > 1)
            <button type="button" class="btn btn-secondary" onclick="previousStep()">Anterior</button>
        @endif
        @if($currentStep < 4)
            <button type="button" class="btn btn-primary" onclick="nextStep()">Próximo</button>
        @else
            <button type="submit" class="btn btn-success">Publicar Evento</button>
        @endif
    </div>
</div>
```

### CSS
```css
.wizard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.wizard-progress {
    height: 4px;
    background: #e9ecef;
    border-radius: 2px;
    margin-bottom: 2rem;
    position: relative;
}

.wizard-progress .progress-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    border-radius: 2px;
    transition: width 0.3s ease;
}

.wizard-steps {
    display: flex;
    justify-content: space-between;
    margin-bottom: 3rem;
    position: relative;
}

.wizard-steps::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 2px;
    background: #e9ecef;
    z-index: 0;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 1;
    flex: 1;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    color: #666;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
}

.step.active .step-number {
    background: var(--primary-color);
    color: white;
    transform: scale(1.1);
    box-shadow: 0 0 0 4px rgba(var(--primary-color-rgb), 0.2);
}

.step.completed .step-number {
    background: #28a745;
    color: white;
}

.step.completed .step-number::after {
    content: '✓';
    font-size: 1.2rem;
}

.step-label {
    font-size: 0.875rem;
    color: #666;
    text-align: center;
}

.step.active .step-label {
    color: var(--primary-color);
    font-weight: 600;
}
```

---

## ⏰ 4. Contador Regressivo (Countdown)

### HTML
```html
<div class="countdown-container">
    <h3>O evento começa em:</h3>
    <div class="countdown" id="countdown" data-date="{{ $event->min_event_dates() }} {{ $event->min_event_time() }}">
        <div class="countdown-item">
            <span class="countdown-value" id="days">00</span>
            <span class="countdown-label">Dias</span>
        </div>
        <div class="countdown-item">
            <span class="countdown-value" id="hours">00</span>
            <span class="countdown-label">Horas</span>
        </div>
        <div class="countdown-item">
            <span class="countdown-value" id="minutes">00</span>
            <span class="countdown-label">Minutos</span>
        </div>
        <div class="countdown-item">
            <span class="countdown-value" id="seconds">00</span>
            <span class="countdown-label">Segundos</span>
        </div>
    </div>
</div>
```

### JavaScript
```javascript
function initCountdown() {
    const countdownEl = document.getElementById('countdown');
    if (!countdownEl) return;
    
    const eventDate = new Date(countdownEl.dataset.date).getTime();
    
    const timer = setInterval(function() {
        const now = new Date().getTime();
        const distance = eventDate - now;
        
        if (distance < 0) {
            clearInterval(timer);
            countdownEl.innerHTML = '<div class="countdown-expired">O evento já começou!</div>';
            return;
        }
        
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        document.getElementById('days').textContent = String(days).padStart(2, '0');
        document.getElementById('hours').textContent = String(hours).padStart(2, '0');
        document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
    }, 1000);
}

document.addEventListener('DOMContentLoaded', initCountdown);
```

### CSS
```css
.countdown-container {
    text-align: center;
    padding: 2rem;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    border-radius: 12px;
    margin: 2rem 0;
}

.countdown {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-top: 1rem;
}

.countdown-item {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.countdown-value {
    font-size: 3rem;
    font-weight: 700;
    line-height: 1;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}

.countdown-label {
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 0.5rem;
    opacity: 0.9;
}

@media (max-width: 768px) {
    .countdown {
        gap: 1rem;
    }
    
    .countdown-value {
        font-size: 2rem;
    }
}
```

---

## 📱 5. Sticky CTA (Call-to-Action Fixo)

### HTML
```html
<div class="sticky-cta" id="stickyCTA">
    <div class="container">
        <div class="sticky-cta-content">
            <div class="sticky-cta-info">
                <span class="sticky-cta-label">A partir de</span>
                <span class="sticky-cta-price">R$ {{ $event->minPrice() }}</span>
            </div>
            <button class="btn btn-primary btn-lg" onclick="scrollToInscricoes()">
                Inscreva-se Agora
            </button>
        </div>
    </div>
</div>
```

### CSS
```css
.sticky-cta {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    box-shadow: 0 -4px 12px rgba(0,0,0,0.1);
    padding: 1rem 0;
    z-index: 1000;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.sticky-cta.visible {
    transform: translateY(0);
}

.sticky-cta-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.sticky-cta-info {
    display: flex;
    flex-direction: column;
}

.sticky-cta-label {
    font-size: 0.875rem;
    color: #666;
}

.sticky-cta-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color);
}

@media (max-width: 768px) {
    .sticky-cta-content {
        flex-direction: column;
    }
    
    .sticky-cta .btn {
        width: 100%;
    }
}
```

### JavaScript
```javascript
window.addEventListener('scroll', function() {
    const stickyCTA = document.getElementById('stickyCTA');
    const inscricoesSection = document.getElementById('inscricoes');
    
    if (!stickyCTA || !inscricoesSection) return;
    
    const inscricoesTop = inscricoesSection.offsetTop;
    const scrollPosition = window.scrollY + window.innerHeight;
    
    if (scrollPosition > inscricoesTop) {
        stickyCTA.classList.add('visible');
    } else {
        stickyCTA.classList.remove('visible');
    }
});

function scrollToInscricoes() {
    const inscricoesSection = document.getElementById('inscricoes');
    if (inscricoesSection) {
        inscricoesSection.scrollIntoView({ behavior: 'smooth' });
    }
}
```

---

## 🎯 6. Toast Notifications

### HTML (Adicionar no layout)
```html
<div id="toast-container" class="toast-container"></div>
```

### JavaScript
```javascript
function showToast(message, type = 'info', duration = 3000) {
    const container = document.getElementById('toast-container');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-icon">
            <i class="fas fa-${getIconForType(type)}"></i>
        </div>
        <div class="toast-message">${message}</div>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    // Animar entrada
    setTimeout(() => toast.classList.add('show'), 10);
    
    // Remover após duração
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

function getIconForType(type) {
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };
    return icons[type] || 'info-circle';
}
```

### CSS
```css
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.toast {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: white;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    min-width: 300px;
    max-width: 500px;
    opacity: 0;
    transform: translateX(400px);
    transition: all 0.3s ease;
}

.toast.show {
    opacity: 1;
    transform: translateX(0);
}

.toast-success {
    border-left: 4px solid #28a745;
}

.toast-error {
    border-left: 4px solid #dc3545;
}

.toast-warning {
    border-left: 4px solid #ffc107;
}

.toast-info {
    border-left: 4px solid #17a2b8;
}

.toast-icon {
    font-size: 1.5rem;
}

.toast-success .toast-icon {
    color: #28a745;
}

.toast-error .toast-icon {
    color: #dc3545;
}

.toast-warning .toast-icon {
    color: #ffc107;
}

.toast-info .toast-icon {
    color: #17a2b8;
}

.toast-message {
    flex: 1;
}

.toast-close {
    background: none;
    border: none;
    cursor: pointer;
    color: #666;
    padding: 0.25rem;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.toast-close:hover {
    opacity: 1;
}
```

---

## 📝 Notas Finais

Estes são exemplos práticos que podem ser adaptados ao seu projeto. Lembre-se de:

1. **Testar em diferentes dispositivos** antes de implementar
2. **Manter consistência** com o design system existente
3. **Otimizar performance** (lazy loading, debounce, etc.)
4. **Considerar acessibilidade** (ARIA labels, contraste, etc.)
5. **Coletar feedback** dos usuários após implementação

Para implementações mais complexas, considere usar bibliotecas especializadas como:
- **Alpine.js** para interatividade simples
- **Vue.js** ou **React** para componentes mais complexos
- **Tailwind CSS** para estilização rápida

