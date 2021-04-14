<template>
	<user-form :isEdit="true"></user-form>
</template>

<script>
import UserForm from './components/EditForm'
export default {
	name: "edit",
	components: {
		UserForm
	}
}
</script>

<style scoped>

</style>