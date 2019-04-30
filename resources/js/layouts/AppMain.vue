<template>
    <div class="larajs">
        <div class="header-main">
            <header-main/>
        </div>
        <el-container>
            <div class="navbar-main">
                <navbar-main/>
            </div>
            <el-main class="bg-grey-light p-20 main w-full">
                <div class="tags-view">
                    <tags-view/>
                </div>
                <div class="w-full wrapper pt-2">
                    <transition name="fade-transform" mode="out-in">
                        <router-view :key="$route.fullPath"></router-view>
                    </transition>
                </div>
                <div class="pt-10">
                    <footer-main/>
                </div>
            </el-main>
        </el-container>
    </div>
</template>

<script>
    import HeaderMain from './HeaderMain';
    import NavbarMain from './NavbarMain';
    import FooterMain from './FooterMain';
    import TagsView from "./TagsView";

    export default {
        name: 'app',
        components: {TagsView, HeaderMain, NavbarMain, FooterMain},
        data() {
            return {
            }
        },
        created(){
        },
        mounted() {
        },
        methods: {
        }
    }
</script>

<style lang="scss" scoped>
    .larajs {
        .header-main {
            position: fixed;
            width: 100%;
            background-color: #fff;
            z-index: 99;
            top: 0;
            left: 0;
        }
        font-size: 1rem;
        font-family: "Poppins", sans-serif;
        font-weight: initial;
        line-height: normal;
        -webkit-font-smoothing: antialiased;
        .main {
            transition: width 0.25s ease, margin 0.25s ease;
            display: -webkit-flex;
            display: flex;
            -webkit-flex-direction: column;
            flex-direction: column;
        }
        .wrapper {
            min-height: calc(100vh - 225px);
            position: relative;
            overflow-y: scroll;
            overflow-x: auto;
            -ms-overflow-style: none;  // IE 10+
            scrollbar-width: none;  // Firefox
            &::-webkit-scrollbar {
                display: none;
            }
        }
    }
</style>
<style lang="scss">

</style>
