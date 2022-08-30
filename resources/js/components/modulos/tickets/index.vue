<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Tickets</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content" id="contenedor_tickets">
            <div class="row">
                <div class="col-md-3">
                    <router-link
                        v-if="permisos.includes('tickets.create')"
                        :to="{ name: 'tickets.create' }"
                        class="btn btn-primary bg-lightblue btn-block btn-flat mb-3"
                        >Nuevo ticket</router-link
                    >
                    <navegacion></navegacion>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Prioridad</h3>
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
                                <li class="nav-item">
                                    <a
                                        href="#"
                                        @click.prevent="
                                            sPrioridad = 'ALTO';
                                            getTickets();
                                        "
                                        class="nav-link"
                                        :class="{
                                            active: sPrioridad == 'ALTO',
                                        }"
                                    >
                                        <i
                                            class="far fa-circle text-danger"
                                        ></i>
                                        Alto
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        href="#"
                                        @click.prevent="
                                            sPrioridad = 'MEDIO';
                                            getTickets();
                                        "
                                        class="nav-link"
                                        :class="{
                                            active: sPrioridad == 'MEDIO',
                                        }"
                                    >
                                        <i
                                            class="far fa-circle text-warning"
                                        ></i>
                                        Medio
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        href="#"
                                        @click.prevent="
                                            sPrioridad = 'BAJO';
                                            getTickets();
                                        "
                                        class="nav-link"
                                        :class="{
                                            active: sPrioridad == 'BAJO',
                                        }"
                                    >
                                        <i
                                            class="far fa-circle text-primary"
                                        ></i>
                                        Bajo
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Tipo de soporte</h3>
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
                                <li
                                    class="nav-item"
                                    v-for="item in listTipoSoportes"
                                    :key="item.id"
                                >
                                    <a
                                        href="#"
                                        @click.prevent="
                                            sTipoSoporte = item.nombre;
                                            getTickets();
                                        "
                                        class="nav-link"
                                        :class="{
                                            active: sTipoSoporte == item.nombre,
                                        }"
                                    >
                                        <i class="far fa-circle text-black"></i>
                                        {{ item.nombre }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Tickets</h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm">
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Buscar ticket"
                                        v-model="search"
                                        @keyup="getTickets"
                                    />
                                    <div
                                        class="input-group-append"
                                        v-if="search != ''"
                                    >
                                        <div
                                            class="btn btn-primary bg-lightblue"
                                            @click="
                                                search = '';
                                                getTickets();
                                            "
                                        >
                                            <i class="fas fa-times"></i>
                                        </div>
                                    </div>
                                    <div class="input-group-append" v-else>
                                        <div class="btn btn-primary bg-lightblue">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="mailbox-controls">
                                <button
                                    v-if="permisos.includes('tickets.create')"
                                    type="button"
                                    class="btn btn-default btn-sm checkbox-toggle"
                                    @click="seleccionaTodo"
                                >
                                    <i
                                        :class="{
                                            'far fa-square': !selecciona,
                                            'far fa-check-square': selecciona,
                                        }"
                                    ></i>
                                </button>
                                <div class="btn-group">
                                    <button
                                        v-if="
                                            permisos.includes('tickets.create')
                                        "
                                        type="button"
                                        class="btn btn-default btn-sm"
                                        @click="eliminaSeleccionados"
                                        :disabled="
                                            array_seleccion.length > 0
                                                ? false
                                                : true
                                        "
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>

                                <button
                                    type="button"
                                    class="btn btn-default btn-sm"
                                    @click="getTickets"
                                >
                                    <i class="fas fa-sync-alt"></i>
                                </button>

                                <button
                                    v-if="sPrioridad != ''"
                                    type="button"
                                    class="btn btn-default btn-sm"
                                    @click="
                                        sPrioridad = '';
                                        getTickets();
                                    "
                                >
                                    {{ sPrioridad }}
                                    <i class="fas fa-times"></i>
                                </button>
                                <button
                                    v-if="sTipoSoporte != ''"
                                    type="button"
                                    class="btn btn-default btn-sm"
                                    @click="
                                        sTipoSoporte = '';
                                        getTickets();
                                    "
                                >
                                    {{ sTipoSoporte }}
                                    <i class="fas fa-times"></i>
                                </button>
                                <span>Total: {{ this.total }} Registros</span>

                                <div class="float-right">
                                    {{ current_page }}-{{ to }}/{{ total }}
                                    <div class="btn-group">
                                        <button
                                            type="button"
                                            class="btn btn-default btn-sm"
                                            @click="prevPage()"
                                        >
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn-default btn-sm"
                                            @click="nextPage()"
                                        >
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <template v-if="listTickets.length > 0">
                                <div class="table-responsive mailbox-messages">
                                    <table
                                        class="table"
                                        v-loading="loadingRegistros"
                                    >
                                        <tbody>
                                            <Ticket
                                                v-for="ticket in listTickets"
                                                :key="ticket.id"
                                                :ticket="ticket"
                                                :seleccionado="selecciona"
                                                @event_seleccion="
                                                    (id, sw) =>
                                                        cuentaSeleccion(id, sw)
                                                "
                                            ></Ticket>
                                        </tbody>
                                    </table>
                                </div>
                            </template>
                            <template v-else>
                                <div class="row">
                                    <div class="col-md-12 text-center p-2">
                                        <i>No se encontrarón registros...</i>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="card-footer p-0">
                            <div class="mailbox-controls">
                                <button
                                    v-if="permisos.includes('tickets.create')"
                                    type="button"
                                    class="btn btn-default btn-sm checkbox-toggle"
                                    @click="seleccionaTodo"
                                >
                                    <i
                                        :class="{
                                            'far fa-square': !selecciona,
                                            'far fa-check-square': selecciona,
                                        }"
                                    ></i>
                                </button>
                                <div
                                    class="btn-group"
                                    v-if="permisos.includes('tickets.create')"
                                >
                                    <button
                                        type="button"
                                        class="btn btn-default btn-sm"
                                        @click="eliminaSeleccionados"
                                        :disabled="
                                            array_seleccion.length > 0
                                                ? false
                                                : true
                                        "
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>

                                <button
                                    type="button"
                                    class="btn btn-default btn-sm"
                                    @click="getTickets"
                                >
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                                <button
                                    v-if="sPrioridad != ''"
                                    type="button"
                                    class="btn btn-default btn-sm"
                                    @click="
                                        sPrioridad = '';
                                        getTickets();
                                    "
                                >
                                    {{ sPrioridad }}
                                    <i class="fas fa-times"></i>
                                </button>
                                <button
                                    v-if="sTipoSoporte != ''"
                                    type="button"
                                    class="btn btn-default btn-sm"
                                    @click="
                                        sTipoSoporte = '';
                                        getTickets();
                                    "
                                >
                                    {{ sTipoSoporte }}
                                    <i class="fas fa-times"></i>
                                </button>
                                <span>Total: {{ this.total }} Registros</span>
                                <div class="float-right">
                                    {{ current_page }}-{{ to }}/{{ total }}
                                    <div class="btn-group">
                                        <button
                                            type="button"
                                            class="btn btn-default btn-sm"
                                        >
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn-default btn-sm"
                                        >
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import navegacion from "./parcial/Navegacion.vue";
import Ticket from "./parcial/TicketRow.vue";
export default {
    components: {
        navegacion,
        Ticket,
    },
    data() {
        return {
            permisos: localStorage.getItem("permisos"),
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
            listTickets: [],
            loadingRegistros: false,
            selecciona: false,
            total: 0,
            current_page: 0,
            to: 0,
            perPage: 0,
            search: "",
            sPrioridad: "",
            listTipoSoportes: [],
            sTipoSoporte: "",
            page: 1,
            lastPage: 1,
            array_seleccion: [],
        };
    },
    mounted() {
        this.getTipoSoportes();
        this.getTickets();
        this.loadingWindow.close();
    },
    computed: {},
    methods: {
        // LISTAR TICKETS
        getTickets() {
            this.loadingRegistros = true;
            axios
                .get("/admin/tickets", {
                    params: {
                        search: this.search,
                        prioridad: this.sPrioridad,
                        tipo_soporte: this.sTipoSoporte,
                        page: this.page,
                    },
                })
                .then((response) => {
                    this.listTickets = response.data.tickets.data;
                    this.total = response.data.tickets.total;
                    this.current_page = response.data.tickets.current_page;
                    this.to = response.data.tickets.to;
                    this.page = response.data.tickets.current_page;
                    this.perPage = response.data.tickets.perPage;
                    this.lastPage = response.data.tickets.last_page;
                    this.loadingRegistros = false;
                });
        },
        seleccionaTodo() {
            this.selecciona = !this.selecciona;
        },
        eliminaSeleccionados() {
            Swal.fire({
                title: "¿Está seguro(a) de eliminar los tickets seleccionados?",
                showCancelButton: true,
                confirmButtonColor: "#05568e",
                confirmButtonText: "Si, eliminar",
                cancelButtonText: "No, cancelar",
                denyButtonText: `No, cancelar`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    for (let i = 0; i < this.array_seleccion.length; i++) {
                        this.eliminaTicket(this.array_seleccion[i]);
                    }
                    this.array_seleccion = [];
                    let self = this;
                    setTimeout(() => {
                        self.getTickets();
                        Swal.fire({
                            icon: "success",
                            title: "¡Registro(s) eliminados(s) con éxito!",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    }, 500);
                }
            });
        },
        // ELIMINA TICKET
        eliminaTicket(id) {
            axios
                .post("/admin/tickets/" + id, {
                    _method: "DELETE",
                })
                .then((res) => {
                    // Swal.fire({
                    //     icon: "success",
                    //     title: res.data.msj,
                    //     showConfirmButton: false,
                    //     timer: 1500,
                    // });
                });
        },
        // TIPO DE SOPORTES
        getTipoSoportes() {
            axios.get("/admin/tipo_soportes").then((response) => {
                this.listTipoSoportes = response.data.tipo_soportes;
            });
        },
        // PAGINACION
        nextPage() {
            this.selecciona = false;
            if (this.page < this.lastPage) {
                this.page++;
                this.getTickets();
            }
        },
        prevPage() {
            this.selecciona = false;
            if (this.page > 1) {
                this.page--;
                this.getTickets();
            }
        },
        cuentaSeleccion(id, sw) {
            if (sw) {
                this.array_seleccion.push(id);
            } else {
                let index = this.array_seleccion.indexOf(id);
                this.array_seleccion.splice(index, 1);
            }
        },
    },
};
</script>

<style>
tr td {
    font-size: 0.8em;
}

tr td a {
    font-size: 1em;
}

tr td span.badge {
    font-size: 0.9em;
}

#contenedor_tickets .nav.nav-pills.flex-column .nav-item a.active {
    background: transparent;
    color: var(--principal);
    font-weight: bold;
}
</style>
