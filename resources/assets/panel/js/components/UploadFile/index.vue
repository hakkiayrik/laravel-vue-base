<template>
	<v-card elevation="0">
		<v-card-text>
            <drop-zone :maxUpload="maxUpload" :maxFileSize="maxFileSize" @uploadFiles="handleFiles($event)"></drop-zone>
            <upload-list :uploadFiles="uploadFiles" @uploadFiles="handleFiles($event)"></upload-list>
		</v-card-text>
	</v-card>
</template>

<script>
import DropZone from './components/DropZone'
import UploadList from './components/UploadList'
import { uploadMediaWithProgress } from '../../api/media.js'

export default {
	name: "UploadFile",
    components: {
        DropZone,
        UploadList
    },
    props: {
	    maxUpload: {
            type: Number,
        },
        maxFileSize: {
            type: Number,
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
            uploadPercentage: [],
			uploadFiles: [],
			uploadMessage: [],
		};
	},
	methods: {
	    handleFiles(data) {
	        this.uploadFiles = data;
	        console.log(this.uploadFiles);
        },

	}
}
</script>

