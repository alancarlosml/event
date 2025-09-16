<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Adicione aqui a exceção para sua rota de pagamento
        '*/obrigado',  // Isso cobre qualquer /algo/obrigado (o * representa o {slug})

        // Outras exceções que você já possa ter, como webhooks
        'webhooks/mercado-pago/notification',  // Exemplo para webhook do Mercado Pago, se você tiver
    ];
}
