<?php

namespace Tests;

use InvalidArgumentException;
use Model\Exceptions\DimensoesIncompativeisException;
use Model\Exceptions\MatrizSingularException;
use Model\Matriz;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Matriz::class)]
class MatrizTest extends TestCase
{
    use ComparaMatriz;

    // ---------- construção ----------

    #[Test]
    public function deve_guardar_as_dimensoes_da_matriz()
    {
        $m = new Matriz([[1, 2, 3], [4, 5, 6]]);

        $this->assertSame(2, $m->linhas());
        $this->assertSame(3, $m->colunas());
        $this->assertFalse($m->ehQuadrada());
    }

    #[Test]
    public function deve_converter_inteiros_para_float()
    {
        $m = new Matriz([[1, 2], [3, 4]]);

        $this->assertSame([[1.0, 2.0], [3.0, 4.0]], $m->paraArray());
        $this->assertTrue($m->ehQuadrada());
    }

    #[Test]
    public function nao_deve_aceitar_matriz_vazia()
    {
        $this->expectException(InvalidArgumentException::class);
        new Matriz([]);
    }

    #[Test]
    public function nao_deve_aceitar_linha_vazia()
    {
        $this->expectException(InvalidArgumentException::class);
        new Matriz([[]]);
    }

    #[Test]
    public function nao_deve_aceitar_linhas_de_tamanhos_diferentes()
    {
        $this->expectException(InvalidArgumentException::class);
        new Matriz([[1, 2], [3]]);
    }

    #[Test]
    public function nao_deve_aceitar_valores_que_nao_sao_numeros()
    {
        $this->expectException(InvalidArgumentException::class);
        new Matriz([[1, 'a'], [3, 4]]);
    }

    #[Test]
    public function nao_deve_aceitar_linha_que_nao_e_array()
    {
        $this->expectException(InvalidArgumentException::class);
        new Matriz([[1, 2], 3]);
    }

    #[Test]
    public function deve_criar_matriz_identidade()
    {
        $this->assertSame(
            [[1.0, 0.0, 0.0], [0.0, 1.0, 0.0], [0.0, 0.0, 1.0]],
            Matriz::identidade(3)->paraArray()
        );
    }

    #[Test]
    public function nao_deve_criar_identidade_com_tamanho_invalido()
    {
        $this->expectException(InvalidArgumentException::class);
        Matriz::identidade(0);
    }

    #[Test]
    public function deve_criar_matriz_nula()
    {
        $this->assertSame([[0.0, 0.0, 0.0], [0.0, 0.0, 0.0]], Matriz::nula(2, 3)->paraArray());
    }

    #[Test]
    public function nao_deve_criar_matriz_nula_com_tamanho_invalido()
    {
        $this->expectException(InvalidArgumentException::class);
        Matriz::nula(2, 0);
    }

    // ---------- soma e subtração ----------

    #[Test]
    public function deve_somar_duas_matrizes()
    {
        $a = new Matriz([[1, 2], [3, 4]]);
        $b = new Matriz([[5, 6], [7, 8]]);

        $this->assertMatrizAproximada([[6, 8], [10, 12]], $a->somar($b)->paraArray());
    }

    #[Test]
    public function deve_somar_com_precisao_de_ponto_flutuante()
    {
        $a = new Matriz([[0.1]]);
        $b = new Matriz([[0.2]]);

        // 0.1 + 0.2 não dá exatamente 0.3 no computador
        $this->assertEqualsWithDelta(0.3, $a->somar($b)->paraArray()[0][0], 1e-12);
    }

    #[Test]
    public function somar_com_matriz_nula_nao_muda_a_matriz()
    {
        $a = new Matriz([[1, 2], [3, 4]]);

        $this->assertMatrizAproximada([[1, 2], [3, 4]], $a->somar(Matriz::nula(2, 2))->paraArray());
    }

    #[Test]
    public function deve_somar_matrizes_1x1()
    {
        $r = (new Matriz([[2]]))->somar(new Matriz([[3]]));

        $this->assertMatrizAproximada([[5]], $r->paraArray());
    }

    #[Test]
    public function nao_deve_somar_matrizes_de_tamanhos_diferentes()
    {
        $this->expectException(DimensoesIncompativeisException::class);
        (new Matriz([[1, 2], [3, 4]]))->somar(new Matriz([[1, 2, 3], [4, 5, 6]]));
    }

    #[Test]
    public function deve_subtrair_duas_matrizes()
    {
        $a = new Matriz([[1, 2], [3, 4]]);
        $b = new Matriz([[5, 6], [7, 8]]);

        $this->assertMatrizAproximada([[-4, -4], [-4, -4]], $a->subtrair($b)->paraArray());
    }

    #[Test]
    public function subtrair_matriz_dela_mesma_da_matriz_nula()
    {
        $a = new Matriz([[1.5, -2], [3, 4]]);

        $this->assertMatrizAproximada([[0, 0], [0, 0]], $a->subtrair($a)->paraArray());
    }

    #[Test]
    public function nao_deve_subtrair_matrizes_de_tamanhos_diferentes()
    {
        $this->expectException(DimensoesIncompativeisException::class);
        (new Matriz([[1, 2]]))->subtrair(new Matriz([[1], [2]]));
    }

    // ---------- escalar ----------

    #[Test]
    public function deve_multiplicar_por_escalar()
    {
        $a = new Matriz([[1, 2], [3, 4]]);

        $this->assertMatrizAproximada([[2.5, 5], [7.5, 10]], $a->multiplicarPorEscalar(2.5)->paraArray());
    }

    #[Test]
    public function multiplicar_por_zero_da_matriz_nula()
    {
        $a = new Matriz([[1, 2], [3, 4]]);

        $this->assertMatrizAproximada([[0, 0], [0, 0]], $a->multiplicarPorEscalar(0)->paraArray());
    }

    // ---------- multiplicação ----------

    #[Test]
    public function deve_multiplicar_matrizes_quadradas()
    {
        $a = new Matriz([[1, 2], [3, 4]]);
        $b = new Matriz([[5, 6], [7, 8]]);

        $this->assertMatrizAproximada([[19, 22], [43, 50]], $a->multiplicar($b)->paraArray());
    }

    #[Test]
    public function deve_multiplicar_matrizes_retangulares()
    {
        $a = new Matriz([[1, 2, 3], [4, 5, 6]]);
        $b = new Matriz([[7, 8], [9, 10], [11, 12]]);

        $r = $a->multiplicar($b);

        $this->assertSame(2, $r->linhas());
        $this->assertSame(2, $r->colunas());
        $this->assertMatrizAproximada([[58, 64], [139, 154]], $r->paraArray());
    }

    #[Test]
    public function multiplicar_pela_identidade_nao_muda_a_matriz()
    {
        $a = new Matriz([[1, 2], [3, 4]]);

        $this->assertMatrizAproximada([[1, 2], [3, 4]], $a->multiplicar(Matriz::identidade(2))->paraArray());
        $this->assertMatrizAproximada([[1, 2], [3, 4]], Matriz::identidade(2)->multiplicar($a)->paraArray());
    }

    #[Test]
    public function multiplicar_pela_matriz_nula_da_matriz_nula()
    {
        $a = new Matriz([[1, 2], [3, 4]]);

        $this->assertMatrizAproximada([[0, 0], [0, 0]], $a->multiplicar(Matriz::nula(2, 2))->paraArray());
    }

    #[Test]
    public function deve_multiplicar_matrizes_1x1()
    {
        $r = (new Matriz([[3]]))->multiplicar(new Matriz([[4]]));

        $this->assertMatrizAproximada([[12]], $r->paraArray());
    }

    #[Test]
    public function nao_deve_multiplicar_com_dimensoes_incompativeis()
    {
        $this->expectException(DimensoesIncompativeisException::class);
        (new Matriz([[1, 2], [3, 4]]))->multiplicar(new Matriz([[1, 2], [3, 4], [5, 6]]));
    }

    // ---------- transposta ----------

    #[Test]
    public function deve_transpor_matriz_retangular()
    {
        $a = new Matriz([[1, 2, 3], [4, 5, 6]]);

        $this->assertMatrizAproximada([[1, 4], [2, 5], [3, 6]], $a->transpor()->paraArray());
    }

    #[Test]
    public function transposta_da_transposta_e_a_propria_matriz()
    {
        $a = new Matriz([[1, 2, 3], [4, 5, 6]]);

        $this->assertMatrizAproximada($a->paraArray(), $a->transpor()->transpor()->paraArray());
    }

    #[Test]
    public function transposta_de_matriz_1x1_e_ela_mesma()
    {
        $this->assertMatrizAproximada([[7]], (new Matriz([[7]]))->transpor()->paraArray());
    }

    // ---------- determinante ----------

    #[Test]
    public function deve_calcular_determinante_2x2()
    {
        $this->assertEqualsWithDelta(-2.0, (new Matriz([[1, 2], [3, 4]]))->determinante(), 1e-9);
    }

    #[Test]
    public function deve_calcular_determinante_3x3()
    {
        $a = new Matriz([[6, 1, 1], [4, -2, 5], [2, 8, 7]]);

        $this->assertEqualsWithDelta(-306.0, $a->determinante(), 1e-9);
    }

    #[Test]
    public function determinante_de_matriz_1x1_e_o_proprio_valor()
    {
        $this->assertEqualsWithDelta(5.0, (new Matriz([[5]]))->determinante(), 1e-9);
    }

    #[Test]
    public function determinante_da_identidade_e_um()
    {
        $this->assertEqualsWithDelta(1.0, Matriz::identidade(4)->determinante(), 1e-9);
    }

    #[Test]
    public function determinante_da_matriz_nula_e_zero()
    {
        $this->assertEqualsWithDelta(0.0, Matriz::nula(3, 3)->determinante(), 1e-9);
    }

    #[Test]
    public function determinante_de_matriz_singular_e_zero()
    {
        $a = new Matriz([[2, 0, 1], [1, 3, 2], [1, 1, 1]]);

        $this->assertEqualsWithDelta(0.0, $a->determinante(), 1e-9);
    }

    #[Test]
    public function determinante_deve_trocar_o_sinal_quando_troca_linhas()
    {
        // o pivô da primeira coluna é zero, então precisa trocar as linhas
        $this->assertEqualsWithDelta(-1.0, (new Matriz([[0, 1], [1, 0]]))->determinante(), 1e-9);
    }

    #[Test]
    public function nao_deve_calcular_determinante_de_matriz_nao_quadrada()
    {
        $this->expectException(DimensoesIncompativeisException::class);
        (new Matriz([[1, 2, 3], [4, 5, 6]]))->determinante();
    }

    // ---------- inversa ----------

    #[Test]
    public function deve_calcular_inversa_2x2()
    {
        $a = new Matriz([[4, 7], [2, 6]]);

        $this->assertMatrizAproximada([[0.6, -0.7], [-0.2, 0.4]], $a->inversa()->paraArray());
    }

    #[Test]
    public function a_vezes_a_inversa_deve_dar_identidade()
    {
        $a = new Matriz([[6, 1, 1], [4, -2, 5], [2, 8, 7]]);

        $this->assertMatrizAproximada(
            Matriz::identidade(3)->paraArray(),
            $a->multiplicar($a->inversa())->paraArray()
        );
    }

    #[Test]
    public function inversa_de_matriz_1x1()
    {
        $this->assertMatrizAproximada([[0.25]], (new Matriz([[4]]))->inversa()->paraArray());
    }

    #[Test]
    public function inversa_da_identidade_e_a_identidade()
    {
        $this->assertMatrizAproximada(
            Matriz::identidade(3)->paraArray(),
            Matriz::identidade(3)->inversa()->paraArray()
        );
    }

    #[Test]
    public function inversa_de_matriz_diagonal()
    {
        $a = new Matriz([[2, 0], [0, 4]]);

        $this->assertMatrizAproximada([[0.5, 0], [0, 0.25]], $a->inversa()->paraArray());
    }

    #[Test]
    public function inversa_precisa_trocar_linhas_quando_o_pivo_e_zero()
    {
        $a = new Matriz([[0, 1], [1, 0]]);

        $this->assertMatrizAproximada([[0, 1], [1, 0]], $a->inversa()->paraArray());
    }

    #[Test]
    public function nao_deve_inverter_matriz_singular()
    {
        $this->expectException(MatrizSingularException::class);
        (new Matriz([[1, 2], [2, 4]]))->inversa();
    }

    #[Test]
    public function nao_deve_inverter_matriz_nula()
    {
        $this->expectException(MatrizSingularException::class);
        Matriz::nula(2, 2)->inversa();
    }

    #[Test]
    public function nao_deve_inverter_matriz_nao_quadrada()
    {
        $this->expectException(DimensoesIncompativeisException::class);
        (new Matriz([[1, 2, 3], [4, 5, 6]]))->inversa();
    }
}
