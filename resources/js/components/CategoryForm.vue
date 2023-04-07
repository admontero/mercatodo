<template>
    <form class="my-4" @submit.prevent="submit">
        <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
            <div class="col">
                <label for="name" class="form-label">{{ $t('Name') }}</label>
                <input
                    :class="{'form-control' : !this.errors?.name, 'form-control is-invalid' : this.errors?.name }"
                    type="text"
                    aria-label="First name"
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
            category: {
                type: Object,
                required: true,
            }
        },
        data() {
            return {
                errors: []
            }
        },
        methods: {
            submit() {
                axios.put(`/api/categories/${this.category.slug}`, this.category).then(res => {
                    this.toast.success(trans('Updated category'), {
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
                }).catch(err => {
                    this.errors = err.response.data.errors;
                })
            }
        }
    }
</script>

<style>

</style>
