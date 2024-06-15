package models

import "gorm.io/gorm"

type Client struct {
	gorm.Model
	IPv4   string `gorm:"type:char(15);not null"`
	Token  string `gorm:"type:char(36);not null"`
	Locked bool   `gorm:"not null;default:false"`
	User   int    `gorm:"not null"`
}