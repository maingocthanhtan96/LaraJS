import {
    UPDATE_MESSAGE
} from '../../store/muation-types'

import { mapState, mapGetters, mapActions } from 'vuex';

export default {
    install(Vue) {
        Vue.mixin({
            mounted() {
                if(this.$refs.table) {
                    // this.$refs.table.setLimit(this.limitPagination);
                    // this.$refs.table.setPage(this.pagePagination);
                    // this.$refs.table.refresh();

                    if(this.setUpdateMessage) {
                        this.notifi('index', this.$t('messages.update'));
                        this.updateMessage(false);
                    }
                }
            },
            methods: {
                //toastr
                ...mapActions({
                    updateMessage: UPDATE_MESSAGE
                })
            },
            computed: {
                ...mapGetters({
                    errors: 'errors',
                    setUpdateMessage: 'setUpdateMessage',
                    loggedIn: 'auth/loggedIn'
                }),
            },
            watch: {
            },
        })
    }
}
