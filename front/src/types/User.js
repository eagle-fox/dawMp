import Client from './Client.js'

/**
 * @class User - Class to represent a User
 */
class User {
    /**
     * @constructor
     * @param {Object} data - The user data.
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
    constructor(data) {
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
    }

    toString() {
        return `${this.nombre} ${this.apellido_primero} ${this.apellido_segundo}`
    }

}

export default User