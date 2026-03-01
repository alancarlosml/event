# CLAUDE.md — Ticket DZ6

## ⚡ REGRAS DE ECONOMIA DE TOKENS (LEIA PRIMEIRO)

- **Não leia arquivos por “explorar”.** Só abra o que for necessário para a tarefa. Use `grep`/busca por nome antes de `read`.
- **Edite cirurgicamente.** Nunca reescreva um arquivo inteiro para mudar trechos.
- **Tarefas complexas:** descreva o plano em 2–4 linhas e espere OK antes de executar.
- **Não explique o que já foi feito.** Foque no próximo passo e em como validar.
- **Comandos pesados** (migrate, migrate:fresh, seed, build) só com confirmação explícita.
- **Escopo incerto:** pergunte em vez de assumir.
- **Padrões:** não invente padrões que não existam no projeto (ex.: service layer formal, single-action controllers). Siga o que já está no código; em dúvida, pergunte.
- **Convenções novas:** ao documentar regras, prefira exemplo curto (bom/ruim) a texto longo.

---

---

## 📁 PORTAL (Laravel) — Onde achar as coisas


---

## 🔗 INTEGRAÇÕES

| Sistema        | Como acessa o portal                    | Observação |
|----------------|-----------------------------------------|------------|
| Mercado Pago   |   | 

---

## 🐛 BUGS E PERFORMANCE

**Bugs:** (1) Reproduzir (log/stack/descrição). (2) Localizar arquivo/método com `grep` ou busca por nome. (3) Corrigir só o necessário, sem refatorar em volta. (4) Dizer como testar (rota, comando ou log esperado).

**Performance:** Ver N+1 primeiro (`->with()`); depois índices em queries pesadas; jobs pesados na fila (não síncronos); usar Redis para cache quando fizer sentido.

---
---

## 🚫 NÃO FAZER SEM CONFIRMAR

- Alterar migrations já aplicadas; alterar isolamento tenant; mudar `.env`/config de serviços externos.
- Refatorar models centrais sem escopo claro.
- Rodar `migrate:fresh` ou `db:seed`.

---

## ✅ FLUXO POR TAREFA

1. **Entender:** Ler só o necessário; perguntar se faltar contexto.
2. **Planejar:** Resumir a mudança em 2–3 linhas e aguardar OK.
3. **Executar:** Edição cirúrgica.
4. **Validar:** Se alterou PHP no portal, rodar `docker compose exec portal ./vendor/bin/pint --test`. Indicar como testar (rota, comando, log).

---

## 📌 CONTEXTO

- Projeto em **fase final** — prioridade estabilidade.
- Stack: PHP 8.2+, Laravel 12, Mysql.
- Sem suite de testes robusta — validar manualmente (rotas/logs).
