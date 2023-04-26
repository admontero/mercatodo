<template>
    <form class="my-4" @submit.prevent="submit" v-if="!loading">
        <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
            <div class="col">
                <label for="name" class="form-label">{{ $t('Name') }}</label>
                <input
                    :class="{'form-control' : !this.errors?.name, 'form-control is-invalid' : this.errors?.name }"
                    type="text"
                    aria-label="Name"
                    name="name"
                    id="name"
                    v-model="category.name"
                >
                <span class="invalid-feedback" role="alert" v-if="this.errors?.name">
                    <strong>{{ this.errors.name[0] }}</strong>
                </span>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
            <div class="col d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    {{ $t('Save') }}
                </button>
            </div>
        </div>
    </form>
    <div class="row row-cols-1 row-cols-md-2 row-cols-2" v-else>
        <div class="col d-flex justify-content-center align-items-center">
            <div class="spinner-border text-primary mt-4" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</template>

<script>
    import { useToast } from "vue-toastification";
    import { trans } from 'laravel-vue-i18n';

    export default {
        setup() {
            const toast = useToast();

            return { toast }
        },
        props: {
            categorySlug: {
                type: String,
            }
        },
        created() {
            if (this.categorySlug) {
                this.loading = true;
                axios.get(`/api/categories/${this.categorySlug}`)
                    .then(res => {
                        this.category = res.data;
                        this.loading = false;
                    })
                    .catch(err => {
                        console.log(err)
                        this.loading = false;
                    })
            }
        },
        data() {
            return {
                category: {},
                errors: [],
                loading: false,
            }
        },
        methods: {
            submit() {
                let method = this.categorySlug ? 'put' : 'post';
                let url = this.categorySlug ? `/api/categories/${this.category.slug}` : '/api/categories' ;
                let message = this.categorySlug ? 'Category updated' : 'Category created';

                axios[method](url, this.category).then(res => {
                    this.toast.success(trans(message), {
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
                    this.errors = [];
                    this.category = this.categorySlug ? res.data : {};
                }).catch(err => {
                    this.errors = err.response.data.errors;
                })
            }
        }
    }
</script>

<style>

</style>
