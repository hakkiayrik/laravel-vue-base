<template>
	<v-dialog v-model="dialog" max-width="600px">
		<template v-slot:default="dialog">
			<v-card>
				<validation-observer ref="form" v-slot="{ invalid, validated, passes, validate }">
					<v-card-title>
						<span class="headline" v-if="selectedCategory">{{ $t("pages.categories.edit") }}</span>
						<span class="headline" v-if="!selectedCategory">{{ $t("pages.categories.create") }}</span>
					</v-card-title>
					<v-card-text>
						<v-form @keyup.native.enter="passes(formSubmit)">
							<v-row>
								<v-col>
									<validation-provider v-slot="{ errors, valid }" name="name" rules="required|max:25">
										<v-text-field
											v-model="category.name"
											:counter="25"
											:error-messages="errors[0]"
											:success="valid"
											:label="$t('fields.name')"
											prepend-inner-icon="title"
											dense
											outlined
											required
										></v-text-field>
									</validation-provider>
									<validation-provider v-slot="{ errors, valid }" name="slug" rules="required|max:50">
										<v-text-field
											v-model="category.slug"
											:counter="25"
											:error-messages="errors[0]"
											:success="valid"
											:label="$t('fields.slug')"
											prepend-inner-icon="link"
											dense
											outlined
											required
										></v-text-field>
									</validation-provider>
									<v-textarea
										v-model="category.description"
										:counter="25"
										:label="$t('fields.description')"
										prepend-inner-icon="notes"
										dense
										outlined
										required
									></v-textarea>
									<validation-provider v-slot="{ errors, valid }" name="order_by" rules="required|numeric">
										<v-text-field
											v-model="category.order_by"
											:counter="25"
											:error-messages="errors[0]"
											:success="valid"
											:label="$t('fields.order_by')"
											prepend-inner-icon="import_export"
											dense
											outlined
											required
										></v-text-field>
									</validation-provider>
								</v-col>
							</v-row>
						</v-form>
					</v-card-text>
					<v-card-actions>
						<v-spacer></v-spacer>
						<v-btn color="error darken-1" text @click="dialog.value = false">{{ $t("buttons.cancel") }}</v-btn>
						<v-btn color="primary darken-1" text @click="formSubmit" :disabled="invalid || !validated" :loading="loading">{{ $t("buttons.save") }}</v-btn>
					</v-card-actions>
				</validation-observer>
			</v-card>
		</template>
	</v-dialog>
</template>

<script>
import helperMixin from '../../../mixins/helper'
import { updateCategory, createCategory, getCategory } from "../../../api/category";
export default {
	name: "FromDialog",
	props: ["selectedCategory"],
	mixins: [helperMixin],
	data() {
		return {
			loading: false,
			category: {
				id: "",
				name: "",
				description: "",
				slug: "",
				order_by: 0
			},
			dialog: true
		}
	},
	mounted() {
		if(this.selectedCategory) {
			this.getCategory(this.selectedCategory)
		}
	},
	watch: {
		dialog() {
			this.$emit("dialog", this.dialog);
		},
		category: {
			handler: function(payload) {
				this.category.slug = this.convertToSlug(payload.name)
			},
			deep: true
		},
	},
	methods: {
		getCategory(id) {
			this.loading = true
			getCategory(id).then(response => {
				this.loading = false
				this.category = response.data
			}).catch(err => {this.loading = false})
		},
		formSubmit() {
			this.loading = true
			if (this.selectedCategory) {
				updateCategory(this.selectedCategory, this.category).then(response => {
					this.loading = false
					this.dialog = false
					this.$emit("refresh", true);
				}).catch(err => {
					this.loading = false
				})
			} else {
				createCategory(this.category).then(response => {
					this.loading = false
					this.dialog = false
					this.$emit("refresh", true);
				}).catch(err => {
					this.loading = false
				})
			}
		}
	}
}
</script>

<style scoped>

</style>