<template>
	<div id="app">
		<router-view/>
		<!-- you can add element-ui's tooltip -->
		<el-tooltip placement="top" content="Back To Top">
			<back-to-top :custom-style="myBackToTopStyle" :visibility-height="60" :back-position="0" transition-name="fade" />
		</el-tooltip>
	</div>
</template>

<script>
	import BackToTop from '@/components/BackToTop';

	export default {
		name: 'App',
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
					background: '#e7eaf1'//The background color of the button
				}
			}
		},
		components: {
			BackToTop
		}
	}
</script>
