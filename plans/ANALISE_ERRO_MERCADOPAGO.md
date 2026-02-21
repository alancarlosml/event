# Análise do Erro: "Conta do Mercado Pago não vinculada ao organizador"

## 📋 Resumo do Problema

O erro **"Conta do Mercado Pago não vinculada ao organizador. Entre em contato com o organizador do evento."** ocorre quando um usuário tenta finalizar um pagamento para um evento, mas o organizador do evento não vinculou sua conta do Mercado Pago através do OAuth.

## 🔍 Localização do Erro no Código

**Arquivo:** `app/Http/Controllers/ConferenceController.php`  
**Linha:** 1078-1083

```php
if (!$mpAccount || empty($mpAccount->access_token)) {
    Log::error('Mercado Pago account not linked for organizer', [
        'event_id' => $event->id,
        'organizer_id' => $organizerParticipant->participante_id
    ]);
    return response()->json(['error' => 'Conta do Mercado Pago não vinculada ao organizador. Entre em contato com o organizador do evento.'], 500);
}
```

## 🎯 Por Que Este Erro Acontece?

### 1. **Arquitetura Marketplace do Mercado Pago**

O sistema utiliza a arquitetura de **Marketplace** do Mercado Pago, onde:

- **Marketplace** (plataforma): Recebe uma taxa (`application_fee` ou `marketplace_fee`) sobre cada transação
- **Vendedor/Organizador**: Recebe o valor líquido (valor total - taxa da plataforma - taxa do Mercado Pago)

### 2. **Requisito de Access Token do Vendedor**

Segundo a documentação oficial do Mercado Pago:

> **Para usar `application_fee` ou `marketplace_fee`, você DEVE usar o `access_token` do VENDEDOR (organizador do evento), não o token do marketplace.**

**Fonte:** Documentação Mercado Pago - Checkout Transparente Marketplace Integration

```json
{
    "transaction_amount": 25,
    "application_fee": 10,  // Taxa da plataforma
    // ... outros campos
}
```

**Authorization Header:** `Bearer {oauth_access_token}` ← **DEVE SER O TOKEN DO VENDEDOR**

### 3. **Fluxo de Vinculação OAuth**

Para que o sistema possa processar pagamentos com `application_fee`, o organizador precisa:

1. **Criar uma aplicação** no [Painel de Desenvolvedores do Mercado Pago](https://www.mercadopago.com.br/developers/panel/app)
2. **Autorizar a aplicação** através do fluxo OAuth:
   - Clicar em "Vincular conta" na criação/edição do evento
   - Ser redirecionado para o Mercado Pago
   - Autorizar a aplicação
   - Ser redirecionado de volta com um código de autorização
   - O sistema troca o código por um `access_token` e salva na tabela `mp_accounts`

### 4. **O Que Acontece Quando Não Há Vinculação**

Quando o organizador **não vinculou** sua conta:

1. ❌ Não existe registro na tabela `mp_accounts` para o `participante_id` do organizador
2. ❌ O sistema não tem um `access_token` válido do vendedor
3. ❌ Não é possível criar pagamentos com `application_fee` (requer token do vendedor)
4. ❌ O sistema retorna o erro antes mesmo de tentar criar o pagamento

## 📊 Fluxo de Dados

```
┌─────────────────┐
│  Usuário tenta  │
│  finalizar      │
│  pagamento      │
└────────┬────────┘
         │
         ▼
┌─────────────────────────┐
│ ConferenceController    │
│ processPayment()        │
└────────┬────────────────┘
         │
         ▼
┌─────────────────────────┐
│ Busca organizador do    │
│ evento na tabela        │
│ participantes_events    │
│ WHERE role = 'admin'    │
└────────┬────────────────┘
         │
         ▼
┌─────────────────────────┐
│ Busca conta MP do       │
│ organizador:            │
│ MpAccount::where(       │
│   participante_id =     │
│   organizer_id          │
│ )->first()              │
└────────┬────────────────┘
         │
    ┌────┴────┐
    │        │
    ▼        ▼
  ✅ Existe  ❌ Não existe
    │        │
    │        └──► ERRO: "Conta do Mercado Pago não vinculada..."
    │
    ▼
┌─────────────────────────┐
│ Usa access_token do     │
│ organizador para criar  │
│ pagamento com           │
│ application_fee         │
└─────────────────────────┘
```

## 🔧 Como Resolver o Problema

### Para o Organizador do Evento:

1. **Acessar o painel de criação/edição do evento**
2. **Na seção "Carteira de pagamento"**, verificar se há mensagem:
   - ✅ "ID da Conta Vinculada: XXXXXX" → Conta já vinculada
   - ❌ "Vincular conta Mercado Pago" → Precisa vincular
3. **Clicar em "Vincular conta"**
4. **Autorizar a aplicação** no Mercado Pago
5. **Aguardar confirmação** de vinculação bem-sucedida

### Para o Desenvolvedor (Verificação):

```sql
-- Verificar se o organizador tem conta vinculada
SELECT 
    pe.event_id,
    pe.participante_id as organizer_id,
    p.name as organizer_name,
    mp.id as mp_account_id,
    mp.access_token IS NOT NULL as has_access_token,
    mp.mp_user_id
FROM participantes_events pe
INNER JOIN participantes p ON p.id = pe.participante_id
LEFT JOIN mp_accounts mp ON mp.participante_id = pe.participante_id
WHERE pe.role = 'admin'
  AND pe.event_id = {EVENT_ID};
```

## 📚 Referências da Documentação Mercado Pago

### 1. **Checkout Transparente com Marketplace**

**Endpoint:** `POST /v1/payments`

**Requisito:** O `Authorization` header deve conter o `access_token` do **vendedor** (obtido via OAuth), não o token do marketplace.

```bash
curl --location 'https://api.mercadopago.com/v1/payments' \
--header 'Authorization: Bearer {oauth_access_token}' \
--data-raw '{
    "transaction_amount": 25,
    "application_fee": 10,
    ...
}'
```

### 2. **OAuth Flow**

1. Redirecionar usuário para:
   ```
   https://auth.mercadopago.com.br/authorization?
     client_id={CLIENT_ID}&
     response_type=code&
     platform_id=mp&
     redirect_uri={REDIRECT_URI}
   ```

2. Usuário autoriza → Mercado Pago redireciona com `code`

3. Trocar código por token:
   ```
   POST /oauth/token
   {
     "client_id": "...",
     "client_secret": "...",
     "code": "...",
     "grant_type": "authorization_code",
     "redirect_uri": "..."
   }
   ```

4. Resposta contém `access_token` do vendedor

## ⚠️ Observações Importantes

### 1. **PIX e application_fee**

O código atual **não envia `application_fee` para pagamentos PIX** (linha 1253-1262):

```php
// PIX não suporta application_fee no marketplace
// A taxa deve ser processada separadamente após o pagamento ser aprovado
```

Isso está correto segundo a documentação do Mercado Pago, pois PIX não suporta `application_fee` diretamente.

### 2. **Fallback no Webhook**

No `MercadoPagoController@notification` (linha 392-400), há um fallback:

```php
if (!$mpAccount || empty($mpAccount->access_token)) {
    Log::warning('Mercado Pago account not linked for organizer - using marketplace token', [
        'event_id' => $order->event_id,
        'organizer_id' => $organizerParticipant->participante_id
    ]);
    $accessToken = $this->accessToken; // Token do marketplace
} else {
    $accessToken = $mpAccount->access_token; // Token do vendedor
}
```

**⚠️ ATENÇÃO:** Este fallback usa o token do marketplace, mas isso pode causar problemas se o pagamento foi criado com `application_fee`, pois o webhook precisa usar o mesmo token que criou o pagamento.

### 3. **Validação na Criação do Evento**

O sistema já valida se o evento é pago e se tem conta vinculada (linha 626-632 em `EventAdminController.php`):

```php
// Validar se evento é pago e se tem conta Mercado Pago vinculada
if ($request->paid == 1) {
    $mercadoPagoResponse = app(MercadoPagoController::class)->checkLinkedAccount($request);
    if (!$mercadoPagoResponse->getData()->linked) {
        return redirect()->back()
            ->withErrors(['paid' => 'Para criar um evento pago, é necessário vincular sua conta do Mercado Pago primeiro.']);
    }
}
```

## ✅ Conclusão

O erro ocorre porque:

1. **O sistema requer o `access_token` do organizador** para criar pagamentos com `application_fee`
2. **O organizador não completou o fluxo OAuth** de vinculação de conta
3. **Sem o token do vendedor**, não é possível processar pagamentos no modelo marketplace

**Solução:** O organizador deve vincular sua conta do Mercado Pago antes de permitir que usuários façam pagamentos para o evento.
