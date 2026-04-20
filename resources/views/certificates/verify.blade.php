<x-site-layout>
    <main id="main">
        <section class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="/">Home</a></li>
                </ol>
                <h2>Verificar Certificado</h2>
            </div>
        </section>

        <section class="inner-page">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 rounded-3">
                            <div class="card-body p-4">
                                <h5 class="mb-3 text-center">
                                    <i class="fas fa-certificate me-2"></i>Verificação de Certificado
                                </h5>

                                <form action="{{ route('certificate.verify') }}" method="GET">
                                    <div class="mb-3">
                                        <label for="code" class="form-label">Código do certificado</label>
                                        <input type="text" name="code" id="code" class="form-control form-control-lg text-center"
                                               placeholder="Ex: ABC123XYZ456"
                                               value="{{ $code ?? '' }}"
                                               style="letter-spacing: 3px; text-transform: uppercase;">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i> Verificar
                                    </button>
                                </form>

                                @if($code)
                                    <hr class="my-4">
                                    @if($certificate)
                                        <div class="alert alert-success">
                                            <h6 class="alert-heading"><i class="fas fa-check-circle me-1"></i> Certificado Válido</h6>
                                            <hr>
                                            <p class="mb-1"><strong>Participante:</strong> {{ $certificate->participant_display_name }}</p>
                                            <p class="mb-1"><strong>Evento:</strong> {{ $certificate->event->name }}</p>
                                            @if($certificate->event->certificate_hours)
                                                <p class="mb-1"><strong>Carga horária:</strong> {{ $certificate->event->certificate_hours }}</p>
                                            @endif
                                            <p class="mb-0"><strong>Emitido em:</strong> {{ $certificate->issued_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    @else
                                        <div class="alert alert-danger">
                                            <h6 class="alert-heading"><i class="fas fa-times-circle me-1"></i> Certificado Não Encontrado</h6>
                                            <p class="mb-0">Nenhum certificado foi encontrado com o código informado. Verifique se digitou corretamente.</p>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-site-layout>
