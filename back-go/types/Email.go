package types

import (
	"errors"
	"strings"
)

type Email struct {
	LocalPart string
	Domain    string
	Extension string
}

func NewEmail(email interface{}) (*Email, error) {
	e := &Email{}
	var emailString string

	switch v := email.(type) {
	case *Email:
		*e = *v
		return e, nil
	case string:
		emailString = v
	default:
		return nil, errors.New("invalid argument type for email")
	}

	parts := strings.Split(emailString, "@")
	if len(parts) != 2 {
		return nil, errors.New("invalid email format")
	}
	e.LocalPart = parts[0]

	domainParts := strings.Split(parts[1], ".")
	if len(domainParts) != 2 {
		return nil, errors.New("invalid domain format")
	}
	e.Domain = domainParts[0]
	e.Extension = domainParts[1]

	return e, nil
}

func (e *Email) GetLocalPart() string {
	return e.LocalPart
}

func (e *Email) GetDomain() string {
	return e.Domain
}

func (e *Email) GetExtension() string {
	return e.Extension
}

func (e *Email) GetEmail() string {
	return e.LocalPart + "@" + e.Domain + "." + e.Extension
}

func (e *Email) String() string {
	return e.GetEmail()
}
