<template>
    <tr>
        <td>
            <div class="d-flex flex-column flex-md-row align-items-md-center">
                <img class="img-fluid" style="max-width: 100px" :src="imageUrl" :alt="`${product.name}'s image'`" />
                <span class="ms-md-4 fw-semibold">{{ product.name }}</span>
            </div>
        </td>
        <td class="align-middle">
            <small class="text-muted" v-if="product.quantity > 1">($ {{ unitPrice }} {{ $t('per unit') }})</small>
            <p class="text-primary fw-semibold lh-1">$ {{ accumulatedPrice }}</p>
        </td>
        <td class="align-middle">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex justify-content-between align-items-center">
                    <button
                        class="btn btn-light btn-sm"
                        @click="decrementFromCart(product)"
                    >
                        <IconMinus :size="12" stroke-width="2" />
                    </button>
                    <div class="px-3">
                        <span class="small">
                            {{ product.quantity }}
                        </span>
                    </div>
                    <button
                        class="btn btn-light btn-sm"
                        @click="incrementFromCart(product)"
                    >
                        <IconPlus :size="12" stroke-width="2" />
                    </button>
                </div>
                <a
                    class="link-danger"
                    role="button"
                    @click="removeFromCart(product)"
                >
                    <IconTrash :size="22" stroke-width="2" />
                </a>
            </div>
        </td>
    </tr>
</template>

<script>
    import { IconTrash, IconPlus, IconMinus } from '@tabler/icons-vue';

    export default {
        components: {
            IconTrash,
            IconPlus,
            IconMinus,
        },
        props: {
            product: {
                type: Object,
                required: true,
            },
        },
        methods: {
            removeFromCart(product) {
                this.$store.commit('removeFromCart', product);
            },
            incrementFromCart(product) {
                this.$store.commit('incrementFromCart', product);
            },
            decrementFromCart(product) {
                this.$store.commit('decrementFromCart', product);
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
                return new Intl.NumberFormat('es-CO').format(this.product.price * this.product.quantity)
            },
            unitPrice() {
                return new Intl.NumberFormat('es-CO').format(this.product.price)
            }
        }
    }
</script>

<style>

</style>
