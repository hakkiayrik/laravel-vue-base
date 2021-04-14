<template>
	<create-form :isEdit="true"></create-form>
</template>

<script>
import CreateForm from './components/CreateForm.vue'
export default {
	name: "edit",
	components: {
		CreateForm
	}
}
</script>

<style scoped>

</style>