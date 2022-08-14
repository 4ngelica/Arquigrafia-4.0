<script>
  import i18n from '../../lang/i18n';

  export default {
    name: 'LandingPage5',
    i18n,
  };
</script>

<template>
  <div class="fullscreen_grafico_bg">
    <div id="page5" class="wrapper" style="opacity: 1;">
      <div class="row">
        <div style="display:flex;justify-content:center;align-items:center;height:100vh;">
          <div class="column-8" style="padding: 0;display:flex;justify-content:center;align-items:center; margin-top: -30px;">
            <div class="column-3" style="width: 40%; padding: 0;">
              <div class="title-grafico" v-html="$t('landing.titlePage5')" />
              <div class="texto-grafico" v-html="$t('landing.paragraphPage5')" />
            </div>
            <div class="column-5" style="width: 60%;">
              <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                width="600px" height="370px" viewBox="0 0 600 370" enable-background="new 0 0 600 370" xml:space="preserve" style="margin-top: 50px; background: rgba(0,0,0,0.6); width:100%;">
                <circle fill="#FFFFFF" cx="151.949" cy="50.004" r="4.547"/>
                <circle fill="#FFFFFF" cx="427.663" cy="105.808" r="4.547"/>
                <circle fill="#FFFFFF" cx="194.939" cy="160.786" r="4.547"/>
                <circle fill="#FFFFFF" cx="368.139" cy="216.589" r="4.547"/>
                <circle fill="#FFFFFF" cx="301.174" cy="271.566" r="4.547"/>
                <circle fill="#FFFFFF" cx="301.051" cy="325.718" r="4.546"/>
                <line stroke="#FFFFFF" stroke-width="2" stroke-miterlimit="10" x1="301.051" y1="325.718" x2="301.051" y2="271.566"/>
                <line stroke="#FFFFFF" stroke-width="2" stroke-miterlimit="10" x1="300.495" y1="272.154" x2="367.712" y2="216.871"/>
                <line stroke="#FFFFFF" stroke-width="2" stroke-miterlimit="10" x1="368.13" y1="215.541" x2="195.06" y2="160.282"/>
                <line fill="none" stroke="#FFFFFF" stroke-width="2" stroke-miterlimit="10" x1="194.067" y1="160.496" x2="427.662" y2="105.808"/>
                <line fill="none" stroke="#FFFFFF" stroke-width="2" stroke-miterlimit="10" x1="427.662" y1="105.808" x2="151.078" y2="49.714"/>
                <line opacity="0.3" fill="none" stroke="#FFFFFF" stroke-miterlimit="10" x1="88.926" y1="50.28" x2="88.926" y2="333.178"/>
                <line opacity="0.3" fill="none" stroke="#FFFFFF" stroke-miterlimit="10" x1="514.99" y1="49.587" x2="514.99" y2="334.322"/>
                <line opacity="0.3" fill="none" stroke="#FFFFFF" stroke-miterlimit="10" x1="89.074" y1="325.841" x2="515.252" y2="325.841"/>
                <line opacity="0.3" fill="none" stroke="#FFFFFF" stroke-miterlimit="10" x1="194.274" y1="326.046" x2="194.274" y2="334.521"/>
                <line opacity="0.3" fill="none" stroke="#FFFFFF" stroke-miterlimit="10" x1="301.336" y1="327.286" x2="301.336" y2="335.554"/>
                <line opacity="0.3" fill="none" stroke="#FFFFFF" stroke-miterlimit="10" x1="407.363" y1="326.459" x2="407.363" y2="335.141"/>
                <line opacity="0.3" fill="none" stroke="#FFFFFF" stroke-miterlimit="10" x1="88.247" y1="104.691" x2="514.218" y2="104.691"/>
                <line opacity="0.3" fill="none" stroke="#FFFFFF" stroke-miterlimit="10" x1="88.682" y1="160.764" x2="514.651" y2="160.764"/>
                <line opacity="0.3" fill="none" stroke="#FFFFFF" stroke-miterlimit="10" x1="89.487" y1="216.92" x2="515.458" y2="216.92"/>
                <line opacity="0.3" fill="none" stroke="#FFFFFF" stroke-miterlimit="10" x1="89.839" y1="271.483" x2="515.81" y2="271.483"/>
                <text transform="matrix(1 0 0 1 140.606 28.8872)" opacity="0.8" fill="#FFFFFF" font-family="'Roboto'" font-size="19.0922" >Average interpretations of FAU-USP</text>
                <text transform="matrix(1 0 0 1 38.106 59.3716)" opacity="0.8" fill="#FFFFFF" font-family="'Roboto'" font-size="12.4009" v-html="$t('landing.txtPag5Open')"></text>
                <text transform="matrix(1 0 0 1 35.4595 110.0688)" opacity="0.8" fill="#FFFFFF" font-family="'Roboto'" font-size="12.4009" v-html="$t('landing.txtPag5Internal')" />
                <text transform="matrix(1 0 0 1 19.1724 165.2529)" opacity="0.8" fill="#FFFFFF" font-family="'Roboto'" font-size="12.4009" v-html="$t('landing.txtPag5Complex')" />
                <text transform="matrix(1 0 0 1 21.3013 219.8164)" opacity="0.8" fill="#FFFFFF" font-family="'Roboto'" font-size="12.4009" v-html="$t('landing.txtPag5Symmetric')" />
                <text transform="matrix(1 0 0 1 9.5205 275.2695)" opacity="0.8" fill="#FFFFFF" font-family="'Roboto'" font-size="12.4009" v-html="$t('landing.txtPag5Translucent')" />
                <text transform="matrix(1 0 0 1 16.6924 330.8047)" opacity="0.8" fill="#FFFFFF" font-family="'Roboto'" font-size="12.4009" v-html="$t('landing.txtPag5Horizontal')" />
                <text transform="matrix(1 0 0 1 528.9961 60.0112)" opacity="0.8" fill="#FFFFFF" font-family="'Roboto'" font-size="12.4009" v-html="$t('landing.txtPag5Close')" />
                <text transform="matrix(1 0 0 1 528.7471 110.0688)" opacity="0.8" fill="#FFFFFF" font-family="'Roboto'" font-size="12.4009" v-html="$t('landing.txtPag5External')" />
                <text transform="matrix(1 0 0 1 527.5898 164.8184)" opacity="0.8" fill="#FFFFFF" font-family="'Roboto'" font-size="12.4009" v-html="$t('landing.txtPag5Simple')" />
                <text transform="matrix(1 0 0 1 528.293 219.9834)" opacity="0.8" fill="#FFFFFF" font-family="'Roboto'" font-size="12.4009" v-html="$t('landing.txtPag5Asymmetric')" />
                <text transform="matrix(1 0 0 1 528.376 275.2695)" opacity="0.8" fill="#FFFFFF" font-family="'Roboto'" font-size="12.4009" v-html="$t('landing.txtPag5Opaque')" />
                <text transform="matrix(1 0 0 1 528.3955 331.0732)" fill="#FFFFFF" font-family="'Roboto'" font-size="12.4009" v-html="$t('landing.txtPag5Vertical')" />
                <text transform="matrix(1 0 0 1 85.2295 345.6855)" opacity="0.8" fill="#FFFFFF" font-family="'Roboto'" font-size="12.4009">0</text>
                <text transform="matrix(1 0 0 1 187.5371 345.0664)" opacity="0.8" fill="#FFFFFF" font-family="'Roboto'" font-size="12.4009">25</text>
                <text transform="matrix(1 0 0 1 294.4531 345.417)" opacity="0.8" fill="#FFFFFF" font-family="'Roboto'" font-size="12.4009">50</text>
                <text transform="matrix(1 0 0 1 400.2949 345.6855)" opacity="0.8" fill="#FFFFFF" font-family="'Roboto'" font-size="12.4009">75</text>
                <text transform="matrix(1 0 0 1 503.4912 345.335)" opacity="0.8" fill="#FFFFFF" font-family="'Roboto'" font-size="12.4009">100</text>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

