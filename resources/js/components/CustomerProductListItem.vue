<template>
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="bg-white rounded shadow-sm">
            <img :src="imageUrl" :alt="`${product.name}'s image'`" class="img-fluid card-img-top">
            <div class="px-4 pt-3">
                <h6 class="text-truncate">{{ product.name }}</h6>
                <p class="small text-muted text-truncate">
                    <span v-if="!!product.description">{{ product.description }}</span>
                    <span class="fst-italic" v-else>
                        --{{ $t('No description available') }}--
                    </span>
                </p>
            </div>
            <div class="d-flex justify-content-center align-items-center bg-light p-1 h5 fw-bold">
                $ {{ productPrice }}
            </div>
            <div class="px-4 pb-3 pt-2">
                <div class="d-grid gap-2">
                    <a class="btn btn-primary btn-sm">{{ $t('Add to Cart') }}</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { trans } from 'laravel-vue-i18n';

    export default {
        props: {
            product: {
                type: Object,
                required: true,
            }
        },
        methods: {
            refreshUser(status) {
                this.customer.user.status = status;
                this.toast.success(trans(`Customer ${status}`), {
                    position: "bottom-left",
                    timeout: 3000,
                    closeOnClick: true,
                    pauseOnFocusLoss: true,
                    pauseOnHover: true,
                    draggable: true,
                    draggablePercent: 0.6,
                    showCloseButtonOnHover: false,
                    hideProgressBar: false,
                    closeButton: "button",
                    icon: true,
                    rtl: false
                });
            }
        },
        computed: {
            productPrice() {
                return new Intl.NumberFormat('es-CO').format(this.product.price)
            },
            imageUrl() {
                if (this.product.image) return 'storage/' + this.product.image

                return 'https://picsum.photos/640/480';
            }
        }
    }
</script>

<style>

</style>
