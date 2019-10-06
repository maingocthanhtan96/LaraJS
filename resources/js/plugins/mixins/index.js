import {
  UPDATE_MESSAGE,
} from '@/store/muation-types';

import { mapGetters, mapActions } from 'vuex';

export default {
  install(Vue) {
    Vue.mixin({
      methods: {
        ...mapActions({
          updateMessage: UPDATE_MESSAGE,
        }),
      },
      computed: {
        ...mapGetters({
          errors: 'errors',
          setUpdateMessage: 'setUpdateMessage',
          loggedIn: 'loggedIn',
        }),
      },
    });
  },
};
