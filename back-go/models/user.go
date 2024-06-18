package models

import (
	"gorm.io/gorm"
)

var db *gorm.DB

type User struct {
	gorm.Model
	Nombre          string   `gorm:"type:varchar(128);not null"`
	NombreSegundo   string   `gorm:"type:varchar(128)"`
	ApellidoPrimero string   `gorm:"type:varchar(128);not null"`
	ApellidoSegundo string   `gorm:"type:varchar(128);not null"`
	Email           string   `gorm:"type:varchar(256);not null;unique;index"`
	Password        string   `gorm:"type:char(64);not null;index"`
	Rol             string   `gorm:"type:enum('ADMIN', 'USER');default:'USER'"`
	Clients         []Client `gorm:"foreignKey:UserID"`
}

func (User) TableName() string {
	return "user"
}
