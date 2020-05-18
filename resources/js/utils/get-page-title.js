import defaultSettings from '@/settings';
import i18n from '@/lang';
import store from '@/store';

const title = defaultSettings.title || store.state.settings.title;

export default function getPageTitle(key) {
  const hasKey = i18n.te(`route.${key}`);
  if (hasKey) {
    const pageName = i18n.t(`route.${key}`);
    return `${pageName} - ${title}`;
  }
  return `${title}`;
}
