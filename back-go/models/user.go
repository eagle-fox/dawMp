package models

import (
	"gorm.io/gorm"
)

var db *gorm.DB

type User struct {
	gorm.Model
	Nombre          string `gorm:"not null"`
	NombreSegundo   string
	ApellidoPrimero string `gorm:"not null"`
	ApellidoSegundo string `gorm:"not null"`
	Email           string `gorm:"not null;unique"`
	Password        string `gorm:"not null"`
	Rol             string `gorm:"type:enum('ADMIN', 'USER');default:'USER'"`
}

func (User) TableName() string {
	return "user"
}

// GetUserByID busca un usuario en la base de datos utilizando el ID proporcionado
func GetUserByID(id int) (*User, error) {
	var user User
	if err := db.Where("id = ?", id).First(&user).Error; err != nil {
		return nil, err
	}
	return &user, nil
}

// UpdateUserByID actualiza un usuario en la base de datos utilizando el ID proporcionado
func UpdateUserByID(id int, user *User) error {
	existingUser := &User{}
	if err := db.First(existingUser, id).Error; err != nil {
		return err
	}

	// Actualizar los campos del usuario existente
	existingUser.Nombre = user.Nombre
	existingUser.NombreSegundo = user.NombreSegundo
	existingUser.ApellidoPrimero = user.ApellidoPrimero
	existingUser.ApellidoSegundo = user.ApellidoSegundo
	existingUser.Email = user.Email
	existingUser.Password = user.Password
	existingUser.Rol = user.Rol

	// Guardar el usuario actualizado en la base de datos
	if err := db.Save(existingUser).Error; err != nil {
		return err
	}

	// Copiar el usuario actualizado al usuario proporcionado
	*user = *existingUser

	return nil
}

// DeleteUserByID elimina un usuario en la base de datos utilizando el ID proporcionado
func DeleteUserByID(id int) error {
	var user User
	if err := db.Where("id = ?", id).Delete(&user).Error; err != nil {
		return err
	}
	return nil
}
