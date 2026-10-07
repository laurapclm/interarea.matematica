<?php

namespace Tests;


trait ComparaMatriz
{
    protected function assertMatrizAproximada(array $esperado, array $obtido, float $delta = 1e-9): void
    {
        $this->assertCount(count($esperado), $obtido);

        foreach ($esperado as $i => $linha) {
            $this->assertCount(count($linha), $obtido[$i]);

            foreach ($linha as $j => $valor) {
                $this->assertEqualsWithDelta($valor, $obtido[$i][$j], $delta);
            }
        }
    }
}
