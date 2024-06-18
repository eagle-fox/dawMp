package models

import (
	"gorm.io/gorm"
	"time"
)

type IotDevice struct {
	gorm.Model
	Token         string `gorm:"type:char(36);not null;unique"`
	Name          string `gorm:"type:varchar(128);default:'IoT'"`
	Especie       string `gorm:"type:varchar(128);default:'Mascota'"`
	Cumpleanos    *time.Time
	Icon          string  `gorm:"type:varchar(256);not null"`
	User          uint    `gorm:"not null"`
	LastLatitude  float64 `gorm:"default:null"`
	LastLongitude float64 `gorm:"default:null"`
}

func (IotDevice) TableName() string {
	return "iot_device"
}
