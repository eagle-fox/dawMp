package constants

type AuthMethods int

const (
	BEARER AuthMethods = iota
	BASIC
	UNSUPPORTED
)

func (a AuthMethods) String() string {

	switch a {
	case BEARER:
		return "BEARER"
	case BASIC:
		return "BASIC"
	default:
		return "UNSUPPORTED"
	}

	// return [...]string{"BEARER", "BASIC", "UNSUPPORTED", "NONE"}[a]
}
