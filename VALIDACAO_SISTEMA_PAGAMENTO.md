# VALIDAÇÃO COMPLETA DO SISTEMA DE PAGAMENTO
## Sistema de Eventos - Mercado Pago (Checkout Transparente)

**Data da Análise:** 11/12/2025  
**Contexto:** Validação do processo de pagamento via Checkout Transparente do Mercado Pago

---

## 📋 RESUMO EXECUTIVO

O sistema utiliza **Checkout Transparente** do Mercado Pago com suporte a:
- ✅ Cartão de Crédito (com parcelamento)
- ✅ PIX (pagamento instantâneo)
- ⚠️ Boleto (implementação parcial)
- ✅ Sistema de Cupons de Desconto
- ✅ Webhooks para notificação de pagamento

---

## 🔍 ANÁLISE DETALHADA

### 1. ESTRUTURA DO SISTEMA DE PAGAMENTO

#### 1.1. Controllers Envolvidos
- **MercadoPagoController.php** - Gerencia webhooks e vinculação de contas
- **ConferenceController.php** - Processa pagamentos pelo Checkout Transparente

#### 1.2. Rotas
```php
// Webhooks do Mercado Pago
Route::post('/webhooks/mercado-pago/notification', 'MercadoPagoController@notification');

// Checkout Transparente
Route::post('{slug}/obrigado', 'ConferenceController@thanks'); // Processa pagamento
Route::post('{slug}/pagamento', 'ConferenceController@payment');  // Método legado

// Cupons
Route::post('/getCoupon', 'ConferenceController@getCoupon');
Route::delete('/{slug}/remover-cupom', 'ConferenceController@removeCoupon');
```

---

## ⚠️ PROBLEMAS IDENTIFICADOS

### 🔴 CRÍTICOS

#### 1. **WEBHOOK NÃO ESTÁ ATUALIZ ANDO O PEDIDO NO CHECKOUT TRANSPARENTE**
**Localização:** `MercadoPagoController@notification` (linha 310-316)

**Problema:**
```php
// Buscar o pedido correspondente primeiro
$order = DB::table('orders')
    ->where('gatway_hash', $paymentId)  // ❌ PROBLEMA!
    ->first();
```

**Causa Raiz:** No Checkout Transparente (ConferenceController@thanks), o `gatway_hash` NÃO é preenchido antes do webhook ser chamado. O pedido é criado com:
```php
DB::table('orders')->insert([
    'hash' => md5(...),
    'status' => 2,  // Pendente
    'gatway_hash' => null,  // ❌ NULL!
    'gatway_reference' => null,
    // ...
]);
```

O `gatway_hash` só é definido DEPOIS que o pagamento retorna, mas o webhook pode chegar ANTES.

**Impacto:** Webhooks de PIX e outros pagamentos instantâneos falham em encontrar o pedido.

**Solução:**
1. Usar `external_reference` para vincular webhook ao pedido
2. Salvar `order_id` no `external_reference` do Mercado Pago
3. Modificar o webhook para buscar por `external_reference`

---

#### 2. **FALTA DE ATUALIZAÇÃO DE STATUS EM PAGAMENTO CARTÃO/PIX/BOLETO**
**Localização:** `ConferenceController@thanks` (linhas 900-1400)

**Problema:** O código processa o pagamento, mas:
- ❌ Não atualiza `gatway_hash` com o `payment_id` antes de retornar
- ❌ Não vincula corretamente o pedido para que o webhook possa encontrá-lo
- ⚠️ Pagamentos aprovados síncronos (cartão) podem funcionar, mas assíncronos (PIX) dependem 100% do webhook

**Código Atual (processPixPayment - linha 1465):**
```php
private function processPixPayment($payment, $order_id, $total)
{
    // ... código de criação do pagamento PIX
    
    // ❌ NÃO VINCULA O payment_id AO ORDER
    return [
        'status' => 'pending',
        'pix' => [
            'qr_code' => $pixData['point_of_interaction']['transaction_data']['qr_code'],
            // ...
        ]
    ];
}
```

**Impacto:** 
- PIX pode não ser confirmado automaticamente
- Cliente paga, mas sistema não reconhece
- Webhook falha em encontrar o pedido

---

#### 3. **PROCESSAMENTO DE BOLETO ESTÁ BLOQUEADO**
**Localização:** `payment.blade.php` (linha 281)

**Problema:**
```html
<div class="payment-method-tab" data-method="ticket" id="ticket_tab" style="display: none;">
```

O boleto só aparece se:
```javascript
let maxDate = "{{ $event->max_event_dates() }} 00:00:00";
let now = new Date();
now.setDate(now.getDate() + 3);

if (now < maxDate) {
    $('#ticket_tab').show();
}
```

**Impacto:** Boleto só será exibido se houver 3+ dias até o evento, limitando uma opção de pagamento importante.

---

### 🟡 IMPORTANTES

#### 4. **SISTEMA DE CUPONS NÃO ESTÁ INTEGRADO AO PAGAMENTO**
**Localização:** `ConferenceController@thanks`

**Problema:** O cupom é armazenado na sessão (`$request->session()->get('coupon')`), mas:
- ⚠️ Não há validação se o cupom ainda é válido no momento do pagamento
- ⚠️ O desconto não é aplicado no valor enviado ao Mercado Pago
- ⚠️ Não há registro da utilização do cupom na tabela `orders_coupons`

**Código Esperado (ausente):**
```php
// Buscar cupom da sessão
$coupon = $request->session()->get('coupon');
$coupon_discount = $request->session()->get('coupon_discount', 0);

// Aplicar desconto ao total
$total_com_desconto = $total - $coupon_discount;

// Registrar uso do cupom
if ($coupon) {
    DB::table('orders_coupons')->insert([
        'order_id' => $order_id,
        'coupon_id' => $coupon[0]['id'],  // ❌ FALTA ID NO ARRAY DA SESSÃO
        'discount_value' => $coupon_discount
    ]);
}
```

**Impacto:** Cupons podem não estar sendo aplicados corretamente aos pagamentos.

---

#### 5. **VALIDAÇÃO DE CUPOM ESTÁ FUNCIONAL MAS INCOMPLETA**
**Localização:** `ConferenceController@getCoupon` (linha 505-594)

**Pontos Positivos:**
- ✅ Valida se o cupom existe
- ✅ Verifica data de validade
- ✅ Checa limite de uso
- ✅ Verifica se usuário já usou

**Pontos de Melhoria:**
- ⚠️ Cupom na sessão é array sem ID do cupom: `[['code' => ..., 'type' => ..., 'value' => ...]]`
- ⚠️ Falta ID do cupom para registrar uso posterior
- ⚠️ Não valida se cupom é válido para os lotes selecionados

---

#### 6. **FALTA DE TIMEOUT/VERIFICAÇÃO DE STATUS DO PAGAMENTO PIX**
**Localização:** `payment.blade.php` (linha 800-847)

**Problema:** Após gerar QR Code PIX:
- ❌ Não há polling para verificar se pagamento foi confirmado
- ❌ Cliente precisa aguardar email ou recarregar página manualmente
- ⚠️ Experiência do usuário comprometida

**Solução Esperada:**
```javascript
// Polling a cada 5 segundos para verificar status
function checkPixPaymentStatus(orderId) {
    setInterval(() => {
        fetch(`/check-payment-status/${orderId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'approved') {
                    showSuccess('Pagamento confirmado!');
                    window.location.href = '/painel/minhas-inscricoes';
                }
            });
    }, 5000);
}
```

---

### 🟢 PONTOS POSITIVOS

#### ✅ Implementações Corretas

1. **Webhook do Mercado Pago** (parcialmente)
   - ✅ Recebe notificações
   - ✅ Valida estrutura da notificação
   - ✅ Busca dados do pagamento
   - ✅ Mapeia status corretamente
   - ✅ Atualiza status do pedido
   - ✅ Gera ingressos automaticamente
   - ✅ Envia email de confirmação

2. **Processamento de Cartão de Crédito**
   - ✅ SDK do Mercado Pago implementado corretamente
   - ✅ Tokenização de cartão
   - ✅ Validação de campos
   - ✅ Suporte a parcelamento
   - ✅ Campos do MP (cardNumber, securityCode, expirationDate)

3. **Sistema de Cupons**
   - ✅ Validações de data
   - ✅ Verificação de limite de uso
   - ✅ Previne uso duplicado por usuário
   - ✅ Cálculo de desconto (percentual e valor fixo)

4. **Interface do Usuário**
   - ✅ Checkout Transparente bem implementado
   - ✅ Máscaras de CPF e CEP
   - ✅ Validação de campos em tempo real
   - ✅ Loading overlay
   - ✅ Mensagens de erro/sucesso

---

## 🔧 CORREÇÕES NECESSÁRIAS

### Prioridade ALTA (Críticas)

#### Correção 1: Webhook - Vinculação pelo external_reference

**Arquivo:** `ConferenceController@thanks`
```php
// Ao criar payment no Mercado Pago, passar order_id
$paymentData = [
    'transaction_amount' => $total,
    'description' => $event->name,
    'payment_method_id' => $formData['payment_method_id'],
    'external_reference' => (string)$order_id,  // ✅ ADICIONAR
    'payer' => $formData['payer'],
    // ...
];
```

**Arquivo:** `MercadoPagoController@notification` (linha 310)
```php
// ANTES:
$order = DB::table('orders')
    ->where('gatway_hash', $paymentId)
    ->first();

// DEPOIS:
// Primeiro, buscar external_reference do pagamento
$externalReference = $paymentData['external_reference'] ?? null;

$order = null;
if ($externalReference) {
    // Buscar por external_reference (order_id)
    $order = DB::table('orders')->where('id', $externalReference)->first();
}

// Fallback: buscar por gatway_hash (para compatibilidade)
if (!$order) {
    $order = DB::table('orders')->where('gatway_hash', $paymentId)->first();
}

if (!$order) {
    Log::warning('Order not found for payment', [
        'payment_id' => $paymentId,
        'external_reference' => $externalReference
    ]);
    return response()->json(['error' => 'Order not found'], 404);
}
```

---

#### Correção 2: Atualizar gatway_hash após criar pagamento

**Arquivo:** `ConferenceController@thanks`
```php
// Após criar o pagamento no Mercado Pago
$mpResponse = $paymentClient->create([...]);

// ✅ ADICIONAR: Atualizar order com payment_id
DB::table('orders')
    ->where('id', $order_id)
    ->update([
        'gatway_hash' => $mpResponse->id,
        'gatway_reference' => $mpResponse->external_reference,
        'gatway_status' => $mpResponse->status,
        'updated_at' => now()
    ]);
```

---

#### Correção 3: Integrar Cupom ao Pagamento

**Arquivo:** `ConferenceController@getCoupon` (linha 585)
```php
// ANTES:
$coupon = [['code' => $coupon->code, 'type' => $coupon->discount_type, 'value' => $coupon->discount_value]];

// DEPOIS:
$coupon = [[
    'id' => $coupon->id,  // ✅ ADICIONAR ID
    'code' => $coupon->code,
    'type' => $coupon->discount_type,
    'value' => $coupon->discount_value
]];
```

**Arquivo:** `ConferenceController@thanks` (antes de criar pagamento)
```php
// ✅ ADICIONAR: Aplicar desconto do cupom
$coupon_data = $request->session()->get('coupon');
$coupon_discount = $request->session()->get('coupon_discount', 0);

$total_a_pagar = $total - $coupon_discount;

// Validar que total não seja negativo
if ($total_a_pagar < 0) {
    $total_a_pagar = 0;
}

// Usar $total_a_pagar no pagamento do Mercado Pago
$paymentData = [
    'transaction_amount' => $total_a_pagar,  // ✅ COM DESCONTO
    // ...
];

// Após pagamento aprovado, registrar uso do cupom
if ($coupon_data && isset($coupon_data[0]['id'])) {
    DB::table('orders')
        ->where('id', $order_id)
        ->update(['coupon_id' => $coupon_data[0]['id']]);
}
```

---

#### Correção 4: Polling de Status PIX

**Arquivo:** Criar nova rota e método
```php
// web.php
Route::get('check-payment-status/{order_id}', 'ConferenceController@checkPaymentStatus')
    ->middleware(['auth:participante', 'verified'])
    ->name('conference.check_payment_status');
```

**Arquivo:** `ConferenceController.php`
```php
public function checkPaymentStatus(Request $request, $order_id)
{
    $order = Order::where('id', $order_id)
        ->where('participante_id', Auth::id())
        ->first();
    
    if (!$order) {
        return response()->json(['error' => 'Pedido não encontrado'], 404);
    }
    
    return response()->json([
        'status' => $order->gatway_status,
        'internal_status' => $order->status,
        'approved' => $order->status == 1
    ]);
}
```

**Arquivo:** `payment.blade.php` (adicionar após exibir QR Code)
```javascript
// Iniciar verificação de status
let checkInterval = setInterval(() => {
    fetch(`{{ url('/check-payment-status') }}/${orderId}`)
        .then(response => response.json())
        .then(data => {
            if (data.approved) {
                clearInterval(checkInterval);
                showSuccess('Pagamento PIX confirmado!');
                setTimeout(() => {
                    window.location.href = '{{ route("event_home.my_registrations") }}';
                }, 2000);
            }
        })
        .catch(error => console.error('Erro ao verificar status:', error));
}, 5000); // Verificar a cada 5 segundos

// Parar após 10 minutos
setTimeout(() => {
    clearInterval(checkInterval);
}, 600000);
```

---

### Prioridade MÉDIA

#### Correção 5: Habilitar Boleto

**Arquivo:** `payment.blade.php` (linha 495-503)
```javascript
// ANTES:
let maxDate = "{{ $event->max_event_dates() }} 00:00:00";
let maxDateObj = new Date(maxDate);
let now = new Date();
now.setDate(now.getDate() + 3);

if (now < maxDateObj) {
    $('#ticket_tab').show();
}

// DEPOIS:
let maxDate = "{{ $event->max_event_dates() }} 00:00:00";
let maxDateObj = new Date(maxDate);
let now = new Date();
now.setDate(now.getDate() + 2);  // Reduzir para 2 dias

if (now < maxDateObj) {
    $('#ticket_tab').show();
} else {
    // Remover opção de boleto completamente se não houver tempo
    $('.payment-method-tab[data-method="ticket"]').parent().remove();
}
```

---

## ✅ CHECKLIST DE VALIDAÇÃO

### Meios de Pagamento

- [ ] **Cartão de Crédito**
  - [x] Interface funcional
  - [x] Tokenização implementada
  - [x] Parcelamento configurado
  - [ ] Atualização de gatway_hash
  - [ ] Webhook vinculando corretamente

- [ ] **PIX**
  - [x] Geração de QR Code
  - [x] Interface funcional
  - [ ] Webhook confirmando pagamento
  - [ ] Polling de status implementado
  - [ ] Atualização automática da tela

- [ ] **Boleto**
  - [x] Interface funcional
  - [ ] Disponibilidade configurada corretamente
  - [x] Geração de PDF
  - [ ] Webhook confirmando pagamento

### System de Cupons

- [ ] **Validações**
  - [x] Código válido
  - [x] Data de validade
  - [x] Limite de uso
  - [x] Uso por usuário
  - [ ] ID do cupom na sessão
  - [ ] Registro em orders_coupons

- [ ] **Desconto**
  - [x] Cálculo percentual
  - [x] Cálculo valor fixo
  - [ ] Aplicação no pagamento
  - [ ] Validação no checkout

### Webhook

- [ ] **Recebimento**
  - [x] Rota configurada
  - [x] Validação de estrutura
  - [ ] Vinculação por external_reference
  - [x] Logging adequado

- [ ] **Processamento**
  - [x] Busca de dados do pagamento
  - [x] Mapeamento de status
  - [ ] Atualização de pedido
  - [x] Geração de ingressos
  - [x] Envio de email

---

## 📊 TESTES RECOMENDADOS

### Testes Manuais

1. **Cartão de Crédito**
   - [ ] Pagamento aprovado (cartão teste)
   - [ ] Pagamento recusado
   - [ ] Parcelamento
   - [ ] Webhook recebido
   - [ ] Ingresso gerado
   - [ ] Email enviado

2. **PIX**
   - [ ] Geração de QR Code
   - [ ] Código Copia e Cola
   - [ ] Pagamento via QR Code
   - [ ] Webhook recebido
   - [ ] Confirmação automática
   - [ ] Polling funcionando

3. **Boleto**
   - [ ] Geração de boleto
   - [ ] PDF disponível
   - [ ] Email com boleto
   - [ ] Pagamento confirmado
   - [ ] Webhook recebido

4. **Cupons**
   - [ ] Aplicar cupom percentual
   - [ ] Aplicar cupom valor fixo
   - [ ] Desconto aplicado no pagamento
   - [ ] Limite de uso respeitado
   - [ ] Uso duplicado bloqueado
   - [ ] Cupom registrado em orders_coupons

### Testes Automatizados Sugeridos

```php
// tests/Feature/PaymentTest.php

/** @test */
public function pix_payment_is_confirmed_by_webhook()
{
    // 1. Criar pedido PIX
    // 2. Simular webhook de aprovação
    // 3. Verificar se status foi atualizado
    // 4. Verificar se ingresso foi gerado
}

/** @test */
public function coupon_discount_is_applied_to_payment()
{
    // 1. Aplicar cupom de 10%
    // 2. Valor original: R$ 100
    // 3. Criar pagamento
    // 4. Verificar que Mercado Pago recebeu R$ 90
}

/** @test */
public function webhook_finds_order_by_external_reference()
{
    // 1. Criar pedido
    // 2. Simular webhook com external_reference
    // 3. Verificar que pedido foi encontrado e atualizado
}
```

---

## 🚨 RISCOS IDENTIFICADOS

### Alto Risco

1. **Perda de Pagamentos PIX**
   - Webhook não encontra pedido
   - Cliente paga mas sistema não reconhece
   - **Mitigação:** Implementar correção 1 urgentemente

2. **Cupons Não Aplicados**
   - Desconto não considerado no pagamento
   - Cliente paga valor cheio
   - **Mitigação:** Implementar correção 3

### Médio Risco

3. **Boleto Indisponível**
   - Opção de pagamento limitada
   - Perda de vendas
   - **Mitigação:** Ajustar lógica de disponibilidade

4. **Experiência Ruim com PIX**
   - Cliente não sabe quando pagamento confirma
   - **Mitigação:** Implementar polling

---

## 📝 RECOMENDAÇÕES FINAIS

### Ações Imediatas (Esta Semana)

1. ✅ **Implementar vinculação por external_reference no webhook**
2. ✅ **Adicionar atualização de gatway_hash após criar pagamento**
3. ✅ **Corrigir sistema de cupons para aplicar desconto**

### Ações Curto Prazo (Próximas 2 Semanas)

4. ⚠️ **Implementar polling de status PIX**
5. ⚠️ **Revisar disponibilidade de boleto**
6. ⚠️ **Adicionar testes automatizados**

### Ações Médio Prazo (Próximo Mês)

7. ⏰ **Implementar dashboard de monitoramento de webhooks**
8. ⏰ **Adicionar retry automático de webhooks falhados**
9. ⏰ **Melhorar logging e alertas**

---

## 📞 SUPORTE E MONITORAMENTO

### Logs Importantes

```bash
# Verificar webhooks recebidos
tail -f storage/logs/laravel.log | grep "Mercado Pago Webhook"

# Verificar pagamentos processados
tail -f storage/logs/laravel.log | grep "Payment request received"

# Verificar erros
tail -f storage/logs/laravel.log | grep "error"
```

### Tabelas para Monitorar

```sql
-- Pedidos pendentes há mais de 1 hora
SELECT * FROM orders 
WHERE status = 2 
AND created_at < DATE_SUB(NOW(), INTERVAL 1 HOUR);

-- Webhooks não processados (se houver tabela)
SELECT * FROM webhook_logs 
WHERE processed = 0 
ORDER BY created_at DESC;

-- Cupons mais usados
SELECT c.code, COUNT(*) as uses 
FROM orders o
JOIN coupons c ON o.coupon_id = c.id
GROUP BY c.id
ORDER BY uses DESC;
```

---

## ✍️ CONCLUSÃO

O sistema de pagamento está **parcialmente funcional**, mas apresenta **falhas críticas** que podem resultar em:
- Perda de receita (pagamentos não confirmados)
- Insatisfação do cliente
- Cupons não aplicados corretamente

As correções propostas são **essenciais** e devem ser implementadas com **prioridade ALTA** antes de qualquer campanha de vendas ou evento importante.

**Estimativa de tempo para correções críticas:** 8-16 horas de desenvolvimento + 4-8 horas de testes.

---

**Documento gerado em:** 11/12/2025  
**Próxima revisão recomendada:** Após implementação das correções críticas
