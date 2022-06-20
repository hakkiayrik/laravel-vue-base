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
								<v-toolbar-title>{{ $t('pages.posts.title') }}</v-toolbar-title>
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
								<v-btn color="primary" dark class="justify-content-between" :to="{name: 'post.create'}" v-permission="['create-post']">
									<v-icon small>add</v-icon>
									{{ $t('buttons.add_new') }}
								</v-btn>
							</v-toolbar>
						</template>
                        <template v-slot:item="{ item }">
                            <tr :key="item.id">
                                <td>{{ item.id }}</td>
                                <td>{{ item[defaultLangCode].title }}</td>
                                <td>{{ item[defaultLangCode].slug }}</td>
                                <td>{{ $t("pages.posts.types."+item.type) }}</td>
                                <td>{{ $t("pages.posts.visibilities."+item.visibility) }}</td>
                                <td>
                                    <v-chip class="mr-2" small color="success" label>
                                        <v-icon x-small class="mr-2">thumb_up</v-icon> {{ item.like }}</v-chip>
                                    <v-chip class="mr-2" small color="error" label>
                                        <v-icon x-small class="mr-2">thumb_down</v-icon> {{ item.dislike }}
                                    </v-chip>
                                </td>
                                <td>{{ item.published_date }}</td>
                                <td>{{ item.created_at_string }}</td>
                                <td class="text-center">
                                    <v-switch
                                        v-model="item.status"
                                        color="success"
                                        class="d-inline-block ma-0"
                                        hide-details
                                        dense
                                        @change="handleStatus(item)"
                                    ></v-switch>
                                </td>
                                <td>
                                    <v-btn icon class="mr-2" small color="primary" dark :to="{ name:'post.edit', params:{id: item.id} }" v-permission="['edit-post']"> <v-icon small>edit</v-icon> </v-btn>
                                    <v-btn icon small color="error" dark v-permission="['delete-post']" @click="deleteItem(item)"> <v-icon small>delete</v-icon> </v-btn>
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
import { getPosts, deletePost } from "../../api/post";

export default {
	name: "index",
	data() {
		return {
            defaultLangCode : process.env.MIX_DEFAULT_LANGUAGE,
			loading: false,
			options: {},
			search: "",
			desserts: [],
			totalDesserts: 0,
		}
	},
	computed: {
		headers() {
			return  [
				{
					text: this.$i18n.t('fields.id'),
					align: 'left',
					value: 'id',
					width:'5%'

				},
				{
					text: this.$i18n.t('fields.title'),
					value: 'title'
				},
				{
					text: this.$i18n.t('fields.slug'),
					value: 'slug'
				},
				{
					text: this.$i18n.t('fields.type'),
					value: 'type'
				},
				{
					text: this.$i18n.t('fields.visibility'),
					value: 'visibility'
				},{
                    text: this.$i18n.t('fields.like_dislike'),
                },

                {
                    text: this.$i18n.t('fields.published_date'),
                    value: 'published_date'
                },
                {
                    text: this.$i18n.t('fields.created_at'),
                    value: 'created_at'
                },
                {
                    text: this.$i18n.t('fields.publish_status'),
                    value: 'status',
                    align: 'center'
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
			getPosts({ sortBy, sortDesc, page, itemsPerPage, search }).then(response => {
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
						deletePost(item.id).then(response => {
							this.desserts.splice(this.desserts.indexOf(item), 1);
						}).catch(err => {})
					}
				}
			})
		},
        handleStatus() {

        }
	},
}
</script>

<style scoped>
.category-list-table tr td {
	text-align: center;
}
</style>
