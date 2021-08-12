import Vue from 'vue';
import VueI18n from 'vue-i18n';
import enLocale from 'element-ui/lib/locale/lang/en';
import viLocale from 'element-ui/lib/locale/lang/vi';
import jaLocale from 'element-ui/lib/locale/lang/ja';
import Locale from './vue-i18n-locales.generated';

Vue.use(VueI18n);
const messages = {
  en: {
    ...Locale.en,
    ...enLocale,
  },
  vi: {
    ...Locale.vi,
    ...viLocale,
  },
  ja: {
    ...Locale.ja,
    ...jaLocale,
  },
};

const i18n = new VueI18n({
  locale: localStorage.getItem('lang') || 'en',
  messages,
  fallbackLocale: 'en',
});

export default i18n;
