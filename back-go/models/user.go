package models

import (
	"gorm.io/gorm"
)

type User struct {
	gorm.Model
	Nombre          string `gorm:"not null"`
	NombreSegundo   string // No se especifica "not null" porque es opcional
	ApellidoPrimero string `gorm:"not null"`
	ApellidoSegundo string `gorm:"not null"`
	Email           string `gorm:"not null;unique"`
	Password        string `gorm:"not null"`
	Rol             string `gorm:"type:enum('ADMIN', 'USER');default:'USER'"`
}