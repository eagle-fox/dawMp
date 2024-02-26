/**
 * @class BasicAuth - Basic Authentication using base64 encoding.
 * @property {string} username - Username
 * @property {string} password - Password
 * @property {string} encoded - Encoded username and password
 */
class BasicAuth {
    encoded = null

    /**
     * @constructor
     * @param {string} username - The username for basic authentication.
     * @param {string} password - The password for basic authentication.
     */
    constructor(username, password) {
        this.encoded = btoa(`${username}:${password}`)
    }
}

export default BasicAuth