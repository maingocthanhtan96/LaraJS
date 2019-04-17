import {
    SET_TABLE_LIMIT,
    SET_TABLE_PAGE,
    UPDATE_MESSAGE
} from '../../store/muation-types'

import { mapState, mapGetters, mapActions } from 'vuex';
import Notifications from 'vue-notification';
import velocity      from 'velocity-animate';

export default {
    install(Vue) {
        Vue.use(Notifications, {velocity});
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
                notifi(group = '', title = '', text = '', type = 'success' , duration = 3000, speed = 300, data = {}) {
                    this.$notify({
                        group: group,
                        title: title,
                        text: text,
                        type: type,
                        data: data,
                        duration: duration,
                        speed: speed,
                    });
                },
                ...mapActions({
                    setPagePagination: SET_TABLE_PAGE,
                    setLimitPagination: SET_TABLE_LIMIT,
                    updateMessage: UPDATE_MESSAGE
                })
            },
            computed: {
                ...mapGetters({
                    errors: 'errors',
                    pagePagination: 'pagePagination',
                    limitPagination: 'limitPagination',
                    setUpdateMessage: 'setUpdateMessage'
                }),
            },
            watch: {
                page: function(newValue) {
                    this.setPagePagination(newValue);
                },
                limit: function(newValue) {
                    this.setLimitPagination(newValue);
                },
            },
        })
    }
}
