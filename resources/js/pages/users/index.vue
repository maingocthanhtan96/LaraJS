<template>
    <el-row>
        <el-col :span="24">
            <el-card>
                <div class="flex justify-end items-center" slot="header">
                    <router-link :to="{name: 'user_form'}" class="hover:bg-purple-dark hover:text-white font-bold border rounded border-purple-dark text-purple-dark bg-transparent py-2 px-3" tag="button">
                        <i class="fa fa-plus mr-2"></i>Create
                    </router-link>
                </div>
                <v-server-table
                    ref="table_users"
                    name="table_users"
                    :columns="table.columns"
                    :options="table.options"
                >
                    <template slot="id" slot-scope="props">{{props.index}}</template>
                    <div slot="actions" slot-scope="{row}" class="flex justify-center items-center">
                        <router-link :to="{name: 'user_form_edit', params: {id: row.id}}"><i class="fa fa-edit text-primary mr-2"></i></router-link>
                        <a class="cursor-pointer" @click="remove(row.id, row.name)"><i class="fa fa-trash-o text-danger"></i></a>
                    </div>
                </v-server-table>
            </el-card>
        </el-col>
    </el-row>
</template>
<script>
    import {list, remove} from '@/api/users';
    export default {
        data() {
            return {
                table: {
                    columns: ['id', 'name', 'email', 'role.name', 'created_at', 'actions'],
                    options: {
						requestFunction: function (data) {
							return list(data);
						},
						headings: {
							id: () => this.$t('table.users.id'),
							name: () => this.$t('table.users.name'),
							'role.name': () => this.$t('table.users.role'),
							created_at: () => this.$t('date.created_at')
						},
						columnsClasses: {
							id: 'text-center',
							created_at: 'text-center',
							'role.name': 'text-center'
						},
						templates: {
							created_at: (h, row) => {
								return this.$options.filters.formatDate(row.created_at);
							},
						},
						sortable: ['id', 'created_at', 'role.name'],
                    }
                }
            }
        },
		mounted() {
		},
        methods: {
            remove(id, name) {
                this.$confirm(this.$t('messages.delete_confirm', {arrtribute: name}), this.$t('message.warning'), {
                    confirmButtonClass: 'outline-none',
                    confirmButtonText: this.$t('button.ok'),
                    cancelButtonClass: this.$t('button.cancel'),
                    type: 'warning',
                    center: true
                }).then(() => {
                    remove(id).then(() => {
                        let index = this.$refs.table_users.data.findIndex((value) => value.id == id );
                        this.$refs.table_users.data.splice(index, 1);
                        this.$message({
                            showClose: true,
                            message: this.$t('messages.delete'),
                            type: 'success'
                        });
                    });
                })
            }
        }
	}
</script>
