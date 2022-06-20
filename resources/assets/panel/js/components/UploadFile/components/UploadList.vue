<template>
    <v-card elevation="0">
        <v-list v-if="uploadFiles.length > 0" dense>
            <template v-for="(item, index) in uploadFiles">
                <v-divider class="my-3"></v-divider>
                <v-list-item class="px-0">
                    <v-list-item-avatar max-height="100" max-width="100" size="50" tile>
                        <v-img :src="item.imagePath" :alt="item.name" class="img-thumbnail"/>
                    </v-list-item-avatar>
                    <v-list-item-content class="py-0 mr-5">
                        <v-progress-linear :value="item.uploadPercentage" v-if="item.uploadPercentage > 0 && item.uploadPercentage < 100" class="mb-1"></v-progress-linear>
                        <v-list-item-title class="d-flex align-center">
                            <span>{{ item.name }} </span>
                        </v-list-item-title>
                        <v-list-item-subtitle>
                            {{ Math.round(item.size / 1024 * 100 ) / 100}} KB
                        </v-list-item-subtitle>
                    </v-list-item-content>
                    <v-list-item-action v-if="item.uploadPercentage == 0">
                        <v-btn color="primary" @click="uploadItem(index)" icon raised small outlined tile>
                            <v-icon small>file_upload</v-icon>
                        </v-btn>
                    </v-list-item-action>
                    <v-list-item-action v-if="item.uploadPercentage == 100">
                        <v-icon dark color="success">cloud_done</v-icon>
                    </v-list-item-action>
                    <v-list-item-action v-if="item.uploadPercentage == 0">
                        <v-btn color="error" @click="removeItem(index)" icon raised small outlined tile class="px-0">
                            <v-icon small>delete</v-icon>
                        </v-btn>
                    </v-list-item-action>
                </v-list-item>
            </template>

        </v-list>
    </v-card>
</template>

<script>
import {uploadMediaWithProgress} from "../../../api/media";

export default {
    name: "UploadList",
    props: ["uploadFiles"],
    data() {
        return {
            loading: false,
            dragover: false,
            uploadLoading: false,
            uploadPercentage: [],
            uploadMessage: [],
        }
    },
    methods: {
        uploadItem(index) {
            this.loading = true
            this.uploadFiles[index].imageLoading = true;
            this.uploadFile(this.uploadFiles[index], index)
        },
        removeItem(index, item) {
            this.uploadFiles.splice(index, 1)
            this.$emit("uploadFiles", this.uploadFiles)
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
            const formData = new FormData()
            const uploadFile = this.uploadFiles[index];
            formData.append('files', file);

            uploadMediaWithProgress({
                url: `media`,
                method: 'post',
                data: formData,
                onUploadProgress: function ( progressEvent ) {
                    uploadFile.uploadPercentage = Math.round( ( progressEvent.loaded * 100) / progressEvent.total )
                    this.uploadFiles.splice(index, 1, uploadFile)
                }.bind(this)
            }).then(response => {
                this.loading = false
            }).catch(err=>{ this.loading = false})
        }
    }
}
</script>

<style scoped>

</style>
