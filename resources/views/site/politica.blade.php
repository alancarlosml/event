<x-site-layout>
    <main id="main">
        <section class="breadcrumbs" aria-label="breadcrumb">
            <div class="container">
                <h2 class="mt-4">Política de Privacidade</h2>
            </div>
        </section>

        <section class="inner-page">
            <div class="container" data-aos="fade-up">
                <article class="legal-content">
                    <p class="text-muted small">Última atualização: {{ now()->format('d/m/Y') }}</p>

                    <p>Esta Política de Privacidade descreve como tratamos os seus dados pessoais na plataforma Ticket DZ6 (“Plataforma”), em conformidade com a Lei Geral de Proteção de Dados (LGPD – Lei nº 13.709/2018).</p>

                    <h2>1. Controlador e finalidade</h2>
                    <p>Os dados são tratados para permitir o cadastro de usuários (participantes e organizadores), a criação e gestão de eventos, a venda de ingressos e inscrições, o processamento de pagamentos e a comunicação necessária ao uso do serviço (confirmações, ingressos, suporte).</p>

                    <h2>2. Dados coletados</h2>
                    <p>Podemos coletar:</p>
                    <ul>
                        <li><strong>Cadastro:</strong> nome, e-mail, telefone e senha (criptografada).</li>
                        <li><strong>Inscrições em eventos:</strong> dados informados no formulário de inscrição (nome, e-mail, telefone e demais campos definidos pelo organizador).</li>
                        <li><strong>Pagamentos:</strong> os pagamentos são processados pelo <strong>Mercado Pago</strong>. Dados de cartão, PIX e boleto são tratados pelo Mercado Pago conforme a <strong>política de privacidade e os termos do Mercado Pago</strong>. Não armazenamos dados completos de cartão em nossos servidores.</li>
                        <li><strong>Uso da plataforma:</strong> logs de acesso, endereço IP e dados técnicos quando necessário para segurança e operação do serviço.</li>
                    </ul>

                    <h2>3. Bases legais</h2>
                    <p>O tratamento é realizado com base em: execução de contrato (prestação do serviço), consentimento (quando aplicável), cumprimento de obrigação legal e legítimo interesse (segurança, melhoria do serviço), sempre em conformidade com a LGPD.</p>

                    <h2>4. Compartilhamento de dados</h2>
                    <p>Seus dados podem ser compartilhados:</p>
                    <ul>
                        <li><strong>Organizadores de eventos:</strong> os dados dos inscritos (nome, e-mail, telefone e respostas do formulário) são disponibilizados ao organizador do evento em que você se inscreveu, para gestão do evento e contato.</li>
                        <li><strong>Mercado Pago:</strong> para processamento de pagamentos, conforme seus termos e política de privacidade.</li>
                        <li><strong>Autoridades:</strong> quando exigido por lei ou ordem judicial.</li>
                    </ul>

                    <h2>5. Retenção e segurança</h2>
                    <p>Mantemos os dados pelo tempo necessário para as finalidades descritas e para cumprimento de obrigações legais. Adotamos medidas técnicas e organizacionais para proteger os dados contra acesso não autorizado, alteração, divulgação ou destruição.</p>

                    <h2>6. Seus direitos (titular)</h2>
                    <p>De acordo com a LGPD, você pode solicitar:</p>
                    <ul>
                        <li>Confirmação da existência de tratamento e acesso aos dados;</li>
                        <li>Correção de dados incompletos ou desatualizados;</li>
                        <li>Anonimização, bloqueio ou eliminação de dados desnecessários ou tratados em desconformidade;</li>
                        <li>Portabilidade dos dados;</li>
                        <li>Revogação do consentimento;</li>
                        <li>Informação sobre compartilhamento com terceiros.</li>
                    </ul>
                    <p>Para exercer esses direitos, entre em contato conosco pelo <a href="{{ route('contact') }}">formulário de contato</a> ou pelo e-mail disponível no site, informando seu nome e e-mail cadastrado.</p>

                    <h2>7. Cookies e tecnologias semelhantes</h2>
                    <p>Utilizamos cookies e tecnologias semelhantes para funcionamento do site (sessão, preferências), segurança (por exemplo, proteção contra abuso) e, quando aplicável, análise de uso. Você pode configurar seu navegador para recusar ou limitar cookies; parte das funcionalidades pode deixar de funcionar corretamente dependendo da configuração.</p>

                    <h2>8. Alterações</h2>
                    <p>Esta Política pode ser atualizada. As alterações serão publicadas nesta página com a data da “Última atualização”. O uso continuado da Plataforma após a publicação constitui aceite das alterações.</p>

                    <p class="mt-4"><a href="{{ route('termos') }}">Consulte também nossos Termos de Uso</a>.</p>
                </article>
            </div>
        </section>
    </main>
</x-site-layout>
