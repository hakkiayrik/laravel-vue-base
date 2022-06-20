<template>
    <post-form :isEdit="true"></post-form>
</template>

<script>
import PostForm from './components/Form.vue'
export default {
    name: "edit",
    components: {
        PostForm
    }
}
</script>

<style scoped>

</style>
