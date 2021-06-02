<template>
	<v-app class="grey lighten-3">
		<avatar-dialog v-if="avatarsDialog" @dialog="handleDialog" :active="user.avatar" @select-avatar="handleAvatar"></avatar-dialog>
		<v-container fluid>
			<v-row>
				<v-col sm="12" md="4" lg="3" xl="2">
					<v-card flat rounded="0">
						<div class="d-flex justify-center align-end pa-5 black">
							<v-img :src="user.avatar.image_path" max-width="200" flat></v-img>
							<v-btn class="save-profile-image white--text" fab color="grey" x-small @click="updateInfo" v-if="showSavePhotoButton">
								<v-icon>save</v-icon>
							</v-btn>
						</div>
						<div>
							<v-btn color="primary" class="profile-image-upload text-white flat rounded-0" elevation="0" block @click="avatarsDialog = true">{{ $t('buttons.select_avatar') }}</v-btn>
						</div>
						<v-card-title>
							<v-icon>account_box</v-icon> {{ user.username }}
						</v-card-title>
						<v-divider></v-divider>
						<v-card-text>
							<v-subheader class="pa-0 font-weight-bold">{{ $t('pages.profile.info') }}</v-subheader>
							<v-list class="pa-0">
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
					<v-card flat rounded="0">
						<v-card-title>
							{{$t("pages.profile.account_information")}}
						</v-card-title>
						<v-card-text>
							<validation-observer ref="infoForm" v-slot="{ invalid, validated, passes, validate }">
								<v-form @keyup.native.enter="passes(updateInfo)">
									<validation-provider v-slot="{ errors, valid }" name="name" rules="required|max:100">
										<v-text-field
											outlined
											v-model="user.name"
											:error-messages="errors[0]"
											:success="valid"
											:label="$t('fields.first_name')"
											prepend-inner-icon="perm_identity"
											required
											dense
										></v-text-field>
									</validation-provider>
									<v-btn color="primary"  @click="updateInfo" :loading="loadingInfo">{{$t("buttons.save")}}</v-btn>
								</v-form>
							</validation-observer>
						</v-card-text>
					</v-card>
					
					<v-card class="mt-5" flat rounded="0">
						<v-card-title>
							{{$t("pages.profile.reset_password")}}
							</v-card-title>
							<v-card-text>
							<validation-observer ref="passwordForm" v-slot="{ invalid, validated, passes, validate }">
								<v-form @keyup.native.enter="passes(updatePassword)">
									<validation-provider v-slot="{ errors, valid }" name="old_password" rules="required|min:6|max:25">
										<v-text-field
											outlined
											v-model="password.old_password"
											type="password"
											:error-messages="errors[0]"
											:success="valid"
											:label="$t('fields.old_password')"
											prepend-inner-icon="lock"
											required
											dense
										></v-text-field>
									</validation-provider>
									<validation-provider v-slot="{ errors, valid }" name="new_password" rules="required|target:old_password|min:6|max:25">
										<v-text-field
											outlined
											v-model="password.new_password"
											type="password"
											:error-messages="errors[0]"
											:success="valid"
											:label="$t('fields.new_password')"
											prepend-inner-icon="lock"
											required
											dense
										></v-text-field>
									</validation-provider>
									<ul class="text--secondary caption">
										<li>{{$t("pages.profile.password_rule_1")}}</li>
										<li>{{$t("pages.profile.password_rule_2")}}</li>
										<li>{{$t("pages.profile.password_rule_3")}}</li>
									</ul>
									<v-btn color="primary" class="mt-2" :disabled="invalid" @click="updatePassword" :loading="loadingPassword">{{$t("buttons.save")}}</v-btn>
								</v-form>
							</validation-observer>
						</v-card-text>
					</v-card>
				</v-col>
			</v-row>
		</v-container>
	</v-app>
</template>

<script>
import AvatarDialog from "../../components/AvatarDialog";
export default {
	name: 'Profile',
	components: {
		AvatarDialog
	},
	data() {
		return {
			loadingInfo: false,
			loadingPassword: false,
			activeTab: 'activity',
			user: {},
			password : {},
			avatarsDialog: false,
			showSavePhotoButton: false,
		}
	},
	created() {
		this.getUser()
	},
	methods: {
		getUser() {
			this.user = this.$store.getters.user
		},
		updateInfo() {
			this.loadingInfo = true
			this.$store.dispatch('user/updateInfo', this.user)
				.then(() => {
					this.loadingInfo = false;
					this.showSavePhotoButton = false
				})
				.catch((err) => {
					if(err.response.status === 400) {
						this.$refs.infoForm.setErrors(err.response.data.data)
					}
					this.loadingInfo = false
				})
			
		},
		updatePassword() {
			this.loadingPassword = true;
			this.$store.dispatch('user/updatePassword', this.password)
				.then(() => {
					this.loadingPassword = false
					this.password = {}
					this.$refs.passwordForm.reset()
				})
				.catch((err) => {
					if(err.response.status === 400) {
						this.$refs.passwordForm.setErrors(err.response.data.data)
					}
					this.loadingPassword = false
				})
		},
		handleAvatar(payload) {
			this.user.avatar.id = payload.id
			this.user.avatar.image_path = payload.image_path
			this.showSavePhotoButton = true
		},
		handleDialog(payload) {
			this.avatarsDialog = payload
		}
	}
}
</script>
<style>
.save-profile-image {
	position: absolute;
	top:10px;
	right: 10px;
}
</style>