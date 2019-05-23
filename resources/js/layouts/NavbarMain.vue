<template>
<div class="overflow-hidden navbar-menu" :style="{'max-width': $store.state.collapse}">
	<el-menu
			class="el-menu-vertical-demo"
			:collapse="$store.state.isCollapse"
			background-color="#2c2f41"
			text-color="#a2a3b7"
			active-text-color="#fff"
			:default-active="activeMenu"
	>
		<template v-for="(router) in routers">
			<template v-if="!router.hidden && router.children && router.children.length > 0 && !router.hasOne">
				<el-submenu :key="router.path" :index="router.path">
					<template slot="title">
						<i v-if="router.meta.icon" :class="router.meta.icon + ' text-lg'"></i>
						<span slot="title" v-if="router.meta.title">{{generateTitle(router.meta.title)}}</span>
					</template>
					<template v-if="router.children && router.children.length > 0" v-for="(child) in router.children" >
						<el-submenu v-if="child.children && child.children.length > 0" v-for="(childSub) in child.children" :key="resolvePath(router.path, child.path)" :index="resolvePath(router.path, child.path)">
							<template slot="title">{{generateTitle(child.meta.title)}}</template>
							<router-link @click.native="refreshPage(resolvePath(resolvePath(router.path, child.path), childSub.path))" :to="{path: resolvePath(resolvePath(router.path, child.path), childSub.path)}"  class="no-underline">
								<el-menu-item :index="resolvePath(resolvePath(router.path, child.path), childSub.path)">{{generateTitle(childSub.meta.title)}}</el-menu-item>
							</router-link>
						</el-submenu>
						<router-link @click.native="refreshPage(resolvePath(router.path, child.path))" v-if="!child.children" :to="{path: resolvePath(router.path, child.path)}"  class="no-underline">
							<el-menu-item :key="resolvePath(router.path, child.path)" :index="resolvePath(router.path, child.path)">{{generateTitle(child.meta.title)}}</el-menu-item>
						</router-link>
					</template>
				</el-submenu>
			</template>
			<template v-if="!router.hidden && router.children.length > 0 && router.hasOne">
				<router-link @click.native="refreshPage(resolvePath(router.path, router.children[0].path))" :to="{path: resolvePath(router.path, router.children[0].path)}" class="no-underline">
					<el-menu-item :index="resolvePath(router.path, router.children[0].path)">
						<i v-if="router.children[0].meta.icon" :class="router.children[0].meta.icon + ' text-lg'"></i>
						<span slot="title" v-if="router.children[0].meta && router.children[0].meta.title">{{generateTitle(router.children[0].meta.title)}}</span>
					</el-menu-item>
				</router-link>
			</template>
		</template>
	</el-menu>
</div>
</template>
<script>
    import path from 'path';
    import {isExternal} from '@/utils';
    import {generateTitle} from "@/utils/i18n";

    export default {
        data() {
            return {

            };
        },
		mounted() {
        	this.activeMenu;
		},
        methods: {
            resolvePath(basePath, routePath) {
                if (this.isExternalLink(routePath)) {
                    return routePath;
                }
                return path.resolve(basePath, routePath);
            },
            isExternalLink(routePath) {
                return isExternal(routePath);
            },
            generateTitle,
			refreshPage(path) {
				this.$nextTick(() => {
					this.$router.replace({
						path: '/redirect' + path
					})
				})
				// this.$router.push({
				// 	path,
				// 	query: {
				// 		//Ensure that each click, query is not the same
				// 		//to ensure that refresh the view
				// 		t: +new Date()
				// 	}
				// })
			}
        },
        computed: {
            routers() {
                return this.$store.getters['permission/routers'];
            },
			activeMenu() {
				const { meta, path } = this.$route;
				// if set path, the sidebar will highlight the path you set
				if (meta.activeMenu) {
					return meta.activeMenu;
				}
				return path;
			},
        }
    };
</script>
<style lang="scss">
	.navbar-menu {
		height: 100%;
		transition: all 0.45s ease 0s;
		.el-menu-vertical-demo {
			position: fixed;
			min-height: calc(100vh - 60px);
			&:not(.el-menu--collapse) {
				width: 200px;
			}
			.vn-w200px {
				&:not(.el-menu--collapse) {
					width: 199px;
				}
			}
		}
	}
	.navbar-main {
		background-color: rgb(44, 47, 65);
	    .el-submenu__title, .el-menu-item-group__title, .el-menu-item, .el-menu-item-group {
	        font-size: 12px !important;
	        background: transparent !important;
	        &:hover {
	            background-color: rgb(26, 28, 41) !important;
	            font-weight: bold;
	            color: #fff !important;
	        }
	    }
	    .is-active {
			color: #fff !important;
	        font-weight: bold;
	    }
	    .el-menu {
	        overflow-x: auto;
	        -ms-overflow-style: none;  // IE 10+
	        scrollbar-width: none;  // Firefox
	        &::-webkit-scrollbar {
	            display: none;
	        }
	    }
	}
</style>
<style lang="scss" scoped>
    .wn-200px:not(.el-menu--collapse) {
        width: 200px;
        min-width: 200px;
    }
</style>
