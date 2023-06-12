# HTTP API

## Acerca de los datos enviados

Los datos enviados pueden ser una estructura JSON, o bien,
datos enviados por un formulario con archivos (`multipart/form-data`).

Para enviar el certificado y llave privada vía JSON, codifique en Base64 el contenido de los archivos
y agregue el prefijo `base64:`. De esta forma se podrán interpretar los datos correctamente.

## Acerca de la respuesta

La respuesta es la mayoría de las veces de tipo JSON (excepto en el caso de descarga).
Contiene el objeto de respuesta del método invocado según la librería `phpcfdi/sat-ws-descarga-masiva`.

La respuesta dependerá de si se enviaron datos correctos (código 400),
si ocurrió un error al consumir el servicio del SAT (código 500),
o si se pudo obtener una respuesta del consumo del servicio del SAT (código 200).
Se devuelve un código 200 aun cuando la respuesta del servicio del SAT sea un error,
porque lo que se evalúa es el consumo del servicio y no la respuesta obtenida.

Datos a recibir en caso de error (`HTTP 400`):

- `error`: Mensaje de error.

Datos a recibir en caso de error de procesamiento (`HTTP 500`):

- `error`: Mensaje de error.

## `GET /complements/cfdi`

Obtiene un listado de código y leyenda de los complementos de CFDI aceptados.

Datos recibidos en caso correcto (`HTTP 200`):

```json5
{
    "cartaporte10": "Carta Porte 1.0",
    "cartaporte20": "Carta Porte 2.0",
    // etc...
}
```

## `GET /complements/retention`

Obtiene un listado de código y leyenda de los complementos de CFDI de Retenciones aceptados.

Datos recibidos en caso correcto (`HTTP 200`):

```json5
{
    "cartaporte10": "Carta Porte 1.0",
    "cartaporte20": "Carta Porte 2.0",
    // etc...
}
```

## `POST /query-by-filters`

Enviar una consulta por filtros de búsqueda.

Datos enviados:

- `certificate`: Certificado FIEL a utilizar.
- `privateKey`: Llave privada del certificado, necesaria para firmar.
- `passphrase`: Clave de la llave privada.
- `token{created, expires, value}`: Token de autenticación del SAT para ser reusado.
- `requestType`: Tipo de solicitud (opcional): **`metadata`** o `xml`.
- `since`: Fecha y hora completa o estampa de tiempo del punto inicial de la consulta.
- `until`: Fecha y hora completa o estampa de tiempo del punto final de la consulta.
- `downloadType`: Tipo de descarga (opcional): **`issued`** o `received`.
- `documentType`: Tipo de documento (opcional): **`undefined`**, `ingreso`, `egreso`, `traslado`, `nomina` o `pago`.
- `complemento`: Filtrado a un complemento (opcional): **`undefined`** o una clave de complemento.
- `documentStatus`: Estado de documentos (opcional): **`undefined`**, `active` o `cancelled`.
- `rfcOnBehalf`: Especificación de RFC a cuenta de terceros (opcional).
- `rfcMatch`: Especificación de RFC de contraparte (opcional).

Datos recibidos en caso correcto (`HTTP 200`):

```json5
{
    "status": { // Código y mensaje de estado de la petición
        "code": 5000, 
        "message": "Solicitud aceptada",
    },
    "requestId": "CEE4BE01-8567-4DEB-8421-ADD60F0B3DAC", // contiene el identificador de la solicitud
}
```

## `POST /query-by-uuid`

Enviar una consulta por UUID.

Datos enviados:

- `certificate`: Certificado FIEL a utilizar.
- `privateKey`: Llave privada del certificado, necesaria para firmar.
- `passphrase`: Clave de la llave privada.
- `token{created, expires, value}`: Token de autenticación del SAT para ser reusado.
- `requestType`: Tipo de solicitud: `xml` o `metadata`.
- `uuid`: UUID del CFDI.

Datos recibidos en caso correcto (`HTTP 200`):

```json5
{
    "status": { // Código y mensaje de estado de la petición
        "code": 5000, 
        "message": "Solicitud aceptada",
    },
    "requestId": "CEE4BE01-8567-4DEB-8421-ADD60F0B3DAC", // contiene el identificador de la solicitud
}
```

## `POST /verify`

Realizar la verificación de una consulta

Datos enviados:

- `certificate`: Certificado FIEL a utilizar.
- `privateKey`: Llave privada del certificado, necesaria para firmar.
- `passphrase`: Clave de la llave privada.
- `token{created, expires, value}`: Token de autenticación del SAT para ser reusado.
- `requestId`: Identificador de la solicitud.

Datos recibidos en caso correcto (`HTTP 200`):

```json5
{
    "status": { // Código y mensaje de estado de la petición
        "code": 5000, 
        "message": "Solicitud aceptada",
    },
    "codeRequest": { // CodigoEstadoSolicitud: Estado de la solicitud de la descarga
        "value": "5000",
        "message": "Solicitud recibida con éxito",
    },
    "statusRequest": { // EstadoSolicitud: Estado de la solicitud de descarga
        "value": 3,
        "message": "Terminada"
    },
    "numberCfdis": 1234, // Número de registros que contiene la solicitud
    "packagesIds": [ // Listado de paquetes generados
        "CEE4BE01-8567-4DEB-8421-ADD60F0B3DAC-1"
    ],
}
```

## `POST /download`

Realizar la descarga de un paquete.

Datos enviados:

- `certificate`: Certificado FIEL a utilizar.
- `privateKey`: Llave privada del certificado, necesaria para firmar.
- `passphrase`: Clave de la llave privada.
- `token{created, expires, value}`: Token de autenticación del SAT para ser reusado.
- `packageId`: Identificador del paquete.

Datos recibidos en caso correcto (`HTTP 200`):

La respuesta es específicamente el archivo de descarga y no una respuesta de tipo `application/json`.

Datos recibidos en caso correcto (`HTTP 400`):

Esta respuesta se da cuando se pudo consumir el servicio, pero el paquete no se ha descargado.

```json5
{
    "status": { // Código y mensaje de estado de la petición
        "code": 5000, 
        "message": "Solicitud aceptada",
    },
}
```
