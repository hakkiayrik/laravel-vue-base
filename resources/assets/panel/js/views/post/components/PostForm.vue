<template>
	<v-app>
		<v-container fluid>
			<media-dialog v-if="mediaDialog" @dialog="handleMediaDialog" @media="handleMedia" />
			<validation-observer ref="form" v-slot="{ invalid, validated, passes, validate }">
				<v-form @keyup.native.enter="passes(formSubmit)">
					<v-row>
						<v-col sm="12" md="7" lg="8" xl="10">
							<v-tabs v-model="tab" show-arrows center-active hide-slider class="outline-tabs-wrapper" background-color="transparent" active-class="active-tab" height="30" right>
								<v-btn small tile elevation="0" color="blue-grey" left class="text-capitalize white--text" @click="mediaDialog = true">
									<v-icon small class="mr-1">add_photo_alternate</v-icon>
									{{ $t("buttons.add_media") }}
								</v-btn>
								<v-spacer></v-spacer>
								<v-tab v-for="lang in languages" :href="`#tab-${lang.lang_code}`" :key="lang.lang_code" outlined="true" class="outline-tab" :class=" $vuetify.theme.dark ? 'tab-dark' : 'tab-light'" :ripple="false">
									{{ lang.name }} <small class="ml-1 red--text" v-if="defaultLangCode === lang.lang_code">({{ $t("global.is_must")  }})</small>
								</v-tab>
							</v-tabs>
							
							<v-tabs-items v-model="tab" fixed-tabs>
								<v-tab-item v-for="lang in languages" :value="`tab-${lang.lang_code}`" :key="lang.lang_code" class="outline-tab-item" reverse-transition>
									<v-card outlined class="pa-3 rounded-0 border-top-0">
										<v-container fluid class="px-0">
											<validation-provider name="title" :rules="(defaultLangCode === lang.lang_code ? 'required': '') + '|max:150'" immediate v-slot="{ errors, valid }">
												<v-text-field
													outlined
													v-model="post[lang.lang_code].title"
													:label="$t('fields.title')"
													:ref="'title-' + lang.lang_code"
													@input="post[lang.lang_code].slug = convertToSlug(post[lang.lang_code].title)"
													prepend-inner-icon="title"
													:success="valid"
													:error-messages="errors[0]"
													required
													dense
												></v-text-field>
											</validation-provider>
											<validation-provider name="slug" :rules="(defaultLangCode === lang.lang_code ? 'required': '') + '|max:180'" immediate v-slot="{ errors, valid }" style="max-width: 300px">
												<v-text-field
													outlined
													v-model="post[lang.lang_code].slug"
													:label="$t('fields.slug')"
													:ref="'slug-' + lang.lang_code"
													prepend-inner-icon="link"
													:success="valid"
													:error-messages="errors[0]"
													required
													dense
												></v-text-field>
											</validation-provider>
											<validation-provider name="meta_keywords" :rules="(defaultLangCode === lang.lang_code ? 'required': '') + '|max:150'" immediate v-slot="{ errors, valid }">
												<v-text-field
													outlined
													v-model="post[lang.lang_code].meta_keywords"
													:label="$t('fields.meta_keywords')"
													:ref="'meta_keywords-' + lang.lang_code"
													prepend-inner-icon="settings_ethernet"
													:success="valid"
													:error-messages="errors[0]"
													required
													dense
												></v-text-field>
											</validation-provider>
											<validation-provider name="meta_description" :rules="(defaultLangCode === lang.lang_code ? 'required': '') + '|max:150'" immediate v-slot="{ errors, valid }">
												<v-textarea
													outlined
													v-model="post[lang.lang_code].meta_description"
													:label="$t('fields.meta_description')"
													:ref="'meta_description-' + lang.lang_code"
													prepend-inner-icon="settings_ethernet"
													:success="valid"
													:error-messages="errors[0]"
													required
													dense
												></v-textarea>
											</validation-provider>
											<vue-editor v-model="post[lang.lang_code].content" />
										</v-container>
									</v-card>
								</v-tab-item>
							</v-tabs-items>
						</v-col>
						<v-col sm="12" md="5" lg="4" xl="2">
							<v-card outlined class="rounded-0">
								<v-card-title class="subtitle-2 py-2">{{ $t("fields.category") }}</v-card-title>
								<v-divider></v-divider>
								<v-card-text>
									<v-row>
										<v-col md="12" class="py-sm-1">
											<v-subheader class="pa-0 fill-height">{{ $t("fields.select_category") }}</v-subheader>
										</v-col>
										<v-col md="12" class="py-sm-0">
											<validation-provider name="category" rules="required" immediate v-slot="{ errors, valid }">
												<v-select v-model="post.categories" :items="categories" item-text="name" item-value="id" :label="$t('fields.category')" :success="valid" :error-messages="errors[0]" required dense multiple outlined>
													<template v-slot:selection="{ item, index }">
														<v-chip
															:key="JSON.stringify(item)"
															v-bind="item.attrs"
															:input-value="item.selected"
															:disabled="item.disabled"
															@click:close="item.parent.selectItem(item)"
															label
															small>
															{{ item.name }}
														</v-chip>
													</template>
												</v-select>
											</validation-provider>
										</v-col>
									</v-row>
								</v-card-text>
							</v-card>
							
							<v-card outlined class="rounded-0 mt-5">
								<v-card-title class="subtitle-2 py-2">{{ $t("global.general") }}</v-card-title>
								<v-divider></v-divider>
								<v-card-text>
									<v-row class="my-0 mb-2">
										<v-col xs="12" sm="4" class="py-sm-1">
											<v-subheader class="pa-0 fill-height">
												{{ $t("global.status") }}:
							 				</v-subheader>
										</v-col>
										<v-col xs="12" sm="8" class="py-sm-0">
											<v-select
												v-model="post.status"
												:items="statusItems"
												item-text="text"
												item-value="value"
												class="subtitle-1"
												hide-details="true"
												:append-outer-icon="post.status ? 'check_circle' : 'unpublished'"
												dense
												solo-inverted
												single-line
											></v-select>
										</v-col>
									</v-row>
									
									<v-row class="my-0 mb-2">
										<v-col xs="12" sm="4" class="py-sm-1">
											<v-subheader class="pa-0 fill-height">
												{{ $t("global.type") }}:
											</v-subheader>
										</v-col>
										<v-col xs="12" sm="8" class="py-sm-0">
											<v-select
												v-model="post.type"
												:items="typeItems"
												item-text="text"
												item-value="value"
												class="subtitle-1"
												hide-details="true"
												:append-outer-icon="post.type === 'article' ? 'article' : 'smart_display'"
												dense
												solo-inverted
												single-line
											></v-select>
										</v-col>
									</v-row>
									
									<v-row class="my-0 mb-2">
										<v-col xs="12" sm="4" class="py-sm-1">
											<v-subheader class="pa-0 fill-height">
												{{ $t("global.visibility") }}:
											</v-subheader>
										</v-col>
										<v-col xs="12" sm="8" class="py-sm-0">
											<v-select
												v-model="post.visibility"
												:items="visibilityItems"
												item-text="text"
												item-value="value"
												class="subtitle-1"
												hide-details="true"
												:append-outer-icon="post.visibility === 'public' ? 'public' : 'lock'"
												dense
												solo-inverted
												single-line
											></v-select>
										</v-col>
									</v-row>
									<v-row class="my-0">
										<v-col xs="12" sm="4" class="py-sm-1">
											<v-subheader class="pa-0 fill-height">
												{{ $t("global.published_date") }}:
											</v-subheader>
										</v-col>
										<v-col xs="12" sm="8" class="py-sm-0">
											<v-menu
												ref="menu"
												v-model="publishedDatePicker"
												:close-on-content-click="false"
												transition="scale-transition"
												:nudge-right="40"
												offset-y>
												<template v-slot:activator="{ on, attrs }">
													<v-text-field
														v-model="post.published_date"
														class="subtitle-1"
														hide-details="true"
														append-outer-icon="event"
														v-bind="attrs"
														v-on="on"
														dense
														solo-inverted
														single-line
													></v-text-field>
												</template>
												<v-date-picker
													v-model="post.published_date"
													:locale="$store.getters.language"
													@input="publishedDatePicker = false"
													no-title
													scrollable
												></v-date-picker>
											</v-menu>
										</v-col>
									</v-row>
									<v-row>
										<v-col cols="12">
											<v-btn depressed color="primary"  :disabled="invalid || !validated" :loading="loading" @click="formSubmit" right>
												{{ $t("buttons.save") }}
											</v-btn>
										</v-col>
									</v-row>
								</v-card-text>
							</v-card>
						</v-col>
					</v-row>
				</v-form>
			</validation-observer>
		</v-container>
	</v-app>
</template>

<script>
import MediaDialog from '../../../components/MediaDialog'
import { getCategories } from '../../../api/category'
import { getPost, updatePost, createPost } from "../../../api/post"
import { VueEditor } from "vue2-editor"
import HelperMixin from '../../../mixins/helper'

export default {
	name: "PostForm",
	mixins: [HelperMixin],
	props: {
		isEdit: {
			type: Boolean,
			default: false
		}
	},
	components: { VueEditor, MediaDialog },
	created() {
		if(this.isEdit) {
			const id= this.$route.params && this.$route.params.id
			this.getPost(id)
		} else {
			this.loadLanguages()
		}
		this.getCategories()
	},
	data() {
		return {
			projectUrl: process.env.APP_URL,
			defaultLangCode : process.env.MIX_DEFAULT_LANGUAGE,
			publishedDatePicker: false,
			typeItems: [
				{text: this.$i18n.t("pages.posts.types.article"), value: 'article'},
				{text: this.$i18n.t("pages.posts.types.video"), value: 'video'}
			],
			statusItems: [
				{text: this.$i18n.t("global.not_published"), value: 0},
				{text: this.$i18n.t("global.published"), value: 1}
			],
			visibilityItems: [
				{ text: this.$i18n.t("global.public"), value: "public"},
				{ text: this.$i18n.t("global.private"), value: "private"}
			],
			categories: [],
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
			post: {
				image: "",
				video_url: "",
				categories: [],
				type: "article",
				status: 1,
				visibility: "public",
				published_date: new Date().toISOString().substr(0, 10),
			},
			mediaDialog: false
		}
	},
	methods: {
		formSubmit(){
			this.loading = true
			let formData = new FormData()
			
			this.buildFormData(formData, this.post)
			
			createPost(formData).then(response => {
				this.$router.push({name: 'post.index'})
			}).catch(err => {
				this.loading = false
				
				if(err.response.status === 400) {
					this.$refs.form.setErrors(err.response.data.data)
				}
			})
		},
		getCategories() {
			this.loading = false
			let query = {page: 1, itemsPerPage: 250}
			getCategories(query).then(response => {
				this.loading = false
				this.categories = response.data.data;
				
			}).catch(err => { this.loading = false });
		},
		loadLanguages() {
			this.languages.forEach( (item, value) => {
				if(this.post[item.lang_code] === undefined) {
					this.post[item.lang_code] = {}
				}
			})
		},
		handleMedia(payload) {
			this.post.image = payload.image;
			this.post.video_url = payload.video_url;
			
			console.log(this.post);
		},
		handleMediaDialog(payload) {
			this.mediaDialog = payload
		}
	}
}
</script>

<style>

</style>