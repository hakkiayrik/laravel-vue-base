<template>
	<v-app>
		<v-container fluid>
			<v-dialog v-model="logDetailDialog" max-width="700px">
				<v-card>
					<v-card-title class="mb-2">{{ logDetailDialogData.title }}</v-card-title>
					<v-card-text>
						<v-row>
							<v-col v-if="logDetailDialogData.old_data != '[]'">
								<v-subheader>{{ $t("global.old") }}</v-subheader>
								<json-viewer :value="jsonParser(logDetailDialogData.old_data)"></json-viewer>
							</v-col>
							<v-col>
								<v-subheader>{{ $t("global.new") }}</v-subheader>
								<json-viewer :value="jsonParser(logDetailDialogData.new_data)"></json-viewer>
							</v-col>
						</v-row>
					</v-card-text>
					<v-card-actions>
						<v-spacer></v-spacer>
						<v-btn text @click="logDetailDialog = false">
							{{ $t('buttons.close') }}
						</v-btn>
					</v-card-actions>
				</v-card>
			</v-dialog>
			<v-row>
				<v-col cols="12">
					<v-data-table
						:headers="headers"
						:items="desserts"
						:loading="loading"
						:options.sync="options"
						:server-items-length="totalDesserts"
						:footer-props="{'items-per-page-options': [25, 50, 100]}"
						class="logTable"
					>
						<template v-slot:top>
							<v-toolbar flat>
								<v-toolbar-title>{{ $t('pages.logs.title') }}</v-toolbar-title>
							</v-toolbar>
						</template>
						<template v-slot:item.user="{ item }">
							{{ item.user.first_name }} {{ item.user.last_name }}
						</template>
						<template v-slot:item.action="{ item }">
							<v-chip color="primary" small v-if="item.action == 'updated'">{{ $t("global." + item.action) }}</v-chip>
							<v-chip color="success" small text-color="white" v-if="item.action == 'created'">{{ $t("global." + item.action) }}</v-chip>
							<v-chip color="error" small v-if="item.action == 'deleted'">{{ $t("global." + item.action) }}</v-chip>
						</template>
						<template v-slot:item.actions="{ item }">
							<v-btn outlined small @click="openLogDetailDialog(item)" v-permission="['access-log']">{{$t("pages.logs.detail")}}</v-btn>
						</template>
					</v-data-table>
				</v-col>
			</v-row>
		</v-container>
	</v-app>
</template>

<script>

import { getLogs } from "../../api/log";

export default {
	name: "index",
	data() {
		return {
			loading: false,
			options: {},
			desserts: [],
			totalDesserts: 0,
			logDetailDialog: false,
			logDetailDialogData: {
				title: '',
				old_data: '{}',
				new_data: '{}',
			}
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
					text: this.$i18n.t('pages.logs.user'),
					align: 'center',
					value: 'user'
				},
				{
					text: this.$i18n.t('pages.logs.action_model'),
					align: 'center',
					value: 'action_model'
				},
				{
					text: this.$i18n.t('pages.logs.created_at'),
					align: 'center',
					value: 'created_at'
				},
				{
					text: this.$i18n.t('pages.logs.ip'),
					align: 'center',
					value: 'ip'
				},
				{
					text: this.$i18n.t('pages.logs.action'),
					align: 'center',
					value: 'action'
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
			getLogs({ sortBy, sortDesc, page, itemsPerPage }).then(response => {
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
						deleteLog(item.id).then(response => {
							this.desserts.splice(this.desserts.indexOf(item), 1);
						}).catch(err => {})
					}
				}
			})
		},
		openLogDetailDialog(payload) {
			this.logDetailDialogData.title = payload.action_model
			this.logDetailDialogData.old_data = payload.old_data
			this.logDetailDialogData.new_data = payload.new_data
			
			this.logDetailDialog = true;
		},
		
		jsonParser(jsonData) {
			return JSON.parse(jsonData)
		}
	},
}
</script>

<style>
	.jv-code {
		background:#f1f1f1;
	}
	.logTable tr{
		border-bottom:none;
	}
	.logTable tr td {
		border: thin solid rgba(0,0,0,.12);
	}
</style>