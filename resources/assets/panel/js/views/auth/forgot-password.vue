<template>
	<v-app>
		<v-main>
			<v-container class="fill-height">
				<v-row>
					<v-col sm="12" md="4" lg="4"></v-col>
					<v-col sm="12" md="4" lg="4">
						<h3 class="mb-3">{{$t("pages.forgotPassword.title")}}</h3>
						<validation-observer ref="form" v-slot="{ invalid, validated, passes, validate }" v-if="!success">
							<v-form @keyup.native.enter="passes(forgotPassword)">
								<validation-provider v-slot="{ errors, valid }" name="email" rules="required|email">
									<v-text-field
										v-model="forgotPasswordForm.email"
										:error-messages="errors[0]"
										:success="valid"
										:label="$t('fields.email')"
										outlined
										required
									></v-text-field>
								</validation-provider>
								<v-row>
									<v-col cols="12" class="d-flex justify-space-between">
										<v-btn class="mr-4" color="primary" :disabled="invalid || !validated" :loading="loading" @click="passes(forgotPassword)">
											{{ $t('buttons.submit') }}
										</v-btn>
										<v-btn :to="{name: 'login'}" color="primary">
											<v-icon left>login</v-icon>
											{{ $t('buttons.login') }}
										</v-btn>
									</v-col>
								</v-row>
							</v-form>
						</validation-observer>
						
						<v-alert icon="check_circle" type="success" v-else>
							{{$t('messages.success.forgot_password')}}
							
							<v-btn :to="{name: 'login'}" color="white" outlined>
								{{ $t('buttons.login') }}
							</v-btn>
						</v-alert>
					</v-col>
					<v-col sm="12" md="4" lg="4"></v-col>
				</v-row>
			</v-container>
		</v-main>
	</v-app>
</template>

<script>
import {forgotPassword} from "../../api/auth";

export default {
	name: "forgot-password",
	data() {
		return {
			loading: false,
			forgotPasswordForm: {
				email: ""
			},
			success: false
		}
	},
	methods : {
		async forgotPassword(){
			this.loading = true
			return new Promise((resolve, reject) => {
				forgotPassword(this.forgotPasswordForm).then(() => {
					this.success = true
					this.loading = false
					resolve();
				}).catch(err => {
					this.loading = false
					if(err.response.status === 400) {
						this.$refs.form.error.setErrors(err.response.data.data)
					}
					
					reject();
				})
			})
		}
		
	}
}
</script>

<style scoped>

</style>