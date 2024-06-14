package models

import (
	"gorm.io/gorm"
)

type IoTData struct {
	gorm.Model
	Device    int     `gorm:"not null"`
	Latitude  float64 `gorm:"not null"`
	Longitude float64 `gorm:"not null"`
}

// TableName sobrescribe el nombre de la tabla para IoTData
func (IoTData) TableName() string {
	return "iot_data"
}
