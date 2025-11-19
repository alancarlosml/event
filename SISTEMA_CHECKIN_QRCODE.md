# Sistema de Check-in com QR Code

## 📋 Resumo da Implementação

Sistema completo de check-in via QR Code implementado para o fluxo de inscrições em eventos.

## 🔄 Fluxo Completo

### 1. **Inscrição no Evento**
- Usuário seleciona lotes e quantidades
- Pode aplicar cupom de desconto (se disponível)
- Preenche dados dos participantes
- Prossegue para pagamento

### 2. **Aplicação de Cupom de Desconto**
- Sistema valida cupom (percentual ou valor fixo)
- Aplica desconto no subtotal
- Exibe valor final com desconto aplicado

### 3. **Pagamento via Mercado Pago**
- Processamento via API do Mercado Pago
- Suporte a: Cartão de Crédito, Débito, Boleto, PIX
- Webhook recebe confirmação de pagamento

### 4. **Geração do Purchase Hash (QR Code)**
Quando o pagamento é **aprovado**, o sistema:
- Gera `purchase_hash` único para cada `order_item`
- Fórmula: `md5(order_hash + order_item_hash + number + created_at + secret)`
- Salva no banco de dados
- **Locais onde é gerado:**
  - `MercadoPagoController@generateTickets()` (via webhook)
  - `ConferenceController@payment()` (pagamento aprovado diretamente)
  - `EventAdminController@print_voucher()` (quando imprime voucher)

### 5. **QR Code na Página "Minhas Inscrições"**
- Exibido apenas para pedidos com `gatway_status = 1` (aprovado)
- Um QR Code por ingresso (order_item)
- Mostra status do check-in:
  - ✅ Check-in realizado (com data/hora)
  - ⏳ Aguardando check-in
- Link para visualizar ingresso completo

### 6. **Check-in no Dia do Evento**
- Organizador escaneia QR Code
- Sistema valida:
  - ✅ QR Code existe e é válido
  - ✅ Pagamento foi aprovado
  - ✅ Ingresso ainda não foi usado
- Registra check-in:
  - `checkin_status = 1`
  - `checkin_at = now()`
- Retorna informações do participante

## 📁 Arquivos Criados/Modificados

### Novos Arquivos
- `app/Http/Controllers/CheckInController.php` - Controller para check-in
- `resources/views/checkin/view_ticket.blade.php` - Visualização do ingresso
- `database/migrations/2024_12_20_000000_add_checkin_fields_to_order_items_table.php` - Campos de check-in

### Arquivos Modificados
- `app/Http/Controllers/MercadoPagoController.php` - Geração de purchase_hash no webhook
- `app/Http/Controllers/ConferenceController.php` - Geração de purchase_hash no pagamento
- `app/Http/Controllers/EventAdminController.php` - Busca de order_items com purchase_hash
- `resources/views/painel_admin/my_registrations.blade.php` - Exibição de QR Codes
- `routes/web.php` - Rotas de check-in

## 🔗 Rotas Criadas

```php
// Visualizar ingresso (público)
GET /checkin/{purchase_hash} -> CheckInController@viewTicket

// Validar check-in (API)
POST /api/checkin/{purchase_hash} -> CheckInController@validateCheckIn
```

## 🗄️ Estrutura do Banco de Dados

### Tabela `order_items` (campos adicionados)
- `checkin_status` (tinyInteger, default: 0) - 0 = não fez check-in, 1 = fez check-in
- `checkin_at` (timestamp, nullable) - Data/hora do check-in
- `purchase_hash` (string, nullable) - Hash único para QR Code

## 📱 Como Usar

### Para Participantes:
1. Acesse "Minhas Inscrições"
2. Visualize os QR Codes dos ingressos confirmados
3. Clique em "Ver ingresso" para ver detalhes completos
4. Apresente o QR Code no dia do evento

### Para Organizadores:
1. Escaneie o QR Code do participante
2. Sistema valida automaticamente
3. Check-in é registrado instantaneamente
4. Visualize informações do participante

## 🔒 Segurança

- Purchase hash é único e não pode ser falsificado
- Validação de pagamento aprovado antes do check-in
- Prevenção de check-in duplicado
- Logs de todas as operações de check-in

## ✅ Validações Implementadas

1. ✅ QR Code existe no banco
2. ✅ Pagamento foi aprovado (`gatway_status = 1`)
3. ✅ Ingresso não foi usado anteriormente
4. ✅ Retorna informações detalhadas do participante
5. ✅ Registra data/hora do check-in

## 🎯 Próximos Passos (Opcional)

- [ ] Página de check-in para organizadores (scanner)
- [ ] Relatório de check-ins por evento
- [ ] Notificação quando check-in é realizado
- [ ] Exportação de lista de participantes com check-in

