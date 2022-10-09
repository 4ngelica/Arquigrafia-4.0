<div id="footer" class="container">
  <div class="twelve columns">
    <div id="credits" class="clearfix">
      <ul class="footer-links">
        @if( Auth::guest() )
        <li><a href="{{ URL::to("/users/login") }}">Login</a></li>
        <li><a href="{{ URL::to("/users/account") }}">Cadastrar-se</a></li>
        @endif
        <li><a href="{{ URL::to("/") }}/project">O projeto</a></li>
        <li><a href="{{ URL::to("/") }}/faq">FAQ</a></li>
        <li><a href="mailto: arquigrafiabrasil@gmail.com">Contato</a></li>
      </ul>
      <ul class="footer-logos">
        <li><a href="http://www.usp.br/" title="USP" id="usp" target="_blank"></a></li>
        <li><a href="http://www.fapesp.br/" title="FAPESP" id="fapesp" target="_blank"></a></li>
        <li><a href="http://www.rnp.br/" title="RNP" id="rnp" target="_blank"></a></li>
      </ul>
      <ul class="footer-logos">
        <li><a href="http://www.cnpq.br/" title="CNPQ" id="cnpq" target="_blank"></a></li>
        <li><a href="http://ccsl.ime.usp.br/" title="CCSL" id="ccsl" target="_blank"></a></li>
        <li><a href="{{ URL::to("/") }}/chancela" title="Chancela do Ministério da Cultura" id="chancela"></a></li>
      </ul>
      <ul class="footer-logos">
        <li><a href="http://www.usp.br/fau/" title="FAU" id="fau" target="_blank"></a></li>
        <li><a href="http://www.ime.usp.br/" title="IME" id="ime" target="_blank"></a></li>
        <li><a href="http://www.eca.usp.br/" title="ECA" id="eca" target="_blank"></a></li>
      </ul>
      <ul class="footer-logos">
        <li><a href="http://www.quapa.fau.usp.br/quapa_desenv/default.htm" title="QUAPÁ" id="quapa" target="_blank"></a></li>
        <li><a href="http://www.vitruvius.com.br/" title="Vitruvius" id="vitruvius" target="_blank"></a></li>
        <li><a href="http://www.scansystem.com.br/" title="Scan system" id="scansystem"
          target="_blank"><img src="{{ asset('img/scan-system.png')}}" height="37" ></a></li>
        </ul>
        <ul class="footer-logos last">
          <li><a href="http://www.bench.com.br/" title="Benchmark" id="benchmark" target="_blank"></a></li>
          <li><a href="http://doctela.com.br/" title="Doctela" id="doctela" target="_blank"></a></li>
        </ul>
      </div>
      <div class="twelve columns alpha omega">
        <p><small>O Arquigrafia tem envidado todos os esforços para que nenhum direito autoral seja violado. Todas as imagens passíveis de download no Arquigrafia possuem uma licença <a href="http://creativecommons.org/licenses/?lang=pt" target="_blank">Creative Commons</a> específica. Caso seja encontrado algum arquivo/imagem que, por qualquer motivo, o autor entenda que afete seus direitos autorais, <a href="mailto: arquigrafiabrasil@gmail.com">clique aqui</a> e informe à equipe do portal Arquigrafia para que a situação seja imediatamente regularizada.</small></p>
      </div>

      <div class="footer-last">
        <div class="footer-msg left">
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
        <p id="copyright">Arquigrafia - {{ date("Y") }} - Arquigrafia é uma marca registrada (INPI). Este site possui uma licença <a href="http://creativecommons.org/licenses/by/3.0/deed.pt_BR" target="_blank">Creative Commons Attribution 3.0</a></p>
      </div>
    </div>
  </div>
