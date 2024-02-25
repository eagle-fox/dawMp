/**
 * @class Client - Class to represent a Client
 */
class Client {
    /**
     * @constructor
     * @param {Object} data - The client data.
     * @param {number} data.id - The client's ID.
     * @param {string} data.ipv4 - The client's IPv4 address.
     * @param {string} data.token - The client's token.
     * @param {boolean} data.locked - Whether the client is locked.
     * @param {Date} data.created_at - The date the client was created.
     * @param {Date} data.updated_at - The date the client was last updated.
     */
    constructor(data) {
        this.id = Number(data.id)
        this.ipv4 = String(data.ipv4)
        this.token = String(data.token)
        this.locked = Boolean(data.locked)
        this.created_at = new Date(data.created_at)
        this.updated_at = new Date(data.updated_at)
    }
}

export default Client