<?php

use Controller\MatrizController;

// escapa o texto antes de mostrar na tela
function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

$operacoes = [
    'somar' => 'Somar (A + B)',
    'subtrair' => 'Subtrair (A - B)',
    'multiplicar' => 'Multiplicar (A x B)',
    'escalar' => 'Multiplicar por escalar (k x A)',
    'transpor' => 'Transposta de A',
    'determinante' => 'Determinante de A',
    'inversa' => 'Inversa de A',
    'gauss' => 'Resolver sistema A.x = b (Gauss)',
    'inversa_sistema' => 'Resolver sistema A.x = b (pela inversa)',
];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="templates/css/style.css">
    <title>Álgebra Linear | Matrizes e Sistemas</title>
</head>

<body>
    <main class="container py-4">
        <h1 class="titulo mb-1">Álgebra Linear</h1>
        <p class="text-secondary mb-4">Operações com matrizes e sistemas lineares.</p>

        <form method="POST" class="card p-4 mb-4">
            <div class="mb-3">
                <label for="operacao" class="form-label fw-bold">Operação</label>
                <select name="operacao" id="operacao" class="form-select">
                    <?php foreach ($operacoes as $valor => $nome): ?>
                        <option value="<?= e($valor) ?>" <?= $operacao === $valor ? 'selected' : '' ?>><?= e($nome) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="entradaA" class="form-label fw-bold">Matriz A</label>
                    <textarea name="entradaA" id="entradaA" rows="5" class="form-control campo-matriz"
                        placeholder="1 2&#10;3 4"><?= e($entradaA) ?></textarea>
                    <div class="form-text">Uma linha por linha da matriz, números separados por espaço.</div>
                </div>

                <div class="col-md-6" id="blocoB">
                    <label for="entradaB" class="form-label fw-bold" id="rotuloB">Matriz B</label>
                    <textarea name="entradaB" id="entradaB" rows="5" class="form-control campo-matriz"><?= e($entradaB) ?></textarea>
                    <div class="form-text" id="ajudaB"></div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4 align-self-start">Calcular</button>
        </form>

        <?php if ($resultado !== null): ?>
            <section class="card p-4">
                <h2 class="h5 mb-3">Resultado</h2>

                <?php if (!$resultado['sucesso']): ?>
                    <div class="alert alert-danger mb-0"><?= e($resultado['mensagem']) ?></div>

                <?php elseif ($resultado['tipo'] === 'matriz'): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered w-auto text-center mb-0">
                            <?php foreach ($resultado['resultado'] as $linha): ?>
                                <tr>
                                    <?php foreach ($linha as $valor): ?>
                                        <td><?= e(MatrizController::formatarNumero($valor)) ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>

                <?php elseif ($resultado['tipo'] === 'numero'): ?>
                    <p class="fs-4 mb-0"><?= e(MatrizController::formatarNumero($resultado['resultado'])) ?></p>

                <?php else: ?>
                    <ul class="list-unstyled fs-5 mb-0">
                        <?php foreach ($resultado['resultado'] as $i => $valor): ?>
                            <li>x<sub><?= $i + 1 ?></sub> = <?= e(MatrizController::formatarNumero($valor)) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>

    <script>
        // muda o campo B de acordo com a operação escolhida
        const select = document.getElementById('operacao');
        const blocoB = document.getElementById('blocoB');
        const rotuloB = document.getElementById('rotuloB');
        const ajudaB = document.getElementById('ajudaB');

        function ajustarCampoB() {
            const op = select.value;

            if (['somar', 'subtrair', 'multiplicar'].includes(op)) {
                blocoB.style.display = '';
                rotuloB.textContent = 'Matriz B';
                ajudaB.textContent = 'Mesmo formato da matriz A.';
            } else if (op === 'escalar') {
                blocoB.style.display = '';
                rotuloB.textContent = 'Escalar k';
                ajudaB.textContent = 'Um único número (pode usar vírgula).';
            } else if (op === 'gauss' || op === 'inversa_sistema') {
                blocoB.style.display = '';
                rotuloB.textContent = 'Vetor b (termos independentes)';
                ajudaB.textContent = 'Um número por equação, separados por espaço.';
            } else {
                blocoB.style.display = 'none';
            }
        }

        select.addEventListener('change', ajustarCampoB);
        ajustarCampoB();
    </script>
</body>

</html>
