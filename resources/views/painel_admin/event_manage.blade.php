<x-site-layout>
    <main id="main">
        <section class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="/">Home</a></li>
                    <li><a href="/painel/meus-eventos">Meus eventos</a></li>
                </ol>
                <h2>Gestão do evento: {{ $event->name }}</h2>
            </div>
        </section>

        <section class="inner-page">
            <div class="container">
                <div class="panel-shell">
                    <aside class="panel-menu-card">
                        <div class="panel-menu-header">
                            <h5>Menu do evento</h5>
                        </div>

                        <div class="list-group list-group-flush">
                            <a href="{{ route('event_home.event_manage', [$event->hash, 'tab' => 'details']) }}"
                               class="panel-menu-link manage-tab-link {{ $tab === 'details' ? 'active' : '' }}"
                               data-manage-tab="details"
                               data-content-url="{{ $contentRoutes['details'] }}">
                                <i class="fas fa-eye"></i>
                                <span>Detalhes</span>
                            </a>
                            <a href="{{ route('event_home.event_manage', [$event->hash, 'tab' => 'reports']) }}"
                               class="panel-menu-link manage-tab-link {{ $tab === 'reports' ? 'active' : '' }}"
                               data-manage-tab="reports"
                               data-content-url="{{ $contentRoutes['reports'] }}">
                                <i class="fa-solid fa-chart-pie"></i>
                                <span>Relatórios</span>
                            </a>

                            @if($canManage)
                                <a href="{{ route('event_home.event_manage', [$event->hash, 'tab' => 'guests']) }}"
                                   class="panel-menu-link manage-tab-link {{ $tab === 'guests' ? 'active' : '' }}"
                                   data-manage-tab="guests"
                                   data-content-url="{{ $contentRoutes['guests'] }}">
                                    <i class="fas fa-person"></i>
                                    <span>Usuários</span>
                                </a>
                                <a href="{{ route('event_home.event_manage', [$event->hash, 'tab' => 'messages']) }}"
                                   class="panel-menu-link manage-tab-link {{ $tab === 'messages' ? 'active' : '' }}"
                                   data-manage-tab="messages"
                                   data-content-url="{{ $contentRoutes['messages'] }}">
                                    <i class="fa-solid fa-envelope"></i>
                                    <span>Contatos</span>
                                </a>
                                <a href="{{ route('event_home.event_manage', [$event->hash, 'tab' => 'certificates']) }}"
                                   class="panel-menu-link manage-tab-link {{ $tab === 'certificates' ? 'active' : '' }}"
                                   data-manage-tab="certificates"
                                   data-content-url="{{ $contentRoutes['certificates'] }}">
                                    <i class="fas fa-certificate"></i>
                                    <span>Certificados</span>
                                </a>
                                <a href="{{ route('event_home.event_manage', [$event->hash, 'tab' => 'edit']) }}"
                                   class="panel-menu-link manage-tab-link {{ $tab === 'edit' ? 'active' : '' }}"
                                   data-manage-tab="edit"
                                   data-content-url="{{ $contentRoutes['edit'] }}">
                                    <i class="fas fa-pencil-alt"></i>
                                    <span>Editar</span>
                                </a>
                            @endif

                            @if($event->place?->name && $event->eventDates->min('date') && $event->lotes->first())
                                <a href="/{{ $event->slug }}" target="_blank" class="panel-menu-link">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    <span>Link do evento</span>
                                </a>
                            @endif

                            @if($canManage)
                                <a href="{{ route('event_home.event_clone', $event->hash) }}" class="panel-menu-link">
                                    <i class="fa-solid fa-copy"></i>
                                    <span>Duplicar</span>
                                </a>
                                <button type="button" class="panel-menu-link text-danger text-start" data-bs-toggle="modal" data-bs-target="#removeEventModal">
                                    <i class="fas fa-trash"></i>
                                    <span>Remover</span>
                                </button>
                            @endif
                        </div>
                    </aside>

                    <section class="panel-content-card">
                        {{-- <div class="panel-content-header d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3">
                            <div class="app-page-copy">
                                <span class="app-page-kicker">Gestão central</span>
                                <h1 class="app-page-title">{{ $event->name }}</h1>
                                <p class="app-page-subtitle">
                                    Status:
                                    @if($event->status == 1)
                                        <span class="badge bg-success ms-1">Ativo</span>
                                    @else
                                        <span class="badge bg-warning text-dark ms-1">Não ativo</span>
                                    @endif
                                </p>
                            </div>
                            <a href="{{ route('event_home.my_events') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Voltar aos eventos
                            </a>
                        </div> --}}

                        <div id="event-manage-stage" class="panel-content-stage">
                            <div class="panel-loading-state">Carregando o conteúdo da aba selecionada...</div>
                        </div>

                        <noscript>
                            <div class="panel-content-stage">
                                <div class="panel-empty-state">
                                    <div>
                                        <p>Seu navegador está com JavaScript desativado.</p>
                                        <a href="{{ $contentUrl }}" class="btn btn-primary">Abrir conteúdo atual</a>
                                    </div>
                                </div>
                            </div>
                        </noscript>
                    </section>
                </div>
            </div>
        </section>
    </main>

    @if($canManage)
        <div class="modal fade" id="removeEventModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Remoção de evento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        Deseja realmente remover esse evento?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                        <form action="{{ route('event_home.destroy', $event->hash) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Sim, remover</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('head')
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
    @endpush

    @push('footer')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const stage = document.getElementById('event-manage-stage');
                const tabLinks = Array.from(document.querySelectorAll('.manage-tab-link'));
                const loadedStyles = new Set(
                    Array.from(document.querySelectorAll('link[rel="stylesheet"][href]')).map((item) => item.href)
                );
                const loadedScripts = new Set(
                    Array.from(document.querySelectorAll('script[src]')).map((item) => item.src)
                );

                if (!stage || !tabLinks.length) {
                    return;
                }

                const setActiveTab = (activeLink) => {
                    tabLinks.forEach((link) => link.classList.toggle('active', link === activeLink));
                };

                const renderLoading = () => {
                    stage.innerHTML = '<div class="panel-loading-state">Carregando o conteúdo da aba selecionada...</div>';
                };

                const renderError = (fallbackUrl) => {
                    stage.innerHTML = `
                        <div class="panel-empty-state">
                            <div>
                                <p>Não foi possível carregar esse conteúdo dentro da gestão central.</p>
                                <a href="${fallbackUrl}" class="btn btn-primary">Abrir página completa</a>
                            </div>
                        </div>
                    `;
                };

                const ensureStyles = (doc) => {
                    doc.querySelectorAll('link[rel="stylesheet"][href]').forEach((styleTag) => {
                        if (loadedStyles.has(styleTag.href)) {
                            return;
                        }

                        const cloned = document.createElement('link');
                        cloned.rel = 'stylesheet';
                        cloned.href = styleTag.href;
                        document.head.appendChild(cloned);
                        loadedStyles.add(styleTag.href);
                    });
                };

                const executeScripts = async (doc) => {
                    const scripts = Array.from(doc.querySelectorAll('script'));

                    for (const script of scripts) {
                        if (script.src) {
                            if (loadedScripts.has(script.src)) {
                                continue;
                            }

                            await new Promise((resolve, reject) => {
                                const cloned = document.createElement('script');
                                cloned.src = script.src;
                                cloned.onload = resolve;
                                cloned.onerror = reject;
                                document.body.appendChild(cloned);
                                loadedScripts.add(script.src);
                            });

                            continue;
                        }

                        const code = script.textContent.trim();

                        if (!code) {
                            continue;
                        }

                        const inline = document.createElement('script');
                        inline.textContent = code;
                        document.body.appendChild(inline);
                        inline.remove();
                    }
                };

                const loadTab = async (link, shouldPushState = true) => {
                    const contentUrl = link.dataset.contentUrl;

                    if (!contentUrl) {
                        window.location.href = link.href;
                        return;
                    }

                    setActiveTab(link);
                    renderLoading();

                    try {
                        const response = await fetch(contentUrl, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Falha ao carregar conteúdo');
                        }

                        const html = await response.text();
                        const doc = new DOMParser().parseFromString(html, 'text/html');
                        const fragment = doc.querySelector('.inner-page > .container')
                            || doc.querySelector('.inner-page')
                            || doc.querySelector('main')
                            || doc.body;

                        stage.innerHTML = `<div class="panel-content-fragment">${fragment.innerHTML}</div>`;

                        ensureStyles(doc);
                        await executeScripts(doc);

                        if (shouldPushState) {
                            window.history.pushState({ tab: link.dataset.manageTab }, '', link.href);
                        }
                    } catch (error) {
                        const fallbackUrl = contentUrl
                            .replace(/([?&])embedded=1(&?)/, '$1')
                            .replace(/[?&]$/, '');
                        renderError(fallbackUrl);
                    }
                };

                const getLinkFromLocation = () => {
                    const tab = new URL(window.location.href).searchParams.get('tab') || 'details';
                    return tabLinks.find((link) => link.dataset.manageTab === tab) || tabLinks[0];
                };

                tabLinks.forEach((link) => {
                    link.addEventListener('click', function (event) {
                        if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                            return;
                        }

                        event.preventDefault();
                        loadTab(link);
                    });
                });

                window.addEventListener('popstate', function () {
                    const currentLink = getLinkFromLocation();
                    loadTab(currentLink, false);
                });

                loadTab(getLinkFromLocation(), false);
            });
        </script>
    @endpush
</x-site-layout>
