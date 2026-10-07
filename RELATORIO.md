# RELATÓRIO TÉCNICO – PROJETO ÁLGEBRA LINEAR

## 1. Introdução

O projeto foi desenvolvido em PHP com o objetivo de criar uma aplicação web capaz de realizar operações de álgebra linear. Entre as funcionalidades estão operações com matrizes e resolução de sistemas lineares.

Além de desenvolver os cálculos, também foram criados testes automatizados utilizando PHPUnit. Os testes foram utilizados para verificar se os resultados estavam corretos, incluindo situações normais, casos de borda e situações em que alguma operação não poderia ser realizada.

O projeto também teve como objetivo praticar a organização de uma aplicação, a utilização de testes automatizados e o versionamento do código com Git e GitHub.

## 2. Organização do projeto

O projeto foi separado em algumas pastas para facilitar a organização e deixar cada parte com uma responsabilidade específica.

Na pasta `Model` ficam as classes responsáveis pelos cálculos. A classe `Matriz` possui as operações relacionadas às matrizes e a classe `SistemaLinear` possui os métodos utilizados para resolver sistemas lineares.

Na pasta `Controller` fica o `MatrizController`, responsável por receber os dados enviados pela interface e chamar os métodos necessários para realizar cada operação.

A pasta `View` contém a parte visual da aplicação. É nela que o usuário informa os valores das matrizes, escolhe a operação e visualiza o resultado.

A pasta `tests` contém os testes automatizados feitos com PHPUnit.

Também foi criada a pasta `Model/Exceptions`, onde ficam as exceções utilizadas para tratar erros específicos durante os cálculos.

Essa separação foi utilizada para evitar que toda a lógica ficasse concentrada em um único arquivo, facilitando a manutenção e a realização dos testes.

## 3. Estruturas de dados utilizadas

As matrizes foram representadas utilizando arrays do PHP. Cada linha da matriz é armazenada como um array dentro de outro array.

Por exemplo, uma matriz como:

```text
1  2
3  4
```

é representada no código de forma semelhante a:

```php
[
    [1, 2],
    [3, 4]
]
```

Essa estrutura foi escolhida porque é simples de entender e permite acessar cada elemento da matriz utilizando índices de linha e coluna.

Os arrays também facilitam a utilização de estruturas de repetição, principalmente nos algoritmos de soma, subtração, multiplicação e nos métodos utilizados para resolver sistemas lineares.

Para os sistemas lineares, também foram utilizados arrays para armazenar a matriz dos coeficientes e o vetor dos resultados.

## 4. Operações com matrizes

Foram implementadas várias operações com matrizes.

A soma é realizada elemento por elemento. Para que a operação seja possível, as duas matrizes precisam possuir a mesma quantidade de linhas e colunas.

A subtração funciona de maneira semelhante à soma, também realizando a operação entre os elementos que estão nas mesmas posições.

Na multiplicação de matrizes, cada elemento do resultado é calculado a partir da multiplicação dos elementos de uma linha da primeira matriz pelos elementos de uma coluna da segunda matriz. Para realizar essa operação, a quantidade de colunas da primeira matriz precisa ser igual à quantidade de linhas da segunda.

Também foi implementada a multiplicação de uma matriz por um escalar. Nesse caso, cada elemento da matriz é multiplicado pelo número informado pelo usuário.

A transposta é obtida trocando as linhas pelas colunas da matriz. Assim, o elemento que estava na posição de uma determinada linha e coluna passa para a posição correspondente de coluna e linha.

O projeto também possui o cálculo do determinante, a matriz inversa, a matriz identidade e a matriz nula.

Para calcular o determinante foi utilizado o método de eliminação de Gauss. Durante o processo, podem ser realizadas trocas de linhas para encontrar um pivô adequado. As trocas são consideradas no cálculo do determinante.

Para encontrar a matriz inversa foi utilizado o método de Gauss-Jordan. Nesse processo, a matriz é trabalhada junto com uma matriz identidade até que a parte correspondente à matriz original se transforme em uma identidade. Quando isso é possível, a outra parte representa a matriz inversa.

## 5. Resolução de sistemas lineares

Foram implementadas duas formas de resolver sistemas lineares.

A primeira utiliza o método de Gauss. O algoritmo realiza operações entre as linhas da matriz para chegar a uma forma que facilite encontrar os valores das incógnitas. Depois dessa etapa, é realizada a substituição regressiva para encontrar os resultados.

Durante esse processo também é utilizado pivoteamento parcial. Essa estratégia permite escolher uma linha com um valor adequado para ser utilizada como pivô, ajudando a evitar problemas durante os cálculos.

A segunda forma utiliza a matriz inversa. Nesse caso, o sistema é representado pela expressão:

```text
A · x = b
```

e, quando a matriz A possui inversa, o vetor das incógnitas pode ser encontrado utilizando:

```text
x = A⁻¹ · b
```

Também foram feitas verificações para identificar situações em que o sistema não possui solução ou possui várias soluções.

## 6. Tratamento de erros

Durante o desenvolvimento foram criadas exceções próprias para tratar situações específicas.

A `DimensoesIncompativeisException` é utilizada quando as dimensões das matrizes não permitem realizar determinada operação, como uma soma entre matrizes de tamanhos diferentes ou uma multiplicação com dimensões incompatíveis.

A `MatrizSingularException` é utilizada quando é solicitada a inversa de uma matriz que não possui inversa.

Também foram criadas as exceções `SistemaImpossivelException` e `SistemaIndeterminadoException`. A primeira é utilizada quando o sistema não possui solução e a segunda quando o sistema possui mais de uma solução.

Além das exceções, existem verificações para impedir que valores ou formatos inválidos sejam utilizados nos cálculos.

A utilização de exceções próprias foi uma decisão de design porque permite identificar melhor qual foi o problema ocorrido e evita que erros diferentes sejam tratados da mesma maneira.

## 7. Testes com PHPUnit

Foram criados testes automatizados para verificar o funcionamento das operações implementadas.

Foram testados os casos normais, nos quais a entrada é válida e o resultado é conhecido, e também casos de borda, como matrizes 1x1, matriz identidade e matriz nula.

Também foram testados casos de erro, como dimensões incompatíveis, matriz singular, sistema impossível, sistema indeterminado e entradas inválidas.

Nos cálculos que utilizam números decimais foi utilizado `assertEqualsWithDelta()`. Esse método permite considerar pequenas diferenças causadas pela representação e pelos cálculos com números de ponto flutuante.

Ao todo, a suíte possui 90 testes e 331 assertions, todos passando corretamente.

Também foi gerado o relatório de cobertura dos testes. O resultado obtido foi de 99,57% de cobertura de linhas, ficando acima do mínimo de 80% estabelecido para o projeto.

## 8. Dificuldades encontradas e soluções

Uma das dificuldades encontradas durante o desenvolvimento foi fazer os algoritmos funcionarem corretamente para diferentes tamanhos de matrizes. Para resolver isso, foram adicionadas verificações das dimensões antes da realização das operações.

Também foi necessário tratar situações em que determinadas operações não poderiam ser realizadas. Por exemplo, uma multiplicação de matrizes só pode acontecer quando as dimensões são compatíveis. Para esses casos, foram criadas exceções específicas.

Outra dificuldade foi trabalhar com matrizes singulares durante o cálculo da inversa. Como uma matriz singular não possui inversa, foi necessário verificar o determinante e tratar essa situação antes de continuar o cálculo.

Na resolução de sistemas lineares, também foi necessário considerar sistemas com uma única solução, sistemas impossíveis e sistemas indeterminados. Foram adicionadas verificações para identificar essas situações e retornar as exceções correspondentes.

A criação dos testes também exigiu atenção. Não bastava verificar apenas os resultados corretos. Foi necessário criar testes para diferentes situações e também para os erros esperados. Isso ajudou a encontrar problemas nos algoritmos e corrigir partes do código durante o desenvolvimento.

## 9. Decisões de design

Uma das principais decisões foi separar o projeto em Model, Controller e View. Dessa forma, os cálculos ficam separados da parte visual da aplicação.

Os algoritmos foram concentrados principalmente nas classes `Matriz` e `SistemaLinear`, enquanto o `MatrizController` ficou responsável por receber os dados da interface e chamar os métodos correspondentes.

A escolha de arrays para representar as matrizes foi feita por ser uma estrutura simples, já utilizada na linguagem PHP e adequada para trabalhar com linhas e colunas.

Também foram utilizadas exceções próprias para facilitar o tratamento de erros e deixar mais claro o motivo pelo qual uma operação não pôde ser realizada.

O PHPUnit foi escolhido para automatizar os testes e permitir verificar os resultados de forma mais rápida e organizada. Dessa forma, sempre que alguma alteração fosse feita nos algoritmos, os testes poderiam ser executados novamente para verificar se as funcionalidades continuavam funcionando.

## 10. Cobertura dos testes

A cobertura de código foi utilizada para verificar quanto do código dos algoritmos estava sendo executado pelos testes.

Foram realizados 90 testes, totalizando 331 assertions, e todos os testes foram aprovados.

O relatório de cobertura apresentou os seguintes resultados:

* Classes: 66,67%
* Métodos: 96,00%
* Linhas: 99,57%

A cobertura de linhas dos principais algoritmos ficou próxima de 100%. A classe `Model\Matriz` apresentou 100% de cobertura de linhas e a classe `Model\SistemaLinear` também apresentou 100%.

O arquivo com o relatório completo de cobertura está disponível em `docs/cobertura.txt`.

## 11. Conclusão

Com o desenvolvimento do projeto foi possível colocar em prática conhecimentos de álgebra linear, programação em PHP, organização de código e testes automatizados.

A aplicação consegue realizar diferentes operações com matrizes e resolver sistemas lineares utilizando os métodos implementados.

Os testes com PHPUnit foram importantes para verificar o funcionamento dos algoritmos, incluindo casos normais, casos de borda e situações de erro. A cobertura de 99,57% das linhas também mostrou que grande parte do código dos algoritmos foi executada durante os testes.

O projeto também permitiu praticar a separação das responsabilidades entre Model, Controller e View, além do uso de Git e GitHub para versionamento.

No final, foi possível juntar os conhecimentos de matemática e programação em uma aplicação funcional, com testes automatizados e documentação do desenvolvimento.
