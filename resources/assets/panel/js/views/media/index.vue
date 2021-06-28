<template>
	<v-app>
		<v-container fluid>
			<v-card>
				<v-card-title>
					<span class="headline">{{ $t("pages.media.title") }}</span>
				</v-card-title>
				<v-card-text>
					<v-tabs v-model="mediaTab" show-arrows center-active hide-slider class="outline-tabs-wrapper" background-color="transparent" active-class="active-tab" height="30" right>
						<v-spacer></v-spacer>
						<v-tab :href="`#tab-media_list`" key="media_list" outlined="true" class="outline-tab" :class=" $vuetify.theme.dark ? 'tab-dark' : 'tab-light'" :ripple="false">
							{{ $t('pages.media.media_list') }}
						</v-tab>
						<v-tab :href="`#tab-upload_media`" key="upload_media" outlined="true" class="outline-tab" :class=" $vuetify.theme.dark ? 'tab-dark' : 'tab-light'" :ripple="false">
							{{ $t('pages.media.upload_media') }}
						</v-tab>
					</v-tabs>
					<v-tabs-items v-model="mediaTab" fixed-tabs class="mb-5">
						<v-tab-item value="tab-media_list" key="media_list" class="outline-tab-item" reverse-transition>
							<v-card  outlined class="pa-3 rounded-0 border-top-0">
								<v-row>
									<v-col v-for="media in mediaData" :key="media.id" class="d-flex child-flex" md="2" lg="2" xl="1" v-if="mediaData">
										<v-hover>
											<template v-slot:default="{ hover }">
												<v-card elevation="0">
													<v-img :src="media.url" :lazy-src="`https://picsum.photos/10/6?image=${media.id * 5 + 10}`" aspect-ratio="1" class="grey lighten-2">
														<template v-slot:placeholder>
															<v-row class="fill-height ma-0" align="center" justify="center">
																<v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
															</v-row>
														</template>
													</v-img>
													<v-fade-transition>
														<v-overlay v-if="hover" absolute color="secondary">
															<v-btn icon><v-icon>fullscreen</v-icon></v-btn>
															<v-btn icon color="red"><v-icon>delete_outline</v-icon></v-btn>
														</v-overlay>
													</v-fade-transition>
												</v-card>
											</template>
										</v-hover>
									</v-col>
									<v-alert prominent type="error" v-else>
										<v-row align="center">
											<v-col class="grow">
												Nunc nonummy metus. Nunc interdum lacus sit amet orci. Nullam dictum felis eu pede mollis pretium. Cras id dui.
											</v-col>
											<v-col class="shrink">
												<v-btn @click="mediaTab = 'tab-upload_media'">{{ $t('buttons.upload_media') }}</v-btn>
											</v-col>
										</v-row>
									</v-alert>
								</v-row>
							</v-card>
						</v-tab-item>
						
						<v-tab-item value="tab-upload_media" key="upload_media" class="outline-tab-item" reverse-transition>
							<v-card  outlined class="pa-3 rounded-0 border-top-0">
								<upload-file />
							</v-card>
						</v-tab-item>
					</v-tabs-items>
				</v-card-text>
			</v-card>
		</v-container>
	</v-app>
</template>

<script>
import { getMedia } from '../../api/media'
import UploadFile from '../../components/UploadFile'

export default {
	name: "index",
	components: {
		UploadFile,
	},
	data() {
		return {
			tabs: [{ code: 'media_list' }, { code: 'upload_media' }],
			mediaData: [],
			mediaTab: ''
		}
	},
	created() {
		this.initialize()
	},
	methods: {
		initialize() {
			this.loading = true
			getMedia().then(response => {
				this.loading = false
				this.mediaData = response.data.data
			}).catch(err => { this.loading = false })
		},
		async deleteItem(item) {
			this.$confirm({
				message: this.$i18n.t('global.cancel_dialog_title'),
				button: {
					no: this.$i18n.t('buttons.no'),
					yes: this.$i18n.t('buttons.yes')
				},
				callback: confirm => {
				
				}
			})
		},
	},
}
</script>

<style scoped>

</style>