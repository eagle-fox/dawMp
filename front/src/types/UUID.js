class UUID {
  constructor(uuid = null) {
    if (uuid instanceof UUID) {
      this.uuid = uuid.uuid
      return
    }

    if (uuid !== null) {
      if (!this.isValidUUID(uuid)) {
        throw new Error('Invalid UUID')
      }
      this.uuid = uuid
    } else {
      this.uuid = this.generate()
    }
  }

  generate() {
    const data = crypto.getRandomValues(new Uint8Array(16))
    data[6] = (data[6] & 0x0f) | 0x40
    data[8] = (data[8] & 0x3f) | 0x80
    const hex = [...data].map((b) => b.toString(16).padStart(2, '0'))
    console.log('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA')
    return `${hex.slice(0, 4).join('')}-${hex.slice(4, 6).join('')}-${hex.slice(6, 8).join('')}-${hex
        .slice(8, 10)
        .join('')}-${hex.slice(10, 16).join('')}`
  }

  isValidUUID(uuid) {
    return /^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i.test(
        uuid,
    )
  }

  toString() {
    return this.uuid
  }

  toJSON() {
    return this.uuid
  }
}

export default UUID