import UUID from '@/types/UUID.js'
import IPv4 from '@/types/IPv4.js'

/**
 * @class Client - Class to represent a Client
 */
class Client {
  /**
   * @constructor
   * @param {Object} data - The client data.
   * @param {number} data.id - The client's ID.
   * @param {IPv4} data.ipv4 - The client's IPv4.js address.
   * @param {UUID} data.token - The client's token.
   * @param {boolean} data.locked - Whether the client is locked.
   * @param {Date} data.created_at - The date the client was created.
   * @param {Date} data.updated_at - The date the client was last updated.
   */
  constructor(data) {
    this.id = Number(data.id)
    this.ipv4 = new IPv4(data.ipv4)
    this.token = new UUID(data.token)
    this.locked = Boolean(data.locked)
    this.created_at = new Date(data.created_at)
    this.updated_at = new Date(data.updated_at)
  }

  toJSON() {
    return {
      id: this.id,
      ipv4: this.ipv4.toJSON(),
      token: this.token.toJSON(),
      locked: this.locked,
      created_at: this.created_at,
      updated_at: this.updated_at,
    }
  }
}

export default Client