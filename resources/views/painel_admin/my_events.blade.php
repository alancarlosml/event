<x-site-layout>
    <main id="main">
        <section class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="/">Home</a></li>
                    <li><a href="/painel/meus-eventos">{{ $isSuperAdmin ?? false ? 'Todos os eventos' : 'Meus eventos' }}</a></li>
                </ol>
                <h2>{{ $isSuperAdmin ?? false ? 'Todos os eventos da plataforma' : 'Listar todos' }}</h2>
            </div>
        </section>

        <section class="inner-page" id="create-event-form">
            <div class="container">
                <div class="app-page-head">
                    <div class="app-page-copy">
                        <span class="app-page-kicker">{{ $isSuperAdmin ?? false ? 'Operação da plataforma' : 'Painel do organizador' }}</span>
                        <h1 class="app-page-title">{{ $isSuperAdmin ?? false ? 'Todos os eventos da plataforma' : 'Eventos sob sua gestão' }}</h1>
                        <p class="app-page-subtitle">Acompanhe status, aplique filtros persistentes e retome seu trabalho com paginação estável.</p>
                    </div>
                    <div class="app-page-actions">
                        <a href="{{ route('event_home.create_event_link') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Criar evento
                        </a>
                    </div>
                </div>

                <div class="mb-3 ps-3 pe-3">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                            <strong>{{ $message }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2" aria-hidden="true"></i>
                            <strong>Erros encontrados:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                        </div>
                    @endif
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <form method="GET" action="{{ route('event_home.my_events') }}" class="row g-2 align-items-end">
                            <div class="col-lg-4">
                                <label for="q" class="form-label mb-1">Busca</label>
                                <input type="text" id="q" name="q" class="form-control" value="{{ $filters['q'] ?? '' }}" placeholder="Nome, hash ou local">
                            </div>
                            <div class="col-lg-2">
                                <label for="status" class="form-label mb-1">Status</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="all" {{ ($filters['status'] ?? 'all') === 'all' ? 'selected' : '' }}>Todos</option>
                                    <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Ativos</option>
                                    <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Não ativos</option>
                                    <option value="incomplete" {{ ($filters['status'] ?? '') === 'incomplete' ? 'selected' : '' }}>Incompletos</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="date_from" class="form-label mb-1">Criado de</label>
                                <input type="date" id="date_from" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                            </div>
                            <div class="col-lg-2">
                                <label for="date_to" class="form-label mb-1">Criado até</label>
                                <input type="date" id="date_to" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                            </div>
                            <div class="col-lg-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">Filtrar</button>
                                <a href="{{ route('event_home.my_events', ['reset_filters' => 1]) }}" class="btn btn-outline-secondary">Limpar</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card events-table-card">
                    <div class="card-body table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Detalhes</th>
                                    <th>Responsável</th>
                                    <th>Local</th>
                                    <th>Data Criação</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events as $event)
                                    @php
                                        $isIncomplete = $event->place_name == '' || $event->participante_name == '' || $event->event_date == '' || $event->lote_name == '';
                                    @endphp
                                    <tr class="{{ $isIncomplete ? 'table-warning' : '' }}">
                                        <td>{{ $event->id }}</td>
                                        <td>
                                            <b>Nome:</b> {{ $event->name }} <br>
                                            <b>Data do evento:</b>
                                            @if($event->date_event_min == $event->date_event_max)
                                                {{ \Carbon\Carbon::parse($event->date_event_min)->format('d/m/Y') }}
                                            @else
                                                De {{ \Carbon\Carbon::parse($event->date_event_min)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($event->date_event_max)->format('d/m/Y') }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $event->admin_name }} <br>
                                            <small>{{ $event->admin_email }}</small>
                                        </td>
                                        <td>{{ $event->place_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($event->created_at)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($isIncomplete)
                                                <span class="badge bg-danger">Incompleto</span>
                                            @elseif($event->status == 1)
                                                <span class="badge bg-success">Ativo</span>
                                            @else
                                                <span class="badge bg-warning">Não ativo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="{{ route('event_home.event_manage', $event->hash) }}">
                                                <i class="fas fa-eye me-1"></i>Detalhes
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">Nenhum evento encontrado com os filtros selecionados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3 d-flex justify-content-end">
                            {{ $events->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @push('head')
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
    @endpush
</x-site-layout>
