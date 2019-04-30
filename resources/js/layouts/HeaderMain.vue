<template>
	<el-header class="items-center d-flex" style="padding: 0">
		<div class="nav-left d-flex justify-between items-center" :style="{'max-width': $store.state.collapse}">
			<div>
				<img src="@/public/images/logo/logo-tanmnt-mini.png" class="" width="90px">
			</div>
			<div class="text-right p-5">
				<i class="mdi mdi-menu text-3xl text-grey-light cursor-pointer"
				   @click="$store.state.isCollapse = !$store.state.isCollapse; $store.state.collapse = $store.state.collapse === '63px' ? '199px' : '63px' "></i>
			</div>
		</div>
		<div class="nav-right d-flex justify-between items-center">
			<div class="pl-3">
				<el-breadcrumb separator-class="el-icon-arrow-right">
					<transition-group name="breadcrumb">
						<el-breadcrumb-item v-for="(item, index) in levelList" :key="item.path">
							<span class="no-redirect" v-if="index === levelList.length - 1">{{generateTitle(item.meta.title)}}</span>
							<a v-else @click.prevent="handleLink(item)">{{generateTitle(item.meta.title)}}</a>
						</el-breadcrumb-item>
					</transition-group>
				</el-breadcrumb>
			</div>
			<div class="nav-right-sub text-right pr-3">
				<div class="language pr-3">
					<el-dropdown
						trigger="click"
						@command="handleCommand"
					>
					<span class="el-dropdown-link">
						<i class="fa fa-language text-3xl"></i>
					</span>
					<el-dropdown-menu slot="dropdown">
						<el-dropdown-item :class="{'bg-blue-light text-white font-bold': $store.getters['lang/lang'] === 'vn'}" icon="flag-icon flag-icon-vn" command="vn">Việt Nam</el-dropdown-item>
						<el-dropdown-item :class="{'bg-blue-light text-white font-bold': $store.getters['lang/lang'] === 'en'}" icon="flag-icon flag-icon-my" command="en">English</el-dropdown-item>
					</el-dropdown-menu>
				</el-dropdown>
				</div>
				<div class="profile">
					<el-dropdown
						trigger="click"
						@command="handleCommand"
					>
					<span class="el-dropdown-link">
						<img src="@/public/images/logo/logo-tanmnt.png" width="40px">
						<i class="el-icon-caret-bottom el-icon--right"></i>
					</span>
						<el-dropdown-menu slot="dropdown">
							<el-dropdown-item command="logout">{{$t('auth.logout')}}</el-dropdown-item>
						</el-dropdown-menu>
					</el-dropdown>
				</div>
			</div>
		</div>
	</el-header>
</template>

<script>
	import {LOGOUT,SET_LANG} from "../store/muation-types";
	import pathToRegexp from 'path-to-regexp';
	import {generateTitle} from "../utils/i18n";

    export default {
		data() {
			return {
				collapse: '199px',
				levelList: null
			}
		},
		created() {
			this.getBreadcrumb();
		},
        mounted() {
			window.onresize = this.scrollFunction;
        },
		methods: {
			handleCommand(command) {
				if (command === 'logout') {
					this.logout();
				}else if (command === 'vn' || command === 'en') {
					localStorage.setItem('lang', command);
					this.$store.dispatch(`lang/${SET_LANG}`, command);
				}

			},
			logout() {
				this.$store.dispatch(`auth/${LOGOUT}`)
					.then(() => {
						this.$router.push({name: 'login'});
					});
			},
			getBreadcrumb() {
				// let matched = this.$route.matched.filter(item => item.name);
				this.levelList = this.$route.matched.filter(item => item.meta.title && item.meta.breadcrumb !== false);
			},
			pathCompile(path) {
				const { params } = this.$route;
				let toPath = pathToRegexp.compile(path);
				return toPath(params);
			},
			handleLink(item) {
				const { redirect, path } = item;
				if (redirect) {
					this.$router.push(redirect);
					return;
				}
				this.$router.push(this.pathCompile(path));
			},
            generateTitle,
            scrollFunction() {
                if (window.innerWidth < 1024) {
                    this.$store.state.isCollapse = true;
                    this.$store.state.collapse = '63px';
                } else {
					this.$store.state.isCollapse = false;
					this.$store.state.collapse = '199px';
                }
            }
		},
		watch: {
			$route() {
				this.getBreadcrumb()
			}
		}
	}
</script>

<style lang="scss" scoped>
	.wx-200px {
		max-width: 200px;
	}

	.nav-left {
		background-color: #2c2f41;
		height: 100%;
		transition: all 0.45s ease 0s;
	}

	.nav-right {
		.nav-right-sub {
			display: flex;
			justify-content: flex-end;
			align-items: center;
		}
	}
</style>
