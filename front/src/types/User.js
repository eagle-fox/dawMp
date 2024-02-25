import Client from './Client.js'

/**
 * @class User - Para validar desde la BD, si quereis crear un usuario manualmente usad el patrón builder.
 */
class User {

    constructor() {
    }

    /**
     * @method withObject - Asignar los valores al usuario desde un objeto de la base de datos.
     * @param {Object | null} data - The user data, if wanted to create a new user manually use the build method.
     * @param {number} data.id - The user's ID.
     * @param {string} data.nombre - The user's first name.
     * @param {string} data.nombre_segundo - The user's second name.
     * @param {string} data.apellido_primero - The user's first surname.
     * @param {string} data.apellido_segundo - The user's second surname.
     * @param {string} data.email - The user's email.
     * @param {string} data.password - The user's password.
     * @param {string} data.rol - The user's role.
     * @param {Date} data.created_at - The date the user was created.
     * @param {Date} data.updated_at - The date the user was last updated.
     * @param {Object<Client>} data.clients - The user's clients.
     */
    withObject(data) {
        try {
            this.id = Number(data.id)
            this.nombre = String(data.nombre)
            this.nombre_segundo = String(data.nombre_segundo)
            this.apellido_primero = String(data.apellido_primero)
            this.apellido_segundo = String(data.apellido_segundo)
            this.email = String(data.email)
            this.password = String(data.password)
            this.rol = String(data.rol)
            this.created_at = new Date(data.created_at)
            this.updated_at = new Date(data.updated_at)
            this.clients = data.clients.map(client => new Client(client))
        } catch (error) {
            console.error('Error creating User, validation failed:', error.message)
            throw error
        }
    }

    /**
     * @method withNombre - Asignar el nombre al usuario
     * @param {string} nombre
     */
    withNombre(nombre) {
        this.nombre = nombre
        return this
    }

    /**
     * @method withNombreSegundo - Asignar el segundo nombre al usuario
     * @param {string} nombre_segundo
     */
    withNombreSegundo(nombre_segundo) {
        this.nombre_segundo = nombre_segundo
        return this
    }

    /**
     * @method withApellidoPrimero - Asignar el primer apellido al usuario
     * @param {string} apellido_primero
     */
    withApellidoPrimero(apellido_primero) {
        this.apellido_primero = apellido_primero
        return this
    }

    /**
     * @method withApellidoSegundo - Asignar el segundo apellido al usuario
     * @param {string} apellido_segundo
     */
    withApellidoSegundo(apellido_segundo) {
        this.apellido_segundo = apellido_segundo
        return this
    }

    /**
     * @method withEmail - Asignar el email al usuario
     * @param {string} email
     */
    withEmail(email) {
        this.email = email
        return this
    }

    /**
     * @method withPassword - Asignar el password al usuario, este password se guarda en texto plano dado que se
     * es el backend el que se encarga de encriptar el password mediante SHA256.
     * @param {string} password
     */
    withPassword(password) {
        this.password = password
        return this
    }

    /**
     * @method withRol - Asignar el rol al usuario
     * @param {string} rol
     */
    withRol(rol) {
        this.rol = rol
        return this
    }

    /**
     * @method withCreatedAt - Asignar la fecha de creación al usuario
     * @param {Date} created_at
     */
    withCreatedAt(created_at) {
        this.created_at = created_at
        return this
    }

    /**
     * @method withUpdatedAt - Asignar la fecha de actualización al usuario
     * @param {Date} updated_at
     */
    withUpdatedAt(updated_at) {
        this.updated_at = updated_at
        return this
    }

    /**
     * @method withClients - Asignar los clientes al usuario. No soportado
     * @param {Array[Client]} clients
     * @throws {Error} - Not supported
     */
    withClients(clients) {
        throw new Error('Not supported')
    }

    /**
     * @method withId - Asignar el ID al usuario - No soportado
     * @param {number} id
     * @throws {Error} - Not supported, ID is immutable and is assigned by the server
     */
    withId(id) {
        throw new Error('Not supported, ID is immutable and is assigned by the server')
    }

    /**
     * @method build - Construir un nuevo usuario
     * @returns {User} - El usuario construido
     */
    build() {
        return new User(this)
    }

    toString() {
        return `${this.nombre} ${this.apellido_primero} ${this.apellido_segundo}`
    }

}

export default User