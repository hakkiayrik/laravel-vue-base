<template>
	<v-card
		@drop.prevent="onDrop($event)"
		@dragover.prevent="dragover = true"
		@dragenter.prevent="dragover = true"
		@dragleave.prevent="dragover = false"
		:class="{ 'grey': dragover, 'darken-4' : $vuetify.theme.dark, 'lighten-4' : !$vuetify.theme.dark }"
		elevation="0">
		<v-card-text>
			<v-alert dense outlined type="error" v-for="(message, index) in uploadMessage" :key="index" v-if="uploadMessage">
				{{ message }}
			</v-alert>
			<!-- New Addition -->
			<v-row class="d-flex flex-column mb-5" dense align="center" justify="center" v-if="!uploadLoading">
				<v-icon class="mt-5" size="60">cloud_upload</v-icon>
				<h3 class="bold">{{ $t("pages.media.upload_media_message") }}</h3>
				<v-subheader class="font-italic">{{ $t("pages.media.max_upload_size_message",  { max_upload_size:  Math.round( (maxFileSize / 1024) *100) / 100 + " MB"  } )  }}</v-subheader>
				<div>
					<v-btn color="primary" class="text-capitalize" @click="uploadItems" v-if="uploadAll && uploadFiles.length > 0">
						<v-icon left>file_upload</v-icon>
						{{ $t("buttons.upload_all") }}
					</v-btn>
					<v-btn color="error" class="text-capitalize" @click="removeItems" v-if="removeAll && uploadFiles.length > 0">
						<v-icon left>cancel</v-icon>
						{{ $t("buttons.remove_all") }}
					</v-btn>
				</div>
			</v-row>
			<v-row class="d-flex flex-column mb-5" dense align="center" justify="center" v-if="uploadLoading">
				<v-progress-circular indeterminate rounded width="7" size="50" class="my-10"></v-progress-circular>
				<h3 class="bold">{{ $t("pages.media.uploading_media_message") }}</h3>
			</v-row>
			<v-list v-if="uploadFiles.length > 0" dense>
				<template v-for="(item, index) in uploadFiles">
					<v-divider></v-divider>
					<v-list-item :key="item.name" class="px-0">
						<v-list-item-content>
							<v-list-item-title class="d-flex align-center">
								<v-img max-height="75" max-width="75" :src="item.imagePath" :alt="item.name" class="mr-3"/>
								<span>{{ item.name }}</span>
								<span class="ml-3 text--secondary">{{ item.size }} bytes</span>
							</v-list-item-title>
						</v-list-item-content>
						<v-list-item-action v-if="!uploadFiles[index].imageLoading">
							<v-btn icon color="primary" @click="uploadItem(index)">
								<v-icon>file_upload</v-icon>
							</v-btn>
						</v-list-item-action>
						<v-list-item-action v-if="!uploadFiles[index].imageLoading">
							<v-btn icon color="error" @click="removeItem(index)">
								<v-icon>cancel</v-icon>
							</v-btn>
						</v-list-item-action>
						<v-list-item-action v-if="uploadFiles[index].imageLoading">
							<v-progress-circular :value="80" indeterminate></v-progress-circular>
						</v-list-item-action>
					</v-list-item>
				</template>
			</v-list>
		</v-card-text>
	</v-card>
</template>

<script>
import { uploadMediaWithProgress } from '../../api/media.js'

export default {
	name: "UploadFile",
	props: {
		maxUpload: {
			type: Number,
			default: 2
		},
		maxFileSize: {
			type: Number,
			default: 2048
		},
		uploadAll: {
			type: Boolean,
			default: true
		},
		removeAll: {
			type: Boolean,
			default: true
		}
	},
	data() {
		return {
			loading: false,
			dragover: false,
			uploadFileData: null,
			uploadLoading: false,
			uploadProgress: [],
			uploadFiles: [],
			uploadMessage: []
		};
	},
	methods: {
		onDrop(e) {
			this.dragover = false
			this.uploadMessage = []
			const files = e.dataTransfer.files
			
			if ( files.length > this.maxUpload || (this.uploadFiles.length + files.length > this.maxUpload) ) {
				this.uploadMessage.push(this.$i18n.t('pages.media.max_upload_error_message', { max_upload: this.maxUpload }));
			} else {
				Array.from(files).forEach(element => {
					//Convert byte to kilobyte and control max upload size
					if((element.size / 1024) > this.maxFileSize) {
						this.uploadMessage.push(this.$i18n.t('pages.media.max_file_size_error_message', { file_name: element.name }));
					} else {
						let reader = new FileReader();
						reader.readAsDataURL(element)
						reader.onload = () => {
							let fileData = element
							fileData.imagePath = reader.result
							fileData.imageLoading = false
							this.uploadFiles.push(fileData)
						}
					}
				});
			}
		},
		uploadItem(index) {
			this.loading = true
			this.uploadFiles[index].imageLoading = true;
			console.log(this.uploadFiles);
			this.uploadFile(this.uploadFiles[index])
		},
		removeItem(index) {
			this.uploadFiles.splice(index, 1)
		},
		uploadItems() {
			this.loading = true
			const formData = new FormData()
			
			let uploadData = this.uploadFiles.map((file, index) => {
				this.uploadFile(this.uploadFiles[index])
			})
		},
		removeItems() { 
			this.uploadFiles = []
		},
		uploadFile(file, index) {
			const indexKey = index;
			const formData = new FormData()
			formData.append('files', file);
			
			uploadMediaWithProgress({
				url: `media`,
				method: 'post',
				data: formData,
				onUploadProgress: function( progressEvent ) {
					this.uploadProgress[indexKey] = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
					console.log(index);
				}.bind(this)
			}).then(response => {
				this.loading = false
				this.uploadLoading = false
				this.uploadFileData = null
			}).catch(err=>{ this.loading = false })
		}
	}
}
</script>

<style scoped>
	.loading-progress {
		width: 100px;
	}
</style>