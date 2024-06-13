package constants

type Rol int

const (
	ADMIN Rol = iota
	USER
	IOT
)

func (r Rol) String() string {
	switch r {
	case ADMIN:
		return "ADMIN"
	case USER:
		return "USER"
	case IOT:
		return "IOT"
	}
}
