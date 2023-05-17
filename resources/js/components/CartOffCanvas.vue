<template>
    <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">{{ $t('My Cart') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div v-if="$store.state.cartCount > 0">
                <ul class="list-group list-group-flush">
                    <cart-off-canvas-item
                        v-for="product in $store.state.cart"
                        :key="product"
                        :product="product"
                    ></cart-off-canvas-item>
                </ul>
                <div class="d-flex justify-content-between align-items-center mt-5">
                    <h3 class="fs-5">Total: </h3>
                    <h3 class="fs-5">$ {{ total }}</h3>
                </div>
            </div>
            <div class="d-flex justify-content-center" v-else>
                <p>{{ $t('Your cart is empty') }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    import CartOffCanvasItem from './CartOffCanvasItem.vue';

    export default {
        components: {
            CartOffCanvasItem,
        },
        computed: {
            total() {
                let total = this.$store.state.cart.reduce(
                    (accumulator, product) => accumulator + (product.price * product.quantity),
                    0
                )

                return new Intl.NumberFormat('es-CO').format(total)
            }
        },
    }
</script>

<style>

</style>
