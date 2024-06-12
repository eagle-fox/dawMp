/**
 * @class URL - Class to handle, validate or generate URL
 * @property {string} url - The URL.
 */
class URL {
    url = null
    /**
     * @constructor
     * @param {('http'|'https')} protocol - The protocol to use for the request. Only 'http' or 'https' are allowed.
     * @param {string} host - The host to use for the request.
     * @param {number} port - The port to use for the request.
     * @throws {Error} - If the protocol is not 'http' or 'https', or if the host or path is not a string, or if the port is not a number.
     */
    protocol
    hostname
    port

    constructor(protocol = 'http', host = 'localhost', port = 2003, path = '') {
        if (protocol !== 'http' && protocol !== 'https') {
            throw new Error('Invalid protocol. Only \'http\' or \'https\' are allowed.')
        }
        if (typeof host !== 'string') {
            throw new Error('Invalid host. Host must be a string.')
        }
        if (typeof port !== 'number') {
            throw new Error('Invalid port. Port must be a number.')
        }
        this.protocol = protocol
        this.hostname = host
        this.port = port
    }

    toString() {
        return `${this.protocol}://${this.hostname}:${this.port}/`
    }
    
}

export default URL