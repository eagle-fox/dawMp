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
            this.token = new UUID().uuid
        }
        if (token instanceof BearerToken) {
            this.token = token.token
            return
        }
        // Validation is inside UUID class
        token = new UUID(token).uuid
    }

    getToken() {
        return this.token
    }


}

export default BearerToken