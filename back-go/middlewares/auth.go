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

// AuthMiddleware returns a middleware function for user authentication.
// It handles both Basic and Bearer authentication methods.
//
// Basic authentication: Expects the header "Authorization: Basic base64(username:password)".
// Bearer authentication: Expects the header "Authorization: Bearer token".
func AuthMiddleware() gin.HandlerFunc {
	return func(c *gin.Context) {
		c.Writer.Header().Set("Access-Control-Allow-Origin", "*")
		c.Writer.Header().Set("Access-Control-Allow-Headers", "*")
		c.Writer.Header().Set("Access-Control-Allow-Methods", "*")

		authHeader := c.GetHeader("Authorization")

		if authHeader == "" {
			c.AbortWithStatusJSON(http.StatusUnauthorized, gin.H{"error": "Authorization header missing"})
			return
		}

		// Handle Basic authentication
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

			email := pair[0]
			password := pair[1]

			// Hash the provided password
			hash := sha256.Sum256([]byte(password))
			hashedPassword := hex.EncodeToString(hash[:])

			user, err := getUserByUsernameAndPassword(email, hashedPassword)
			if err != nil {
				c.AbortWithStatusJSON(http.StatusUnauthorized, gin.H{"error": "Invalid username or password"})
				return
			}

			c.Set("user", user)
			c.Next()
			return
		}

		// Handle Bearer authentication
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

// getUserByUsernameAndPassword retrieves a user from the database by their username and hashed password.
// It returns the user if found, or an error if the user does not exist or another error occurs.
//
// Parameters:
//   - username: The username provided in the Basic authentication header.
//   - password: The hashed password of the user.
//
// Returns:
//   - *models.User: The authenticated user.
//   - error: An error if the user is not found or another error occurs.
func getUserByUsernameAndPassword(email, password string) (*models.User, error) {
	var user models.User
	log.Default().Println(email, password)

	if err := models.DB.Where("email = ? AND password = ?", email, password).First(&user).Error; err != nil {
		return nil, err
		log.Println("Error al autenticar con el usuario: ", email)
	}
	log.Println("Autenticado con éxito con el usuario: ", user.Email)
	return &user, nil
}

// getUserByBearerToken retrieves a user from the database by their Bearer token.
// It returns the user if found, or an error if the user does not exist or another error occurs.
//
// Parameters:
//   - token: The Bearer token provided in the Authorization header.
//
// Returns:
//   - *models.User: The authenticated user.
//   - error: An error if the user is not found or another error occurs.
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
