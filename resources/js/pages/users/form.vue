<template>
	<el-row>
		<el-col :span="24">
			<el-card>
				<div slot="header">
					<template v-if="$route.params.id">
						{{$t('table.users.form_edit')}}
					</template>
					<template v-else>
						{{$t('table.users.form_create')}}
					</template>
				</div>
				<el-form ref="users" :model="form" :rules="rules" status-icon>
					<el-form-item :label="$t('table.users.name')" prop="name">
						<el-input autofocus v-model="form.name"/>
					</el-form-item>
					<el-form-item :error="errors.email ? errors.email[0] + '' : ''" :label="$t('table.users.email')" prop="email">
						<el-input v-model="form.email"/>
					</el-form-item>
					<el-form-item :label="$t('table.users.role')" prop="role_id">
						<el-select v-model="form.role_id" placeholder="Role" class="w-full">
							<el-option v-for="role in rolesList" :key="'role_' + role.id" :label="role.name" :value="role.id"></el-option>
						</el-select>
					</el-form-item>
					<el-form-item v-if="!$route.params.id" required :label="$t('table.users.password')" prop="password">
						<el-input show-password type="password" v-model="form.password"/>
					</el-form-item>
					<el-form-item v-if="!$route.params.id" required :label="$t('table.users.password_confirm')" prop="password_confirm">
						<el-input show-password type="password" v-model="form.password_confirm"/>
					</el-form-item>
					<el-form-item class="flex justify-center">
							<template v-if="$route.params.id">
								<el-button @click="update('users')" :loading="loading" plain type="primary" icon="fa fa-edit mr-2">
									{{$t('table.users.form_edit')}}
								</el-button>
							</template>
							<template v-else>
								<el-button @click="store('users')" :loading="loading" plain type="success" icon="fa fa-plus mr-2">
									{{$t('table.users.form_create')}}
								</el-button>
							</template>
					</el-form-item>
				</el-form>
			</el-card>
		</el-col>
	</el-row>
</template>

<script>
	import {store, roles, edit, update} from '@/api/users';
	export default {
		data() {
			const password = (rule, value, cb) => {
				if(value === '') {
					cb(new Error(this.$t('validation.required', {attribute: this.$t('table.users.password')})));
				}else {
					if(this.form.password_confirm !== '') {
						this.$refs.users.validateField('password_confirm');
					}
					cb();
				}
			};
			const password_confirm = (rule, value, cb) => {
				if(value === '') {
					cb(new Error(this.$t('validation.required', {attribute: this.$t('table.users.password_confirm')})));
				} else if (value !== this.form.password) {
					cb(new Error(this.$t('validation.confirmed', {attribute: this.$t('table.users.password_confirm')})))
				} else {
					cb();
				}
			};
			return {
				loading: false,
				rolesList: [],
				form:{
					name: '',
					email: '',
					role_id: '',
					password: '',
					password_confirm: ''
				},
				rules: {
					name: [
						{required: true, message: this.$t('validation.required', {attribute: this.$t('table.users.name')}), trigger: 'blur'}
					],
					email: [
						{required: true, message: this.$t('validation.required', {attribute: this.$t('table.users.email')}), trigger: 'blur'},
						{type: 'email', message: this.$t('validation.email', {attribute: this.$t('table.users.email')}), trigger: ['blur', 'change']}
					],
					role_id: [
						{required: true, message: this.$t('validation.required', {attribute: this.$t('table.users.role')}), trigger: 'change'},
					],
					password: [
						{validator: password, trigger: ['change', 'blur']}
					],
					password_confirm: [
						{validator: password_confirm, trigger: ['change', 'blur']}
					]
				}
			}
		},
		mounted() {
			this.roles();

			let {id} = this.$route.params;
			if(id) {
				edit(id)
					.then(res => this.form = res.data.data);
			}
		},
		methods: {
			store(users) {
				this.loading = true;
				this.$refs[users].validate(valid => {
					if(valid) {
						store(this.form)
							.then(res => {
								this.$message({
									showClose: true,
									message: this.$t('messages.create'),
									type: 'success'
								});
								this.$refs[users].resetFields();
							})
							.catch(err => {
								console.log(err);
							})
					}else {
						return false;
					}
				});
				this.loading = false;
			},
			roles() {
				roles().then(err => this.rolesList = err.data.data);
			},
			update(users) {
				this.$refs[users].validate(valid => {
					if(valid) {
						delete this.form.password;
						update(this.form, this.$route.params.id)
							.then(res => {
								this.$message({
									showClose: true,
									message: this.$t('messages.update'),
									type: 'success'
								});
								this.$router.push({name: 'users'});
							}).catch(err => {
								console.log(err);
							});
					}else {
						return false;
					}
				});
				this.loading = false;
			}
		},
		watch: {
		}
	}
</script>