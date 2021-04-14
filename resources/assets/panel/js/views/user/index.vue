<template>
	<v-app>
		<v-container fluid>
			<v-row>
				<v-col cols="12">
					<v-data-table
						:headers="headers"
						:items="desserts"
						:loading="loading"
						:options.sync="options"
						:server-items-length="totalDesserts"
						:footer-props="{'items-per-page-options': [25, 50, 100]}"
					>
						<template v-slot:top>
							<v-toolbar flat>
								<v-toolbar-title>{{ $t('pages.users.title') }}</v-toolbar-title>
								<v-spacer></v-spacer>
								<v-text-field
									v-model="search"
									append-icon="search"
									:label="$t('fields.search')"
									flat
									dense
									solo-inverted
									hide-details
									single-line
									class="mr-2"
								></v-text-field>
							</v-toolbar>
						</template>
						<template v-slot:item.actions="{ item }">
							<v-btn :to="{ name:'user.edit', params:{id: item.id} }" icon class="mr-2" small color="primary" dark v-permission="['edit-user']"> <v-icon small>edit</v-icon> </v-btn>
							<v-btn icon small color="error" dark @click="deleteItem(item)" v-permission="['delete-user']"> <v-icon small>delete</v-icon> </v-btn>
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

import { getUsers, deleteUser } from "../../api/user";

export default {
	name: "index",
	data() {
		return {
			loading: false,
			options: {},
			search: "",
			desserts: [],
			totalDesserts: 0
		}
	},
	computed: {
		headers() {
			return  [
				{
					text: this.$i18n.t('global.id'),
					align: 'left',
					value: 'id',
					width:'5%'
					
				},
				{
					text: this.$i18n.t('pages.users.username'),
					align: 'center',
					value: 'username'
				},
				{
					text: this.$i18n.t('pages.users.first_name'),
					align: 'center',
					value: 'first_name'
				},
				{
					text: this.$i18n.t('pages.users.last_name'),
					align: 'center',
					value: 'last_name'
				},
				{
					text: this.$i18n.t('pages.users.email'),
					align: 'center',
					value: 'email'
				},
				{
					text: this.$i18n.t('pages.users.created_at'),
					align: 'center',
					value: 'created_at'
				},
				{
					text: this.$i18n.t('global.actions'),
					align: 'center',
					value: 'actions',
					width: "5%",
					sortable: false
				},
			]
			
		}
	},
	watch: {
		options: {
			handler () {
				this.initialize()
			},
			deep: true,
		},
		search() {
			this.initialize();
		}
	},
	methods: {
		initialize() {
			this.loading = true
			const { sortBy, sortDesc, page, itemsPerPage } = this.options
			let search = this.search.trim().toLowerCase();
			getUsers({ sortBy, sortDesc, page, itemsPerPage, search }).then(response => {
				this.loading = false
				this.desserts = response.data.data
				this.totalDesserts = response.data.links.total
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
						deleteUser(item.id).then(response => {
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