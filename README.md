# Álgebra Linear em PHP

Aplicação web desenvolvida em PHP para realizar operações com matrizes e resolver sistemas lineares. O projeto faz parte da atividade interárea do SENAI e foi desenvolvido utilizando uma estrutura baseada no projeto FitCalc da professora, com separação em Controller, Model, View e tests. Todos os algoritmos possuem testes automatizados utilizando PHPUnit.

## Tecnologias

PHP 8.4, HTML5, CSS3, Bootstrap 5, Laravel Herd, PHPUnit 12, Composer, Git e GitHub.

## Como instalar e executar

É necessário ter o PHP 8.4 ou superior, Composer e Laravel Herd instalados. O projeto não utiliza banco de dados.

Para instalar, clone o repositório dentro da pasta monitorada pelo Laravel Herd:

    git clone <link-do-repositorio> algebra-linear
    cd algebra-linear

Depois instale as dependências:

    composer install

Com o Laravel Herd, abra no navegador o endereço criado para a pasta, por exemplo:

    http://algebra-linear.test

Também é possível executar utilizando o servidor embutido do PHP:

    php -S localhost:8000

Depois acesse:

    http://localhost:8000

## Como usar a interface

Primeiro escolha a operação que deseja realizar. Depois informe a matriz A, colocando uma linha da matriz em cada linha do campo e separando os números por espaços. Quando necessário, informe também a matriz B, um escalar ou o vetor b do sistema. Em seguida, clique em Calcular.

Também são aceitos ponto e vírgula para separar linhas e vírgula como separador decimal.

Um exemplo de sistema linear é:

    x + y = 3
    x - y = 1

Matriz A:

    1  1
    1 -1

Vetor b:

    3
    1

Resultado:

    x1 = 2
    x2 = 1

## Algoritmos implementados

O projeto possui os seguintes algoritmos: soma de matrizes (Matriz::somar), subtração de matrizes (Matriz::subtrair), multiplicação de matrizes (Matriz::multiplicar), multiplicação por escalar (Matriz::multiplicarPorEscalar), matriz transposta (Matriz::transpor), determinante (Matriz::determinante), matriz inversa (Matriz::inversa), matriz identidade (Matriz::identidade), matriz nula (Matriz::nula), resolução de sistemas lineares pelo método de Gauss (SistemaLinear::resolver) e resolução de sistemas pela matriz inversa (SistemaLinear::resolverPorInversa).

A soma e a subtração exigem matrizes com as mesmas dimensões. A multiplicação de matrizes exige que o número de colunas da primeira matriz seja igual ao número de linhas da segunda. O determinante utiliza eliminação de Gauss e troca de linhas quando necessário. A inversa utiliza o método de Gauss-Jordan. A resolução de sistemas por Gauss utiliza pivoteamento parcial e substituição regressiva.

## Tratamento de erros

O projeto possui exceções próprias para tratar situações inválidas, como dimensões incompatíveis, matriz singular, sistema impossível, sistema indeterminado e entradas inválidas. As exceções estão localizadas em Model/Exceptions/.

## Estrutura do projeto

    Controller/
        MatrizController.php

    Model/
        Matriz.php
        SistemaLinear.php
        Exceptions/

    View/
        home.php

    tests/
        Testes automatizados do projeto

    docs/
        Documentação e relatórios

    index.php
        Ponto de entrada da aplicação

    composer.json
        Dependências e configurações do projeto

    phpunit.xml
        Configuração do PHPUnit

    RELATORIO.md
        Relatório técnico do projeto

## Como rodar os testes

Para executar todos os testes automatizados, utilize:

    vendor/bin/phpunit

Para visualizar os testes com mais detalhes:

    vendor/bin/phpunit --testdox

Os testes verificam casos normais, casos de borda, como matrizes 1x1, identidade e matriz nula, casos de erro, como dimensões incompatíveis, matriz singular, sistema impossível e sistema indeterminado, além de entradas inválidas. Também são utilizados testes de precisão numérica com assertEqualsWithDelta().

Atualmente, a suíte possui 90 testes e 331 assertions, todos passando corretamente.

## Relatório de cobertura

A cobertura de código é utilizada para verificar quanto do código dos algoritmos foi executado pelos testes. Para gerar o relatório é necessário ter um driver de cobertura, como Xdebug ou PCOV, habilitado no PHP.

Com o Xdebug habilitado, no PowerShell:

    $env:XDEBUG_MODE="coverage"
    vendor/bin/phpunit --coverage-text

Para salvar o relatório em um arquivo dentro da pasta docs:

    $env:XDEBUG_MODE="coverage"
    vendor/bin/phpunit --coverage-text > docs/cobertura.txt

Também é possível gerar um relatório em HTML:

    $env:XDEBUG_MODE="coverage"
    vendor/bin/phpunit --coverage-html coverage

O requisito do projeto é atingir no mínimo 80% de cobertura dos algoritmos.

## Relatório técnico

O relatório técnico está disponível no arquivo RELATORIO.md. Nele são apresentados a lógica dos algoritmos implementados, a estrutura e organização do projeto, as decisões de design, as estruturas de dados utilizadas, o tratamento de exceções, os testes realizados e as dificuldades encontradas durante o desenvolvimento e suas respectivas soluções.

## Autores

- Nome 1 Ariel França Paixão
- Nome 2 Laura Pereira Cardoso