package types

import (
	"crypto/rand"
	"fmt"
	"io"
)

type UUID [16]byte

func NewUUID() (UUID, error) {
	var uuid UUID
	_, err := io.ReadFull(rand.Reader, uuid[:])
	if err != nil {
		return UUID{}, err
	}

	// variant bits; see section 4.1.1
	uuid[8] = uuid[8]&^0xc0 | 0x80

	// version 4 (pseudo-random); see section 4.1.3
	uuid[6] = uuid[6]&^0xf0 | 0x40

	return uuid, nil
}

func (u UUID) String() string {
	return fmt.Sprintf("%x-%x-%x-%x-%x", u[0:4], u[4:6], u[6:8], u[8:10], u[10:])
}
