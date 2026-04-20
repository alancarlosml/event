<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Certificate;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;

use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    private const RELEASE_MODE_PAID = 'paid';
    private const RELEASE_MODE_CHECKIN = 'checkin';

    public function edit($hash)
    {
        $event = Event::with(['owner', 'eventDates'])->where('hash', $hash)->firstOrFail();

        if (! $this->userCanManageEventCertificates($event)) {
            abort(403);
        }

        return view('painel_admin.event_certificates', compact('event'));
    }

    public function updateSettings(Request $request, $hash)
    {
        $event = Event::where('hash', $hash)->firstOrFail();

        if (! $this->userCanManageEventCertificates($event)) {
            abort(403);
        }

        $validated = $request->validate([
            'certificate_enabled' => 'required|in:0,1',
            'certificate_release_mode' => 'required|in:paid,checkin',
            'certificate_hours' => 'nullable|string|max:50',
            'certificate_signature_name' => 'nullable|string|max:255',
            'certificate_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'certificate_signature_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $event->certificate_enabled = $validated['certificate_enabled'];
        $event->certificate_release_mode = $validated['certificate_release_mode'];
        $event->certificate_hours = $validated['certificate_hours'] ?? null;
        $event->certificate_signature_name = $validated['certificate_signature_name'] ?? null;

        if ($request->hasFile('certificate_logo')) {
            if ($event->certificate_logo && Storage::disk('public')->exists($event->certificate_logo)) {
                Storage::disk('public')->delete($event->certificate_logo);
            }

            $event->certificate_logo = $request->file('certificate_logo')
                ->store('certificates/logos', 'public');
        }

        if ($request->hasFile('certificate_signature_image')) {
            if ($event->certificate_signature_image && Storage::disk('public')->exists($event->certificate_signature_image)) {
                Storage::disk('public')->delete($event->certificate_signature_image);
            }

            $event->certificate_signature_image = $request->file('certificate_signature_image')
                ->store('certificates/signatures', 'public');
        }

        $event->save();

        return back()->with('success', 'Configuracoes de certificado atualizadas com sucesso.');
    }

    public function deleteImage(Request $request, $hash, $type)
    {
        $event = Event::where('hash', $hash)->firstOrFail();

        if (! $this->userCanManageEventCertificates($event)) {
            abort(403);
        }

        $field = $type === 'logo' ? 'certificate_logo' : 'certificate_signature_image';

        if ($event->$field && Storage::disk('public')->exists($event->$field)) {
            Storage::disk('public')->delete($event->$field);
        }

        $event->$field = null;
        $event->save();

        return back()->with('success', 'Imagem removida com sucesso.');
    }

    public function download($orderHash)
    {
        $order = Order::with([
            'participante',
            'event.owner',
            'event.eventDates',
            'order_items.lote',
        ])
            ->where('hash', $orderHash)
            ->where('participante_id', Auth::id())
            ->firstOrFail();

        $event = $order->event;

        if (! $event || ! $event->certificate_enabled) {
            return back()->withErrors(['error' => 'Este evento nao emite certificados.']);
        }

        $eventEnded = $this->eventHasEnded($event);
        $releaseMode = $event->certificate_release_mode ?: self::RELEASE_MODE_PAID;
        $certificates = $order->order_items->map(function (OrderItem $orderItem) use ($event, $order, $eventEnded) {
            $participantName = $orderItem->get_name_participante()?->answer ?? $order->participante->name;
            $isEligible = $this->isOrderItemEligibleForCertificate($event, $order, $orderItem, $eventEnded);

            return (object) [
                'order_item' => $orderItem,
                'participant_name' => $participantName,
                'is_eligible' => $isEligible,
                'status_label' => $this->getEligibilityStatusLabel($event, $order, $orderItem, $eventEnded),
                'download_url' => $isEligible
                    ? route('event_home.certificate.download_item', [$order->hash, $orderItem->id])
                    : null,
            ];
        });

        return view('certificates.list', compact('order', 'event', 'certificates', 'releaseMode'));
    }

    public function downloadItem($orderHash, $orderItemId)
    {
        $order = Order::with([
            'participante',
            'event.owner',
            'event.eventDates',
            'order_items.lote',
        ])
            ->where('hash', $orderHash)
            ->where('participante_id', Auth::id())
            ->where('status', 1)
            ->firstOrFail();

        $event = $order->event;

        if (! $event || ! $event->certificate_enabled) {
            return back()->withErrors(['error' => 'Este evento nao emite certificados.']);
        }

        if (! $this->eventHasEnded($event)) {
            return back()->withErrors(['error' => 'O certificado estara disponivel apos a finalizacao do evento.']);
        }

        $orderItem = $order->order_items->firstWhere('id', (int) $orderItemId);

        if (! $orderItem) {
            abort(404, 'Ingresso nao encontrado para este pedido.');
        }

        if (! $this->isOrderItemEligibleForCertificate($event, $order, $orderItem, true)) {
            return back()->withErrors(['error' => 'Este certificado ainda nao esta disponivel para o ingresso selecionado.']);
        }

        $certificate = Certificate::firstOrCreate(
            [
                'event_id' => $event->id,
                'order_item_id' => $orderItem->id,
            ],
            [
                'order_id' => $order->id,
                'participante_id' => $order->participante_id,
            ]
        );

        $data = [
            'certificate' => $certificate->loadMissing('orderItem'),
            'event' => $event,
            'participante' => $order->participante,
            'participantName' => $certificate->getParticipantName(),
            'logoUrl' => $event->certificate_logo ? $this->getStorageFilePath($event->certificate_logo) : null,
            'signatureUrl' => $event->certificate_signature_image ? $this->getStorageFilePath($event->certificate_signature_image) : null,
            'siteLogoUrl' => $this->getPublicFilePath('assets/img/logo_principal.png'),
            'ownerLogoUrl' => $event->owner?->icon ? $this->getStorageFilePath($event->owner->icon) : null,
        ];

        $pdf = Pdf::loadView('certificates.pdf', $data)
            ->setPaper('a4', 'landscape');

        $filename = 'certificado-' . Str::slug($event->name . '-' . $data['participantName']) . '.pdf';

        return $pdf->download($filename);
    }

    public function verify(Request $request)
    {
        $certificate = null;
        $code = $request->input('code');

        if ($code) {
            $certificate = Certificate::with(['event', 'participante', 'orderItem'])
                ->where('code', strtoupper(trim($code)))
                ->first();
        }

        return view('certificates.verify', compact('certificate', 'code'));
    }

    private function eventHasEnded(Event $event): bool
    {
        $maxDate = $event->max_event_dates();

        return $maxDate && $maxDate < now()->toDateString();
    }

    private function isOrderItemEligibleForCertificate(Event $event, Order $order, OrderItem $orderItem, bool $eventEnded): bool
    {
        if (! $event->certificate_enabled || ! $eventEnded || ! $order->isConfirmed()) {
            return false;
        }

        $releaseMode = $event->certificate_release_mode ?: self::RELEASE_MODE_PAID;

        if ($releaseMode === self::RELEASE_MODE_CHECKIN) {
            return (int) $orderItem->checkin_status === 1;
        }

        return true;
    }

    private function getEligibilityStatusLabel(Event $event, Order $order, OrderItem $orderItem, bool $eventEnded): string
    {
        if (! $eventEnded) {
            return 'Disponivel apos o encerramento do evento';
        }

        if (! $order->isConfirmed()) {
            return 'Pedido com pagamento pendente';
        }

        $releaseMode = $event->certificate_release_mode ?: self::RELEASE_MODE_PAID;
        if ($releaseMode === self::RELEASE_MODE_CHECKIN && (int) $orderItem->checkin_status !== 1) {
            return 'Aguardando check-in deste ingresso';
        }

        return 'Disponivel para download';
    }

    private function getStorageFilePath(?string $path): ?string
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::disk('public')->path($path);
    }

    private function getPublicFilePath(string $relativePath): ?string
    {
        $path = public_path($relativePath);

        return file_exists($path) ? $path : null;
    }

    private function userCanManageEventCertificates(Event $event): bool
    {
        return $event->participantesEvents()
            ->where('participante_id', Auth::id())
            ->where('role', 'admin')
            ->where('status', 1)
            ->exists();
    }
}
