<template>
    <v-card
        elevation="0"
        @drop.prevent="onDrop($event)"
        @dragover.prevent="dragover = true"
        @dragenter.prevent="dragover = true"
        @dragleave.prevent="dragover = false"
        class="dropzone-area">
        <v-alert dense outlined type="error" v-for="(message, index) in uploadMessage" :key="index" v-if="uploadMessage">
            {{ message }}
        </v-alert>
        <!-- New Addition -->
        <v-row class="d-flex flex-column mb-5" dense align="center" justify="center">
            <v-icon class="mt-5" size="60">cloud_upload</v-icon>
            <h3 class="bold text-center">{{ $t("pages.media.upload_media_message") }}</h3>
            <h3 class="bold mt-5 mb-5">{{ $t("global.or") }}</h3>
            <label class="btn display-inline">
                {{ $t("buttons.select_file") }}
                <input type="file" name="select_file" ref="uploadInput" :multiple="maxUpload > 1" @change="inputOnChange">
            </label>
            <v-subheader class="font-italic">{{ $t("pages.media.max_upload_size_message",  { max_upload_size:  Math.round( (maxFileSize / 1024) *100) / 100 + " MB"  } )  }}</v-subheader>
        </v-row>
    </v-card>
</template>

<script>
export default {
    name: "DropZone",
    props: {
        maxUpload: {
            type: Number,
            default: 2
        },
        maxFileSize: {
            type: Number,
            default: 2048
        },
    },
    data() {
        return {
            loading: false,
            dragover: false,
            uploadFiles: [],
            uploadMessage: [],
        }
    },
    methods: {
        onDrop(e) {
            this.dragover = false
            this.uploadFiles = []
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
                            fileData.uploadPercentage = 0
                            this.uploadFiles.push(fileData);
                        }
                    }
                });

                this.$emit("uploadFiles", this.uploadFiles)
            }
        },
        inputOnChange(e) {
            this.dragover = false
            this.uploadFiles = []
            this.uploadMessage = []
            const files = e.target.files
            console.log(e);
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
                            fileData.uploadPercentage = 0
                            this.uploadFiles.push(fileData);
                        }
                    }
                });

                this.$emit("uploadFiles", this.uploadFiles)
                this.$refs.uploadInput.value = ""
            }
        }
    }
}
</script>

<style scoped>
    .dropzone-area {
        border-radius: 0;
        border:3px dashed #b0b0b0;
    }
    .btn {
        background-color: #1976d2;
        border: 0;
        color: #fff;
        cursor: pointer;
        display: inline-block;
        font-weight: bold;
        padding: 10px 20px;
        position: relative;
        text-transform: uppercase;
    }

    .btn:hover {
        background-color: #4597e5;
    }

    input[type="file"] {
        position: absolute;
        opacity: 0;
        z-index: -1;
    }
</style>
