

  <div class="footer container">
    <div class="d-flex justify-content-between w-100 mb-4">

    <div class="col-12 col-md-2">
      <ul class="footer-links d-flex flex-md-column justify-content-around">
        @if( Auth::guest() )
        <li><a href="{{ URL::to("/users/login") }}">Login</a></li>
        <li><a href="{{ URL::to("/users/account") }}">Cadastrar-se</a></li>
        @endif
        <li><a href="{{ URL::to("/") }}/project">O projeto</a></li>
        <li><a href="{{ URL::to("/") }}/faq">FAQ</a></li>
        <li><a href="mailto: arquigrafiabrasil@gmail.com">Contato</a></li>
      </ul>
    </div>
    <div class="col-md-2 d-none d-md-block border-start">
      <ul class="footer-logos">
        <li id="usp"><a href="http://www.usp.br/" title="USP"  target="_blank"></a></li>
        <li id="fapesp"><a href="http://www.fapesp.br/" title="FAPESP"  target="_blank"></a></li>
        <li id="rnp"><a href="http://www.rnp.br/" title="RNP"  target="_blank"></a></li>
      </ul>
    </div>
    <div class="col-md-2 d-none d-md-block">
      <ul class="footer-logos">
        <li id="cnpq"><a href="http://www.cnpq.br/" title="CNPQ"  target="_blank"></a></li>
        <li id="ccsl"><a href="http://ccsl.ime.usp.br/" title="CCSL"  target="_blank"></a></li>
        <li id="chancela"><a href="{{ URL::to("/") }}/chancela" title="Chancela do Ministério da Cultura" ></a></li>
      </ul>
    </div>
    <div class="col-md-2 d-none d-md-block">
      <ul class="footer-logos">
        <li id="fau"><a href="http://www.usp.br/fau/" title="FAU"  target="_blank"></a></li>
        <li id="ime"><a href="http://www.ime.usp.br/" title="IME"  target="_blank"></a></li>
        <li id="eca"><a href="http://www.eca.usp.br/" title="ECA"  target="_blank"></a></li>
      </ul>
    </div>
    <div class="col-md-2 d-none d-md-block">
      <ul class="footer-logos">
        <li id="quapa"><a href="http://www.quapa.fau.usp.br/quapa_desenv/default.htm" title="QUAPÁ"  target="_blank"></a></li>
        <li id="vitruvius"><a href="http://www.vitruvius.com.br/" title="Vitruvius"  target="_blank"></a></li>
        <li id="scansystem"><a href="http://www.scansystem.com.br/" title="Scan system"  target="_blank"><img src="{{ asset('img_scenario4/scan-system.webp')}}" height="37" ></a></li>
        </ul>
    </div>
    <div class="col-md-2 d-none d-md-block">
      <ul class="footer-logos last w-100">
        <li id="benchmark"><a href="http://www.bench.com.br/" title="Benchmark"  target="_blank"></a></li>
        <li id="doctela" ><a href="http://doctela.com.br/" title="Doctela" target="_blank"></a></li>
      </ul>
    </div>
  </div>

  <div class="twelve columns alpha omega">
    <p><small>O Arquigrafia tem envidado todos os esforços para que nenhum direito autoral seja violado. Todas as imagens passíveis de download no Arquigrafia possuem uma licença <b><a class="footer-links" href="http://creativecommons.org/licenses/?lang=pt" target="_blank">Creative Commons</a></b> específica. Caso seja encontrado algum arquivo/imagem que, por qualquer motivo, o autor entenda que afete seus direitos autorais, <a class="footer-links" href="mailto: arquigrafiabrasil@gmail.com">clique aqui</a> e informe à equipe do portal Arquigrafia para que a situação seja imediatamente regularizada.</small></p>
  </div>

    <div class="d-flex flex-md-row flex-column">
      <div class="footer-logo"></div>
      <p>O Arquigrafia conta com um total de {{ $count ?? 0 }} fotos.<br />
        @guest
          <a href="/users/login">Faça o login</a> e compartilhe também suas imagens.
        @endguest
        @auth
          Compartilhe também suas imagens.
        @endauth
      </p>
    </div>
    <p id="copyright">Arquigrafia - {{ date("Y") }} - Arquigrafia é uma marca registrada (INPI). Este site possui uma licença <b><a class="footer-links" href="http://creativecommons.org/licenses/by/3.0/deed.pt_BR" target="_blank">Creative Commons Attribution 3.0</a></b></p>
</div>
