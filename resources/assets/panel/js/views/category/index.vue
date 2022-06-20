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
                        class="category-list-table"
                        ref="sortableTable"
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
                                    @change="initialize"
								></v-text-field>
								<v-btn color="primary" dark class="justify-content-between" @click="formDialog = true" v-permission="['create-category']">
									<v-icon small>add</v-icon>
									{{ $t('buttons.add_new') }}
								</v-btn>
							</v-toolbar>
						</template>
                        <template v-slot:item="{ item }">
                            <tr class="sortableRow"  :key="item.id">
                                <td>
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn style="cursor: move" icon class="sortHandle">
                                                <v-icon>drag_handle</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>{{$t('global.sort')}}</span>
                                    </v-tooltip>
                                </td>
                                <td>{{item.id}}</td>
                                <td>{{item.name}}</td>
                                <td>
                                    <v-chip class="mr-2" small v-for="lang in item.translations" label color="primary" :key="lang.locale">
                                        {{ lang.locale.toUpperCase()}}
                                    </v-chip>
                                </td>
                                <td>{{item.created_at}}</td>
                                <td>
                                    <v-btn icon class="mr-2" small color="primary" dark v-permission="['edit-category']" @click="handleSelectedCategory(item.id)"> <v-icon small>edit</v-icon> </v-btn>
                                    <v-btn icon small color="error" dark v-permission="['delete-category']" @click="deleteItem(item)"> <v-icon small>delete</v-icon> </v-btn>
                                </td>
                            </tr>
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
import { getCategories, updateDisplayOrder, deleteCategory } from "../../api/category";
import FormDialog from './components/FormDialog.vue'
import Sortable from 'sortablejs'

export default {
	name: "index",
	components: {
		FormDialog
	},
	data() {
		return {
            expandRow: null,
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
    mounted() {
        this.initializeSortable()
    },
    computed: {
		headers() {
			return  [
				{
					text: this.$i18n.t('fields.order_by'),
					value: 'order_by',
					width: "5%",
				},
				{
					text: this.$i18n.t('fields.id'),
					value: 'id',
					width:'5%'

				},
				{
					text: this.$i18n.t('fields.name'),
					value:  this.defaultLangCode + '.name'
				},
                {
                    text: this.$i18n.t('fields.translations'),
                    value: 'created_at'
                },
                {
                    text: this.$i18n.t('fields.created_at'),
                    value: 'created_at'
                },
				{
					text: this.$i18n.t('global.actions'),
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
        initializeSortable() {
            new Sortable(
                this.$refs.sortableTable.$el.getElementsByTagName('tbody')[0],
                {
                    draggable: '.sortableRow',
                    handle: '.sortHandle',
                    onStart: this.dragStart,
                    onEnd: this.dragReOrder
                }
            )
        },
        dragStart({item}) {
            const nextSib = item.nextSibling
            if (nextSib && nextSib.classList.contains('datatable__expand-row')) {
                this.expandRow = nextSib
            } else {
                this.expandRow = null
            }
        },
        dragReOrder({item, oldIndex, newIndex}) {
            const nextSib = item.nextSibling
            if (nextSib && nextSib.classList.contains('datatable__expand-row') && nextSib !== this.expandRow) {
                item.parentNode.insertBefore(item, nextSib.nextSibling)
            }
            const movedItem = this.desserts.splice(oldIndex, 1)[0]
            this.desserts.splice(newIndex, 0, movedItem)
            this.updateDisplayOrder();
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
        updateDisplayOrder() {
            let items = this.desserts.map(page => page.id);
            updateDisplayOrder({items})
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
