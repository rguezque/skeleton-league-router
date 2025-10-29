<?php declare(strict_types = 1);

use Core\App;

if(!function_exists('env')) {
    /**
     * Obtiene el valor de una variable de entorno. Si la variable no está definida, devuelve el valor por defecto proporcionado.
     * 
     * @param string $key La clave de la variable de entorno.
     * @param mixed $default (Opcional) Valor por defecto si la variable no está definida.
     * @return mixed El valor de la variable de entorno o el valor por defecto.
     */
    function env(string $key, mixed $default = null): mixed {
        return $_ENV[trim($key)] ?? $default;
    }
}

if(!function_exists('pipe')) {
    /**
     * Devuelve el resultado de ejecutar una secuencia de funciones en pipeline sobre un valor específico.
     * Ej. `pipe('strtolower', 'ucwords', 'trim')('  jOHn dOE  ')` devuelve 'John Doe'
     * 
     * @param array<Closure> $fns Listado de funciones a ejecutar en cadena
     * @return mixed El resultado de aplicar las funciones sobre un valor
     */
    function pipe(...$fns) {
        return fn($initial_value) => 
            array_reduce($fns, function($accumulator, $func) {
                return call_user_func($func, $accumulator);
            }, $initial_value);
    }
}

if(!function_exists('sanitize_input')) {
    /**
     * Sanitiza recursivamente un valor o un arreglo de valores.
     *
     * Aplica el filtro FILTER_SANITIZE_FULL_SPECIAL_CHARS para codificar
     * caracteres especiales HTML y prevenir ataques XSS.
     *
     * @param mixed $data La variable (string o array) a sanitizar.
     * @return mixed Los datos sanitizados.
     */
    function sanitize_input($data) {
        if (is_array($data)) {
            // Si es un arreglo, aplica la función a cada elemento.
            return array_map('sanitize_input', $data);
        }

        if (is_string($data)) {
            // Usa FILTER_SANITIZE_FULL_SPECIAL_CHARS para codificar caracteres especiales HTML,
            // lo que es la principal defensa contra XSS.
            return filter_var($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }

        // Devuelve otros tipos de datos (como números enteros, booleanos) sin modificar,
        // ya que no contienen scripts.
        return $data;
    }
}

if(!function_exists('generate_csrf_token')) {
    /**
     * Genera el token CSRF, lo guarda en la sesión y lo devuelve.
     * Si ya existe un token en la sesión, lo devuelve.
     * 
     * @return string El token CSRF actual.
     */
    function generate_csrf_token() {
        // Verifica si el token ya existe en la sesión
        if (!isset($_SESSION['csrf_token'])) {
            // Genera un token criptográficamente seguro
            // bin2hex convierte bytes aleatorios en una cadena hexadecimal
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
        }

        return $_SESSION['csrf_token'];
    }
}

if(!function_exists('csrf_field')) {
    /**
     * Genera el campo input hidden con el token CSRF para el formulario.
     * 
     * @return void Imprime directamente el campo HTML.
     */
    function csrf_field() {
        $token = generate_csrf_token();
        // Usa htmlspecialchars para asegurar que el token se imprima de forma segura
        echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}

if(!function_exists('validate_csrf_token')) {
    /**
     * Valida el token enviado en la solicitud (`$_POST` o `$_GET`) contra el token de la sesión.
     * 
     * @param Parameters $request El objeto de la solicitud. Normalmente `$_POST` (`Request::getBody()`) o `$_GET` (`Request::getQuery()`).
     * @return bool True si el token es válido, false si no lo es o falta.
     */
    function validate_csrf_token() {
        // 1. Verificar que el token de la sesión existe
        if (!isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token'])) {
            return false; // No hay token de sesión para validar
        }
        
        // 2. Verificar que el token de la solicitud existe
        if (!isset($_REQUEST['csrf_token']) || empty($_REQUEST['csrf_token'])) {
            return false; // El formulario no envió el token
        }
    
        // 3. Comparar de forma segura
        // hash_equals previene ataques de sincronización (timing attacks)
        if (hash_equals($_SESSION['csrf_token'], $_REQUEST['csrf_token'])) {
            // Opcional y recomendado: Consumir el token después de usarlo
            unset($_SESSION['csrf_token']);
            return true;
        }
        
        return false; // Los tokens no coinciden
    }
}

if(!function_exists('resources')) {
    /**
     * Imprime las etiquetas `<link>` y `<script>` para los recursos especificados relativos a /public/static. 
     * Si los scripts tienen la extensión .module.js, se les añade el atributo type="module".
     * 
     * @param array $styles Array de rutas a hojas de estilo CSS.
     * @param array $scripts Array de rutas a archivos JavaScript.
     * @return void
     */
    function resources(array $styles = [], array $scripts = []) : void {
        if (empty($styles) && empty($scripts)) {
            return; // No hay recursos que cargar
        }

        $static_url = App::getStaticDirectory();

        foreach ($styles as $style) {
            $style = DIRECTORY_SEPARATOR . ltrim($style, '/\\'); // Asegura que la ruta sea relativa al basepath
            echo '<link rel="stylesheet" href="' . $static_url . htmlspecialchars($style, ENT_QUOTES) . '">' . PHP_EOL;
        }

        foreach ($scripts as $option => $script) {
            $script = DIRECTORY_SEPARATOR . ltrim($script, '/\\'); // Asegura que la ruta sea relativa al basepath
            $is_module = preg_match('/\.module\.js$/i', $script);
            $type_attr = $is_module ? ' type="module"' : '';
            $attrs = ''; // Por defecto, sin atributos adicionales
            if(!is_numeric($option)) {
                $attrs = ' '.$option;
            }
            echo '<script src="' . $static_url . htmlspecialchars($script, ENT_QUOTES) . '"' . $type_attr . $attrs .'></script>' . PHP_EOL;
        }
    }
}

?>