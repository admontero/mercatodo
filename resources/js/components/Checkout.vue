<template>
    <div class="py-3 px-5" v-if="$store.state.cartCount > 0">
        <h3 class="fs-2 text-start text-primary fw-semibold">{{ $t('Your order') }}</h3>
        <div class="row gx-4 mt-4">
            <div class="col-md-8">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">{{ $t('Product') }}</th>
                            <th scope="col">{{ $t('Price') }}</th>
                            <th scope="col">{{ $t('Quantity') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <checkout-product-item
                            v-for="product in $store.state.cart"
                            :key="product.id"
                            :product="product"
                        ></checkout-product-item>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <a class="d-inline-block link-info text-decoration-none my-2" href="/">
                    <IconChevronLeft :size="16" stroke-width="2" />
                    {{ $t('Continue shopping') }}
                </a>
                <div class="p-3 border rounded">
                    <div class="d-flex justify-content-between align-items-center text-primary mb-3">
                        <h5>Total: </h5>
                        <h5 class="fw-semibold">$ {{ total }}</h5>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="/checkout" class="btn btn-primary" role="button">
                            {{ $t('Continue Purchase') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="checkout-empty mx-auto d-flex flex-column justify-content-center" v-else>
        <h3 class="fs-2 text-start">{{ $t('Your cart is empty') }}</h3>
        <p>
            {{ $t('To continue shopping, continue browsing through the products with the help of the searches and filters') }}.
        </p>
        <div class="d-grid gap-2">
            <a href="/" class="btn btn-lg btn-primary text-uppercase" role="button">
                {{ $t('select products') }}
            </a>
        </div>
    </div>
</template>

<script>
    import CheckoutProductItem from './CheckoutProductItem.vue';
    import { IconChevronLeft } from '@tabler/icons-vue';

    export default {
        components: {
            CheckoutProductItem,
            IconChevronLeft,
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

<style scoped>
    .checkout-empty {
        height: calc(100vh - 150px);
        max-width: 60%;
    }
</style>
