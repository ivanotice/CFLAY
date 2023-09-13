<?php
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $imagen = $_FILES['imagen'];

    // Verificar que se haya seleccionado una imagen válida
    if($imagen['error'] === UPLOAD_ERR_OK) {
        $apiKey = "TU_API_KEY"; // Reemplaza esto con tu propia API key de imgbb

        // Configurar la solicitud a la API de imgbb
        $ch = curl_init();
        $url = 'https://api.imgbb.com/1/upload';
        $cfile = new CURLFile($imagen['tmp_name'], $imagen['type'], $imagen['name']);
        $data = array('key' => $apiKey, 'image' => $cfile, 'name' => $titulo);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Realizar la solicitud a la API de imgbb
        $response = curl_exec($ch);
        curl_close($ch);

        // Decodificar la respuesta JSON
        $result = json_decode($response, true);

        // Verificar si la subida fue exitosa y obtener la URL de la imagen
        if($result['status'] === 200) {
            $imageUrl = $result['data']['url'];
            echo "¡Imagen subida exitosamente! La URL de la imagen es: <a href='$imageUrl'>$imageUrl</a>";
        } else {
            echo "Error al subir la imagen: " . $result['error']['message'];
        }
    } else {
        echo "Error al subir la imagen: " . $imagen['error'];
    }
}
?>
