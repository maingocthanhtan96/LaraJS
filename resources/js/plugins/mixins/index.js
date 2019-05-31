import {
    UPDATE_MESSAGE
} from '../../store/muation-types'

import { mapState, mapGetters, mapActions } from 'vuex';

export default {
    install(Vue) {
        Vue.mixin({
            mounted() {
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
