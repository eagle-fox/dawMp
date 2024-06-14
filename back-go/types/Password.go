package types

import (
	"crypto/sha256"
	"database/sql/driver"
	"encoding/hex"
	"errors"
	"os"
	"regexp"
)

type Password struct {
	hashedPassword string
	plainPassword  string
	debug          bool
	strongPassword bool
}

func NewPassword(password interface{}) (*Password, error) {
	p := &Password{}
	var plainPassword string

	switch v := password.(type) {
	case *Password:
		p.hashedPassword = v.hashedPassword
		if v.debug {
			p.plainPassword = v.plainPassword
		}
		return p, nil
	case string:
		plainPassword = v
	default:
		return nil, errors.New("invalid argument type for password")
	}

	p.debug = os.Getenv("GINTONIC_DEV_TOOLS") == "true"
	if p.debug {
		p.plainPassword = plainPassword
	}
	p.strongPassword = os.Getenv("STRONG_PASSWORD") == "true"
	if p.strongPassword {
		if err := p.validatePassword(plainPassword); err != nil {
			return nil, err
		}
	}
	hash := sha256.Sum256([]byte(plainPassword))
	p.hashedPassword = hex.EncodeToString(hash[:])

	return p, nil
}

func (p *Password) validatePassword(password string) error {
	if len(password) < 12 {
		return errors.New("password must be at least 12 characters long")
	}
	if match, _ := regexp.MatchString("[A-Z]", password); !match {
		return errors.New("password must contain at least one uppercase letter")
	}
	if match, _ := regexp.MatchString("[a-z]", password); !match {
		return errors.New("password must contain at least one lowercase letter")
	}
	if match, _ := regexp.MatchString("[0-9]", password); !match {
		return errors.New("password must contain at least one number")
	}
	if match, _ := regexp.MatchString("\\W", password); !match {
		return errors.New("password must contain at least one special character")
	}
	return nil
}

func (p *Password) String() string {
	return p.hashedPassword
}

// Value implements the driver Valuer interface.
func (p Password) Value() (driver.Value, error) {
	return p.hashedPassword, nil
}

// Scan implements the Scanner interface.
func (p *Password) Scan(value interface{}) error {
	if value == nil {
		return errors.New("password cannot be null")
	}

	hashedPassword, ok := value.(string)
	if !ok {
		return errors.New("invalid password type")
	}

	p.hashedPassword = hashedPassword
	return nil
}
