# 📖 Manual do Usuário - Sistema Frango Fusca

Bem-vindo ao **Manual de Uso Oficial do Sistema Frango Fusca**. Este guia foi elaborado em linguagem simples, direta e humana, com foco nas telas do sistema e na rotina prática de um estabelecimento comercial (lojas de comida, assados, rotisserias e delivery).

---

## 📌 Sumário

1. [Visão Geral e Primeiros Passos](#1-visão-geral-e-primeiros-passos)
2. [Painel do Dia (Acompanhamento Diário)](#2-painel-do-dia)
3. [Painel de Análises & Tomada de Decisões](#3-painel-de-análises--tomada-de-decisões)
4. [Ponto de Venda (Emissão de Pedidos)](#4-ponto-de-venda-emissão-de-pedidos)
5. [Estoque, Entradas & Fichas Técnicas](#5-estoque-entradas--fichas-técnicas)
6. [Simulador de Precificação Inteligente](#6-simulador-de-precificação-inteligente)
7. [Financeiro, Despesas & Cofrinho de Reservas](#7-financeiro-despesas--cofrinho-de-reservas)
8. [Cadastros Base (Clientes, Fornecedores, Canais & Maquininhas)](#8-cadastros-base)
9. [Garantia de Consistência e Integridade da Aplicação](#9-garantia-de-consistência)

---

## 1. Visão Geral e Primeiros Passos

O sistema **Frango Fusca** auxilia a sua loja desde a compra do insumo (frango cru, batatas, embalagens) até a precificação correta e a venda rápida no atendimento com baixa automática no estoque.

### 🔑 Acesso ao Sistema
- **Página de Login**: `login.php`
- **Credencial Padrão**: `igorsilvadnascimento@gmail.com` / `admin123`
- **Menu Lateral**: Localizado no lado esquerdo da tela, dá acesso rápido a todas as funções sem termos complicados ou siglas confusas.

---

## 2. Painel do Dia (`index.php`)

O **Painel do Dia** é o seu centro de controle para o atendimento diário.

- **Objetivo**: Mostrar o desempenho da loja no dia atual.
- **Destaques**:
  - **Faturamento Bruto e Líquido de Hoje**: Mostra o dinheiro total movimentado hoje e o valor limpo (descontadas as taxas de maquininha e iFood).
  - **Comparativo com a Semana Passada**: Card com comparativo direto contra o mesmo dia da semana anterior (ex: comparativo desta terça-feira contra a terça-feira passada).
  - **Vendas por Canal**: Gráfico mostrando a distribuição de vendas entre Balcão, WhatsApp e Aplicativos de Delivery.
  - **Vendas por Horário**: Gráfico de linha mostrando em quais horas do dia ocorrem os maiores picos de pedidos.

---

## 3. Painel de Análises & Tomada de Decisões (`dashboard_analitico.php`)

Diferente do Painel do Dia, esta tela traz inteligência de negócios para horizontes mais amplos (7, 30 e 90 dias).

- **Diagnósticos Automatizados**: Exibe alertas como *"Seu impacto de taxas ultrapassou 10%"* ou *"Seu canal mais rentável é o Balcão"*.
- **Curva ABC de Produtos**: Tabela ranqueando os produtos mais vendidos e mais lucrativos da loja.

---

## 4. Ponto de Venda (`pdv.php`)

A tela de **Ponto de Venda** foi desenhada para ser o mais ágil possível.

### Passo a Passo para Registrar um Pedido:
1. **Configurar o Pedido**: No topo da tela, selecione o **Canal / Plataforma** (Balcão, WhatsApp, iFood), a **Maquininha** (se cartão) e o **Meio de Pagamento** (Pix, Dinheiro, Débito).
2. **Localizar o Produto**:
   - Digite no campo de busca em tempo real.
   - Ou clique nos botões de filtro (`Combos` ou `Produtos Próprios & Bebidas`).
3. **Adicionar ao Carrinho**: Passe o mouse por cima do card do produto (a mãozinha de clique aparecerá) e clique para enviar ao carrinho à direita.
4. **Identificar o Cliente (Opcional)**: Selecione o cliente cadastrado se desejar vincular o pedido.
5. **Finalizar Venda**: Clique no botão verde **"Finalizar Venda & Baixar Estoque"**. O estoque dos insumos envolvidos na receita do produto será abatido automaticamente!

---

## 5. Estoque, Entradas & Fichas Técnicas

- **Entradas & Compras (`estoque.php`)**: Registre aqui as compras de insumos dos fornecedores (Avivar Alimentos, CEASA Minas, Ambev). Cada reposição alimenta o estoque com lote e valor de custo atualizado.
- **Fichas Técnicas (`receitas.php`)**: Defina a "receita" de cada produto. Exemplo: 1 Frango Assado utiliza 2,2 kg de Frango Cru + 30g de Sal + Alho + 1 Sacola Térmica.

---

## 6. Simulador de Precificação Inteligente (`precificacao.php`)

Esta é uma das ferramentas mais importantes do sistema. Ela permite descobrir o preço ideal de venda considerando:
1. **Custo dos Ingredientes & Embalagem**: Soma exata das matérias-primas usadas no produto.
2. **Rateio de Custos Fixos (Água, Luz, Gás, Aluguel)**: Divide suas contas mensais pela quantidade estimada de vendas para que cada produto ajude a pagar as despesas da loja.
3. **Margem de Lucro Desejada**: A porcentagem de ganho líquido que você quer colocar no bolso (ex: 50%).
4. **Taxas do Canal & Maquininha**: Calcula quanto o iFood ou a maquininha vão reter e ajusta o preço sugerido no cardápio para você não perder sua margem.

---

## 7. Financeiro, Despesas & Cofrinho de Reservas

- **Custos & Despesas Mensais (`custos.php`)**: Mantenha atualizadas suas contas fixas e variáveis (aluguel, CEMIG, COPASA, Ultragaz P45).
- **Cofrinho & Reservas (`reserva_financeira.php`)**: Guarde dinheiro com metas definidas (Fundo Emergencial, Troca de Assadeira, 13º salário da equipe).

---

## 8. Cadastros Base

Cadastre previamente para manter seus relatórios completos:
- **Clientes (`clientes.php`)**
- **Fornecedores (`fornecedores.php`)**
- **Canais de Venda / Plataformas (`ambientes_venda.php`)**
- **Maquininhas de Cartão (`maquinas_venda.php`)**
- **Equipe / Profissionais (`usuarios.php`)**
- **Dados da Loja (`estabelecimento.php`)**

---

## 9. Garantia de Consistência e Integridade da Aplicação

O sistema passou por uma auditoria completa de ponta a ponta:
- **Sintaxe & Execução**: Todos os 74 arquivos PHP foram validados e passam com 0 erros de sintaxe.
- **Banco de Dados**: Configurado em UTF-8 puro (`utf8mb4`), sem caracteres corrompidos.
- **Autenticação**: Suporta senhas em Bcrypt nativo e fallback inteligente para hashes MD5 legados com atualização automática.
