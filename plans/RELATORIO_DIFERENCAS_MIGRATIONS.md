# Relatório de Diferenças entre Banco de Produção e Migrations

## Tabelas Faltantes nas Migrations

### 1. `event_times`
- **Status**: Tabela existe no banco mas não há migration
- **Campos**:
  - `id` (int unsigned)
  - `time` (timestamp)
  - `status` (int)
  - `event_dates_id` (int unsigned nullable)
  - `created_at`, `updated_at`

### 2. `orders_coupons`
- **Status**: Tabela existe no banco mas não há migration
- **Campos**:
  - `id` (int unsigned)
  - `order_id` (int unsigned)
  - `coupon_id` (int unsigned)

## Diferenças em Tabelas Existentes

### 1. `orders`
**Campos faltantes na migration:**
- `date_used` (timestamp nullable, default current_timestamp on update)
- `event_date_id` (int unsigned NOT NULL) - está comentado na migration

**Campos com diferenças de nullable:**
- `gatway_hash` - no banco é nullable, na migration é NOT NULL
- `gatway_reference` - no banco é nullable, na migration é NOT NULL
- `gatway_status` - no banco é nullable, na migration é NOT NULL
- `gatway_payment_method` - no banco é nullable, na migration é NOT NULL
- `gatway_date_status` - no banco é nullable, na migration é NOT NULL
- `gatway_description` - no banco é nullable, na migration é NOT NULL

### 2. `order_items`
**Campos faltantes na migration:**
- `hash` (varchar 200 NOT NULL)

**Campos com diferenças:**
- `event_date_id` - no banco NÃO existe, na migration existe (deve ser removido)

### 3. `event_dates`
**Campos com diferenças de nullable:**
- `time_begin` - no banco é nullable (DEFAULT NULL), na migration é NOT NULL
- `time_end` - no banco é nullable (DEFAULT NULL), na migration é NOT NULL

## Resumo de Ações Necessárias

1. ✅ Criar migration para `event_times`
2. ✅ Criar migration para `orders_coupons`
3. ✅ Adicionar campo `date_used` em `orders`
4. ✅ Descomentar e adicionar `event_date_id` em `orders`
5. ✅ Tornar campos `gatway_*` nullable em `orders`
6. ✅ Adicionar campo `hash` em `order_items`
7. ✅ Remover campo `event_date_id` de `order_items` (não existe no banco)
8. ✅ Tornar `time_begin` e `time_end` nullable em `event_dates`
9. ✅ Ajustar tamanho do campo `hash` em `lotes` para varchar(200)
10. ✅ Ajustar tamanho do campo `number` em `order_items` para varchar(200)

## Migrations Criadas/Corrigidas

### Novas Migrations Criadas:
- `2022_07_13_014004_create_event_times_table.php` - Tabela event_times
- `2022_08_01_214740_create_orders_coupons_table.php` - Tabela orders_coupons

### Migrations Corrigidas:
- `2022_08_01_214739_create_orders_table.php` - Adicionados campos e ajustados nullable
- `2022_09_25_083656_create_order_items_table.php` - Adicionado hash, removido event_date_id
- `2022_07_13_014003_create_event_dates_table.php` - Campos time_begin e time_end nullable
- `2022_07_14_015741_create_lotes_table.php` - Ajustado tamanho do hash para 200

## Status Final

✅ **Todas as diferenças foram corrigidas!** As migrations agora estão sincronizadas com o banco de produção.

