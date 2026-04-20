<x-site-layout>
    <main id="main">
        <section class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="/">Home</a></li>
                    <li><a href="/painel/meus-eventos">Meus eventos</a></li>
                </ol>
                <h2>Certificados: {{ $event->name }}</h2>
            </div>
        </section>

        <section class="inner-page" id="create-event-form">
            <div class="container">
                <div class="mb-3 px-3">
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

                @include('painel_admin.partials.certificate_settings_form', ['event' => $event])

                <div class="mt-3">
                    <a href="{{ route('event_home.my_events') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar para meus eventos
                    </a>
                </div>
            </div>
        </section>
    </main>

    @push('head')
        <link rel="stylesheet" href="{{ asset('assets_admin/css/modern-admin.css') }}" type="text/css">
    @endpush

    @push('footer')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const certificateEnabledSwitch = document.getElementById('certificate_enabled_switch');
                const certificateEnabledInput = document.getElementById('certificate_enabled_input');
                const certificateEnabledLabel = document.querySelector('label[for="certificate_enabled_switch"]');

                if (certificateEnabledSwitch && certificateEnabledInput && certificateEnabledLabel) {
                    certificateEnabledSwitch.addEventListener('change', function () {
                        certificateEnabledInput.value = this.checked ? '1' : '0';
                        certificateEnabledLabel.textContent = this.checked ? 'Ativado' : 'Desativado';
                    });
                }
            });
        </script>
    @endpush
</x-site-layout>
