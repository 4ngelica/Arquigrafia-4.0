<script>
  import i18n from '../../lang/i18n';

  export default {
    name: 'LandingPage6',
    i18n,
  };
</script>

<template>
  <div id="page6" class="wrapper">
    <div class="row">
      <div style="display:flex;justify-content:center;align-items:center;height:100vh;">
        <div class="column-7" style="padding: 0;">
          <div class="title-georef">{{ $t('landing.titlePage6') }}</div>
          <div class="texto-georef">{{ $t('landing.paragraphPage6') }}</div>
          <div id="mapa-georref">
            <img src="/img/mapa-temp.png" />
              <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="905px"
                 height="380px" viewBox="0 0 905 380" enable-background="new 0 0 905 380" xml:space="preserve" style="z-index: 99999; height: auto;">
                <g id="Layer_2"></g>
                <g id="Layer_1">
                  <path id="masp" class="georref-hover" fill="#1D1D1B" d="M532.404,225.592c-5.497,0-13.742,2.499-13.742,12.493c0,4.997,10.994,22.487,13.742,27.484
                    c2.746-4.997,13.742-22.487,13.742-27.484C546.146,228.091,537.904,225.592,532.404,225.592z M532.404,242.457
                    c-2.415,0-4.372-1.956-4.372-4.372c0-2.415,1.957-4.373,4.372-4.373s4.372,1.958,4.372,4.373
                    C536.776,240.501,534.819,242.457,532.404,242.457z"/>
                  <path fill="#1D1D1B" d="M-269.025,309.621c-3.866,0-9.668,1.758-9.668,8.789c0,3.516,7.735,15.82,9.668,19.336
                    c1.933-3.516,9.668-15.82,9.668-19.336C-259.357,311.379-265.156,309.621-269.025,309.621z M-269.025,321.486
                    c-1.699,0-3.076-1.377-3.076-3.076s1.377-3.076,3.076-3.076s3.076,1.377,3.076,3.076S-267.326,321.486-269.025,321.486z"/>
                  <path id="conjuntonacional" class="georref-hover" fill="#1D1D1B" d="M394.376,133.024c-5.497,0-13.742,2.499-13.742,12.493c0,4.997,10.994,22.487,13.742,27.484
                    c2.746-4.997,13.742-22.487,13.742-27.484C408.118,135.523,399.876,133.024,394.376,133.024z M394.376,149.89
                    c-2.415,0-4.372-1.956-4.372-4.372c0-2.415,1.957-4.373,4.372-4.373s4.372,1.958,4.372,4.373
                    C398.748,147.934,396.791,149.89,394.376,149.89z"/>
                  <path id="paulista" class="georref-hover" fill="#1D1D1B" d="M316.404,52.592c-5.497,0-13.742,2.499-13.742,12.493c0,4.997,10.994,22.487,13.742,27.484
                    c2.746-4.997,13.742-22.487,13.742-27.484C330.146,55.091,321.904,52.592,316.404,52.592z M316.404,69.457
                    c-2.415,0-4.372-1.956-4.372-4.372c0-2.415,1.957-4.373,4.372-4.373s4.372,1.958,4.372,4.373
                    C320.776,67.501,318.819,69.457,316.404,69.457z"/>
                  <path id="gazeta" class="georref-hover" fill="#1D1D1B" d="M591.404,309.592c-5.497,0-13.742,2.499-13.742,12.493c0,4.997,10.994,22.487,13.742,27.484
                    c2.746-4.997,13.742-22.487,13.742-27.484C605.146,312.091,596.904,309.592,591.404,309.592z M591.404,326.457
                    c-2.415,0-4.372-1.956-4.372-4.372c0-2.415,1.957-4.373,4.372-4.373s4.372,1.958,4.372,4.373
                    C595.776,324.501,593.819,326.457,591.404,326.457z"/>
                  <path id="trianon" class="georref-hover" fill="#1D1D1B" d="M457,257.135c-5.497,0-13.742,2.499-13.742,12.493c0,4.997,10.994,22.487,13.742,27.484
                    c2.746-4.997,13.742-22.487,13.742-27.484C470.742,259.634,462.5,257.135,457,257.135z M457,274c-2.415,0-4.372-1.956-4.372-4.372
                    c0-2.415,1.957-4.373,4.372-4.373s4.372,1.958,4.372,4.373C461.372,272.044,459.415,274,457,274z"/>
                </g>
              </svg>
              <img class="hover-image" id="img-paulista" src="/img/map-paulista.png" />
              <img class="hover-image" id="img-gazeta" src="/img/map-gazeta.png" />
              <img class="hover-image" id="img-masp" src="/img/map-masp.png" />
              <img class="hover-image" id="img-conjuntonacional" src="/img/map-conjuntonacional.png" />
              <img class="hover-image" id="img-trianon" src="/img/map-trianon.png" />
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

