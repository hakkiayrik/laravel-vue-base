<template>
	<v-dialog v-model="dialog" max-width="600px">
		<template v-slot:default="dialog">
			<v-card>
				<validation-observer ref="form" v-slot="{ invalid, validated, passes, validate }">
					<v-form @keyup.native.enter="passes(formSubmit)">
						<v-card-title>
							<span class="headline" v-if="selectedCategory">{{ $t("pages.categories.edit") }}</span>
							<span class="headline" v-if="!selectedCategory">{{ $t("pages.categories.create") }}</span>
						</v-card-title>
						<v-card-text>
							<v-tabs v-model="tab" show-arrows center-active hide-slider class="outline-tabs-wrapper" background-color="transparent" active-class="active-tab" height="30" right>
								<v-spacer></v-spacer>
								<v-tab v-for="lang in languages" :href="`#tab-${lang.lang_code}`" :key="lang.lang_code" outlined="true" class="outline-tab" :class=" $vuetify.theme.dark ? 'tab-dark' : 'tab-light'" :ripple="false">
									{{ lang.name }} <small class="ml-1 red--text" v-if="defaultLangCode === lang.lang_code">({{ $t("global.is_must")  }})</small>
								</v-tab>
							</v-tabs>
							<v-tabs-items v-model="tab" fixed-tabs class="mb-5">
								<v-tab-item v-for="lang in languages" :value="`tab-${lang.lang_code}`" :key="lang.lang_code" class="outline-tab-item" reverse-transition>
									<v-card  outlined class="pa-3 rounded-0 border-top-0">
										<validation-provider  name="name" v-slot="{ errors, valid }" :rules="(defaultLangCode === lang.lang_code ? 'required': '') + '|max:25'">
											<v-text-field
												v-model="category[lang.lang_code].name"
												:label="$t('fields.name')"
												:ref="'name-' + lang.lang_code"
												prepend-inner-icon="title"
												:error-messages="errors[0]"
												:success="valid"
												dense
												outlined
												required
											></v-text-field>
										</validation-provider>
										<validation-provider name="slug" v-slot="{ errors, valid }" :rules="(defaultLangCode === lang.lang_code ? 'required': '') + '|max:50'">
											<v-text-field
												v-model="category[lang.lang_code].slug"
												:label="$t('fields.slug')"
												:ref="'slug-' + lang.lang_code"
												prepend-inner-icon="link"
												:error-messages="errors[0]"
												:success="valid"
												dense
												outlined
												required
											></v-text-field>
										</validation-provider>
										<v-textarea
											v-model="category[lang.lang_code].description"
											:label="$t('fields.description')"
											:ref="'description-' + lang.lang_code"
											prepend-inner-icon="notes"
											dense
											outlined
										></v-textarea>
									</v-card>
									
								</v-tab-item>
							</v-tabs-items>
							
							<validation-provider name="order_by" v-slot="{ errors, valid }" rules="numeric">
								<v-text-field
									v-model="category.order_by"
									:label="$t('fields.order_by')"
									prepend-inner-icon="import_export"
									:error-messages="errors[0]"
									:success="valid"
									dense
									outlined
									required
								></v-text-field>
							</validation-provider>
							
						</v-card-text>
						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn color="error darken-1" text @click="dialog.value = false">{{ $t("buttons.cancel") }}</v-btn>
							<v-btn color="primary darken-1" text @click="formSubmit" :disabled="invalid || !validated" :loading="loading">{{ $t("buttons.save") }}</v-btn>
						</v-card-actions>
					</v-form>
				</validation-observer>
			</v-card>
		</template>
	</v-dialog>
</template>

<script>
import helperMixin from '../../../mixins/helper'
import { updateCategory, createCategory, getCategory } from "../../../api/category";
export default {
	name: "FromDialog",
	props: ["selectedCategory"],
	mixins: [helperMixin],
	data() {
		return {
			loading: false,
			defaultLangCode : process.env.MIX_DEFAULT_LANGUAGE,
			languages: [
				{
					id: 1,
					lang_code: "en",
					name: "English",
					is_rtl: false,
					active: true,
				},
				{
					id: 1,
					lang_code: "tr",
					name: "Türkçe",
					is_rtl: false,
					active: true
				}
			],
			tab: 'tab-' + process.env.MIX_DEFAULT_LANGUAGE,
			category: {
				id: "",
				order_by: "0"
			},
			dialog: true
		}
	},
	created() {
		if(this.selectedCategory) {
			this.getCategory(this.selectedCategory)
		}
		this.loadLanguages()
	},
	watch: {
		dialog() {
			this.category = {
				id: "",
				order_by: "0"
			}
			this.$emit("dialog", this.dialog);
		},
		category: {
			handler: function(payload) {
				this.category.slug = this.convertToSlug(payload.name)
			},
			deep: true
		},
	},
	methods: {
		getCategory(id) {
			this.loading = true
			getCategory(id).then(response => {
				this.loading = false
				this.category = response.data
			}).then(async response => {
				await this.$refs.form.validate()
			}).catch(err => {this.loading = false})
		},
		formSubmit() {
			this.loading = true
			if (this.selectedCategory) {
				updateCategory(this.selectedCategory, this.category).then(response => {
					this.loading = false
					this.$emit("refresh", true);
					this.dialog = false
				}).catch(err => {
					this.loading = false
				})
			} else {
				createCategory(this.category).then(response => {
					this.loading = false
					this.$emit("refresh", true);
					this.dialog = false
				}).catch(err => {
					this.loading = false
				})
			}
		},
		loadLanguages() {
			this.languages.forEach( (item, value) => {
				if(this.category[item.lang_code] === undefined) {
					this.category[item.lang_code] = {}
				}
			})
		},
	}
}
</script>

<style scoped>

</style>