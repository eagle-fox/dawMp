package middlewares

import (
	"crypto/sha256"
	"encoding/base64"
	"encoding/hex"
	"log"
	"net/http"
	"strings"

	"back-go/models"
	"github.com/gin-gonic/gin"
)

func AuthMiddleware() gin.HandlerFunc {
	return func(c *gin.Context) {
		authHeader := c.GetHeader("Authorization")

		if authHeader == "" {
			c.AbortWithStatusJSON(http.StatusUnauthorized, gin.H{"error": "Authorization header missing"})
			return
		}

		// Manejar autenticación Basic
		if strings.HasPrefix(authHeader, "Basic ") {
			payload, err := base64.StdEncoding.DecodeString(authHeader[6:])
			if err != nil {
				c.AbortWithStatusJSON(http.StatusBadRequest, gin.H{"error": "Invalid base64"})
				return
			}
			pair := strings.SplitN(string(payload), ":", 2)

			if len(pair) != 2 {
				c.AbortWithStatusJSON(http.StatusBadRequest, gin.H{"error": "Invalid basic auth format"})
				return
			}

			username := pair[0]
			password := pair[1]

			// Hashear la contraseña proporcionada
			hash := sha256.Sum256([]byte(password))

			hashedPassword := hex.EncodeToString(hash[:])

			user, err := getUserByUsernameAndPassword(username, hashedPassword)
			if err != nil {
				c.AbortWithStatusJSON(http.StatusUnauthorized, gin.H{"error": "Invalid username or password"})
				return
			}

			c.Set("user", user)
			c.Next()
			return
		}

		// Manejar autenticación Bearer
		if strings.HasPrefix(authHeader, "Bearer ") {
			token := authHeader[7:]

			user, err := getUserByBearerToken(token)
			if err != nil {
				c.AbortWithStatusJSON(http.StatusUnauthorized, gin.H{"error": "Invalid token"})
				return
			}

			c.Set("user", user)
			c.Next()
			return
		}

		c.AbortWithStatusJSON(http.StatusBadRequest, gin.H{"error": "Unsupported authentication method"})
	}
}

func getUserByUsernameAndPassword(username, password string) (*models.User, error) {
	var user models.User
	log.Default().Println(username, password)
	// if in User table there is a email like and a password like
	if err := models.DB.Where("email = ? AND password = ?",
		username, password).First(&user).Error; err != nil {
		return nil, err
	}
	return &user, nil
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
