<template>
	<v-app>
		<form-dialog v-if="formDialog" @dialog="handleDialog" @refresh="refreshData" :selected-category="selectedCategory"></form-dialog>
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
								<v-toolbar-title>{{ $t('pages.categories.title') }}</v-toolbar-title>
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
								<v-btn color="primary" dark class="justify-content-between" @click="formDialog = true" v-permission="['create-category']">
									<v-icon small>add</v-icon>
									{{ $t('buttons.add_new') }}
								</v-btn>
							</v-toolbar>
						</template>
						<template v-slot:item.actions="{ item }">
							<v-btn icon class="mr-2" small color="primary" dark v-permission="['edit-category']" @click="handleSelectedCategory(item.id)"> <v-icon small>edit</v-icon> </v-btn>
							<v-btn icon small color="error" dark v-permission="['delete-category']" @click="deleteItem(item)"> <v-icon small>delete</v-icon> </v-btn>
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
import { getCategories, deleteCategory } from "../../api/category";
import FormDialog from './components/FormDialog.vue'
export default {
	name: "index",
	components: {
		FormDialog
	},
	data() {
		return {
			defaultLangCode : process.env.MIX_DEFAULT_LANGUAGE,
			loading: false,
			options: {},
			search: "",
			desserts: [],
			totalDesserts: 0,
			formDialog: false,
			selectedCategory: ""
		}
	},
	computed: {
		headers() {
			return  [
				{
					text: this.$i18n.t('fields.order_by'),
					align: 'left',
					value: 'order_by',
					width: "5%",
				},
				{
					text: this.$i18n.t('fields.id'),
					align: 'left',
					value: 'id',
					width:'5%'
					
				},
				{
					text: this.$i18n.t('fields.name'),
					align: 'center',
					value: 'name'
				},
				{
					text: this.$i18n.t('fields.slug'),
					align: 'center',
					value: 'slug'
				},
				{
					text: this.$i18n.t('fields.description'),
					align: 'center',
					value: 'description'
				},
				{
					text: this.$i18n.t('fields.created_at'),
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
			getCategories({ sortBy, sortDesc, page, itemsPerPage, search }).then(response => {
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
						deleteCategory(item.id).then(response => {
							this.desserts.splice(this.desserts.indexOf(item), 1);
						}).catch(err => {})
					}
				}
			})
		},
		handleSelectedCategory(id) {
			this.selectedCategory = id
			this.handleDialog(true);
		},
		handleDialog(payload) {
			this.formDialog = payload
			if(!payload) {
				this.selectedCategory = ""
			}
		},
		refreshData() {
			this.initialize();
		}
	},
}
</script>

<style scoped>
	.category-list-table tr td {
		text-align: center;
	}
</style>