<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Carpetas</h3>
                <div class="card-tools">
                    <button
                        type="button"
                        class="btn btn-tool"
                        data-card-widget="collapse"
                    >
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item active">
                        <router-link
                            exact
                            :to="{ name: 'tickets.index' }"
                            class="nav-link"
                        >
                            <i class="fas fa-inbox"></i> Tickets
                            <span
                                class="badge bg-primary float-right"
                                v-if="
                                    cantidad_sin_ver > 0 &&
                                    user.tipo != 'PERSONAL'
                                "
                                v-text="cantidad_sin_ver"
                            ></span>
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="user.tipo != 'TÉCNICO'">
                        <router-link
                            :to="{ name: 'tickets.mis_tickets' }"
                            class="nav-link"
                        >
                            <i class="far fa-envelope"></i> Mis tickets
                        </router-link>
                    </li>
                    <li
                        class="nav-item"
                        v-if="permisos.includes('tickets.create')"
                    >
                        <router-link
                            :to="{ name: 'tickets.tickets_eliminados' }"
                            class="nav-link"
                        >
                            <i class="far fa-trash-alt"></i> Eliminados
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: JSON.parse(localStorage.getItem("user")),
            permisos: localStorage.getItem("permisos"),
            cantidad_sin_ver: 0,
        };
    },
    mounted() {
        this.cantidadTicketsSinVer();
    },
    methods: {
        // CANTIDAD SIN VER
        cantidadTicketsSinVer() {
            axios
                .get("/admin/tickets/cantidadTicketsSinVer")
                .then((response) => {
                    this.cantidad_sin_ver = response.data;
                });
        },
    },
};
</script>

<style></style>
