<template>
	<v-app>
		<v-main>
			<v-container class="fill-height">
				<v-row>
					<v-col sm="12" md="4" lg="4"></v-col>
					<v-col sm="12" md="4" lg="4">
						<h3 class="mb-3">{{$t("pages.login.title")}}</h3>
						<validation-observer ref="form" v-slot="{ invalid, validated, passes, validate }">
							<v-alert type="error" v-if="error.status">
								{{ error.message }}
							</v-alert>
							<v-form @keyup.native.enter="passes(handleLogin)">
								<validation-provider v-slot="{ errors, valid }" name="username" rules="required|max:25">
									<v-text-field
										v-model="loginForm.username"
										:counter="25"
										:error-messages="errors[0]"
										:success="valid"
										:label="$t('fields.username')"
										outlined
										required
									></v-text-field>
								</validation-provider>
								<validation-provider v-slot="{ errors,valid }" name="password" rules="required">
									<v-text-field
										v-model="loginForm.password"
										:error-messages="errors[0]"
										:success="valid"
										:label="$t('fields.password')"
										type="password"
										outlined
										required
									></v-text-field>
								</validation-provider>
								<v-row>
									<v-col cols="12" class="d-flex justify-space-between align-center">
										<v-checkbox
											v-model="loginForm.rememberMe"
											value="1"
											:label="$t('fields.remember_me')"
											type="checkbox"
										></v-checkbox>
										<router-link :to="{name: 'forgot-password'}">{{$t('global.forgot_password')}}</router-link>
									</v-col>
								</v-row>
								
								<v-btn class="mr-4" color="primary" :disabled="invalid || !validated" :loading="loading"
								       @click="passes(handleLogin)">
									{{ $t('buttons.login') }}
								</v-btn>
								<v-btn @click="clear">
									{{ $t('buttons.clear') }}
								</v-btn>
							</v-form>
						</validation-observer>
					</v-col>
					<v-col sm="12" md="4" lg="4"></v-col>
				</v-row>
			</v-container>
		</v-main>
	
	</v-app>
</template>

<script>

export default {
	data() {
		return {
			loading: false,
			loginForm: {
				username: '',
				password: '',
				rememberMe: false,
			},
			error: {
				status: false,
				message: ''
			},
			redirect: undefined
		}
	},
	watch: {
		$route: {
			handler: function(route) {
				const query = route.query
				if (query) {
					this.redirect = query.redirect
					this.otherQuery = this.getOtherQuery(query)
				}
			},
			immediate: true
		}
	},
	methods: {
		async handleLogin() {
			this.loading = true
			this.error.status = false
			this.$store.dispatch('user/login', this.loginForm)
				.then(() => {
					this.$router.push({ path: this.redirect || '/', query: this.otherQuery }).catch(err => {})
					this.loading = false
				})
				.catch( (err) => {
					this.loading = false
					console.log(err)
					if (err.response.data.data) {
						this.$refs.form.setErrors(err.response.data.data)
					} else {
						this.error.status = true
						this.error.message = err.response.data.message
					}
				})
		},
		clear() {
			this.loginForm = {
				username: '',
				password: '',
				rememberMe: false,
			}
		},
		getOtherQuery(query) {
			return Object.keys(query).reduce((acc, cur) => {
				if (cur !== 'redirect') {
					acc[cur] = query[cur]
				}
				return acc
			}, {})
		}
	},
}
</script>
