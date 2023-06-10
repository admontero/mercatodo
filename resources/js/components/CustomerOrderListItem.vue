<template>
    <div class="card border-light mb-3">
        <div class="card-header bg-light rounded-0 p-4 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex gap-5">
                    <div>
                        <small class="d-block fw-semibold">{{ $t('Date of order') }}</small>
                        <small>{{ order.date }}</small>
                    </div>
                    <div>
                        <small class="d-block fw-semibold">{{ $t('Total') }}</small>
                        <small>$ {{ totalPrice }}</small>
                    </div>
                </div>
                <div class="text-end">
                    <span class="d-block fw-semibold"># {{ order.code }}</span>
                    <span class="badge bg-info text-uppercase" v-if="order.state === 'pending'">
                        {{ $t(order.state) }}
                    </span>
                    <span class="badge bg-danger text-uppercase" v-else-if="order.state === 'canceled'">
                        {{ $t(order.state) }}
                    </span>
                    <span class="badge bg-success text-uppercase" v-else-if="order.state === 'completed'">
                        {{ $t(order.state) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="d-md-flex justify-content-between gap-2">
                <div class="w-md-75">
                    <customer-order-list-item-product
                        v-for="product in order.products"
                        :key="product.id"
                        :product="product"
                    ></customer-order-list-item-product>
                </div>
                <div class="w-md-25">
                    <div class="d-flex justify-content-end" v-if="order.state === 'pending'">
                        <a class="btn btn-sm btn-primary" :href="order.url" role="button">
                            {{ $t('Continue Purchase') }}
                        </a>
                    </div>
                    <div class="d-flex justify-content-end" v-if="order.state === 'canceled'">
                        <button class="btn btn-sm btn-primary" @click="newOrder">
                            {{ $t('Retry Purchase') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomerOrderListItemProduct from './CustomerOrderListItemProduct.vue';

    export default {
        props: {
            order: {
                type: Object,
                required: true
            }
        },
        components: {
            CustomerOrderListItemProduct
        },
        methods: {
            retryPay() {
                this.$emit('retryPay', true)
                this.error = ''
                axios.put(`/api/customer/payments/${this.order.code}`)
                    .then(res => {
                        window.location.href = res.data.url
                    })
                    .catch(err => {
                        this.error = err.response.data.message
                        this.loading = false
                    })
            },
            newOrder() {
                this.$emit('retryPay', true)
                this.$store.commit('loadToCart', this.order.products);
                window.location.href = '/checkout'
            }
        },
        computed: {
            totalPrice() {
                return new Intl.NumberFormat('es-CO').format(this.order.total)
            }
        }
    }
</script>

<style>

</style>
