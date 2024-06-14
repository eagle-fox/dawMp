package middlewares

import (
	"encoding/base64"
	"net/http"
	"strings"

	"github.com/eagle-fox/dawMp/models"
	"github.com/gin-gonic/gin"
	"gorm.io/gorm"
)

// Asumiendo que tienes una variable global para tu conexión a la base de datos
var db *gorm.DB

// AuthMiddleware realiza la autenticación usando Basic Auth o Bearer token.
func AuthMiddleware() gin.HandlerFunc {
	return func(c *gin.Context) {
		authHeader := c.GetHeader("Authorization")

		// Verificar si se proporciona Authorization header
		if authHeader == "" {
			c.AbortWithStatusJSON(http.StatusUnauthorized, gin.H{"error": "Authorization header missing"})
			return
		}

		// Manejar autenticación Basic
		if strings.HasPrefix(authHeader, "Basic ") {
			// Decodificar credenciales
			payload, err := base64.StdEncoding.DecodeString(authHeader[6:])
			if err != nil {
				c.AbortWithStatusJSON(http.StatusBadRequest, gin.H{"error": "Invalid base64"})
				return
			}
			pair := strings.SplitN(string(payload), ":", 2)

			// Verificar que se proporcionen usuario y contraseña
			if len(pair) != 2 {
				c.AbortWithStatusJSON(http.StatusBadRequest, gin.H{"error": "Invalid basic auth format"})
				return
			}

			username := pair[0]
			password := pair[1]

			// Validar contra la base de datos (ejemplo)
			user, err := getUserByUsernameAndPassword(username, password)
			if err != nil {
				c.AbortWithStatusJSON(http.StatusUnauthorized, gin.H{"error": "Invalid username or password"})
				return
			}

			// Guardar el usuario en el contexto
			c.Set("user", user)
			c.Next()
			return
		}

		// Manejar autenticación Bearer
		if strings.HasPrefix(authHeader, "Bearer ") {
			token := authHeader[7:]

			// Validar contra la base de datos
			user, err := getUserByBearerToken(token)
			if err != nil {
				c.AbortWithStatusJSON(http.StatusUnauthorized, gin.H{"error": "Invalid token"})
				return
			}

			// Guardar el usuario en el contexto
			c.Set("user", user)
			c.Next()
			return
		}

		// Si no es ni Basic ni Bearer
		c.AbortWithStatusJSON(http.StatusBadRequest, gin.H{"error": "Unsupported authentication method"})
	}
}

// Función para obtener usuario por nombre de usuario y contraseña
func getUserByUsernameAndPassword(username, password string) (*models.User, error) {
	var user models.User
	if err := db.Where("username = ? AND password = ?", username, password).First(&user).Error; err != nil {
		return nil, err
	}
	return &user, nil
}

// Función para obtener usuario por token Bearer
func getUserByBearerToken(token string) (*models.User, error) {
	var client models.Client
	if err := db.Where("token = ?", token).First(&client).Error; err != nil {
		return nil, err
	}

	var user models.User
	if err := db.Where("id = ?", client.User).First(&user).Error; err != nil {
		return nil, err
	}

	return &user, nil
}
