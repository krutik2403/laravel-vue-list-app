<template>
    <div class="my-app">
        <b-container>
            <div>
                <div>Date: {{ new Date().toJSON().slice(0, 10) }}</div>
                <div class="h1 text-center">My Awesome List</div>
            </div>
            <div class="d-flex justify-content-between">
                <div># {{ item.id ? "Edit" : "Add" }} User</div>
                <div>By : Krutik Patel</div>
            </div>
            <b-card class="mb-3">
                <b-card-body>
                    <b-form
                        inline
                        @submit.prevent="
                            item.id ? updateUser(item.id) : createUser()
                        "
                    >
                        <b-form-group>
                            <b-form-input
                                id="name"
                                class="mb-2 mr-sm-2 mb-sm-0"
                                placeholder="Name"
                                v-model="item.name"
                                :disabled="creating"
                                required
                            ></b-form-input>
                        </b-form-group>
                        <b-form-group>
                            <b-form-input
                                id="email"
                                class="mb-2 mr-sm-2 mb-sm-0"
                                placeholder="Email"
                                v-model="item.email"
                                type="email"
                                :disabled="creating"
                                :state="errors && errors.email ? false : null"
                                required
                            ></b-form-input>
                            <b-form-invalid-feedback>{{
                                errors && errors.email && errors.email[0]
                            }}</b-form-invalid-feedback>
                        </b-form-group>
                        <b-button
                            type="submit"
                            variant="primary"
                            :disabled="creating"
                        >
                            <div class="d-flex align-items-center">
                                <b-spinner small v-if="creating" class="mr-2" />
                                {{ item.id ? "Update" : "Submit" }}
                            </div>
                        </b-button>
                        <b-button
                            type="submit"
                            variant="danger"
                            class="ml-2"
                            v-if="item.id"
                            @click="setItem({ id: null, name: '', email: '' })"
                        >
                            Clear
                        </b-button>
                    </b-form>
                </b-card-body>
            </b-card>
            <div># Users</div>
            <b-spinner v-if="loading" />
            <b-table
                responsive
                fixed
                head-variant="light"
                hover
                bordered
                noCollapse
                :items="items"
                :fields="fields"
            >
                <template #cell(actions)="data">
                    <b-button
                        variant="primary"
                        @click="
                            setItem({
                                id: data.item.id,
                                name: data.item.name,
                                email: data.item.email
                            })
                        "
                    >
                        Update
                    </b-button>
                    <b-button
                        variant="danger"
                        :disabled="deleting.id === data.item.id"
                        @click="showAlert(data.item.id)"
                    >
                        <div class="d-flex align-items-center">
                            <b-spinner
                                small
                                v-if="deleting.id === data.item.id"
                                class="mr-2"
                            />
                            Delete
                        </div>
                    </b-button>
                </template>
            </b-table>
        </b-container>
    </div>
</template>

<script>
import {
    BButton,
    BTable,
    BSpinner,
    BContainer,
    BCard,
    BCardBody,
    BForm,
    BFormGroup,
    BFormInput,
    BFormInvalidFeedback,
    BAlert,
    BPopover
} from "bootstrap-vue";
export default {
    el: "#app",
    components: {
        BButton,
        BTable,
        BSpinner,
        BContainer,
        BCard,
        BCardBody,
        BForm,
        BFormGroup,
        BFormInput,
        BFormInvalidFeedback,
        BAlert,
        BPopover
    },
    data() {
        return {
            loading: false,
            creating: false,
            deleting: {
                id: null,
                state: false
            },
            updating: false,
            errors: null,
            item: {
                id: null,
                name: "",
                email: ""
            },
            items: null,
            fields: ["name", "email", "actions"]
        };
    },
    mounted() {
        this.getUsers();
    },
    methods: {
        showAlert(id) {
            this.$swal({
                title: "Are you sure want to delete?",
                icon: "warning",
                confirmButtonText: "Yes",
                showCancelButton: true
            }).then(res => {
                res.isConfirmed && this.deleteUser(id);
            });
        },
        setItem(data) {
            this.item = data;
        },
        async getUsers() {
            this.loading = true;
            await axios
                .get("/api/users")
                .then(res => {
                    this.loading = false;
                    this.items = res.data.users;
                })
                .catch(err => {
                    this.loading = false;
                    this.$toasted.show("Error occured while retriving.", {
                        type: "error"
                    });
                });
        },
        async createUser() {
            this.creating = true;
            await axios
                .post("/api/users", this.item)
                .then(res => {
                    this.creating = false;
                    this.errors = null;
                    this.item = { name: "", email: "" };
                    this.$toasted.show(res.data.message, { type: "success" });
                    this.getUsers();
                })
                .catch(err => {
                    this.creating = false;
                    this.errors = err.response.data.errors;
                    this.$toasted.show("Error occured while creating.", {
                        type: "error"
                    });
                });
        },
        async deleteUser(id) {
            this.deleting.id = id;
            this.deleting.state = true;

            await axios
                .delete(`/api/users/${id}`)
                .then(res => {
                    this.deleting.id = null;
                    this.deleting.state = false;
                    this.$toasted.show(res.data.message, { type: "success" });
                    this.getUsers();
                })
                .catch(err => {
                    this.deleting.id = null;
                    this.deleting = false;
                    this.$toasted.show("Error occured while deleting.", {
                        type: "error"
                    });
                });
        },
        async updateUser(id) {
            this.updating = true;
            await axios
                .post(`/api/users/${id}`, this.item)
                .then(res => {
                    this.updating = false;
                    this.errors = null;
                    this.item = { name: "", email: "" };
                    this.$toasted.show(res.data.message, { type: "success" });
                    this.getUsers();
                })
                .catch(err => {
                    this.updating = false;
                    this.errors = err.response.data.errors;
                    this.$toasted.show("Error occured while updating.", {
                        type: "error"
                    });
                });
        }
    }
};
</script>
