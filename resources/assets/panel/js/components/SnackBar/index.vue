<template>
	<v-snackbar v-model="snackbar.snackbar" :timeout="snackbar.timeout" :color="snackbar.color">
		{{ snackbar.text }}
		
		<template v-slot:action="{ attrs }">
			<v-btn text v-bind="attrs" @click="snackbar.snackbar = false">
				{{ $t("buttons.close") }}
			</v-btn>
		</template>
	</v-snackbar>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
	name: "Snackbar",
	computed: {
		...mapGetters([
			"snackbar"
		])
	}
}
</script>

<style scoped>

</style>