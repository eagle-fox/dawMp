package models

import (
	"github.com/eagle-fox/dawMp/types"
	"gorm.io/gorm"
)

type Client struct {
	gorm.Model
	IPv4   types.IPv4
	Token  types.UUID
	Locked bool
	User   User `gorm:"foreignKey:User"`
}
