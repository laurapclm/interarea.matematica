<?php

namespace Model;

use InvalidArgumentException;
use Model\Exceptions\DimensoesIncompativeisException;
use Model\Exceptions\SistemaImpossivelException;
use Model\Exceptions\SistemaIndeterminadoException;

class SistemaLinear
{
    /**
     * Resolve A.x = b por eliminação de Gauss com pivoteamento parcial
     * e substituição regressiva.
     *
     * @param Matriz $a matriz dos coeficientes
     * @param array  $b termos independentes
     * @return float[] solução x
     */
    public function resolver(Matriz $a, array $b): array
    {
        $b = $this->validarVetor($a, $b);

        $n = $a->linhas();
        $m = $a->colunas();

       
        $aug = $a->paraArray();
        for ($i = 0; $i < $n; $i++) {
            $aug[$i][] = $b[$i];
        }

        $linhaPivo = 0;

        for ($c = 0; $c < $m && $linhaPivo < $n; $c++) {
            $p = $linhaPivo;
            for ($l = $linhaPivo + 1; $l < $n; $l++) {
                if (abs($aug[$l][$c]) > abs($aug[$p][$c])) {
                    $p = $l;
                }
            }

   
            if (abs($aug[$p][$c]) < Matriz::EPSILON) {
                continue;
            }

            if ($p !== $linhaPivo) {
                [$aug[$p], $aug[$linhaPivo]] = [$aug[$linhaPivo], $aug[$p]];
            }

            for ($l = $linhaPivo + 1; $l < $n; $l++) {
                $fator = $aug[$l][$c] / $aug[$linhaPivo][$c];
                for ($k = $c; $k <= $m; $k++) {
                    $aug[$l][$k] -= $fator * $aug[$linhaPivo][$k];
                }
            }

            $linhaPivo++;
        }

        
        for ($l = $linhaPivo; $l < $n; $l++) {
            if (abs($aug[$l][$m]) > Matriz::EPSILON) {
                throw new SistemaImpossivelException("O sistema é impossível (não possui solução).");
            }
        }

   
        if ($linhaPivo < $m) {
            throw new SistemaIndeterminadoException("O sistema é indeterminado (possui infinitas soluções).");
        }

      
        $x = array_fill(0, $m, 0.0);
        for ($i = $m - 1; $i >= 0; $i--) {
            $soma = $aug[$i][$m];
            for ($j = $i + 1; $j < $m; $j++) {
                $soma -= $aug[$i][$j] * $x[$j];
            }
            $x[$i] = $soma / $aug[$i][$i];
        }

        return $x;
    }

    /**
     * Resolve A.x = b fazendo x = A^-1 . b (só serve para matriz quadrada).
     *
     * @return float[]
     */
    public function resolverPorInversa(Matriz $a, array $b): array
    {
        if (!$a->ehQuadrada()) {
            throw new DimensoesIncompativeisException("Para resolver pela inversa a matriz dos coeficientes precisa ser quadrada.");
        }

        $b = $this->validarVetor($a, $b);
        $inversa = $a->inversa()->paraArray();

        $x = [];
        for ($i = 0; $i < $a->linhas(); $i++) {
            $soma = 0.0;
            for ($j = 0; $j < $a->colunas(); $j++) {
                $soma += $inversa[$i][$j] * $b[$j];
            }
            $x[$i] = $soma;
        }

        return $x;
    }

   
    private function validarVetor(Matriz $a, array $b): array
    {
        $b = array_values($b);

        if (count($b) !== $a->linhas()) {
            throw new DimensoesIncompativeisException(
                "O vetor b precisa ter {$a->linhas()} valores, um para cada equação."
            );
        }

        foreach ($b as $i => $valor) {
            if (!is_int($valor) && !is_float($valor)) {
                throw new InvalidArgumentException("O vetor b só pode conter números.");
            }
            $b[$i] = (float) $valor;
        }

        return $b;
    }
}
