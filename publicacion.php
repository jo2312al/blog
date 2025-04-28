<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escribir Nuevo Artículo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/ot3ylpkxqs7181mw1rbulmqgqhj5d76f3nj5uu9q23e6se4i/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        #dropzone {
            margin-bottom: 3rem;
        }
        .dropzone {
            border: 2px dashed #0087F7;
            border-radius: 5px;
            background: white;
        }
        .dropzone .dz-message {
            text-align: center;
            margin: 2em 0;
        }
        .dropzone .dz-message span.note {
            font-size: 0.8em;
            font-weight: 200;
            display: block;
            margin-top: 0.4em;
            color: #777;
        }
        .dropzone .dz-preview.dz-image-preview {
            background: transparent;
        }
    </style>
</head>
<body>
    <?php
    require_once 'vendor/autoload.php';
    DB::$user = 'root';
    DB::$password = '';
    DB::$dbName = 'canacintra';
    DB::$host = 'localhost';
    DB::$encoding = 'utf8';
    $categorias = DB::query("SELECT id, nombre FROM categoria");
    ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <!-- Tu navbar aquí -->
    </nav>

    <!-- Formulario -->
    <div class="container form-container">
        <h2 class="mb-4">Escribir nuevo artículo</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Escribir Artículo</li>
            </ol>
        </nav>

        <form method="post" action="guardar_publicacion.php" id="publicacion-form">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Enter a descriptive title">
            </div>

            <div class="mb-3">
                <label for="categoria" class="form-label">Categoría</label>
                <select class="form-select" id="categoria" name="fk_categoria">
                <option value="">Select a category</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['id'] ?>"><?= $categoria['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Imágenes</label>
                <section>
                    <div id="dropzone">
                        <div class="dropzone needsclick" id="demo-upload">
                            <div class="dz-message needsclick">
                                Suelta los archivos aquí o haz clic para subir.<br>
                                <span class="note needsclick">Sube una o más imágenes de alta calidad (tamaño recomendado: 1200x630px).</span>
                            </div>
                        </div>
                    </div>
                </section>
                <input type="hidden" name="archivo_ids" id="archivo_ids" value="">
            </div>

            <div class="mb-3">
                <label for="contenido" class="form-label">Contenido</label>
                <textarea class="form-control" id="contenido" name="contenido" rows="5" placeholder="Write your article content here..."></textarea>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button type="submit" name="accion" value="publicar" class="btn btn-purple">Publicar</button>
                    <button type="submit" name="accion" value="borrador" class="btn btn-light-purple">Borrador</button>
                    <button type="button" class="btn btn-link">Preview</button>
                </div>
                <div>
                    <a href="#" class="text-muted">tag</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <!-- Tu footer aquí -->
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'image code',
            toolbar: 'undo redo | link image | code',
            image_title: true,
            automatic_uploads: true,
            images_upload_url: 'upload_image.php',
            file_picker_types: 'image',
            file_picker_callback: (cb, value, meta) => {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.addEventListener('change', (e) => {
                    const file = e.target.files[0];
                    const reader = new FileReader();
                    reader.addEventListener('load', () => {
                        const id = 'blobid' + (new Date()).getTime();
                        const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        const base64 = reader.result.split(',')[1];
                        const blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        cb(blobInfo.blobUri(), { title: file.name });
                    });
                    reader.readAsDataURL(file);
                });
                input.click();
            },
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });

        console.log('Dropzone:', typeof Dropzone);

        try {
            const myDropzone = new Dropzone('#demo-upload', {
                url: 'upload_dropzone.php',
                paramName: 'file',
                maxFilesize: 5,
                acceptedFiles: 'image/*',
                autoProcessQueue: true,
                addRemoveLinks: true,
                timeout: 30000,
                init: function() {
                    console.log('Dropzone inicializado');
                    const archivoIds = [];

                    this.on('addedfile', (file) => {
                        console.log('Archivo añadido:', file.name, 'Tamaño:', file.size);
                    });

                    this.on('sending', (file, xhr, formData) => {
                        console.log('Enviando archivo:', file.name);
                    });

                    this.on('success', (file, response) => {
                        console.log('Respuesta del servidor (cruda):', response);
                        console.log('Tipo de respuesta:', typeof response);
                        let parsedResponse = response;
                        if (typeof response === 'string') {
                            try {
                                parsedResponse = JSON.parse(response);
                                console.log('Respuesta parseada:', parsedResponse);
                            } catch (e) {
                                console.error('Error al parsear JSON:', e, 'Respuesta:', response);
                                alert('Error al subir el archivo: Respuesta no es JSON válido');
                                this.removeFile(file);
                                return;
                            }
                        }
                        if (parsedResponse && parsedResponse.success) {
                            archivoIds.push(parsedResponse.archivo_id);
                            document.querySelector('#archivo_ids').value = archivoIds.join(',');
                            console.log('IDs de archivos:', archivoIds);
                        } else {
                            console.error('Error en la respuesta:', parsedResponse);
                            let errorMsg = 'Respuesta inválida';
                            if (parsedResponse && parsedResponse.error) {
                                errorMsg = parsedResponse.error;
                            }
                            alert('Error al subir el archivo: ' + errorMsg);
                            this.removeFile(file);
                        }
                    });

                    this.on('error', (file, errorMessage, xhr) => {
                        console.error('Error al subir archivo:', errorMessage, xhr);
                        let message = 'Error desconocido';
                        if (typeof errorMessage === 'string') {
                            message = errorMessage;
                        } else if (errorMessage && errorMessage.error) {
                            message = errorMessage.error;
                        } else if (xhr) {
                            message = 'Error del servidor: ' + xhr.status + ' ' + xhr.statusText;
                        }
                        alert('Error al subir el archivo: ' + message);
                        this.removeFile(file);
                    });

                    this.on('removedfile', (file) => {
                        console.log('Archivo eliminado:', file.name);
                    });

                    document.querySelector('#publicacion-form').addEventListener('submit', (e) => {
                        const titulo = document.querySelector('#titulo').value;
                        const fk_categoria = document.querySelector('#categoria').value;
                        const contenido = document.querySelector('#contenido').value;
                        const archivo_ids = document.querySelector('#archivo_ids').value;

                        if (!titulo || !fk_categoria || !contenido || !archivo_ids) {
                            e.preventDefault();
                            alert('Todos los campos son obligatorios, incluyendo al menos una imagen.');
                        } else {
                            console.log('Enviando formulario con archivo_ids:', archivo_ids);
                        }
                    });
                }
            });
        } catch (error) {
            console.error('Error al inicializar Dropzone:', error);
            alert('Error al inicializar Dropzone: ' + error.message);
        }
    </script>
</body>
</html>