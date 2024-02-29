import UUID from '@/types/UUID.js'

/**
 * @class BearerToken - Class to handle, validate or generate Bearer Token
 * @property {string} token - The bearer token.
 */
class BearerToken {
  token = null

  /**
   * @constructor
   * @param {string} token - The bearer token.
   */
  constructor(token = null) {
    if (token === null) {
      this.token = new UUID()
      return
    }
    if (token instanceof BearerToken) {
      this.token = token.token
      return
    }
    this.token = new UUID(token)
  }

  getToken() {
    return this.token
  }

  toJSON() {
    return this.token.toString()
  }

}

export default BearerToken