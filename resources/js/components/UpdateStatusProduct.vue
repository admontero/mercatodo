<template>
    <div class="form-check form-switch">
        <input
            class="form-check-input"
            type="checkbox"
            id="flexSwitchCheckDefault"
            :checked="product.state === 'activated'"
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
                let url = this.product.state === 'activated'
                    ? `/api/admin/products/${this.product.slug}/inactivate`
                    : `/api/admin/products/${this.product.slug}/activate`;
                axios.post(url)
                    .then(res => {
                        this.$emit('updateProduct', res.data.state)
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
