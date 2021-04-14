<template>
	<v-app>
		<avatar-dialog v-if="avatarsDialog" @dialog="handleDialog" :active="admin.avatar" @select-avatar="handleAvatar"></avatar-dialog>
		<v-container fluid>
			<v-row>
				<v-col sm="12" md="4" lg="3" xl="2">
					<v-card flat rounded="0" elevation="1">
						<div class="d-flex justify-center align-end pa-5 black">
							<v-img :src="admin.avatar.image_path" max-width="200" flat></v-img>
						</div>
						<div>
							<v-btn color="primary" class="profile-image-upload text-white flat rounded-0" elevation="0" block @click="avatarsDialog = true">{{ $t('buttons.select_avatar') }}</v-btn>
						</div>
						<v-card-title class="justify-center">
							<v-icon>account_box</v-icon> {{ admin.username }}
						</v-card-title>
					</v-card>
				</v-col>
				<v-col sm="12" md="8" lg="9" xl="10">
					<v-card flat rounded="0" elevation="1">
						<v-tabs dark background-color="#42b983" show-arrows v-model="selectedTab">
							<v-tabs-slider color="teal lighten-3"></v-tabs-slider>
							<v-tab href="#tab-settings">
								<v-icon small class="mr-1">dns</v-icon>
								{{ $t('global.form') }}
							</v-tab>
						</v-tabs>
						
						<v-tabs-items v-model="selectedTab">
							<v-tab-item value="tab-settings">
								<validation-observer ref="form" v-slot="{ invalid, validated, passes, validate }">
									<v-form @keyup.native.enter="passes(formSubmit)">
										<v-card flat>
											<v-card-title>{{ $t("pages.admins.add_admin") }}</v-card-title>
											<v-card-text>
												<v-row>
													<v-col>
														<validation-provider v-slot="{ errors, valid }" name="first_name" rules="required|max:25">
															<v-text-field
																v-model="admin.first_name"
																:counter="25"
																:error-messages="errors[0]"
																:success="valid"
																:label="$t('fields.first_name')"
																prepend-inner-icon="person"
																dense
																outlined
																required
															></v-text-field>
														</validation-provider>
													
													</v-col>
													<v-col>
														<validation-provider v-slot="{ errors, valid }" name="last_name" rules="required|max:25">
															<v-text-field
																v-model="admin.last_name"
																:counter="25"
																:error-messages="errors[0]"
																:success="valid"
																:label="$t('fields.last_name')"
																prepend-inner-icon="person"
																dense
																outlined
																required
															></v-text-field>
														</validation-provider>
													
													</v-col>
												</v-row>
												<v-row>
													<v-col>
														<validation-provider v-slot="{ errors,valid }" name="email" rules="required|email">
															<v-text-field
																v-model="admin.email"
																:counter="150"
																:error-messages="errors[0]"
																:success="valid"
																:label="$t('fields.email')"
																prepend-inner-icon="alternate_email"
																dense
																outlined
																required
															></v-text-field>
														</validation-provider>
													</v-col>
													
													<v-col>
														<validation-provider v-slot="{ errors, valid }" name="username" rules="required|max:25">
															<v-text-field
																v-model="admin.username"
																:counter="25"
																:error-messages="errors[0]"
																:success="valid"
																:label="$t('fields.username')"
																prepend-inner-icon="assignment_ind"
																dense
																outlined
																required
															></v-text-field>
														</validation-provider>
													</v-col>
												</v-row>
												<v-row>
													<v-col>
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
																return-object
																dense
																outlined
															></v-select>
														</validation-provider>
													</v-col>
												</v-row>
												<v-row>
													<v-col>
														<validation-provider v-slot="{ errors, valid }" name="password" rules="required|min:6">
															<v-text-field
																v-model="admin.password"
																:counter="25"
																:error-messages="errors[0]"
																:success="valid"
																:label="$t('fields.password')"
																prepend-inner-icon="password"
																:append-icon="showPassword ? 'visibility' : 'visibility_off'"
																@click:append="showPassword = !showPassword"
																:type="showPassword ? 'text':'password'"
																outlined
																dense
																required
															></v-text-field>
														</validation-provider>
													
													</v-col>
													<v-col>
														<validation-provider v-slot="{ errors,valid }" name="password_confirmation" rules="required|min:6|confirmed:password">
															<v-text-field
																v-model="admin.password_confirmation"
																:counter="25"
																:error-messages="errors[0]"
																:success="valid"
																:label="$t('fields.password_confirmation')"
																prepend-inner-icon="password"
																:append-icon="showPasswordConfirm ? 'visibility' : 'visibility_off'"
																@click:append="showPasswordConfirm = !showPasswordConfirm"
																:type="showPasswordConfirm ? 'text':'password'"
																dense
																outlined
																required
															></v-text-field>
														</validation-provider>
													</v-col>
												</v-row>
												<v-row>
													<v-col>
														<v-btn class="mr-4" color="primary" :disabled="invalid || !validated" :loading="loadingForm" @click="passes(formSubmit)">
															{{ $t('buttons.submit') }}
														</v-btn>
													</v-col>
												</v-row>
											</v-card-text>
										</v-card>
									</v-form>
								</validation-observer>
							</v-tab-item>
						</v-tabs-items>
					</v-card>
				</v-col>
			</v-row>
		</v-container>
	</v-app>
</template>

<script>
import AvatarDialog from "../../../components/AvatarDialog";
import {getRoles} from "../../../api/role";
import { createAdmin } from "../../../api/admin";
export default {
	name: 'From',
	components: {
		AvatarDialog
	},
	props: ['isEdit'],
	data() {
		return {
			selectedTab: 'tab-settings',
			loadingForm: false,
			activeTab: 'settings',
			admin: {
				first_name: "",
				last_name: "",
				username: "",
				email: "",
				password: "",
				password_confirmation: "",
				role: "",
				avatar: {
					id: 1,
					image_path: "",
				},
			},
			password : {},
			roles: [],
			status: [
				{ label: this.$i18n.t('global.active'), value: 1 },
				{ label: this.$i18n.t('global.passive'), value: 0 },
			],
			interval: null,
			showPassword: false,
			showPasswordConfirm: false,
			avatarsDialog: false,
			showSavePhotoButton: false,
		}
	},
	created() {
		this.loadRoles()
	},
	methods: {
		loadRoles() {
			getRoles().then(response => {
				this.loading = false;
				this.roles = response.data;
			}).catch(err => { this.loading = false })
		},
		formSubmit() {
			this.loading = true
			this.admin.role = this.admin.role.name;
			createAdmin(this.admin).then(response => {
				this.loading = false
				this.$router.push({name: 'admin.index'})
			}).catch(err => {
				this.loading = false
				
				if(err.response.status === 400) {
					this.$refs.form.setErrors(err.response.data.data)
				}
			})
		},
		handleAvatar(payload) {
			this.admin.avatar.id = payload.id
			this.admin.avatar.image_path = payload.image_path
			this.showSavePhotoButton = true
		},
		handleDialog(payload) {
			this.avatarsDialog = payload
		}
	},
}
</script>

<style>
.v-input--selection-controls{
	margin-top: 0!important;
}

</style>