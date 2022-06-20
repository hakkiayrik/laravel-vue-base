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
                                <v-toolbar-title>{{ $t('menus.attribute_groups.index') }}</v-toolbar-title>
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
                                <v-btn color="primary" dark class="justify-content-between" :to="{name: 'attribute-group.create'}" v-permission="['create-attribute']">
                                    <v-icon small>add</v-icon>
                                    {{ $t('pages.attribute_groups.add_attribute_group') }}
                                </v-btn>
                            </v-toolbar>
                        </template>
                        <template v-slot:item.actions="{ item }">
                            <v-btn :to="{ name:'attribute-group.edit', params:{id: item.id} }" icon class="mr-2" small color="primary" dark v-permission="['edit-attribute']"> <v-icon small>edit</v-icon> </v-btn>
                            <v-btn icon small color="error" dark @click="deleteItem(item)" v-permission="['delete-attribute']"> <v-icon small>delete</v-icon> </v-btn>
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
import { getAttributeGroups, deleteAttributeGroup } from "../../api/attribute";

export default {
    name: "index",
    data() {
        return {
            loading: false,
            desserts: [],
            options: {},
            search: "",
            totalDesserts: 0
        }
    },
    watch: {
        options: {
            handler () {
                this.initialize()
            },
            deep: true,
        },
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
                    text: this.$i18n.t('pages.attribute_groups.attribute_group_name'),
                    align: 'left',
                    value: 'name'
                },
                {
                    text: this.$i18n.t('fields.created_at'),
                    align: 'left',
                    value: 'created_at',
                    width:'10%'
                },
                {
                    text: this.$i18n.t('global.actions'),
                    align: 'center',
                    value: 'actions',
                    width: "5%",
                    sortable: false
                },
            ]

        }
    },
    methods: {
        initialize() {
            this.loading = true
            const { sortBy, sortDesc, page, itemsPerPage } = this.options
            let search = this.search.trim().toLowerCase();
            getAttributeGroups({ sortBy, sortDesc, page, itemsPerPage, search }).then(response => {
                this.loading = false
                this.desserts = response.data.data
                this.totalDesserts = response.data.links.total
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
                        deleteAttributeGroup(item.id).then(response => {
                            this.desserts.splice(this.desserts.indexOf(item), 1);
                        }).catch(err => {})
                    }
                }
            })
        }
    },
}
</script>

<style scoped>

</style>
