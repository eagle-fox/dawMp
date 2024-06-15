// Package controllers provides the handlers for the HTTP endpoints of the application.
package controllers

import (
	"back-go/models"
	"crypto/sha256"
	"encoding/base64"
	"encoding/hex"
	"github.com/gin-gonic/gin"
	"net/http"
	"strconv"
	"strings"
)

// UserControllerIndex handles the GET request to fetch all users.
// It returns a list of user records in JSON format.
// @param c *gin.Context - The context of the request.
func UserControllerIndex(c *gin.Context) {
	var users []models.User
	if err := models.DB.Find(&users).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"message": "Error getting data"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"data": users})
}

// UserControllerStore handles the POST request to create a new user record.
// It expects JSON input with user details.
// @param c *gin.Context - The context of the request.
func UserControllerStore(c *gin.Context) {
	var input struct {
		Nombre          string `json:"nombre"`
		NombreSegundo   string `json:"nombre_segundo"`
		Apellido        string `json:"apellido"`
		Email           string `json:"email"`
		Password        string `json:"password"`
		ApellidoPrimero string `json:"apellido_primero"`
		ApellidoSegundo string `json:"apellido_segundo"`
		Rol             string `json:"rol"`
	}

	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}

	hash := sha256.Sum256([]byte(input.Password))
	hashedPassword := hex.EncodeToString(hash[:])

	user := models.User{
		Nombre:          input.Nombre,
		NombreSegundo:   input.NombreSegundo,
		Email:           input.Email,
		Password:        hashedPassword,
		ApellidoPrimero: input.ApellidoPrimero,
		ApellidoSegundo: input.ApellidoSegundo,
		Rol:             input.Rol,
	}

	if err := models.DB.Create(&user).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"message": "Error creating data"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"user": user})
}

// getUserByEmailAndPassword retrieves a user by their email and password.
// @param email string - The user's email.
// @param password string - The user's password.
// @return *models.User, error - The user object and an error, if any.
func getUserByEmailAndPassword(email, password string) (*models.User, error) {
	var user models.User
	hash := sha256.Sum256([]byte(password))
	hashedPassword := hex.EncodeToString(hash[:])
	if err := models.DB.Preload("Clients").Where("email = ? AND password = ?", email, hashedPassword).First(&user).Error; err != nil {
		return nil, err
	}
	return &user, nil
}

// UserControllerLogin handles the login request using basic or bearer authentication.
// It validates the user credentials and returns the user record if successful.
// @param c *gin.Context - The context of the request.
func UserControllerLogin(c *gin.Context) {
	authHeader := c.GetHeader("Authorization")

	// Verificar si se proporciona Authorization header
	if authHeader == "" {
		c.JSON(http.StatusUnauthorized, gin.H{"error": "Authorization header missing"})
		return
	}

	// Manejar autenticación Basic
	if strings.HasPrefix(authHeader, "Basic ") {
		// Decodificar credenciales
		payload, err := base64.StdEncoding.DecodeString(authHeader[6:])
		if err != nil {
			c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid base64"})
			return
		}
		pair := strings.SplitN(string(payload), ":", 2)

		// Verificar que se proporcionen usuario y contraseña
		if len(pair) != 2 {
			c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid basic auth format"})
			return
		}

		email := pair[0]
		password := pair[1]

		// Validar contra la base de datos (ejemplo)
		user, err := getUserByEmailAndPassword(email, password)
		if err != nil {
			c.JSON(http.StatusUnauthorized, gin.H{"error": "Invalid email or password"})
			return
		}

		c.JSON(http.StatusOK, gin.H{"user": user})
		return
	}

	// Manejar autenticación Bearer
	if strings.HasPrefix(authHeader, "Bearer ") {
		token := authHeader[7:]

		// Validar contra la base de datos
		user, err := getUserByBearerToken(token)
		if err != nil {
			c.JSON(http.StatusUnauthorized, gin.H{"error": "Invalid token"})
			return
		}

		c.JSON(http.StatusOK, gin.H{"user": user})
		return
	}

	// Si no es ni Basic ni Bearer
	c.JSON(http.StatusBadRequest, gin.H{"error": "Unsupported authentication method"})
}

// getUserByBearerToken retrieves a user by their bearer token.
// @param token string - The user's bearer token.
// @return *models.User, error - The user object and an error, if any.
func getUserByBearerToken(token string) (*models.User, error) {
	var client models.Client
	if err := models.DB.Where("token = ?", token).First(&client).Error; err != nil {
		return nil, err
	}
	var user models.User
	if err := models.DB.Preload("Clients").Where("id = ?", client.User).First(&user).Error; err != nil {
		return nil, err
	}
	return &user, nil
}

// UserControllerShow handles the GET request to fetch a specific user by ID.
// It returns the user record in JSON format.
// @param c *gin.Context - The context of the request.
func UserControllerShow(c *gin.Context) {
	id, err := strconv.Atoi(c.Param("id"))
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid user ID"})
		return
	}
	user, err := models.GetUserByID(id)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error fetching user"})
		return
	}
	c.JSON(http.StatusOK, gin.H{"user": user})
}

// UserControllerUpdate handles the PUT request to update a specific user by ID.
// It expects JSON input to update the user record.
// @param c *gin.Context - The context of the request.
func UserControllerUpdate(c *gin.Context) {
	id, err := strconv.Atoi(c.Param("id"))
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid user ID"})
		return
	}
	var user models.User
	if err := c.ShouldBindJSON(&user); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
		return
	}
	if err := models.UpdateUserByID(id, &user); err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error updating user"})
		return
	}
	c.JSON(http.StatusOK, gin.H{"user": user})
}

// UserControllerDestroy handles the DELETE request to delete a specific user by ID.
// It returns a confirmation message in JSON format.
// @param c *gin.Context - The context of the request.
func UserControllerDestroy(c *gin.Context) {
	id, err := strconv.Atoi(c.Param("id"))
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid user ID"})
		return
	}
	if err := models.DeleteUserByID(id); err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Error deleting user"})
		return
	}
	c.JSON(http.StatusOK, gin.H{"message": "User deleted"})
}