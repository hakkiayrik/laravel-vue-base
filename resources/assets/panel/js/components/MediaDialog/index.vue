<template>
	<v-dialog v-model="dialog" persistent max-width="100%" width="1000px">
		<v-card>
			<v-card-title>
				<span class="headline">{{ $t("global.add_media") }}</span>
			</v-card-title>
			<v-card-text>
				<v-tabs v-model="selectedTab" show-arrows center-active hide-slider class="outline-tabs-wrapper" background-color="transparent" active-class="active-tab" height="30">
					<v-tab outlined="true" class="outline-tab" href="#tab-1" :class=" $vuetify.theme.dark ? 'tab-dark' : 'tab-light'" :ripple="false">
						{{ this.$i18n.t("pages.media.media_list") }}
					</v-tab>
					<v-tab outlined="true" class="outline-tab" href="#tab-2" :class=" $vuetify.theme.dark ? 'tab-dark' : 'tab-light'" :ripple="false">
						{{ this.$i18n.t("pages.media.upload_media") }}
					</v-tab>
				</v-tabs>

				<v-tabs-items v-model="selectedTab" fixed-tabs>
					<v-tab-item class="outline-tab-item" reverse-transition value="tab-1">
						<v-card outlined class="pa-3 rounded-0 border-top-0">
							<v-container fluid class="px-0">
                                <media-list :is-selectable="true" :select-count="1" :is-deletable="false" @selected-media="handleMedia"></media-list>
							</v-container>
						</v-card>
					</v-tab-item>

					<v-tab-item class="outline-tab-item" reverse-transition value="tab-2">
						<v-card outlined class="pa-3 rounded-0 border-top-0">
							<v-container fluid class="px-0">
                                <upload-file :max-upload="5"/>
							</v-container>
						</v-card>
					</v-tab-item>
				</v-tabs-items>
			</v-card-text>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn color="error" @click="dialog = false" text>{{ $t("buttons.cancel") }}</v-btn>
				<v-btn color="primary" text  @click="saveMedia">{{ $t("buttons.save") }}</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import MediaList from '../../components/MediaList'
import UploadFile from '../../components/UploadFile'

export default {
	name: "index",
    components: {
        UploadFile,
        MediaList
    },
	data() {
		return {
			selectedTab: "",
			dialog: true,
		    selectedMedia: [],
			rules: [
				value => !value || value.size < 2000000 || this.$i18n.t("messages.errors.max_image_error"),
			],
			tabs: [
				{id: 1, title: this.$i18n.t("global.video")},
				{id: 2, title: this.$i18n.t("global.image")}
			]
		}
	},
	watch: {
		dialog() {
			this.$emit("dialog", this.dialog);
		}
	},
	methods: {
		saveMedia() {
			this.dialog = false
			this.$emit("selected-media", this.selectedMedia)
		},
		handleMedia(payload) {
		    this.selectedMedia = payload
        }
	}
}
</script>

<style scoped>

</style>
