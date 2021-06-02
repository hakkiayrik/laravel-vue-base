<template>
	<post-form :isEdit="false"></post-form>
</template>

<script>
import PostForm from './components/PostForm.vue'
export default {
	name: "create",
	components: {
		PostForm
	}
}
</script>

<style scoped>

</style>