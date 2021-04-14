<template>
	<v-app>
		<v-container fluid>
			<v-row>
				<v-col cols="12">
					<v-data-table :headers="headers" :items="desserts" sort-by="calories" :laoding="loading" :options.sync="options">
						<template v-slot:top>
							<v-toolbar flat>
								<v-toolbar-title>{{ $t('menus.roles.index') }}</v-toolbar-title>
								<v-spacer></v-spacer>
								<v-btn color="primary" dark class="justify-content-between" :to="{name: 'role.create'}" v-permission="['create-role']">
									<v-icon small>add</v-icon>
									{{ $t('pages.roles.add_role') }}
								</v-btn>
							</v-toolbar>
						</template>
						<template v-slot:item.actions="{ item }">
							<v-btn :to="{ name:'role.edit', params:{id: item.id} }" icon class="mr-2" small color="primary" dark v-permission="['edit-role']"> <v-icon small>edit</v-icon> </v-btn>
							<v-btn icon small color="error" dark @click="deleteItem(item)" v-permission="['delete-role']"> <v-icon small>delete</v-icon> </v-btn>
						</template>
						<template v-slot:no-data>
							<v-btn color="primary" @click="initialize">Reset</v-btn>
						</template>
					</v-data-table>
				</v-col>
			</v-row>
		</v-container>
	</v-app>
</template>


<script>
import { getRoles, deleteRole } from "../../api/role";

export default {
	name: "index",
	data() {
		return {
			headers: [
				{
					text: this.$i18n.t('global.id'),
					align: 'left',
					value: 'id',
					width:'5%'
				
				},
				{
					text: this.$i18n.t('pages.roles.role_name'),
					align: 'left',
					value: 'name'
				},
				{
					text: this.$i18n.t('global.actions'),
					align: 'center',
					value: 'actions',
					width: "5%",
					sortable: false
				},
			],
			desserts: [],
			options: {},
			loading: false
		}
	},
	created() {
		this.initialize()
	},
	watch: {
		options: {
			handler () {
				this.initialize()
			},
			deep: true,
		},
	},
	methods: {
		initialize() {
			getRoles().then(response => {
				this.loading = false;
				this.desserts = response.data;
			}).catch(err => { this.loading = false })
		},
		async deleteItem(item) {
			this.$confirm({
				message: this.$i18n.t('global.cancel_dialog_title'),
				button: {
					no: this.$i18n.t('buttons.no'),
					yes: this.$i18n.t('buttons.yes')
				},
				callback: confirm => {
					if (confirm) {
						deleteRole(item.id).then(response => {
							this.desserts.splice(this.desserts.indexOf(item), 1);
						}).catch(err => {})
					}
				}
			})
		}
	},
}
</script>

<style scoped>

</style>