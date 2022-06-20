<template>
    <v-app>
        <attribute-form v-if="attributeFormDialog" @dialog="handleDialog" @refresh="refreshAttributes" :selected-attribute="selectedAttribute"></attribute-form>
        <v-container fluid>
            <validation-observer ref="form" v-slot="{ invalid, validated, passes, validate }">
                <v-form @keyup.native.enter="passes(formSubmit)">
                    <validation-provider v-slot="{ errors, valid }" name="attribute-group_name" rules="required|max:25">
                        <v-text-field
                            v-model="attributeGroup.name"
                            :counter="25"
                            :error-messages="errors[0]"
                            :success="valid"
                            :label="$t('fields.group_name')"
                            prepend-inner-icon="title"
                            dense
                            outlined
                            required
                        ></v-text-field>
                    </validation-provider>

                    <v-row>
                        <v-col>
                            <v-card elevation="0" tile outlined>
                                <v-card-title style="border-bottom: 1px solid #434343">
                                    <v-subheader class="pa-0">{{$t("pages.attribute_groups.all_attribute_list")}}</v-subheader>
                                    <v-spacer></v-spacer>
                                    <v-text-field v-model="searchAllAttributes" append-icon="search" :label="$t('fields.search')" hide-details solo-inverted dense></v-text-field>
                                    <v-spacer></v-spacer>
                                    <v-btn color="primary" tile small @click="attributeFormDialog = true" v-permission="['create-attribute']">{{ $t("pages.attribute_groups.add_attribute") }}</v-btn>
                                </v-card-title>
                                <v-card-text class="attribute-list pa-0">
                                    <v-list class="pa-0" v-if="attributes.length > 0">
                                        <template v-for="(item, index) in attributes">
                                            <v-list-item :key="item.name">
                                                <v-list-item-avatar>
                                                    <v-icon v-if="item.type === 'text'">text_fields</v-icon>
                                                    <v-icon v-else-if="item.type === 'number'">pin</v-icon>
                                                    <v-icon v-else-if="item.type === 'select'">inventory</v-icon>
                                                    <v-icon v-else-if="item.type === 'checkbox'">check_box</v-icon>
                                                    <v-icon v-else-if="item.type === 'radio'">radio_button_checked</v-icon>
                                                    <v-icon v-else-if="item.type === 'boolean'">toggle_on</v-icon>
                                                </v-list-item-avatar>
                                                <v-list-item-content>
                                                    <v-list-item-title>{{ item.label }}</v-list-item-title>
                                                </v-list-item-content>
                                                <v-list-item-action>
                                                    <v-tooltip bottom>
                                                        <template v-slot:activator="{ on, attrs }">
                                                            <v-btn tile x-small fab v-bind="attrs" v-on="on" color="primary" @click="editAttribute(item.id)">
                                                                <v-icon>edit</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>{{$t("buttons.edit")}}</span>
                                                    </v-tooltip>
                                                </v-list-item-action>
                                                <v-list-item-action class="ml-4">
                                                    <v-tooltip bottom>
                                                        <template v-slot:activator="{ on, attrs }">
                                                            <v-btn tile x-small fab v-bind="attrs" v-on="on" color="error" @click="deleteAttribute(item)">
                                                                <v-icon>delete</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>{{$t("buttons.delete")}}</span>
                                                    </v-tooltip>
                                                </v-list-item-action>
                                                <v-list-item-action>
                                                    <v-tooltip bottom>
                                                        <template v-slot:activator="{ on, attrs }">
                                                            <v-btn tile x-small fab v-bind="attrs" color="success" v-on="on" @click="addAttributeToGroup(item)">
                                                                <v-icon>double_arrow</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>{{$t("buttons.add")}}</span>
                                                    </v-tooltip>
                                                </v-list-item-action>

                                            </v-list-item>
                                            <v-divider></v-divider>
                                        </template>
                                    </v-list>
                                    <v-alert type="error" tile v-else class="ma-4">
                                        {{$t("pages.attribute_groups.attribute_not_found")}}
                                    </v-alert>
                                </v-card-text>
                            </v-card>
                        </v-col>
                        <v-col>
                            <v-card elevation="0" tile outlined>
                                <v-card-title style="border-bottom: 1px solid #434343">
                                    <v-subheader class="pa-0">{{$t("pages.attribute_groups.assigned_attribute_list")}}</v-subheader>
                                    <v-spacer></v-spacer>
                                    <v-text-field v-model="searchAllAttributes" append-icon="search" :label="$t('fields.search')" hide-details solo-inverted dense></v-text-field>
                                    <v-spacer></v-spacer>
                                    <v-btn color="primary" tile small @click="saveAssignedAttributes">{{ $t("buttons.save") }}</v-btn>
                                </v-card-title>
                                <v-card-text class="pa-0">
                                    <v-list class="pa-0" v-if="attributeGroup.attributes.length > 0">
                                        <template v-for="(item, index) in attributeGroup.attributes">
                                            <v-list-item :key="item.name">
                                                <v-list-item-avatar>
                                                    <v-icon v-if="item.type === 'text'">text_fields</v-icon>
                                                    <v-icon v-else-if="item.type === 'number'">pin</v-icon>
                                                    <v-icon v-else-if="item.type === 'select'">inventory</v-icon>
                                                    <v-icon v-else-if="item.type === 'checkbox'">check_box</v-icon>
                                                    <v-icon v-else-if="item.type === 'radio'">radio_button_checked</v-icon>
                                                    <v-icon v-else-if="item.type === 'boolean'">toggle_on</v-icon>
                                                </v-list-item-avatar>
                                                <v-list-item-content>
                                                    <v-list-item-title>{{ item.label }}</v-list-item-title>
                                                </v-list-item-content>
                                                <v-list-item-action class="ml-4">
                                                    <v-tooltip bottom>
                                                        <template v-slot:activator="{ on, attrs }">
                                                            <v-btn tile x-small fab v-bind="attrs" v-on="on" color="error" @click="deleteAssignedAttribute(item)">
                                                                <v-icon>delete</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>{{$t("buttons.delete")}}</span>
                                                    </v-tooltip>
                                                </v-list-item-action>
                                                <v-list-item-action>
                                                    <v-tooltip bottom>
                                                        <template v-slot:activator="{ on, attrs }">
                                                            <v-btn tile x-small fab v-bind="attrs" v-on="on" color="primary"  @click="editAttribute(item.id)">
                                                                <v-icon>edit</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>{{$t("buttons.edit")}}</span>
                                                    </v-tooltip>
                                                </v-list-item-action>
                                            </v-list-item>
                                            <v-divider></v-divider>
                                        </template>
                                    </v-list>
                                    <v-alert type="error" tile v-else class="ma-4">
                                        {{$t("pages.attribute_groups.assigned_attribute_not_found")}}
                                    </v-alert>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>

                    <v-btn color="primary" class="my-2" @click="formSubmit" :disabled="invalid || !validated" :loading="loading">{{ $t("buttons.save") }}</v-btn>
                </v-form>
            </validation-observer>
        </v-container>
    </v-app>
</template>

<script>
import {addAttributeGroup, getAttributeGroup, updateAttributeGroup, getAttributes, deleteAttribute, assignAttributes} from '../../../api/attribute'
import AttributeForm from "../components/AttributeForm";

export default {
    name: "From",
    components: {
        AttributeForm
    },
    props: {
        isEdit: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            attributeGroup: {
                id: 0,
                name: '',
                attributes: []
            },
            search: "",
            expanded: [],
            routes: [],
            loading: false,
            selectedAttribute: "",
            attributeFormDialog: false,
            searchAllAttributes: "",
            searchUnsignedAttributes: "",
            attributes: [],
        }
    },
    created() {
        if(this.isEdit) {
            const id = this.$route.params && this.$route.params.id
            this.initialize(id);
        }
    },
    methods: {
        initialize(id) {
            getAttributeGroup(id).then(response => {
                this.attributeGroup = response.data
            }).then(async response => await this.$refs.form.validate())

            this.getAttributes()
        },
        getAttributes() {
            getAttributes().then(response => {
                this.attributes = response.data.data

                this.attributes.forEach((attribute, index) => {
                    const found = this.attributeGroup.attributes.find(item => {
                       return attribute.id == item.id
                    });
                    console.log("deneme ", found);
                    if(typeof found != "undefined") {
                        this.attributes.splice(index, 1);
                    }
                })

            })
        },
        formSubmit() {
            this.loading = true;

            if(this.isEdit) {
                updateAttributeGroup(this.attributeGroup.id, this.attributeGroup).then(response => {
                    this.loading = false
                    this.$router.push({name: 'attribute-group.index'})
                }).catch(err => {
                    this.loading = false

                    if(err.response.status === 400) {
                        this.$refs.form.setErrors(err.response.data.data)
                    }
                })
            } else {
                addAttributeGroup(this.attributeGroup).then(response => {
                    this.loading = false
                    this.$router.push({name: 'attribute-group.index'})
                }).catch(err => {
                    this.loading = false

                    if(err.response.status === 400) {
                        this.$refs.form.setErrors(err.response.data.data)
                    }
                })
            }
        },
        editAttribute(id) {
            this.selectedAttribute = id
            this.attributeFormDialog = true
        },
        deleteAttribute(item) {
            this.$confirm({
                message: this.$i18n.t('global.cancel_dialog_title'),
                button: {
                    no: this.$i18n.t('buttons.no'),
                    yes: this.$i18n.t('buttons.yes')
                },
                callback: confirm => {
                    if (confirm) {
                        deleteAttribute(item.id).then(response => {
                            this.attributes.splice(this.attributes.indexOf(item), 1);
                        }).catch(err => {})
                    }
                }
            })
        },
        deleteAssignedAttribute(item) {
            let attributeIndex = this.attributeGroup.attributes.indexOf(item);

            this.attributes.push(this.attributeGroup.attributes[attributeIndex]);
            this.attributeGroup.attributes.splice(attributeIndex, 1);
        },
        addAttributeToGroup(item)
        {
            let attributeIndex = this.attributes.indexOf(item);

            this.attributeGroup.attributes.push(this.attributes[attributeIndex]);
            this.attributes.splice(attributeIndex, 1);
        },
        saveAssignedAttributes()
        {
            this.loading = true
            let attributes = this.attributeGroup.attributes.map(item => item.id);
            assignAttributes(this.attributeGroup.id, attributes).then(response => {
                this.loading = false
            }).catch(err => {
                this.loading = false
            })
        },
        refreshAttributes() {
            this.getAttributes();
        },
        handleSelectedCategory(id) {
            this.selectedAttribute = id
            this.handleDialog(true);
        },
        handleDialog(payload) {
            this.attributeFormDialog = payload
            if(!payload) {
                this.selectedAttribute = ""
            }
        },
    },
}
</script>

<style>
    .v-input--selection-controls{
        margin-top: 0!important;
    }
    .attribute-list {
        overflow: auto;
        max-height: 342px;
    }

    .attribute-list::-webkit-scrollbar-track
    {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        background-color: #cdcdcd;
    }

    .attribute-list::-webkit-scrollbar
    {
        width: 5px;
    }

    .attribute-list::-webkit-scrollbar-thumb
    {
        background-color: #434343;
    }
</style>
