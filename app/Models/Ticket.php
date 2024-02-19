<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_soporte_id', 'prioridad', 'asunto', 'descripcion', 'estado', 'editable', 'fecha_registro', 'envio',
        'user_id', 'eliminado'
    ];

    protected $with = ['archivos', 'solucion_tickets', 'user.informacion_usuario'];

    public function tipo_soporte()
    {
        return $this->belongsTo(TipoSoporte::class, 'tipo_soporte_id');
    }

    public function solucion_tickets()
    {
        return $this->hasMany(SolucionTicket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function archivos()
    {
        return $this->morphMany(ArchivosTicket::class, 'archivo', 'modelo_tipo', 'modelo_id');
    }

    public function getFechaRegistroAttribute($value)
    {
        return date('Y-m-d', strtotime($value));
    }

    // METODOS
    public function registros()
    {
        return $this->solucion_tickets()->where('tipo_incidencia', 'REGISTRO');
    }
    public function soluciones()
    {
        return $this->solucion_tickets()->where('tipo_incidencia', 'SOLUCION');
    }

    public static function getRespuestaAutomatica($pregunta)
    {
        $respuesta = null;
        if (strpos("Mi computadora no enciende", $pregunta) !== false) {
            $respuesta = "Verifica que esté conectada a una fuente de energía, intenta encenderla con otro cable de alimentación y asegúrate de que el interruptor de la fuente de alimentación esté en la posición correcta. Si sigue sin encender, podría haber un problema con la fuente de poder o la placa madre";
        }
        if (strpos("Mi computadora está lenta", $pregunta) !== false) {
            $respuesta = "Puedes intentar desfragmentar el disco duro, eliminar programas innecesarios, aumentar la memoria RAM o considerar la posibilidad de cambiar a un disco de estado sólido (SSD). También es útil realizar un escaneo en busca de malware y asegurarse de que los controladores estén actualizados";
        }
        if (strpos("Por qué mi conexión a Internet es inestable", $pregunta) !== false) {
            $respuesta = "Puede deberse a problemas con el router, interferencias de otros dispositivos, o incluso problemas con el proveedor de servicios. Intenta reiniciar el router, cambiar de canal Wi-Fi, o contactar a tu proveedor de servicios de Internet para obtener asistencia";
        }
        if (strpos("Mi impresora no imprime correctamente", $pregunta) !== false) {
            $respuesta = "Verifica que haya suficiente papel y tinta/toner. Actualiza los controladores de la impresora, limpia los cabezales y asegúrate de que no haya atascos de papel. Si el problema persiste, considera reinstalar la impresora";
        }
        if (strpos("He perdido archivos importantes. ¿Cómo puedo recuperarlos?", $pregunta) !== false) {
            $respuesta = "Intenta utilizar software de recuperación de datos. También verifica la papelera de reciclaje o la carpeta de archivos temporales. En el futuro, realiza copias de seguridad periódicas para evitar la pérdida de datos";
        }
        if (strpos("Mi computadora muestra una pantalla azul (BSOD)", $pregunta) !== false) {
            $respuesta = "La pantalla azul indica un error del sistema. Puede ser causado por problemas de hardware, controladores incompatibles o fallas en el sistema operativo. Anota el código de error y busca información específica sobre el problema en línea o consulta a un profesional de soporte técnico";
        }
        if (strpos("Mi software no se instala correctamente. ¿Cómo puedo solucionarlo?", $pregunta) !== false) {
            $respuesta = "Asegúrate de que el software sea compatible con tu sistema operativo. Intenta desinstalar y reinstalar el programa. Si el problema persiste, verifica si hay conflictos con otros programas o contacta al soporte técnico del fabricante";
        }
        if (strpos("Mi computadora se reinicia de forma inesperada. ¿Cuál podría ser la causa?", $pregunta) !== false) {
            $respuesta = "Este problema podría estar relacionado con sobrecalentamiento, problemas de fuente de alimentación, o incluso malware. Verifica la temperatura del sistema, asegúrate de que el ventilador esté funcionando correctamente y realiza un escaneo antivirus. Si el problema persiste, considera revisar la fuente de poder o buscar asistencia profesional.";
        }
        if (strpos("¿Cómo puedo solucionar problemas de audio en mi computadora?", $pregunta) !== false) {
            $respuesta = "Asegúrate de que los altavoces o auriculares estén conectados correctamente. Verifica el control de volumen y los ajustes de audio en el sistema operativo. Actualiza los controladores de audio y asegúrate de que no haya conflictos con otros dispositivos";
        }
        if (strpos("Mi computadora no reconoce un dispositivo USB. ¿Cómo puedo resolver esto?", $pregunta) !== false) {
            $respuesta = "Prueba conectando el dispositivo a otro puerto USB. Asegúrate de que el dispositivo esté correctamente enchufado y funcione en otra computadora. Intenta desinstalar y reinstalar los controladores USB. Si nada funciona, podría ser un problema de hardware en la computadora o el dispositivo.";
        }
        if (strpos("Mi sistema operativo muestra mensajes de error al iniciar. ¿Qué debo hacer?", $pregunta) !== false) {
            $respuesta = "Anota el mensaje de error y realiza una búsqueda en línea para obtener información sobre el problema específico. Intenta iniciar en modo seguro y desinstalar recientemente instalado o actualizado software que pueda estar causando conflictos. En casos extremos, puedes considerar la reinstalación del sistema operativo.";
        }
        if (strpos("¿Cómo puedo proteger mi computadora contra virus y malware?", $pregunta) !== false) {
            $respuesta = "Utiliza software antivirus actualizado y realiza escaneos periódicos. Mantén el sistema operativo y todos los programas actualizados. Evita hacer clic en enlaces sospechosos o descargar archivos de fuentes no confiables. Realiza copias de seguridad regulares para proteger tus datos en caso de un ataque.";
        }
        if (strpos("Mi pantalla muestra píxeles muertos o distorsiones. ¿Cómo puedo resolver este problema?", $pregunta) !== false) {
            $respuesta = "Verifica si el cable de conexión entre la computadora y el monitor está bien conectado. Si el problema persiste, prueba con otro cable o puerto. También puedes actualizar los controladores de la tarjeta gráfica y realizar pruebas con otro monitor para descartar problemas de hardware.";
        }
        if (strpos("Mi laptop se calienta mucho. ¿Cómo puedo evitar el sobrecalentamiento?", $pregunta) !== false) {
            $respuesta = "Asegúrate de que las ventilaciones estén limpias y sin obstrucciones. Utiliza una base de enfriamiento para elevar la laptop y permitir un mejor flujo de aire. Cierra programas innecesarios que puedan estar consumiendo recursos y considera la aplicación de pasta térmica en el procesador si es necesario.";
        }
        if (strpos("Mi ratón o teclado inalámbrico no responde. ¿Cómo puedo solucionarlo?", $pregunta) !== false) {
            $respuesta = "Verifica si los dispositivos tienen batería y si están encendidos. Intenta volver a emparejarlos con su receptor USB. Si eso no funciona, prueba con otro puerto USB o reinicia la computadora. Si es un problema recurrente, puede ser necesario actualizar los controladores o reemplazar los dispositivos.";
        }
        if (strpos("Mi conexión Wi-Fi es lenta o intermitente. ¿Cómo puedo mejorarla?", $pregunta) !== false) {
            $respuesta = "Ubica el router en un lugar central y libre de obstrucciones. Actualiza el firmware del router y ajusta el canal Wi-Fi para evitar interferencias. También puedes considerar la actualización de tu tarjeta de red o utilizar un extensor de alcance para mejorar la cobertura.";
        }
        if (strpos('Mi sistema operativo muestra mensajes de error de "falta de memoria". ¿Cómo puedo abordar esto?', $pregunta) !== false) {
            $respuesta = "Cierra programas innecesarios que consuman demasiada memoria. Considera agregar más memoria RAM a tu computadora si es posible. También puedes identificar y solucionar posibles fugas de memoria mediante la supervisión del Administrador de tareas o Activity Monitor.";
        }
        if (strpos('Mi software se bloquea o se congela con frecuencia. ¿Qué debo hacer?', $pregunta) !== false) {
            $respuesta = "Asegúrate de que el software esté actualizado. Verifica si hay actualizaciones de los controladores de hardware relacionados. Si el problema persiste, intenta desinstalar y reinstalar el software. También puedes revisar los registros de errores para obtener pistas sobre la causa del bloqueo.";
        }

        return $respuesta;
    }
}
