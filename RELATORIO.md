# RELATÓRIO TÉCNICO – PROJETO ÁLGEBRA LINEAR

## 1. Introdução

O projeto foi feito em PHP com o objetivo de criar uma aplicação que realizasse algumas operações de álgebra linear. Entre elas estão operações com matrizes e resolução de sistemas lineares.

Além de fazer os cálculos, também foram criados testes com PHPUnit para verificar se os resultados estavam corretos e se os erros eram tratados quando alguma operação não podia ser realizada.

## 2. Organização do projeto

O projeto foi separado em algumas pastas para facilitar a organização.

Na pasta `Model` ficam as classes responsáveis pelos cálculos. A classe `Matriz` possui as operações com matrizes e a classe `SistemaLinear` possui os métodos relacionados à resolução de sistemas.

Na pasta `Controller` fica o `MatrizController`, que recebe os dados da página e chama os métodos necessários para realizar cada operação.

A pasta `View` possui a parte visual da aplicação, onde o usuário pode informar os valores e escolher o cálculo que deseja realizar.

A pasta `tests` contém os testes feitos com PHPUnit.

Também foi criada a pasta `Model/Exceptions`, onde ficam as exceções usadas para tratar alguns erros específicos.

## 3. Operações com matrizes

Foram implementadas várias operações com matrizes.

A soma e a subtração são feitas elemento por elemento. Para essas operações, as duas matrizes precisam ter o mesmo tamanho.

Na multiplicação de matrizes, é necessário que a quantidade de colunas da primeira matriz seja igual à quantidade de linhas da segunda.

Também foi implementada a multiplicação de uma matriz por um número, onde cada elemento da matriz é multiplicado pelo valor informado.

A transposta troca as linhas pelas colunas.

O projeto também possui cálculo de determinante, matriz inversa, matriz identidade e matriz nula.

Para calcular o determinante foi utilizado o método de eliminação de Gauss. Já para encontrar a matriz inversa foi utilizado o método de Gauss-Jordan.

## 4. Sistemas lineares

Foram implementadas duas formas de resolver sistemas lineares.

Uma delas utiliza o método de Gauss. Primeiro são feitas as operações necessárias para transformar a matriz em uma forma mais simples e depois são encontrados os valores das incógnitas.

A outra utiliza a matriz inversa. Nesse caso, é necessário que a matriz dos coeficientes tenha uma inversa para que o sistema possa ser resolvido dessa forma.

Também foram feitas verificações para identificar quando um sistema não possui solução ou quando possui várias soluções.

## 5. Tratamento de erros

Durante o desenvolvimento foram criadas algumas exceções para tratar erros específicos.

A `DimensoesIncompativeisException` é usada quando as dimensões das matrizes não permitem realizar determinada operação.

A `MatrizSingularException` é usada quando é tentado calcular a inversa de uma matriz que não possui inversa.

Também foram criadas as exceções `SistemaImpossivelException` e `SistemaIndeterminadoException` para os casos em que o sistema não possui solução ou possui mais de uma solução.

Além disso, existem verificações para evitar que valores inválidos sejam utilizados nos cálculos.

## 6. Testes com PHPUnit

Foram criados testes automatizados para verificar as funções do projeto.

Foram testados os casos normais das operações, mas também situações como matrizes 1x1, matriz identidade, matriz nula e casos em que as dimensões não são compatíveis.

Também foram testados erros relacionados a matrizes singulares e sistemas impossíveis ou indeterminados.

Nos cálculos com números decimais foi utilizado `assertEqualsWithDelta()` para considerar pequenas diferenças causadas pelos cálculos.

Ao todo, a suíte possui 90 testes e 331 assertions, todos passando corretamente.

## 7. Dificuldades durante o desenvolvimento

Uma das dificuldades foi fazer os algoritmos funcionarem corretamente para diferentes tamanhos de matrizes.

Também foi necessário pensar nos casos em que não era possível realizar uma operação, como na multiplicação de matrizes com dimensões incompatíveis ou na tentativa de encontrar a inversa de uma matriz singular.

Outra parte que exigiu atenção foi a criação dos testes, porque não bastava testar somente os resultados corretos. Também foi necessário testar os erros e alguns casos diferentes para garantir que os métodos estavam funcionando.

## 8. Decisões tomadas no projeto

A principal decisão foi separar o projeto em Model, Controller e View. Dessa forma, os cálculos ficam separados da parte visual.

Os algoritmos ficaram principalmente nas classes `Matriz` e `SistemaLinear`, enquanto o Controller ficou responsável por receber os dados da interface e chamar esses métodos.

Também foram utilizadas exceções próprias para facilitar o tratamento dos erros.

O PHPUnit foi escolhido para automatizar os testes e facilitar a verificação dos resultados durante o desenvolvimento.

## 9. Conclusão

Com o desenvolvimento do projeto foi possível colocar em prática os conhecimentos de álgebra linear e programação em PHP.

A aplicação consegue realizar diferentes operações com matrizes e resolver sistemas lineares usando os métodos implementados.

Os testes com PHPUnit também ajudaram a verificar o funcionamento do projeto e encontrar problemas durante o desenvolvimento.

No final, foi possível juntar a parte de matemática com a programação e os testes automatizados em uma única aplicação.