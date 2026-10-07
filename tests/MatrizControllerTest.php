<?php

namespace Tests;

use Controller\MatrizController;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(MatrizController::class)]
class MatrizControllerTest extends TestCase
{
    use ComparaMatriz;

    private MatrizController $controller;

    protected function setUp(): void
    {
        $this->controller = new MatrizController();
    }

  

    #[Test]
    public function deve_ler_matriz_com_quebra_de_linha()
    {
        $m = $this->controller->lerMatriz("1 2\n3 4");

        $this->assertMatrizAproximada([[1, 2], [3, 4]], $m->paraArray());
    }

    #[Test]
    public function deve_ler_matriz_com_ponto_e_virgula_e_enter_do_windows()
    {
        $m = $this->controller->lerMatriz("1 2; 3 4\r\n5 6");

        $this->assertMatrizAproximada([[1, 2], [3, 4], [5, 6]], $m->paraArray());
    }

    #[Test]
    public function deve_ler_numeros_com_virgula_decimal_e_espacos_extras()
    {
        $m = $this->controller->lerMatriz("  1,5    -2 \n\n 0.25 3  ");

        $this->assertMatrizAproximada([[1.5, -2], [0.25, 3]], $m->paraArray());
    }

    #[Test]
    public function nao_deve_ler_matriz_vazia()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->controller->lerMatriz("   \n  ");
    }

    #[Test]
    public function nao_deve_ler_matriz_com_letras()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->controller->lerMatriz("1 a\n3 4");
    }

    #[Test]
    public function nao_deve_ler_numero_gigante()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->controller->lerNumero("1e999");
    }

    #[Test]
    public function deve_ler_vetor()
    {
        $this->assertEqualsWithDelta([3.0, 1.5, -2.0], $this->controller->lerVetor("3 1,5\n-2"), 1e-12);
    }

    #[Test]
    public function nao_deve_ler_vetor_vazio()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->controller->lerVetor("  ");
    }

   

    #[Test]
    public function deve_somar_pelo_controller()
    {
        $r = $this->controller->executar('somar', "1 2\n3 4", "5 6\n7 8");

        $this->assertTrue($r['sucesso']);
        $this->assertSame('matriz', $r['tipo']);
        $this->assertMatrizAproximada([[6, 8], [10, 12]], $r['resultado']);
    }

    #[Test]
    public function deve_subtrair_pelo_controller()
    {
        $r = $this->controller->executar('subtrair', "5 6\n7 8", "1 2\n3 4");

        $this->assertMatrizAproximada([[4, 4], [4, 4]], $r['resultado']);
    }

    #[Test]
    public function deve_multiplicar_pelo_controller()
    {
        $r = $this->controller->executar('multiplicar', "1 2\n3 4", "5 6\n7 8");

        $this->assertMatrizAproximada([[19, 22], [43, 50]], $r['resultado']);
    }

    #[Test]
    public function deve_multiplicar_por_escalar_pelo_controller()
    {
        $r = $this->controller->executar('escalar', "1 2\n3 4", "2,5");

        $this->assertMatrizAproximada([[2.5, 5], [7.5, 10]], $r['resultado']);
    }

    #[Test]
    public function deve_transpor_pelo_controller()
    {
        $r = $this->controller->executar('transpor', "1 2 3\n4 5 6");

        $this->assertMatrizAproximada([[1, 4], [2, 5], [3, 6]], $r['resultado']);
    }

    #[Test]
    public function deve_calcular_determinante_pelo_controller()
    {
        $r = $this->controller->executar('determinante', "1 2\n3 4");

        $this->assertSame('numero', $r['tipo']);
        $this->assertEqualsWithDelta(-2.0, $r['resultado'], 1e-9);
    }

    #[Test]
    public function deve_calcular_inversa_pelo_controller()
    {
        $r = $this->controller->executar('inversa', "4 7\n2 6");

        $this->assertMatrizAproximada([[0.6, -0.7], [-0.2, 0.4]], $r['resultado']);
    }

    #[Test]
    public function deve_resolver_sistema_pelo_controller()
    {
        $r = $this->controller->executar('gauss', "1 1\n1 -1", "3 1");

        $this->assertTrue($r['sucesso']);
        $this->assertSame('vetor', $r['tipo']);
        $this->assertEqualsWithDelta([2.0, 1.0], $r['resultado'], 1e-9);
    }

    #[Test]
    public function deve_resolver_sistema_pela_inversa_no_controller()
    {
        $r = $this->controller->executar('inversa_sistema', "1 1\n1 -1", "3 1");

        $this->assertEqualsWithDelta([2.0, 1.0], $r['resultado'], 1e-9);
    }



    #[Test]
    public function deve_devolver_erro_com_dimensoes_incompativeis()
    {
        $r = $this->controller->executar('somar', "1 2\n3 4", "1 2 3");

        $this->assertFalse($r['sucesso']);
        $this->assertNull($r['resultado']);
        $this->assertStringContainsString('mesmo tamanho', $r['mensagem']);
    }

    #[Test]
    public function deve_devolver_erro_com_matriz_singular()
    {
        $r = $this->controller->executar('inversa', "1 2\n2 4");

        $this->assertFalse($r['sucesso']);
        $this->assertStringContainsString('singular', $r['mensagem']);
    }

    #[Test]
    public function deve_devolver_erro_com_sistema_impossivel()
    {
        $r = $this->controller->executar('gauss', "1 1\n2 2", "1 5");

        $this->assertFalse($r['sucesso']);
        $this->assertStringContainsString('impossível', $r['mensagem']);
    }

    #[Test]
    public function deve_devolver_erro_com_sistema_indeterminado()
    {
        $r = $this->controller->executar('gauss', "1 1\n2 2", "2 4");

        $this->assertFalse($r['sucesso']);
        $this->assertStringContainsString('indeterminado', $r['mensagem']);
    }

    #[Test]
    public function deve_devolver_erro_com_operacao_desconhecida()
    {
        $r = $this->controller->executar('dividir', "1 2\n3 4");

        $this->assertFalse($r['sucesso']);
        $this->assertSame('Operação desconhecida.', $r['mensagem']);
    }

    #[Test]
    public function deve_devolver_erro_com_entrada_invalida()
    {
        $r = $this->controller->executar('somar', "1 x", "1 2");

        $this->assertFalse($r['sucesso']);
        $this->assertNotSame('', $r['mensagem']);
    }

 

    #[Test]
    public function deve_formatar_numeros_para_a_tela()
    {
        $this->assertSame('2', MatrizController::formatarNumero(2.0));
        $this->assertSame('100', MatrizController::formatarNumero(100.0));
        $this->assertSame('0.6', MatrizController::formatarNumero(0.6));
        $this->assertSame('-2.5', MatrizController::formatarNumero(-2.5));
        $this->assertSame('0.3333', MatrizController::formatarNumero(1 / 3));
        $this->assertSame('0', MatrizController::formatarNumero(0.0));
        $this->assertSame('0', MatrizController::formatarNumero(-0.0));
        $this->assertSame('0', MatrizController::formatarNumero(-1e-12));
    }
}
