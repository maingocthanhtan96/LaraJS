import Vue from 'vue';
import VueI18n from 'vue-i18n';
import Locale from '@/lang/vue-i18n-locales.generated';

Vue.use(VueI18n);
const messages = {
  en: {
    ...Locale.en,
  },
  vi: {
    ...Locale.vi,
  },
  ja: {
    ...Locale.ja,
  },
};

const i18n = new VueI18n({
  locale: localStorage.getItem('fe_lang') || 'en',
  messages,
  fallbackLocale: 'ja',
});

export default i18n;
