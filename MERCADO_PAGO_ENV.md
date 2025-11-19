# Configuração das Credenciais do Mercado Pago

Este arquivo documenta as variáveis de ambiente necessárias para o funcionamento do sistema de pagamentos com Mercado Pago.

## Variáveis Obrigatórias

Adicione as seguintes variáveis ao seu arquivo `.env`:

```env
# Credenciais do Mercado Pago (Site Owner/Marketplace)
MERCADO_PAGO_PUBLIC_KEY=APP_USR-a3e06f96-cfb8-4146-af72-82e32ee227de
MERCADO_PAGO_ACCESS_TOKEN=APP_USR-7032844900997456-111410-8d164d9421ff0190af065ff2d996e80b-2924037064
MERCADO_PAGO_CLIENT_ID=7032844900997456
MERCADO_PAGO_CLIENT_SECRET=vlHi7aACjpPdXduyqpe8oJiBplthwsqi
MERCADO_PAGO_USER_ID=2924037064

# URL de redirecionamento após autorização OAuth
MERCADO_PAGO_REDIRECT_URI=https://seudominio.com.br/mercado-pago/link-account
```

## Descrição das Variáveis

- **MERCADO_PAGO_PUBLIC_KEY**: Chave pública usada no frontend para inicializar o SDK do Mercado Pago
- **MERCADO_PAGO_ACCESS_TOKEN**: Token de acesso da conta do site owner (marketplace)
- **MERCADO_PAGO_CLIENT_ID**: ID do cliente da aplicação no Mercado Pago
- **MERCADO_PAGO_CLIENT_SECRET**: Segredo do cliente da aplicação no Mercado Pago
- **MERCADO_PAGO_USER_ID**: ID do usuário no Mercado Pago
- **MERCADO_PAGO_REDIRECT_URI**: URL de callback após autorização OAuth do organizador

## Importante

- As credenciais acima são da conta do **site owner** (marketplace)
- Cada **organizador** precisa vincular sua própria conta do Mercado Pago através do fluxo OAuth
- Os tokens dos organizadores são armazenados na tabela `mp_accounts`
- O sistema calcula automaticamente a divisão: site owner recebe a taxa configurada (geralmente 7%), organizador recebe o restante

## Como Funciona

1. **Site Owner**: Recebe uma porcentagem configurável (padrão 7%) de cada venda
2. **Organizador**: Recebe o restante (93% no exemplo acima)
3. **Pagamento**: Processado na conta do organizador, com a taxa automaticamente direcionada para o site owner

## Notas de Segurança

- **NUNCA** commite o arquivo `.env` no repositório
- Mantenha as credenciais seguras e não as compartilhe
- Use credenciais diferentes para ambiente de desenvolvimento e produção

