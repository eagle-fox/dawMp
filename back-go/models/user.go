package models

import (
	"gorm.io/gorm"
)

var db *gorm.DB

type User struct {
	gorm.Model
	Nombre          string `gorm:"not null"`
	NombreSegundo   string
	ApellidoPrimero string   `gorm:"not null"`
	ApellidoSegundo string   `gorm:"not null"`
	Email           string   `gorm:"not null;unique"`
	Password        string   `gorm:"not null"`
	Rol             string   `gorm:"type:enum('ADMIN', 'USER');default:'USER'"`
	Clients         []Client `gorm:"foreignKey:UserID"`
}

func (User) TableName() string {
	return "user"
}
