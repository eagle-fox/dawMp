/**
 * Class representing an IPv4 address.
 */
class IPv4 {
    /**
     * Create an IPv4 instance.
     * @param {IPv4|string|number} ipv4 - The IPv4 address.
     */
    constructor(ipv4) {
        if (ipv4 instanceof IPv4) {
            this.ipv4 = ipv4.ipv4;
            return;
        }

        if (typeof ipv4 === 'string' && this.isValidIPv4(ipv4)) {
            this.ipv4 = this.inet_aton(ipv4);
            return;
        }

        if (typeof ipv4 === 'number') {
            this.ipv4 = this.inet_ntoa(ipv4);
            return;
        }

        throw new Error("Invalid argument type for IPv4 address");
    }

    /**
     * Check if the input is a valid IPv4 address.
     * @param {string} ipv4 - The IPv4 address.
     * @return {boolean} True if the input is a valid IPv4 address, false otherwise.
     */
    isValidIPv4(ipv4) {
        const ipv4Regex = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
        return ipv4Regex.test(ipv4);
    }

    /**
     * Convert an IPv4 address from string to integer.
     * @param {string} ipv4 - The IPv4 address.
     * @return {number} The integer representation of the IPv4 address.
     */
    inet_aton(ipv4) {
        return ipv4.split('.').reduce((ipInt, octet) => (ipInt<<8) + parseInt(octet, 10), 0) >>> 0;
    }

    /**
     * Convert an IPv4 address from integer to string.
     * @param {number} ipInt - The integer representation of the IPv4 address.
     * @return {string} The string representation of the IPv4 address.
     */
    inet_ntoa(ipInt) {
        return [(ipInt>>>24) & 0xFF, (ipInt>>>16) & 0xFF, (ipInt>>>8) & 0xFF, ipInt & 0xFF].join('.');
    }

    /**
     * Get the string representation of the IPv4 address.
     * @return {string} The string representation of the IPv4 address.
     */
    toString() {
        return this.inet_ntoa(this.ipv4);
    }

    /**
     * Get the JSON representation of the IPv4 address.
     * @return {string} The JSON representation of the IPv4 address.
     */
    toJSON() {
        return this.toString();
    }

    /**
     * Compare this IPv4 instance with another.
     * @param {IPv4} other - The other IPv4 instance.
     * @return {boolean} True if the two instances represent the same IPv4 address, false otherwise.
     */
    equals(other) {
        if (!(other instanceof IPv4)) {
            throw new Error("Argument must be an instance of IPv4");
        }
        return this.inet_aton(this.ipv4) === this.inet_aton(other.ipv4);
    }
}

export default IPv4;