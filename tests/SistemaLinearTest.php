<?php

namespace Tests;

use InvalidArgumentException;
use Model\Exceptions\DimensoesIncompativeisException;
use Model\Exceptions\MatrizSingularException;
use Model\Exceptions\SistemaImpossivelException;
use Model\Exceptions\SistemaIndeterminadoException;
use Model\Matriz;
use Model\SistemaLinear;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(SistemaLinear::class)]
class SistemaLinearTest extends TestCase
{
    private SistemaLinear $sistema;

    protected function setUp(): void
    {
        $this->sistema = new SistemaLinear();
    }

    private function assertVetorAproximado(array $esperado, array $obtido, float $delta = 1e-9): void
    {
        $this->assertCount(count($esperado), $obtido);

        foreach ($esperado as $i => $valor) {
            $this->assertEqualsWithDelta($valor, $obtido[$i], $delta);
        }
    }

    // ---------- Gauss: casos felizes ----------

    #[Test]
    public function deve_resolver_sistema_2x2()
    {
        // x + y = 3
        // x - y = 1
        $a = new Matriz([[1, 1], [1, -1]]);

        $this->assertVetorAproximado([2, 1], $this->sistema->resolver($a, [3, 1]));
    }

    #[Test]
    public function deve_resolver_sistema_3x3()
    {
        // 2x + y - z = 8
        // -3x - y + 2z = -11
        // -2x + y + 2z = -3
        $a = new Matriz([[2, 1, -1], [-3, -1, 2], [-2, 1, 2]]);

        $this->assertVetorAproximado([2, 3, -1], $this->sistema->resolver($a, [8, -11, -3]));
    }

    #[Test]
    public function deve_resolver_sistema_com_solucao_decimal()
    {
        // 2x + y = 1
        // x + 3y = 2  ->  x = 0.2, y = 0.6
        $a = new Matriz([[2, 1], [1, 3]]);

        $this->assertVetorAproximado([0.2, 0.6], $this->sistema->resolver($a, [1, 2]));
    }

    // ---------- Gauss: casos de borda ----------

    #[Test]
    public function deve_resolver_sistema_1x1()
    {
        $this->assertVetorAproximado([2], $this->sistema->resolver(new Matriz([[4]]), [8]));
    }

    #[Test]
    public function sistema_com_identidade_devolve_o_proprio_b()
    {
        $this->assertVetorAproximado([5, -3, 7], $this->sistema->resolver(Matriz::identidade(3), [5, -3, 7]));
    }

    #[Test]
    public function deve_trocar_linhas_quando_o_primeiro_pivo_e_zero()
    {
        // 0x + y = 5
        // x + 0y = 7
        $a = new Matriz([[0, 1], [1, 0]]);

        $this->assertVetorAproximado([7, 5], $this->sistema->resolver($a, [5, 7]));
    }

    #[Test]
    public function deve_resolver_sistema_com_mais_equacoes_que_incognitas()
    {
        // 3 equações, 2 incógnitas, mas consistente
        $a = new Matriz([[1, 1], [1, -1], [2, 1]]);

        $this->assertVetorAproximado([2, 1], $this->sistema->resolver($a, [3, 1, 5]));
    }

    // ---------- Gauss: casos de erro ----------

    #[Test]
    public function deve_acusar_sistema_impossivel()
    {
        // x + y = 1
        // 2x + 2y = 5
        $this->expectException(SistemaImpossivelException::class);
        $this->sistema->resolver(new Matriz([[1, 1], [2, 2]]), [1, 5]);
    }

    #[Test]
    public function deve_acusar_sistema_indeterminado()
    {
        // x + y = 2
        // 2x + 2y = 4
        $this->expectException(SistemaIndeterminadoException::class);
        $this->sistema->resolver(new Matriz([[1, 1], [2, 2]]), [2, 4]);
    }

    #[Test]
    public function matriz_nula_com_b_zero_e_indeterminado()
    {
        $this->expectException(SistemaIndeterminadoException::class);
        $this->sistema->resolver(Matriz::nula(2, 2), [0, 0]);
    }

    #[Test]
    public function matriz_nula_com_b_diferente_de_zero_e_impossivel()
    {
        $this->expectException(SistemaImpossivelException::class);
        $this->sistema->resolver(Matriz::nula(2, 2), [1, 0]);
    }

    #[Test]
    public function deve_acusar_indeterminado_quando_tem_mais_incognitas_que_equacoes()
    {
        // x + y + z = 6
        // y + z = 5
        $this->expectException(SistemaIndeterminadoException::class);
        $this->sistema->resolver(new Matriz([[1, 1, 1], [0, 1, 1]]), [6, 5]);
    }

    #[Test]
    public function nao_deve_aceitar_b_com_tamanho_errado()
    {
        $this->expectException(DimensoesIncompativeisException::class);
        $this->sistema->resolver(new Matriz([[1, 1], [1, -1]]), [3]);
    }

    #[Test]
    public function nao_deve_aceitar_b_com_valores_que_nao_sao_numeros()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->sistema->resolver(new Matriz([[1, 1], [1, -1]]), [3, 'x']);
    }

    // ---------- pela inversa ----------

    #[Test]
    public function resolver_pela_inversa_da_o_mesmo_resultado_do_gauss()
    {
        $a = new Matriz([[2, 1, -1], [-3, -1, 2], [-2, 1, 2]]);
        $b = [8, -11, -3];

        $this->assertVetorAproximado([2, 3, -1], $this->sistema->resolverPorInversa($a, $b));
        $this->assertVetorAproximado(
            $this->sistema->resolver($a, $b),
            $this->sistema->resolverPorInversa($a, $b)
        );
    }

    #[Test]
    public function resolver_pela_inversa_sistema_1x1()
    {
        $this->assertVetorAproximado([2], $this->sistema->resolverPorInversa(new Matriz([[4]]), [8]));
    }

    #[Test]
    public function nao_deve_resolver_pela_inversa_com_matriz_singular()
    {
        $this->expectException(MatrizSingularException::class);
        $this->sistema->resolverPorInversa(new Matriz([[1, 1], [2, 2]]), [2, 4]);
    }

    #[Test]
    public function nao_deve_resolver_pela_inversa_com_matriz_nao_quadrada()
    {
        $this->expectException(DimensoesIncompativeisException::class);
        $this->sistema->resolverPorInversa(new Matriz([[1, 1, 1], [0, 1, 1]]), [6, 5]);
    }

    #[Test]
    public function nao_deve_resolver_pela_inversa_com_b_de_tamanho_errado()
    {
        $this->expectException(DimensoesIncompativeisException::class);
        $this->sistema->resolverPorInversa(new Matriz([[1, 1], [1, -1]]), [3, 1, 2]);
    }
}
