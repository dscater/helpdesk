<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Incidencias Ticket</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-5">
                        <div class="row mb-1">
                            <div class="col-md-6 p-1">
                                <router-link
                                    :to="{ name: 'tickets.index' }"
                                    class="btn btn-default btn-flat btn-block"
                                    ><i class="fa fa-arrow-left"></i>
                                    Volver</router-link
                                >
                            </div>
                            <div
                                class="col-md-6 p-1"
                                v-if="
                                    oTicket.estado != 'SOLUCIONADO' &&
                                    permisos.includes('tickets.create')
                                "
                            >
                                <router-link
                                    :to="{
                                        name: 'tickets.edit',
                                        params: { id: id },
                                    }"
                                    class="btn btn-default btn-flat btn-block"
                                >
                                    <i class="fa fa-edit"></i> Editar ticket
                                </router-link>
                            </div>
                        </div>
                        <TicketIncidencias
                            :ticket="oTicket"
                        ></TicketIncidencias>
                    </div>
                    <div class="col-md-7">
                        <div class="card card-dark bg-chat mb-0">
                            <!-- <div class="card-header" style="padding: 5px 20px; border-bottom:solid 2px var(--principal)">
                                <div class="mailbox-controls">
                                    <button
                                        type="button"
                                        class="btn btn-default btn-sm"
                                        @click="getListaIncidencias()"
                                    >
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div> -->
                            <div
                                class="card-body contenedor_incidencias"
                                id="contenedor_incidencias"
                                v-loading="loading_incidencias"
                            >
                                <div class="timeline">
                                    <div class="time-label">
                                        <span class="bg-lightblue">{{
                                            formatoFecha2(
                                                oTicket.fecha_registro
                                            )
                                        }}</span>
                                    </div>

                                    <div
                                        v-for="item in listIncidencias"
                                        :key="item.id"
                                    >
                                        <i
                                            class="fas"
                                            :class="{
                                                'fa-envelope bg-blue':
                                                    item.tipo_incidencia ==
                                                    'REGISTRO',
                                                'fa-pen bg-info':
                                                    item.tipo_incidencia ==
                                                    'SOLUCION',
                                            }"
                                        ></i>
                                        <div class="timeline-item">
                                            <span class="time">
                                                <span
                                                    class="text-gray font-weight-bold"
                                                    v-html="
                                                        getEstadoEnvio(item)
                                                    "
                                                ></span>
                                                <i class="fas fa-clock"></i>
                                                Hace
                                                {{
                                                    formatoFecha(
                                                        item.created_at
                                                    )
                                                }}</span
                                            >
                                            <h3 class="timeline-header">
                                                <span>{{ item.asunto }}</span>
                                                <br />
                                                <small class="mb-0">
                                                    Por:
                                                    <span>{{
                                                        item.user?.full_name
                                                    }}</span>
                                                </small>
                                            </h3>
                                            <div class="timeline-body">
                                                {{ item.descripcion }}
                                            </div>
                                            <div class="timeline-footer">
                                                <div
                                                    class="row"
                                                    v-if="
                                                        item.archivos.length > 0
                                                    "
                                                >
                                                    <div class="col-md-12">
                                                        <ul
                                                            class="mailbox-attachments d-flex align-items-stretch clearfix"
                                                        >
                                                            <li
                                                                v-for="a in item.archivos"
                                                                :key="a.id"
                                                            >
                                                                <span
                                                                    class="mailbox-attachment-icon"
                                                                    v-html="
                                                                        obtieneIcono(
                                                                            a
                                                                        )
                                                                    "
                                                                ></span>
                                                                <div
                                                                    class="mailbox-attachment-info"
                                                                >
                                                                    <a
                                                                        href="#"
                                                                        @click.prevent="
                                                                            descargar(
                                                                                a
                                                                            )
                                                                        "
                                                                        class="mailbox-attachment-name"
                                                                        ><i
                                                                            class="fas fa-paperclip"
                                                                        ></i>
                                                                        {{
                                                                            a.nombre_original
                                                                        }}</a
                                                                    >
                                                                    <span
                                                                        class="mailbox-attachment-size clearfix mt-1"
                                                                    >
                                                                        <span>{{
                                                                            item.size
                                                                        }}</span>
                                                                        <button
                                                                            @click="
                                                                                descargar(
                                                                                    a
                                                                                )
                                                                            "
                                                                            class="btn btn-default btn-sm float-right"
                                                                        >
                                                                            <i
                                                                                class="fas fa-cloud-download-alt"
                                                                            ></i>
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div
                                                    class="row"
                                                    v-if="
                                                        oTicket.estado !=
                                                        'SOLUCIONADO'
                                                    "
                                                >
                                                    <div class="col-md-12">
                                                        <router-link
                                                            v-if="
                                                                item.user_id ==
                                                                    user.id ||
                                                                user.tipo ==
                                                                    'ADMINISTRADOR'
                                                            "
                                                            :to="{
                                                                name: 'tickets.editar_incidencia',
                                                                params: {
                                                                    id: item.id,
                                                                },
                                                            }"
                                                            class="btn btn-warning btn-flat rounded-circle btn-sm"
                                                            ><i
                                                                class="fa fa-edit"
                                                            ></i
                                                        ></router-link>
                                                        <a
                                                            v-if="
                                                                item.user_id ==
                                                                    user.id ||
                                                                user.tipo ==
                                                                    'ADMINISTRADOR'
                                                            "
                                                            @click.prevent="
                                                                eliminaIncidencia(
                                                                    item.id,
                                                                    item.asunto
                                                                )
                                                            "
                                                            class="btn btn-danger btn-flat rounded-circle btn-sm"
                                                            ><i
                                                                class="fa fa-trash"
                                                            ></i
                                                        ></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <i
                                            class="fas"
                                            :class="{
                                                'fa-clock bg-warning':
                                                    oTicket.estado ==
                                                    'PENDIENTE',
                                                'fa-check-circle bg-success':
                                                    oTicket.estado ==
                                                    'SOLUCIONADO',
                                            }"
                                        ></i>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="card-footer"
                                style="
                                    padding: 5px 20px;
                                    border-top: solid 2px var(--principal);
                                "
                            >
                                <div class="mailbox-controls">
                                    <button
                                        type="button"
                                        class="btn btn-primary bg-lightblue btn-sm btn-flat btn-block"
                                        @click="
                                            getListaIncidencias();
                                            getTicket();
                                        "
                                    >
                                        Actualizar
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="oTicket.estado != 'SOLUCIONADO'">
                            <FormIncidencia
                                :ticket="oTicket"
                                @enviado="
                                    getListaIncidencias();
                                    getTicket();
                                "
                            ></FormIncidencia>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import TicketIncidencias from "./parcial/TicketIncidencias.vue";
import FormIncidencia from "./parcial/FormIncidencia.vue";
export default {
    components: {
        TicketIncidencias,
        FormIncidencia,
    },
    props: ["id"],
    data() {
        return {
            permisos: localStorage.getItem("permisos"),
            user: JSON.parse(localStorage.getItem("user")),
            loadingWindow: Loading.service({
                fullscreen: this.fullscreenLoading,
            }),
            loading_incidencias: false,
            oTicket: {
                id: 0,
                tipo_soporte_id: "",
                prioridad: "ALTO",
                asunto: "",
                descripcion: "",
                informacion_usuario: {},
                archivos: [],
            },
            listIncidencias: [],
            listArchivos: [],
        };
    },
    mounted() {
        this.getTicket();
        this.loadingWindow.close();
    },
    methods: {
        scrollIncidencias() {
            var elem = document.getElementById("contenedor_incidencias");
            elem.scrollTop = elem.scrollHeight - elem.clientHeight;
        },
        getTicket() {
            axios.get("/admin/tickets/" + this.id).then((response) => {
                this.oTicket = response.data;
                this.getListaIncidencias();
                if (this.oTicket.envio != "RECIBIDO") {
                    setTimeout(this.setEstadoEnvio, 200);
                }
            });
        },
        setEstadoEnvio() {
            axios
                .post("/admin/tickets/setEstadoEnvio/" + this.oTicket.id, {
                    envio: "RECIBIDO",
                })
                .then((response) => {
                    this.oTicket = response.data;
                });
        },
        getListaIncidencias() {
            this.loading_incidencias = true;
            axios
                .get("/admin/tickets/listaIncidencias/" + this.id)
                .then((response) => {
                    this.listIncidencias = response.data.incidencias;
                    setTimeout(this.scrollIncidencias, 200);
                    this.loading_incidencias = false;
                });
        },
        formatoFecha(date) {
            return this.$moment(String(date)).fromNow(true);
        },
        formatoFecha2(date) {
            return this.$moment(String(date)).format("DD/MM/YYYY");
        },

        // ARCHIVOS
        obtieneIcono(archivo) {
            let array = archivo.archivo.split(".");
            if (array[1] == "docx" || array[1] == "doc") {
                return '<i class="far fa-file-word"></i>';
            }
            if (array[1] == "pdf") {
                return '<i class="far fa-file-pdf"></i>';
            }
            if (
                array[1] == "png" ||
                array[1] == "jpg" ||
                array[1] == "jpeg" ||
                array[1] == "webpdm" ||
                array[1] == "gif"
            ) {
                return `<img src="${archivo.url}" alt="Imagen">`;
            }
            return '<i class="fas fa-paperclip"></i>';
        },
        obtieneIconoDescarga(archivo) {
            let array = archivo.archivo.split(".");
            if (
                array[1] == "png" ||
                array[1] == "jpg" ||
                array[1] == "jpeg" ||
                array[1] == "webpdm" ||
                array[1] == "gif"
            ) {
                return '<i class="fas fa-camera"></i>';
            }
            return '<i class="fas fa-paperclip"></i>';
        },
        eliminaIncidencia(id, descripcion) {
            Swal.fire({
                title: "¿Quierés eliminar este registro?",
                html: `<strong>${descripcion}</strong>`,
                showCancelButton: true,
                confirmButtonColor: "#05568e",
                confirmButtonText: "Si, eliminar",
                cancelButtonText: "No, cancelar",
                denyButtonText: `No, cancelar`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    axios
                        .post("/admin/solucion_tickets/" + id, {
                            _method: "DELETE",
                        })
                        .then((res) => {
                            this.getListaIncidencias();
                            this.filter = "";
                            Swal.fire({
                                icon: "success",
                                title: res.data.msj,
                                showConfirmButton: false,
                                timer: 1500,
                            });
                        });
                }
            });
        },
        descargar(archivo) {
            axios
                .get(archivo.url_descarga, { responseType: "blob" })
                .then((response) => {
                    const blob = new Blob([response.data]);
                    const link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download = archivo.archivo;
                    link.click();
                    URL.revokeObjectURL(link.href);
                })
                .catch(console.error);
        },
        getEstadoEnvio(item) {
            if (item.envio == "ENVIADO") {
                return `<span>Enviado</span> <i class="fa fa-check"></i>`;
            } else {
                return `<span class="text-primary">Recibido</span> <i class="fa fa-check text-primary"></i>`;
            }
        },
    },
};
</script>

<style>
.mailbox-attachment-info a {
    font-size: 0.9rem;
}
#contenedor_incidencias {
    max-height: 70vh;
    overflow: auto;
}

#contenedor_incidencias::-webkit-scrollbar {
    width: 8px;
}

#contenedor_incidencias::-webkit-scrollbar-track {
    width: 8px;
}

#contenedor_incidencias::-webkit-scrollbar-thumb {
    background: #888;
}

/* ARCHIVOS */
.mailbox-attachments li {
    width: 130px;
}

.mailbox-attachment-icon {
    height: 100px;
    padding: 2px 2px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.mailbox-attachment-icon i {
    font-size: 1em;
}

.mailbox-attachment-icon img {
    max-width: 100%;
    max-height: 100px;
}
.mailbox-attachment-info {
    white-space: nowrap;
    overflow: hidden !important;
    text-overflow: ellipsis;
}
.mailbox-attachment-name {
    white-space: nowrap;
    overflow: hidden !important;
    text-overflow: ellipsis;
}

.mailbox-attachments {
    justify-content: center;
    flex-wrap: wrap;
}
</style>
