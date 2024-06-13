package types

import (
	"crypto/sha256"
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

func (p *Password) GetHashedPassword() string {
	return p.hashedPassword
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

func (p *Password) GetPassword() string {
	if p.debug {
		return p.hashedPassword
	}
	return "Unauthorized access to hashed password. Debug mode is off."
}

func (p *Password) String() string {
	return p.hashedPassword
}
