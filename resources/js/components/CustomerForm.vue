<template>
    <form class="my-4" @submit.prevent="submit">
        <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
            <div class="col">
                <label for="first_name" class="form-label">First Name</label>
                <input
                    :class="{'form-control' : !this.errors?.first_name, 'form-control is-invalid' : this.errors?.first_name }"
                    type="text"
                    aria-label="First name"
                    name="first_name"
                    id="first_name"
                    v-model="customer.first_name"
                >
                <span class="invalid-feedback" role="alert" v-if="this.errors?.first_name">
                    <strong>{{ this.errors.first_name[0] }}</strong>
                </span>
            </div>
            <div class="col">
                <label for="last_name" class="form-label">Last Name</label>
                <input
                    :class="{'form-control' : !this.errors?.last_name, 'form-control is-invalid' : this.errors?.last_name }"
                    type="text"
                    aria-label="Last name"
                    name="last_name"
                    id="last_name"
                    v-model="customer.last_name"
                >
                <span class="invalid-feedback" role="alert" v-if="this.errors?.last_name">
                    <strong>{{ this.errors.last_name[0] }}</strong>
                </span>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
            <div class="col">
                <label for="document_type" class="form-label">Document Type</label>
                <select
                    :class="{'form-control' : !this.errors?.document_type, 'form-control is-invalid' : this.errors?.document_type }"
                    id="document_type"
                    name="document_type"
                    v-model="customer.document_type"
                >
                    <option
                        class="text-muted"
                        :value="null"
                    >
                        -- Seleccione el tipo de documento --
                    </option>
                    <option
                        value="CC"
                        :selected="customer.document_type === 'CC'"
                    >
                        Cédula de Ciudadanía
                    </option>
                    <option
                        value="CE"
                        :selected="customer.document_type === 'CE'"
                    >
                        Cédula de Extranjería
                    </option>
                    <option
                        value="P"
                        :selected="customer.document_type === 'P'"
                    >
                        Pasaporte
                    </option>
                </select>
                <span class="invalid-feedback" role="alert" v-if="this.errors?.document_type">
                    <strong>{{ this.errors.document_type[0] }}</strong>
                </span>
            </div>
            <div class="col">
                <label for="document" class="form-label">Document</label>
                <input
                    :class="{'form-control' : !this.errors?.document, 'form-control is-invalid' : this.errors?.document }"
                    type="text"
                    aria-label="Last name"
                    name="document"
                    id="document"
                    v-model="customer.document"
                >
                <span class="invalid-feedback" role="alert" v-if="this.errors?.document">
                    <strong>{{ this.errors.document[0] }}</strong>
                </span>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
            <div class="col">
                <label for="address" class="form-label">Address</label>
                <input
                    class="form-control"
                    type="text"
                    aria-label="First name"
                    name="address"
                    id="address"
                    v-model="customer.address"
                >
            </div>
            <div class="col">
                <label for="phone" class="form-label">Phone</label>
                <input
                    class="form-control"
                    type="text"
                    aria-label="Last name"
                    name="phone"
                    id="phone"
                    v-model="customer.phone"
                >
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
            <div class="col">
                <label for="cell_phone" class="form-label">Cell Phone</label>
                <input
                    class="form-control"
                    type="text"
                    aria-label="First name"
                    name="cell_phone"
                    id="cell_phone"
                    v-model="customer.cell_phone"
                >
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                Guardar
            </button>
        </div>
    </form>
</template>

<script>
    import { useToast } from "vue-toastification";

    export default {
        setup() {
            const toast = useToast();

            return { toast }
        },
        props: {
            customer: {
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
                axios.put(`/api/customers/${this.customer.id}`, this.customer).then(res => {
                    this.toast.success("Información actualizada", {
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
