package models

import "gorm.io/gorm"

type Client struct {
	gorm.Model
	IPv4   string `gorm:"type:char(15);not null;index"`
	Token  string `gorm:"type:char(36);not null;index"`
	Locked bool   `gorm:"not null;default:false"`
	UserID uint   `gorm:"not null"`
}

func (Client) TableName() string {
	return "clients"
}
