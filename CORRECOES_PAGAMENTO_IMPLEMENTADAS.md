# Correções Implementadas no Fluxo de Pagamento

## ✅ Correções Realizadas

### 1. **Validação Prévia de Conta Vinculada** ✅
**Arquivo:** `app/Http/Controllers/ConferenceController.php` - Método `paymentView()`

- Adicionada validação antes de exibir a página de pagamento
- Verifica se o organizador tem conta do Mercado Pago vinculada
- Retorna erro amigável se a conta não estiver vinculada
- Evita que o usuário chegue até a tela de pagamento sem condições de pagar

**Código adicionado:**
```php
// Verificar se o organizador tem conta vinculada
if ($event->paid == 1) {
    $organizerParticipant = DB::table('participantes_events')
        ->where('event_id', $event->id)
        ->where('role', 'admin')
        ->first(['participante_id']);
    
    if ($organizerParticipant) {
        $mpAccount = MpAccount::where('participante_id', $organizerParticipant->participante_id)->first();
        
        if (!$mpAccount || empty($mpAccount->access_token)) {
            return redirect()->back()->withErrors([
                'error' => 'O organizador deste evento ainda não vinculou sua conta do Mercado Pago...'
            ]);
        }
    }
}
```

### 2. **Verificação e Renovação Automática de Token** ✅
**Arquivo:** `app/Http/Controllers/ConferenceController.php`

- Adicionados métodos auxiliares:
  - `isTokenExpiredOrExpiring()`: Verifica se o token está expirado ou próximo de expirar (menos de 7 dias)
  - `renewAccessToken()`: Renova o token usando o `refresh_token` via API do Mercado Pago

- Implementada renovação automática em dois pontos:
  1. No `paymentView()` - antes de mostrar a página
  2. No `processPayment()` (thanks) - antes de criar o pagamento

**Benefícios:**
- Evita erros por token expirado
- Renovação automática sem intervenção do organizador
- Logs detalhados para debugging

### 3. **Correção do Fallback no Webhook** ✅
**Arquivo:** `app/Http/Controllers/MercadoPagoController.php` - Método `notification()`

**Problema anterior:**
- O webhook usava fallback para token do marketplace quando não encontrava conta vinculada
- Isso causava inconsistência, pois o pagamento foi criado com token do organizador

**Correção:**
- Removido o fallback para token do marketplace
- Agora retorna erro 500 se não encontrar conta vinculada
- O Mercado Pago tentará novamente quando o organizador vincular a conta
- Logs melhorados para identificar o problema

**Código corrigido:**
```php
if (!$mpAccount || empty($mpAccount->access_token)) {
    Log::error('Mercado Pago account not linked for organizer in webhook', [
        'event_id' => $order->event_id,
        'organizer_id' => $organizerParticipant->participante_id,
        'payment_id' => $paymentId
    ]);
    
    return response()->json([
        'error' => 'Organizer account not linked. Payment cannot be processed.',
        'message' => 'O organizador precisa vincular sua conta do Mercado Pago para processar este pagamento.'
    ], 500);
}

// Usar o token do organizador (mesmo usado para criar o pagamento)
$accessToken = $mpAccount->access_token;
```

### 4. **Validação na View de Pagamento** ✅
**Arquivo:** `resources/views/conference/payment.blade.php`

- Adicionada verificação JavaScript para validar dados de sessão
- Exibe erro se a sessão expirou ou dados estão inválidos
- Melhora a experiência do usuário

### 5. **Melhorias no Tratamento de Erros** ✅
**Arquivo:** `app/Http/Controllers/ConferenceController.php` - Método `processPayment()`

- Mensagens de erro mais específicas e amigáveis
- Tratamento diferenciado para erros 4xx (cliente) e 5xx (servidor)
- Interpretação de erros comuns do Mercado Pago:
  - Erros de cartão → "Dados do cartão inválidos"
  - Saldo insuficiente → "Saldo insuficiente"
  - Erros de application_fee → "Erro na configuração do pagamento"
- Extração de mensagens detalhadas do campo `cause` da resposta do Mercado Pago

## 🔍 Problemas Identificados e Corrigidos

### Problema 1: Erro "Conta não vinculada" no momento do pagamento
**Causa:** Não havia validação prévia antes de permitir acesso à página de pagamento

**Solução:** Validação adicionada no `paymentView()` que verifica antes de exibir a página

### Problema 2: Token expirado causando falhas silenciosas
**Causa:** Tokens OAuth expiram após ~180 dias, mas não havia renovação automática

**Solução:** Implementada renovação automática usando `refresh_token`

### Problema 3: Inconsistência no webhook
**Causa:** Webhook usava token diferente do usado para criar o pagamento

**Solução:** Removido fallback, agora retorna erro se não encontrar conta vinculada

### Problema 4: Mensagens de erro genéricas
**Causa:** Erros do Mercado Pago não eram interpretados adequadamente

**Solução:** Melhorado tratamento de erros com mensagens específicas

## 📊 Fluxo Corrigido

```
1. Usuário acessa página de pagamento
   ↓
2. paymentView() valida:
   ✅ Evento existe
   ✅ Organizador tem conta vinculada
   ✅ Token não está expirado (renova se necessário)
   ↓
3. Se tudo OK → Exibe página de pagamento
   Se não → Retorna erro e redireciona
   ↓
4. Usuário tenta pagar
   ↓
5. processPayment() valida novamente:
   ✅ Token ainda válido (renova se necessário)
   ✅ Cria pagamento com token do organizador
   ↓
6. Webhook recebe notificação:
   ✅ Usa mesmo token do organizador
   ✅ Processa pagamento corretamente
```

## 🧪 Como Testar

1. **Teste de conta não vinculada:**
   - Criar evento pago sem vincular conta
   - Tentar acessar página de pagamento
   - Deve retornar erro antes de mostrar formulário

2. **Teste de renovação de token:**
   - Vincular conta do Mercado Pago
   - Simular token próximo de expirar (modificar `expires_in` no banco)
   - Acessar página de pagamento
   - Deve renovar automaticamente

3. **Teste de webhook:**
   - Criar pagamento com conta vinculada
   - Verificar logs do webhook
   - Deve usar token do organizador

## 📝 Notas Importantes

1. **Renovação de Token:**
   - Requer `refresh_token` válido
   - Se não houver `refresh_token`, não é possível renovar automaticamente
   - Organizador precisará reautorizar a aplicação

2. **Webhook:**
   - Se o organizador desvincular a conta após criar pagamento, o webhook falhará
   - O Mercado Pago tentará novamente automaticamente
   - Quando o organizador vincular novamente, o webhook funcionará

3. **Compatibilidade:**
   - Registros antigos sem `expires_in` são considerados válidos
   - Sistema funciona com registros novos e antigos

## 🔄 Próximos Passos Recomendados

1. Adicionar notificação ao organizador quando token estiver próximo de expirar
2. Implementar dashboard para organizador ver status da conta vinculada
3. Adicionar métricas de renovação de token
4. Considerar implementar fila para processar renovações em background
