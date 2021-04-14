<template>
	<v-app>
		<v-container fluid>
			<validation-observer ref="form" v-slot="{ invalid, validated, passes, validate }">
				<v-form @keyup.native.enter="passes(formSubmit)">
					<validation-provider v-slot="{ errors, valid }" name="role_name" rules="required|max:25">
						<v-text-field
							v-model="role.name"
							:counter="25"
							:error-messages="errors[0]"
							:success="valid"
							:label="$t('fields.role_name')"
							dense
							outlined
							required
						></v-text-field>
					</validation-provider>
			
					<v-data-table
						:headers="headers"
						:items="routes"
						:expanded.sync="expanded"
						item-key="name"
						:search="search"
					>
						<template v-slot:top>
							<v-toolbar flat>
								<v-toolbar-title>{{ $t('pages.roles.permissions') }}</v-toolbar-title>
								<v-spacer></v-spacer>
							</v-toolbar>
						</template>
						<template v-slot:item.menu="{ item }">
							{{ $t('menus.' + item.meta.title) }}
						</template>
						
						<template v-slot:item.access="{ item }">
							<v-switch
								v-model="role.permissions"
								color="success"
								:value="'access-' + item.name"
								hide-details
							></v-switch>
						</template>
						
						<template v-slot:item.create="{ item }">
							<v-switch
								v-model="role.permissions"
								color="success"
								:value="'create-' + item.name"
								hide-details
								v-if="item.meta.crud"
							></v-switch>
						</template>
						
						<template v-slot:item.edit="{ item }">
							<v-switch
								v-model="role.permissions"
								color="success"
								:value="'edit-' + item.name"
								hide-details
								v-if="item.meta.crud"
							></v-switch>
						</template>
						
						<template v-slot:item.delete="{ item }">
							<v-switch
								v-model="role.permissions"
								color="success"
								:value="'delete-' + item.name"
								hide-details
								v-if="item.meta.crud"
							></v-switch>
						</template>
					</v-data-table>
					<v-btn color="primary" @click="formSubmit" :disabled="invalid || !validated" :loading="loading">{{ $t("buttons.save") }}</v-btn>
				</v-form>
			</validation-observer>
		</v-container>
	</v-app>
</template>

<script>
import {addRole, getRole, updateRole} from '../../../api/role'
import {asyncRoutes, constantRoutes} from '../../../router'
import {mapGetters} from 'vuex'

export default {
	name: "From",
	props: {
		isEdit: {
			type: Boolean,
			default: false
		}
	},
	data() {
		return {
			role: {
				id: 0,
				name: '',
				permissions: []
			},
			search: "",
			expanded: [],
			routes: [],
			loading: false
		}
	},
	created() {
		this.generateRoutes(asyncRoutes);
		this.generateRoutes(constantRoutes);
		
		if(this.isEdit) {
			const id = this.$route.params && this.$route.params.id
			this.getRole(id);
		}
	},
	computed: {
		...mapGetters([
			'user'
		]),
		headers() {
			return [
				{
					text: this.$i18n.t('pages.roles.menu'),
					align: 'left',
					value: 'menu',
				},
				{
					text: this.$i18n.t('pages.roles.access'),
					value: 'access',
				},
				{
					text: this.$i18n.t('pages.roles.create'),
					value: 'create',
				},
				{
					text: this.$i18n.t('pages.roles.edit'),
					value: 'edit',
				},
				{
					text: this.$i18n.t('pages.roles.delete'),
					value: 'delete',
				}
			]
		}
	},
	methods: {
		generateRoutes(routes, basePath = '/') {
			for (let route of routes) {
				if (route.hidden) {
					continue
				}
				if (route.path === "/") {
					continue
				}
				if (route.meta.hidePermission) {
					continue
				}
				
				this.routes.push(route)
				
				if (route.children) {
					this.generateRoutes(route.children, route.path)
				}
				
			}
		},
		getRole(id) {
			getRole(id).then(response => {
				this.role = response.data
			}).then(async response => await this.$refs.form.validate())
		},
		formSubmit() {
			this.loading = true;
			
			if(this.isEdit) {
				updateRole(this.role.id, this.role).then(response => {
					this.loading = false
					this.$router.push({name: 'role.index'})
					
					if(this.role.id == this.user.role.id){
						window.location.reload();
					}
				}).catch(err => {
					this.loading = false
					
					if(err.response.status === 400) {
						this.$refs.form.setErrors(err.response.data.data)
					}
				})
			} else {
				addRole(this.role).then(response => {
					this.loading = false
					this.$router.push({name: 'role.index'})
				}).catch(err => {
					this.loading = false
					
					if(err.response.status === 400) {
						this.$refs.form.setErrors(err.response.data.data)
					}
				})
			}
		}
	},
}
</script>

<style>
.v-input--selection-controls{
	margin-top: 0!important;
}
</style>