<template>
    <v-app>
        <v-container fluid>
            <v-row>
                <v-col cols="12">
                    <v-data-table
                        :headers="headers"
                        :items="desserts"
                        :laoding="loading"
                        :options.sync="options"
                        :server-items-length="totalDesserts"
                        :footer-props="{'items-per-page-options': [25, 50, 100]}"
                    >
                        <template v-slot:top>
                            <v-toolbar flat>
                                <v-toolbar-title>{{ $t('pages.languages.title') }}</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="search"
                                    :label="$t('fields.search')"
                                    flat
                                    dense
                                    solo-inverted
                                    hide-details
                                    single-line
                                    class="mr-2"
                                    @change="initialize"
                                ></v-text-field>
                            </v-toolbar>
                        </template>
                        <template v-slot:item.active="{ item }">
                            <v-switch
                                v-model="item.active"
                                color="success"
                                class="d-inline-block ma-0"
                                hide-details
                                dense
                                @change="handleLanguage(item)"
                            ></v-switch>
                        </template>
                        <template v-slot:item.is_rtl="{ item }">
                            <v-chip class="ma-2" small color="success" v-if="item.is_rtl">{{ $t("global.yes") }}</v-chip>
                            <v-chip class="ma-2" small color="error" v-if="!item.is_rtl">{{ $t("global.no") }}</v-chip>
                        </template>
                        <template v-slot:no-data>
                            <v-btn color="primary" @click="initialize">Reset</v-btn>
                        </template>
                    </v-data-table>
                </v-col>
            </v-row>
        </v-container>
    </v-app>
</template>


<script>
import { getLanguages, changeActive } from "../../api/language";

export default {
    name: "index",
    data() {
        return {
            loading: false,
            options: {},
            search: "",
            desserts: [],
            totalDesserts: 0
        }
    },
    computed: {
        headers() {
            return  [
                {
                    text: this.$i18n.t('global.id'),
                    align: 'left',
                    value: 'id',
                    width:'5%'

                },
                {
                    text: this.$i18n.t('global.name'),
                    align: 'left',
                    value: 'name'
                },
                {
                    text: this.$i18n.t('global.lang_code'),
                    align: 'left',
                    value: 'lang_code'
                },
                {
                    text: this.$i18n.t('global.is_rtl'),
                    align: 'left',
                    value: 'is_rtl'
                },
                {
                    text: this.$i18n.t('global.active'),
                    align: 'center',
                    value: 'active',
                    width: "5%",
                    sortable: false
                },
            ]

        }
    },
    watch: {
        options: {
            handler () {
                this.initialize()
            },
            deep: true,
        }
    },
    methods: {
        initialize() {
            this.loading = true
            const { sortBy, sortDesc, page, itemsPerPage } = this.options
            let search = this.search.trim().toLowerCase();
            getLanguages({ sortBy, sortDesc, page, itemsPerPage, search }).then(response => {
                this.loading = false;
                this.desserts = response.data.data;
                this.totalDesserts = response.data.data.links.total
            }).catch(err => { this.loading = false })
        },
        handleLanguage(item) {
            changeActive(item.id, {active: item.active}).then(response => {
                console.log(response)
            })
        }
    },
}
</script>

<style scoped>

</style>
