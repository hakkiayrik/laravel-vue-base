<template>
    <v-card elevation="0">
        <v-dialog transition="dialog-bottom-transition" v-model="dialogToggle" width="50%" max-width="100%" tile>
            <template v-slot:default="dialog">
                <v-card outlined elevation="2">
                    <v-card-actions class="justify-end">
                        {{fullScreenMedia.name}}
                        <v-spacer></v-spacer>
                        <v-btn text @click="dialogToggle = false" icon>
                            <v-icon large>close</v-icon>
                        </v-btn>
                    </v-card-actions>
                    <v-card-text class="pa-0">
                        <v-img :lazy-src="`https://picsum.photos/10/6?image=${fullScreenMedia.id * 5 + 10}`" :src="fullScreenMedia.url"></v-img>
                    </v-card-text>
                </v-card>
            </template>
        </v-dialog>
        <v-card-text>
            <v-row v-if="mediaData.length > 0">
                <v-col v-for="media in mediaData" :key="media.id" class="d-flex child-flex" :id="'media-' + media.id" cols="4" sm="4" md="3" lg="3">
                    <v-hover>
                        <template v-slot:default="{ hover }">
                            <v-card elevation="0" tile :class="{'select-hover': isSelectable}" style="position: relative">
                                <v-img :src="media.url" :lazy-src="`https://picsum.photos/10/6?image=${media.id * 5 + 10}`" aspect-ratio="1" class="grey lighten-2" width="100%">
                                    <template v-slot:placeholder>
                                        <v-row class="fill-height ma-0" align="center" justify="center">
                                            <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                                        </v-row>
                                    </template>
                                </v-img>
                                <v-fade-transition>
                                    <v-overlay v-if="hover" absolute color="secondary">
                                        <v-btn icon @click="showFullScreenMedia(media)"><v-icon>fullscreen</v-icon></v-btn>
                                        <v-btn icon color="red" @click="deleteItem(media)" v-if="isDeletable"><v-icon>delete_outline</v-icon></v-btn>
                                    </v-overlay>
                                </v-fade-transition>
                                <v-btn icon tile class="checkBox" @click="selectMedia(media)" v-if="isSelectable">
                                    <v-icon color="white" :id="'checkedMediaIcon-' + media.id">add</v-icon>
                                </v-btn>
                            </v-card>
                        </template>
                    </v-hover>
                </v-col>
            </v-row>
            <v-row v-else>
                <v-col align="center" justify="center">
                    <v-icon class="mt-5" size="60">sentiment_dissatisfied</v-icon>
                    <h3 class="bold">{{ $t("pages.media.not_found_media_message") }}</h3>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
import {getMedia, deleteMedia} from "../../api/media";
export default {
    name: "MediaList",
    data() {
        return {
            dialogToggle: false,
            mediaData: [],
            fullScreenMedia: {},
            selectedMedia: []
        }
    },
    created() {
        this.initialize()
    },
    props: {
        isSelectable: {
            type: Boolean,
            default: false,
        },
        isDeletable: {
            type: Boolean,
            default: true,
        },
        selectCount: {
            type: Number,
            default: 1,
        }
    },
    watch: {
        mediaTab: function (val) {
            if(val === 'tab-media_list') {
                this.initialize()
            }
        }
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
                    if (confirm) {
                        deleteMedia(item.id).then(response => {
                            this.mediaData.splice(this.mediaData.indexOf(item), 1);
                        }).catch(err => {})
                    }
                }
            })
        },
        showFullScreenMedia(item){
            this.fullScreenMedia = item
            this.dialogToggle = true;
        },
        selectMedia(media) {
            let element =  document.getElementById("media-" + media.id)
            let isActive = this.selectedMedia.find(item => item == media.url)

            if(typeof isActive != "undefined") {
                let elementIndex = this.selectedMedia.indexOf(media.url)
                this.selectedMedia.splice(elementIndex, 1)

                document.getElementById('checkedMediaIcon-' + media.id).innerHTML = 'add'
                element.classList.remove("selected-image")
            } else {
                if(this.selectCount > 1 && this.selectedMedia.length < this.selectCount  ) {
                    this.selectedMedia.push(media.url)
                    document.getElementById('checkedMediaIcon-' + media.id).innerHTML = 'check'
                    element.classList.add("selected-image")
                } else if(this.selectCount == 1 ) {
                    //clear selected media
                    this.selectedMedia = []
                    let selectedElement = document.getElementsByClassName("selected-image")
                    if(selectedElement.length) {
                        selectedElement[0].classList.remove("selected-image")
                    }

                    this.selectedMedia.push(media.url)
                    document.getElementById('checkedMediaIcon-' + media.id).innerHTML = 'check'
                    element.classList.add("selected-image")

                    this.$emit("selected-media", this.selectedMedia);
                }
            }

            this.$emit("selected-media", this.selectedMedia);
        }
    },
}
</script>

<style>
    .checkBox {
        position: absolute;
        top:-3px;
        right: -3px;
        margin: 0;
        padding: 0;
        background-color: #1976d2;
        z-index: 10;
    }

    .selected-image .checkBox {
        background-color: #42b983!important;
    }

    .checkBox .v-input--selection-controls__input {
        margin: 0!important;
    }
    .select-hover{
        border:3px solid #1976d2 !important;
    }
    .selected-image .select-hover {
        border-color: #42b983!important;
    }
</style>
