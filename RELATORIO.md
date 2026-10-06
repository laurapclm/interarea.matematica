# Relatório Técnico — Álgebra Linear em PHP

**Disciplina:** atividade interárea (Matemática aplicada + Programação)
**Autores:** Nome 1, Nome 2
**Repositório:** link do GitHub

## 1. Objetivo

Implementar em PHP algoritmos de álgebra linear (operações com matrizes e resolução de sistemas lineares), com uma interface web simples e testes automatizados com PHPUnit.

## 2. Lógica dos algoritmos

### 2.1 Soma, subtração e escalar
As duas matrizes precisam ter o mesmo tamanho. O resultado é calculado posição a posição: `C[i][j] = A[i][j] ± B[i][j]`. No escalar, cada elemento é multiplicado por `k`.

### 2.2 Multiplicação
Para `A (m x n)` e `B (n x p)` o resultado é `C (m x p)`, com `C[i][j] = soma de A[i][k] * B[k][j]` para `k` de 0 a n-1. Se o número de colunas de A for diferente do número de linhas de B, é lançada `DimensoesIncompativeisException`. Complexidade O(m·n·p).

### 2.3 Transposta
Troca linhas por colunas: `T[j][i] = A[i][j]`.

### 2.4 Determinante
Usa eliminação de Gauss, transformando a matriz em triangular superior. O determinante é o produto da diagonal. Detalhes:
- em cada coluna escolhemos como pivô o maior valor em módulo (pivoteamento parcial), que reduz erros numéricos;
- cada troca de linhas multiplica o determinante por -1;
- se o pivô for menor que `EPSILON` (1e-10), a matriz é singular e o determinante é 0.

Complexidade O(n³). Foi escolhido no lugar da expansão de Laplace, que é O(n!) e inviável para matrizes maiores.

### 2.5 Inversa (Gauss-Jordan)
Monta a matriz aumentada `[A | I]` e aplica operações de linha até chegar em `[I | A⁻¹]`. Para cada coluna: escolhe o pivô, troca linhas se necessário, divide a linha do pivô para ele virar 1 e zera a coluna nas outras linhas. Se não houver pivô válido, a matriz é singular e lança `MatrizSingularException`. Complexidade O(n³).

### 2.6 Sistema linear por Gauss
Resolve `A·x = b` com a matriz aumentada `[A | b]`:
1. Para cada coluna procura o pivô (maior valor em módulo da parte ainda não processada), troca de linha e elimina os valores abaixo.
2. Se uma coluna não tem pivô, ela é pulada.
3. Depois da eliminação, as linhas sem pivô têm todos os coeficientes zerados. Se alguma delas tem termo independente diferente de zero (`0 = c`), o sistema é **impossível**.
4. Se o número de pivôs for menor que o número de incógnitas, o sistema é **indeterminado**.
5. Caso contrário, a solução é única e sai por substituição regressiva, de baixo para cima.

Esse método também aceita sistemas com mais equações do que incógnitas (desde que consistentes).

### 2.7 Sistema pela inversa
Calcula `x = A⁻¹ · b`. Só funciona para matriz quadrada e invertível, por isso foi mantido como segundo método para comparação com o Gauss (um dos testes confere que os dois dão o mesmo resultado).

## 3. Decisões de design

- **Organização:** seguimos a estrutura do projeto da professora. `Model` guarda os algoritmos, `Controller` cuida de ler o texto da tela e de montar a resposta, `View` só mostra. Assim os algoritmos não dependem da interface e podem ser testados sozinhos.
- **Estrutura de dados:** a matriz é um array de arrays (`float[][]`) guardado dentro da classe `Matriz`, com atributos privados. Todos os valores são convertidos para `float` no construtor, e as operações devolvem uma nova matriz em vez de alterar a original.
- **Validação no construtor:** matriz vazia, linhas com tamanhos diferentes e valores que não são números geram `InvalidArgumentException`. Assim nenhuma operação trabalha com dados quebrados.
- **Exceções próprias:** `DimensoesIncompativeisException` (estende `InvalidArgumentException`) e `MatrizSingularException`, `SistemaImpossivelException`, `SistemaIndeterminadoException` (estendem `DomainException`). O Controller captura `LogicException`, que é a classe pai das duas, e transforma a mensagem em aviso na tela.
- **Tolerância numérica:** números do tipo `float` têm erro de arredondamento (ex.: `0.1 + 0.2`), então "zero" é qualquer valor com módulo menor que `Matriz::EPSILON = 1e-10`. Nos testes as comparações usam `assertEqualsWithDelta()`.
- **Sem banco de dados:** o enunciado não exige persistência.
- **Testes:** os testes do Model testam os algoritmos direto. Os testes do Controller conferem a leitura dos textos, as mensagens de erro e a formatação dos números.

## 4. Dificuldades e soluções

- **Erro de ponto flutuante:** depois da eliminação, valores que deveriam ser 0 ficavam em torno de 1e-16, o que atrapalhava a detecção de matriz singular e de sistema impossível. Resolvido com a constante `EPSILON` e com o pivoteamento parcial.
- **Diferenciar impossível de indeterminado:** os dois casos aparecem como "linhas zeradas" depois do escalonamento. A diferença está no termo independente: `0 = c` com `c ≠ 0` é impossível, e `0 = 0` com falta de pivôs é indeterminado. A checagem é feita nessa ordem.
- **Pivô zero:** matrizes como `[[0,1],[1,0]]` quebram a eliminação sem troca de linhas. Foi implementada a troca (com o ajuste de sinal no determinante) e criados testes específicos.
- **Entrada pelo formulário:** o usuário pode digitar vírgula decimal, linhas em branco ou quebra de linha do Windows. O Controller normaliza o texto antes de criar a matriz.
- **"-0" na tela:** resultados como `-0.0` apareciam como `-0`. O método `formatarNumero` corrige isso e limita a 4 casas decimais.
- **Resultado esperado dos testes:** os valores esperados foram calculados à mão (ex.: determinante `-306` da matriz `[[6,1,1],[4,-2,5],[2,8,7]]`) para não depender do próprio código.

## 5. Testes e cobertura

Os testes ficam em `tests/` e rodam com `vendor/bin/phpunit`. Os prints da execução e do relatório de cobertura estão em `docs/` (ver README).

## 6. Conclusão

O projeto cobre as operações básicas com matrizes e dois métodos para resolver sistemas lineares, com tratamento de erros e testes automatizados para os casos felizes, de borda e de erro.
