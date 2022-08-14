import Vue from 'vue';
import i18next from 'i18next';
import LanguageDetector from 'i18next-browser-languagedetector';
import VueI18Next from '@panter/vue-i18next';
import landingEN from '../lang/en/landing.en';
import landingPT from '../lang/pt/landing.pt';

Vue.use(VueI18Next);

i18next
  .use(LanguageDetector)
  .init({
    fallbackLng: 'pt',
    detection: {
      lookupQuerystring: 'lng',
      order: ['querystring', 'navigator'],
    },
    resources: {
      en: {
        translation: {
          landing: landingEN,
        },
      },
      pt: {
        translation: {
          landing: landingPT,
        },
      },
    },
  });

export const changeLanguage = lang => i18next.changeLanguage(lang);

export default new VueI18Next(i18next);

