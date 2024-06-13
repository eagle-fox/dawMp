package models

import (
	"gorm.io/gorm"
)

type IotDevice struct {
	gorm.Model
	User          User `gorm:"foreignKey:User"`
	LastLatitude  float64
	LastLongitude float64
	IotData       []IotData `gorm:"foreignKey:Device"`
}
