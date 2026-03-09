@php
    $statusMap = [
        1 => ['label' => 'Confirmado', 'class' => 'success', 'icon' => 'fa-check-circle'],
        2 => ['label' => 'Pendente', 'class' => 'warning', 'icon' => 'fa-clock'],
        3 => ['label' => 'Rejeitado', 'class' => 'danger', 'icon' => 'fa-times-circle'],
        4 => ['label' => 'Cancelado', 'class' => 'secondary', 'icon' => 'fa-ban'],
        5 => ['label' => 'Reembolsado', 'class' => 'info', 'icon' => 'fa-undo'],
        6 => ['label' => 'Contestado', 'class' => 'dark', 'icon' => 'fa-exclamation-triangle'],
    ];

    $info = $statusMap[$status ?? 0] ?? ['label' => 'Desconhecido', 'class' => 'secondary', 'icon' => 'fa-question-circle'];
@endphp

<span class="badge bg-{{ $info['class'] }}">
    <i class="fas {{ $info['icon'] }} me-1"></i>{{ $info['label'] }}
</span>
