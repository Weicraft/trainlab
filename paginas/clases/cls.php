<?php

class Master
{
    //Base de datos
    protected static $db;

    //Creación del arreglo de errores para las validaciones
    protected static $errores = [];

    //Definir la conexión a la BD
    public static function setDB($database)
    {
        self::$db = $database;
    }

    //U -> Sincroniza el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    //Traer los datos de la BD
    public static function consultarSQL($qry)
    {
        //Consultar la base de datos
        $result = self::$db->query($qry);

        //Iterar la base de datos
        $array = [];
        while ($registro = $result->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }

        //Liberar la memoria
        $result->free();

        //Retornar los resultados
        return $array;
    }

    //Creación del objeto
    protected static function crearObjeto($registro)
    {
        $objeto = new self;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    //Validación de errores
    public static function getErrores()
    {
        return self::$errores;
    }
}

class USERS extends Master
{

    //Arreglo pamodo_fact y U
    protected static $columnaDB = [
        'nombre',
        'usuario',
        'pass',
        'estado_user_activ'
    ];

    //Declarando las variables del objeto
    public $id_user;
    public $nombre;
    public $usuario;
    public $pass;
    public $estado_user_activ;
    public $contar;

    //Construcción del objeto
    public function __construct($args = [])
    {

        $this->id_user = $args['id_user'] ?? '';
        $this->nombre = $args['nombre'] ?? '';
        $this->usuario = $args['usuario'] ?? '';
        $this->pass = $args['pass'] ?? '';
        $this->estado_user_activ = $args['estado_user_activ'] ?? '';
        $this->contar = $args['contar'] ?? '';
    }

    //C -> Guardar los datos
    public function crear($nombre, $usuario, $pass)
    {
        $passwordHash = password_hash($pass, PASSWORD_BCRYPT);

        $qry = "INSERT INTO user (nombre, usuario, pass, estado_user_activ)
        VALUES ('$nombre', '$usuario', '$passwordHash', 'A')";
        self::$db->query($qry);
    }

    //R -> Listar USERS
    public static function listarUser()
    {
        $qry = "SELECT * FROM user WHERE estado_user_activ = 'A' ORDER BY id_user DESC";
        $result = self::consultarSQL($qry);

        return $result;
    }

    //R -> Listar USER por ID
    public static function listarUserId($id_user)
    {
        $qry = "SELECT * FROM user WHERE id_user = '$id_user' AND estado_user_activ = 'A'";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //R -> Listar USER por Usuario
    public static function listarUserUsuario($usuario)
    {
        $qry = "SELECT * FROM user WHERE usuario = '$usuario' AND estado_user_activ = 'A'";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //R -> CONTAR USUARIOS
    public static function contarUsers()
    {
        $qry = "SELECT count(*) as contar from user";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //U -> Actualizar datos del USER:
    public function editUser(
        $id_user,
        $nombre,
        $usuario
    ) {
        $qry = "UPDATE user SET nombre='$nombre', usuario='$usuario' WHERE id_user='$id_user'";
        self::$db->query($qry);
    }

    //U -> Actualizar contraseña del USER:
    public function editPassUser(
        $id_user,
        $pass
    ) {
        $passwordHash = password_hash($pass, PASSWORD_BCRYPT);

        $qry = "UPDATE user SET pass='$passwordHash' WHERE id_user='$id_user'";
        self::$db->query($qry);
    }

    //U -> Eliminar USER:
    public function elimUser($id_user)
    {
        $qry = "UPDATE user SET estado_user_activ = 'D' WHERE id_user='$id_user'";
        self::$db->query($qry);
    }

    //Identificar y unir los atributos de la BD
    public function atributos()
    {
        $atributos = [];
        foreach (self::$columnaDB as $columna) {
            if ($columna === 'id_user') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    //Traer los datos de la BD
    public static function consultarSQL($qry)
    {
        //Consultar la base de datos
        $result = self::$db->query($qry);

        //Iterar la base de datos
        $array = [];
        while ($registro = $result->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }
        //Liberar la memoria
        $result->free();

        //Retornar los resultados
        return $array;
    }

    //Creación del objeto
    protected static function crearObjeto($registro)
    {
        $objeto = new self;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    //Sanitizar los datos
    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }
}

class EMPRESA extends Master
{

    //Arreglo pamodo_fact y U
    protected static $columnaDB = [
        'nombre_empresa',
        'certificador',
        'cargo_certificador',
        'prefijo'
    ];

    //Declarando las variables del objeto
    public $id_empresa;
    public $nombre_empresa;
    public $certificador;
    public $cargo_certificador;
    public $prefijo;

    //Construcción del objeto
    public function __construct($args = [])
    {

        $this->id_empresa = $args['id_user'] ?? '';
        $this->nombre_empresa = $args['nombre_empresa'] ?? '';
        $this->certificador = $args['certificador'] ?? '';
        $this->cargo_certificador = $args['cargo_certificador'] ?? '';
        $this->prefijo = $args['prefijo'] ?? '';
    }

    //R -> Listar EMRPRESA
    public static function listarEmpresa()
    {
        $qry = "SELECT * FROM empresa WHERE id_empresa = '1'";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //U -> Actualizar datos del USER:
    public function editEmpresa(
        $nombre_empresa,
        $certificador,
        $cargo_certificador,
        $prefijo
    ) {
        $qry = "UPDATE empresa SET nombre_empresa='$nombre_empresa', certificador='$certificador', cargo_certificador = '$cargo_certificador', prefijo = '$prefijo'
        WHERE id_empresa='1'";
        self::$db->query($qry);
    }

    //Identificar y unir los atributos de la BD
    public function atributos()
    {
        $atributos = [];
        foreach (self::$columnaDB as $columna) {
            if ($columna === 'id_empresa') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    //Traer los datos de la BD
    public static function consultarSQL($qry)
    {
        //Consultar la base de datos
        $result = self::$db->query($qry);

        //Iterar la base de datos
        $array = [];
        while ($registro = $result->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }
        //Liberar la memoria
        $result->free();

        //Retornar los resultados
        return $array;
    }

    //Creación del objeto
    protected static function crearObjeto($registro)
    {
        $objeto = new self;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    //Sanitizar los datos
    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }
}

class CURSOS extends Master
{

    //Arreglo pamodo_fact y U
    protected static $columnaDB = [
        'titulo_curso',
        'descripcion',
        'tipo_curso',
        'fecha_creacion',
        'fecha_actualizacion',
        'examen',
        'validez_cert'
    ];

    //Declarando las variables del objeto
    public $id_curso;
    public $titulo_curso;
    public $descripcion;
    public $tipo_curso;
    public $fecha_creacion;
    public $fecha_actualizacion;
    public $examen;
    public $estado_curso_activ;
    public $validez_cert;

    //Construcción del objeto
    public function __construct($args = [])
    {

        $this->id_curso = $args['id_curso'] ?? '';
        $this->titulo_curso = $args['titulo_curso'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->tipo_curso = $args['tipo_curso'] ?? '';
        $this->fecha_creacion = $args['fecha_creacion'] ?? '';
        $this->fecha_actualizacion = $args['fecha_actualizacion'] ?? '';
        $this->examen = $args['examen'] ?? '';
        $this->estado_curso_activ = $args['estado_curso_activ'] ?? '';
        $this->validez_cert = $args['validez_cert'] ?? '';
    }

    //C -> Guardar los datos
    public function crear($titulo_curso, $descripcion, $tipo_curso, $fecha_creacion, $examen, $validez_cert)
    {
        $qry = "INSERT INTO cursos (titulo_curso, descripcion, tipo_curso, fecha_creacion, fecha_actualizacion, examen, estado_curso_activ, validez_cert)
        VALUES ('$titulo_curso', '$descripcion', '$tipo_curso', '$fecha_creacion', NULL, '$examen', 'A', '$validez_cert')";
        self::$db->query($qry);
    }

    //R -> Listar CURSOS
    public static function listarCursos()
    {
        $qry = "SELECT * FROM cursos WHERE estado_curso_activ = 'A' ORDER BY id_curso DESC";
        $result = self::consultarSQL($qry);

        return $result;
    }

    //R -> Listar CURSO por ID
    public static function listarCursoId($id_curso)
    {
        $qry = "SELECT * FROM cursos WHERE id_curso = '$id_curso' AND estado_curso_activ = 'A'";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //R -> Listar CURSOS
    public static function BuscarCursos($texto)
    {
        $qry = "SELECT * FROM cursos WHERE titulo_curso LIKE '%$texto%' AND estado_curso_activ = 'A' ORDER BY id_curso DESC";
        $result = self::consultarSQL($qry);

        return $result;
    }

    //U -> Actualizar datos del CURSO:
    public function editCurso(
        $id_curso,
        $titulo_curso,
        $descripcion,
        $fecha_creacion,
        $tipo_curso,
        $examen,
        $validez_cert
    ) {
        $qry = "UPDATE cursos SET titulo_curso='$titulo_curso', descripcion='$descripcion', fecha_creacion='$fecha_creacion', tipo_curso='$tipo_curso', 
        examen = '$examen', validez_cert = '$validez_cert' WHERE id_curso='$id_curso'";
        self::$db->query($qry);
    }

    //U -> Modificar fecha de actualizacion del CURSO:
    public function actualizarCurso($id_curso, $fecha_actualizacion)
    {
        $qry = "UPDATE cursos SET fecha_actualizacion='$fecha_actualizacion' WHERE id_curso='$id_curso'";
        self::$db->query($qry);
    }

    //U -> Eliminar CURSO:
    public function elimCurso($id_curso)
    {
        $qry = "UPDATE cursos SET estado_curso_activ='D' WHERE id_curso='$id_curso'";
        self::$db->query($qry);
    }

    //Identificar y unir los atributos de la BD
    public function atributos()
    {
        $atributos = [];
        foreach (self::$columnaDB as $columna) {
            if ($columna === 'id_curso') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    //Traer los datos de la BD
    public static function consultarSQL($qry)
    {
        //Consultar la base de datos
        $result = self::$db->query($qry);

        //Iterar la base de datos
        $array = [];
        while ($registro = $result->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }
        //Liberar la memoria
        $result->free();

        //Retornar los resultados
        return $array;
    }

    //Creación del objeto
    protected static function crearObjeto($registro)
    {
        $objeto = new self;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    //Sanitizar los datos
    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }
}

class PARTICIPANTES extends Master
{

    //Arreglo pamodo_fact y U
    protected static $columnaDB = [
        'nombre_particip',
        'apellidos_particip',
        'tipo_doc',
        'num_doc',
        'email_particip',
        'telefono_particip',
        'cargo_particip',
        'estado_particip_activ',
        'activo_aula'
    ];

    //Declarando las variables del objeto
    public $id_particip;
    public $nombre_particip;
    public $apellidos_particip;
    public $tipo_doc;
    public $num_doc;
    public $email_particip;
    public $telefono_particip;
    public $cargo_particip;
    public $estado_particip_activ;
    public $activo_aula;

    //Construcción del objeto
    public function __construct($args = [])
    {

        $this->id_particip = $args['id_particip'] ?? '';
        $this->nombre_particip = $args['nombre_particip'] ?? '';
        $this->apellidos_particip = $args['apellidos_particip'] ?? '';
        $this->tipo_doc = $args['tipo_doc'] ?? '';
        $this->num_doc = $args['num_doc'] ?? '';
        $this->email_particip = $args['email_particip'] ?? '';
        $this->telefono_particip = $args['telefono_particip'] ?? '';
        $this->cargo_particip = $args['cargo_particip'] ?? '';
        $this->estado_particip_activ = $args['estado_particip_activ'] ?? '';
        $this->activo_aula = $args['activo_aula'] ?? '';
    }

    //C -> Guardar los datos
    public function crear($nombre_particip, $apellidos_particip, $tipo_doc, $num_doc, $email_particip, $telefono_particip, $cargo_particip)
    {
        $qry = "INSERT INTO participantes (nombre_particip, apellidos_particip, tipo_doc, num_doc, email_particip, telefono_particip, cargo_particip, estado_particip_activ, activo_aula)
        VALUES ('$nombre_particip', '$apellidos_particip', '$tipo_doc', '$num_doc', '$email_particip', '$telefono_particip', '$cargo_particip', 'A', 'A')";
        self::$db->query($qry);
    }

    //R -> Listar PARTICIPANTES
    public static function listarParticipantes()
    {
        $qry = "SELECT * FROM participantes WHERE estado_particip_activ = 'A' ORDER BY apellidos_particip DESC, nombre_particip DESC";
        $result = self::consultarSQL($qry);

        return $result;
    }

    //R -> Listar PARTICIPANTE por ID
    public static function listarParticipanteId($id_particip)
    {
        $qry = "SELECT * FROM participantes WHERE id_particip = '$id_particip' AND estado_particip_activ = 'A'";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //R -> Buscar PARTICIPANTES por criterio de texto
    public static function BuscarParticip($texto)
    {
        $qry = "SELECT * FROM participantes 
        WHERE ( nombre_particip LIKE '%$texto%' 
        OR apellidos_particip LIKE '%$texto%'
        OR num_doc LIKE '%$texto%'
        OR email_particip LIKE '%$texto%'
        OR telefono_particip LIKE '%$texto%'
        OR cargo_particip LIKE '%$texto%' )
        AND estado_particip_activ = 'A' ORDER BY id_particip DESC";
        $result = self::consultarSQL($qry);

        return $result;
    }

    //U -> Actualizar datos del PARTICIPANTE:
    public function editParticipante(
        $id_particip,
        $nombre_particip,
        $apellidos_particip,
        $tipo_doc,
        $num_doc,
        $email_particip,
        $telefono_particip,
        $cargo_particip
    ) {
        $qry = "UPDATE participantes SET nombre_particip='$nombre_particip', apellidos_particip='$apellidos_particip', tipo_doc='$tipo_doc',
        num_doc='$num_doc', email_particip='$email_particip', telefono_particip='$telefono_particip', cargo_particip='$cargo_particip'
        WHERE id_particip='$id_particip'";
        self::$db->query($qry);
    }

    //U -> Desactivar PARTICIPANTE del aula:
    public function desacAulaParticipante($id_particip)
    {
        $qry = "UPDATE participantes SET activo_aula='D' WHERE id_particip='$id_particip'";
        self::$db->query($qry);
    }

    //U -> Eliminar PARTICIPANTE:
    public function elimParticipante($id_particip)
    {
        $qry = "UPDATE participantes SET estado_particip_activ='D' WHERE id_particip='$id_particip'";
        self::$db->query($qry);
    }

    //Identificar y unir los atributos de la BD
    public function atributos()
    {
        $atributos = [];
        foreach (self::$columnaDB as $columna) {
            if ($columna === 'id_particip') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    //Traer los datos de la BD
    public static function consultarSQL($qry)
    {
        //Consultar la base de datos
        $result = self::$db->query($qry);

        //Iterar la base de datos
        $array = [];
        while ($registro = $result->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }
        //Liberar la memoria
        $result->free();

        //Retornar los resultados
        return $array;
    }

    //Creación del objeto
    protected static function crearObjeto($registro)
    {
        $objeto = new self;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    //Sanitizar los datos
    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }
}

class DOI extends Master
{

    //Arreglo pamodo_fact y U
    protected static $columnaDB = [
        'nom_doc'
    ];

    //Declarando las variables del objeto
    public $id_doc;
    public $nom_doc;

    //Construcción del objeto
    public function __construct($args = [])
    {

        $this->id_doc = $args['id_doc'] ?? '';
        $this->nom_doc = $args['nom_doc'] ?? '';
    }

    //R -> Listar USERS
    public static function listarDoc()
    {
        $qry = "SELECT * FROM doi ORDER BY id_doc";
        $result = self::consultarSQL($qry);

        return $result;
    }

    //R -> Listar USERS
    public static function listarDocId($id_doc)
    {
        $qry = "SELECT * FROM doi WHERE id_doc='$id_doc' ORDER BY id_doc DESC";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //Identificar y unir los atributos de la BD
    public function atributos()
    {
        $atributos = [];
        foreach (self::$columnaDB as $columna) {
            if ($columna === 'id_doc') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    //Traer los datos de la BD
    public static function consultarSQL($qry)
    {
        //Consultar la base de datos
        $result = self::$db->query($qry);

        //Iterar la base de datos
        $array = [];
        while ($registro = $result->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }
        //Liberar la memoria
        $result->free();

        //Retornar los resultados
        return $array;
    }

    //Creación del objeto
    protected static function crearObjeto($registro)
    {
        $objeto = new self;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    //Sanitizar los datos
    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }
}

class CONTENIDOS extends Master
{

    //Arreglo pamodo_fact y U
    protected static $columnaDB = [
        'id_curso',
        'tipo_content',
        'titulo_content',
        'estado_content_activ'
    ];

    //Declarando las variables del objeto
    public $id_content;
    public $id_curso;
    public $tipo_content;
    public $titulo_content;
    public $estado_content_activ;
    public $contarContenidos;

    //Construcción del objeto
    public function __construct($args = [])
    {

        $this->id_content = $args['id_content'] ?? '';
        $this->id_curso = $args['id_curso'] ?? '';
        $this->tipo_content = $args['tipo_content'] ?? '';
        $this->titulo_content = $args['titulo_content'] ?? '';
        $this->estado_content_activ = $args['estado_content_activ'] ?? '';
        $this->contarContenidos = $args['contarContenidos'] ?? '';
    }

    //C -> Guardar los datos
    public function crear($id_curso, $tipo_content, $titulo_content)
    {
        $qry = "INSERT INTO contenidos (id_curso, tipo_content, titulo_content, estado_content_activ)
        VALUES ('$id_curso', '$tipo_content', '$titulo_content', 'A')";
        self::$db->query($qry);
    }

    //R -> Listar CONTENIDOS por CURSO
    public static function listarContenidoCurso($id_curso)
    {
        $qry = "SELECT * FROM contenidos WHERE id_curso = '$id_curso' AND estado_content_activ = 'A' ORDER BY id_content DESC";
        $result = self::consultarSQL($qry);

        return $result;
    }

    //R -> Listar CONTENIDO por ID
    public static function listarContenidoId($id_content)
    {
        $qry = "SELECT * FROM contenidos WHERE id_content = '$id_content' AND estado_content_activ = 'A'";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //R -> Listar último CONTENIDO
    public static function listarUltimoContenido()
    {
        $qry = "SELECT * FROM contenidos ORDER BY id_content DESC LIMIT 1;";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //R -> Contar CONTENIDOS por curso
    public static function contarContenidos($id_curso)
    {
        $qry = "SELECT COUNT(*) AS contarContenidos FROM contenidos WHERE id_curso = '$id_curso' AND estado_content_activ = 'A'";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //U -> Actualizar datos del CONTENIDO:
    public function editContenido(
        $id_content,
        $tipo_content,
        $titulo_content
    ) {
        $qry = "UPDATE contenidos SET tipo_content='$tipo_content', titulo_content='$titulo_content' WHERE id_content='$id_content'";
        self::$db->query($qry);
    }

    //U -> Eliminar CONTENIDO:
    public function elimContenido($id_content)
    {
        $qry = "UPDATE contenidos SET estado_content_activ='D' WHERE id_content='$id_content'";
        self::$db->query($qry);
    }

    //Identificar y unir los atributos de la BD
    public function atributos()
    {
        $atributos = [];
        foreach (self::$columnaDB as $columna) {
            if ($columna === 'id_content') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    //Traer los datos de la BD
    public static function consultarSQL($qry)
    {
        //Consultar la base de datos
        $result = self::$db->query($qry);

        //Iterar la base de datos
        $array = [];
        while ($registro = $result->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }
        //Liberar la memoria
        $result->free();

        //Retornar los resultados
        return $array;
    }

    //Creación del objeto
    protected static function crearObjeto($registro)
    {
        $objeto = new self;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    //Sanitizar los datos
    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }
}

class PROGRESO extends Master
{

    //Arreglo pamodo_fact y U
    protected static $columnaDB = [
        'id_particip',
        'id_content',
        'estado_progress',
        'fecha_hora_inicio',
        'fecha_hora_fin'
    ];

    //Declarando las variables del objeto
    public $id_progress;
    public $id_particip;
    public $id_content;
    public $estado_progress;
    public $fecha_hora_inicio;
    public $fecha_hora_fin;
    public $contar;
    public $contarProgreso;

    //Construcción del objeto
    public function __construct($args = [])
    {

        $this->id_progress = $args['id_progress'] ?? '';
        $this->id_particip = $args['id_particip'] ?? '';
        $this->id_content = $args['id_content'] ?? '';
        $this->estado_progress = $args['estado_progress'] ?? '';
        $this->fecha_hora_inicio = $args['fecha_hora_inicio'] ?? '';
        $this->fecha_hora_fin = $args['fecha_hora_fin'] ?? '';
        $this->contar = $args['contar'] ?? '';
        $this->contarProgreso = $args['contarProgreso'] ?? '';
    }

    //R -> Contar Progreso
    public static function contarProgreso($id_particip, $id_content)
    {
        $qry = "SELECT COUNT(*) AS contar FROM progreso WHERE id_particip = '$id_particip' AND id_content = '$id_content'";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //R -> Listar CONTENIDO Finalizado
    public static function listarContenido($id_content, $id_particip)
    {
        $qry = "SELECT * FROM progreso WHERE id_content = '$id_content' AND id_particip = '$id_particip'";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //R -> Contar los CONTENIDOS iniciados de un Curso
    public static function listarProgresosIniciados($id_curso, $id_particip)
    {
        $qry = "SELECT COUNT(*) AS contarProgreso FROM contenidos
        INNER JOIN progreso ON progreso.id_content = contenidos.id_content
        WHERE id_curso = '$id_curso' AND id_particip = '$id_particip' AND fecha_hora_inicio IS NOT NULL and fecha_hora_fin IS NULL AND estado_content_activ = 'A'";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //R -> Contar los CONTENIDOS finalizados de un Curso
    public static function listarProgresosFinalizados($id_curso, $id_particip)
    {
        $qry = "SELECT COUNT(*) AS contarProgreso FROM contenidos
        INNER JOIN progreso ON progreso.id_content = contenidos.id_content
        WHERE id_curso = '$id_curso' AND id_particip = '$id_particip' AND fecha_hora_fin IS NOT NULL AND estado_content_activ = 'A'";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //U -> Eliminar PROGRESO para reiniciar Curso:
    public function elimProgreso($id_curso, $id_particip)
    {
        $qry = "DELETE progreso
        FROM progreso 
        INNER JOIN contenidos ON progreso.id_content = contenidos.id_content
        WHERE id_curso='$id_curso' AND id_particip='$id_particip'";
        self::$db->query($qry);
    }

    //U -> Eliminar PROGRESO de participante 0 (administrador de la plataforma):
    public function elimProgresoParticip0()
    {
        $qry = "DELETE FROM progreso WHERE id_particip='0'";
        self::$db->query($qry);
    }

    //Identificar y unir los atributos de la BD
    public function atributos()
    {
        $atributos = [];
        foreach (self::$columnaDB as $columna) {
            if ($columna === 'id_progress') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    //Traer los datos de la BD
    public static function consultarSQL($qry)
    {
        //Consultar la base de datos
        $result = self::$db->query($qry);

        //Iterar la base de datos
        $array = [];
        while ($registro = $result->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }
        //Liberar la memoria
        $result->free();

        //Retornar los resultados
        return $array;
    }

    //Creación del objeto
    protected static function crearObjeto($registro)
    {
        $objeto = new self;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    //Sanitizar los datos
    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }
}

class ASIGNACIONES extends Master
{

    //Arreglo pamodo_fact y U
    protected static $columnaDB = [
        'id_particip',
        'id_curso',
        'fecha_asign',
        'estado_aprob',
        'intentos',
        'nota',
        'fecha_fin',
        'apellidos_particip',
        'nombre_particip',
        'nom_doc',
        'num_doc',
        'cargo_particip'
    ];

    //Declarando las variables del objeto
    public $id_asign;
    public $id_particip;
    public $id_curso;
    public $fecha_asign;
    public $estado_aprob;
    public $intentos;
    public $nota;
    public $titulo_curso;
    public $fecha_fin;
    public $apellidos_particip;
    public $nombre_particip;
    public $nom_doc;
    public $num_doc;
    public $cargo_particip;

    //Construcción del objeto
    public function __construct($args = [])
    {

        $this->id_asign = $args['id_asign'] ?? '';
        $this->id_particip = $args['id_particip'] ?? '';
        $this->id_curso = $args['id_curso'] ?? '';
        $this->fecha_asign = $args['fecha_asign'] ?? '';
        $this->estado_aprob = $args['estado_aprob'] ?? '';
        $this->intentos = $args['intentos'] ?? '';
        $this->nota = $args['nota'] ?? '';
        $this->titulo_curso = $args['titulo_curso'] ?? '';
        $this->fecha_fin = $args['fecha_fin'] ?? '';
        $this->apellidos_particip = $args['apellidos_particip'] ?? '';
        $this->nombre_particip = $args['nombre_particip'] ?? '';
        $this->nom_doc = $args['nom_doc'] ?? '';
        $this->num_doc = $args['num_doc'] ?? '';
        $this->cargo_particip = $args['cargo_particip'] ?? '';
    }

    //C -> Guardar los datos
    public function crear($id_particip, $id_curso, $fecha_asign)
    {
        $qry = "INSERT INTO asignaciones (id_particip, id_curso, fecha_asign, estado_aprob, intentos, nota, fecha_fin) 
                VALUES ($id_particip, $id_curso, '$fecha_asign', 'C', '0', NULL, NULL)";
        self::$db->query($qry);
    }

    //R -> Listar CURSOS ASIGNADOS
    public static function listarCursosAsignados($id_particip)
    {
        $qry = "SELECT * FROM asignaciones 
        INNER JOIN cursos ON cursos.id_curso = asignaciones.id_curso
        WHERE id_particip = $id_particip";
        $result = self::consultarSQL($qry);

        return $result;
    }

    //R -> Listar CURSOS ASIGNACION por ID
    public static function listarAsignacionId($id_asign)
    {
        $qry = "SELECT * FROM asignaciones 
        INNER JOIN cursos ON cursos.id_curso = asignaciones.id_curso
        WHERE id_asign = $id_asign";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //R -> Listar Asignación por Participante y Curso
    public static function listarAsignacion($id_particip, $id_curso)
    {
        $qry = "SELECT * FROM asignaciones WHERE id_particip = $id_particip AND id_curso = '$id_curso'";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //R -> Listar Asignación por Curso
    public static function listarAsignacionCurso($id_curso)
    {
        $qry = "SELECT * FROM asignaciones 
        INNER JOIN participantes ON participantes.id_particip = asignaciones.id_particip
        INNER JOIN doi ON doi.id_doc = participantes.tipo_doc
        WHERE id_curso = '$id_curso'";
        $result = self::consultarSQL($qry);

        return $result;
    }

    //U -> Aprobar Curso asignado:
    public function aprobarCursoAsig($id_particip, $id_curso, $fecha_fin)
    {
        $qry = "UPDATE asignaciones SET estado_aprob='A', fecha_fin = '$fecha_fin' WHERE id_particip='$id_particip' AND id_curso='$id_curso'";
        self::$db->query($qry);
    }

    //U -> Reprobar Curso asignado:
    public function reprobarCursoAsig($id_particip, $id_curso)
    {
        $qry = "UPDATE asignaciones SET estado_aprob='R' WHERE id_particip='$id_particip' AND id_curso='$id_curso'";
        self::$db->query($qry);
    }

    //U -> Reprobar Curso asignado:
    public function reempezarCursoAsig($id_particip, $id_curso)
    {
        $qry = "UPDATE asignaciones SET estado_aprob='C' WHERE id_particip='$id_particip' AND id_curso='$id_curso'";
        self::$db->query($qry);
    }

    //U -> Actualizar Intentos:
    public function intentosCursoAsig($intentos, $id_asign)
    {
        $qry = "UPDATE asignaciones SET intentos='$intentos' WHERE id_asign='$id_asign'";
        self::$db->query($qry);
    }

    //U -> Asignar Nota:
    public function nota($nota, $id_curso, $id_particip)
    {
        $qry = "UPDATE asignaciones SET nota='$nota' WHERE id_curso='$id_curso' AND id_particip='$id_particip'";
        self::$db->query($qry);
    }

    //U -> Eliminar ASIGNACION:
    public function elimAsign($id_asign)
    {
        $qry = "DELETE FROM asignaciones WHERE id_asign='$id_asign'";
        self::$db->query($qry);
    }

    //U -> Reiniciar ASIGNACION:
    public function reiniciarAsign($id_asign)
    {
        $qry = "UPDATE asignaciones SET estado_aprob = 'I', intentos = NULL, nota = NULL, fecha_fin = NULL WHERE id_asign='$id_asign'";
        self::$db->query($qry);
    }

    //Identificar y unir los atributos de la BD
    public function atributos()
    {
        $atributos = [];
        foreach (self::$columnaDB as $columna) {
            if ($columna === 'id_doc') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    //Traer los datos de la BD
    public static function consultarSQL($qry)
    {
        //Consultar la base de datos
        $result = self::$db->query($qry);

        //Iterar la base de datos
        $array = [];
        while ($registro = $result->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }
        //Liberar la memoria
        $result->free();

        //Retornar los resultados
        return $array;
    }

    //Creación del objeto
    protected static function crearObjeto($registro)
    {
        $objeto = new self;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    //Sanitizar los datos
    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }
}

class EXAMEN_PREGUNTAS extends Master
{

    //Arreglo pamodo_fact y U
    protected static $columnaDB = [
        'id_curso',
        'texto_pregunta'
    ];

    //Declarando las variables del objeto
    public $id_pregunta;
    public $id_curso;
    public $texto_pregunta;

    //Construcción del objeto
    public function __construct($args = [])
    {

        $this->id_pregunta = $args['id_pregunta'] ?? '';
        $this->id_curso = $args['id_curso'] ?? '';
        $this->texto_pregunta = $args['texto_pregunta'] ?? '';
    }

    //C -> Guardar los datos
    public function crear($id_curso, $texto_pregunta)
    {
        $qry = "INSERT INTO preguntas (id_curso, texto_pregunta) VALUES ('$id_curso', '$texto_pregunta')";
        self::$db->query($qry);

        // Importante: asignar el ID generado al objeto
        $this->id_pregunta = self::$db->insert_id;
    }

    //R -> Listar EXAMEN por CURSO
    public static function listarExamenCurso($id_curso)
    {
        $qry = "SELECT * FROM preguntas WHERE id_curso = $id_curso";
        $result = self::consultarSQL($qry);

        return $result;
    }

    //R -> Listar PREGUNTA por OD
    public static function listarPreguntaId($id_pregunta)
    {
        $qry = "SELECT * FROM preguntas WHERE id_pregunta = $id_pregunta";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //U -> Actualizar datos del CONTENIDO:
    public function editPregunta(
        $id_pregunta,
        $texto_pregunta
    ) {
        $qry = "UPDATE preguntas SET texto_pregunta='$texto_pregunta' WHERE id_pregunta='$id_pregunta'";
        self::$db->query($qry);
    }

    //U -> Eliminar PREGUNTA:
    public function elimPregunta($id_pregunta)
    {
        $qry = "DELETE FROM preguntas WHERE id_pregunta='$id_pregunta'";
        self::$db->query($qry);
    }

    public function eliminarExamen($id_curso)
    {
        // Eliminar respuestas primero
        $qry1 = "DELETE respuestas
                 FROM respuestas 
                 INNER JOIN preguntas ON respuestas.id_pregunta = preguntas.id_pregunta
                 WHERE preguntas.id_curso = '$id_curso'";
        self::$db->query($qry1);

        // Luego eliminar preguntas
        $qry2 = "DELETE FROM preguntas WHERE id_curso = $id_curso";
        self::$db->query($qry2);
    }

    //Identificar y unir los atributos de la BD
    public function atributos()
    {
        $atributos = [];
        foreach (self::$columnaDB as $columna) {
            if ($columna === 'id_pregunta') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    //Traer los datos de la BD
    public static function consultarSQL($qry)
    {
        //Consultar la base de datos
        $result = self::$db->query($qry);

        //Iterar la base de datos
        $array = [];
        while ($registro = $result->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }
        //Liberar la memoria
        $result->free();

        //Retornar los resultados
        return $array;
    }

    //Creación del objeto
    protected static function crearObjeto($registro)
    {
        $objeto = new self;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    //Sanitizar los datos
    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }
}

class EXAMEN_RESPUESTAS extends Master
{

    //Arreglo pamodo_fact y U
    protected static $columnaDB = [
        'id_pregunta',
        'texto_respuesta',
        'es_correcta'
    ];

    //Declarando las variables del objeto
    public $id_respuesta;
    public $id_pregunta;
    public $texto_respuesta;
    public $es_correcta;

    //Construcción del objeto
    public function __construct($args = [])
    {

        $this->id_respuesta = $args['id_respuesta'] ?? '';
        $this->id_pregunta = $args['id_pregunta'] ?? '';
        $this->texto_respuesta = $args['texto_respuesta'] ?? '';
        $this->es_correcta = $args['es_correcta'] ?? '';
    }

    //C -> Guardar los datos
    public function crear($id_pregunta, $texto_respuesta, $es_correcta)
    {
        $qry = "INSERT INTO respuestas (id_pregunta, texto_respuesta, es_correcta)
        VALUES ('$id_pregunta', '$texto_respuesta', '$es_correcta')";
        self::$db->query($qry);
    }

    //R -> Listar RESPUESTAS por PREGUNTAS
    public static function listarRespuestasPregunta($id_pregunta)
    {
        $qry = "SELECT * FROM respuestas WHERE id_pregunta = $id_pregunta";
        $result = self::consultarSQL($qry);

        return $result;
    }

    //U -> Actualizar datos del CONTENIDO:
    public function editRespuesta(
        $id_respuesta,
        $texto_respuesta
    ) {
        $qry = "UPDATE respuestas SET texto_respuesta='$texto_respuesta' WHERE id_respuesta='$id_respuesta'";
        self::$db->query($qry);
    }

    //U -> Eliminar RESPUESTAS:
    public function elimRespuestas($id_pregunta)
    {
        $qry = "DELETE FROM respuestas WHERE id_pregunta='$id_pregunta'";
        self::$db->query($qry);
    }

    //Identificar y unir los atributos de la BD
    public function atributos()
    {
        $atributos = [];
        foreach (self::$columnaDB as $columna) {
            if ($columna === 'id_pregunta') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    //Traer los datos de la BD
    public static function consultarSQL($qry)
    {
        //Consultar la base de datos
        $result = self::$db->query($qry);

        //Iterar la base de datos
        $array = [];
        while ($registro = $result->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }
        //Liberar la memoria
        $result->free();

        //Retornar los resultados
        return $array;
    }

    //Creación del objeto
    protected static function crearObjeto($registro)
    {
        $objeto = new self;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    //Sanitizar los datos
    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }
}

class CERTIFICADOS extends Master
{

    //Arreglo pamodo_fact y U
    protected static $columnaDB = [
        'id_particip',
        'id_curso',
        'cod_cert',
        'fecha_emision'
    ];

    //Declarando las variables del objeto
    public $id_cert;
    public $id_particip;
    public $id_curso;
    public $cod_cert;
    public $fecha_emision;

    //Construcción del objeto
    public function __construct($args = [])
    {

        $this->id_cert = $args['id_cert'] ?? '';
        $this->id_particip = $args['id_particip'] ?? '';
        $this->id_curso = $args['id_curso'] ?? '';
        $this->cod_cert = $args['cod_cert'] ?? '';
        $this->fecha_emision = $args['fecha_emision'] ?? '';
    }

    //C -> Guardar los datos
    public function crear($id_particip, $id_curso, $cod_cert, $fecha_emision)
    {
        $qry = "INSERT INTO certificados (id_particip, id_curso, cod_cert, fecha_emision)
        VALUES ('$id_particip', '$id_curso', '$cod_cert', '$fecha_emision')";
        self::$db->query($qry);
    }

    //R -> Listar CERTIFICADOS
    public static function listarCertificados()
    {
        $qry = "SELECT * FROM certificados ORDER BY id_cert DESC";
        $result = self::consultarSQL($qry);

        return $result;
    }

    //R -> Listar último CERTIFICADO:
    public static function listarUltimoCertificado()
    {
        $qry = "SELECT * FROM certificados ORDER BY id_cert DESC LIMIT 1";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //R -> Listar CERTIFICADOS por ID
    public static function listarCertificadoId($id_cert)
    {
        $qry = "SELECT * FROM certificados WHERE id_cert = '$id_cert'";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }


    //R -> Listar CERTIFICADOS por participate y curso
    public static function listarCertificadoPartCurso($id_particip, $id_curso)
    {
        $qry = "SELECT * FROM certificados WHERE id_particip = '$id_particip' AND id_curso = '$id_curso'";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //Identificar y unir los atributos de la BD
    public function atributos()
    {
        $atributos = [];
        foreach (self::$columnaDB as $columna) {
            if ($columna === 'id_certific') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    //Traer los datos de la BD
    public static function consultarSQL($qry)
    {
        //Consultar la base de datos
        $result = self::$db->query($qry);

        //Iterar la base de datos
        $array = [];
        while ($registro = $result->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }
        //Liberar la memoria
        $result->free();

        //Retornar los resultados
        return $array;
    }

    //Creación del objeto
    protected static function crearObjeto($registro)
    {
        $objeto = new self;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    //Sanitizar los datos
    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }
}

class SESIONES extends Master
{

    //Arreglo para C y U
    protected static $columnaDB = ['id_orden', 'identificador', 'id_user', 'estado_sesion', 'sesion'];

    //Declarando las variables del objeto
    public $id_sesion;
    public $id_orden;
    public $identificador;
    public $id_user;
    public $estado_sesion;
    public $sesion;
    //Construcción del objeto
    public function __construct($args = [])
    {

        $this->id_sesion = $args['id_sesion'] ?? '';
        $this->id_orden = $args['id_orden'] ?? '';
        $this->identificador = $args['identificador'] ?? '';
        $this->id_user = $args['id_user'] ?? '';
        $this->estado_sesion = $args['estado_sesion'] ?? '';
        $this->sesion = $args['sesion'] ?? '';
    }

    //C -> Crear Sesion
    public function crearSesion($id_user)
    {

        $qry = "INSERT INTO sesiones (id_sesion, id_orden, identificador, id_user, estado_sesion, sesion) VALUES
        ('', 1, 1, '$id_user', 1, 'Módulo de Administración'),
        ('', 2, 2, '$id_user', 1, 'Crear, editar o eliminar cursos y contenidos'),
        ('', 3, 3, '$id_user', 1, 'Crear, editar o eliminar examen'),
        ('', 4, 4, '$id_user', 1, 'Crear, editar o eliminar participantes'),
        ('', 5, 5, '$id_user', 1, 'Asignar/Desasignar cursos a participantes')";
        self::$db->query($qry);
    }

    //R -> Listar Sesiones
    public static function listarSesiones()
    {
        $qry = "SELECT * FROM sesiones
        ORDER BY id_orden ASC";
        $result = self::consultarSQL($qry);

        return $result;
    }

    //R -> Listar Sesiones
    public static function listarSesionesUsuario($usuario)
    {
        $qry = "SELECT * FROM sesiones
        WHERE id_user = '$usuario'
        ORDER BY id_orden ASC";
        $result = self::consultarSQL($qry);

        return $result;
    }

    //R -> Listar Sesiones por Usuario e identificador
    public static function listarSesionesPorIdentificacorUsuario($identificador, $usuario)
    {
        $qry = "SELECT * FROM sesiones
        WHERE identificador = '$identificador'
        AND id_user = '$usuario'";
        $result = self::consultarSQL($qry);

        return array_shift($result);
    }

    //U -> Prender Sesión:
    public function prenderSesion($identificador, $id_user)
    {
        $qry = "UPDATE sesiones SET estado_sesion='1'
         WHERE identificador='$identificador'
         AND id_user = '$id_user'";
        self::$db->query($qry);
    }

    //U -> Apagar Sesión:
    public function apagarSesiones($id_user)
    {
        $qry = "UPDATE sesiones SET estado_sesion='0'
        WHERE id_user = '$id_user'";
        self::$db->query($qry);
    }


    //Identificar y unir los atributos de la BD
    public function atributos()
    {
        $atributos = [];
        foreach (self::$columnaDB as $columna) {
            if ($columna === 'id_sesion') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    //Traer los datos de la BD
    public static function consultarSQL($qry)
    {
        //Consultar la base de datos
        $result = self::$db->query($qry);

        //Iterar la base de datos
        $array = [];
        while ($registro = $result->fetch_assoc()) {
            $array[] = self::crearObjeto($registro);
        }
        //Liberar la memoria
        $result->free();

        //Retornar los resultados
        return $array;
    }

    //Creación del objeto
    protected static function crearObjeto($registro)
    {
        $objeto = new self;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    //Sanitizar los datos
    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }
}
