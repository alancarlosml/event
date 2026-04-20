<div class="card shadow-sm border-0 rounded-3">
    <div class="card-body border-top">
        <h5 class="mb-3"><i class="fas fa-certificate me-2"></i>Certificado</h5>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('event_home.certificate.settings', $event->hash) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Emitir certificado?</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="certificate_enabled_switch" {{ $event->certificate_enabled ? 'checked' : '' }}>
                        <label class="form-check-label" for="certificate_enabled_switch">
                            {{ $event->certificate_enabled ? 'Ativado' : 'Desativado' }}
                        </label>
                    </div>
                    <input type="hidden" name="certificate_enabled" id="certificate_enabled_input" value="{{ $event->certificate_enabled ? 1 : 0 }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Regra de liberação</label>
                    <select name="certificate_release_mode" class="form-select">
                        <option value="paid" {{ ($event->certificate_release_mode ?? 'paid') === 'paid' ? 'selected' : '' }}>Pagamento confirmado</option>
                        <option value="checkin" {{ ($event->certificate_release_mode ?? 'paid') === 'checkin' ? 'selected' : '' }}>Check-in realizado</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Carga horária</label>
                    <input type="text" name="certificate_hours" class="form-control"
                           placeholder="Ex: 8h, 40h"
                           value="{{ old('certificate_hours', $event->certificate_hours) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nome do assinante</label>
                    <input type="text" name="certificate_signature_name" class="form-control"
                           placeholder="Nome que aparecerá no certificado"
                           value="{{ old('certificate_signature_name', $event->certificate_signature_name) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Logo do certificado</label>
                    @if($event->certificate_logo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $event->certificate_logo) }}" alt="Logo" style="max-height: 80px;">
                            <button type="submit"
                                    class="btn btn-sm btn-outline-danger ms-2"
                                    title="Remover logo"
                                    formaction="{{ route('event_home.certificate.delete_image', [$event->hash, 'logo']) }}"
                                    formmethod="POST"
                                    name="_method"
                                    value="DELETE"
                                    onclick="return confirm('Deseja remover a logo do certificado?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    @endif
                    <input type="file" name="certificate_logo" class="form-control" accept="image/jpeg,image/png">
                    <small class="text-muted">JPG ou PNG, máx. 2MB</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Imagem da assinatura</label>
                    @if($event->certificate_signature_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $event->certificate_signature_image) }}" alt="Assinatura" style="max-height: 80px;">
                            <button type="submit"
                                    class="btn btn-sm btn-outline-danger ms-2"
                                    title="Remover assinatura"
                                    formaction="{{ route('event_home.certificate.delete_image', [$event->hash, 'signature']) }}"
                                    formmethod="POST"
                                    name="_method"
                                    value="DELETE"
                                    onclick="return confirm('Deseja remover a assinatura do certificado?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    @endif
                    <input type="file" name="certificate_signature_image" class="form-control" accept="image/jpeg,image/png">
                    <small class="text-muted">JPG ou PNG, máx. 2MB. Ideal: fundo transparente (PNG)</small>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Salvar configurações de certificado
            </button>
        </form>
    </div>
</div>
