<x-site-layout>
    <main id="main">
        <section class="breadcrumbs" aria-label="breadcrumb">
            <div class="container">
                <h2 class="mt-4">Termos de Uso</h2>
            </div>
        </section>

        <section class="inner-page">
            <div class="container" data-aos="fade-up">
                <article class="legal-content">
                    <p class="text-muted small">Última atualização: {{ now()->format('d/m/Y') }}</p>

                    <h2>1. Aceite dos termos</h2>
                    <p>Ao acessar ou utilizar a plataforma Ticket DZ6 (“Plataforma”), você concorda com estes Termos de Uso. A Plataforma permite a criação e gestão de eventos, venda de ingressos e inscrições de participantes. O uso continuado constitui aceite de eventuais alterações publicadas nesta página.</p>

                    <h2>2. Uso da plataforma</h2>
                    <p>A Plataforma pode ser utilizada para:</p>
                    <ul>
                        <li>Cadastro como participante para inscrição em eventos e compra de ingressos;</li>
                        <li>Cadastro como organizador para criação de eventos, definição de lotes, preços, cupons e publicação de páginas de evento;</li>
                        <li>Processamento de pagamentos relacionados às inscrições e ingressos.</li>
                    </ul>
                    <p>É vedado o uso para fins ilícitos, abusivos ou que prejudiquem terceiros ou a operação da Plataforma.</p>

                    <h2>3. Obrigações do usuário</h2>
                    <p>O usuário compromete-se a:</p>
                    <ul>
                        <li>Fornecer informações verdadeiras e atualizadas no cadastro e nas inscrições;</li>
                        <li>Manter o sigilo de sua senha e ser responsável por todas as atividades realizadas em sua conta;</li>
                        <li>Utilizar a Plataforma de forma adequada, em conformidade com a lei e com estes Termos.</li>
                    </ul>

                    <h2>4. Pagamentos e Mercado Pago</h2>
                    <p>Os pagamentos realizados na Plataforma (cartão de crédito, débito, PIX e boleto bancário) são processados pelo <strong>Mercado Pago</strong>. Ao efetuar um pagamento, você está sujeito também aos <strong>Termos e Condições</strong> e à política de privacidade do Mercado Pago aplicáveis ao processamento de pagamentos. A Plataforma não armazena dados completos de cartão; o tratamento de dados de pagamento é realizado conforme as regras e a documentação do Mercado Pago.</p>

                    <h2>5. Cancelamento e reembolso</h2>
                    <p>As regras de cancelamento e reembolso dependem de cada evento e são definidas pelo organizador. Em caso de dúvida ou pedido de cancelamento/reembolso, o participante deve entrar em contato com o organizador do evento através do formulário ou e-mail indicado na página do evento. A Plataforma atua como intermediária e pode, quando aplicável, viabilizar o reembolso conforme políticas do meio de pagamento (Mercado Pago) e combinação com o organizador.</p>

                    <h2>6. Propriedade intelectual e responsabilidade</h2>
                    <p>O conteúdo da Plataforma (layout, marcas, textos e funcionalidades) é protegido por lei. O organizador é responsável pelo conteúdo que publica (textos, imagens, dados do evento). A Plataforma não se responsabiliza por conteúdo de terceiros nem por eventos realizados fora do ambiente virtual, limitando-se às ferramentas oferecidas para gestão de eventos e vendas.</p>

                    <h2>7. Alterações e lei aplicável</h2>
                    <p>Estes Termos podem ser alterados a qualquer momento, com publicação nesta página. O uso continuado após a publicação constitui aceite das alterações. Em caso de conflito, prevalece a lei brasileira, com foro no domicílio do usuário.</p>

                    <p class="mt-4"><a href="{{ route('politica') }}">Consulte também nossa Política de Privacidade</a>.</p>
                </article>
            </div>
        </section>
    </main>
</x-site-layout>
