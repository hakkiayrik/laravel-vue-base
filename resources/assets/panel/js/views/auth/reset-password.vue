<template>
	<v-app>
		<v-main>
			<v-container class="fill-height">
				<v-row>
					<v-col sm="12" md="4" lg="4"></v-col>
					<v-col sm="12" md="4" lg="4">
						<h3 class="mb-3">{{$t("pages.resetPassword.title")}}</h3>
						<validation-observer ref="form" v-slot="{ invalid, validated, passes, validate }" v-if="!success && !error">
							<v-form @keyup.native.enter="passes(resetPassword)">
								<validation-provider v-slot="{ errors, valid }" name="password" rules="required|min:6">
									<v-text-field
										type="password"
										v-model="resetPasswordForm.password"
										:error-messages="errors[0]"
										:success="valid"
										prepend-inner-icon="lock"
										:label="$t('fields.password')"
										outlined
										required
									></v-text-field>
								</validation-provider>
								<validation-provider v-slot="{ errors, valid }" name="password_confirm" rules="required|min:6|confirmed:password">
									<v-text-field
										type="password"
										v-model="resetPasswordForm.password_confirmation"
										:error-messages="errors[0]"
										:success="valid"
										prepend-inner-icon="lock"
										:label="$t('fields.password_confirm')"
										outlined
										required
									></v-text-field>
								</validation-provider>
								<ul class="text--secondary caption mb-5">
									<li>{{$t("pages.profile.password_rule_1")}}</li>
									<li>{{$t("pages.profile.password_rule_2")}}</li>
									<li>{{$t("pages.profile.password_rule_3")}}</li>
								</ul>
								<v-row>
									<v-col cols="12" class="d-flex justify-space-between">
										<v-btn class="mr-4" color="primary" :disabled="invalid || !validated" :loading="loading" @click="passes(resetPassword)">
											{{ $t('buttons.submit') }}
										</v-btn>
									</v-col>
								</v-row>
							</v-form>
						</validation-observer>
						<v-row>
							<v-col cols="12" v-if="success">
								<v-alert icon="check_circle" type="success">
									{{ $t('messages.success.reset_password') }}
								</v-alert>
								<v-btn :to="{name: 'login'}" color="primary" class="float-right">
									<v-icon left>login</v-icon>
									{{ $t('buttons.login') }}
								</v-btn>
							</v-col>
						</v-row>
						
						<v-alert icon="check_circle" type="danger" v-if="error">
							{{ $t('messages.error.reset_password') }}
						</v-alert>
						
					</v-col>
					<v-col sm="12" md="4" lg="4"></v-col>
				</v-row>
			</v-container>
		</v-main>
	</v-app>
</template>
<script>
import {findToken, resetPassword} from "../../api/auth";

export default {
	name: "reset-password",
	data() {
		return {
			loading: false,
			success: false,
			error: false,
			resetPasswordForm: {
				email: "",
				token: "",
				password: "",
				password_confirmation: ""
			}
		}
	},
	created() {
		return new Promise((resolve, reject) => {
			findToken(this.$route.params.token).then(response => {
				this.loading = false
				this.resetPasswordForm.email = response.data.email
				this.resetPasswordForm.token = response.data.token
				resolve()
			}).catch((err => {
				this.loading = false
				this.error = true
				reject()
			}))
		})
	},
	methods: {
		resetPassword() {
			this.loading = true
			return new Promise((resolve, reject) => {
				resetPassword(this.resetPasswordForm). then(response => {
					this.loading = false
					this.success = true
					resolve()
				}).catch(err => {
					this.loading = false
					this.success = false
					if (err.response.status === 400) {
						this.$refs.form.setErrors(err.response.data.data)
					}
					reject()
				})
			})
		}
	}
}
</script>

<style scoped>

</style>