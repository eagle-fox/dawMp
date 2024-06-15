package controllers

import (
	"back-go/models"
	"encoding/base64"
	"github.com/gin-gonic/gin"
	"net/http"
	"strconv"
	"strings"
)

func UserControllerIndex(c *gin.Context) {

	// Si el usuario es un administrador, proceder como de costumbre
	var users []models.User
	if err := models.DB.Find(&users).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"message": "Error getting data"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"data": users})
}

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

	user := models.User{
		Nombre:          input.Nombre,
		NombreSegundo:   input.NombreSegundo,
		Email:           input.Email,
		Password:        input.Password,
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

// Función para obtener usuario por email y contraseña
func getUserByEmailAndPassword(email, password string) (*models.User, error) {
	var user models.User
	if err := models.DB.Where("email = ? AND password = ?", email, password).First(&user).Error; err != nil {
		return nil, err
	}
	return &user, nil
}

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

func getUserByBearerToken(token string) (*models.User, error) {
	var client models.Client
	if err := models.DB.Where("token = ?", token).First(&client).Error; err != nil {
		return nil, err

	}
	var user models.User
	if err := models.DB.Where("id = ?", client.ID).First(&user).Error; err != nil {
		return nil, err
	}

	return &user, nil
}

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
