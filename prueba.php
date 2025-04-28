<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Probar Subida de Imagen con TinyMCE</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!-- Incluir TinyMCE desde el CDN -->
    <script src="https://cdn.tiny.cloud/1/ot3ylpkxqs7181mw1rbulmqgqhj5d76f3nj5uu9q23e6se4i/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

    <div class="container mt-5">
        <h1>Probar Subida de Imagen con TinyMCE</h1>
        <form action="upload_image.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="contenido" class="form-label">Escribe tu artículo</label>
                <textarea class="form-control" id="contenido" name="contenido" rows="10"></textarea>
            </div>
        </form>
    </div>

    <!-- Configuración de TinyMCE -->
    <script>
       tinymce.init({
  selector: 'textarea',  // Aplica a todos los textareas
  plugins: 'image code',  // Plugins de imagen y código
  toolbar: 'undo redo | link image | code',  // Barra de herramientas
  image_title: true,  // Habilitar título de imagen
  automatic_uploads: true,  // Subir automáticamente las imágenes
  images_upload_url: 'upload_image.php',  // Aquí va la URL del script que maneja la subida de imágenes
  file_picker_types: 'image',  // Solo permitir imágenes
  file_picker_callback: (cb, value, meta) => {
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');  // Solo imágenes

    input.addEventListener('change', (e) => {
      const file = e.target.files[0];  // El archivo seleccionado

      const reader = new FileReader();
      reader.addEventListener('load', () => {
        const id = 'blobid' + (new Date()).getTime();  // ID único para la imagen
        const blobCache =  tinymce.activeEditor.editorUpload.blobCache;
        const base64 = reader.result.split(',')[1];  // Imagen en base64
        const blobInfo = blobCache.create(id, file, base64);

        blobCache.add(blobInfo);

        // Insertar la imagen en el editor
        cb(blobInfo.blobUri(), { title: file.name });
      });
      reader.readAsDataURL(file);  // Leer el archivo como base64
    });

    input.click();  // Abre el selector de archivos
  },
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'  // Estilo del contenido
});

    </script>
</body>
</html>
