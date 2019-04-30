import Vue from 'vue';
import VueI18n from 'vue-i18n';
import enLocale from 'element-ui/lib/locale/lang/en';
import vnLocale from 'element-ui/lib/locale/lang/vi';
import Locale from './vue-i18n-locales.generated';
// import ElementLocale from 'element-ui/lib/locale';

Vue.use(VueI18n);
const messages = {
    en: {
        ...Locale.en,
        ...enLocale
    },
    vn: {
        ...Locale.vn,
        ...vnLocale
    }
};

const i18n = new VueI18n({
    locale: localStorage.getItem('lang') || 'en',
    messages,
    fallbackLocale: 'en'
});

// ElementLocale.i18n((key, value) => i18n.t(key, value));
export default i18n;
