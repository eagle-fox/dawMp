package models

import (
	"github.com/eagle-fox/dawMp/types"
	"gorm.io/gorm"
)

type IotData struct {
	gorm.Model
	Device    types.UUID
	Latitude  float64
	Longitude float64
}
