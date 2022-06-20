<template>
	<v-app>
        <v-dialog v-model="previewDialog" fullscreen hide-overlay transition="dialog-bottom-transition" v-if="post[tab.split('-')[1]]">
            <v-card>
                <v-toolbar dark color="primary" tile>
                    <v-btn icon dark @click="previewDialog = false">
                        <v-icon>clear</v-icon>
                    </v-btn>
                    <v-toolbar-title>{{ $t('buttons.preview') }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <h2 class="text-center">{{ post[tab.split("-")[1]].title}}</h2>
                <v-card-text v-html="post[tab.split('-')[1]].content"></v-card-text>
            </v-card>
        </v-dialog>
		<v-container fluid>
			<media-dialog v-if="mediaDialog" @dialog="handleMediaDialog" @selected-media="handleMedia"/>
			<validation-observer ref="form" v-slot="{ invalid, validated, passes, validate }">
				<v-form>
					<v-row>
						<v-col sm="12" md="7" lg="8" xl="10">
							<v-tabs v-model="tab" show-arrows center-active hide-slider class="outline-tabs-wrapper" background-color="transparent" active-class="active-tab" height="30" right>
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
                                            <v-btn small tile elevation="0" color="blue-grey" left class="text-capitalize white--text" @click="openModal">
                                                <v-icon small class="mr-1">add_photo_alternate</v-icon>
                                                {{ $t("buttons.add_media") }}
                                            </v-btn>
											<vue-editor
                                                ref="editor"
                                                v-model="post[lang.lang_code].content"
                                                :editor-toolbar="customToolbar"
                                                :editor-options="editorSettings"
                                                :custom-modules="customModulesForEditor"
                                                @focus="onFocus"/>
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
											<validation-provider name="categories" rules="required" immediate v-slot="{ errors, valid }">
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
										<v-col>
											<v-btn depressed color="primary"  :disabled="invalid || !validated" :loading="saveLoading" @click="formSubmit" right>
												{{ $t("buttons.save") }}
											</v-btn>
										</v-col>
                                        <v-col class="text-right" v-if="post[tab.split('-')[1]]">
                                            <v-btn depressed link text color="primary"  :disabled="invalid || !validated" :loading="saveLoading" @click="previewDialog = true" right>
                                                {{ $t("buttons.preview") }}
                                            </v-btn>
                                        </v-col>
									</v-row>
								</v-card-text>
							</v-card>

                            <v-card outlined class="rounded-0 mt-5" v-if="post.author.id">
                                <v-card-title class="subtitle-2 py-2">{{ $t("fields.author") }}</v-card-title>
                                <v-divider></v-divider>
                                <v-card-text>
                                    <v-list-item class="grow">
                                        <v-list-item-avatar color="grey darken-3">
                                            <v-img class="elevation-6" alt="" :src="post.author.avatar.image_path"
                                            ></v-img>
                                        </v-list-item-avatar>

                                        <v-list-item-content>
                                            <v-list-item-title> {{ post.author.first_name + " " + post.author.last_name }} </v-list-item-title>
                                            <v-list-item-subtitle>{{ post.author.username }}</v-list-item-subtitle>
                                        </v-list-item-content>
                                        <v-list-item-action>
                                            <v-list-item-action-text v-text="post.created_at_string"></v-list-item-action-text>
                                        </v-list-item-action>
                                    </v-list-item>
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
import Quill from 'quill'
import { VueEditor } from "vue2-editor"
import ImageResize from 'quill-image-resize-module'
import MediaDialog from '../../../components/MediaDialog'
import HelperMixin from '../../../mixins/helper'
import { getCategories } from '../../../api/category'
import { getPost, updatePost, createPost } from "../../../api/post"
import {getLanguages} from "../../../api/language"
import {uploadMediaWithProgress} from "../../../api/media"

Quill.register('modules/imageResize', ImageResize)

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
	mounted() {
		if(this.isEdit) {
			const id= this.$route.params && this.$route.params.id
			this.getPost(id)
		} else {
			this.getLanguages()
            this.getCategories()
		}
	},
	data() {
		return {
            saveLoading: false,
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
			languages: [],
			tab: 'tab-' + process.env.MIX_DEFAULT_LANGUAGE,
			post: {
				image: "",
                author: {},
				video_url: "",
				categories: [],
				type: "article",
				status: 1,
				visibility: "public",
				published_date: new Date().toISOString().substr(0, 10),
                content: '',
			},
			mediaDialog: false,
            previewDialog: false,
            formData: new FormData(),
            customToolbar: [
                [{ 'font': [] }],
                [{ 'header': [false, 1, 2, 3, 4, 5, 6, ] }],
                [{ 'size': ['small', false, 'large', 'huge'] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{'align': ''}, {'align': 'center'}, {'align': 'right'}, {'align': 'justify'}],
                [{ 'header': 1 }, { 'header': 2 }],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'color': [] }, { 'background': [] }],
                ['link', 'video', 'formula'],
                [{ 'direction': 'rtl' }],
                ['clean'],
            ],
            editorSettings: {
                modules: {
                    imageResize: {}
                }
            },
            customModulesForEditor: [
                { alias: "imageResize", module: ImageResize }
            ],
            quill: null
		}
	},
	methods: {
	    getPost(id) {
            getPost(id).then(response => {
                this.loading = false
                this.post = response.data;
                this.getLanguages()
                this.getCategories()
            }).catch(err => { this.loading = false })
        },
        getCategories() {
            let query = {page: 1, itemsPerPage: 250}
            getCategories(query).then(response => {
                this.loading = false
                this.categories = response.data.data;

            }).catch(err => { this.loading = false });
        },
        getLanguages() {
            this.loading = true
            getLanguages({page: 1, itemsPerPage: 250, active: true}).then(response => {
                this.loading = false
                this.languages = response.data.data
                this.loadLanguages()
            }).catch(err => {this.loading = false})
        },
		formSubmit(){
			this.loading = true
            let postData = this.post;
			delete postData.author

            this.buildFormData(this.formData, postData)

            if (this.isEdit) {
                updatePost(this.post.id, this.formData).then(response => {
                    this.$router.push({name: 'post.index'})
                }).catch(err => {
                    this.loading = false
                    if(err.response.status === 400) {
                        this.$refs.form.setErrors(err.response.data.data)
                    }
                })
            } else {
                createPost(this.formData).then(response => {
                    this.$router.push({name: 'post.index'})
                }).catch(err => {
                    this.loading = false

                    if(err.response.status === 400) {
                        this.$refs.form.setErrors(err.response.data.data)
                    }
                })
            }
		},
		loadLanguages() {
            let _self = this
            this.languages.forEach( (item, value) => {
                if(typeof _self.post[item.lang_code] == 'undefined') {
                    _self.post[item.lang_code] = {}
                }
            })
		},
        handleImageAdded: function(file, Editor, cursorLocation, resetUploader) {
            let formData = new FormData();
            formData.append("files", file);

            uploadMediaWithProgress({
                url: `media`,
                method: 'post',
                data: formData
            }).then(response => {
                const url = response.data[0].url; // Get url from response
                Editor.insertEmbed(cursorLocation, "image", url);
                resetUploader();
            }).catch(err=>{ this.loading = false})
        },
		handleMedia(payload) {
			payload.forEach(async (element) => {
                this.focusEditor()
                const Editor = this.quill
                const range = Editor.getSelection()
                const cursorLocation = await range.index
                await Editor.editor.insertEmbed(cursorLocation, 'image', element)
            })
		},
		handleMediaDialog(payload) {
			this.mediaDialog = payload
		},
        onFocus(quill) {
            this.quill = quill
        },
        openModal() {
	        this.mediaDialog = true
        },
        focusEditor() {
            this.$refs.editor[0].quill.focus()
        },
	}
}
</script>

<style>

</style>
