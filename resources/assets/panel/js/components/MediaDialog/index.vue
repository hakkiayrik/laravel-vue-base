<template>
	<v-dialog v-model="dialog" persistent max-width="600px">
		<v-card>
			<v-card-title>
				<span class="headline">{{ $t("global.add_media") }}</span>
			</v-card-title>
			<v-card-text>
				<v-tabs v-model="selectedTab" show-arrows center-active hide-slider class="outline-tabs-wrapper" background-color="transparent" active-class="active-tab" height="30">
					<v-tab outlined="true" class="outline-tab" href="#tab-1" :class=" $vuetify.theme.dark ? 'tab-dark' : 'tab-light'" :ripple="false">
						{{ this.$i18n.t("global.video") }}
					</v-tab>
					<v-tab outlined="true" class="outline-tab" href="#tab-2" :class=" $vuetify.theme.dark ? 'tab-dark' : 'tab-light'" :ripple="false">
						{{ this.$i18n.t("global.image") }}
					</v-tab>
				</v-tabs>
				
				<v-tabs-items v-model="selectedTab" fixed-tabs>
					<v-tab-item class="outline-tab-item" reverse-transition value="tab-1">
						<v-card outlined class="pa-3 rounded-0 border-top-0">
							<v-container fluid class="px-0">
								<v-text-field
									v-model="media.video_url"
									:label="$t('fields.add_youtube_video_url')"
									prepend-icon="link"
									dense
									outlined
								></v-text-field>
							</v-container>
						</v-card>
					</v-tab-item>
					
					<v-tab-item class="outline-tab-item" reverse-transition value="tab-2">
						<v-card outlined class="pa-3 rounded-0 border-top-0">
							<v-container fluid class="px-0">
								<v-file-input
									v-model="media.image"
									:rules="rules"
									accept="image/png, image/jpeg, image/jpg, image/bmp"
									:label="$t('fields.select_image')"
									prepend-icon="image"
									show-size
									truncate-length="50"
									outlined
									dense>
									<template v-slot:selection="{ index, text }">
										<v-chip v-if="index < 2" color="success" dark label small>{{ text }}</v-chip>
										<span v-else-if="index === 2" class="overline grey--text text--darken-3 mx-2"> +{{ files.length - 2 }} File(s)
								      </span>
									</template>
								</v-file-input>
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
export default {
	name: "index",
	data() {
		return {
			selectedTab: "",
			dialog: true,
			media: {
				image: null,
				video_url: null
			},
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
			this.$emit("media", this.media)
		}
	}
}
</script>

<style scoped>

</style>