# Álgebra Linear em PHP

Aplicação web em PHP que implementa operações com matrizes e resolução de sistemas lineares.
Todos os algoritmos são testados com PHPUnit.

Projeto da atividade interárea do SENAI (estrutura baseada no projeto FitCalc da professora: `Controller`, `Model`, `View`, `tests`).

## Tecnologias

PHP >= 8.4, HTML5, CSS3, Bootstrap 5, Laravel Herd, PHPUnit 12, Composer, Git e GitHub.

## Como instalar e executar

1. Instale o [Laravel Herd](https://herd.laravel.com/) e o Composer.
2. Clone o repositório dentro da pasta que o Herd monitora (ex.: `~/Herd`):
   ```bash
   git clone <link-do-repositorio> algebra-linear
   cd algebra-linear
   ```
3. Instale as dependências:
   ```bash
   composer install
   ```
4. Abra no navegador o endereço que o Herd criar para a pasta (ex.: `http://algebra-linear.test`).

Não precisa de banco de dados.

Sem o Herd também funciona com o servidor embutido do PHP:
```bash
php -S localhost:8000
```

## Como usar a interface

1. Escolha a operação.
2. Digite a matriz A (uma linha da matriz por linha do campo, números separados por espaço). Também funciona com `;` para separar linhas e vírgula como decimal.
3. Preencha o campo B quando a operação pedir (outra matriz, um escalar ou o vetor b do sistema).
4. Clique em **Calcular**.

Exemplo de sistema `x + y = 3` e `x - y = 1`:

```
Matriz A:        Vetor b:
1 1              3 1
1 -1
```
Resultado: `x1 = 2`, `x2 = 1`.

## Algoritmos implementados

| Algoritmo | Onde fica | Observação |
|---|---|---|
| Soma e subtração de matrizes | `Matriz::somar`, `Matriz::subtrair` | exige mesmo tamanho |
| Multiplicação de matrizes | `Matriz::multiplicar` | colunas de A = linhas de B |
| Multiplicação por escalar | `Matriz::multiplicarPorEscalar` | |
| Transposta | `Matriz::transpor` | |
| Determinante | `Matriz::determinante` | eliminação de Gauss com troca de linhas |
| Inversa | `Matriz::inversa` | Gauss-Jordan sobre `[A \| I]` |
| Matriz identidade e nula | `Matriz::identidade`, `Matriz::nula` | |
| Sistema linear por Gauss | `SistemaLinear::resolver` | pivoteamento parcial + substituição regressiva |
| Sistema linear pela inversa | `SistemaLinear::resolverPorInversa` | x = A⁻¹·b |

Erros tratados com exceções próprias (`Model/Exceptions`): dimensões incompatíveis, matriz singular, sistema impossível e sistema indeterminado.

## Estrutura

```
Controller/MatrizController.php   lê os textos da tela e chama os algoritmos
Model/Matriz.php                  operações com matrizes
Model/SistemaLinear.php           resolução de sistemas
Model/Exceptions/                 exceções do projeto
View/home.php                     tela
templates/css/style.css           estilo
tests/                            testes PHPUnit
docs/                             relatório de cobertura e print dos testes
index.php                         ponto de entrada
```

## Como rodar os testes

```bash
vendor/bin/phpunit
```

Ou, com saída mais detalhada (nome de cada teste):
```bash
vendor/bin/phpunit --testdox
```

Os testes cobrem:
- casos felizes (resultados calculados à mão);
- casos de borda (matriz 1x1, identidade, matriz nula, pivô zero);
- casos de erro (dimensões incompatíveis, matriz singular, sistema impossível/indeterminado, entradas inválidas);
- precisão numérica com `assertEqualsWithDelta()`.

## Relatório de cobertura

A cobertura precisa de uma extensão de cobertura (Xdebug ou PCOV) ativa no PHP.

```bash
# relatório no terminal (salvar em docs/)
XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-text > docs/cobertura.txt

# relatório em HTML (abrir coverage/index.html)
XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-html coverage
```

No Windows (PowerShell): `$env:XDEBUG_MODE="coverage"; vendor/bin/phpunit --coverage-text`.

Coloque aqui o print da execução dos testes e da cobertura (arquivos em `docs/`):

- `docs/testes.png` (todos os testes passando)
- `docs/cobertura.txt` ou `docs/cobertura.png`

## Relatório técnico

Veja [RELATORIO.md](RELATORIO.md).

## Autores

- Nome 1
- Nome 2
