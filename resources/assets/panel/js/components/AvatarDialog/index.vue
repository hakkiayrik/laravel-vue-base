<template>
	<v-dialog v-model="dialog" max-width="800">
		<template v-slot:default="dialog">
			<v-card>
				<v-card-title>
					<span class="headline">{{ $t("components.avatar_dialog.select_avatar") }}</span>
				</v-card-title>
				<v-card-text>
					<v-container fluid>
						<v-row>
							<v-col v-for="(avatar, index) in avatars" :key="avatar.id"  cols="1" class="d-flex justify-center">
								<v-img :src="avatar.image_path" aspect-ratio="1" class="rounded-circle" :class="{selected: active.id === avatar.id}" @click="selectAvatar(index)">
									<template v-slot:placeholder>
										<v-row class="fill-height ma-0" align="center" justify="center">
											<v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
										</v-row>
									</template>
								</v-img>
							</v-col>
						</v-row>
					</v-container>
				</v-card-text>
				<v-card-actions class="justify-end">
					<v-btn text @click="dialog.value = false">{{ $t("buttons.close") }}</v-btn>
				</v-card-actions>
			</v-card>
		</template>
	</v-dialog>
</template>

<script>
import { getAvatars } from "../../api/avatar";

export default {
	name: "index",
	props: ['active'],
	data() {
		return {
			loading:false,
			dialog:true,
			avatars: []
		}
	},
	created() {
		this.initialize()
	},
	watch:{
		dialog() {
			this.$emit('dialog', this.dialog)
		}
	},
	methods: {
		initialize() {
			this.loading = true
			getAvatars().then(response => {
				this.loading = false;
				this.avatars = response.data.data;
			}).catch(err => { this.loading = false })
		},
		selectAvatar(index) {
			const avatarData = this.avatars[index];
			this.$emit('select-avatar', avatarData)
		}
	}
}
</script>

<style>
	.v-image.selected {
		border:3px solid #56ff00!important;
	}
</style>