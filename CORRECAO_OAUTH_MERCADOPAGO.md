# Correção do Problema de OAuth do Mercado Pago

## Problema Identificado

Ao clicar em "Vincular conta do Mercado Pago", o usuário era redirecionado para uma página de erro do Mercado Pago com a mensagem:
> "Estamos um problema e já estamos trabalhando para resolvê-lo. Por favor, tente novamente em alguns minutos."

### Causas do Problema

1. **Rota não existia**: O método `linkAccount` existia no controller, mas não havia rota registrada para receber o callback do OAuth.

2. **URL incorreta do redirect_uri**: A URL gerada tinha dupla barra (`//public`), indicando configuração incorreta:
   ```
   http://localhost:8080//public/painel/eventos/organizador/conta-mercadopago
   ```

3. **redirect_uri não correspondia ao configurado**: O `redirect_uri` usado na URL de autorização não correspondia ao configurado no painel do Mercado Pago.

4. **Parâmetro `state` desnecessário**: O parâmetro `state` estava sendo enviado na requisição de troca do código por token, mas não é necessário nessa etapa.

## Soluções Implementadas

### 1. Criação da Rota de Callback

Adicionada rota nomeada para o callback do OAuth em `routes/web.php`:

```php
Route::get('/mercado-pago/link-account', 'App\Http\Controllers\MercadoPagoController@linkAccount')
    ->middleware(['auth:participante', 'verified'])
    ->name('mercado-pago.link-account');
```

### 2. Correção da URL na View

A view `create_event.blade.php` foi atualizada para usar `route('mercado-pago.link-account')` ao invés de `env('MERCADO_PAGO_REDIRECT_URI')`:

```php
// Antes
redirect_uri={{ urlencode(env('MERCADO_PAGO_REDIRECT_URI', '')) }}

// Depois
redirect_uri={{ urlencode(route('mercado-pago.link-account')) }}
```

### 3. Melhorias no Controller

- Uso de `route()` para gerar a URL do `redirect_uri` corretamente
- Remoção do parâmetro `state` da requisição de token
- Melhor tratamento de erros com mensagens mais específicas
- Validação do código de autorização recebido
- Tratamento específico para `ClientException` do Guzzle
- Validação de campos opcionais (`public_key`, `refresh_token`)

### 4. Melhorias de Segurança

- Validação de autenticação do usuário
- Validação da existência do participante
- Logs detalhados para debugging
- Tratamento de erros específicos do Mercado Pago

## Configuração Necessária no Painel do Mercado Pago

⚠️ **IMPORTANTE**: Você precisa configurar o `redirect_uri` no painel do Mercado Pago:

1. Acesse o [Painel de Desenvolvedores do Mercado Pago](https://www.mercadopago.com.br/developers/panel/app)
2. Selecione sua aplicação
3. Vá em "Credenciais" ou "Configurações"
4. Adicione a URL de redirecionamento exatamente como:
   - **Desenvolvimento**: `http://localhost:8080/mercado-pago/link-account`
   - **Produção**: `https://seudominio.com.br/mercado-pago/link-account`

⚠️ **O redirect_uri deve corresponder EXATAMENTE** à URL usada na requisição de autorização, incluindo:
- Protocolo (http/https)
- Domínio completo
- Porta (se aplicável)
- Caminho completo

## Variáveis de Ambiente

Você pode manter a variável `MERCADO_PAGO_REDIRECT_URI` no `.env` para referência, mas ela não é mais usada diretamente no código. O sistema agora usa a rota nomeada do Laravel.

```env
MERCADO_PAGO_CLIENT_ID=seu_client_id
MERCADO_PAGO_CLIENT_SECRET=seu_client_secret
MERCADO_PAGO_ACCESS_TOKEN=seu_access_token
# Opcional - mantido para referência
MERCADO_PAGO_REDIRECT_URI=https://seudominio.com.br/mercado-pago/link-account
```

## Testando a Correção

1. Certifique-se de que a rota está registrada:
   ```bash
   php artisan route:list | grep mercado-pago
   ```

2. Verifique se a URL gerada está correta:
   - Acesse a página de criação/edição de evento
   - Inspecione o link "Vincular conta"
   - A URL deve ser: `https://auth.mercadopago.com.br/authorization?...&redirect_uri=http://localhost:8080/mercado-pago/link-account`

3. Teste o fluxo completo:
   - Clique em "Vincular conta"
   - Autorize no Mercado Pago
   - Você deve ser redirecionado de volta para a aplicação
   - Deve aparecer a mensagem de sucesso

## Possíveis Erros Restantes

Se ainda houver problemas, verifique:

1. **Credenciais incorretas**: Verifique se `MERCADO_PAGO_CLIENT_ID` e `MERCADO_PAGO_CLIENT_SECRET` estão corretos
2. **redirect_uri não configurado no painel**: O redirect_uri deve estar registrado no painel do Mercado Pago
3. **URL não acessível**: Em desenvolvimento local, use `http://localhost:8080` (sem `/public`)
4. **HTTPS em produção**: O Mercado Pago requer HTTPS em produção

## Logs

Os erros agora são logados com mais detalhes. Verifique o arquivo `storage/logs/laravel.log` para mais informações sobre qualquer erro que ocorrer.

