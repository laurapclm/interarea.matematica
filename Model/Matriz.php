<?php

namespace Model;

use InvalidArgumentException;
use Model\Exceptions\DimensoesIncompativeisException;
use Model\Exceptions\MatrizSingularException;

class Matriz
{

    public const EPSILON = 1e-10;

    private array $dados;
    private int $linhas;
    private int $colunas;

    public function __construct(array $dados)
    {
        $dados = array_values($dados);

        if (count($dados) === 0 || !is_array($dados[0]) || count($dados[0]) === 0) {
            throw new InvalidArgumentException("A matriz não pode ser vazia.");
        }

        $colunas = count($dados[0]);

        foreach ($dados as $i => $linha) {
            if (!is_array($linha) || count($linha) !== $colunas) {
                throw new InvalidArgumentException("Todas as linhas da matriz devem ter o mesmo número de colunas.");
            }

            $linha = array_values($linha);

            foreach ($linha as $j => $valor) {
                if (!is_int($valor) && !is_float($valor)) {
                    throw new InvalidArgumentException("A matriz só pode conter números.");
                }
                $linha[$j] = (float) $valor;
            }

            $dados[$i] = $linha;
        }

        $this->dados = $dados;
        $this->linhas = count($dados);
        $this->colunas = $colunas;
    }

    /**
     * Matriz identidade n x n.
     */
    public static function identidade(int $n): Matriz
    {
        if ($n < 1) {
            throw new InvalidArgumentException("O tamanho da matriz identidade deve ser maior que zero.");
        }

        $dados = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $dados[$i][$j] = $i === $j ? 1.0 : 0.0;
            }
        }

        return new Matriz($dados);
    }

    /**
     * Matriz nula (só zeros).
     */
    public static function nula(int $linhas, int $colunas): Matriz
    {
        if ($linhas < 1 || $colunas < 1) {
            throw new InvalidArgumentException("A matriz nula precisa ter pelo menos 1 linha e 1 coluna.");
        }

        return new Matriz(array_fill(0, $linhas, array_fill(0, $colunas, 0.0)));
    }

    public function linhas(): int
    {
        return $this->linhas;
    }

    public function colunas(): int
    {
        return $this->colunas;
    }

    public function ehQuadrada(): bool
    {
        return $this->linhas === $this->colunas;
    }

    public function paraArray(): array
    {
        return $this->dados;
    }

    /**
     * Soma elemento a elemento.
     */
    public function somar(Matriz $outra): Matriz
    {
        $this->verificarMesmoTamanho($outra, "somar");

        $resultado = [];
        for ($i = 0; $i < $this->linhas; $i++) {
            for ($j = 0; $j < $this->colunas; $j++) {
                $resultado[$i][$j] = $this->dados[$i][$j] + $outra->dados[$i][$j];
            }
        }

        return new Matriz($resultado);
    }

    /**
     * Subtração elemento a elemento (this - outra).
     */
    public function subtrair(Matriz $outra): Matriz
    {
        $this->verificarMesmoTamanho($outra, "subtrair");

        $resultado = [];
        for ($i = 0; $i < $this->linhas; $i++) {
            for ($j = 0; $j < $this->colunas; $j++) {
                $resultado[$i][$j] = $this->dados[$i][$j] - $outra->dados[$i][$j];
            }
        }

        return new Matriz($resultado);
    }

    public function multiplicarPorEscalar(float $escalar): Matriz
    {
        $resultado = [];
        for ($i = 0; $i < $this->linhas; $i++) {
            for ($j = 0; $j < $this->colunas; $j++) {
                $resultado[$i][$j] = $this->dados[$i][$j] * $escalar;
            }
        }

        return new Matriz($resultado);
    }

    /**
     * Multiplicação de matrizes (linha x coluna).
     * Só dá certo se colunas de A = linhas de B.
     */
    public function multiplicar(Matriz $outra): Matriz
    {
        if ($this->colunas !== $outra->linhas) {
            throw new DimensoesIncompativeisException(
                "Para multiplicar, o número de colunas da matriz A ({$this->colunas}) deve ser igual ao número de linhas da matriz B ({$outra->linhas})."
            );
        }

        $resultado = [];
        for ($i = 0; $i < $this->linhas; $i++) {
            for ($j = 0; $j < $outra->colunas; $j++) {
                $soma = 0.0;
                for ($k = 0; $k < $this->colunas; $k++) {
                    $soma += $this->dados[$i][$k] * $outra->dados[$k][$j];
                }
                $resultado[$i][$j] = $soma;
            }
        }

        return new Matriz($resultado);
    }

    public function transpor(): Matriz
    {
        $resultado = [];
        for ($i = 0; $i < $this->linhas; $i++) {
            for ($j = 0; $j < $this->colunas; $j++) {
                $resultado[$j][$i] = $this->dados[$i][$j];
            }
        }

        return new Matriz($resultado);
    }

    /**
     * Determinante por eliminação de Gauss (com troca de linhas).
     * Cada troca de linhas inverte o sinal do determinante.
     */
    public function determinante(): float
    {
        if (!$this->ehQuadrada()) {
            throw new DimensoesIncompativeisException("O determinante só existe para matrizes quadradas.");
        }

        $n = $this->linhas;
        $m = $this->dados;
        $det = 1.0;

        for ($c = 0; $c < $n; $c++) {
            // escolhe o maior pivô da coluna (mais estável)
            $pivo = $c;
            for ($l = $c + 1; $l < $n; $l++) {
                if (abs($m[$l][$c]) > abs($m[$pivo][$c])) {
                    $pivo = $l;
                }
            }

            if (abs($m[$pivo][$c]) < self::EPSILON) {
                return 0.0;
            }

            if ($pivo !== $c) {
                [$m[$pivo], $m[$c]] = [$m[$c], $m[$pivo]];
                $det = -$det;
            }

            $det *= $m[$c][$c];

            for ($l = $c + 1; $l < $n; $l++) {
                $fator = $m[$l][$c] / $m[$c][$c];
                for ($k = $c; $k < $n; $k++) {
                    $m[$l][$k] -= $fator * $m[$c][$k];
                }
            }
        }

        return $det;
    }

    /**
     * Inversa pelo método de Gauss-Jordan: monta [A | I] e reduz até virar [I | A^-1].
     */
    public function inversa(): Matriz
    {
        if (!$this->ehQuadrada()) {
            throw new DimensoesIncompativeisException("A inversa só existe para matrizes quadradas.");
        }

        $n = $this->linhas;

        // matriz aumentada [A | I]
        $aug = [];
        for ($i = 0; $i < $n; $i++) {
            $aug[$i] = array_merge($this->dados[$i], array_fill(0, $n, 0.0));
            $aug[$i][$n + $i] = 1.0;
        }

        for ($c = 0; $c < $n; $c++) {
            $pivo = $c;
            for ($l = $c + 1; $l < $n; $l++) {
                if (abs($aug[$l][$c]) > abs($aug[$pivo][$c])) {
                    $pivo = $l;
                }
            }

            if (abs($aug[$pivo][$c]) < self::EPSILON) {
                throw new MatrizSingularException("A matriz é singular (determinante zero) e não possui inversa.");
            }

            if ($pivo !== $c) {
                [$aug[$pivo], $aug[$c]] = [$aug[$c], $aug[$pivo]];
            }

            // deixa o pivô igual a 1
            $valorPivo = $aug[$c][$c];
            for ($k = 0; $k < 2 * $n; $k++) {
                $aug[$c][$k] /= $valorPivo;
            }

            // zera o resto da coluna
            for ($l = 0; $l < $n; $l++) {
                if ($l === $c) {
                    continue;
                }

                $fator = $aug[$l][$c];

                for ($k = 0; $k < 2 * $n; $k++) {
                    $aug[$l][$k] -= $fator * $aug[$c][$k];
                }
            }
        }

        $inversa = [];
        for ($i = 0; $i < $n; $i++) {
            $inversa[$i] = array_slice($aug[$i], $n);
        }

        return new Matriz($inversa);
    }

    private function verificarMesmoTamanho(Matriz $outra, string $operacao): void
    {
        if ($this->linhas !== $outra->linhas || $this->colunas !== $outra->colunas) {
            throw new DimensoesIncompativeisException(
                "As matrizes precisam ter o mesmo tamanho para {$operacao} ({$this->linhas}x{$this->colunas} e {$outra->linhas}x{$outra->colunas})."
            );
        }
    }
}
