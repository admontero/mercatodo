<template>
    <div class="d-flex justify-content-between mb-3">
        <img class="img-fluid" style="max-width: 75px" :src="imageUrl" :alt="`${product.name}'s image'`" />
        <div class="flex-grow-1 ms-2">
            <small class="d-block text-primary">{{ product.name }}</small>
            <small class="fw-semibold">{{ product.pivot.quantity }} {{ $t('unt') }}.</small>
            <small class="fw-semibold ms-2">$ {{ accumulatedPrice }}</small>
        </div>
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
        computed: {
            productPrice() {
                return new Intl.NumberFormat('es-CO').format(this.product.price)
            },
            imageUrl() {
                if (this.product.image) return 'storage/' + this.product.image

                return 'https://picsum.photos/640/480';
            },
            accumulatedPrice() {
                return new Intl.NumberFormat('es-CO').format(this.product.pivot.price * this.product.pivot.quantity)
            },
            unitPrice() {
                return new Intl.NumberFormat('es-CO').format(this.product.price)
            }
        }
    }
</script>

<style>

</style>
