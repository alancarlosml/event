<x-site-layout>
    <main id="main">
        <section class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="/">Home</a></li>
                    <li><a href="/painel/minhas-inscricoes">Minhas inscrições</a></li>
                </ol>
                <h2>Certificados do pedido {{ $order->hash }}</h2>
            </div>
        </section>

        <section class="inner-page">
            <div class="container">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                        <div>
                            <h4 class="mb-1">Certificados disponíveis</h4>
                            <small class="text-muted">
                                Evento: {{ $event->name }} |
                                Regra de liberação:
                                {{ $releaseMode === 'checkin' ? 'check-in realizado' : 'pagamento confirmado' }}
                            </small>
                        </div>
                        <a href="{{ route('event_home.my_registrations') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Voltar
                        </a>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ingresso</th>
                                        <th>Participante</th>
                                        <th>Lote</th>
                                        <th>Status</th>
                                        <th class="text-end">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($certificates as $item)
                                        <tr>
                                            <td>#{{ $item->order_item->number }}</td>
                                            <td>{{ $item->participant_name }}</td>
                                            <td>{{ $item->order_item->lote?->name ?? 'Não informado' }}</td>
                                            <td>
                                                @if($item->is_eligible)
                                                    <span class="badge bg-success">{{ $item->status_label }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $item->status_label }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if($item->download_url)
                                                    <a href="{{ $item->download_url }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-download me-1"></i>Baixar PDF
                                                    </a>
                                                @else
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                                        <i class="fas fa-lock me-1"></i>Indisponível
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Nenhum ingresso encontrado neste pedido.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-site-layout>
