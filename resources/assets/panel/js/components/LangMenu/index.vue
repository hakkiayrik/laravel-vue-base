<template>
	<v-menu open-on-hover bottom offset-y transition="slide-y-transition">
		<template v-slot:activator="{ on, attrs }">
			<v-btn icon class="ml-3" tile  v-bind="attrs" v-on="on">
				<v-icon>g_translate</v-icon>
			</v-btn>
		</template>
		
		<v-list dense class="pa-0">
			<v-list-item v-for="(lang, index) in languages" :key="lang" @click="setLang(index)">
				<v-list-item-title>{{ $t(`${lang}`) }}</v-list-item-title>
			</v-list-item>
		</v-list>
	</v-menu>
</template>

<script>
export default {
	name: "index",
	data() {
		return {
			languages: JSON.parse(process.env.MIX_PANEL_LANGUAGES),
		}
	},
	computed: {
		lang() {
			return this.$store.getters.language
		}
	},
	methods: {
		setLang(lang) {
			this.$i18n.locale = lang
			this.$store.dispatch('app/setLanguage', lang)
		}
	}
}
</script>

<style scoped>

</style>