<template>
    <li class="list-group-item">
        <div class="d-flex gap-2">
            <div class="w-25">
                <img class="img-fluid" :src="imageUrl" :alt="`${product.name}'s image'`">
            </div>
            <div class="flex-grow-1">
                <h4 class="text-black-50 small">{{ product.name }}</h4>
                <p>$ {{ accumulatedPrice }}</p>
            </div>
            <div class="w-25 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-end">
                    <a
                        class="link-danger"
                        role="button"
                        @click="removeFromCart(product)"
                    >
                        <IconTrash :size="20" stroke-width="2" />
                    </a>
                </div>
                <div class="d-flex justify-content-end align-items-center">
                    <button
                        class="btn btn-light btn-sm py-0"
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
                        class="btn btn-light btn-sm py-0"
                        @click="incrementFromCart(product)"
                    >
                        <IconPlus :size="12" stroke-width="2" />
                    </button>
                </div>
            </div>
        </div>
    </li>
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
            }
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
            }
        }
    }
</script>

<style>

</style>
