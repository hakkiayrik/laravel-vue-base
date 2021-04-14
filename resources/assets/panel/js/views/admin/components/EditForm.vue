<template>
	<v-app>
		<v-container fluid>
			<v-row>
				<v-col sm="12" md="4" lg="3" xl="2">
					<v-card flat rounded="0" elevation="1">
						<div class="d-flex justify-center align-end pa-5 black">
							<v-img :src="admin.avatar.image_path" max-width="200" flat></v-img>
						</div>
						<v-card-title class="justify-center">
							<v-icon>account_box</v-icon> {{ admin.username }}
						</v-card-title>
						<v-divider></v-divider>
						<v-card-text>
							<v-subheader class="pa-0 font-weight-bold">{{ $t('pages.admins.info') }}</v-subheader>
							<v-list class="pa-0">
								<v-list-item class="pa-0">
									<v-list-item-icon class="mr-1">
										<v-icon color="green">perm_identity</v-icon>
									</v-list-item-icon>
									{{ admin.first_name }} {{ admin.last_name }}
								</v-list-item>
								<v-list-item class="pa-0">
									<v-list-item-icon class="mr-1">
										<v-icon color="green">alternate_email</v-icon>
									</v-list-item-icon>
									{{ admin.email }}
								</v-list-item>
								<v-list-item class="pa-0">
									<v-list-item-icon class="mr-1">
										<v-icon color="green">access_time</v-icon>
									</v-list-item-icon>
									{{ admin.created_at }}
								</v-list-item>
							</v-list>
						</v-card-text>
					</v-card>
				</v-col>
				<v-col sm="12" md="8" lg="9" xl="10">
					<v-card flat rounded="0" elevation="1">
						<v-tabs dark background-color="#42b983" show-arrows v-model="selectedTab">
							<v-tabs-slider color="teal lighten-3"></v-tabs-slider>
							<v-tab href="#tab-settings">
								<v-icon small class="mr-1">engineering</v-icon>
								{{ $t('global.settings') }}
							</v-tab>
						</v-tabs>
						
						<v-tabs-items v-model="selectedTab">
							<v-tab-item value="tab-settings">
									<v-form @keyup.native.enter="passes(formSubmit)">
										<v-card flat>
											<v-card-title>{{ $t("pages.admins.role") }}</v-card-title>
											<v-card-text class="pb-0">
												<validation-provider name="select_role" rules="required" immediate v-slot="{ errors, valid  }">
													<v-select
														v-model="admin.role"
														:items="roles"
														item-text="name"
														item-value="id"
														:error-messages="errors[0]"
														:success="valid"
														:label="$t('pages.roles.select_role')"
														prepend-inner-icon="admin_panel_settings"
														dense
														outlined
													></v-select>
												</validation-provider>
											</v-card-text>
										</v-card>
										<v-card flat>
											<v-card-title>{{ $t("fields.status") }}</v-card-title>
											<v-card-text class="pb-0">
												<validation-provider name="status" rules="required" immediate v-slot="{ errors, valid  }">
													<v-select
														v-model="admin.status"
														:items="status"
														item-text="label"
														item-value="value"
														:error-messages="errors[0]"
														:success="valid"
														:label="$t('fields.status')"
														:prepend-inner-icon="admin.status ? 'person' : 'person_off'"
														dense
														outlined
													></v-select>
												</validation-provider>
											</v-card-text>
										</v-card>
										<v-card flat>
											<v-card-title>{{ $t("pages.admins.reset_password") }}</v-card-title>
											<v-card-text class="pb-0">
												<v-alert text dense color="teal" icon="info" border="left">{{ $t('pages.admins.password_information') }}</v-alert>
												<v-text-field
													outlined
													v-model="admin.password"
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
import {getRoles} from "../../../api/role";
import { getAdmin, updateAdmin } from "../../../api/admin";
export default {
	name: 'From',
	props: ['isEdit'],
	data() {
		return {
			selectedTab: 'tab-settings',
			loadingForm: false,
			activeTab: 'settings',
			admin: {
				avatar: {
					id: 0,
					image_path: ""
				}
			},
			password : {},
			roles: [],
			status: [
				{ label: this.$i18n.t('global.active'), value: 1 },
				{ label: this.$i18n.t('global.passive'), value: 0 },
			],
			interval: null,
		}
	},
	created() {
		this.loadRoles()
		if(this.isEdit) {
			const id = this.$route.params && this.$route.params.id;
			this.getUser(id)
		}
	},
	methods: {
		loadRoles() {
			getRoles().then(response => {
				this.loading = false;
				this.roles = response.data;
				this.roles.push({ id : 0, name: this.$i18n.t('global.no_role'), permission: []})
			}).catch(err => { this.loading = false })
		},
		getUser(id) {
			getAdmin(id).then(response => {
				this.loading = false;
				this.admin = response.data;
			}).catch(err => { this.loading = false })
		},
		formSubmit() {
			this.loading = true
			this.admin.role = this.admin.role.name
			if(this.isEdit) {
				updateAdmin(this.admin.id, this.admin).then(response => {
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