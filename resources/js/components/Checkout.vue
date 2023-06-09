<template>
    <div class="py-3 px-5" v-if="$store.state.cartCount > 0">
        <h3 class="fs-2 text-start text-primary fw-semibold">{{ $t('Your order') }}</h3>
        <div class="alert alert-dismissible alert-danger" v-if="this.error">
            <span>{{ $t('There is a problem to process the purchase, try it later please.') }}</span>
        </div>
        <div class="row gx-4 mt-4" v-if="!loading">
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
                    <select
                        class="form-control mb-3"
                        id="provider"
                        name="provider"
                        v-model="order.provider"
                    >
                        <option :value="processor" v-for="(processor, index) in processors" :key="index">
                            {{ processor }}
                        </option>
                    </select>
                    <div class="d-flex justify-content-between align-items-center text-primary mb-3">
                        <h5>Total: </h5>
                        <h5 class="fw-semibold">$ {{ total }}</h5>
                    </div>
                    <div class="d-grid gap-2">
                        <a @click="pay" class="btn btn-primary" role="button">
                            {{ $t('Finalize Purchase') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-else>
            <div class="col d-flex justify-content-center align-items-center">
                <div class="spinner-border text-primary mt-4" role="status">
                    <span class="visually-hidden">Loading...</span>
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
        props: {
            processors: {
                type: Array,
                required: true
            }
        },
        components: {
            CheckoutProductItem,
            IconChevronLeft,
        },
        data() {
            return {
                order: {},
                loading: false,
                error: '',
            }
        },
        mounted() {
            this.order.provider = this.processors[0]
        },
        methods: {
            pay() {
                this.loading = true
                this.error = ''
                axios.post('/api/customer/payments', this.getFormData())
                    .then(res => {
                        this.$store.dispatch('clearCart');
                        window.location.href = res.data.url
                    })
                    .catch(err => {
                        this.error = err.response.data.message
                        this.loading = false
                    })
            },
            getFormData() {
                const formData = new FormData();
                if (this.order.provider) formData.append('provider', this.order.provider)
                formData.append('products', JSON.stringify(this.$store.state.cart))
                formData.append('total', this.$store.state.cart.reduce((accumulator, product) => accumulator + (product.price * product.quantity), 0))

                return formData;
            },
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
