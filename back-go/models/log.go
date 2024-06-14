package models

import (
	"gorm.io/gorm"
)

type Log struct {
	gorm.Model
	UserID   int    `gorm:"not null"`
	ClientID int    `gorm:"not null"`
	Message  string `gorm:"not null"`
}