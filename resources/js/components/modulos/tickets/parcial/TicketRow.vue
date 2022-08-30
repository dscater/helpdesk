<template>
    <tr
        :class="{
            pendiente: ticket.estado == 'PENDIENTE',
            solucionado: ticket.estado == 'SOLUCIONADO',
        }"
    >
        <td>
            <div
                class="icheck-primary"
                v-if="ticket.user_id == user.id || user.tipo == 'ADMINISTRADOR'"
            >
                <input type="checkbox" value="" :checked="check" />
                <label
                    @click="
                        check = !check;
                        emiteSeleccion();
                    "
                ></label>
            </div>
            <div class="icheck-primary" v-else>
                <input type="checkbox" value="" disabled />
                <label></label>
            </div>
        </td>
        <td class="mailbox-star" v-html="spanPrioridad"></td>
        <td class="mailbox-name">
            <router-link
                v-if="ticket.eliminado == 0"
                :to="{ name: 'tickets.incidencias', params: { id: ticket.id } }"
                >{{ ticket.user.full_name }}</router-link
            >
            <template v-else>{{ ticket.user.full_name }} </template>
        </td>
        <td class="mailbox-subject">
            <b>{{ ticket.asunto }}</b> -
            <small
                ><i>{{ ticket.tipo_soporte.nombre }}</i></small
            >
            - {{ descripcionCorta }}
            <i
                class="fas fa-check text-primary"
                v-if="ticket.envio == 'RECIBIDO'"
            ></i>
        </td>
        <td class="mailbox-attachment">
            <i class="fas fa-paperclip" v-if="ticket.archivos.length > 0"></i>
        </td>
        <td class="mailbox-date">
            {{ formatoFecha(ticket.fecha_registro) }}
        </td>
    </tr>
</template>

<script>
export default {
    props: ["ticket", "seleccionado"],
    data() {
        return {
            user: JSON.parse(localStorage.getItem("user")),
            check: false,
        };
    },
    watch: {
        seleccionado: function (newVal) {
            if (this.user.tipo == "ADMINISTRADOR") {
                this.check = newVal;
                this.emiteSeleccion();
            } else {
                if (this.user.id == this.ticket.user_id) {
                    this.check = newVal;
                    this.emiteSeleccion();
                }
            }
        },
    },
    computed: {
        descripcionCorta() {
            if (this.ticket.descripcion.length > 40) {
                return this.ticket.descripcion.substr(0, 40) + "...";
            } else {
                return this.ticket.descripcion;
            }
        },
        spanPrioridad() {
            let tipo = "danger";
            if (this.ticket.prioridad == "MEDIO") {
                tipo = "warning";
            }
            if (this.ticket.prioridad == "BAJO") {
                tipo = "primary";
            }
            return `<span class="badge bg-${tipo}">${this.ticket.prioridad}</span>`;
        },
    },
    methods: {
        formatoFecha(date) {
            return this.$moment(String(date)).format("DD [de] MMMM [del] YYYY");
        },
        emiteSeleccion() {
            if (this.check) {
                this.$emit("event_seleccion", this.ticket.id, true);
            } else {
                this.$emit("event_seleccion", this.ticket.id, false);
            }
        },
    },
};
</script>

<style>
.pendiente {
    background: yellow;
}
.solucionado {
    background: rgb(174, 240, 119);
}
</style>
