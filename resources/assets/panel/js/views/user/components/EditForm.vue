<template>
	<v-app>
		<v-container fluid>
			<v-row>
				<v-col sm="12" md="4" lg="3" xl="2">
					<v-card flat rounded="0" elevation="1">
						<div class="d-flex justify-center pa-3">
							<v-img  class="rounded-pill" max-width="75%" :src="user.avatar ? user.avatar.image_path : ''" flat>
								<template v-slot:placeholder>
									<v-row class="fill-height ma-0" align="center" justify="center">
										<v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
									</v-row>
								</template>
							</v-img>
						</div>
						<v-card-title class="justify-center">
							<v-icon>account_box</v-icon> {{ user.username }}
						</v-card-title>
						<v-divider></v-divider>
						<v-card-text>
							<v-subheader class="pa-0 font-weight-bold">{{ $t('pages.users.info') }}</v-subheader>
							<v-list class="pa-0">
								<v-list-item class="pa-0">
									<v-list-item-icon class="mr-1">
										<v-icon color="green">perm_identity</v-icon>
									</v-list-item-icon>
									{{ user.first_name + " " + user.last_name }}
								</v-list-item>
								<v-list-item class="pa-0">
									<v-list-item-icon class="mr-1">
										<v-icon color="green">alternate_email</v-icon>
									</v-list-item-icon>
									{{ user.email }}
								</v-list-item>
								<v-list-item class="pa-0">
									<v-list-item-icon class="mr-1">
										<v-icon color="green">access_time</v-icon>
									</v-list-item-icon>
									{{ user.created_at }}
								</v-list-item>
							</v-list>
						</v-card-text>
					</v-card>
				</v-col>
				<v-col sm="12" md="8" lg="9" xl="10">
					<v-dialog v-model="logDetailDialog" max-width="500px">
						<v-card>
							<v-card-title>{{ logDetailDialogData.title }}</v-card-title>
							<v-card-text>
								<json-viewer :value="jsonParser(logDetailDialogData.message)"></json-viewer>
							</v-card-text>
							<v-card-actions>
								<v-btn color="primary" text @click="logDetailDialog = false">
									{{ $t('buttons.close') }}
								</v-btn>
							</v-card-actions>
						</v-card>
					</v-dialog>
					
					<v-card flat rounded="0" elevation="1">
						<v-tabs dark background-color="#42b983" show-arrows v-model="selectedTab">
							<v-tabs-slider color="teal lighten-3"></v-tabs-slider>
							<v-tab href="#tab-activity">
								<v-icon small class="mr-1">timeline</v-icon>
								{{ $t('global.activity') }}
							</v-tab>
							<v-tab href="#tab-settings">
								<v-icon small class="mr-1">engineering</v-icon>
								{{ $t('global.settings') }}
							</v-tab>
						</v-tabs>
						
						<v-tabs-items v-model="selectedTab">
							<v-tab-item value="tab-activity">
								<v-card flat>
									<v-card-text class="py-0">
										<v-timeline>
											<v-timeline-item v-for="item in userLogs" :key="item.id" :color="item.color" :icon="item.icon" fill-dot>
												<span slot="opposite">{{ item.created_at }}</span>
												<v-alert :value="true" :color="item.color" class="white--text ma-0">
													<v-row>
														<v-col class="grow">
															{{ item.message }}
														</v-col>
														<v-col class="shrink">
															<v-btn outlined color="white" small  @click="openLogDetailDialog(item)">{{ $t("buttons.detail") }}</v-btn>
														</v-col>
													</v-row>
												</v-alert>
											</v-timeline-item>
										</v-timeline>
									</v-card-text>
								</v-card>
							</v-tab-item>
							<v-tab-item value="tab-settings">
									<v-form @keyup.native.enter="passes(formSubmit)">
										<v-card flat>
											<v-card-title>{{ $t("fields.status") }}</v-card-title>
											<v-card-text class="pb-0">
												<validation-provider name="status" rules="required" immediate v-slot="{ errors, valid  }">
													<v-select
														v-model="user.status"
														:items="status"
														item-text="label"
														item-value="value"
														:error-messages="errors[0]"
														:success="valid"
														:label="$t('fields.status')"
														:prepend-inner-icon="user.status ? 'person' : 'person_off'"
														dense
														outlined
													></v-select>
												</validation-provider>
											</v-card-text>
										</v-card>
										<v-card flat>
											<v-card-title>{{ $t("pages.users.reset_password") }}</v-card-title>
											<v-card-text class="pb-0">
												<v-alert text dense color="teal" icon="info" border="left">{{ $t('pages.users.password_information') }}</v-alert>
												<v-text-field
													outlined
													v-model="user.password"
													type="password"
													:label="$t('fields.new_password')"
													prepend-inner-icon="lock"
													required
													dense
												></v-text-field>
											</v-card-text>
										</v-card>
										<v-card flat>
											<v-card-text>
												<v-btn color="primary" block @click="formSubmit" :loading="loadingForm">{{ $t("buttons.save") }}</v-btn>
											</v-card-text>
										</v-card>
									</v-form>
								
							</v-tab-item>
						</v-tabs-items>
					</v-card>
				</v-col>
			</v-row>
		</v-container>
	</v-app>
</template>

<script>
import { getUser, getUserLogs, updateUser } from "../../../api/user";
export default {
	name: 'From',
	props: ['isEdit'],
	
	data() {
		return {
			selectedTab: 'tab-activity',
			loadingForm: false,
			activeTab: 'activity',
			user: {},
			password : {},
			status: [
				{ label: this.$i18n.t('global.active'), value: 1 },
				{ label: this.$i18n.t('global.passive'), value: 0 },
			],
			interval: null,
			userLogs: [],
			logDetailDialog: false,
			logDetailDialogData: {
				title: '',
				message: '{}',
			}
		}
	},
	created() {
		if(this.isEdit) {
			const id = this.$route.params && this.$route.params.id;
			this.getUser(id)
		}
	},
	methods: {
		getUser(id) {
			getUser(id).then(response => {
				this.loading = false;
				this.user = response.data;
				
				this.getUserLogs(id)
			}).catch(err => { this.loading = false })
		},
		getUserLogs(id) {
			getUserLogs(id).then(response => {
				this.loading = false;
				this.userLogs = response.data;
			}).catch(err => { this.loading = false })
		},
		formSubmit() {
			this.loading = true
			if(this.isEdit) {
				updateUser(this.user.id, this.user).then(response => {
					this.loading = false
					this.$router.push({name: 'user.index'})
				}).catch(err => {
					this.loading = false
					
					if(err.response.status === 400) {
						this.$refs.form.setErrors(err.response.data.data)
					}
				})
			}
		},
		openLogDetailDialog(payload) {
			this.logDetailDialogData.title = payload.message
			this.logDetailDialogData.message = payload.data
			
			this.logDetailDialog = true;
		},
		
		jsonParser(jsonData) {
			return JSON.parse(jsonData)
		}
	},
}
</script>

<style>
	.v-input--selection-controls{
		margin-top: 0!important;
	}
	
</style>