<x-guestsite-layout>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="logo text-center mb-5 mt-5">
                    <a href="/">
                        <img src="{{ asset('assets/img/logo_principal.png') }}" alt="">
                    </a>
                </div>
            </div>
            <section id="checkout" class="section-bg">
                <div class="container pb-5">
                    <div class="py-5 text-center">
                        <div class="section-header">
                            <h2>Imprima seu voucher</h2>
                            <p class="lead">Por favor, apresente esse voucher na data e local do seu evento.</p>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-8 text-center">
                            @foreach ($items as $k => $item)
                            <div class="card" style="width: 24rem; margin: 0 auto; margin-bottom: 30px">
                                <div class="card-body text-center">
                                    <h4 class="card-title">#{{$item->number}}</h4>
                                    <h5 class="card-title">{{$item->event_name}}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">{{ \Carbon\Carbon::parse($item->data_chosen)->format('d/m/Y') }}</h6>
                                    <p class="card-text">{{$item->place_name}}</p>
                                    <div class="my-3">
                                        {!! QrCode::size(300)->generate($item->purchase_hash) !!}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <a href="javascript:window.print()" class="btn btn-primary">Imprimir</a>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- End Contact Section -->
        </div>
    </section>

    @push('bootstrap_version')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
              crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush


    @push('footer')
        <script type="text/javascript" src="{{ asset('assets_conference/js/jquery.mask.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Scripts específicos para impressão de voucher
            });
        </script>
    @endpush

</x-guestsite-layout>
