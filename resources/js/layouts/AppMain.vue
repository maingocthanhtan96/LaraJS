<template>
    <div class="larajs">
        <div class="container-scroller" :class="{'d-none': checkDisplayNone}">
            <div class="container-fluid page-body-wrapper">
                <header-main/>
                <navbar-main/>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <transition>
                            <router-view></router-view>
                        </transition>
                    </div>
                    <footer-main/>
                </div>
            </div>
        </div>
        <router-view name="page404"></router-view>
        <router-view name="login"></router-view>
    </div>
</template>

<script>
    import HeaderMain from './HeaderMain';
    import NavbarMain from './NavbarMain';
    import FooterMain from './FooterMain';

    export default {
        name: 'app',
        components: {HeaderMain, NavbarMain, FooterMain},
        data() {
            return {
                displayNone: ['/404', '/login']
            }
        },
        methods: {
            checkDisplayNone() {
                console.log(this.$route.fullPath, this.displayNone.includes(this.$route.fullPath));
                return this.displayNone.includes(this.$route.fullPath)
            }
        }
    }
</script>

<style lang="scss">

</style>