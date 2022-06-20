<template>
    <v-dialog v-model="dialog" max-width="600px">
        <v-card>
            <validation-observer ref="form" v-slot="{ invalid, validated, passes, validate }">
                <v-form @keyup.native.enter="passes(formSubmit)">
                    <v-card-title>
                        <span class="headline" v-if="selectedAttribute">{{ $t("pages.attribute_groups.edit_attribute") }}</span>
                        <span class="headline" v-if="!selectedAttribute">{{ $t("pages.attribute_groups.add_attribute") }}</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-alert dense type="error" v-if="attributeError.status" class="mb-10" tile>{{ attributeError.message }}</v-alert>
                            <v-row>
                                <v-col class="py-0">
                                    <validation-provider v-slot="{ errors, valid }" name="attribute_name" rules="required|max:25|min:2">
                                        <v-text-field
                                            v-model="attribute.label"
                                            :label="$t('fields.attribute_name')"
                                            class="pa-0"
                                            :error-messages="errors[0]"
                                            :success="valid"
                                            outlined
                                            dense
                                            required></v-text-field>
                                    </validation-provider>
                                </v-col>

                                <v-col class="py-0">
                                    <validation-provider v-slot="{ errors, valid }" name="attribute_name" rules="required">
                                        <v-select
                                            v-model="attribute.type"
                                            :items="attributeTypes"
                                            item-text="name"
                                            item-value="name"
                                            :error-messages="errors[0]"
                                            :success="valid"
                                            :label="$t('fields.attribute_type')"
                                            outlined
                                            dense
                                            required></v-select>
                                    </validation-provider>
                                </v-col>
                            </v-row>
                            <v-row v-if="attribute.type == 'select' || attribute.type == 'checkbox' || attribute.type == 'radio' || attribute.type == 'boolean'">
                                <v-col>
                                    <v-subheader class="pa-0">{{ $t("global.options") }}</v-subheader>
                                    <v-divider></v-divider>
                                    <v-simple-table>
                                        <template v-slot:default>
                                            <tbody>
                                                <tr v-for="(option, index) in attribute.options" :key="option.name">
                                                    <td class="pa-0">{{ option.name }}</td>
                                                    <td width="5%"  class="pa-0">
                                                        <v-tooltip bottom>
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <v-btn tile x-small fab v-bind="attrs" color="error" v-on="on" @click="removeOption(index)">
                                                                    <v-icon>delete</v-icon>
                                                                </v-btn>
                                                            </template>
                                                            <span>{{$t("buttons.edit")}}</span>
                                                        </v-tooltip>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </template>
                                    </v-simple-table>
                                    <v-list class="pa-0">
                                        <v-list-item class="pa-0">
                                            <v-list-item-content>
                                                <v-text-field v-model="attributeOption.name" :label="$t('fields.option_name')" class="pa-0" outlined dense hide-details></v-text-field>
                                            </v-list-item-content>
                                            <v-list-item-action>
                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-btn tile x-small fab v-bind="attrs" v-on="on" class="primary" @click="addOption">
                                                            <v-icon>add</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>{{$t("buttons.add")}}</span>
                                                </v-tooltip>
                                            </v-list-item-action>
                                        </v-list-item>
                                    </v-list>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error darken-1" text @click="dialog = false">{{ $t('buttons.close') }}</v-btn>
                        <v-btn color="primary darken-1" text @click="passes(formSubmit)">{{ $t('buttons.save') }}</v-btn>
                    </v-card-actions>
                </v-form>
            </validation-observer>
        </v-card>
    </v-dialog>
</template>

<script>
import {getAttribute, addAttribute, updateAttribute} from "../../../api/attribute";

export default {
    name: "AttributeForm",
    props: ["selectedAttribute"],
    data() {
        return {
            loading: false,
            dialog: true,
            attribute: {
                label: "",
                html_name: "",
                type: "",
                display_order: 1,
                options: []
            },
            attributeError: {
                status: false,
                message: ""
            },
            attributeOption: {
                name: ""
            },
            attributeTypes: [
                {
                    "id": 1,
                    "name": "text",
                    "label": "Text"
                },
                {
                    "id": 2,
                    "name": "number",
                    "label": "Number"
                },
                {
                    "id": 3,
                    "name": "select",
                    "label": "Select"
                },
                {
                    "id": 4,
                    "name": "checkbox",
                    "label": "Checkbox"
                },
                {
                    "id": 5,
                    "name": "radio",
                    "label": "Radio"
                },
                {
                    "id": 6,
                    "name": "boolean",
                    "label": "Boolean"
                }
            ]
        }
    },
    mounted() {
        if(this.selectedAttribute) {
            this.getAttribute(this.selectedAttribute)
        }
    },
    watch: {
        dialog() {
            if(!this.dialog) {
                this.attribute = {}
            }

            this.$emit("dialog", this.dialog);
        },
    },
    methods: {
        getAttribute(id) {
            this.loading = true
            getAttribute(id).then(response => {
                this.loading = false
                this.attribute = response.data
                this.handleOption()
            }).then(async response => {
                await this.$refs.form.validate()
            }).catch(err => {this.loading = false})
        },
        formSubmit() {
            this.loading = true
            let data = {
                label: this.attribute.label,
                type: this.attribute.type,
                options: JSON.stringify(this.attribute.options)
            }

            this.checkAttributeValues();
            if(!this.attributeError.status) {
                if (this.selectedAttribute) {
                    updateAttribute(this.selectedAttribute, data).then(response => {
                        this.loading = false
                        this.$emit("refresh", true);
                        this.dialog = false
                    }).catch(err => {
                        this.loading = false
                        this.$refs.form.setErrors(err.response.data.data)
                    })
                } else {
                    addAttribute(data).then(response => {
                        this.loading = false
                        this.$emit("refresh", true);
                        this.dialog = false
                    }).catch(err => {
                        this.loading = false
                        this.$refs.form.setErrors(err.response.data.data)
                    })
                }
            }
        },
        handleOption() {
            if(this.attribute.options != null) {
                this.attribute.options = JSON.parse(this.attribute.options)
            } else {
                this.attribute.options = []
            }
            console.log(this.attribute.options);
        },
        addOption() {
            if(this.attributeOption.name != "") {
                this.attribute.options.push(this.attributeOption)
                this.attributeOption = {
                    name: ""
                }
            }
        },
        removeOption(index) {
            this.attribute.options.splice(index, 1)
        },
        checkAttributeValues(){
            if( (this.attribute.type == 'select' ||
                this.attribute.type == 'checkbox' ||
                this.attribute.type == 'radio' ||
                this.attribute.type == 'boolean') &&
                this.attribute.options.length == 0
            ) {
                this.attributeError.message = this.$i18n.t("pages.attribute_groups.empty_option_error_message");
                this.attributeError.status = true
            } else {
                this.attributeError.message = "";
                this.attributeError.status = false
            }
        }
    }
}
</script>

<style scoped>

</style>
