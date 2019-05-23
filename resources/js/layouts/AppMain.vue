<template>
    <div class="larajs">
        <div class="header-main">
            <div :class="{'fixed-header': settings.fixedHeader}">
                <header-main/>
            </div>
        </div>
        <el-container >
            <div class="navbar-main" :class="{'p-0': !settings.fixedHeader}">
                <navbar-main/>
            </div>
            <el-main class="bg-gray-300 main w-full pb-3" :class="[{'pt-20': settings.fixedHeader},{'ml-5': $store.state.collapse === '63px' }]">
                <div class="tags-view">
                    <tags-view v-if="settings.tagsView"/>
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
        <!-- you can add element-ui's tooltip -->
        <el-tooltip placement="top" content="Back To Top">
            <back-to-top :custom-style="myBackToTopStyle" :visibility-height="60" :back-position="0" transition-name="fade" />
        </el-tooltip>
    </div>
</template>

<script>
    import defaultSettings from '@/settings';
    import HeaderMain from './HeaderMain';
    import NavbarMain from './NavbarMain';
    import FooterMain from './FooterMain';
    import TagsView from "./TagsView";

    import BackToTop from '@/components/BackToTop';

    export default {
        name: 'app',
        components: {TagsView, HeaderMain, NavbarMain, FooterMain, BackToTop },
        data() {
            return {
                // customizable button style, show/hide critical point, return position
                myBackToTopStyle: {
                    right: '20px',
                    bottom: '50px',
                    width: '40px',
                    height: '40px',
                    'border-radius': '4px',
                    'line-height': '45px', //Please keep consistent with height to center vertically
                    background: '#e7eaf1',//The background color of the button,
                    display: 'flex',
                    'align-items': 'center',
                    'justify-content': 'center',
                }
            }
        },
        created(){
        },
        mounted() {
        },
        methods: {
        },
        computed: {
            settings() {
                console.log(defaultSettings);
                return defaultSettings;
            }
        }
    }
</script>

<style lang="scss" scoped>
    .larajs {
        .header-main {
            .fixed-header {
                position: fixed;
                width: 100%;
                background-color: #fff;
                z-index: 99;
                top: 0;
                left: 0;
            }
        }
        .navbar-main {
            padding-top: 60px;
        }
        font-size: 1rem;
        font-family: "Poppins", sans-serif;
        font-weight: initial;
        line-height: normal;
        -webkit-font-smoothing: antialiased;
        .main {
            /*transition: width 0.25s ease, margin 0.25s ease;*/
            transition: margin-left .5s;
            display: -webkit-flex;
            display: flex;
            -webkit-flex-direction: column;
            flex-direction: column;
            margin-left: 199px;
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
