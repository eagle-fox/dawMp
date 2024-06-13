package models

import (
	"github.com/eagle-fox/dawMp/constants"
	"github.com/eagle-fox/dawMp/types"
	"gorm.io/gorm"
)

type User struct {
	gorm.Model
	Nombre          string
	NombreSegundo   string
	ApellidoPrimero string
	ApellidoSegundo string
	Email           types.Email
	Password        types.Password
	Rol             constants.Rol
	Clients         []Client `gorm:"foreignKey:User"`
	Locked          bool
}
