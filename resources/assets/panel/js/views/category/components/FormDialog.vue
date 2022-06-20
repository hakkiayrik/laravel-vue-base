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
								<v-tab v-for="lang in languages"
                                       :href="`#tab-${lang.lang_code}`"
                                       :key="lang.lang_code"
                                       outlined="true" class="outline-tab"
                                       :class="$vuetify.theme.dark ? 'tab-dark' : 'tab-light'">
									{{ lang.name }} <small class="ml-1 red--text" v-if="defaultLangCode === lang.lang_code">({{ $t("global.is_must")  }})</small>
								</v-tab>
							</v-tabs>
							<v-tabs-items v-model="tab" fixed-tabs class="mb-5">
								<v-tab-item v-for="lang in languages" :value="`tab-${lang.lang_code}`" :key="lang.lang_code" class="outline-tab-item" reverse-transition>
									<v-card  outlined class="pa-3 rounded-0 border-top-0">
										<validation-provider  name="name" immediate v-slot="{ errors, valid }" :rules="(defaultLangCode == lang.lang_code ? 'required': '') + '|max:25'">
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
                                                @input="category[lang.lang_code].slug = convertToSlug(category[lang.lang_code].name)"
											></v-text-field>
										</validation-provider>
										<validation-provider name="slug" immediate v-slot="{ errors, valid }" :rules="(defaultLangCode == lang.lang_code ? 'required': '') + '|max:50'">
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
						</v-card-text>
						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn color="error darken-1" text @click="dialog.value = false">{{ $t("buttons.cancel") }}</v-btn>
							<v-btn color="primary darken-1" text @click="passes(formSubmit)" :disabled="invalid || !validated" :loading="saveLoading">
                                {{ $t("buttons.save") }}
                            </v-btn>
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
import { getLanguages } from "../../../api/language";

export default {
	name: "FromDialog",
	props: ["selectedCategory"],
	mixins: [helperMixin],
	data() {
		return {
			saveLoading: false,
			defaultLangCode : process.env.MIX_DEFAULT_LANGUAGE,
			languages: [],
			tab: 'tab-' + process.env.MIX_DEFAULT_LANGUAGE,
			category: {
				id: "",
				order_by: "0",
                created_at: "",
                translations: []
			},
			dialog: true
		}
	},
    mounted() {
	    if(this.selectedCategory) {
            this.getCategory(this.selectedCategory)
        } else {
	        this.getLanguages()
        }
    },
	watch: {
		dialog() {
			this.category = {
				id: "",
				order_by: "0",
                created_at: ""
			}
			this.$emit("dialog", this.dialog);
		},
	},
	methods: {
        getLanguages() {
            this.loading = true
            getLanguages({page: 1, itemsPerPage: 250, active: true}).then(response => {
                this.loading = false
                this.languages = response.data.data
                this.loadLanguages()
            }).catch(err => {this.loading = false})
        },
		getCategory(id) {
			this.loading = true
			getCategory(id).then(response => {
				this.loading = false
				this.category = response.data
                this.getLanguages()
                //this.initTranslations()
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
		    let _self = this
			this.languages.forEach( (item, value) => {
				if(typeof _self.category[item.lang_code] == 'undefined') {
                    _self.category[item.lang_code] = {}
				}
			})
		},
        initTranslations() {
            let _self = this
            let translations = this.category.translations
            delete this.category.translations
            translations.forEach(function (item) {
                let lang = item.locale;
                delete item.locale;
                _self.category[lang] = item;
            })
        }
	}
}
</script>

<style scoped>

</style>
