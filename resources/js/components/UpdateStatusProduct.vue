<template>
    <div class="form-check form-switch">
        <input
            class="form-check-input"
            type="checkbox"
            id="flexSwitchCheckDefault"
            :checked="product.status === 'activated'"
            :disabled="loading"
            v-on:input="updateStatus"
        >
        <label class="form-check-label" for="flexSwitchCheckDefault"></label>
    </div>
</template>

<script>
    export default {
        props: {
            product: {
                type: Object,
                required: true,
            }
        },
        data() {
            return {
                loading: false,
            }
        },
        methods: {
            updateStatus () {
                this.loading = true;
                axios.post(`/api/admin/products/${this.product.slug}/status`)
                    .then(res => {
                        this.$emit('updateProduct', res.data.status)
                        this.loading = false;
                    }).catch(err => {
                        console.log(err.response.data)
                        this.loading = false;
                    })
            }
        }
    }
</script>

<style>

</style>
