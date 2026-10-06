<?php

namespace Controller;

use InvalidArgumentException;
use LogicException;
use Model\Matriz;
use Model\SistemaLinear;

class MatrizController
{
    public function __construct(private SistemaLinear $sistema = new SistemaLinear())
    {
    }

  
    public function executar(string $operacao, string $entradaA, string $entradaB = ''): array
    {
        try {
            $a = $this->lerMatriz($entradaA);

            return match ($operacao) {
                'somar' => $this->sucesso('matriz', $a->somar($this->lerMatriz($entradaB))->paraArray()),
                'subtrair' => $this->sucesso('matriz', $a->subtrair($this->lerMatriz($entradaB))->paraArray()),
                'multiplicar' => $this->sucesso('matriz', $a->multiplicar($this->lerMatriz($entradaB))->paraArray()),
                'escalar' => $this->sucesso('matriz', $a->multiplicarPorEscalar($this->lerNumero($entradaB))->paraArray()),
                'transpor' => $this->sucesso('matriz', $a->transpor()->paraArray()),
                'determinante' => $this->sucesso('numero', $a->determinante()),
                'inversa' => $this->sucesso('matriz', $a->inversa()->paraArray()),
                'gauss' => $this->sucesso('vetor', $this->sistema->resolver($a, $this->lerVetor($entradaB))),
                'inversa_sistema' => $this->sucesso('vetor', $this->sistema->resolverPorInversa($a, $this->lerVetor($entradaB))),
                default => throw new InvalidArgumentException("Operação desconhecida."),
            };
        } catch (LogicException $erro) {
       
            return [
                'sucesso' => false,
                'tipo' => null,
                'resultado' => null,
                'mensagem' => $erro->getMessage(),
            ];
        }
    }

   
    public function lerMatriz(string $texto): Matriz
    {
        $texto = str_replace(["\r\n", "\r", ";"], "\n", $texto);

        $linhas = [];
        foreach (explode("\n", $texto) as $linha) {
            $linha = trim($linha);
            if ($linha !== '') {
                $linhas[] = $linha;
            }
        }

        if (count($linhas) === 0) {
            throw new InvalidArgumentException("Preencha a matriz.");
        }

        $dados = [];
        foreach ($linhas as $linha) {
            $numeros = [];
            foreach (preg_split('/\s+/', $linha) as $parte) {
                $numeros[] = $this->lerNumero($parte);
            }
            $dados[] = $numeros;
        }

        return new Matriz($dados);
    }

 
    public function lerVetor(string $texto): array
    {
        $partes = preg_split('/[\s;]+/', trim($texto), -1, PREG_SPLIT_NO_EMPTY);

        if (count($partes) === 0) {
            throw new InvalidArgumentException("Preencha o vetor b.");
        }

        $vetor = [];
        foreach ($partes as $parte) {
            $vetor[] = $this->lerNumero($parte);
        }

        return $vetor;
    }

  
    public function lerNumero(string $texto): float
    {
        $texto = str_replace(',', '.', trim($texto));

        if ($texto === '' || !is_numeric($texto)) {
            throw new InvalidArgumentException("Valor inválido: \"{$texto}\". Use apenas números.");
        }

        $numero = (float) $texto;

        if (!is_finite($numero)) {
            throw new InvalidArgumentException("O número \"{$texto}\" é grande demais.");
        }

        return $numero;
    }


    public static function formatarNumero(float $numero): string
    {
        $texto = rtrim(rtrim(number_format($numero, 4, '.', ''), '0'), '.');

       
        if ($texto === '' || $texto === '-0') {
            return '0';
        }

        return $texto;
    }

    private function sucesso(string $tipo, array|float $resultado): array
    {
        return [
            'sucesso' => true,
            'tipo' => $tipo,
            'resultado' => $resultado,
            'mensagem' => '',
        ];
    }
}
