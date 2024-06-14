package types

import (
	"database/sql/driver"
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

func (e *Email) String() string {
	return e.GetEmail()
}

func (e *Email) GetEmail() string {
	return e.LocalPart + "@" + e.Domain + "." + e.Extension
}

// Value implements the driver Valuer interface.
func (e Email) Value() (driver.Value, error) {
	return e.String(), nil
}

// Scan implements the Scanner interface.
func (e *Email) Scan(value interface{}) error {
	if value == nil {
		return errors.New("email cannot be null")
	}

	emailString, ok := value.(string)
	if !ok {
		return errors.New("invalid email type")
	}

	parsedEmail, err := NewEmail(emailString)
	if err != nil {
		return err
	}

	*e = *parsedEmail
	return nil
}
